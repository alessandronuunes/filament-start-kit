<?php

declare(strict_types=1);

namespace App\Livewire;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Jeffgreco13\FilamentBreezy\Livewire\PersonalInfo;

class MyCustomProfile extends PersonalInfo
{
    public bool $hasAvatars = true;

    public array $only = ['first_name', 'last_name', 'email', 'avatar'];

    protected function getAvatarUploadComponent(): FileUpload
    {
        return FileUpload::make('avatar')
            ->visibility('private')
            ->label(__('filament-breezy::default.fields.avatar'))
            ->avatar();
    }

    protected function getEmailComponent(): TextInput
    {
        return TextInput::make('email')->required()->disabled();
    }

    protected function getFirstNameComponent(): TextInput
    {
        return TextInput::make('first_name')
            ->label(__('Nome'))
            ->required();
    }

    protected function getLastNameComponent(): TextInput
    {
        return TextInput::make('last_name')
            ->required()
            ->label(__('Sobrenome'));
    }

    protected function getProfileFormSchema(): array
    {
        return [
            // removido o upload de avatar ate corrigir o b.o do storage
            //$this->getAvatarUploadComponent(),
            Group::make([
                $this->getFirstNameComponent(),
                $this->getLastNameComponent(),
                $this->getEmailComponent(),
            ])->columnSpan(2),
        ];
    }
}
