<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Models\Location;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Collection; // Pastikan ini ada

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama'),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->label('Email'),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn (string $state): string => bcrypt($state))
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->label('Password'),
                Select::make('role')
                    ->options([
                        'admin' => 'Admin Pusat',
                        'employee' => 'Pegawai / Admin Cabang',
                        'customer' => 'Pelanggan',
                    ])
                    ->required()
                    ->native(false)
                    ->label('Role'),
                Select::make('location_id')
                    ->relationship('location', 'name')
                    ->nullable()
                    ->label('Lokasi Cabang (Untuk Pegawai)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama'),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->label('Email'),
                TextColumn::make('role')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->label('Role'),
                TextColumn::make('location.name')
                    ->searchable()
                    ->sortable()
                    ->default('N/A')
                    ->label('Lokasi Cabang'),
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
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin Pusat',
                        'employee' => 'Pegawai / Admin Cabang',
                        'customer' => 'Pelanggan',
                    ])
                    ->label('Filter Role'),
                Tables\Filters\SelectFilter::make('location_id')
                    ->relationship('location', 'name')
                    ->label('Filter Lokasi'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->hidden(function (User $record): bool {
                        // Sembunyikan tombol delete jika user adalah admin dan dia satu-satunya admin
                        if ($record->hasRole('admin')) {
                            return User::where('role', 'admin')->count() <= 1;
                        }
                        return false;
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->hidden(function (?Collection $records): bool { // <--- PERUBAHAN DI SINI: Parameter menjadi ?Collection $records (nullable)
                            // Jika $records null (tidak ada yang dipilih atau inisialisasi), jangan sembunyikan
                            if (is_null($records) || $records->isEmpty()) {
                                return false;
                            }

                            // Jika mencoba menghapus admin dan jumlah admin yang tersisa akan 0
                            $selectedAdminsCount = $records->where('role', 'admin')->count();
                            $totalAdminsCount = User::where('role', 'admin')->count();

                            if ($selectedAdminsCount > 0 && ($totalAdminsCount - $selectedAdminsCount) < 1) {
                                return true; // Sembunyikan aksi jika akan menghapus semua admin
                            }
                            return false;
                        }),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}