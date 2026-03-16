<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Exports\AttendancesExport;

class AttendanceReportController extends Controller
{
    public function download(Request $request)
    {
        $reportType = $request->get('report_type');
        $format = $request->get('format', 'pdf');

        $query = Attendance::with('user');

        if (!Auth::user()->hasRole('admin')) {
            $query->where('user_id', Auth::id());
        }

        switch ($reportType) {
            case 'daily':
                $date = $request->get('date', now()->toDateString());
                $query->whereDate('clock_in', $date);
                $title = "Attendance Daily Report - {$date}";
                break;

            case 'monthly':
                $month = $request->get('month', now()->month);
                $year = $request->get('year', now()->year);
                $query->whereYear('clock_in', $year)
                      ->whereMonth('clock_in', $month);
                $title = "Attendance Monthly Report - {$month}-{$year}";
                break;

            case 'custom':
                $start = $request->get('start_date');
                $end = $request->get('end_date');
                $query->whereBetween('clock_in', [$start, $end]);
                $title = "Attendance Report {$start} to {$end}";
                break;

            case 'filtered':
                $title = "Filtered Attendance Report";
                break;

            default:
                abort(400);
        }

        $attendances = $query->get();

        if ($format === 'excel') {
            return Excel::download(
                new AttendancesExport($attendances),
                Str::slug($title) . '.xlsx'
            );
        }

        $pdf = Pdf::loadView('reports.attendance-report', [
            'attendances' => $attendances,
            'title' => $title,
        ]);

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download(Str::slug($title) . '.pdf');
    }
}
