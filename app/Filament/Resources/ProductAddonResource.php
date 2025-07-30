<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductAddonResource\Pages;
use App\Filament\Resources\ProductAddonResource\RelationManagers;
use App\Models\ProductAddon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
// use Filament\Forms\Components\TextInput\Mask;

// Import komponen Filament yang dibutuhkan
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

class ProductAddonResource extends Resource
{
    protected static ?string $model = ProductAddon::class;

    protected static ?string $navigationIcon = 'heroicon-o-plus-circle'; // Contoh ikon untuk addon

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product_id') // Dropdown untuk memilih produk terkait
                    ->relationship('product', 'name') // Mengambil nama dari model Product
                    ->required()
                    ->label('Produk Terkait'),
                TextInput::make('name') // Nama addon (misal: "Ekstra Keju", "Saus BBQ")
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Addon'),
                TextInput::make('price') // Harga addon
                    ->numeric()
                    ->required()
                    ->prefix('Rp')
                    // ->mask(fn (TextInput\Mask $mask) => $mask
                    //     ->numeric()
                    //     ->thousandsSeparator(',')
                    //     ->decimalCharacters(2)
                    //     ->decimalPoint('.')
                    //     ->mapToDecimal())
                    ->label('Harga Addon'),
                Toggle::make('is_available') // Status ketersediaan addon
                    ->required()
                    ->default(true)
                    ->label('Tersedia'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name') // Menampilkan nama produk dari relasi
                    ->searchable()
                    ->sortable()
                    ->label('Produk'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Addon'),
                TextColumn::make('price')
                    ->money('IDR')
                    ->sortable()
                    ->label('Harga'),
                ToggleColumn::make('is_available')
                    ->label('Tersedia'),
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
                Tables\Filters\SelectFilter::make('product_id') // Filter berdasarkan produk
                    ->relationship('product', 'name')
                    ->label('Filter Produk'),
                Tables\Filters\TernaryFilter::make('is_available')
                    ->label('Ketersediaan')
                    ->boolean()
                    ->trueLabel('Tersedia')
                    ->falseLabel('Tidak Tersedia') // <--- BARIS INI ADALAH AKHIRNYA, TANPA nullableLabel
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
            'index' => Pages\ListProductAddons::route('/'),
            'create' => Pages\CreateProductAddon::route('/create'),
            'edit' => Pages\EditProductAddon::route('/{record}/edit'),
        ];
    }
}