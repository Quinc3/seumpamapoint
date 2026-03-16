<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\ThermalPrintService;

class PrintController extends Controller
{
    public function invoice(Order $order, ThermalPrintService $service)
    {
        $result = $service->printInvoice($order);
        $content = is_array($result) ? ($result['content'] ?? '') : (string) $result;

        return response($content, 200, ['Content-Type' => 'text/plain']);
    }
}
