<?php

namespace App\Filament\Resources\Conversacions\Pages;

use App\Filament\Resources\Conversacions\ConversacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListConversacions extends ListRecords
{
    protected static string $resource = ConversacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
