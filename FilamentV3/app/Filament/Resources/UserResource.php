<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rules\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Admin';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Clientes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->rule(Password::default())
                    ->required(),
                Forms\Components\TextInput::make('password_confirmation')
                    ->password()
                    ->same('password')
                    ->rule(Password::default())
                    ->required(),
                Forms\Components\Select::make('role')
                    ->relationship('roles', 'name')
                    ->preload()
                    ->multiple(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('change_password')
                    // ->steps([
                    //     Forms\Components\Wizard\Step::make('Passo 1')->schema([
                    //         Forms\Components\TextInput::make('name')->required(),
                    //     ]),
                    //     Forms\Components\Wizard\Step::make('Passo 2')->schema([
                    //         Forms\Components\TextInput::make('email')->required(),
                    //     ])
                    // ])
                    ->icon('heroicon-m-user')
                    ->color('info')
                    // ->requiresConfirmation()
                    ->form([
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->rule(Password::default())
                            ->required(),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->password()
                            ->same('password')
                            ->rule(Password::default())
                            ->required(),
                    ])->action(function (User $record, array $data) {
                        $record->update([
                            'password' => bcrypt($data['password'])
                        ]);

                        Notification::make()
                            ->title('Password successfully updated!')
                            ->success()
                            ->send();
                    }),
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
            'index' => Pages\ListUsers::route('/'),
            // 'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        // return self::getModel()::count();
        return Filament::getTenant()->members->count();
    }
}
