@extends('layouts.app')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Detail Tiket</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Informasi Laporan</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nomor Tiket:</strong><br> {{ $ticket->number }}</p>
                            <p><strong>Nama Pelapor:</strong><br> {{ $ticket->guest_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Divisi:</strong><br> {{ $ticket->guest_divisi }}</p>
                            <p><strong>Tanggal Laporan:</strong><br> {{ $ticket->created_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>
                    <hr>
                    <p><strong>Detail Masalah:</strong></p>
                    <p style="white-space: pre-wrap;">{{ $ticket->problemdetail }}</p>

                    <hr>
                    {{-- Tombol untuk menuju halaman assign --}}
                    @if($ticket->status == 'Open')
                    <a href="{{ route('tickets.assignForm', $ticket->id) }}" class="btn btn-primary"><i class="fas fa-arrow-right"></i> Lanjutkan ke Penugasan</a>
                    @else
                    <div class="alert alert-info">Tiket ini sudah ditugaskan kepada <strong>{{ $ticket->technician->name ?? 'N/A' }}</strong>.</div>
                    @endif

                    <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection