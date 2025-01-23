<?php

namespace App\Filament\Pages;

use App\Models\Store;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Pages\Dashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Dashboard as DashboardFilament;


class DashboardPage extends DashboardFilament
{
    use HasFiltersForm;

    // protected static ?string $navigationIcon = 'heroicon-o-home';
    // protected static string $view = 'filament.pages.dashboard-page';
    // protected static ?string $title = 'Dash';

    public function filtersForm(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('store_id')
                ->options(fn () => Store::pluck('name', 'id')),
            Forms\Components\DatePicker::make('startDate'),
            Forms\Components\DatePicker::make('endDate'),
        ]);
    }
}
