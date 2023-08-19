<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Order;

class TripayCallbackController extends Controller
{
    // Isi dengan private key anda
    protected $privateKey = '3h0tr-jk3pX-oeAxt-ZzLxz-C1jkw';

    public function handle(Request $request)
    {
        $callbackSignature = $request->server('HTTP_X_CALLBACK_SIGNATURE');
        $json = $request->getContent();
        $signature = hash_hmac('sha256', $json, $this->privateKey);

        if ($signature !== (string) $callbackSignature) {
            return Response::json([
                'success' => false,
                'message' => 'Invalid signature',
            ]);
        }

        if ('payment_status' !== (string) $request->server('HTTP_X_CALLBACK_EVENT')) {
            return Response::json([
                'success' => false,
                'message' => 'Unrecognized callback event, no action was taken',
            ]);
        }

        $data = json_decode($json);

        if (JSON_ERROR_NONE !== json_last_error()) {
            return Response::json([
                'success' => false,
                'message' => 'Invalid data sent by tripay',
            ]);
        }

        $uniqueRef = $data->reference;
        $status = strtoupper((string) $data->status);

        if ($data->is_closed_payment === 1) {
            $order = Order::where('reference', $uniqueRef)
                ->where('status', '=', 'UNPAID')
                ->first();

            if (!$order) {
                return Response::json([
                    'success' => false,
                    'message' => 'No invoice found or already paid: ' . $uniqueRef,
                ]);
            }

            switch ($status) {
                case 'PAID':
                    $order->update(['status' => 'PAID']);
                    break;

                case 'EXPIRED':
                    $order->update(['status' => 'EXPIRED']);
                    break;

                case 'FAILED':
                    $order->update(['status' => 'FAILED']);
                    break;

                default:
                    return Response::json([
                        'success' => false,
                        'message' => 'Unrecognized payment status',
                    ]);
            }

            return Response::json(['success' => true]);
        }
    }
}