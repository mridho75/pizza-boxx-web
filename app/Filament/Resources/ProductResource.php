<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
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
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Forms\Components\MarkdownEditor; // Alternatif Rich Editor jika lebih suka markdown

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    // ICON INI YANG MENYEBABKAN ERROR SVG NOT FOUND SEBELUMNYA
    // Kita akan gunakan ikon yang pasti ada, misal 'heroicon-o-archive-box'
    protected static ?string $navigationIcon = 'heroicon-o-archive-box'; // GANTI INI DENGAN IKON YANG ADA

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([ // Menggunakan Card untuk mengelompokkan input
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->label('Nama Produk'),
                    Select::make('category_id') // Dropdown untuk memilih kategori
                        ->relationship('category', 'name') // Mengambil nama dari model Category
                        ->required()
                        ->label('Kategori'),
                    Textarea::make('description') // Textarea untuk deskripsi
                        ->nullable()
                        ->columnSpanFull() // Mengambil lebar penuh
                        ->label('Deskripsi Produk'),
                    TextInput::make('base_price')
                        ->numeric() // Hanya menerima angka
                        ->required()
                        ->prefix('Rp') // Menambahkan prefix 'Rp'
                        // ->mask(fn (TextInput\Mask $mask) => $mask
                        //     ->numeric()
                        //     ->thousandsSeparator(',')
                        //     ->decimalCharacters(2)
                        //     ->decimalPoint('.')
                        //     ->mapToDecimal() // <--- TIDAK ADA KURUNG TUTUP DI SINI
                        // ) // <--- KURUNG TUTUP INI MENUTUP FUNGSI mask()
                        ->label('Harga Dasar'),
                    FileUpload::make('image_path') // Input untuk upload gambar
                        ->image() // Hanya menerima file gambar
                        ->directory('product-images') // Simpan gambar di storage/app/public/product-images
                        ->nullable()
                        ->label('Gambar Produk'),
                    Toggle::make('is_available') // Toggle untuk ketersediaan
                        ->required()
                        ->default(true)
                        ->label('Tersedia'),
                ])->columns(2), // Mengatur 2 kolom dalam Card
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path') // Menampilkan gambar
                    ->square() // Membuat gambar persegi
                    ->label('Gambar'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Produk'),
                TextColumn::make('category.name') // Menampilkan nama kategori dari relasi
                    ->searchable()
                    ->sortable()
                    ->label('Kategori'),
                TextColumn::make('base_price')
                    ->money('IDR') // Format sebagai mata uang Rupiah
                    ->sortable()
                    ->label('Harga Dasar'),
                ToggleColumn::make('is_available') // Kolom toggle untuk ketersediaan
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
                Tables\Filters\SelectFilter::make('category_id') // Filter berdasarkan kategori
                    ->relationship('category', 'name')
                    ->label('Filter Kategori'),
                Tables\Filters\TernaryFilter::make('is_available') // Filter ketersediaan
                    ->label('Ketersediaan')
                    ->boolean() // Filter boolean
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
            // Nanti kita akan tambahkan RelationManager untuk ProductOption dan ProductAddon di sini
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    // Tambahkan fungsi ini untuk mendefinisikan relasi ke Category
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);
    }
}