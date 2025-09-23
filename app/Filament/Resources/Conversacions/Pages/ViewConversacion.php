<?php

namespace App\Filament\Resources\Conversacions\Pages;

use App\Filament\Resources\Conversacions\ConversacionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewConversacion extends ViewRecord
{
    protected static string $resource = ConversacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
