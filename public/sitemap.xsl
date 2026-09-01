<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" 
    xmlns:html="http://www.w3.org/TR/REC-html40"
    xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
    xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
    <xsl:template match="/">
        <html xmlns="http://www.w3.org/1999/xhtml" lang="id">
        <head>
            <title>XML Sitemap - IndoRoster Indonesia</title>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <style type="text/css">
                body {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                    font-size: 13.5px;
                    color: #334155;
                    background-color: #f8fafc;
                    margin: 0;
                    padding: 0;
                    line-height: 1.5;
                }
                .header {
                    background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
                    color: #ffffff;
                    padding: 32px 24px;
                    box-shadow: 0 4px 12px rgba(234, 88, 12, 0.15);
                }
                .container {
                    max-width: 1200px;
                    margin: 0 auto;
                    padding: 0 16px;
                }
                .header h1 {
                    margin: 0 0 8px 0;
                    font-size: 26px;
                    font-weight: 800;
                    letter-spacing: -0.5px;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }
                .header p {
                    margin: 0;
                    font-size: 13.5px;
                    opacity: 0.92;
                    max-width: 800px;
                    line-height: 1.6;
                }
                .header a {
                    color: #fef08a;
                    text-decoration: underline;
                }
                .content {
                    margin: -20px auto 40px auto;
                }
                .card {
                    background: #ffffff;
                    border-radius: 12px;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
                    border: 1px solid #e2e8f0;
                    overflow: hidden;
                }
                .stats-bar {
                    padding: 16px 24px;
                    background: #fff7ed;
                    border-bottom: 1px solid #fed7aa;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    flex-wrap: wrap;
                    gap: 12px;
                    font-size: 13px;
                    color: #9a3412;
                    font-weight: 600;
                }
                .stats-badge {
                    background: #ea580c;
                    color: #ffffff;
                    padding: 3px 10px;
                    border-radius: 9999px;
                    font-size: 12px;
                    font-weight: 700;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    text-align: left;
                }
                th {
                    background-color: #f1f5f9;
                    color: #475569;
                    font-size: 12px;
                    font-weight: 700;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    padding: 12px 20px;
                    border-bottom: 1px solid #cbd5e1;
                }
                td {
                    padding: 12px 20px;
                    border-bottom: 1px solid #f1f5f9;
                    color: #1e293b;
                }
                tr:hover {
                    background-color: #f8fafc;
                }
                tr:nth-child(even) {
                    background-color: #ffffff;
                }
                tr:nth-child(odd) {
                    background-color: #fafbfd;
                }
                a {
                    color: #0284c7;
                    text-decoration: none;
                    word-break: break-all;
                }
                a:hover {
                    color: #0369a1;
                    text-decoration: underline;
                }
                .priority-tag {
                    display: inline-block;
                    padding: 2px 8px;
                    border-radius: 6px;
                    font-size: 11.5px;
                    font-weight: 600;
                    background-color: #f1f5f9;
                    color: #475569;
                }
                .priority-high {
                    background-color: #ecfdf5;
                    color: #059669;
                    border: 1px solid #a7f3d0;
                }
                .footer {
                    text-align: center;
                    padding: 24px;
                    color: #94a3b8;
                    font-size: 12px;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="container">
                    <h1>
                        <span>IndoRoster</span>
                        <span style="font-size: 18px; font-weight: 400; opacity: 0.85;">| XML Sitemap</span>
                    </h1>
                    <p>
                        Peta situs XML resmi ini dibuat otomatis untuk mesin pencari seperti <strong>Google Search</strong>, <strong>Bing</strong>, dan <strong>Yandex</strong> agar seluruh katalog produk roster, artikel, halaman lokal, dan portal ekspor terindeks dengan optimal.
                    </p>
                </div>
            </div>

            <div class="container content">
                <div class="card">
                    <!-- CONDITIONAL VIEW: SITEMAP INDEX VS URLSET -->
                    <xsl:choose>
                        <!-- SITEMAP INDEX VIEW -->
                        <xsl:when test="/*[local-name()='sitemapindex']">
                            <div class="stats-bar">
                                <div>
                                    <span>Master Sitemap Index: </span>
                                    <span class="stats-badge"><xsl:value-of select="count(//*[local-name()='sitemap'])"/> Sub-Sitemap</span>
                                </div>
                                <div>
                                    <span>Format: </span>
                                    <span style="color: #0f172a;">Google Sitemap Index XML v0.9</span>
                                </div>
                            </div>

                            <table>
                                <thead>
                                    <tr>
                                        <th style="width: 70%;">Sub-Sitemap XML</th>
                                        <th style="width: 30%; text-align: right;">Terakhir Diperbarui</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <xsl:for-each select="//*[local-name()='sitemap']">
                                        <tr>
                                            <td>
                                                <xsl:variable name="subURL" select="*[local-name()='loc']"/>
                                                <a href="{$subURL}" target="_blank" style="font-weight: 600;">
                                                    <xsl:value-of select="*[local-name()='loc']"/>
                                                </a>
                                            </td>
                                            <td style="text-align: right; color: #64748b; font-size: 12px;">
                                                <xsl:value-of select="concat(substring(*[local-name()='lastmod'], 1, 10), ' ', substring(*[local-name()='lastmod'], 12, 5))"/>
                                            </td>
                                        </tr>
                                    </xsl:for-each>
                                </tbody>
                            </table>
                        </xsl:when>

                        <!-- URLSET VIEW -->
                        <xsl:otherwise>
                            <div class="stats-bar">
                                <div>
                                    <span>Total URL Terdaftar: </span>
                                    <span class="stats-badge"><xsl:value-of select="count(//*[local-name()='url'])"/> Halaman</span>
                                </div>
                                <div>
                                    <span>Format: </span>
                                    <span style="color: #0f172a;">Google Sitemaps XML v0.9 (Standard)</span>
                                </div>
                            </div>

                            <table>
                                <thead>
                                    <tr>
                                        <th style="width: 50%;">Alamat URL Halaman</th>
                                        <th style="width: 10%; text-align: center;">Gambar</th>
                                        <th style="width: 10%; text-align: center;">Prioritas</th>
                                        <th style="width: 12%; text-align: center;">Frekuensi</th>
                                        <th style="width: 18%; text-align: right;">Terakhir Diperbarui</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <xsl:for-each select="//*[local-name()='url']">
                                        <tr>
                                            <td>
                                                <xsl:variable name="itemURL" select="*[local-name()='loc']"/>
                                                <a href="{$itemURL}" target="_blank">
                                                    <xsl:value-of select="*[local-name()='loc']"/>
                                                </a>
                                            </td>
                                            <td style="text-align: center;">
                                                <xsl:variable name="imgCount" select="count(*[local-name()='image'])"/>
                                                <xsl:choose>
                                                    <xsl:when test="$imgCount &gt; 0">
                                                        <span style="font-weight: 700; color: #ea580c; background: #fff7ed; padding: 2px 8px; border-radius: 9999px; font-size: 11px; border: 1px solid #fed7aa;">
                                                            <xsl:value-of select="$imgCount"/>
                                                        </span>
                                                    </xsl:when>
                                                    <xsl:otherwise>
                                                        <span style="color: #94a3b8; font-size: 12px;">0</span>
                                                    </xsl:otherwise>
                                                </xsl:choose>
                                            </td>
                                            <td style="text-align: center;">
                                                <xsl:variable name="prioVal" select="*[local-name()='priority']"/>
                                                <xsl:choose>
                                                    <xsl:when test="$prioVal &gt;= 0.8">
                                                        <span class="priority-tag priority-high">
                                                            <xsl:value-of select="concat(*[local-name()='priority'] * 100, '%')"/>
                                                        </span>
                                                    </xsl:when>
                                                    <xsl:otherwise>
                                                        <span class="priority-tag">
                                                            <xsl:value-of select="concat(*[local-name()='priority'] * 100, '%')"/>
                                                        </span>
                                                    </xsl:otherwise>
                                                </xsl:choose>
                                            </td>
                                            <td style="text-align: center; color: #64748b; font-size: 12px;">
                                                <xsl:value-of select="*[local-name()='changefreq']"/>
                                            </td>
                                            <td style="text-align: right; color: #64748b; font-size: 12px;">
                                                <xsl:value-of select="concat(substring(*[local-name()='lastmod'], 1, 10), ' ', substring(*[local-name()='lastmod'], 12, 5))"/>
                                            </td>
                                        </tr>
                                    </xsl:for-each>
                                </tbody>
                            </table>
                        </xsl:otherwise>
                    </xsl:choose>
                </div>

                <div class="footer">
                    <p>
                        © 2026 IndoRoster Indonesia. Produsen Roster Beton, Loster Arsitektural, Bata Expose &amp; Eksportir Global.
                    </p>
                </div>
            </div>
        </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
