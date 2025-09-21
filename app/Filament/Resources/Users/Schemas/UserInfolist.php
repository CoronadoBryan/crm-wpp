<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Nombre'),
                TextEntry::make('email')
                    ->label('Correo electrónico'),
                TextEntry::make('email_verified_at')
                    ->label('Correo verificado el')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->label('Creado el')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label('Actualizado el')
                    ->dateTime(),
                TextEntry::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->separator(','),
            ]);
    }
}
