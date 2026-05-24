<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\ShippingLabel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    /**
     * Cetak Invoice PDF ukuran A4.
     */
    public function invoice(Invoice $invoice, Request $request)
    {
        // Authorization check: Admin OR Owner OR Valid Signed URL
        $isAdmin = auth()->check() && auth()->user()->isAdmin();
        $isOwner = auth()->check() && $invoice->order && $invoice->order->user_id === auth()->id();
        $hasValidSignature = $request->hasValidSignature();

        if (!$isAdmin && !$isOwner && !$hasValidSignature) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mencetak invoice ini.');
        }

        $invoice->load(['order.items.product', 'order.items.variant', 'order.user']);
        
        $pdf = Pdf::loadView('print.invoice', ['invoice' => $invoice])
            ->setPaper('a4', 'portrait');

        // Sanitize filename: replace / with -
        $filename = str_replace(['/', '\\'], '-', $invoice->invoice_number);

        return $pdf->stream('Invoice-Faktur-' . $filename . '.pdf');
    }

    /**
     * Cetak Detail Pesanan / Surat Pesanan PDF ukuran A4.
     */
    public function order(Order $order, Request $request)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mencetak pesanan ini.');
        }

        $order->load(['items.product', 'items.variant', 'user']);

        // Jika ada parameter ship=1 dan status saat ini adalah processing, update ke shipped
        if ($request->query('ship') == '1' && $order->status === 'processing') {
            $order->update([
                'status' => 'shipped',
                'shipped_at' => now(),
            ]);
        }
        
        $pdf = Pdf::loadView('print.order', ['order' => $order])
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Pesanan-' . $order->order_number . '.pdf');
    }

    /**
     * Cetak Label Pengiriman / Resi Thermal ukuran 150x100mm.
     */
    public function shippingLabel(ShippingLabel $shippingLabel)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mencetak label pengiriman ini.');
        }

        $shippingLabel->load(['order']);
        $shippingLabel->markAsPrinted();

        return view('print.shipping-label', ['label' => $shippingLabel]);
    }
}
