<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    public const STATUS_NUEVO = 'nuevo';

    public const STATUS_EN_CONTACTO = 'en_contacto';

    public const STATUS_CONVERTIDO = 'convertido';

    public const STATUS_CONTACTED = 'contacted';

    public const STATUS_MEETING_SCHEDULED = 'meeting_scheduled';

    public const STATUS_PROPOSAL_SENT = 'proposal_sent';

    public const STATUS_WON = 'won';

    public const STATUS_LOST = 'lost';

    public static function statusOptions(): array
    {
        return [
            'nuevo' => 'New',
            'contacted' => 'Contacted',
            'meeting_scheduled' => 'Meeting scheduled',
            'proposal_sent' => 'Proposal sent',
            'won' => 'Won',
            'lost' => 'Lost',
            // Legacy
            'en_contacto' => 'In contact',
            'convertido' => 'Converted',
        ];
    }

    public const QUALITY_FRIO = 'frio';

    public const QUALITY_MEDIO = 'medio';

    public const QUALITY_CALIENTE = 'caliente';

    public const SOURCE_CONTACT = 'contact';
    public const SOURCE_LEAD_MAGNET = 'lead_magnet';

    protected $fillable = [
        'name',
        'email',
        'project_type',
        'what_to_automate',
        'budget_range',
        'urgency',
        'message',
        'score',
        'status',
        'source',
        'utm_source',
        'utm_medium',
        'utm_campaign',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
        ];
    }

    public function getQualityAttribute(): string
    {
        return match (true) {
            $this->score >= 9 => self::QUALITY_CALIENTE,
            $this->score >= 5 => self::QUALITY_MEDIO,
            default => self::QUALITY_FRIO,
        };
    }

    public function getQualityLabelAttribute(): string
    {
        return match ($this->quality) {
            self::QUALITY_CALIENTE => 'Caliente',
            self::QUALITY_MEDIO => 'Medio',
            default => 'Frío',
        };
    }

    public function scopeByStatus($query, string $status): void
    {
        $query->where('status', $status);
    }

    public function scopeOrderByScore($query, string $direction = 'desc'): void
    {
        $query->orderBy('score', $direction);
    }

    public function events(): HasMany
    {
        return $this->hasMany(LeadEvent::class);
    }
}
