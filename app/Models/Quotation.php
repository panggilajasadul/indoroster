<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_number',
        'user_id',
        'contact_name',
        'company_name',
        'job_title',
        'phone',
        'email',
        'project_type',
        'project_location_city',
        'project_address',
        'deadline_date',
        'notes',
        'attachment_path',
        'status',
        'estimated_total',
        'handled_by',
    ];

    protected $casts = [
        'deadline_date' => 'date',
        'estimated_total' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->quotation_number)) {
                $prefix = 'QTN-'.date('Ym').'-';
                $last = self::where('quotation_number', 'like', $prefix.'%')->orderByDesc('id')->first();
                $seq = 1;
                if ($last && preg_match('/-(\d+)$/', $last->quotation_number, $matches)) {
                    $seq = (int) $matches[1] + 1;
                }
                $model->quotation_number = $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
