<?php

namespace App\Filament\Resources\Leads\Schemas;

use App\Models\Lead;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->disabled(),
                TextInput::make('email')
                    ->label('Email address')
                    ->disabled(),
                TextInput::make('project_type')
                    ->label('Project type')
                    ->disabled(),
                Textarea::make('what_to_automate')
                    ->label('What to automate')
                    ->disabled()
                    ->columnSpanFull(),
                TextInput::make('budget_range')
                    ->disabled(),
                TextInput::make('urgency')
                    ->disabled(),
                Textarea::make('message')
                    ->label('Additional details')
                    ->disabled()
                    ->columnSpanFull(),
                TextInput::make('score')
                    ->disabled(),
                TextInput::make('source')
                    ->label('Source')
                    ->disabled(),
                TextInput::make('utm_source')
                    ->label('UTM Source')
                    ->disabled(),
                TextInput::make('utm_medium')
                    ->label('UTM Medium')
                    ->disabled(),
                TextInput::make('utm_campaign')
                    ->label('UTM Campaign')
                    ->disabled(),
                Select::make('status')
                    ->options(Lead::statusOptions())
                    ->required()
                    ->native(false),
            ]);
    }
}
