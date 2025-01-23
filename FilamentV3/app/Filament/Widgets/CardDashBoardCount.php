<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Facades\Filament;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CardDashBoardCount extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?string $pollingInterval = null;
    
    protected static ?int $sort = 1;

    protected function getColumns(): int
    {
        return 2;
    }

    protected function getStats(): array
    {
        // dd($this->filters);
        return [
            Stat::make('Total de clientes', Filament::getTenant()->members->count())
                ->description('Clientes ao longo do ano')
                ->chart([10, 20, 30])
                ->descriptionColor('info')
                ->icon('heroicon-m-users')
                ->descriptionIcon('heroicon-m-users'),
            Stat::make('Total de pedidos', $this->loadOrdersFiltersAndQuery()->count()),
            // Stat::make('Total de tst', 6),
        ];
    }

    private function loadOrdersFiltersAndQuery()
    {
        return Order::loadWithTenant()
            ->when($this->filters['store_id'], fn($query) => $query->whereStoreId($this->filters['store_id']))
            ->when($this->filters['startDate'], fn($query) => $query->whereCreatedAt($this->filters['startDate']))
            ->when($this->filters['endDate'], fn($query) => $query->whereCreatedAt($this->filters['endDate']));
    }
}
