<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;
use App\Mail\AdminOrderNotification;

class PaymentCallbackController extends Controller
{
    public function midtransCallback(Request $request)
    {
        $payload = $request->getContent();
        $notification = json_decode($payload);

        if (!$notification) {
            return response()->json(['message' => 'Invalid JSON payload'], 400);
        }

        $orderId = $notification->order_id;

        // Handle Midtrans dashboard test notification immediately
        if ($orderId && str_contains($orderId, 'payment_notif_test')) {
            Log::info('Midtrans Test Notification received successfully', ['order_id' => $orderId]);
            return response()->json(['message' => 'Test notification received successfully'], 200);
        }

        $statusCode = $notification->status_code;
        $grossAmount = $notification->gross_amount;
        $signatureKey = $notification->signature_key;
        
        $serverKey = config('midtrans.server_key');

        // Verify signature
        $mySignatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($mySignatureKey !== $signatureKey) {
            Log::warning('Midtrans Invalid Signature', ['order_id' => $orderId]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::where('order_number', $orderId)->first();

        if (!$order) {
            Log::warning('Midtrans Callback: Order Not Found', ['order_id' => $orderId]);
            return response()->json(['message' => 'Order not found, callback acknowledged'], 200);
        }

        $transactionStatus = $notification->transaction_status;
        $paymentType = $notification->payment_type;
        $fraudStatus = $notification->fraud_status ?? null;
        $transactionId = $notification->transaction_id;

        // Extract bank and VA number if available
        $bank = null;
        $vaNumber = null;

        if (isset($notification->va_numbers[0])) {
            $bank = $notification->va_numbers[0]->bank;
            $vaNumber = $notification->va_numbers[0]->va_number;
        } elseif (isset($notification->permata_va_number)) {
            $bank = 'permata';
            $vaNumber = $notification->permata_va_number;
        } elseif (isset($notification->bill_key)) {
            $bank = 'mandiri';
            $vaNumber = $notification->bill_key; // or combined biller_code + bill_key
        }

        // Log the payment
        Payment::updateOrCreate(
            ['transaction_id' => $transactionId],
            [
                'order_id' => $order->id,
                'payment_type' => $paymentType,
                'bank' => $bank,
                'va_number' => $vaNumber,
                'gross_amount' => $grossAmount,
                'status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'raw_response' => $notification,
            ]
        );

        $isPaid = false;

        // Handle transaction status
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $order->update(['payment_status' => 'pending']);
            } else if ($fraudStatus == 'accept') {
                $isPaid = true;
            }
        } else if ($transactionStatus == 'settlement') {
            $isPaid = true;
        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $order->update(['payment_status' => $transactionStatus == 'expire' ? 'expired' : 'failed']);
            if ($order->status == 'pending_payment') {
                $order->update(['status' => 'cancelled']);
            }
        } else if ($transactionStatus == 'pending') {
            $order->update(['payment_status' => 'unpaid']);
        }

        if ($isPaid) {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            // Clear cart if user is logged in
            if ($order->user_id) {
                try {
                    \App\Models\Cart::where('user_id', $order->user_id)->delete();
                } catch (\Exception $e) {
                    Log::error('Failed to clear cart in callback', ['error' => $e->getMessage()]);
                }
            }

            // Create Invoice automatically
            if (!$order->invoice) {
                \App\Models\Invoice::create([
                    'order_id' => $order->id,
                    'invoice_number' => \App\Models\Invoice::generateInvoiceNumber(),
                    'invoice_date' => now(),
                    'subtotal' => $order->subtotal,
                    'shipping_cost' => $order->shipping_cost,
                    'discount_amount' => $order->discount_amount,
                    'grand_total' => $order->grand_total,
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
            }

            // Create Order Status History
            $order->statusHistories()->create([
                'status' => 'paid',
                'description' => 'Pembayaran berhasil dikonfirmasi via Midtrans (' . $paymentType . ')',
                'changed_by' => null, // System
            ]);

            // Send Invoice Email Automatically
            try {
                // Email ke Pembeli
                Mail::to($order->shipping_email)->send(new InvoiceMail($order));
                Log::info('Invoice Email Sent Automatically to Customer', ['order_id' => $order->id]);

                // Email ke Admin
                Mail::to('abdulhamid66266@gmail.com')->send(new AdminOrderNotification($order));
                Log::info('Admin Notification Email Sent', ['order_id' => $order->id]);
                
            } catch (\Exception $e) {
                Log::error('Failed to send notification emails', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        Log::info('Midtrans Notification Processed', ['order_id' => $orderId, 'status' => $transactionStatus]);

        return response()->json(['message' => 'Success']);
    }
}
