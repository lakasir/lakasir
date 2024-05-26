<?php

namespace App\Filament\Tenant\Resources\UserResource\Pages;

use App\Filament\Tenant\Resources\UserResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->action(function ($record) {
                    if (Filament::auth()->id() == $record->id) {
                        Notification::make()
                            ->title('You cannot delete yourself')
                            ->warning()
                            ->send();

                        return;
                    }

                    $record->delete();

                    return redirect($this->getResource()::getUrl());
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return '/member/users';
    }
}
