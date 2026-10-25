<?php

namespace App\Filament\Pages;

use App\Filament\Support\Fields;
use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * @property-read Schema $form
 */
class ManageSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?int $navigationSort = 10;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public static function getNavigationLabel(): string
    {
        return __('admin.settings.title');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.settings');
    }

    public function getTitle(): string
    {
        return __('admin.settings.title');
    }

    public function mount(): void
    {
        $this->form->fill(Setting::values());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make(__('admin.settings.contact'))
                    ->description(__('admin.settings.contact_help'))
                    ->icon('heroicon-o-phone')
                    ->columns(['default' => 1, 'md' => 2])
                    ->schema([
                        TextInput::make('phone')->label(__('admin.settings.phone'))->tel()->required()->extraInputAttributes(['dir' => 'ltr']),
                        TextInput::make('whatsapp')->label(__('admin.settings.whatsapp'))->helperText(__('admin.settings.whatsapp_help'))
                            ->regex('/^\d{8,15}$/')->extraInputAttributes(['dir' => 'ltr']),
                        TextInput::make('email')->label(__('admin.settings.email'))->email()->required(),
                        TextInput::make('notification_email')->label(__('admin.settings.notification_email'))
                            ->helperText(__('admin.settings.notification_email_help'))->email()->required(),
                        TextInput::make('address_en')->label(__('admin.settings.address').' — '.__('admin.english')),
                        TextInput::make('address_ar')->label(__('admin.settings.address').' — '.__('admin.arabic'))->extraInputAttributes(['dir' => 'rtl']),
                        TextInput::make('working_hours_en')->label(__('admin.settings.working_hours').' — '.__('admin.english')),
                        TextInput::make('working_hours_ar')->label(__('admin.settings.working_hours').' — '.__('admin.arabic'))->extraInputAttributes(['dir' => 'rtl']),
                        TextInput::make('map_query')->label(__('admin.settings.map_query'))->helperText(__('admin.settings.map_query_help')),
                        TextInput::make('founded_year')->label(__('admin.settings.founded_year'))->numeric()->minValue(1950)->maxValue(2100),
                    ]),
                Section::make(__('admin.settings.social'))
                    ->description(__('admin.settings.social_help'))
                    ->icon('heroicon-o-share')
                    ->columns(['default' => 1, 'md' => 3])
                    ->schema([
                        TextInput::make('instagram')->label('Instagram')->url()->prefixIcon('heroicon-o-camera'),
                        TextInput::make('facebook')->label('Facebook')->url()->prefixIcon('heroicon-o-globe-alt'),
                        TextInput::make('linkedin')->label('LinkedIn')->url()->prefixIcon('heroicon-o-briefcase'),
                    ]),
                Section::make(__('admin.settings.branding'))
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Grid::make(['default' => 1, 'md' => 2])->schema([
                            Fields::image('hero_image', 'site', __('admin.settings.hero_image')),
                            Fields::image('about_image', 'site', __('admin.settings.about_image')),
                        ]),
                        FileUpload::make('profile_pdf')
                            ->label(__('admin.settings.profile_pdf'))
                            ->helperText(__('admin.settings.profile_pdf_help'))
                            ->acceptedFileTypes(['application/pdf'])
                            ->disk('public')
                            ->directory('site')
                            ->visibility('public')
                            ->maxSize(30 * 1024),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('save')->label(__('admin.settings.save'))->submit('save')->keyBindings(['mod+s']),
                    ])->sticky(),
                ]),
        ]);
    }

    public function save(): void
    {
        Setting::put($this->form->getState());

        Notification::make()->title(__('admin.settings.saved'))->success()->send();
    }
}
