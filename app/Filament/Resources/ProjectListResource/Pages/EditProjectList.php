<?php

namespace App\Filament\Resources\ProjectListResource\Pages;

use App\Filament\Resources\ProjectListResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjectList extends EditRecord
{
    protected static string $resource = ProjectListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
