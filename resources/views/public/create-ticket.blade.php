    {{-- resources/views/public/create_ticket.blade.php --}}
    @extends('layouts.guest') {{-- Atau layout lain yang lebih simpel --}}

    @section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h4>Formulir Laporan Kendala</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('public.ticket.store') }}" method="POST">
                    @csrf

                    {{-- Menampilkan semua error di bagian atas (opsional, tapi bagus untuk debugging) --}}
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Terjadi Kesalahan!</strong> Mohon periksa kembali isian Anda.
                    </div>
                    @endif

                    {{-- Field Nama --}}
                    <div class="form-group">
                        <label for="guest_name">Nama</label>
                        <input type="text" id="guest_name" name="guest_name" class="form-control @error('guest_name') is-invalid @enderror" value="{{ old('guest_name') }}" required>
                        @error('guest_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    {{-- Field Divisi --}}
                    <div class="form-group">
                        <label for="guest_divisi">Divisi</label>
                        <input type="text" id="guest_divisi" name="guest_divisi" class="form-control @error('guest_divisi') is-invalid @enderror" value="{{ old('guest_divisi') }}" required>
                        @error('guest_divisi')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    {{-- Field Detail Masalah --}}
                    <div class="form-group">
                        <label for="detail">Jelaskan Masalah Secara Detail</label>
                        <textarea id="detail" name="detail" class="form-control @error('detail') is-invalid @enderror" rows="6" required>{{ old('detail') }}</textarea>
                        @error('detail')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>


                    <button type="submit" class="btn btn-primary">Kirim Laporan</button>
                    <a href="{{ url('/') }}" class="btn btn-secondary">Batal</a>
                </form>

                {{-- resources/views/public/create_ticket.blade.php --}}

                <form action="{{ route('public.ticket.store') }}" method="POST">
                    @csrf

                    {{-- ====================================================== --}}
                    {{-- ===== TEMPELKAN KODE DI BAWAH INI UNTUK MELIHAT ERROR ===== --}}
                    {{-- ====================================================== --}}
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Terjadi Kesalahan!</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    {{-- ====================================================== --}}

            </div>
        </div>
    </div>
    @endsection