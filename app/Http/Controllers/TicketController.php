<?php

namespace App\Http\Controllers;

use App\Models\Sla;
use App\Models\User;
use App\Models\Ticket;
use App\Mail\MailNotify;
use App\Models\Customer;
use App\Http\Requests\AssignTicketRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Providers\LogActivity as ProvidersLogActivity;

class TicketController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['permission:tickets.index|tickets.create|tickets.edit|tickets.delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        // Cek jika pengguna memiliki peran 'Teknisi'
        if ($user->hasRole('Teknisi')) {
            // Jika Teknisi, hanya tampilkan tiket yang di-assign kepadanya
            $tickets = Ticket::where('assignee', $user->id)
                ->latest()
                ->when(request()->q, function ($query) {
                    $query->where('problemsummary', 'like', '%' . request()->q . '%');
                })
                ->paginate(10);
        } else {
            // Jika bukan Teknisi (misal: Admin), tampilkan semua tiket
            $tickets = Ticket::latest()
                ->when(request()->q, function ($query) {
                    $query->where('problemsummary', 'like', '%' . request()->q . '%');
                })
                ->paginate(10);
        }

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function create()
    {
        $slas = Sla::orderBy('name', 'asc')->get();
        $users = User::role('teknisi')->get();
        return view('tickets.create', compact('slas', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $this->validate($request, [
            'updated_customer' => 'required',
            'reporteddate' => 'required|date',
            'sla_id' => 'required',
            'summary' => 'required',
            'detail' => 'required',
            'technician_id' => 'required',
        ]);

        $year = today()->year;
        $latest_ticket = Ticket::latest()->first();
        $last_number = Str::of($latest_ticket->number)->explode('/');
        $new_number = $last_number->get(0) + 1;
        $ticket = Ticket::create([
            'number' => $new_number . "/" . $year,
            'sla_id' => $request->input('sla_id'),
            'reportedby' => Auth::id(),
            'customer_id' => $request->input('updated_customer'),
            'reporteddate' => $request->input('reporteddate'),
            'problemsummary' => $request->input('summary'),
            'problemdetail' => $request->input('detail'),
            'status' => 'Assigned',
            'assignee' => $request->input('technician_id'),
            'assigneddate' => now()
        ]);

        $ticket->save();


        if ($ticket) {
            //redirect dengan pesan sukses
            $technician = User::findOrFail($request->input('technician_id'));
            $user = Auth::user();
            $subject = 'Membuat Tiket No ' . $ticket->number;
            event(new ProvidersLogActivity($user, $subject));
            Mail::to($technician->email)->queue(new MailNotify($ticket));
            // dispatch(new SendEmailJob($technician->email, $ticket));
            return redirect()->route('tickets.index')->with(['success' => 'Tiket Berhasil Dibuat']);
        } else {
            //     //redirect dengan pesan error
            return redirect()->route('tickets.index')->with(['error' => 'Tiket Gagal Dibuat']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function updateForm(Ticket $ticket)
    {
        if (Auth::user()->hasRole('Teknisi')) {
            // Teknisi melihat form untuk update resolusi dan status
            return view('tickets.workspace', compact('ticket'));
        }

        // Admin melihat halaman detail dan tombol untuk assign
        return view('tickets.update', compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status'     => 'required|in:In Progress,Closed',
            'resolution' => 'required|string',
        ]);

        $ticket->update([
            'status'     => $request->status,
            'resolution' => $request->resolution,
        ]);

        // Logika event atau notifikasi bisa ditambahkan di sini

        return redirect()->route('tickets.index')->with('success', 'Tiket berhasil di-update!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        if ($ticket) {
            $user = Auth::user();
            $subject = 'Menghapus Tiket No ' . $ticket->number;
            event(new ProvidersLogActivity($user, $subject));
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }
    }

    public function assignForm(Ticket $ticket)
    {
        // Ambil data teknisi dan SLA untuk dropdown
        $technicians = User::role('teknisi')->get();
        $slas = Sla::orderBy('name', 'asc')->get();

        // Arahkan ke view baru yang akan kita buat
        return view('tickets.assign', compact('ticket', 'technicians', 'slas'));
    }

    /**
     * Menyimpan data penugasan tiket.
     */
    public function assignStore(AssignTicketRequest $request, Ticket $ticket)
    {
        $ticket->update([
            'assignee'      => $request->assignee,
            'sla_id'        => $request->sla_id,
            'status'        => Ticket::STATUS_ASSIGNED,
            'assigneddate'  => now(),
        ]);

        return redirect()->route('tickets.index')->with('success', 'Tiket berhasil ditugaskan!');
    }
}
