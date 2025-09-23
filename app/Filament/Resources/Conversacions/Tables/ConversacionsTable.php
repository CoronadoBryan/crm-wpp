<?php

namespace App\Filament\Resources\Conversacions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;

class ConversacionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                    
                TextColumn::make('cliente.telefono')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('cliente.alias')
                    ->label('Alias')
                    ->searchable()
                    ->placeholder('Sin alias'),
                    
                TextColumn::make('usuario.name')
                    ->label('Agente')
                    ->searchable()
                    ->sortable(),
                    
                BadgeColumn::make('estado.nombre')
                    ->label('Estado')
                    ->colors([
                        'primary' => 'asignado',
                        'warning' => 'en_atencion', 
                        'success' => 'finalizado',
                    ]),
                    
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                    
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('id_estado')
                    ->relationship('estado', 'nombre')
                    ->label('Estado')
                    ->placeholder('Todos los estados'),
                    
                SelectFilter::make('id_usuario')
                    ->relationship('usuario', 'name')
                    ->label('Agente')
                    ->placeholder('Todos los agentes'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50]);
    }
}
