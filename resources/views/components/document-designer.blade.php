<div x-data="documentDesigner({
    orientation: @entangle('data.orientation'),
    paperSize: @entangle('data.paper_size'),
    margins: @entangle('data.margins'),
    logoX: @entangle('data.logo_x'),
    logoY: @entangle('data.logo_y'),
    logoW: @entangle('data.logo_width'),
    logoH: @entangle('data.logo_height'),
    sigX: @entangle('data.signature_x'),
    sigY: @entangle('data.signature_y'),
    sigW: @entangle('data.signature_width'),
    sigH: @entangle('data.signature_height'),
    stampX: @entangle('data.stamp_x'),
    stampY: @entangle('data.stamp_y'),
    stampW: @entangle('data.stamp_width'),
    stampH: @entangle('data.stamp_height'),
    stampOpacity: @entangle('data.stamp_opacity'),
    stampRotation: @entangle('data.stamp_rotation'),
    elements: @entangle('data.elements'),
    logoPath: @entangle('data.logo_path'),
    sigPath: @entangle('data.signature_path'),
    templateSigPath: @entangle('data.template_signature_path'),
    stampPath: @entangle('data.stamp_path'),
    companyName: @entangle('data.company_name'),
    companyAddress: @entangle('data.company_address'),
    companyPhone: @entangle('data.company_phone'),
    companyEmail: @entangle('data.company_email'),
    signerName: @entangle('data.signer_name'),
    signerPosition: @entangle('data.signer_position'),
    customSections: @entangle('data.custom_sections'),
    customPaymentInstructionsTitle: @entangle('data.custom_payment_instructions_title'),
    customPaymentInstructions: @entangle('data.custom_payment_instructions'),
    customDeliveryNotesTitle: @entangle('data.custom_delivery_notes_title'),
    customDeliveryNotes: @entangle('data.custom_delivery_notes'),
    customReceiptNotesTitle: @entangle('data.custom_receipt_notes_title'),
    customReceiptNotes: @entangle('data.custom_receipt_notes'),
    customTermsTitle: @entangle('data.custom_terms_title'),
    customTermsAndConditions: @entangle('data.custom_terms_and_conditions'),
    customOrderNotesTitle: @entangle('data.custom_order_notes_title'),
    customOrderNotes: @entangle('data.custom_order_notes'),
    type: @entangle('data.type'),
    @if(isset($isDocumentMode) && $isDocumentMode)
        clientName: @entangle('data.client_name'),
        grandTotal: @entangle('data.grand_total'),
        subtotal: @entangle('data.subtotal'),
        discount: @entangle('data.discount'),
        taxAmount: @entangle('data.tax_amount'),
        documentNumber: @entangle('data.document_number'),
        items: @entangle('data.items')
    @else
        clientName: 'Nama Klien / Instansi',
        grandTotal: 1500000,
        subtotal: 1500000,
        discount: 0,
        taxAmount: 0,
        documentNumber: 'QTN-20260823-001',
        items: []
    @endif
})" 
class="document-designer-container" 
style="width: 100%; display: flex; flex-direction: column; align-items: center; background: #cbd5e1; padding: 20px; border-radius: 8px; overflow: auto; min-height: 600px; box-sizing: border-box;"
@mouseup="stopDrag" 
@touchend="stopDrag"
@mousemove="onDrag($event)" 
@touchmove="onDrag($event)">

    <!-- Designer Styles -->
    <style>
        .canvas-container {
            background: #fff;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            position: relative;
            box-sizing: border-box;
            user-select: none;
            transition: width 0.3s ease, height 0.3s ease;
        }
        .margin-indicator {
            position: absolute;
            border: 1px dashed #ef4444;
            pointer-events: none;
            box-sizing: border-box;
            z-index: 1;
        }
        .designer-element {
            position: absolute;
            border: 1px dashed #3b82f6;
            background: rgba(59, 130, 246, 0.03);
            cursor: move;
            box-sizing: border-box;
            z-index: 10;
        }
        .designer-element:hover {
            border: 1.5px solid #2563eb;
            background: rgba(59, 130, 246, 0.08);
        }
        .designer-element.active {
            border: 2px solid #16a34a;
            background: rgba(22, 163, 74, 0.06);
            z-index: 20;
        }
        .element-label {
            position: absolute;
            top: -18px;
            left: 0;
            background: #2563eb;
            color: #fff;
            font-size: 9px;
            font-weight: bold;
            padding: 1px 4px;
            border-radius: 2px;
            white-space: nowrap;
            pointer-events: none;
        }
        .designer-element.active .element-label {
            background: #16a34a;
        }
        /* Resize handles */
        .resize-handle {
            position: absolute;
            width: 8px;
            height: 8px;
            background: #2563eb;
            border: 1px solid #fff;
            bottom: -4px;
            right: -4px;
            cursor: se-resize;
            border-radius: 50%;
            z-index: 21;
        }
        .designer-element.active .resize-handle {
            background: #16a34a;
        }
        
        /* Company info text */
        .preview-company-title { font-weight: bold; font-size: 10px; color: #c2410c; }
        .preview-company-body { font-size: 7px; color: #666; line-height: 1.2; }
        
        /* Dummy elements visuals */
        .preview-title-box { display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 11px; text-transform: uppercase; border-bottom: 2px solid #c2410c; }
        .preview-dummy-box { border: 1px solid #e2e8f0; background: #f8fafc; border-radius: 3px; font-size: 7px; padding: 4px; color: #64748b; font-weight: bold; }
        .preview-table-box { width: 100%; border: 1px solid #e2e8f0; font-size: 7px; }
        .preview-table-box th { background: #f1f5f9; padding: 3px; border-bottom: 1px solid #cbd5e1; }
        .preview-table-box td { padding: 4px; border-bottom: 1px solid #f1f5f9; }
        .preview-signature-box { display: flex; flex-direction: column; align-items: center; justify-content: space-between; height: 100%; font-size: 8px; text-align: center; }
        .preview-signature-name { font-weight: bold; text-decoration: underline; margin-top: 5px; }
    </style>

    <!-- Toolbar visualizer -->
    <div style="width: 100%; display: flex; justify-content: space-between; background: #1e293b; color: #fff; padding: 8px 15px; border-radius: 6px; margin-bottom: 15px; font-size: 11px; align-items: center; box-sizing: border-box;">
        <div>
            Kertas: <span style="font-weight: bold; color: #f97316;" x-text="paperSize.toUpperCase() + ' (' + orientation.toUpperCase() + ')'"></span>
        </div>
        <div style="font-size: 10px; color: #94a3b8;">
            * Geser untuk memindahkan | Seret bulatan pojok kanan bawah untuk me-resize | Pilih elemen untuk mengunci
        </div>
    </div>

    <!-- Page Wrapper Canvas A4 -->
    <div class="canvas-container" 
         x-ref="canvas"
         :style="'width: ' + getCanvasWidth() + 'px; height: ' + getCanvasHeight() + 'px;'">
        
        <!-- Margins lines display -->
        <div class="margin-indicator"
             :style="'left: ' + toPx(margins.left || 15) + 'px; ' +
                     'top: ' + toPx(margins.top || 15) + 'px; ' +
                     'width: ' + (getCanvasWidth() - toPx(margins.left || 15) - toPx(margins.right || 15)) + 'px; ' +
                     'height: ' + (getCanvasHeight() - toPx(margins.top || 15) - toPx(margins.bottom || 15)) + 'px;'">
        </div>

        <!-- 1. Logo element -->
        <template x-if="elements.logo && elements.logo.visible">
            <div class="designer-element"
                 :class="activeElement === 'logo' ? 'active' : ''"
                 @mousedown.stop="startDrag('logo', $event)"
                 @touchstart.stop="startDrag('logo', $event)"
                 :style="'left: ' + toPx(logoX) + 'px; top: ' + toPx(logoY) + 'px; width: ' + toPx(logoW) + 'px; height: ' + toPx(logoH) + 'px;'">
                <div class="element-label">Logo</div>
                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #fafafa; overflow: hidden; pointer-events: none;">
                    <template x-if="logoPath">
                        <img :src="getImageUrl(logoPath)" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                    </template>
                    <template x-if="!logoPath">
                        <span style="font-size: 8px; color: #94a3b8; text-align: center;">Logo Indoroster</span>
                    </template>
                </div>
                <div class="resize-handle" @mousedown.stop="startResize('logo', $event)" @touchstart.stop="startResize('logo', $event)"></div>
            </div>
        </template>

        <!-- 2. Company Info element -->
        <template x-if="elements.company_info && elements.company_info.visible">
            <div class="designer-element"
                 :class="activeElement === 'company_info' ? 'active' : ''"
                 @mousedown.stop="startDrag('company_info', $event)"
                 @touchstart.stop="startDrag('company_info', $event)"
                 :style="'left: ' + toPx(elements.company_info.x) + 'px; top: ' + toPx(elements.company_info.y) + 'px; width: ' + toPx(elements.company_info.width) + 'px; height: ' + toPx(elements.company_info.height) + 'px;'">
                <div class="element-label">Info Perusahaan</div>
                <div style="width: 100%; height: 100%; padding: 4px; pointer-events: none; text-align: right; overflow: hidden;">
                    <div class="preview-company-title" x-text="companyName || 'INDOROSTER INDONESIA'"></div>
                    <div class="preview-company-body" x-text="(companyAddress || 'Kp. Cicadas, Purwakarta') + '\nWA: ' + (companyPhone || '-')"></div>
                </div>
                <div class="resize-handle" @mousedown.stop="startResize('company_info', $event)" @touchstart.stop="startResize('company_info', $event)"></div>
            </div>
        </template>

        <!-- 3. Document Title element -->
        <template x-if="elements.document_title && elements.document_title.visible">
            <div class="designer-element"
                 :class="activeElement === 'document_title' ? 'active' : ''"
                 @mousedown.stop="startDrag('document_title', $event)"
                 @touchstart.stop="startDrag('document_title', $event)"
                 :style="'left: ' + toPx(elements.document_title.x) + 'px; top: ' + toPx(elements.document_title.y) + 'px; width: ' + toPx(elements.document_title.width) + 'px; height: ' + toPx(elements.document_title.height) + 'px;'">
                <div class="element-label">Judul Dokumen</div>
                <div class="preview-title-box" style="width: 100%; height: 100%; pointer-events: none;" x-text="getDocTitleLabel()"></div>
                <div class="resize-handle" @mousedown.stop="startResize('document_title', $event)" @touchstart.stop="startResize('document_title', $event)"></div>
            </div>
        </template>

        <!-- 4. Metadata table element -->
        <template x-if="elements.metadata_table && elements.metadata_table.visible">
            <div class="designer-element"
                 :class="activeElement === 'metadata_table' ? 'active' : ''"
                 @mousedown.stop="startDrag('metadata_table', $event)"
                 @touchstart.stop="startDrag('metadata_table', $event)"
                 :style="'left: ' + toPx(elements.metadata_table.x) + 'px; top: ' + toPx(elements.metadata_table.y) + 'px; width: ' + toPx(elements.metadata_table.width) + 'px; height: ' + toPx(elements.metadata_table.height) + 'px;'">
                <div class="element-label">Nomor & Tanggal</div>
                <div class="preview-dummy-box" style="width: 100%; height: 100%; pointer-events: none;">
                    No: <span x-text="documentNumber || 'QTN-20260823-001'"></span><br>
                    Tgl: 23 Agu 2026
                </div>
                <div class="resize-handle" @mousedown.stop="startResize('metadata_table', $event)" @touchstart.stop="startResize('metadata_table', $event)"></div>
            </div>
        </template>

        <!-- 5. Customer info element -->
        <template x-if="elements.customer_info && elements.customer_info.visible">
            <div class="designer-element"
                 :class="activeElement === 'customer_info' ? 'active' : ''"
                 @mousedown.stop="startDrag('customer_info', $event)"
                 @touchstart.stop="startDrag('customer_info', $event)"
                 :style="'left: ' + toPx(elements.customer_info.x) + 'px; top: ' + toPx(elements.customer_info.y) + 'px; width: ' + toPx(elements.customer_info.width) + 'px; height: ' + toPx(elements.customer_info.height) + 'px;'">
                <div class="element-label">Info Pelanggan</div>
                <div class="preview-dummy-box" style="width: 100%; height: 100%; pointer-events: none;">
                    Kepada Yth:<br>
                    <strong x-text="clientName || 'Nama Customer'"></strong>
                </div>
                <div class="resize-handle" @mousedown.stop="startResize('customer_info', $event)" @touchstart.stop="startResize('customer_info', $event)"></div>
            </div>
        </template>

        <!-- 6. Intro text element -->
        <template x-if="elements.intro_text && elements.intro_text.visible">
            <div class="designer-element"
                 :class="activeElement === 'intro_text' ? 'active' : ''"
                 @mousedown.stop="startDrag('intro_text', $event)"
                 @touchstart.stop="startDrag('intro_text', $event)"
                 :style="'left: ' + toPx(elements.intro_text.x) + 'px; top: ' + toPx(elements.intro_text.y) + 'px; width: ' + toPx(elements.intro_text.width) + 'px; height: ' + toPx(elements.intro_text.height) + 'px;'">
                <div class="element-label">Teks Pengantar</div>
                <div style="width: 100%; height: 100%; border: 1.5px dashed #e2e8f0; font-size: 8px; padding: 4px; pointer-events: none; color: #94a3b8; overflow: hidden;">
                    Dengan hormat, bersama surat ini kami ajukan penawaran...
                </div>
                <div class="resize-handle" @mousedown.stop="startResize('intro_text', $event)" @touchstart.stop="startResize('intro_text', $event)"></div>
            </div>
        </template>

        <!-- 7. Product table element -->
        <template x-if="elements.product_table && elements.product_table.visible">
            <div class="designer-element"
                 :class="activeElement === 'product_table' ? 'active' : ''"
                 @mousedown.stop="startDrag('product_table', $event)"
                 @touchstart.stop="startDrag('product_table', $event)"
                  :style="'left: ' + toPx(elements.product_table.x) + 'px; top: ' + toPx(elements.product_table.y) + 'px; width: ' + toPx(elements.product_table.width) + 'px; height: ' + toPx(getProductTableHeight()) + 'px;'">
                <div class="element-label">Tabel Produk</div>
                <div style="width: 100%; height: 100%; background: #fff; pointer-events: none; overflow: hidden;">
                    <table class="preview-table-box">
                        <thead>
                            <tr>
                                <th style="width: 8%">No</th>
                                <th style="text-align: left;">Produk / Deskripsi</th>
                                <th style="width: 10%">Qty</th>
                                <th style="width: 15%">Harga</th>
                                <th style="width: 18%">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, index) in getDisplayItems()" :key="index">
                                <tr>
                                    <td x-text="index + 1" style="text-align: center;"></td>
                                    <td style="text-align: left;">
                                        <div style="font-weight: bold;" x-text="item.product_name"></div>
                                        <template x-if="item.sku || item.dimensions || item.variant_name">
                                            <div style="font-size: 6px; color: #64748b; margin-top: 1px;" x-text="(item.sku ? 'SKU: ' + item.sku : '') + (item.dimensions ? ' | Ukuran: ' + item.dimensions : '') + (item.variant_name ? ' | Varian: ' + item.variant_name : '')"></div>
                                        </template>
                                    </td>
                                    <td x-text="item.quantity" style="text-align: center;"></td>
                                    <td x-text="'Rp' + formatNumber(item.price)" style="text-align: right;"></td>
                                    <td x-text="'Rp' + formatNumber(item.total)" style="text-align: right;"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <div class="resize-handle" @mousedown.stop="startResize('product_table', $event)" @touchstart.stop="startResize('product_table', $event)"></div>
            </div>
        </template>

        <!-- 8. Terms Box element -->
        <template x-if="elements.terms_box && elements.terms_box.visible">
            <div class="designer-element"
                 :class="activeElement === 'terms_box' ? 'active' : ''"
                 @mousedown.stop="startDrag('terms_box', $event)"
                 @touchstart.stop="startDrag('terms_box', $event)"
                  :style="'left: ' + toPx(elements.terms_box.x) + 'px; top: ' + toPx(getTermsBoxTop()) + 'px; width: ' + toPx(elements.terms_box.width) + 'px; height: ' + toPx(elements.terms_box.height) + 'px;'">
                <div class="element-label">Ketentuan & Syarat</div>
                <div class="preview-dummy-box" style="width: 100%; height: 100%; pointer-events: none; overflow: hidden; font-size: 8px;">
                    <div style="font-weight: bold; border-bottom: 1px solid #e2e8f0; padding-bottom: 2px; margin-bottom: 3px;" x-text="getTermsTitle()"></div>
                    <div style="font-size: 7px; color: #475569; white-space: pre-line; line-height: 1.2;" x-text="getTermsText()"></div>
                </div>
                <div class="resize-handle" @mousedown.stop="startResize('terms_box', $event)" @touchstart.stop="startResize('terms_box', $event)"></div>
            </div>
        </template>

        <!-- Dynamic Extra Custom Sections -->
        <template x-if="customSections">
            <template x-for="(sec, idx) in customSections" :key="idx">
                <div class="designer-element"
                     :class="activeElement === ('custom_section_' + idx) ? 'active' : ''"
                     @mousedown.stop="startDrag('custom_section_' + idx, $event)"
                     @touchstart.stop="startDrag('custom_section_' + idx, $event)"
                     :style="'left: ' + toPx(elements['custom_section_' + idx] ? elements['custom_section_' + idx].x : 15) + 'px; ' +
                             'top: ' + toPx(getCustomSectionTop(idx)) + 'px; ' +
                             'width: ' + toPx(elements['custom_section_' + idx] ? elements['custom_section_' + idx].width : 180) + 'px; ' +
                             'height: ' + toPx(elements['custom_section_' + idx] ? elements['custom_section_' + idx].height : 30) + 'px;'">
                    <div class="element-label" style="background: #10b981;" x-text="sec.title || ('Bagian #' + (idx + 1))"></div>
                    <div class="preview-dummy-box" style="width: 100%; height: 100%; pointer-events: none; overflow: hidden; font-size: 8px;">
                        <div style="font-weight: bold; border-bottom: 1px solid #e2e8f0; padding-bottom: 2px; margin-bottom: 3px;" x-text="sec.title"></div>
                        <div style="font-size: 7px; color: #475569; white-space: pre-line; line-height: 1.2;" x-text="sec.content"></div>
                    </div>
                    <div class="resize-handle" style="background: #10b981;" @mousedown.stop="startResize('custom_section_' + idx, $event)" @touchstart.stop="startResize('custom_section_' + idx, $event)"></div>
                </div>
            </template>
        </template>

        <!-- 9. Financial Summary element -->
        <template x-if="elements.financial_summary && elements.financial_summary.visible">
            <div class="designer-element"
                 :class="activeElement === 'financial_summary' ? 'active' : ''"
                 @mousedown.stop="startDrag('financial_summary', $event)"
                 @touchstart.stop="startDrag('financial_summary', $event)"
                 :style="'left: ' + toPx(elements.financial_summary.x) + 'px; top: ' + toPx(getFinancialSummaryTop()) + 'px; width: ' + toPx(elements.financial_summary.width) + 'px; height: ' + toPx(elements.financial_summary.height) + 'px;'">
                <div class="element-label">Ringkasan Biaya</div>
                <div class="preview-dummy-box" style="width: 100%; height: 100%; pointer-events: none; text-align: right;">
                    Subtotal: <span x-text="'Rp' + formatNumber(subtotal)"></span><br>
                    Diskon: <span x-text="'Rp' + formatNumber(discount)"></span><br>
                    Total: <span x-text="'Rp' + formatNumber(grandTotal)"></span>
                </div>
                <div class="resize-handle" @mousedown.stop="startResize('financial_summary', $event)" @touchstart.stop="startResize('financial_summary', $event)"></div>
            </div>
        </template>

        <!-- 10. Signature block element (with absolute signature and stamp overlays) -->
        <template x-if="elements.signature_block && elements.signature_block.visible">
            <div class="designer-element"
                 :class="activeElement === 'signature_block' ? 'active' : ''"
                 @mousedown.stop="startDrag('signature_block', $event)"
                 @touchstart.stop="startDrag('signature_block', $event)"
                 :style="'left: ' + toPx(elements.signature_block.x) + 'px; top: ' + toPx(elements.signature_block.y) + 'px; width: ' + toPx(elements.signature_block.width) + 'px; height: ' + toPx(elements.signature_block.height) + 'px;'">
                <div class="element-label">Blok TTD Pengirim</div>
                <div class="preview-signature-box" style="width: 100%; height: 100%; pointer-events: none; padding: 4px;">
                    <div>Hormat kami,<br><strong x-text="companyName || 'Indoroster'"></strong></div>
                    
                    <div style="margin-top: 15px;">
                        <div class="preview-signature-name" x-text="signerName || 'Penanggung Jawab'"></div>
                        <div style="font-size: 7px; color: #64748b;" x-text="signerPosition || 'Authorized Signatory'"></div>
                    </div>
                </div>
                <div class="resize-handle" @mousedown.stop="startResize('signature_block', $event)" @touchstart.stop="startResize('signature_block', $event)"></div>
            </div>
        </template>

        <!-- 11. Recipient sign element -->
        <template x-if="elements.recipient_sign_block && elements.recipient_sign_block.visible">
            <div class="designer-element"
                 :class="activeElement === 'recipient_sign_block' ? 'active' : ''"
                 @mousedown.stop="startDrag('recipient_sign_block', $event)"
                 @touchstart.stop="startDrag('recipient_sign_block', $event)"
                 :style="'left: ' + toPx(elements.recipient_sign_block.x) + 'px; top: ' + toPx(elements.recipient_sign_block.y) + 'px; width: ' + toPx(elements.recipient_sign_block.width) + 'px; height: ' + toPx(elements.recipient_sign_block.height) + 'px;'">
                <div class="element-label">TTD Penerima</div>
                <div class="preview-signature-box" style="width: 100%; height: 100%; pointer-events: none; padding: 4px;">
                    <div>Disetujui Oleh,<br>Klien / Penerima</div>
                    <div>
                        <div style="font-weight: bold; margin-top: 5px;">( ___________________ )</div>
                        <div style="font-size: 7px; color: #64748b;">Tanda Tangan & Nama Terang</div>
                    </div>
                </div>
                <div class="resize-handle" @mousedown.stop="startResize('recipient_sign_block', $event)" @touchstart.stop="startResize('recipient_sign_block', $event)"></div>
            </div>
        </template>

        <!-- 12. Footer element -->
        <template x-if="elements.footer && elements.footer.visible">
            <div class="designer-element"
                 :class="activeElement === 'footer' ? 'active' : ''"
                 @mousedown.stop="startDrag('footer', $event)"
                 @touchstart.stop="startDrag('footer', $event)"
                 :style="'left: ' + toPx(elements.footer.x) + 'px; top: ' + toPx(elements.footer.y) + 'px; width: ' + toPx(elements.footer.width) + 'px; height: ' + toPx(elements.footer.height) + 'px;'">
                <div class="element-label">Footer</div>
                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 8px; color: #cbd5e1; font-weight: bold; pointer-events: none; border-top: 1.5px dashed #cbd5e1;">
                    Footer: Halaman 1 dari 1
                </div>
                <div class="resize-handle" @mousedown.stop="startResize('footer', $event)" @touchstart.stop="startResize('footer', $event)"></div>
            </div>
        </template>

        <!-- 13. Loose Signature asset handle for drag and drop placement relative to canvas -->
        <template x-if="sigPath || templateSigPath">
            <div class="designer-element"
                 :class="activeElement === 'signature' ? 'active' : ''"
                 @mousedown.stop="startDrag('signature', $event)"
                 @touchstart.stop="startDrag('signature', $event)"
                 :style="'left: ' + toPx(sigX) + 'px; top: ' + toPx(sigY) + 'px; width: ' + toPx(sigW) + 'px; height: ' + toPx(sigH) + 'px; z-index: 13; border: 1.5px solid #a855f7;'">
                <div class="element-label" style="background: #a855f7;">TTD Image</div>
                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; pointer-events: none;">
                    <img :src="getImageUrl(sigPath || templateSigPath)" style="max-width: 100%; max-height: 100%; object-fit: contain; mix-blend-mode: multiply;">
                </div>
                <div class="resize-handle" style="background: #a855f7;" @mousedown.stop="startResize('signature', $event)" @touchstart.stop="startResize('signature', $event)"></div>
            </div>
        </template>

        <!-- 14. Loose Stamp asset handle for drag and drop placement relative to canvas -->
        <template x-if="stampPath">
            <div class="designer-element"
                 :class="activeElement === 'stamp' ? 'active' : ''"
                 @mousedown.stop="startDrag('stamp', $event)"
                 @touchstart.stop="startDrag('stamp', $event)"
                 :style="'left: ' + toPx(stampX) + 'px; top: ' + toPx(stampY) + 'px; width: ' + toPx(stampW) + 'px; height: ' + toPx(stampH) + 'px; z-index: 12; opacity: ' + stampOpacity + '; transform: rotate(' + stampRotation + 'deg); border: 1.5px solid #f97316;'">
                <div class="element-label" style="background: #f97316;">Stempel Image</div>
                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; pointer-events: none;">
                    <img :src="getImageUrl(stampPath)" style="max-width: 100%; max-height: 100%; object-fit: contain; mix-blend-mode: multiply;">
                </div>
                <div class="resize-handle" style="background: #f97316;" @mousedown.stop="startResize('stamp', $event)" @touchstart.stop="startResize('stamp', $event)"></div>
            </div>
        </template>

        <!-- Header Divider Line (Kop Surat Line) -->
        <div style="position: absolute; border-bottom: 2px solid #c2410c; pointer-events: none; z-index: 2;"
             :style="'left: ' + toPx(margins.left || 15) + 'px; ' +
                     'width: ' + (getCanvasWidth() - toPx(margins.left || 15) - toPx(margins.right || 15)) + 'px; ' +
                     'top: ' + toPx(Math.max(parseFloat(logoY || 15) + parseFloat(logoH || 25), parseFloat(elements.company_info.y || 15) + parseFloat(elements.company_info.height || 25)) + 1) + 'px;'"></div>

    </div>

    <!-- Alpine.js Visual Coordinates sync logic -->
    <script>
        function documentDesigner(data) {
            return {
                orientation: data.orientation || 'portrait',
                paperSize: data.paperSize || 'a4',
                margins: data.margins || { top: 15, bottom: 15, left: 15, right: 15 },
                
                logoX: data.logoX || 15,
                logoY: data.logoY || 15,
                logoW: data.logoW || 50,
                logoH: data.logoH || 25,
                
                sigX: data.sigX || 140,
                sigY: data.sigY || 220,
                sigW: data.sigW || 40,
                sigH: data.sigH || 20,
                
                stampX: data.stampX || 130,
                stampY: data.stampY || 215,
                stampW: data.stampW || 35,
                stampH: data.stampH || 35,
                stampOpacity: data.stampOpacity || 0.80,
                stampRotation: data.stampRotation || 0,
                
                elements: data.elements || {},
                logoPath: data.logoPath || '',
                sigPath: data.sigPath || '',
                templateSigPath: data.templateSigPath || '',
                stampPath: data.stampPath || '',
                companyName: data.companyName || 'Indoroster',
                companyAddress: data.companyAddress || '',
                companyPhone: data.companyPhone || '',
                companyEmail: data.companyEmail || '',
                signerName: data.signerName || '',
                signerPosition: data.signerPosition || '',
                customSections: data.customSections || [],
                customPaymentInstructionsTitle: data.customPaymentInstructionsTitle || '',
                customPaymentInstructions: data.customPaymentInstructions || '',
                customDeliveryNotesTitle: data.customDeliveryNotesTitle || '',
                customDeliveryNotes: data.customDeliveryNotes || '',
                customReceiptNotesTitle: data.customReceiptNotesTitle || '',
                customReceiptNotes: data.customReceiptNotes || '',
                customTermsTitle: data.customTermsTitle || '',
                customTermsAndConditions: data.customTermsAndConditions || '',
                customOrderNotesTitle: data.customOrderNotesTitle || '',
                customOrderNotes: data.customOrderNotes || '',
                type: data.type || '',

                clientName: data.clientName || 'Nama Klien / Instansi',
                grandTotal: data.grandTotal || 1500000,
                subtotal: data.subtotal || 1500000,
                discount: data.discount || 0,
                taxAmount: data.taxAmount || 0,
                documentNumber: data.documentNumber || '',
                items: data.items || [],

                // State tracker
                activeElement: null,
                isDragging: false,
                isResizing: false,
                dragStart: { x: 0, y: 0 },
                elemStart: { x: 0, y: 0, w: 0, h: 0 },

                formatNumber(val) {
                    if (!val) return '0';
                    return parseFloat(val).toLocaleString('id-ID');
                },

                getDisplayItems() {
                    if (this.items && this.items.length > 0) {
                        let validItems = this.items.filter(item => item.product_name || item.product_id);
                        if (validItems.length > 0) {
                            return validItems.map(item => ({
                                product_name: item.product_name || 'Memuat...',
                                quantity: item.quantity || 1,
                                price: item.price || 0,
                                total: item.total || (item.quantity * item.price) || 0,
                                sku: item.sku || '',
                                dimensions: item.dimensions || '',
                                variant_name: item.variant_name || ''
                            }));
                        }
                    }
                    return [{
                        product_name: 'Roster Beton Motif Loster Abu-Abu (Contoh)',
                        quantity: 100,
                        price: 15000,
                        total: 1500000,
                        sku: 'RST-BTA-001',
                        dimensions: '20 x 20 x 10 cm',
                        variant_name: 'Abu-Abu'
                    }];
                },

                getProductTableHeight() {
                    let itemsCount = this.getDisplayItems().length;
                    return 12 + (itemsCount * 8);
                },

                getFinancialSummaryTop() {
                    return this.elements.product_table ? (this.elements.product_table.y + this.getProductTableHeight() + 3) : 180;
                },

                getFinancialSummaryHeight() {
                    return this.elements.financial_summary ? (this.elements.financial_summary.height || 20) : 20;
                },

                getTermsBoxTop() {
                    return this.getFinancialSummaryTop() + this.getFinancialSummaryHeight() + 5;
                },

                getCustomSectionTop(idx) {
                    let runningY = this.getTermsBoxTop() + (this.elements.terms_box ? (this.elements.terms_box.height || 30) : 30) + 4;
                    for (let i = 0; i < idx; i++) {
                        let prevKey = 'custom_section_' + i;
                        let prevH = this.elements[prevKey] ? (this.elements[prevKey].height || 30) : 30;
                        runningY += prevH + 4;
                    }
                    return runningY;
                },

                getTermsTitle() {
                    let t = this.type;
                    if (['faktur', 'invoice', 'proforma_invoice', 'supplier_invoice', 'commercial_invoice'].includes(t)) {
                        return this.customPaymentInstructionsTitle || 'Petunjuk Pembayaran';
                    } else if (['surat_jalan', 'delivery_note', 'packing_list', 'goods_receipt', 'export_packing_list', 'shipping_instruction'].includes(t)) {
                        return this.customDeliveryNotesTitle || 'Catatan Pengiriman';
                    } else if (['kwitansi', 'receipt', 'customer_statement'].includes(t)) {
                        return this.customReceiptNotesTitle || 'Keterangan Tambahan';
                    } else if (['penawaran', 'quotation', 'purchase_order', 'certificate_of_origin'].includes(t)) {
                        return this.customTermsTitle || 'Syarat & Ketentuan';
                    } else if (['surat_pesanan', 'sales_order'].includes(t)) {
                        return this.customOrderNotesTitle || 'Catatan Alur Pesanan';
                    }
                    return 'Syarat & Ketentuan';
                },

                getTermsText() {
                    let t = this.type;
                    if (['faktur', 'invoice', 'proforma_invoice', 'supplier_invoice', 'commercial_invoice'].includes(t)) {
                        return this.customPaymentInstructions || '';
                    } else if (['surat_jalan', 'delivery_note', 'packing_list', 'goods_receipt', 'export_packing_list', 'shipping_instruction'].includes(t)) {
                        return this.customDeliveryNotes || '';
                    } else if (['kwitansi', 'receipt', 'customer_statement'].includes(t)) {
                        return this.customReceiptNotes || '';
                    } else if (['penawaran', 'quotation', 'purchase_order', 'certificate_of_origin'].includes(t)) {
                        return this.customTermsAndConditions || '';
                    } else if (['surat_pesanan', 'sales_order'].includes(t)) {
                        return this.customOrderNotes || '';
                    }
                    return '';
                },

                init() {
                    // Force state listener
                    this.$watch('orientation', (val) => { this.orientation = val; });
                    this.$watch('paperSize', (val) => { this.paperSize = val; });
                    this.$watch('logoX', (val) => { this.logoX = val; });
                    this.$watch('logoY', (val) => { this.logoY = val; });
                    this.$watch('logoW', (val) => { this.logoW = val; });
                    this.$watch('logoH', (val) => { this.logoH = val; });
                    this.$watch('sigX', (val) => { this.sigX = val; });
                    this.$watch('sigY', (val) => { this.sigY = val; });
                    this.$watch('sigW', (val) => { this.sigW = val; });
                    this.$watch('sigH', (val) => { this.sigH = val; });
                    this.$watch('stampX', (val) => { this.stampX = val; });
                    this.$watch('stampY', (val) => { this.stampY = val; });
                    this.$watch('stampW', (val) => { this.stampW = val; });
                    this.$watch('stampH', (val) => { this.stampH = val; });
                    this.$watch('elements', (val) => { this.elements = val; });
                    this.$watch('customSections', (val) => { this.customSections = val; });
                    this.$watch('items', (val) => { this.items = val; });

                    window.addEventListener('open-new-tab', event => {
                        window.open(event.detail.url, '_blank');
                    });
                },

                getImageUrl(path) {
                    if (!path) return '';
                    if (Array.isArray(path)) {
                        path = path[0];
                    }
                    if (typeof path === 'object' && path !== null) {
                        if (path.temporaryUrl) return path.temporaryUrl;
                        if (path.path) path = path.path;
                    }
                    if (typeof path !== 'string') return '';
                    
                    if (path.includes('livewire-file:') || path.includes('livewire-temp') || path.includes('livewire-tmp')) {
                        const filename = path.split('/').pop();
                        return '/livewire/preview-file/' + filename;
                    }
                    
                    if (path.startsWith('http') || path.startsWith('data:')) {
                        return path;
                    }
                    
                    return '/storage/' + path;
                },

                getCanvasWidth() {
                    // 1mm = roughly 2.83 pixels at scale. A4 Portrait: 210mm -> 594px.
                    return this.orientation === 'landscape' ? 840 : 594;
                },

                getCanvasHeight() {
                    return this.orientation === 'landscape' ? 594 : 840;
                },

                getMmWidth() {
                    return this.orientation === 'landscape' ? 297 : 210;
                },

                getMmHeight() {
                    return this.orientation === 'landscape' ? 210 : 297;
                },

                getScale() {
                    // scale factor: mm per pixel
                    return this.getMmWidth() / this.getCanvasWidth();
                },

                toPx(mm) {
                    return Math.round(mm / this.getScale());
                },

                toMm(px) {
                    return Math.round(px * this.getScale());
                },

                startDrag(elementKey, e) {
                    this.activeElement = elementKey;
                    this.isDragging = true;
                    this.isResizing = false;

                    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                    this.dragStart = { x: clientX, y: clientY };

                    if (['logo', 'signature', 'stamp'].includes(elementKey)) {
                        this.elemStart = {
                            x: elementKey === 'logo' ? this.logoX : (elementKey === 'signature' ? this.sigX : this.stampX),
                            y: elementKey === 'logo' ? this.logoY : (elementKey === 'signature' ? this.sigY : this.stampY)
                        };
                    } else if (this.elements[elementKey]) {
                        this.elemStart = {
                            x: this.elements[elementKey].x,
                            y: this.elements[elementKey].y
                        };
                    }
                },

                startResize(elementKey, e) {
                    this.activeElement = elementKey;
                    this.isDragging = false;
                    this.isResizing = true;

                    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                    this.dragStart = { x: clientX, y: clientY };

                    if (['logo', 'signature', 'stamp'].includes(elementKey)) {
                        this.elemStart = {
                            w: elementKey === 'logo' ? this.logoW : (elementKey === 'signature' ? this.sigW : this.stampW),
                            h: elementKey === 'logo' ? this.logoH : (elementKey === 'signature' ? this.sigH : this.stampH)
                        };
                    } else if (this.elements[elementKey]) {
                        this.elemStart = {
                            w: this.elements[elementKey].width,
                            h: this.elements[elementKey].height
                        };
                    }
                },

                onDrag(e) {
                    if (!this.isDragging && !this.isResizing) return;

                    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                    
                    const deltaXpx = clientX - this.dragStart.x;
                    const deltaYpx = clientY - this.dragStart.y;
                    
                    const deltaXmm = this.toMm(deltaXpx);
                    const deltaYmm = this.toMm(deltaYpx);

                    const maxW = this.getMmWidth();
                    const maxH = this.getMmHeight();

                    if (this.isDragging) {
                        const newX = Math.max(0, Math.min(maxW, this.elemStart.x + deltaXmm));
                        const newY = Math.max(0, Math.min(maxH, this.elemStart.y + deltaYmm));

                        if (this.activeElement === 'logo') {
                            this.logoX = newX;
                            this.logoY = newY;
                        } else if (this.activeElement === 'signature') {
                            this.sigX = newX;
                            this.sigY = newY;
                        } else if (this.activeElement === 'stamp') {
                            this.stampX = newX;
                            this.stampY = newY;
                        } else if (this.elements[this.activeElement]) {
                            this.elements[this.activeElement].x = newX;
                            this.elements[this.activeElement].y = newY;
                        }
                    } else if (this.isResizing) {
                        const newW = Math.max(10, Math.min(maxW, this.elemStart.w + deltaXmm));
                        const newH = Math.max(5, Math.min(maxH, this.elemStart.h + deltaYmm));

                        if (this.activeElement === 'logo') {
                            this.logoW = newW;
                            this.logoH = newH;
                        } else if (this.activeElement === 'signature') {
                            this.sigW = newW;
                            this.sigH = newH;
                        } else if (this.activeElement === 'stamp') {
                            this.stampW = newW;
                            this.stampH = newH;
                        } else if (this.elements[this.activeElement]) {
                            this.elements[this.activeElement].width = newW;
                            this.elements[this.activeElement].height = newH;
                        }
                    }
                },

                stopDrag() {
                    this.isDragging = false;
                    this.isResizing = false;
                },

                getDocTitleLabel() {
                    const titles = {
                        quotation: 'Surat Penawaran Harga',
                        sales_order: 'Surat Pesanan (Sales Order)',
                        invoice: 'Faktur Penjualan (Invoice)',
                        receipt: 'Kwitansi Pembayaran',
                        proforma_invoice: 'Proforma Invoice',
                        surat_jalan: 'Surat Jalan Pengiriman',
                        delivery_note: 'Delivery Note',
                        packing_list: 'Packing List',
                        purchase_order: 'Purchase Order',
                        goods_receipt: 'Goods Receipt',
                        supplier_invoice: 'Supplier Invoice',
                        customer_statement: 'Customer Statement',
                    };
                    const typeSelect = document.getElementById('data.type');
                    const activeType = typeSelect ? typeSelect.value : 'invoice';
                    return titles[activeType] || 'Dokumen Bisnis';
                }
            };
        }
    </script>
</div>
