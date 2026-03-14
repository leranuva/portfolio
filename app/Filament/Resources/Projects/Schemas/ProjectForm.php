<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true),
                TextInput::make('slug')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Textarea::make('description')
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image()
                    ->directory('projects')
                    ->disk('blog'),
                Select::make('project_category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('url')
                    ->url()
                    ->maxLength(255),
                TextInput::make('video_url')
                    ->url()
                    ->maxLength(255)
                    ->placeholder('https://youtube.com/...'),
                TextInput::make('order')
                    ->numeric()
                    ->default(0),
                DateTimePicker::make('published_at'),
            ]);
    }
}
