<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LeadsStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $thisMonth = Lead::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $hotLeads = Lead::where('score', '>=', 10)->count();

        $total = Lead::count();
        $won = Lead::whereIn('status', ['won', 'convertido'])->count();
        $conversion = $total > 0 ? round(($won / $total) * 100, 1) : 0;

        return [
            Stat::make('Leads this month', $thisMonth)
                ->description('New leads in ' . now()->translatedFormat('F'))
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('primary'),

            Stat::make('Hot leads (score ≥10)', $hotLeads)
                ->description('High-intent leads')
                ->descriptionIcon('heroicon-m-fire')
                ->color($hotLeads > 0 ? 'success' : 'gray'),

            Stat::make('Conversion rate', $conversion . '%')
                ->description($won . ' won / ' . $total . ' total')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($conversion >= 10 ? 'success' : ($conversion >= 5 ? 'warning' : 'gray')),
        ];
    }
}
