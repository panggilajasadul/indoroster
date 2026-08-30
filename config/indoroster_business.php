<?php

/**
 * IndoRoster — Business Rules yang Sudah Terverifikasi
 *
 * Data ini HANYA boleh digunakan setelah dikonfirmasi langsung oleh pemilik.
 * Setiap perubahan harus disertai tanggal verifikasi.
 *
 * JANGAN menambahkan data baru tanpa konfirmasi eksplisit.
 */

return [

    // ─── Kapasitas Produksi ───────────────────────────────────────────────────
    'production' => [
        'capacity_pcs' => 10_000,
        'capacity_unit' => 'per_bulan',         // VERIFIED: 31 Agustus 2026
        'capacity_label' => '10.000 pcs/bulan', // Untuk display publik
        'capacity_verified_at' => '2026-08-31',
        'capacity_source' => 'owner_confirmation',

        // Narasi aman untuk konten publik
        'capacity_copy' => 'Kapasitas produksi pabrik IndoRoster mencapai 10.000 pcs per bulan.',
        'capacity_copy_soft' => 'Kapasitas produksi kami terencana dan dapat mengakomodasi kebutuhan proyek skala menengah hingga besar.',
    ],

    // ─── Minimum Order Quantity ───────────────────────────────────────────────
    'moq' => [
        // VERIFIED: 31 Agustus 2026 — berlaku untuk SEMUA motif tanpa kecuali
        'retail_pcs' => 1_000,
        'retail_label' => '1.000 pcs',
        'retail_copy' => 'Minimum pemesanan retail: 1.000 pcs (berlaku untuk semua motif)',

        'wholesale_pcs' => 5_000,
        'wholesale_label' => '5.000 pcs',
        'wholesale_copy' => 'Minimum pemesanan grosir: 5.000 pcs (berlaku untuk semua motif)',

        // Apakah ada pengecualian motif? VERIFIED: TIDAK — berlaku semua motif
        'has_motif_exceptions' => false,
        'exception_notes' => null,

        'verified_at' => '2026-08-31',
        'verified_source' => 'owner_confirmation',
    ],

    // ─── Data yang BELUM Terverifikasi ────────────────────────────────────────
    // Jangan gunakan data di bawah ini secara publik sebelum dikonfirmasi.
    'pending_verification' => [
        'shipping_routes' => [
            'status' => 'UNVERIFIED',
            'note' => 'Rute, biaya, estimasi waktu pengiriman belum dikonfirmasi. Gunakan narasi aman.',
        ],
        'price_nominal' => [
            'status' => 'UNVERIFIED',
            'note' => 'Harga per pcs tidak boleh dipublikasikan. Arahkan ke request quotation.',
        ],
    ],

    // ─── Narasi Pengiriman Aman ───────────────────────────────────────────────
    // Gunakan narasi ini sampai data pengiriman terverifikasi.
    'shipping_safe_copy' => 'Pengiriman untuk kebutuhan proyek direncanakan berdasarkan jumlah pesanan, jenis produk, lokasi tujuan, dan kebutuhan jadwal. Hubungi tim kami untuk informasi pengiriman ke lokasi Anda.',

    // ─── Narasi Harga Aman ────────────────────────────────────────────────────
    // Jangan cantumkan harga nominal — gunakan narasi ini.
    'pricing_safe_copy' => 'Harga roster beton disesuaikan berdasarkan jenis motif, ketebalan, jumlah pemesanan, dan lokasi pengiriman. Kirimkan kebutuhan proyek Anda untuk mendapatkan penawaran yang sesuai.',

];
