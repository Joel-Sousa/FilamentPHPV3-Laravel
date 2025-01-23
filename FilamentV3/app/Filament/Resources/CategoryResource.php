<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Admin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('store_id')
                    ->relationship(
                        'store',
                        'name',
                        fn (Builder $query) => $query
                            ->whereRelation('tenant', 'tenant_id', '=', Filament::getTenant()->id)
                    )
                    ->preload()
                    ->searchable(),
                Forms\Components\TextInput::make('name')
                    ->reactive()
                    ->debounce(600)
                    ->afterStateUpdated(function ($state, $set) {
                        $state = Str::slug($state);
                        $set('slug', $state);
                    }),
                Forms\Components\TextInput::make('description'),
                Forms\Components\TextInput::make('slug')
                ->disabled()
                ->dehydrated()
                ->required(),

                // Forms\Components\Fieldset::make('Dados')
                //     ->schema([
                //         Forms\Components\Select::make('store_id')
                //             ->relationship(
                //                 'store',
                //                 'name',
                //                 fn (Builder $query) => $query
                //                     ->whereRelation('tenant', 'tenant_id', '=', Filament::getTenant()->id)
                //             )
                //             ->preload()
                //             ->searchable(),
                //         Forms\Components\TextInput::make('name')
                //             ->reactive()
                //             ->debounce(600)
                //             ->afterStateUpdated(function ($state, $set) {
                //                 $state = Str::slug($state);
                //                 $set('slug', $state);
                //             }),
                //         Forms\Components\TextInput::make('description'),
                //         Forms\Components\TextInput::make('slug')
                //             ->disabled()
                //             ->dehydrated()
                //             ->required(),
                //     ])

                // Forms\Components\Tabs::make('Tabs')
                //     ->schema([
                //         Forms\Components\Tabs\Tab::make('Tab 1')->schema([
                //             Forms\Components\Select::make('store_id')
                //                 ->relationship(
                //                     'store',
                //                     'name',
                //                     fn (Builder $query) => $query
                //                         ->whereRelation('tenant', 'tenant_id', '=', Filament::getTenant()->id)
                //                 )
                //                 ->preload()
                //                 ->searchable(),
                //             Forms\Components\TextInput::make('name')
                //                 ->reactive()
                //                 ->debounce(600)
                //                 ->afterStateUpdated(function ($state, $set) {
                //                     $state = Str::slug($state);
                //                     $set('slug', $state);
                //                 }),
                //         ]),
                //         Forms\Components\Tabs\Tab::make('Tab 2')->schema([
                //             Forms\Components\TextInput::make('description'),
                //             Forms\Components\TextInput::make('slug')
                //                 ->disabled()
                //                 ->dehydrated()
                //                 ->required(),
                //         ]),
                //     ])

                // Forms\Components\Wizard::make()
                //     ->schema([
                //         Forms\Components\Wizard\Step::make('Step 1')->schema([
                //             Forms\Components\Select::make('store_id')
                //                 ->relationship(
                //                     'store',
                //                     'name',
                //                     fn (Builder $query) => $query
                //                         ->whereRelation('tenant', 'tenant_id', '=', Filament::getTenant()->id)
                //                 )
                //                 ->preload()
                //                 ->searchable(),
                //             Forms\Components\TextInput::make('name')
                //                 ->reactive()
                //                 ->required()
                //                 ->debounce(600)
                //                 ->afterStateUpdated(function ($state, $set) {
                //                     $state = Str::slug($state);
                //                     $set('slug', $state);
                //                 }),
                //         ]),
                //         Forms\Components\Wizard\Step::make('Step 2')->schema([
                //             Forms\Components\TextInput::make('description'),
                //             Forms\Components\TextInput::make('slug')
                //                 ->disabled()
                //                 ->dehydrated()
                //                 ->required(),
                //         ]),
                //     ])
    ])/* ->columns(1) */;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->date('d/m/Y'),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        // return self::getModel()::count();
        return self::getModel()::loadWithTenant()->count();
    }
}
