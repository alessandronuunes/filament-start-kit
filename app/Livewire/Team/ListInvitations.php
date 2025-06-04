<?php

declare(strict_types=1);

namespace App\Livewire\Team;

use App\Events\InvitationCreated;
use App\Models\Invitation;
use App\Models\Team;

use function App\Support\tenant;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ListInvitations extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $team = tenant(Team::class);

                return $team->invitations()->getQuery();
            })
            ->columns([
                TextColumn::make('email')
                    ->label('E-mail'),
            ])
            ->actions([
                Action::make('resend')
                    ->icon('heroicon-o-paper-airplane')
                    ->label(__('Reenviar'))
                    ->action($this->resend(...)),
                ActionGroup::make([
                    DeleteAction::make(),
                ]),

            ]);
    }

    public function resend(Invitation $record): void
    {
        try {
            InvitationCreated::dispatch($record);

            $this->success();
        } catch (\Throwable $e) {
            $this->addError('error', $e->getMessage());

            $this->fail();
        }
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

    public function render(): View
    {
        return view('livewire.team.list-invitations');
    }
}
