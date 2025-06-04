<?php

declare(strict_types=1);

namespace App\Filament\Actions;

use App\Livewire\Negotiations\Detail;
use App\Models\Negotiation;
use Filament\Infolists\Components\Livewire;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;

class ViewOpportunityAction extends Action
{
    public static function make(?string $name = 'viewOpportunity'): static
    {
        return parent::make($name)
            ->modalHeading('')
            ->modalContent(
                fn (Action $action) => view('filament.resources.negotiation-resource.view-heading', ['action' => $action])
            )
            ->modalSubmitAction(false)
            ->modalCancelAction(false)
            ->fillForm(fn (Negotiation $record): array => $record->attributesToArray())
            ->modalWidth(MaxWidth::FiveExtraLarge)
            ->infolist(function (Negotiation $record) {
                return [
                    Livewire::make(Detail::class, ['record' => $record])
                        ->lazy(),
                ];
            });
    }
}
