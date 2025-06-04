<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Settings\Pages\Team;

use App\Filament\Clusters\Settings;
use App\Filament\Components\Commissions;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Pages\Tenancy\EditTenantProfile as BaseEditTenantProfile;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\HtmlString;

class EditTenancyProfile extends BaseEditTenantProfile
{
    protected static string $view = 'filament.clusters.settings.pages.team.edit-tenant-profile';

    protected static ?string $cluster = Settings::class;

    protected static bool $isDiscovered = true;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationGroup(): ?string
    {
        return __('Equipe');
    }

    public static function getNavigationLabel(): string
    {
        return __('Geral');
    }

    public static function getLabel(): string
    {
        return __('Geral');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    TextInput::make('name')
                        ->label(__('Nome'))
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Get $get, Set $set, ?string $state, EditTenancyProfile $livewire) {
                            if (blank($get('slug'))) {
                                $set('slug', str($state)->slug());
                                $livewire->validateOnly('data.slug');
                            }
                        })
                        ->maxLength(32)
                        ->required(),
                    TextInput::make('slug')
                        ->label(__('Slug'))
                        ->unique(column: 'slug', ignoreRecord: true)
                        ->live(debounce: 500)
                        ->afterStateUpdated(function (EditTenancyProfile $livewire, ?string $state, TextInput $component) {
                            $component->state(str($state)->slug());
                            $livewire->validateOnly($component->getStatePath());
                        })
                        ->maxLength(48)
                        ->required(),
                ])->id('team_name')
                ->columns()
                ->compact()
                ->heading(__('Nome da Equipe'))
                ->description(__('Este é o nome visível da sua equipe.').'.')
                ->footerActionsAlignment(Alignment::Between)
                ->footerActions([
                    Action::make('team_name_description')
                        ->label(fn (): HtmlString => new HtmlString('<span class="overflow-hidden break-words text-sm text-gray-500 dark:text-gray-400 font-normal">Por favor, use no máximo 32 carácteres.</span>'))
                        ->link()
                        ->disabled(),
                    Action::make('team_name')
                        ->label(__('Salvar'))
                        ->action(fn (EditTenancyProfile $livewire) => $livewire->save()),
                ]),
            ]);
    }

    public static function getRelativeRouteName(): string
    {
        return 'settings.profile';
    }

    public static function getSlug(): string
    {
        return 'settings/profile';
    }

    public static function getRouteName(?string $panel = null): string
    {
        $panel = $panel ? Filament::getPanel($panel) : Filament::getCurrentPanel();

        $routeName = 'profile';
        $routeName = static::prependClusterRouteBaseName($routeName);

        return $panel->generateRouteName($routeName);
    }
}
