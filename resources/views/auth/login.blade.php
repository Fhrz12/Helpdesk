{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.guest') {{-- atau layout auth Anda --}}

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
            <div class="login-brand">
                <img src="../assets/img/stisla-fill.svg" alt="logo" width="100" class="shadow-light rounded-circle">
            </div>

            <div class="row">
                {{-- KOTAK 1: UNTUK CUSTOMER MEMBUAT TIKET --}}
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Layanan Pelanggan</h4>
                        </div>
                        <div class="card-body text-center">
                            <p>Jika Anda mengalami kendala atau memiliki keluhan, silakan buat tiket laporan melalui tombol di bawah ini.</p>
                            {{-- Tombol ini akan mengarah ke halaman form publik --}}
                            <a href="{{ route('public.ticket.create') }}" class="btn btn-lg btn-primary">
                                <i class="fas fa-ticket-alt"></i> Buat Tiket Laporan
                            </a>
                        </div>
                    </div>
                </div>

                {{-- KOTAK 2: FORM LOGIN UNTUK STAF --}}
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Login Staf</h4>
                        </div>
                        <div class="card-body">
                            {{-- Letakkan Form Login Anda yang sudah ada di sini --}}
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input id="email" type="email" class="form-control" name="email" tabindex="1" required autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="control-label">Password</label>
                                    <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                        Login
                                    </button>
                                </div>
                            </form>
                            {{-- Akhir dari form login --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="simple-footer">
                Copyright &copy; {{ date('Y') }}
            </div>
        </div>
    </div>
</div>
@endsection