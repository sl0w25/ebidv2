<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectListResource\Pages;
use App\Filament\Resources\ProjectListResource\RelationManagers;
use App\Models\ProjectList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextArea;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectListResource extends Resource
{
    protected static ?string $model = ProjectList::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-plus';

    protected static ?string $navigationLabel = 'Add Project';

    protected static ?string $modelLabel = 'Project List';

    protected static ?int $navigationSort = 1;

    public $id;
    
    public static function getModelLabel(): string
{
    return __('Project');
}

public function mount(): void 
{
    $this->form->fill();
}

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                ->description('Project Details')
                ->schema([
               
                 Hidden::make('project_id')
                // ->disabled()
                ->default(ProjectList::generateAutoNumber())
                ->afterStateUpdated(function($set, $state) {
                                  
                    $pr = ProjectList::find($state);
                    
                    if($pr){
                       
                        $set('project_id', $pr->id);

                    } 
                    else 
                    {

                        $set('project_id', null);
                    }
                }),

                TextArea::make('project_title')
                ->label('Project Title')
                ->columnSpanFull()
                ->required(),

                TextInput::make('abc')
                ->label('ABC')
                ->required()
                ->prefix('₱')
                ->currencyMask(thousandSeparator: ',',decimalSeparator: '.',precision: 2),

                Select::make('enduser')
                ->required()
                ->searchable()
                ->options([
                    'ADMIN - DC' => 'ADMIN - DC',
                    'ADMIN - GSS' => 'ADMIN - GSS',
                    'ADMIN - BGMS' => 'ADMIN - BGMS',
                    'ADMIN - RECORDS' => 'ADMIN - RECORDS',
                    'ADMIN - PROCUREMENT' => 'ADMIN - PROCUREMENT',
                    'ADMIN - PROPERTY AND SUPPLY' => 'ADMIN - PROPERTY AND SUPPLY',
                    'ADMIN - COA' => 'ADMIN - COA',
                    'FMD -DC' => 'FMD -DC',
                    'FMD - ACCOUNTING' => 'FMD - ACCOUNTING',
                    'FMD - BUDGET' => 'FMD - BUDGET',
                    'FMD - CASH' => 'FMD - CASH',
                    'HRMD - DC' => 'HRMD - DC',
                    'HRMD - HRWS' => 'HRMD - HRWS',
                    'HRMD - LDS' => 'HRMD - LDS',
                    'HRMD - PERSONNEL' => 'HRMD - PERSONNEL',
                    'DRMD - DC' => 'DRMD - DC',
                    'DRMD - RRLMS' => 'DRMD - RRLMS',
                    'OFFICE OF THE FIELD DIRECTOR - ORD' => 'OFFICE OF THE FIELD DIRECTOR - ORD',
                    'OFFICE OF THE FIELD DIRECTOR - OARDO' => 'OFFICE OF THE FIELD DIRECTOR - OARDO',
                    'OFFICE OF THE FIELD DIRECTOR - OARDA' => 'OFFICE OF THE FIELD DIRECTOR - OARDA',
                    'OFFICE OF THE FIELD DIRECTOR - SMU' => 'OFFICE OF THE FIELD DIRECTOR - SMU',
                    'OFFICE OF THE FIELD DIRECTOR - RJJWC' => 'OFFICE OF THE FIELD DIRECTOR - RJJWC',
                    'PANTAWID - DC' => 'PANTAWID - DC',
                    'PANTAWID - ICT' => 'PANTAWID - ICT',
                    'PROTECTIVE - DC' => 'PROTECTIVE - DC',
                    'PROTECTIVE - CIS' => 'PROTECTIVE - CIS',
                    'PROTECTIVE - TRAVEL CLEARANCE' => 'PROTECTIVE - TRAVEL CLEARANCE',
                    'PROTECTIVE - ARRS' => 'PROTECTIVE - ARRS',
                    'PROTECTIVE - RCC' => 'PROTECTIVE - RCC',
                    'PROTECTIVE - CBSS' => 'PROTECTIVE - CBSS',
                    'PROTECTIVE - SFP' => 'PROTECTIVE - SFP',
                    'PPD - DC' => 'PPD - DC',
                    'PPD - PDPS' => 'PPD - PDPS',
                    'PPD - NHTS' => 'PPD - NHTS',
                    'PPD - SOCTECH' => 'PPD - SOCTECH',
                    'PPD - STANDARDS' => 'PPD - STANDARDS',
                    'PPD - CBU' => 'PPD - CBU',
                    'PPD - ICTMS' => 'PPD - ICTMS',
                    'PROMOTIVE - DC' => 'PROMOTIVE - DC',
                    'PROMOTIVE -KC' => 'PROMOTIVE -KC',
                    'PROMOTIVE - SLP' => 'PROMOTIVE - SLP',
                    'PROMOTIVE - EPAHP' => 'PROMOTIVE - EPAHP',
                    'OFD - Special Concerns Section' => 'OFD - Special Concerns Section',                  
                ])->native(false),

                    DateTimePicker::make('submission_date')
                    ->label('Deadline of Submission')
                    ->required()
                    ->displayFormat('M d, Y h:i A')
                    ->format('M d, Y h:i A')
                    //->extraAttributes(['data-flatpickr' => '{"enableSeconds": false}']) 
                    ->timezone('Singapore'),
                   // ->minDate(now()->subDay(30)),

                    Grid::make(2) // Create a grid with 3 columns
                    ->schema([
                        FileUpload::make('itb')
                            ->label('ITB')
                            ->directory('uploads/itb') 
                            ->acceptedFileTypes(['application/pdf'])
                            ->columnSpan(1)
                            ->required(),
                
                        FileUpload::make('sbb')
                            ->label('SBB (If Applicable)')
                            ->directory('uploads/sbb') 
                            ->acceptedFileTypes(['application/pdf'])
                            
                            ->columnSpan(1)
                            
                            ])

                     ])->columns(3),

               
                ])->statePath('data');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project_title')
                ->label('Project Title')
                ->wrap(),

                TextColumn::make('abc')
                ->label('ABC')
                ->currency('PHP'),

                TextColumn::make('enduser')
                ->label('End User')
                ->wrap(),

                TextColumn::make('submission_date')
                ->label('Deadline of Submission')
                ->wrap()


            ])
            ->filters([
                //
            ])
            ->actions([
               // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
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
            'index' => Pages\ListProjectLists::route('/'),
            'create' => Pages\CreateProjectList::route('/create'),
            'edit' => Pages\EditProjectList::route('/{record}/edit'),
        ];
    }
}
