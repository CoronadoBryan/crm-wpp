<?php

namespace App\Filament\Resources\Clientes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClientesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('telefono')
                    ->label('Teléfono')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->tel()
                    ->prefix('+51')
                    ->maxLength(9)
                    ->minLength(9)
                    ->rules(['regex:/^[0-9]{9}$/'])
                    ->placeholder('987654321')
                    ->helperText('Ingresa solo los 9 dígitos. El +51 se agregará automáticamente')
                    ->dehydrateStateUsing(function ($state) {
                        // Asegurar que siempre se guarde con 51
                        if (strlen($state) === 9 && is_numeric($state)) {
                            return '51' . $state;
                        }
                        // Si ya tiene 51 al inicio, devolverlo tal como está
                        if (strlen($state) === 11 && str_starts_with($state, '51')) {
                            return $state;
                        }
                        return $state;
                    })
                    ->formatStateUsing(function ($state) {
                        // Al mostrar en el form, quitar el 51 si existe
                        if (strlen($state) === 11 && str_starts_with($state, '51')) {
                            return substr($state, 2);
                        }
                        return $state;
                    }),
                TextInput::make('alias')
                    ->label('Alias/Nombre')
                    ->nullable()
                    ->placeholder('Ej: Juan Pérez')
                    ->helperText('Nombre o alias para identificar al cliente'),
            ]);
    }
}
