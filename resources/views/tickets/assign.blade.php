@extends('layouts.app')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Assign Ticket</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Formulir Penugasan Tiket No: {{ $ticket->number }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('tickets.assignStore', $ticket->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>URGENCY / SLA</label>
                            <select class="form-control @error('sla_id') is-invalid @enderror" name="sla_id" required>
                                <option value="">- Pilih SLA -</option>
                                @foreach ($slas as $sla)
                                <option value="{{ $sla->id }}">{{ $sla->name }}</option>
                                @endforeach
                            </select>
                            @error('sla_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>ASSIGN TO</label>
                            <select class="form-control @error('assignee') is-invalid @enderror" name="assignee" required>
                                <option value="">- Pilih Teknisi -</option>
                                @foreach ($technicians as $technician)
                                <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                                @endforeach
                            </select>
                            @error('assignee')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Penugasan</button>
                        <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection