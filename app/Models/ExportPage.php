<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportPage extends Model
{
    use HasFactory;

    protected $table = 'export_pages';

    protected $fillable = [
        'country_slug',
        'country_name',
        'flag_emoji',
        'region',
        'destination_port',
        'transit_time',
        'is_active',
        'meta_title',
        'meta_description',
        'hero_headline',
        'hero_subheadline',
        'hero_badge',
        'sections_config',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sections_config' => 'array',
    ];
}
