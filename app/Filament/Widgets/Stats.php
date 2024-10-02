<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\Bid;
use App\Models\ProjectList;
use Carbon\CarbonImmutable;

use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;

class Stats extends BaseWidget
{
    use InteractsWithPageFilters;
    
    protected static string $routePath = 'Stats';



    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;
 
        return [

            Stat::make(
                label: 'Total Projects',
                value: ProjectList::query()
                       ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                       ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                       ->count()
            ),
            Stat::make(
                label: 'Total Submitted Bids',
                value: Bid::query()
                       ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                       ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                       ->count()
            ),
            // Stat::make('Average time on page', '3:12'),





            // BaseWidget\Stat::make(
            //     label: 'Total Projects',
            //     value: ProjectList::query()
            //         ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
            //         ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
            //         ->count(),
            // ),
                    
            
        ];

        
    }
}
