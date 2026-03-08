<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadEvent extends Model
{
    public const TYPE_LEAD_CREATED = 'lead_created';

    public const TYPE_EMAIL_SENT = 'email_sent';

    public const TYPE_FOLLOWUP_SENT = 'followup_sent';

    public const TYPE_MEETING_BOOKED = 'meeting_booked';

    public const TYPE_PROPOSAL_SENT = 'proposal_sent';

    public const TYPE_WON = 'won';

    public $timestamps = false;

    protected $fillable = [
        'lead_id',
        'event_type',
        'metadata',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }
}
