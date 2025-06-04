<?php

declare(strict_types=1);

namespace App\Livewire\Team;

use App\Filament\Components\Commissions;
use App\Models\Team;
use App\Models\User;

use function App\Support\tenant;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ListMembers extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $team = tenant(Team::class);

                return $team->members()->getQuery();
            })
            ->columns([
                Split::make([
                    ImageColumn::make('avatar')
                        ->label('Foto')
                        ->circular()
                        ->defaultImageUrl(fn (User $record) => $record->getFilamentAvatarUrl())
                        ->grow(false),
                    Stack::make([
                        TextColumn::make('first_name')
                            ->label('Nome')
                            ->size(TextColumn\TextColumnSize::Medium)
                            ->weight(FontWeight::Medium)
                            ->formatStateUsing(fn (User $record) => $record->getFilamentName()),
                        TextColumn::make('email')
                            ->label('E-mail')
                            ->weight(FontWeight::ExtraLight)
                            ->size(TextColumn\TextColumnSize::Small)
                            ->color(Color::Gray),
                    ]),
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.team.list-members');
    }

    protected function success(): Notification
    {
        return Notification::make()
            ->title(__('filament-actions::edit.single.notifications.saved.title'))
            ->success()
            ->send();
    }

    protected function fail(): Notification
    {
        return Notification::make()
            ->title(__('Erro ao salvar'))
            ->danger()
            ->send();
    }

    public function getFormCommission(User $record): array | null
    {
        /** @phpstan-ignore-next-line */
        $commission = $record->currentTeam()->first()->pivot->commission;

        if ($commission) {
            return [
                'commission' => json_decode($commission, true),
            ];
        }

        return $commission;
    }
}
