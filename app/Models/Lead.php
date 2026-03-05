<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    public const STATUS_NUEVO = 'nuevo';

    public const STATUS_EN_CONTACTO = 'en_contacto';

    public const STATUS_CONVERTIDO = 'convertido';

    public const QUALITY_FRIO = 'frio';

    public const QUALITY_MEDIO = 'medio';

    public const QUALITY_CALIENTE = 'caliente';

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
}
