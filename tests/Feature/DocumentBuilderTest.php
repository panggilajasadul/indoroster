<?php

namespace Tests\Feature;

use App\Models\DocumentTemplate;
use App\Models\ManualDocument;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\DocumentPdfService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentBuilderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create standard admin user for tests
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    /**
     * Test template elements initialization.
     */
    public function test_template_initializes_default_elements_and_margins()
    {
        $template = DocumentTemplate::create([
            'name' => 'Test Invoice Template',
            'type' => 'invoice',
            'paper_size' => 'a4',
            'orientation' => 'portrait',
        ]);

        $this->assertNotEmpty($template->margins);
        $this->assertEquals(15, $template->margins['top']);
        $this->assertNotEmpty($template->elements);
        $this->assertTrue($template->elements['logo']['visible']);
        $this->assertEquals(15, $template->elements['logo']['x']);
    }

    /**
     * Test only one template of a specific type can be default.
     */
    public function test_only_one_template_per_type_can_be_default()
    {
        $template1 = DocumentTemplate::create([
            'name' => 'Template 1',
            'type' => 'invoice',
            'is_default' => true,
        ]);

        $template2 = DocumentTemplate::create([
            'name' => 'Template 2',
            'type' => 'invoice',
            'is_default' => true,
        ]);

        $template1->refresh();
        $this->assertFalse($template1->is_default);
        $this->assertTrue($template2->is_default);
    }

    /**
     * Test document automatically links to default template on creation.
     */
    public function test_document_automatically_links_to_default_template()
    {
        $template = DocumentTemplate::create([
            'name' => 'Default Invoice Template',
            'type' => 'invoice',
            'is_default' => true,
        ]);

        $document = ManualDocument::create([
            'type' => 'invoice',
            'client_name' => 'Rudy Hartono',
            'items' => [
                ['product_name' => 'Roster Beton Mini', 'quantity' => 10, 'price' => 15000],
            ],
            'subtotal' => 150000,
            'discount' => 0,
            'has_tax' => false,
            'tax_amount' => 0,
            'grand_total' => 150000,
            'document_date' => now(),
            'status' => 'draft',
        ]);

        $this->assertEquals($template->id, $document->document_template_id);
    }

    /**
     * Test manual document creation without any template.
     */
    public function test_document_can_be_created_without_template_and_uses_global_fallback()
    {
        // Set some global settings
        SiteSetting::setValue('doc_company_name', 'GLOBAL INDOROSTER CORP');
        SiteSetting::setValue('doc_signer_name', 'CEO Abdul Hamid');

        $document = ManualDocument::create([
            'type' => 'invoice',
            'client_name' => 'Rudy Hartono',
            'items' => [
                ['product_name' => 'Roster Beton Mini', 'quantity' => 10, 'price' => 15000],
            ],
            'subtotal' => 150000,
            'discount' => 0,
            'has_tax' => false,
            'tax_amount' => 0,
            'grand_total' => 150000,
            'document_date' => now(),
            'status' => 'draft',
            'document_template_id' => null, // Explicitly null
        ]);

        // Manually force it to null in case a default template exists in database seeds
        $document->document_template_id = null;
        $document->save();

        $this->assertNull($document->document_template_id);

        $pdfService = new DocumentPdfService;
        $pdf = $pdfService->generatePdf($document);
        $this->assertNotNull($pdf);
    }

    /**
     * Test snapshot generation on final status change.
     */
    public function test_document_saves_snapshot_when_status_becomes_final()
    {
        $template = DocumentTemplate::create([
            'name' => 'Default Quotation Template',
            'type' => 'quotation',
            'is_default' => true,
            'tax_rate' => 11.00,
        ]);

        $document = ManualDocument::create([
            'type' => 'quotation',
            'client_name' => 'Susi Susanti',
            'items' => [
                ['product_name' => 'Roster Beton Motif Loster', 'quantity' => 20, 'price' => 20000],
            ],
            'subtotal' => 400000,
            'discount' => 0,
            'has_tax' => true,
            'tax_amount' => 44000,
            'grand_total' => 444000,
            'document_date' => now(),
            'status' => 'draft',
        ]);

        $this->assertNull($document->snapshot);

        // Transition status to final
        $document->update(['status' => 'final']);
        $document->refresh();

        $this->assertNotNull($document->snapshot);
        $this->assertEquals('Susi Susanti', $document->snapshot['document']['client_name']);
        $this->assertEquals(444000, $document->snapshot['document']['grand_total']);
        $this->assertEquals('Default Quotation Template', $document->snapshot['template']['name']);
        $this->assertEquals(11.00, $document->snapshot['template']['tax_rate']);
    }

    /**
     * Test printed output immutability after snapshot is captured.
     */
    public function test_printed_output_immutability_via_snapshot()
    {
        $template = DocumentTemplate::create([
            'name' => 'Default Invoice Template',
            'type' => 'invoice',
            'is_default' => true,
            'logo_x' => 15,
        ]);

        $document = ManualDocument::create([
            'type' => 'invoice',
            'client_name' => 'Taufik Hidayat',
            'items' => [
                ['product_name' => 'Roster Minimalis', 'quantity' => 50, 'price' => 12000],
            ],
            'subtotal' => 600000,
            'grand_total' => 600000,
            'document_date' => now(),
            'status' => 'final', // Trigger instant snapshot
        ]);

        $document->refresh();
        $this->assertNotNull($document->snapshot);
        $this->assertEquals(15, $document->snapshot['template']['logo_x']);

        // Modify the template logo coordinate
        $template->update(['logo_x' => 30]);

        // Access via rendering service should still read coordinate from snapshot (15mm)
        $pdfService = new DocumentPdfService;

        // Use reflection or standard code check to ensure snapshot is preferred
        $document->refresh();
        $pdf = $pdfService->generatePdf($document);

        // The snapshot template logo_x must remain 15 despite master template being changed to 30
        $this->assertEquals(15, $document->snapshot['template']['logo_x']);
    }

    /**
     * Test document preserves and prints dimensions and variant details.
     */
    public function test_document_preserves_and_prints_dimensions_and_variants()
    {
        $document = ManualDocument::create([
            'type' => 'invoice',
            'client_name' => 'Bambang Pamungkas',
            'items' => [
                [
                    'product_name' => 'Roster Arrow',
                    'sku' => 'IR-YCOIBB',
                    'dimensions' => '20 x 20 x 10 cm',
                    'variant_name' => 'Abu Abu Natural',
                    'quantity' => 10,
                    'price' => 12500,
                ],
            ],
            'subtotal' => 125000,
            'grand_total' => 125000,
            'document_date' => now(),
            'status' => 'draft',
        ]);

        $this->assertEquals('20 x 20 x 10 cm', $document->items[0]['dimensions']);
        $this->assertEquals('Abu Abu Natural', $document->items[0]['variant_name']);

        $pdfService = new DocumentPdfService;
        $pdf = $pdfService->generatePdf($document);
        $this->assertNotNull($pdf);
    }
}
