<?php

namespace App\Http\Controllers;

use App\Models\PrintJob;
use App\Models\Order;
use App\Services\ThermalPrintService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PrintJobController extends Controller
{
    // Return next pending job and mark it processing (atomic enough for simple use)
    public function pending(ThermalPrintService $service)
    {
        $job = PrintJob::where('status', 'pending')->orderBy('created_at')->first();

        if (! $job) {
            return response()->noContent();
        }

        // mark processing
        $job->status = 'processing';
        $job->attempts += 1;
        $job->last_attempt_at = now();
        $job->save();

        // If payment metadata isn't on the print job (legacy jobs), copy from order
        if (is_null($job->cash_received) && $order = Order::find($job->order_id)) {
            $job->cash_received = $job->cash_received ?? ($order->cash_received ?? null);
            $job->cash_change = $job->cash_change ?? ($order->cash_change ?? null);
            $job->save();
        }

        $order = Order::find($job->order_id);
        if (! $order) {
            $job->status = 'failed';
            $job->error_message = 'Order not found';
            $job->save();
            return response()->json(['error' => 'order not found'], 404);
        }

        $result = $service->printInvoice($order);
        $content = is_array($result) ? ($result['content'] ?? '') : (string) $result;

        return response()->json([
            'job_id' => $job->id,
            'order_id' => $order->id,
            'content' => $content,
            'cash_received' => $job->cash_received ?? $order->cash_received ?? null,
            'cash_change' => $job->cash_change ?? $order->cash_change ?? null,
        ]);
    }

    // Mark job completed or failed
    public function complete(Request $request, $jobId)
    {
        $job = PrintJob::find($jobId);
        if (! $job) return response()->json(['error' => 'job not found'], 404);

        $status = $request->input('status', 'done');
        $job->status = in_array($status, ['done','failed']) ? $status : 'done';
        $job->error_message = $request->input('error');
        $job->save();

        return response()->json(['success' => true]);
    }

    // Return the most recent print job (for dashboard widget)
    public function last()
    {
        $job = PrintJob::orderBy('created_at', 'desc')->first();
        if (! $job) return response()->json(null, 204);

        return response()->json([
            'job_id' => $job->id,
            'order_id' => $job->order_id,
            'status' => $job->status,
            'cash_received' => $job->cash_received,
            'cash_change' => $job->cash_change,
            'created_at' => $job->created_at,
        ]);
    }
}
