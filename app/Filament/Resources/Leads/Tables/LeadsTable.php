<?php

namespace App\Filament\Resources\Leads\Tables;

use App\Models\Lead;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LeadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('project_type')
                    ->label('Project type')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('budget_range')
                    ->label('Budget')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                TextColumn::make('urgency')
                    ->label('Urgency')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                TextColumn::make('score')
                    ->label('Score')
                    ->badge()
                    ->color(fn (Lead $record): string => match ($record->quality) {
                        'caliente' => 'success',
                        'medio' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('quality_label')
                    ->label('Quality')
                    ->badge()
                    ->color(fn (Lead $record): string => match ($record->quality) {
                        'caliente' => 'success',
                        'medio' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state)))
                    ->color(fn (string $state): string => match ($state) {
                        'convertido' => 'success',
                        'en_contacto' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'nuevo' => 'New',
                        'en_contacto' => 'In contact',
                        'convertido' => 'Converted',
                    ]),
                SelectFilter::make('quality')
                    ->options([
                        'caliente' => 'Hot (9+)',
                        'medio' => 'Medium (5-8)',
                        'frio' => 'Cold (0-4)',
                    ])
                    ->query(function ($query, array $data): void {
                        $value = $data['value'] ?? null;
                        if (blank($value)) {
                            return;
                        }
                        match ($value) {
                            'caliente' => $query->where('score', '>=', 9),
                            'medio' => $query->whereBetween('score', [5, 8]),
                            'frio' => $query->where('score', '<=', 4),
                            default => null,
                        };
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
