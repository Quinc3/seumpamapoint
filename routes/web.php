<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DebugController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\AttendanceReportController;

Route::get('/print/invoice/{order}', [PrintController::class, 'invoice'])
    ->name('print.invoice');

// Provide a named `login` route that redirects to Filament admin login
Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');

// Print job endpoints for admin clients (polling QZ Tray clients)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/print/pending', [\App\Http\Controllers\PrintJobController::class, 'pending']);
    Route::post('/admin/print/{job}/complete', [\App\Http\Controllers\PrintJobController::class, 'complete']);
    Route::get('/admin/print/last', [\App\Http\Controllers\PrintJobController::class, 'last']);
});

// Duplicate closure route removed: `print.invoice` handled by `PrintController`


// SYSTEM ROUTES
// Route::get('/', [SystemController::class, 'welcome']);
Route::get('/', function () {
    return redirect('/admin');
});

// REPORT ROUTES
Route::prefix('reports')->group(function () {
    Route::get('/invoice/{order}', [ReportController::class, 'downloadInvoice'])->name('download.invoice');
    Route::get('/sales-report', [ReportController::class, 'downloadSalesReport'])->name('download.sales-report');
    Route::get('/monthly-report', [ReportController::class, 'downloadMonthlyReport'])->name('download.monthly-report');
    Route::get('/daily-report', [ReportController::class, 'downloadDailyReport'])->name('download.daily-report');
    Route::get('/download', [ReportController::class, 'downloadReport'])->name('download.report');
});

// DEBUG & TEST ROUTES
Route::prefix('debug')->group(function () {
    Route::get('/test-receipt/{orderId}', [DebugController::class, 'testReceipt']);
    Route::get('/test-print/{orderId}', [DebugController::class, 'testPrint']);
    Route::get('/test-event/{orderId}', [DebugController::class, 'testEvent']);
    Route::get('/order-events/{orderId}', [DebugController::class, 'debugOrderEvents']);
    Route::get('/list-orders', [DebugController::class, 'listOrders']);
    Route::get('/printer-test', [DebugController::class, 'printerTest']);
    Route::get('/make-roles', [DebugController::class, 'makeRoles']);
});

// SYSTEM MAINTENANCE ROUTES
Route::prefix('system')->group(function () {
    Route::get('/clear-cache', [SystemController::class, 'clearCache']);
    Route::get('/info', [SystemController::class, 'systemInfo']);
});

// ATTENDANCE REPORT ROUTE
Route::get('/reports/attendance', [AttendanceReportController::class, 'download'])
    ->name('attendance.report.download');

// // HEALTH CHECK ROUTES
// Route::get('/', fn () => response('OK', 200));
// Route::get('/health', fn () => response()->json(['status'=>'ok']));
