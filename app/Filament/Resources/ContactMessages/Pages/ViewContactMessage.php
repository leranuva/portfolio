<?php

namespace App\Filament\Resources\ContactMessages\Pages;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Models\ContactMessage;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewContactMessage extends ViewRecord
{
    protected static string $resource = ContactMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('markAsRead')
                ->label('Marcar como leído')
                ->icon('heroicon-o-check')
                ->visible(fn (ContactMessage $record): bool => ! $record->isRead())
                ->action(function (ContactMessage $record): void {
                    $record->markAsRead();
                }),
        ];
    }
}
