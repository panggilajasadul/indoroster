<?php
include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ShippingLabel;

$label = ShippingLabel::whereHas('order', function($q) {
    $q->where('order_number', 'INV-20260509-0004');
})->first();

if ($label) {
    echo "Current Printed At: " . ($label->printed_at ? $label->printed_at->toDateTimeString() : 'NULL') . "\n";
    echo "Marking as printed...\n";
    $label->markAsPrinted();
    echo "New Printed At: " . ($label->refresh()->printed_at ? $label->printed_at->toDateTimeString() : 'NULL') . "\n";
} else {
    echo "Label not found\n";
}
