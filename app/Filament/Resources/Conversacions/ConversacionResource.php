<?php

namespace App\Filament\Resources\Conversacions;

use App\Filament\Resources\Conversacions\Pages\CreateConversacion;
use App\Filament\Resources\Conversacions\Pages\EditConversacion;
use App\Filament\Resources\Conversacions\Pages\ListConversacions;
use App\Filament\Resources\Conversacions\Pages\ViewConversacion;
use App\Filament\Resources\Conversacions\Schemas\ConversacionForm;
use App\Filament\Resources\Conversacions\Schemas\ConversacionInfolist;
use App\Filament\Resources\Conversacions\Tables\ConversacionsTable;
use App\Models\Conversacion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ConversacionResource extends Resource
{
    protected static ?string $model = Conversacion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Conversacion';

    // AGREGAR ESTAS LÍNEAS PARA CAMBIAR LOS TÍTULOS:
    protected static ?string $navigationLabel = 'Conversaciones';           // Nombre en el menú lateral
    protected static ?string $modelLabel = 'Conversación';                  // Título singular
    protected static ?string $pluralModelLabel = 'Conversaciones';          // Título de la tabla (arriba)
    protected static ?string $slug = 'conversaciones';                      // URL amigable

    public static function form(Schema $schema): Schema
    {
        return ConversacionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ConversacionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ConversacionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListConversacions::route('/'),
            'create' => CreateConversacion::route('/create'),
            'view' => ViewConversacion::route('/{record}'),
            'edit' => EditConversacion::route('/{record}/edit'),
        ];
    }
}
