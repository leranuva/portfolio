<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Arr;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\Support\Htmlable;
use Throwable;

class SiteSettings extends Page
{
    use CanUseDatabaseTransactions;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Settings';

    protected static ?string $title = 'Site settings';

    protected static ?string $slug = 'site-settings';

    protected static ?int $navigationSort = 100;

    public ?array $data = [];

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $heroImage = SiteSetting::get('hero_image');
        $heroCv = SiteSetting::get('hero_cv_url');

        $this->data = [
            'hero_name' => SiteSetting::get('hero_name', ''),
            'hero_title' => SiteSetting::get('hero_title', ''),
            'hero_subtitle' => SiteSetting::get('hero_subtitle', ''),
            'hero_image' => $heroImage ? Arr::wrap($heroImage) : [],
            'hero_cv_url' => $heroCv ? Arr::wrap($heroCv) : [],
            'about_text' => SiteSetting::get('about_text', ''),
            'meta_title' => SiteSetting::get('meta_title', ''),
            'meta_description' => SiteSetting::get('meta_description', ''),
            'contact_email' => SiteSetting::get('contact_email', ''),
            'contact_github' => SiteSetting::get('contact_github'),
            'contact_twitter' => SiteSetting::get('contact_twitter'),
            'calendly_url' => SiteSetting::get('calendly_url'),
        ];
    }

    public function save(): void
    {
        try {
            $this->beginDatabaseTransaction();

            $data = $this->form->getState();

            foreach ($data as $key => $value) {
                $group = match (true) {
                    str_starts_with($key, 'hero_') => 'hero',
                    str_starts_with($key, 'about_') => 'about',
                    str_starts_with($key, 'meta_') => 'seo',
                    str_starts_with($key, 'contact_') => 'contact',
                    $key === 'calendly_url' => 'contact',
                    default => 'general',
                };
                // FileUpload returns array for single-file fields; store first path only
                if (in_array($key, ['hero_image', 'hero_cv_url'], true) && is_array($value)) {
                    $value = ! empty($value) ? reset($value) : '';
                }
                SiteSetting::set($key, $value ?? '', $group);
            }

            $this->commitDatabaseTransaction();

            Notification::make()
                ->success()
                ->title('Settings saved')
                ->send();

            $this->redirect(static::getUrl(), navigate: FilamentView::hasSpaMode(static::getUrl()));
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction() ?
                $this->rollBackDatabaseTransaction() :
                $this->commitDatabaseTransaction();

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            throw $exception;
        }
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('settings')
                    ->tabs([
                        Tab::make('Profile')
                            ->schema([
                                FileUpload::make('hero_image')
                                    ->label('Profile image')
                                    ->disk('blog')
                                    ->directory('profile')
                                    ->image()
                                    ->avatar()
                                    ->maxSize(2048)
                                    ->helperText('Recommended: square image. Max. 2 MB.'),
                                TextInput::make('hero_name')
                                    ->label('Name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('hero_title')
                                    ->label('Professional title')
                                    ->maxLength(255),
                                Textarea::make('hero_subtitle')
                                    ->label('Subtitle / Tagline')
                                    ->rows(3)
                                    ->maxLength(500),
                                FileUpload::make('hero_cv_url')
                                    ->label('CV (PDF)')
                                    ->disk('blog')
                                    ->directory('cv')
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->maxSize(5120)
                                    ->helperText('Upload your CV in PDF. Max. 5 MB.'),
                            ]),
                        Tab::make('SEO')
                            ->schema([
                                TextInput::make('meta_title')
                                    ->label('Meta title')
                                    ->maxLength(70)
                                    ->helperText('Title for search engines. Empty = name + title.'),
                                Textarea::make('meta_description')
                                    ->label('Meta description')
                                    ->rows(3)
                                    ->maxLength(160)
                                    ->helperText('Description for search engines. Max. 160 characters.'),
                            ]),
                        Tab::make('About')
                            ->schema([
                                Textarea::make('about_text')
                                    ->label('Biography text')
                                    ->rows(5),
                            ]),
                        Tab::make('Contact')
                            ->schema([
                                TextInput::make('contact_email')
                                    ->label('Email')
                                    ->email()
                                    ->maxLength(255),
                                TextInput::make('contact_github')
                                    ->label('GitHub')
                                    ->url()
                                    ->maxLength(255),
                                TextInput::make('contact_twitter')
                                    ->label('Twitter/X')
                                    ->url()
                                    ->maxLength(255),
                                TextInput::make('calendly_url')
                                    ->label('Calendly URL')
                                    ->url()
                                    ->maxLength(500)
                                    ->placeholder('https://calendly.com/your-username/30min')
                                    ->helperText('Embed a free 30-min consultation. Leave empty to hide the section.'),
                            ]),
                    ]),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save')
                ->submit('save')
                ->keyBindings(['mod+s']),
        ];
    }

    public function getTitle(): string | Htmlable
    {
        return static::$title ?? 'Settings';
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make($this->getFormActions())
                            ->key('form-actions'),
                    ]),
            ]);
    }
}
