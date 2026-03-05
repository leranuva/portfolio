<?php

namespace App\Services;

class LeadScoringService
{
    public function calculateScore(array $data): int
    {
        $score = 0;

        // Presupuesto: alto +4, medio +2, bajo +1
        $score += match ($data['budget_range'] ?? null) {
            'alto' => 4,
            'medio' => 2,
            'bajo' => 1,
            default => 0,
        };

        // Urgencia: inmediato +3, pronto +2, flexible +1
        $score += match ($data['urgency'] ?? null) {
            'inmediato' => 3,
            'pronto' => 2,
            'flexible' => 1,
            default => 0,
        };

        // Tipo de proyecto: automatización +5, web a medida +3, freelance +2
        $projectType = strtolower($data['project_type'] ?? '');
        if (str_contains($projectType, 'automatización') || str_contains($projectType, 'automatizacion')) {
            $score += 5;
        } elseif (str_contains($projectType, 'web') || str_contains($projectType, 'sistema')) {
            $score += 3;
        } elseif (str_contains($projectType, 'freelance') || str_contains($projectType, 'retainer')) {
            $score += 2;
        }

        // Qué automatizar: si menciona procesos, integraciones, APIs
        $whatToAutomate = strtolower($data['what_to_automate'] ?? '');
        if (str_contains($whatToAutomate, 'proceso') || str_contains($whatToAutomate, 'api') || str_contains($whatToAutomate, 'integracion')) {
            $score += 2;
        }

        return min($score, 15);
    }
}
