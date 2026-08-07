<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

try {
    $s = new App\Http\Controllers\SitemapController();
    $res = $s->index();
    $xmlContent = $res->getContent();
    
    // Fix URLs if they contain test_sitemap.php due to direct script execution
    $xmlContent = str_replace('/test_sitemap.php', '', $xmlContent);
    
    // Write to physical sitemap.xml
    $filePath = __DIR__ . '/sitemap.xml';
    file_put_contents($filePath, $xmlContent);
    
    header('Content-Type: text/plain');
    echo "SUCCESS: sitemap.xml has been generated physically at public/sitemap.xml!\n";
    echo "You can view it here: https://indoroster.com/sitemap.xml\n";
} catch (\Throwable $e) {
    header('Content-Type: text/plain');
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "FILE: " . $e->getFile() . " on line " . $e->getLine() . "\n";
    echo "TRACE:\n" . $e->getTraceAsString() . "\n";
}
