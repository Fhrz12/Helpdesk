<?php

use Illuminate\Support\Facades\Auth;
use App\Models\News;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Log_user;
use App\Services\ReportService;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SlaController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\LogUserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PermissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');

// Route untuk form tiket publik (tanpa login)
Route::get('/lapor', [App\Http\Controllers\PublicTicketController::class, 'create'])->name('public.ticket.create');
Route::post('/lapor', [App\Http\Controllers\PublicTicketController::class, 'store'])->name('public.ticket.store');


Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', function () {
        $report = new ReportService(now()->format('Y'), now()->format('m'));
        $news = News::latest()->take(3)->get();
        $user = Auth::user(); // Ambil pengguna yang sedang login

        // Cek jika pengguna adalah Teknisi
        if ($user->hasRole('Teknisi')) {

            // Jika Teknisi, buat laporan hanya untuk dirinya sendiri
            $reportForSelf = new ReportService(now()->format('Y'), now()->format('m'), $user->id);

            // Buat koleksi yang berisi satu laporan saja
            $allReports = collect([
                [
                    'name'     => $user->name,
                    'assigned' => $reportForSelf->getMonthlyTickets(),
                    'expired'  => $reportForSelf->getOverdueTickets('red'),
                    'warning'  => $reportForSelf->getOverdueTickets('yellow'),
                    'pending'  => $reportForSelf->getMonthlyPendingTickets(),
                    'done'     => $reportForSelf->getMonthlyDoneTickets()
                ]
            ]);
        } else {
            // Jika bukan Teknisi (Admin), ambil laporan semua teknisi
            $allReports = $report->getAllTechnicianTickets();
        }

        // Kirim data yang sudah difilter ke view
        return view('dashboard', compact('report', 'allReports', 'news'));
    })->name('dashboard');

    Route::get('tickets/{ticket}/assign', [TicketController::class, 'assignForm'])->name('tickets.assignForm');
    Route::post('tickets/{ticket}/assign', [TicketController::class, 'assignStore'])->name('tickets.assignStore');

    // Daftarkan route untuk halaman update/edit secara manual
    Route::get('tickets/{ticket}/edit', [TicketController::class, 'updateForm'])->name('tickets.edit');

    // Daftarkan sisa resource route, kecuali edit, create, dan store
    Route::resource('tickets', TicketController::class)->except([
        'create',
        'store',
        'edit'
    ]);

    Route::resource('permissions', PermissionController::class)->only([
        'index'
    ]);

    Route::resource('roles', RoleController::class)->except([
        'show'
    ]);

    Route::resource('users', UserController::class)->except([
        'show'
    ]);

    Route::resource('news', NewsController::class)->except([
        'show'
    ]);

    Route::resource('customers', CustomerController::class)->except([
        'show'
    ]);

    Route::resource('slas', SlaController::class);

    Route::resource('projects', ProjectController::class)->except([
        'show'
    ]);


    Route::get('user-log', [LogUserController::class, 'index'])->name('userlog.index');
});

//permissions




require __DIR__ . '/auth.php';
