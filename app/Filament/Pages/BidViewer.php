<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Models\Bid;
use App\Models\ProjectList;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;
use Filament\Forms\Components\TextInput\Mask;
use Livewire\Component;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BidViewer extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public ?array $data = []; 

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.bid-viewer';

    protected static ?int $navigationSort = 2;

   
    //public $password;

    public $id;

    public $abc;

    public $enduser;

    public $deadline;

    public $selectedProject = null;

    public $project;

    public $pr;

    public $linkfile;

  
    



    // public ProjectList $projectlist;

    public function mount(): void 
    {
        $this->form->fill();

        $this->loadRecords();

        $this->userRecords();
    }


    protected function loadRecords(): void
    {
        // Fetch records from the model
        $this->project = Bid::all();
    }

    protected function userRecords(): void
    {
        // Fetch records from the model
        $this->user = User::all();
    }



    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('')
          ->description('Project Information')
            ->schema([
                Select::make('project_title')
                ->label('Project Title')
                ->required()
                ->placeholder('Select a Project')
                ->columnSpanFull()
                ->reactive()
                ->searchable()
                ->getSearchResultsUsing(fn (string $search): ?array => ProjectList::where('project_title', 'like', "%{$search}%")
                                        ->limit(10)->pluck('project_title','id')
                                        ->toArray()
                                        )
                                        
                ->afterStateUpdated(function($set, $state) {
                                  
                    

                    $pr = ProjectList::find($state);
                    
                    if($pr){
                       
                        
                        $set('project_title', $pr->project_title);
                        $set('abc', $pr->abc);
                        $set('enduser', $pr->enduser);
                        $set('deadline', $pr->submission_date);
                        $set('id', $pr->id);

                        $this->selectedProject = $pr->id;

                    } 
                    else 
                    {
                        $set('company_name', null);
                        $set('project_title', null);
                        $set('abc', null);
                        $set('enduser', null);
                        $set('id', null);
                        $set('deadline', null);
                    }
                })
                ,

                TextInput::make('abc')
                ->label('Approved Budget of the Contract')
                ->prefix('₱')
                ->required()
                ->disabled()
                ->currencyMask(thousandSeparator: ',',decimalSeparator: '.',precision: 2)
                ->maxLength(15),


                TextInput::make('enduser')
                ->prefixIcon('heroicon-o-user')
                ->label('End-User')
                ->disabled()
                ->required(),

                TextInput::make('deadline')
                ->label('Closing Date')
                ->prefixIcon('heroicon-o-calendar-days')
                ->rule('date_format:M d, Y h:i A')
               // ->default(fn () => $this->deadline->created_at->format('Y-m-d H:i:s')) // Format date from the database
                ->disabled(), // Make it read-only

            ])->columns(3)
        ])->statePath('data');
    }




//   protected function getTableQuery()
//     {
//         // Define the query for retrieving bids
//         return Bid::query()->when($this->selectedProject, function ($query) {
//             return $query->where('project_id', $this->selectedProject);
//         });

      
        
