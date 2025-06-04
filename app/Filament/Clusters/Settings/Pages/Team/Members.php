<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Settings\Pages\Team;

use App\Events\InvitationCreated;
use App\Filament\Clusters\Settings;
use App\Models\Invitation;
use App\Models\Team;
use App\Rules\AlreadyMember;

use function App\Support\tenant;

use Filament\Facades\Filament;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Resources\Components\Tab;
use Filament\Resources\Concerns\HasTabs;
use Filament\Support\Enums\Alignment;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\HtmlString;

class Members extends Page implements HasForms
{
    use HasTabs;
    use InteractsWithForms;

    public ?array $data = [];

    protected static string $view = 'filament.clusters.settings.pages.team.members';

    protected static ?string $cluster = Settings::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationGroup(): ?string
    {
        return __('Equipe');
    }

    public function getTitle(): string|Htmlable
    {
        return __('Membros');
    }

    public function mount(): void
    {
        $this->getForm('form')->fill();

        $this->loadDefaultActiveTab();
    }

    public static function getNavigationLabel(): string
    {
        return __('Membros');
    }

    public function form(Form $form): Form
    {
        return $form
            ->model(Filament::getTenant())
            ->schema([
                Section::make()
                    ->description(__('Convide membros para a equipe.'))
                    ->schema([
                        Repeater::make('emailAddresses')
                            ->label(__('Endereço de E-mail'))
                            ->minItems(1)
                            ->maxItems(5)
                            ->defaultItems(1)
                            ->deletable(fn ($state) => is_array($state) && count($state) > 1)
                            ->reorderable(false)
                            ->addActionLabel(__('Adicionar outro membro'))
                            ->simple(
                                TextInput::make('email')
                                    ->required()
                                    ->placeholder('example@mail.com')
                                    ->email()
                                    ->distinct()
                                    ->unique(Invitation::class)
                                    ->rule(new AlreadyMember()),
                            ),
                    ])
                    ->headerActions([
                        Action::make('inviteLink')
                            ->label(__('Link de convite'))
                            ->icon('heroicon-o-clipboard')
                            ->button()
                            ->size('sm')
                            ->action(function (Members $livewire, Team $record) {
                                $signedRoute = URL::signedRoute('filament.app.invite.accept', ['invitation_code' => $record->invitation_code]);

                                $livewire->js('window.navigator.clipboard.writeText("'.$signedRoute.'");');

                                Notification::make()
                                    ->title(__('Link de convite copiado'))
                                    ->success()
                                    ->send();
                            }),
                    ])
                    ->id('form')
                    ->footerActionsAlignment(Alignment::Between)
                    ->footerActions([
                        Action::make('team_name_description')
                            ->label(fn (): HtmlString => new HtmlString('<span class="overflow-hidden break-words text-sm text-gray-500 dark:text-gray-400 font-normal">Há um limite de 5 convites por vez.</span>'))
                            ->link()
                            ->disabled(),
                        Action::make('invite')
                            ->label(__('Convidar'))
                            ->action('create'),
                    ]),
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        $formData = $this->getForm('form')->getState();
        $emailAddresses = $formData['emailAddresses'];

        foreach ($emailAddresses as $emailAddress) {
            $data = [
                'team_id' => tenant(Team::class)->id,
                'user_id' => auth()->id(),
                'email' => $emailAddress,
            ];

            tap(Invitation::create($data), fn (Invitation $invitation) => InvitationCreated::dispatch($invitation));
        }

        $this->getSavedNotification(count($emailAddresses))->send();

        $this->getForm('form')->fill();
    }

    protected function getSavedNotification(int $count = 0): ?Notification
    {
        $title = $this->getSavedNotificationTitle($count);

        if (blank($title)) {
            return null;
        }

        return Notification::make()
            ->success()
            ->title($this->getSavedNotificationTitle($count));
    }

    protected function getSavedNotificationTitle(int $count): ?string
    {
        return match ($count) {
            1 => __('Convite enviado com sucesso.'),
            default => __('Convites enviados com sucesso.'),
        };
    }

    public function getTabs(): array
    {
        // Tables are livewire components. They are located in app/Livewire/Team

        /** @var Team $team */
        $team = Filament::getTenant();

        return [
            'members' => Tab::make(__('Membros')),
            'pending-invitations' => Tab::make(__('Convites Pendentes'))
                ->badge(Invitation::where('team_id', $team->id)->count()),
        ];
    }

    public function updatedActiveTab(): void
    {
        // It's important because it changes the default behavior of when tabs are changed.
        // Case it's changed, it will occur an error when trying to change tabs.
    }
}
