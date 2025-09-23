<?php

namespace App\Filament\Resources\Conversacions\Pages;

use App\Filament\Resources\Conversacions\ConversacionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditConversacion extends EditRecord
{
    protected static string $resource = ConversacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
