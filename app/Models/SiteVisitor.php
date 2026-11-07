<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteVisitor extends Model
{
    protected $fillable = [
        'session_id',
        'ip_address',
        'country_code',
        'country_name',
        'user_agent',
        'visit_date',
        'hits',
        'last_activity_at',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'last_activity_at' => 'datetime',
    ];
}