//     }


    // protected function getTableColumns(): array
    // {
    //     return [

    //         TextColumn::make('id')->label('ID'),
    //         TextColumn::make('name_of_bidder_and_or_authorized_representative')->label('Name of Bidder')->wrap(),
    //         TextColumn::make('company_name')->label('Company Name'),
    //         TextColumn::make('created_at')->label('Summitted Date')->dateTime(),
    //     ];

        
    // }

    // public function updatedselectedProject($value): void
    // {
    //     $this->selectedProject = $value;
    //     $this->resetTable(); // Refresh the table when the recordId changes
    // }
 

    public function table(Table $table): Table
    {
        return $table   
            ->query(Bid::query()
            ->when($this->project, function ($query) {
                $query->where('project_id', $this->selectedProject);
                    
            })
            )
            ->columns([
                TextColumn::make('name_of_bidder_and_or_authorized_representative')->label('Name of Bidder'),
                TextColumn::make('company_name')->label('Company Name'),
                TextColumn::make('date_and_time_of_submission')->label('Date/Time of Submission')->dateTime('M d, Y h:i A'),
                IconColumn::make('status')
                ->label('On-Time')
                ->icon(function ($state) {
                    if ($state === 'ON-TIME') {
                        return 'heroicon-o-check-circle';
                    } elseif ($state === 'LATE') {
                        return 'heroicon-o-x-circle';
                    } else {
                        return 'heroicon-o-x-circle';
                    }
                })
                ->color(function ($state) {
                    if ($state === 'ON-TIME') {
                        return 'success';
                    } elseif ($state === 'LATE') {
                        return 'danger';
                    } else {
                        return 'danger';
                    }
                })
                ->tooltip(function ($state) {
                    if ($state === 'ON-TIME') {
                        return 'Submitted On Time';
                    } elseif ($state === 'LATE') {
                        return 'Late Submission';
                    } else {
                        return 'Late Submission';
                    }
                }),
                // TextColumn::make('link')->label('Link')
                // ->wrap()
                // ->formatStateUsing(fn ($state) => '<a href="' . $state . '" class="text-blue-600 underline" target="_blank">' . $state . '</a>')
                // ->html()
                // ->extraAttributes([
                //     'class' => 'btn btn-primary']),
                
                
            ])
            ->filters([
                // Define any filters if needed
            ])
                ->actions([

                    ViewAction::make('View')
                        ->label('View')
                        ->modalHeading('Bidders Information')
                        ->form([
                            TextInput::make('company_name')
                                ->label('Company Name')
                                ->prefixIcon('heroicon-o-building-office')
                                ->required(),
                            TextInput::make('name_of_bidder_and_or_authorized_representative')
                                ->label('Name of Bidder/Authorized Representative')
                                ->prefixIcon('heroicon-o-user-circle')
                                ->required(),
                            TextInput::make('official_mobile_no')
                                ->label('Mobile No.')
                                ->prefixIcon('heroicon-o-calculator')
                                ->required(),
                            TextInput::make('date_and_time_of_submission')
                                ->label('Date Submitted')
                                ->prefixIcon('heroicon-o-calendar')
                                ->required(),
                            
                                
                        ])
                        ->action(function ($record, $data, $livewire) {
                            $linkfile = $record->linkfile;
                            // Check if the password matches the current user's password
                            if (!Hash::check($data['password'], auth()->user()->password)) {
                                // Password does not match
                                throw ValidationException::withMessages([
                                    Notification::make()
                                    ->title('Error')
                                    ->body('Password is Incorrect!')
                                    ->danger()
                                    ->seconds(2) 
                                    ->send()
                                    
                                ]);
                            }       
                            $this->js("window.open('$linkfile', '_blank');");
                                    
                        }),
                        

                    Action::make('confirmAction')
                        ->label('Download')
                        ->form([
                            TextInput::make('password')
                                ->label('Please Enter the Password of the BAC Chairperson or Vice Chairperson')
                                ->password() // Hides the password input
                                ->required(),
                        ])
                        ->action(function ($record, $data, $livewire) {
                            $linkfile = $record->linkfile;
                            // Check if the password matches the current user's password
                            if (!Hash::check($data['password'], auth()->user()->password)) {
                                // Password does not match
                                throw ValidationException::withMessages([
                                    Notification::make()
                                    ->title('Error')
                                    ->body('Password is Incorrect!')
                                    ->danger()
                                    ->seconds(2) 
                                    ->send()
                                    
                                ]);
                            }       
                            $this->js("window.open('$linkfile', '_blank');");
                                    
                        })
                        
                ])
            

            
            ->bulkActions([
                // Define any bulk actions if needed
            ])
            ->poll('0.5s');
    }     
    


    // protected function getActions(): array
    // {
    //     return [
    //         Action::make('openModal')
    //             ->label('Open Modal')
    //             ->action('openModal'),
    //     ];
    // }

    // public function openModal(): void
    // {
    //     $this->isModalOpen = true;
    // }

    // public function closeModal(): void
    // {
    //     $this->isModalOpen = false;
    // }

    // public function checkPasswordAndDownload()
    // {
    //     if ($this->password === $this->correctPassword) {
    //        // return 
    //     } else {
    //         $this->addError('password', 'The password is incorrect.');
    //     }
    // }

    // protected function getFormSchema(): array
    // {
    //     return [
    //         TextInput::make('password')
    //             ->password()
    //             ->label('Enter Password')
    //             ->required(),
    //     ];
    // }

    // public function save(): void
    // {
    //     // Handle the form submission logic
    //     $this->validate();
    //     $this->closeModal();
    // }


    // public function updatedselectedProject($value): void
    // {
    //     $this->selectedProject = $value;
    //     $this->emitSelf('refresh');
    // }

   

//     public function render(): View
//     {
//     return view('filament.pages.BidViewer', [
//         'users' => $this->selectedProject,
//     ]);
//    }
}
