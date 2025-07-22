<?php

namespace App\Services;

use DateTime;
use DateInterval;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Ticket;

class ReportService
{

    private $year, $month, $id;

    public function __construct(int $year, int $month, int $id = null)
    {
        $this->year = $year;
        $this->month = $month;
        $this->id = $id;
    }

    // Tambahkan method privat ini
    private function getBaseMonthlyQuery()
    {
        return Ticket::whereYear('reporteddate', $this->year)
            ->whereMonth('reporteddate', $this->month)
            ->when(!is_null($this->id), function ($query) {
                $query->where('assignee', $this->id);
            });
    }

    // Kemudian, sederhanakan method lainnya
    public function getMonthlyTickets()
    {
        return $this->getBaseMonthlyQuery()->count();
    }

    public function getMonthlyDoneTickets()
    {
        return $this->getBaseMonthlyQuery()->where('status', Ticket::STATUS_CLOSED)->count();
    }

    public function getMonthlyPendingTickets()
    {
        return $this->getBaseMonthlyQuery()->where('status', 'Pending')->count(); // <-- Gunakan konstanta di sini jika ada
    }

    public function getOverdueTickets(String $code = null)
    {
        $assignedTickets = Ticket::with('sla:id,resolution,warning')
            ->whereYear('reporteddate', $this->year)
            ->whereMonth('reporteddate', $this->month)
            ->where('status', 'Assigned')
            ->when(!is_null($this->id), function ($assignedTickets) {
                $assignedTickets = $assignedTickets->whereYear('reporteddate', $this->year)
                    ->whereMonth('reporteddate', $this->month)
                    ->where('status', 'Assigned')
                    ->where('assignee', $this->id);
            })->get();
        $assigned_plus = 0;

        if ($code == 'red') {
            foreach ($assignedTickets as $ticket) {
                $time = Carbon::parse($ticket->reporteddate);
                $new_time = $time->addHours($ticket->sla->resolution);
                if ($new_time->lt(now())) {
                    $assigned_plus = +1;
                }
            }
        } elseif ($code == 'yellow') {
            foreach ($assignedTickets as $ticket) {
                $time = Carbon::parse($ticket->reporteddate);
                $warning_time = $time->addHours($ticket->sla->warning);
                $resolution_time = $time->addHours($ticket->sla->resolution);
                if (now()->between($warning_time, $resolution_time)) {
                    $assigned_plus = +1;
                }
            }
        }
        return $assigned_plus;
    }

    public function getAllTechnicianTickets()
    {
        $technicians = User::role('Teknisi')->get();
        $allReport = collect([]); // Inisialisasi koleksi kosong

        foreach ($technicians as $technician) {
            $report = new ReportService(now()->format('Y'), now()->format('m'), $technician->id);

            // Tambahkan data ke dalam koleksi $allReport
            // Kita tidak perlu variabel $combined di sini
            $allReport->push([
                'name'     => $technician->name,
                'assigned' => $report->getMonthlyTickets(),
                'expired'  => $report->getOverdueTickets('red'),
                'warning'  => $report->getOverdueTickets('yellow'),
                'pending'  => $report->getMonthlyPendingTickets(),
                'done'     => $report->getMonthlyDoneTickets()
            ]);
        }

        // Kembalikan koleksi $allReport yang sudah terisi (atau tetap kosong jika tidak ada teknisi)
        return $allReport;
    }
}
