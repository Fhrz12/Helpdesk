<?php

namespace App\Http\Controllers;

use App\Models\Sla;
use App\Models\Ticket;
use App\Http\Requests\StorePublicTicketRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PublicTicketController extends Controller
{
    /**
     * Menampilkan form untuk publik membuat tiket.
     */
    public function create()
    {
        // Mengambil data SLA untuk dropdown di form
        $slas = Sla::orderBy('name', 'asc')->get();

        // DAFTAR DIVISI (Bagian ini yang mungkin hilang atau salah)
        $divisions = [
            'Farming',
            'Hunter',
            'Customer Service',
            'PPIC',
            'Finance',
            'GA',
            'MAP'
            // Tambahkan nama divisi lain di sini jika perlu
        ];

        // Kirim data slas dan divisions ke view
        return view('public.create-ticket', compact('slas', 'divisions'));
    }

    /**
     * Menyimpan tiket baru dari form publik.
     */
    public function store(StorePublicTicketRequest $request)
    {
        $year = today()->year;

        $latest_ticket = Ticket::latest('id')->first();
        $new_number = 1; // Nilai default jika belum ada tiket
        if ($latest_ticket) {
            $parts = explode('/', $latest_ticket->number);
            $new_number = (int)$parts[0] + 1;
        }

        $ticket = Ticket::create([
            'number'         => $new_number . "/" . $year,
            'guest_name'     => $request->guest_name,
            'guest_divisi'   => $request->guest_divisi,
            'problemdetail'  => $request->detail,
            'problemsummary' => 'Laporan dari ' . $request->guest_name,
            'status'         => Ticket::STATUS_OPEN,
            'reporteddate'   => now(),
        ]);

        if ($ticket) {
            return redirect('/')->with(['success' => 'Tiket Anda No. ' . $ticket->number . ' berhasil dibuat!']);
        } else {
            return redirect()->back()->with(['error' => 'Gagal membuat tiket, silakan coba lagi.'])->withInput();
        }
    }
}
