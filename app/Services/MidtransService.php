<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        // Set your Merchant Server Key
        Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = config('midtrans.is_production');
        // Set sanitization on (default)
        Config::$isSanitized = config('midtrans.is_sanitized');
        // Set 3DS transaction for credit card to true
        Config::$is3ds = config('midtrans.is_3ds');

        // Local Patch: Disable SSL verification for local environment
        if (config('app.env') === 'local') {
            Config::$curlOptions = [
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTPHEADER     => [], // Tambahkan ini biar SDK Midtrans tidak error (Bug SDK)
            ];
        }
    }

    /**
     * Create Snap Token for an Order.
     * 
     * @param Order $order
     * @return string Snap Token
     */
    public function getSnapToken(Order $order): string
    {
        $itemDetails = [];

        // Build item details from order items
        foreach ($order->items as $item) {
            $itemDetails[] = [
                'id'       => $item->product_id . '-' . ($item->product_variant_id ?? '0'),
                'price'    => (int) $item->product_price,
                'quantity' => $item->quantity,
                'name'     => substr($item->product_name, 0, 50),
            ];
        }

        // Add shipping cost if exists
        if ($order->shipping_cost > 0) {
            $itemDetails[] = [
                'id'       => 'SHIPPING',
                'price'    => (int) $order->shipping_cost,
                'quantity' => 1,
                'name'     => 'Ongkos Kirim',
            ];
        }

        // Add discount if exists (as negative price)
        if ($order->discount_amount > 0) {
            $itemDetails[] = [
                'id'       => 'DISCOUNT',
                'price'    => -(int) $order->discount_amount,
                'quantity' => 1,
                'name'     => 'Diskon',
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id'     => $order->order_number,
                'gross_amount' => (int) $order->grand_total,
            ],
            'customer_details' => [
                'first_name' => $order->shipping_name,
                'email'      => $order->shipping_email,
                'phone'      => $order->shipping_phone,
                'billing_address' => [
                    'first_name'   => $order->shipping_name,
                    'phone'        => $order->shipping_phone,
                    'address'      => $order->shipping_address,
                    'city'         => $order->shipping_city,
                    'postal_code'  => $order->shipping_postal_code,
                    'country_code' => 'IDN',
                ],
                'shipping_address' => [
                    'first_name'   => $order->shipping_name,
                    'phone'        => $order->shipping_phone,
                    'address'      => $order->shipping_address,
                    'city'         => $order->shipping_city,
                    'postal_code'  => $order->shipping_postal_code,
                    'country_code' => 'IDN',
                ]
            ],
            'item_details' => $itemDetails,
            'callbacks' => [
                'finish' => url('/checkout/success'),
                'error' => url('/keranjang'),
                'pending' => url('/member/pesanan'),
            ],
        ];

        try {
            return Snap::getSnapToken($params);
        } catch (\Exception $e) {
            \Log::error('Midtrans Snap Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
