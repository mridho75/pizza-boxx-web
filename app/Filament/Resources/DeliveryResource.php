<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeliveryResource\Pages;
use App\Filament\Resources\DeliveryResource\RelationManagers;
use App\Models\Delivery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeliveryResource extends Resource
{
    protected static ?string $model = Delivery::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                BelongsToSelect::make('order_id')
                    ->relationship('order', 'id')
                    ->label('Order')
                    ->required(),
                BelongsToSelect::make('delivery_employee_id')
                    ->relationship('deliveryEmployee', 'name')
                    ->label('Kurir')
                    ->required(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'on_delivery' => 'On Delivery',
                        'delivered' => 'Delivered',
                        'failed' => 'Failed',
                    ])
                    ->label('Status Pengantaran')
                    ->required(),
                DateTimePicker::make('assigned_at')->label('Waktu Ditugaskan'),
                DateTimePicker::make('picked_up_at')->label('Waktu Diambil'),
                DateTimePicker::make('delivered_at')->label('Waktu Sampai'),
                Textarea::make('notes')->label('Catatan Kurir'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('order.id')->label('Order'),
                TextColumn::make('deliveryEmployee.name')->label('Kurir'),
                BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'pending',
                        'warning' => 'on_delivery',
                        'success' => 'delivered',
                        'danger' => 'failed',
                    ])
                    ->label('Status'),
                TextColumn::make('assigned_at')->dateTime('d M Y H:i')->label('Ditugaskan'),
                TextColumn::make('picked_up_at')->dateTime('d M Y H:i')->label('Diambil'),
                TextColumn::make('delivered_at')->dateTime('d M Y H:i')->label('Sampai'),
                TextColumn::make('notes')->limit(30)->label('Catatan'),
                TextColumn::make('created_at')->dateTime('d M Y H:i')->label('Dibuat'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListDeliveries::route('/'),
            'create' => Pages\CreateDelivery::route('/create'),
            'edit' => Pages\EditDelivery::route('/{record}/edit'),
        ];
    }
}
