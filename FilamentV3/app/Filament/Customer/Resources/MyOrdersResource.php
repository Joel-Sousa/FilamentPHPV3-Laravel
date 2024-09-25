<?php

namespace App\Filament\Customer\Resources;

use App\Filament\Customer\Resources\MyOrdersResource\Pages;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MyOrdersResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Meus pedidos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->whereUserId(auth()->id()))
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('§')->sortable(),
                Tables\Columns\TextColumn::make('items_count')->searchable(),
                Tables\Columns\TextColumn::make('orderTotal')->money('BRL'),
                Tables\Columns\TextColumn::make('created_at')->date('d/m/Y H:i:s'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function infolist(Infolists\Infolist $infolist): Infolists\Infolist
    {
        return $infolist->schema([

            Infolists\Components\Split::make([
                Infolists\Components\Section::make([
                    Infolists\Components\TextEntry::make('code'),
                    Infolists\Components\TextEntry::make('orderTotal')->money('BRL'),
                    Infolists\Components\TextEntry::make('items_count'),
                ])->columns(3),
                Infolists\Components\Split::make([
                    Infolists\Components\Section::make([
                        Infolists\Components\TextEntry::make('user.name'),
                        Infolists\Components\TextEntry::make('created_at')->date('d/m/Y H:i:s'),
                    ]),
                ])->grow(false),
            ])->columnSpanFull()
            ->from('md'),


            Infolists\Components\Section::make([
                Infolists\Components\RepeatableEntry::make('items')->schema([
                    Infolists\Components\TextEntry::make('product.name')->label('Produto'),
                    Infolists\Components\TextEntry::make('amount'),
                    Infolists\Components\TextEntry::make('order_value')->money('BRL'),
                ]),
            ])->columns(1),


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
            'index' => Pages\ListMyOrders::route('/'),
            // 'create' => Pages\CreateMyOrders::route('/create'),
            'view' => Pages\ViewMyOrders::route('/{record}'),
            // 'edit' => Pages\EditMyOrders::route('/{record}/edit'),
        ];
    }
}
