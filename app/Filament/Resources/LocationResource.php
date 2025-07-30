<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocationResource\Pages;
use App\Filament\Resources\LocationResource\RelationManagers;
use App\Models\Location;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront'; // Contoh ikon

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Lokasi'),
                TextInput::make('address')
                    ->required()
                    ->maxLength(255)
                    ->label('Alamat Lengkap'),
                TextInput::make('phone')
                    ->tel()
                    ->nullable()
                    ->maxLength(20)
                    ->label('Telepon'),
                TextInput::make('opening_hours')
                    ->nullable()
                    ->maxLength(255)
                    ->placeholder('Contoh: 09:00 - 22:00')
                    ->label('Jam Operasional'),
                Textarea::make('delivery_area_geojson')
                    ->nullable()
                    ->columnSpanFull()
                    ->label('Data Area Pengiriman (GeoJSON)'),
                // --- TAMBAHAN FIELD KOORDINAT DAN RADIUS ---
                TextInput::make('latitude')
                    ->numeric()
                    ->nullable()
                    ->label('Latitude (Garis Lintang)'),
                TextInput::make('longitude')
                    ->numeric()
                    ->nullable()
                    ->label('Longitude (Garis Bujur)'),
                TextInput::make('delivery_radius_km')
                    ->numeric()
                    ->default(5)
                    ->minValue(1)
                    ->label('Radius Pengantaran (KM)'),
                // --- AKHIR TAMBAHAN FIELD ---
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Lokasi'),
                TextColumn::make('address')
                    ->limit(50)
                    ->label('Alamat'),
                TextColumn::make('phone')
                    ->label('Telepon'),
                TextColumn::make('opening_hours')
                    ->label('Jam Operasional'),
                TextColumn::make('latitude') // Tambahkan kolom di tabel
                    ->label('Lat'),
                TextColumn::make('longitude') // Tambahkan kolom di tabel
                    ->label('Long'),
                TextColumn::make('delivery_radius_km') // Tambahkan kolom di tabel
                    ->label('Radius (KM)'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Dibuat Pada'),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Diperbarui Pada'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListLocations::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'edit' => Pages\EditLocation::route('/{record}/edit'),
        ];
    }
}