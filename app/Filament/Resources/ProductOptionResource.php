<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductOptionResource\Pages;
use App\Filament\Resources\ProductOptionResource\RelationManagers;
use App\Models\ProductOption;
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
use Filament\Tables\Columns\TextColumn;

class ProductOptionResource extends Resource
{
    protected static ?string $model = ProductOption::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-vertical'; // Contoh ikon untuk opsi produk

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product_id') // Dropdown untuk memilih produk terkait
                    ->relationship('product', 'name') // Mengambil nama dari model Product
                    ->required()
                    ->label('Produk Terkait'),
                TextInput::make('type') // Jenis opsi (misal: "Ukuran", "Jenis Adonan")
                    ->required()
                    ->maxLength(255)
                    ->label('Jenis Opsi'),
                TextInput::make('name') // Nama opsi (misal: "Regular", "Large", "Thin Crust")
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Opsi'),
                TextInput::make('price_modifier') // Penyesuaian harga
                    ->numeric()
                    ->default(0.00)
                    ->prefix('Rp')
                    // ->mask(fn (TextInput\Mask $mask) => $mask
                    //     ->numeric()
                    //     ->thousandsSeparator(',')
                    //     ->decimalCharacters(2)
                    //     ->decimalPoint('.')
                    //     ->mapToDecimal())
                    ->label('Penyesuaian Harga'), // Bisa positif (tambah harga) atau negatif (kurangi harga)
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
                TextColumn::make('type')
                    ->searchable()
                    ->sortable()
                    ->label('Jenis Opsi'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Opsi'),
                TextColumn::make('price_modifier')
                    ->money('IDR')
                    ->sortable()
                    ->label('Penyesuaian Harga'),
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
                Tables\Filters\SelectFilter::make('type') // Filter berdasarkan jenis opsi
                    ->options([
                        'Size' => 'Ukuran',
                        'Crust Type' => 'Jenis Adonan',
                        // Tambahkan jenis opsi lain jika ada
                    ])
                    ->label('Filter Jenis Opsi'),
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
            'index' => Pages\ListProductOptions::route('/'),
            'create' => Pages\CreateProductOption::route('/create'),
            'edit' => Pages\EditProductOption::route('/{record}/edit'),
        ];
    }
}