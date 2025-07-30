<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\User; // Penting untuk relasi user/pegawai
use App\Models\Location; // Penting untuk relasi lokasi
use App\Models\Promo; // Penting untuk relasi promo
use App\Models\Product; // Pastikan ini ada untuk perhitungan

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn; // Untuk status
use Filament\Forms\Components\Wizard; // Untuk form multi-step
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Repeater; // Untuk item pesanan
use Filament\Forms\Components\Hidden; // Untuk menyembunyikan ID produk (jika masih diperlukan)
use Filament\Tables\Filters\SelectFilter; // Untuk filter dropdown

// Import tambahan untuk perhitungan:
use Filament\Forms\Get;
use Filament\Forms\Set;


class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag'; // Contoh ikon untuk pesanan

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Informasi Pelanggan & Pesanan')
                        ->schema([
                            Forms\Components\Grid::make(2)
                                ->schema([
                                    TextInput::make('customer_name')
                                        ->required()
                                        ->maxLength(255)
                                        ->label('Nama Pelanggan'),
                                    TextInput::make('customer_phone')
                                        ->tel()
                                        ->required()
                                        ->maxLength(20)
                                        ->label('Telepon Pelanggan'),
                                    TextInput::make('customer_email')
                                        ->email()
                                        ->nullable()
                                        ->maxLength(255)
                                        ->label('Email Pelanggan'),
                                    Select::make('user_id') // Jika user adalah pelanggan yang login
                                        ->relationship('user', 'name')
                                        ->placeholder('Pilih User (Opsional)')
                                        ->label('User Pelanggan')
                                        ->live(), // Agar perubahan user memicu refresh
                                    Select::make('location_id') // Toko yang melayani pesanan
                                        ->relationship('location', 'name')
                                        ->required()
                                        ->label('Lokasi Toko')
                                        ->live(), // Agar perubahan lokasi memicu refresh
                                ]),
                            Forms\Components\Grid::make(2)
                                ->schema([
                                    Select::make('order_type')
                                        ->options([
                                            'delivery' => 'Delivery',
                                            'pickup' => 'Pickup',
                                        ])
                                        ->required()
                                        ->native(false)
                                        ->live() // Agar perubahan tipe pesanan memicu refresh
                                        ->label('Tipe Pesanan'),
                                    Select::make('payment_method')
                                        ->options([
                                            'online' => 'Online Payment',
                                            'cash_on_delivery' => 'Cash on Delivery (COD)',
                                            'card_on_pickup' => 'Card on Pickup',
                                        ])
                                        ->required()
                                        ->native(false)
                                        ->label('Metode Pembayaran'),
                                ]),
                            Textarea::make('delivery_address')
                                ->nullable()
                                ->columnSpanFull()
                                ->label('Alamat Pengiriman (Jika Delivery)'),
                            Textarea::make('delivery_notes')
                                ->nullable()
                                ->columnSpanFull()
                                ->label('Catatan Pengiriman'),
                        ]),
                    Step::make('Detail Item Pesanan')
                        ->schema([
                            Repeater::make('orderItems') // Ini akan diisi dari relasi hasMany OrderItem
                                ->relationship('orderItems')
                                ->schema([
                                    Select::make('product_id')
                                        ->relationship('product', 'name')
                                        ->required()
                                        ->label('Produk')
                                        ->live() // Agar update unit_price dan product_name otomatis
                                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                                            $product = Product::find($state);
                                            if ($product) {
                                                $set('unit_price', $product->base_price);
                                                $set('product_name', $product->name); // <--- BARIS KRUSIAL INI
                                            }
                                        }),
                                    TextInput::make('product_name') // <--- TIDAK LAGI HIDDEN, WAJIB & READONLY
                                        ->required() // Wajib diisi karena di DB wajib
                                        ->readOnly() // Hanya dibaca, diisi otomatis dari product_id
                                        ->label('Nama Produk (Otomatis)'),
                                    TextInput::make('quantity')
                                        ->numeric()
                                        ->required()
                                        ->default(1)
                                        ->minValue(1)
                                        ->live() // Agar perubahan kuantitas memicu perhitungan ulang
                                        ->label('Jumlah'),
                                    TextInput::make('unit_price')
                                        ->numeric()
                                        ->required()
                                        ->prefix('Rp')
                                        ->label('Harga Satuan')
                                        ->readOnly(), // Ini harus readOnly
                                ])
                                ->collapsible() // Bisa dilipat
                                ->defaultItems(1)
                                ->columns(3) // Tampilan 3 kolom per item
                                ->addActionLabel('Tambah Item Pesanan')
                                ->live() // Agar perubahan item memicu perhitungan total
                                // Callback afterStateUpdated untuk menghitung total
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    static::updateTotals($get, $set);
                                }),
                                // afterStateDehydrated dihapus karena tidak didukung di versi Anda
                        ]),
                    Step::make('Ringkasan & Status')
                        ->schema([
                            Forms\Components\Grid::make(2)
                                ->schema([
                                    TextInput::make('subtotal_amount')
                                        ->numeric()
                                        ->required()
                                        ->prefix('Rp')
                                        ->readOnly() // Hanya dibaca, dihitung otomatis
                                        ->label('Subtotal'),
                                    TextInput::make('discount_amount')
                                        ->numeric()
                                        ->required()
                                        ->prefix('Rp')
                                        ->default(0.00)
                                        ->readOnly() // Baca saja, dihitung otomatis
                                        ->label('Diskon'),
                                    TextInput::make('delivery_fee')
                                        ->numeric()
                                        ->required()
                                        ->prefix('Rp')
                                        ->default(0.00)
                                        ->label('Biaya Pengiriman')
                                        ->live(), // Live agar perubahan biaya pengiriman memicu total
                                    TextInput::make('total_amount')
                                        ->numeric()
                                        ->required()
                                        ->prefix('Rp')
                                        ->readOnly() // Hanya dibaca, dihitung otomatis
                                        ->label('Total Pembayaran'),
                                    Select::make('promo_id')
                                        ->relationship('promo', 'name')
                                        ->placeholder('Pilih Promo (Opsional)')
                                        ->nullable()
                                        ->live() // Live agar perubahan promo memicu perhitungan ulang
                                        ->afterStateUpdated(function (Get $get, Set $set) {
                                            static::updateTotals($get, $set);
                                        })
                                        ->label('Promo Digunakan'),
                                ]),
                            Select::make('status')
                                ->options([
                                    'pending' => 'Pending (Menunggu Konfirmasi)',
                                    'accepted' => 'Accepted (Diterima Toko)',
                                    'preparing' => 'Preparing (Sedang Disiapkan)',
                                    'ready_for_delivery' => 'Ready for Delivery/Pickup (Siap Diantar/Diambil)',
                                    'on_delivery' => 'On Delivery (Dalam Pengantaran)',
                                    'delivered' => 'Delivered (Sudah Diantar)',
                                    'completed' => 'Completed (Selesai)',
                                    'cancelled' => 'Cancelled (Dibatalkan)',
                                    'refunded' => 'Refunded (Dikembalikan)',
                                ])
                                ->required()
                                ->native(false)
                                ->label('Status Pesanan'),
                            Select::make('delivery_employee_id')
                                ->relationship('deliveryEmployee', 'name', fn (Builder $query) => $query->where('role', 'employee'))
                                ->nullable()
                                ->label('Pegawai Pengantar (Opsional)'),
                            DatePicker::make('delivered_at')
                                ->nullable()
                                ->native(false)
                                ->label('Waktu Selesai/Diantar'),
                        ]),
                ]) // Hapus submitAction kustom di sini agar pakai default Filament
            ]);
    }

    // --- FUNGSI PERHITUNGAN TOTAL INI DI BAWAH FUNGSI form() ---
    public static function updateTotals(Get $get, Set $set): void
    {
        $orderItems = $get('orderItems');
        $subtotal = 0;

        if (is_array($orderItems)) {
            foreach ($orderItems as $item) {
                // Pastikan item produk sudah dipilih dan quantity/unit_price ada
                $subtotal += ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
            }
        }

        $discountAmount = 0;
        $promoId = $get('promo_id');
        if ($promoId) {
            $promo = Promo::find($promoId);
            // Pastikan promo ada dan subtotal memenuhi min_order_amount
            if ($promo && ($subtotal >= ($promo->min_order_amount ?? 0))) {
                if ($promo->type === 'percentage') {
                    $discountAmount = $subtotal * ($promo->value / 100);
                } elseif ($promo->type === 'fixed_amount') {
                    $discountAmount = $promo->value;
                }
                // Tambahkan logika untuk tipe promo lain jika diperlukan (buy_x_get_y, free_delivery)
            }
        }
        $set('discount_amount', round($discountAmount, 2));


        $deliveryFee = $get('delivery_fee'); // Ambil dari input
        $total = $subtotal - $discountAmount + $deliveryFee;

        $set('subtotal_amount', round($subtotal, 2));
        $set('total_amount', round($total, 2));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID Pesanan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer_name')
                    ->searchable()
                    ->sortable()
                    ->label('Pelanggan'),
                TextColumn::make('location.name')
                    ->searchable()
                    ->sortable()
                    ->label('Toko'),
                TextColumn::make('order_type')
                    ->label('Tipe')
                    ->badge(), // Tampilkan sebagai badge
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'accepted' => 'info',
                        'preparing' => 'warning',
                        'ready_for_delivery' => 'sky',
                        'on_delivery' => 'primary',
                        'delivered' => 'success',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        'refunded' => 'danger',
                    }),
                TextColumn::make('total_amount')
                    ->money('IDR')
                    ->sortable()
                    ->label('Total'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Dibuat'),
                TextColumn::make('deliveryEmployee.name') // Menampilkan nama pegawai pengantar
                    ->label('Pegawai Pengantar'),
                TextColumn::make('delivered_at')
                    ->dateTime()
                    ->label('Waktu Selesai'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'preparing' => 'Preparing',
                        'ready_for_delivery' => 'Ready for Delivery',
                        'on_delivery' => 'On Delivery',
                        'delivered' => 'Delivered',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'refunded' => 'Refunded',
                    ])
                    ->label('Filter Status'),
                SelectFilter::make('order_type')
                    ->options([
                        'delivery' => 'Delivery',
                        'pickup' => 'Pickup',
                    ])
                    ->label('Filter Tipe'),
                SelectFilter::make('location_id')
                    ->relationship('location', 'name')
                    ->label('Filter Lokasi'),
                SelectFilter::make('delivery_employee_id')
                    ->relationship('deliveryEmployee', 'name', fn (Builder $query) => $query->where('role', 'employee'))
                    ->label('Filter Pegawai'),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Dari Tanggal'),
                        DatePicker::make('created_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
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
            // Ini akan kita isi nanti untuk menampilkan item pesanan dalam tampilan detail order
            // OrderResource\RelationManagers\OrderItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}