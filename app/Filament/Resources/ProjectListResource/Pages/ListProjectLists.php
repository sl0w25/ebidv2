<?php

namespace App\Filament\Resources\ProjectListResource\Pages;

use App\Filament\Resources\ProjectListResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjectLists extends ListRecords
{
    protected static string $resource = ProjectListResource::class;

    protected static ?string $modelLabel = 'Project Lists';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Add New Project'),
        ];
    }

    


}
