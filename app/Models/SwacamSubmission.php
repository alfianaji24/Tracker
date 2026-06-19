<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SwacamSubmission extends Model
{
    protected $fillable = [
        'pelanggan_id',
        'periode',
        'meter_awal',
        'meter_reading',
        'pemakaian',
        'photo_path',
        'photo_quality',
        'blur_detected',
        'brightness_score',
        'ocr_confidence',
        'status',
        'admin_notes',
        'submitted_at',
        'approved_at'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'blur_detected' => 'boolean',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    /**
     * Get status badge
     */
    public function getStatusBadge()
    {
        $badges = [
            'submitted' => '<span class="badge badge-warning" style="background: #fef3c7; color: #b45309;">Submitted</span>',
            'approved' => '<span class="badge badge-success">Approved</span>',
            'rejected' => '<span class="badge badge-danger">Rejected</span>',
        ];

        return $badges[$this->status] ?? '';
    }

    /**
     * Get quality badge
     */
    public function getQualityBadge()
    {
        if ($this->photo_quality >= 80) {
            return '<span class="badge" style="background: #d1fae5; color: #065f46;">Excellent</span>';
        } elseif ($this->photo_quality >= 60) {
            return '<span class="badge" style="background: #dbeafe; color: #1e40af;">Good</span>';
        } elseif ($this->photo_quality >= 40) {
            return '<span class="badge" style="background: #fef3c7; color: #92400e;">Fair</span>';
        } else {
            return '<span class="badge" style="background: #fee2e2; color: #7f1d1d;">Poor</span>';
        }
    }
}
