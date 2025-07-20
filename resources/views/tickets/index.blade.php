@extends('layouts.app')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Ticket</h1>
        </div>

        <div class="section-body">

            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-clipboard-list"></i> Tickets</h4>
                </div>

                <div class="card-body">
                    {{-- Form untuk pencarian tiket --}}
                    <form action="{{ route('tickets.index') }}" method="GET">
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="q" value="{{ request()->q }}"
                                    placeholder="Cari berdasarkan masalah...">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> CARI
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" style="text-align: center;width: 6%">NO.</th>
                                    <th scope="col">NO TIKET</th>
                                    <th scope="col">PELAPOR</th>
                                    <th scope="col">DIVISI</th>
                                    <th scope="col">STATUS</th>
                                    <th scope="col" style="width: 15%;text-align: center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tickets as $no => $ticket)
                                <tr>
                                    <th scope="row" style="text-align: center">{{ ++$no + ($tickets->currentPage()-1) * $tickets->perPage() }}</th>
                                    <td>{{ $ticket->number }}</td>

                                    {{-- Menampilkan nama pelapor (dari tamu atau dari customer terdaftar) --}}
                                    <td>{{ $ticket->guest_name ?? $ticket->customer->name }}</td>


                                    <td>{{ $ticket->guest_divisi }}</td>
                                    <td>
                                        @if($ticket->status == 'Open')
                                        <span class="badge badge-primary">OPEN</span>
                                        @elseif($ticket->status == 'Assigned')
                                        <span class="badge badge-info">ASSIGNED</span>
                                        @elseif($ticket->status == 'In Progress')
                                        <span class="badge badge-warning">IN PROGRESS</span>
                                        @else
                                        <span class="badge badge-success">CLOSED</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{-- Tombol Edit ini sekarang berfungsi sebagai "Assign" --}}
                                        <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-pencil-alt"></i> LIHAT/ASSIGN
                                        </a>

                                        {{-- Tombol Hapus (jika diperlukan) --}}
                                        <button onClick="Delete(this.id)" class="btn btn-sm btn-danger" id="{{ $ticket->id }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="alert alert-danger">
                                            Data Tiket belum Tersedia.
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div style="text-align: center">
                            {{$tickets->links("vendor.pagination.bootstrap-4")}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

<script>
    //ajax delete
    function Delete(id) {
        var id = id;
        var token = $("meta[name='csrf-token']").attr("content");

        swal({
            title: "APAKAH KAMU YAKIN ?",
            text: "INGIN MENGHAPUS DATA INI!",
            icon: "warning",
            buttons: [
                'TIDAK',
                'YA'
            ],
            dangerMode: true,
        }).then(function(isConfirm) {
            if (isConfirm) {


                //ajax delete
                jQuery.ajax({
                    url: "/tickets/" + id,
                    data: {
                        "id": id,
                        "_token": token
                    },
                    type: 'DELETE',
                    success: function(response) {
                        if (response.status == "success") {
                            swal({
                                title: 'BERHASIL!',
                                text: 'DATA BERHASIL DIHAPUS!',
                                icon: 'success',
                                timer: 1000,
                                showConfirmButton: false,
                                showCancelButton: false,
                                buttons: false,
                            }).then(function() {
                                location.reload();
                            });
                        } else {
                            swal({
                                title: 'GAGAL!',
                                text: 'DATA GAGAL DIHAPUS!',
                                icon: 'error',
                                timer: 1000,
                                showConfirmButton: false,
                                showCancelButton: false,
                                buttons: false,
                            }).then(function() {
                                location.reload();
                            });
                        }
                    }
                });

            } else {
                return true;
            }
        })
    }
</script>
@stop