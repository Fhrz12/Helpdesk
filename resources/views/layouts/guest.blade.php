<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>HELPDESK SYSTEM</title>
    <link rel="shortcut icon" href="{{ asset('assets/img/school.svg') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">

    {{-- Script Sweet Alert --}}
    <script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
</head>

<body style="background: #e2e8f0">
    <div id="app">
        <section class="section">
            @yield('content')
        </section>
    </div>

    {{-- KODE UNTUK MENAMPILKAN NOTIFIKASI --}}
    <script>
        // Cek jika ada session 'success'
        @if(session() -> has('success'))
        swal({
            type: "success",
            icon: "success",
            title: "BERHASIL!",
            text: "{{ session('success') }}",
            timer: 3000, // Tampilkan selama 3 detik
            showConfirmButton: false,
            showCancelButton: false,
            buttons: false,
        });
        // Cek jika ada session 'error'
        @elseif(session() -> has('error'))
        swal({
            type: "error",
            icon: "error",
            title: "GAGAL!",
            text: "{{ session('error') }}",
            timer: 3000,
            showConfirmButton: false,
            showCancelButton: false,
            buttons: false,
        });
        @endif
    </script>
</body>

</html>