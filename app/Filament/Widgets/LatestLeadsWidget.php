<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Leads\LeadResource;
use App\Models\Lead;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestLeadsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Lead::query()->latest()->limit(5))
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('email'),
                TextColumn::make('project_type')->label('Project'),
                TextColumn::make('score')
                    ->badge()
                    ->color(fn (Lead $record): string => match ($record->quality) {
                        'caliente' => 'success',
                        'medio' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')->dateTime()->since(),
            ])
            ->recordUrl(fn (Lead $record): string => LeadResource::getUrl('view', ['record' => $record]));
    }

    protected function getTableHeading(): ?string
    {
        return 'Latest leads';
    }
}
