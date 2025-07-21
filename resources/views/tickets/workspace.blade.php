@extends('layouts.app')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Workspace Tiket #{{ $ticket->number }}</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Laporan dari {{ $ticket->guest_name }}</h4>
                </div>
                <div class="card-body">
                    <p><strong>Divisi:</strong> {{ $ticket->guest_divisi }}</p>
                    <p><strong>Masalah:</strong></p>
                    <p style="white-space: pre-wrap;">{{ $ticket->problemdetail }}</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>Update Pengerjaan Tiket</h4>
                </div>
                <div class="card-body">
                    {{-- Form ini akan mengirim data ke method update --}}
                    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>RESOLUSI / TINDAKAN YANG DILAKUKAN</label>
                            <textarea name="resolution" class="form-control" rows="6" required>{{ old('resolution', $ticket->resolution) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>UBAH STATUS</label>
                            <select name="status" class="form-control" required>
                                <option value="">- Pilih Status -</option>
                                <option value="In Progress" {{ $ticket->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Closed" {{ $ticket->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Tiket</button>
                        <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection