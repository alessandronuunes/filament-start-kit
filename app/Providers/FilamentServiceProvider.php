<?php

declare(strict_types=1);

namespace App\Providers;

use App\Mail\ResetPassword;
use App\Mail\VerifyEmail;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Infolists;
use Filament\Notifications\Auth\ResetPassword as FilamentResetPassword;
use Filament\Notifications\Auth\VerifyEmail as FilamentVerifyEmail;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Facades\FilamentView;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Page::$reportValidationErrorUsing = function (ValidationException $exception): void {
            Notification::make()
                ->title($exception->getMessage())
                ->danger()
                ->send();
        };

        Page::$formActionsAreSticky = true;

        Actions\ActionGroup::configureUsing(
            fn (Actions\ActionGroup $action) => $action->icon('heroicon-o-ellipsis-horizontal')
        );

        Actions\Action::configureUsing(
            fn (Actions\Action $action) => $action
                ->modalWidth(MaxWidth::Medium)
                ->closeModalByClickingAway(false)
                ->modalFooterActionsAlignment(Alignment::End)
                ->stickyModalFooter()
                ->stickyModalHeader()
        );

        Actions\CreateAction::configureUsing(
            fn (Actions\CreateAction $action) => $action
                ->icon('heroicon-o-plus')
                ->label(__('Adicionar'))
                ->createAnother(false)
                ->modalFooterActionsAlignment(Alignment::End)
        );

        Actions\EditAction::configureUsing(
            fn (Actions\EditAction $action) => $action->icon('heroicon-o-pencil')
        );

        Actions\DeleteAction::configureUsing(
            fn (Actions\DeleteAction $action) => $action->icon('heroicon-o-trash')
        );

        Table::configureUsing(
            fn (Table $table) => $table
                ->filtersFormWidth(MaxWidth::ExtraSmall)
                ->striped()
                ->defaultPaginationPageOption(25)
                ->defaultSort('created_at', 'desc')
                ->paginationPageOptions([25, 50, 100])
        );

        Tables\Actions\Action::configureUsing(
            fn (Tables\Actions\Action $action) => $action
                ->modalWidth(MaxWidth::Medium)
                ->closeModalByClickingAway(false)
                ->modalFooterActionsAlignment(Alignment::End)
                ->stickyModalFooter()
                ->stickyModalHeader()
        );

        Tables\Actions\CreateAction::configureUsing(
            fn (Tables\Actions\CreateAction $action) => $action
                ->icon('heroicon-o-plus')
                ->createAnother(false)
        );

        Tables\Actions\EditAction::configureUsing(
            fn (Tables\Actions\EditAction $action) => $action->icon('heroicon-o-pencil')
        );

        Tables\Actions\DeleteAction::configureUsing(
            fn (Tables\Actions\DeleteAction $action) => $action->icon('heroicon-o-trash')
        );

        Tables\Columns\ImageColumn::configureUsing(
            fn (Tables\Columns\ImageColumn $column) => $column->extraImgAttributes(['loading' => 'lazy'])
        );

        Tables\Columns\TextColumn::configureUsing(
            fn (Tables\Columns\TextColumn $column) => $column
                ->limit(50)
                ->wrap()
                ->timezone(config('app.timezone'))
        );

        Tables\Filters\SelectFilter::configureUsing(
            fn (Tables\Filters\SelectFilter $filter) => $filter->native(false)
        );

        Forms\Components\Actions\Action::configureUsing(
            fn (Forms\Components\Actions\Action $action) => $action
                ->modalWidth(MaxWidth::Medium)
                ->closeModalByClickingAway(false)
                ->stickyModalFooter()
                ->stickyModalHeader()
        );

        Forms\Components\Select::configureUsing(
            fn (Forms\Components\Select $component) => $component
                ->native(false)
                ->selectablePlaceholder(
                    fn (Forms\Components\Select $component) => ! $component->isRequired(),
                )
                ->searchable(
                    fn (Forms\Components\Select $component) => $component->hasRelationship()
                )
                ->preload(
                    fn (Forms\Components\Select $component) => $component->isSearchable()
                )
        );

        Forms\Components\DateTimePicker::configureUsing(
            fn (Forms\Components\DateTimePicker $component) => $component
                ->timezone(config('app.timezone'))
                ->seconds(false)
                ->native(false)
                ->weekStartsOnSunday()
                ->displayFormat('d/m/Y H:i')
                ->maxDate('9999-12-31T23:59')
        );

        Forms\Components\Repeater::configureUsing(
            fn (Forms\Components\Repeater $component) => $component->deleteAction(
                fn (Forms\Components\Actions\Action $action) => $action->requiresConfirmation(),
            )
        );

        Forms\Components\Builder::configureUsing(
            fn (Forms\Components\Builder $component) => $component->deleteAction(
                fn (Forms\Components\Actions\Action $action) => $action->requiresConfirmation(),
            )
        );

        Forms\Components\FileUpload::configureUsing(
            fn (Forms\Components\FileUpload $component) => $component->moveFiles()
        );

        Forms\Components\RichEditor::configureUsing(
            fn (Forms\Components\RichEditor $component) => $component->disableToolbarButtons(['blockquote', 'codeBlock'])
        );

        Forms\Components\Textarea::configureUsing(
            fn (Forms\Components\Textarea $component) => $component->rows(4)
        );

        Infolists\Components\Section::macro('empty', function () {
            /** @var Infolists\Components\Section $this */
            return $this->extraAttributes(['empty' => true]);
        });

        Infolists\Components\Actions\Action::configureUsing(
            fn (Infolists\Components\Actions\Action $action) => $action
                ->modalWidth(MaxWidth::Medium)
                ->closeModalByClickingAway(false)
                ->modalFooterActionsAlignment(Alignment::End)
                ->stickyModalFooter()
                ->stickyModalHeader()
        );

        Page::formActionsAlignment(Alignment::End);

        Section::configureUsing(function (Section $section) {
            $section->footerActionsAlignment(Alignment::End);
        });

        Password::defaults(function () {
            return Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised();
        });

        CreateRecord::disableCreateAnother();

        FilamentResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $url = URL::signedRoute('filament.app.auth.password-reset.reset', [
                'email' => $notifiable->getEmailForPasswordReset(),
                'token' => $token,
            ]);

            $expiration = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');

            return (new ResetPassword($url, $expiration))
                ->to($notifiable->email);
        });

        FilamentVerifyEmail::toMailUsing(function (object $notifiable, string $verificationUrl) {
            return (new VerifyEmail($verificationUrl))
                ->to($notifiable->email);
        });

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_AFTER,
            fn (): View => view('components.trial-status-component')
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
            fn (): View => view('components.auth.terms-service-and-privacy')
        );

        // Adicionar o componente de status do trial no início de todas as páginas do Filament
        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_START,
            fn (): string => '<x-trial-status />'
        );
    }
}
