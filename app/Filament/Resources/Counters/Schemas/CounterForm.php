<?php

namespace App\Filament\Resources\Counters\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CounterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('label')
                    ->required(),
                TextInput::make('value')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('icon'),
                TextInput::make('suffix'),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
