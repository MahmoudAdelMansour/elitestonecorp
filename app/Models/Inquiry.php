<?php

namespace App\Models;

use App\Enums\InquiryStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inquiry extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['status' => InquiryStatus::class];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    /** Human-friendly reference shown to the customer, e.g. ES-26-0042. */
    public function reference(): string
    {
        return 'ES-'.$this->created_at->format('y').'-'.str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
    }
}
