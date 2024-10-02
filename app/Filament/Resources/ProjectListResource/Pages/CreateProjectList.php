<?php

namespace App\Filament\Resources\ProjectListResource\Pages;

use App\Models\ProjectList;
use App\Filament\Resources\ProjectListResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProjectList extends CreateRecord
{
    protected static string $resource = ProjectListResource::class;


    // public static function handleRecordCreation(array $data): void
    // {
    //    $data['project_id'] = ProjectList::generateAutoNumber(); // Ensure this method exists

    //     // Create the project list using the validated data
    //     ProjectList::create($data);
    // }
   

}
