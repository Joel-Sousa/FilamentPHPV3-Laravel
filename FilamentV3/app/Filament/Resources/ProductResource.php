<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $modelLabel = 'Produt';

    protected static ?string $pluralModelLabel = 'Produtoss';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Admin';

    protected static ?string $navigationLabel = 'Produtos';

    protected static ?int $navigationSort = 2;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->reactive()
                    ->debounce(600)
                    ->afterStateUpdated(function ($state, $set) {
                        $state = Str::slug($state);
                        $set('slug', $state);
                    }),
                Forms\Components\Select::make('store_id')
                    ->relationship(
                        'store',
                        'name',
                        fn (Builder $query) => $query
                            ->whereRelation('tenant', 'tenant_id', '=', Filament::getTenant()->id)
                    )
                    ->preload()
                    ->searchable(),
                Forms\Components\TextInput::make('description'),
                Forms\Components\RichEditor::make('body')->required(),

                Forms\Components\Section::make('Dados')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->columnSpan(1)
                            ->required(),
                        Forms\Components\Toggle::make('status')
                            ->columnSpan(1)
                            ->required(),
                        Forms\Components\TextInput::make('stock')
                            ->columnSpan(1)
                            ->required(),
                        Forms\Components\TextInput::make('slug')
                            ->columnSpan(2)
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                        Forms\Components\Select::make('categories')
                            ->multiple()
                            ->relationship(
                                'categories',
                                'name',
                                function (Builder $query, Get $get) {
                                    $data = $query->whereRelation('tenant', 'tenant_id', '=', Filament::getTenant()->id)
                                        ->whereRelation('store', 'store_id', '=', $get('store_id'));
                                        // dd($data);
                                    return $data;
                                }
                            )
                        ->preload()
                        ->searchable()
                        ,
                    ])->columns(5)

            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('BRL'),
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
            RelationManagers\PhotosRelationManager::class
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

    public static function getNavigationBadge(): ?string
    {
        // return self::getModel()::count();
        return self::getModel()::loadWithTenant()->count();
    }
}
