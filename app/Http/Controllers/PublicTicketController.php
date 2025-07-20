<?php

namespace App\Http\Controllers;

use App\Models\Sla;
use App\Models\Ticket;
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
        return view('public.create-ticket', compact('slas'));
    }

    /**
     * Menyimpan tiket baru dari form publik.
     */
    public function store(Request $request)
    {
        $request->validate([
            'guest_name'   => 'required|string|max:100',
            'guest_divisi' => 'required|string|max:100',
            'detail'       => 'required|string',
        ]);

        $year = today()->year;

        // --- BLOK KODE YANG DIPERBAIKI ---
        $latest_ticket = Ticket::latest('id')->first();
        $new_number = 1; // Nilai default jika belum ada tiket
        if ($latest_ticket) {
            $parts = explode('/', $latest_ticket->number);
            $new_number = (int)$parts[0] + 1;
        }
        // --- AKHIR BLOK KODE YANG DIPERBAIKI ---

        $ticket = Ticket::create([
            'number'         => $new_number . "/" . $year,
            'guest_name'     => $request->guest_name,
            'guest_divisi'   => $request->guest_divisi,
            'problemdetail'  => $request->detail,
            'problemsummary' => 'Laporan dari ' . $request->guest_name,
            'status'         => 'Open',
            'reporteddate'   => now(),
        ]);

        if ($ticket) {
            return redirect('/')->with(['success' => 'Tiket Anda No. ' . $ticket->number . ' berhasil dibuat!']);
        } else {
            return redirect()->back()->with(['error' => 'Gagal membuat tiket, silakan coba lagi.'])->withInput();
        }
    }
}
