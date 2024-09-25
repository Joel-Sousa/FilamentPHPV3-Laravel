<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoreResource\Pages;
use App\Filament\Resources\StoreResource\RelationManagers;
use App\Models\Store;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;


class StoreResource extends Resource
{
    protected static ?string $model = Store::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Admin';

    protected static ?int $navigationSort = 1;
    
    protected static ?string $navigationLabel = 'Lojas';


    public static function getNavigationIcon(): Htmlable
    {
        return new class implements Htmlable
        {
            public function toHtml()
            {
                return '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#5A5A62" viewBox="0 0 256 256"><path d="M208,32H48A16,16,0,0,0,32,48V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V48A16,16,0,0,0,208,32Zm-16,72h16v48H192Zm16-16H192V48h16ZM48,48H176V208H48ZM208,208H192V168h16v40Zm-56.25-42a39.76,39.76,0,0,0-17.19-23.34,32,32,0,1,0-45.12,0A39.84,39.84,0,0,0,72.25,166a8,8,0,0,0,15.5,4c2.64-10.25,13.06-18,24.25-18s21.62,7.73,24.25,18a8,8,0,1,0,15.5-4ZM96,120a16,16,0,1,1,16,16A16,16,0,0,1,96,120Z"></path></svg>';
            }
        };
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->reactive()
                    ->debounce(600)
                    ->afterStateUpdated(function ($state, $set) {
                        // $state = Str::slug($state);
                        $state = str()->of($state)->slug();
                        $set('slug', $state);
                    }),
                Forms\Components\TextInput::make('phone')
                    ->required(),
                Forms\Components\RichEditor::make('about')
                    ->required(),
                Forms\Components\FileUpload::make('logo')
                    ->directory('stores')
                    ->disk('public')
                    // ->rules(['image'])
                    ->image(),
                Forms\Components\TextInput::make('slug')
                ->dehydrated()
                ->disabled(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\ImageColumn::make('logo')->circular(),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->date('d/m/Y'),
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
            'index' => Pages\ListStores::route('/'),
            'create' => Pages\CreateStore::route('/create'),
            'edit' => Pages\EditStore::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        // return self::getModel()::count();
        return self::getModel()::loadWithTenant()->count();
    }
}
