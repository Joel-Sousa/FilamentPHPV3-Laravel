<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class OrdersByMonthChart extends ChartWidget
{
    protected static ?string $pollingInterval = null;
    
    protected static ?string $heading = 'Total Vendas Mes';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    protected function getData(): array
    {

        $data = Trend::model(Order::class)
        ->between(
            // start: now()->startOfYear(),
            start: now()->subYear(),
            // end: now()->endOfYear(),
            end: now(),
        )
        ->perMonth()
        ->count();
        
        return [
            'datasets' => [
                // [
                //     'label' => 'Blog posts created',
                //     'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                //     'backgroundColor' => '#36a2eb',
                //     'borderColor' => '#9bd0f5',
                // ],
                [
                    'label' => 'Blog posts',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            // 'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),

        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
