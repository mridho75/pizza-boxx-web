<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromoResource\Pages;
use App\Filament\Resources\PromoResource\RelationManagers;
use App\Models\Promo;
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
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class PromoResource extends Resource
{
    protected static ?string $model = Promo::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift'; // Contoh ikon untuk promo

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Promosi'),
                TextInput::make('code')
                    ->unique(ignoreRecord: true) // Kode harus unik
                    ->nullable()
                    ->maxLength(50)
                    ->label('Kode Kupon (Opsional)'),
                Textarea::make('description')
                    ->nullable()
                    ->columnSpanFull()
                    ->label('Deskripsi Promo'),
                Select::make('type')
                    ->options([
                        'percentage' => 'Persentase (%)',
                        'fixed_amount' => 'Jumlah Tetap (Rp)',
                        'buy_x_get_y' => 'Beli X Dapat Y',
                        'free_delivery' => 'Gratis Ongkir',
                    ])
                    ->required()
                    ->native(false) // Tampilan dropdown lebih modern
                    ->label('Tipe Promo'),
                TextInput::make('value')
                    ->numeric()
                    ->nullable()
                    ->label('Nilai Promo (Angka)'), // Misal: 10 (untuk 10%) atau 20000 (untuk Rp20.000)
                TextInput::make('min_order_amount')
                    ->numeric()
                    ->nullable()
                    ->prefix('Rp')
                    // ->mask(fn (TextInput\Mask $mask) => $mask
                    //     ->numeric()
                    //     ->thousandsSeparator(',')
                    //     ->decimalCharacters(2)
                    //     ->decimalPoint('.')
                    //     ->mapToDecimal())
                    ->label('Minimum Belanja'),
                DateTimePicker::make('start_date')
                    ->required()
                    ->native(false) // Tampilan picker lebih modern
                    ->label('Tanggal Mulai Berlaku'),
                DateTimePicker::make('end_date')
                    ->nullable()
                    ->native(false) // Tampilan picker lebih modern
                    ->label('Tanggal Berakhir (Opsional)'),
                TextInput::make('usage_limit')
                    ->numeric()
                    ->nullable()
                    ->label('Batas Penggunaan Total (Opsional)'),
                Toggle::make('is_active')
                    ->required()
                    ->default(true)
                    ->label('Aktif'),
            ])->columns(2); // Atur form menjadi 2 kolom
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Promo'),
                TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->default('N/A') // Tampilkan N/A jika kode kosong
                    ->label('Kode Kupon'),
                TextColumn::make('type')
                    ->label('Tipe'),
                TextColumn::make('value')
                    ->label('Nilai')
                    ->formatStateUsing(function (string $state, Promo $record): string {
                        if ($record->type === 'percentage') {
                            return $state . '%';
                        } elseif ($record->type === 'fixed_amount') {
                            return 'Rp ' . number_format((float)$state, 0, ',', '.');
                        }
                        return $state ?? 'N/A';
                    }),
                TextColumn::make('min_order_amount')
                    ->money('IDR')
                    ->label('Min. Belanja'),
                TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable()
                    ->label('Mulai'),
                TextColumn::make('end_date')
                    ->dateTime()
                    ->sortable()
                    ->default('Tidak Ada')
                    ->label('Berakhir'),
                TextColumn::make('usage_limit')
                    ->default('Tidak Ada')
                    ->label('Batas'),
                TextColumn::make('uses')
                    ->label('Digunakan'),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Aktif'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Dibuat Pada'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'percentage' => 'Persentase',
                        'fixed_amount' => 'Jumlah Tetap',
                        'buy_x_get_y' => 'Beli X Dapat Y',
                        'free_delivery' => 'Gratis Ongkir',
                    ])
                    ->label('Filter Tipe'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->boolean(),
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
            'index' => Pages\ListPromos::route('/'),
            'create' => Pages\CreatePromo::route('/create'),
            'edit' => Pages\EditPromo::route('/{record}/edit'),
        ];
    }
}