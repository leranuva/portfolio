<?php

namespace App\Filament\Resources\Skills\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Slider;
use Filament\Schemas\Schema;

class SkillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Slider::make('percentage')
                    ->required()
                    ->minValue(0)
                    ->maxValue(100)
                    ->default(0),
                TextInput::make('icon')
                    ->maxLength(255)
                    ->placeholder('heroicon-o-code-bracket'),
                TextInput::make('order')
                    ->numeric()
                    ->default(0),
            ]);
    }
}
