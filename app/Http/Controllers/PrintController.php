<?php

namespace App\Http\Controllers;

use App\Models\DocumentTemplate;
use App\Models\Invoice;
use App\Models\ManualDocument;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ShippingLabel;
use App\Services\DocumentPdfService;
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

        if (! $isAdmin && ! $isOwner && ! $hasValidSignature) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mencetak invoice ini.');
        }

        $invoice->load(['order.items.product', 'order.items.variant', 'order.user']);

        $paymentStage = null;
        if ($request->filled('payment_id')) {
            $paymentStage = Payment::find($request->get('payment_id'));
        }

        $pdf = Pdf::loadView('print.invoice', [
            'invoice' => $invoice,
            'paymentStage' => $paymentStage,
        ])->setPaper('a4', 'portrait');

        // Sanitize filename: replace / with -
        $stageSuffix = $paymentStage ? '-Tahap-'.$paymentStage->id : '';
        $filename = str_replace(['/', '\\'], '-', $invoice->invoice_number).$stageSuffix;

        return $pdf->stream('Invoice-Faktur-'.$filename.'.pdf');
    }

    /**
     * Cetak Detail Pesanan / Surat Pesanan PDF ukuran A4.
     */
    public function order(Order $order, Request $request)
    {
        $isAdmin = auth()->check() && auth()->user()->isAdmin();
        $hasValidSignature = $request->hasValidSignature();

        if (! $isAdmin && ! $hasValidSignature) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mencetak pesanan / surat jalan ini.');
        }

        $order->load(['items.product', 'items.variant', 'user', 'batches']);

        $batch = null;
        if ($request->filled('batch_id')) {
            $batch = $order->batches()->find($request->get('batch_id'));
        }

        // Jika ada parameter ship=1 dan status saat ini adalah processing, update ke shipped (hanya jika bukan PO Batch)
        if ($request->query('ship') == '1' && $order->status === 'processing' && ! $batch && $order->fulfillment_type !== 'po_batch') {
            $order->update([
                'status' => 'shipped',
                'shipped_at' => now(),
            ]);
        }

        $pdf = Pdf::loadView('print.order', [
            'order' => $order,
            'batch' => $batch,
        ])->setPaper('a4', 'portrait');

        $filenameSuffix = $batch ? '-'.str_replace(' ', '', $batch->batch_name) : '';

        return $pdf->stream('SuratJalan-'.$order->order_number.$filenameSuffix.'.pdf');
    }

    /**
     * Cetak Surat Perintah Kerja Produksi (SPK) PDF ukuran A4.
     */
    public function productionOrder(Order $order, Request $request)
    {
        $isAdmin = auth()->check() && auth()->user()->isAdmin();
        $hasValidSignature = $request->hasValidSignature();

        if (! $isAdmin && ! $hasValidSignature) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mencetak dokumen pengajuan produksi ini.');
        }

        $order->load(['items.product', 'items.variant.material', 'user', 'batches']);

        $batch = null;
        if ($request->filled('batch_id')) {
            $batch = $order->batches()->find($request->get('batch_id'));
        }

        $sppNumber = 'SPP/'.date('Ymd', strtotime($order->created_at)).'/'.$order->order_number.($batch ? '-B'.$batch->batch_number : '');

        $factoryName = $request->get('factory_name')
            ?: ($batch?->factory_name ?: ($order->factory_name ?: 'Pabrik Utama Plered (Purwakarta)'));
        $factoryPicName = $request->get('factory_pic_name')
            ?: ($batch?->factory_pic_name ?: ($order->factory_pic_name ?: 'Kang Asep (Mandor Pabrik)'));
        $factoryPicPhone = $request->get('factory_pic_phone')
            ?: ($batch?->factory_pic_phone ?: ($order->factory_pic_phone ?: ''));
        $factoryNotes = $request->get('factory_notes')
            ?: ($batch?->notes ?: ($order->fulfillment_notes ?: $order->admin_notes));

        $targetDate = $batch?->estimated_dispatch_date?->format('d M Y')
            ?: ($order->ready_shipping_date?->format('d M Y') ?: now()->addDays(7)->format('d M Y'));

        $pdf = Pdf::loadView('print.production-order', [
            'order' => $order,
            'batch' => $batch,
            'sppNumber' => $sppNumber,
            'spkNumber' => $sppNumber, // fallback alias
            'factoryName' => $factoryName,
            'factoryPicName' => $factoryPicName,
            'factoryPicPhone' => $factoryPicPhone,
            'factoryNotes' => $factoryNotes,
            'targetDate' => $targetDate,
        ])->setPaper('a4', 'portrait');

        $filenameSuffix = $batch ? '-'.str_replace(' ', '', $batch->batch_name) : '';

        return $pdf->stream('SPP-'.$order->order_number.$filenameSuffix.'.pdf');
    }

    /**
     * Cetak Surat Pengantar Pengambilan Barang (SPPB) dari Pabrik Rekanan / Vendor PDF ukuran A4.
     */
    public function pickupOrder(Order $order, Request $request)
    {
        $isAdmin = auth()->check() && auth()->user()->isAdmin();
        $hasValidSignature = $request->hasValidSignature();

        if (! $isAdmin && ! $hasValidSignature) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mencetak surat pengantar ambil barang ini.');
        }

        $order->load(['items.product', 'items.variant.material', 'user', 'batches']);

        $batch = null;
        if ($request->filled('batch_id')) {
            $batch = $order->batches()->find($request->get('batch_id'));
        }

        $sppbNumber = 'SPPB/'.date('Ymd', strtotime($order->created_at)).'/'.$order->order_number.($batch ? '-B'.$batch->batch_number : '');

        $factoryName = $request->get('factory_name')
            ?: ($batch?->factory_name ?: ($order->factory_name ?: 'CV. Sumber Berkah Roster'));
        $factoryPicName = $request->get('factory_pic_name')
            ?: ($batch?->factory_pic_name ?: ($order->factory_pic_name ?: 'Pak Asep (Pemilik Pabrik Rekanan)'));
        $factoryPicPhone = $request->get('factory_pic_phone')
            ?: ($batch?->factory_pic_phone ?: ($order->factory_pic_phone ?: ''));
        $factoryAddress = $request->get('factory_address')
            ?: ($batch?->factory_address ?: ($order->factory_address ?: 'Jl. Raya Anjun No. 45, Plered, Purwakarta'));
        $pickupDriverName = $request->get('pickup_driver_name')
            ?: ($batch?->pickup_driver_name ?: ($order->pickup_driver_name ?: ($order->courier ?: 'Supir Internal IndoRoster')));
        $pickupDriverPlate = $request->get('pickup_driver_plate')
            ?: ($batch?->pickup_driver_plate ?: ($order->pickup_driver_plate ?: ($order->tracking_number ?: '-')));
        $factoryNotes = $request->get('factory_notes')
            ?: ($batch?->notes ?: ($order->fulfillment_notes ?: $order->admin_notes));

        $pdf = Pdf::loadView('print.pickup-order', [
            'order' => $order,
            'batch' => $batch,
            'sppbNumber' => $sppbNumber,
            'spabNumber' => $sppbNumber, // fallback alias
            'factoryName' => $factoryName,
            'factoryPicName' => $factoryPicName,
            'factoryPicPhone' => $factoryPicPhone,
            'factoryAddress' => $factoryAddress,
            'pickupDriverName' => $pickupDriverName,
            'pickupDriverPlate' => $pickupDriverPlate,
            'factoryNotes' => $factoryNotes,
        ])->setPaper('a4', 'portrait');

        $filenameSuffix = $batch ? '-'.str_replace(' ', '', $batch->batch_name) : '';

        return $pdf->stream('SPPB-'.$order->order_number.$filenameSuffix.'.pdf');
    }

    /**
     * Cetak Label Pengiriman / Resi Thermal ukuran 150x100mm.
     */
    public function shippingLabel(ShippingLabel $shippingLabel)
    {
        if (! auth()->check() || ! auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mencetak label pengiriman ini.');
        }

        $shippingLabel->load(['order']);
        $shippingLabel->markAsPrinted();

        return view('print.shipping-label', ['label' => $shippingLabel]);
    }

    /**
     * Cetak Dokumen Offline Manual PDF ukuran A4.
     */
    public function manualDocument($documentId, DocumentPdfService $pdfService, Request $request)
    {
        if (! auth()->check() || ! auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mencetak dokumen ini.');
        }

        if ($request->query('preview_session') == '1') {
            $sessionKey = 'unsaved_doc_preview_'.auth()->id();
            if (session()->has($sessionKey)) {
                $sessionData = session()->get($sessionKey);

                $document = new ManualDocument;
                $document->forceFill($sessionData);

                if (! empty($sessionData['document_template_id'])) {
                    $document->document_template_id = $sessionData['document_template_id'];
                    $document->load('documentTemplate');
                }
            } else {
                abort(404, 'Data pratinjau tidak ditemukan di sesi.');
            }
        } else {
            $document = ManualDocument::findOrFail($documentId);
        }

        $pdf = $pdfService->generatePdf($document);

        $filename = str_replace(['/', '\\', ' '], '-', $document->document_number);

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="'.$document->getTypeLabel().'-'.$filename.'.pdf"')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Cetak preview uji coba template desain dengan data tiruan (Mock).
     */
    public function templateTest(DocumentTemplate $template, DocumentPdfService $pdfService)
    {
        if (! auth()->check() || ! auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin.');
        }

        // Construct a mock ManualDocument representation
        $document = new ManualDocument;
        $document->type = $template->type;
        $document->document_number = 'MOCK-'.strtoupper($template->type).'-001';
        $document->document_date = now();
        $document->client_name = 'PT. Contoh Customer Jaya';
        $document->client_phone = '08123456789';
        $document->client_email = 'customer@contoh.com';
        $document->client_address = 'Jl. Jenderal Sudirman No. 123, Jakarta Selatan';
        $document->document_template_id = $template->id;

        $document->items = [
            [
                'product_name' => 'Roster Beton Minimalis Abu-Abu (Ukuran 20x20 cm)',
                'quantity' => 150,
                'price' => 15000,
                'total' => 2250000,
            ],
            [
                'product_name' => 'Biaya Kirim Armada L300 (Jabodetabek)',
                'quantity' => 1,
                'price' => 350000,
                'total' => 350000,
            ],
        ];

        $document->subtotal = 2600000;
        $document->discount = 100000;
        $document->has_tax = true;
        $document->tax_amount = 2500000 * ($template->tax_rate / 100);
        $document->grand_total = 2500000 + $document->tax_amount;
        $document->status = 'draft'; // Prevent freezing layout snapshot during testing

        $document->extra_data = [
            'payment_instructions' => "Transfer Bank BCA No. Rek: 231-xxxx-xxx a/n INDOROSTER INDONESIA\nBayar DP minimal 50% untuk konfirmasi pesanan.\nPelunasan dilakukan sebelum barang dikirim.",
            'payment_instructions_title' => 'Petunjuk Pembayaran',
            'delivery_notes' => "- Mohon periksa kondisi barang saat diterima.\n- Tanda tangani surat jalan sebagai bukti penerimaan.",
            'delivery_notes_title' => 'Catatan Pengiriman',
            'terms_and_conditions' => "1. Harga di atas dapat berubah menyesuaikan volume pemesanan final.\n2. Penawaran harga ini berlaku selama 30 hari sejak diterbitkan.",
            'terms_title' => 'Syarat & Ketentuan',
        ];

        $pdf = $pdfService->generatePdf($document);

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="TEST-PREVIEW-'.$template->name.'.pdf"')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Cetak Kuitansi / Bukti Pembayaran PDF.
     */
    public function receipt(Payment $payment, Request $request)
    {
        $payment->load(['order.user', 'order.invoice']);

        $pdf = Pdf::loadView('print.receipt', ['payment' => $payment])
            ->setPaper('a4', 'portrait');

        $filename = str_replace(['/', '\\'], '-', $payment->receipt_number ?? ('KW-'.$payment->id));

        return $pdf->stream('Kuitansi-'.$filename.'.pdf');
    }
}
