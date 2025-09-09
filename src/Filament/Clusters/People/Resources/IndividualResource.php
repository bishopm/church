<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources;

use Bishopm\Church\Filament\Clusters\People\Resources\IndividualResource\Pages;
use Bishopm\Church\Filament\Clusters\People;
use Bishopm\Church\Jobs\SendEmail;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Actions;
use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Pastor;
use Bishopm\Church\Models\Pastoralnote;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Bishopm\Church\Mail\ChurchMail;
use Bishopm\Church\Models\Anniversary;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Set;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class IndividualResource extends Resource
{
    protected static ?int $navigationSort = 1;

    protected static ?string $model = Individual::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $cluster = People::class;

    public $record;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')->columnSpanFull()->columns(2)
                ->tabs([
                    Tab::make('Personal')->schema([
                        Forms\Components\TextInput::make('surname')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('firstname')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('birthdate')
                            ->native(false)
                            ->displayFormat('Y-m-d')
                            ->format('Y-m-d'),
                        Forms\Components\FileUpload::make('image')
                            ->image(),
                        Forms\Components\Radio::make('title')
                            ->options([
                                'Dr' => 'Dr',
                                'Mr' => 'Mr',
                                'Mrs' => 'Mrs',
                                'Ms' => 'Ms',
                            ])->inline(),
                        Forms\Components\Radio::make('sex')
                            ->options([
                                'female' => 'Female',
                                'male' => 'Male',
                            ])
                            ->inline(),
                        Actions::make([
                            Action::make('Create namebadge')
                                ->hidden(fn (string $operation) => $operation === 'create')
                                ->url(
                                    fn (Individual $record) => route('reports.barcodes',['newonly' => $record->id]),
                                )
                                ]),
                        Forms\Components\Placeholder::make('lastseen')->hiddenLabel()
                            ->hidden(fn (string $operation) => $operation === 'create')
                            ->content(fn (Individual $record) => "Last service attended: " . $record->lastseen)
                    ]),
                    Tab::make('Contact')->schema([
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255)
                            ->suffixAction(
                                Action::make('emailForm')->label('Send an email')
                                ->icon('heroicon-m-envelope')
                                ->form([
                                    Forms\Components\TextInput::make('subject')->label('Subject')->required(),
                                    FileUpload::make('attachment')->preserveFilenames()->directory('attachments'),
                                    MarkdownEditor::make('body'),
                                    Forms\Components\Select::make('sender')
                                        ->selectablePlaceholder(false)
                                        ->options(function () {
                                            $user=(auth()->user());
                                            return [
                                                0 => setting('general.church_name'),
                                                $user->id => $user->name
                                            ];
                                        })
                                ])
                                ->action(function (array $data, Individual $record): void {
                                    self::sendEmail($data,$record);
                                })),
                        Forms\Components\TextInput::make('cellphone')
                            ->tel()
                            ->maxLength(255)
                            ->suffixAction(
                                Action::make('smsForm')->label('Send an SMS')
                                ->icon('heroicon-m-device-phone-mobile')
                                ->form([
                                    Forms\Components\Textarea::make('smsMessage')
                                        ->label('Message')
                                        ->required(),
                                ])
                                ->action(function (array $data, Individual $record): void {
                                    //dd($record);
                                })),
                        Forms\Components\TextInput::make('officephone')
                            ->label('Office phone')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\Select::make('household_id')
                            ->label('Household')
                            ->required()    
                            ->relationship(name: 'household', titleAttribute: 'addressee')
                            ->searchable()
                            ->createOptionModalHeading('Add a new household')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('addressee')
                                    ->required(),
                                Forms\Components\TextInput::make('address1')->label('Address 1'),
                                Forms\Components\TextInput::make('address2')->label('Address 2'),
                                Forms\Components\TextInput::make('address3')->label('Address 3'),
                                Forms\Components\TextInput::make('homephone')->label('Home phone'),
                                Forms\Components\TextInput::make('sortsurname')->required()->label('Family surname for sorting purposes'),
                            ]),
                        Section::make('Household details')
                            ->hiddenOn('create')
                            ->headerActions([
                                Action::make('Edit household record')
                                    ->url(function (Individual $record){
                                        return route('filament.admin.people.resources.households.edit', ['record' => $record->household_id]);
                                    })
                                ])
                            ->description('Changing these fields will affect all members of this household')
                            ->relationship('household')
                            ->schema([
                                Group::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('addressee')->required(),
                                        Forms\Components\TextInput::make('address1')->label('Address'),
                                        Forms\Components\TextInput::make('address2')->hiddenlabel(true),
                                        Forms\Components\TextInput::make('address3')->hiddenlabel(true)
                                    ]),
                                Group::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('homephone')->label('Home phone'),
                                        Forms\Components\TextInput::make('sortsurname')->label('Household sort surname'),
                                    ])
                            ])
                            ->columns(2),
                    ]),
                    Tab::make('Pastoral')->hiddenOn('create')
                        ->schema([
                        Forms\Components\Placeholder::make('pastoraltext')
                            ->content(function (Individual $record){
                                $anniversaries = Anniversary::where('household_id',$record->household_id)->get();
                                $pastoralnotes=Pastoralnote::where('pastoralnotable_type','individual')->where('pastoralnotable_id',$record->id)
                                ->orWhere(function ($q) use ($record) { $q->where('pastoralnotable_type', 'household')->where('pastoralnotable_id', $record->household_id);})
                                ->orderBy('pastoraldate','DESC')->get();
                                $content="<table>";
                                if (count($anniversaries)){
                                    $content=$content . "<tr><th colspan=\"2\" class=\"text-left\">Anniversaries</th><th colspan=\"2\" class=\"text-right\">";
                                    foreach ($anniversaries as $anniv){
                                        $content=$content . "<tr><td>" . svg('heroicon-o-calendar',['width'=>15,'height'=>15])->toHtml() . "</td><td>" . $anniv->anniversarydate . "</td><td colspan=\"2\" class=\"px-3\">" . $anniv->details . "</td><td></td></tr>";
                                    }
                                }
                                $edit = svg('heroicon-o-pencil-square',['width'=>15,'height'=>15])->toHtml();
                                if (count($pastoralnotes)){
                                    $content=$content . "<tr><th class=\"text-left\" colspan=\"3\">Pastoral Notes</th></tr>";
                                }
                                foreach ($pastoralnotes as $pn){
                                    if ($pn->pastoralnotable_type=="individual"){
                                        $svg = svg('heroicon-o-user',['width'=>15,'height'=>15])->toHtml();
                                    } elseif ($pn->pastoralnotable_type=="household"){
                                        $svg = svg('heroicon-o-user-group',['width'=>15,'height'=>15])->toHtml();
                                    }
                                    $content=$content . "<tr><td>" . $svg. "</td><td>" . $pn->pastoraldate . "</td><td class=\"px-3\">";
                                    $content=$content . $pn->details . "</td><td class=\"px-3\">". $pn->pastor->individual->fullname . "</td>";
                                    $content=$content . "<td><a href=\"" . route('filament.admin.people.resources.pastoralnotes.edit',$pn->id) . "\">" . $edit . "</a></td></tr>";
                                }
                                $content = $content . "</table>";
                                return new HtmlString($content);
                            })
                            ->columnSpanFull()
                            ->label(''),
                        Actions::make([
                            Action::make('Add pastoral note')
                                ->form([
                                    Grid::make(2)
                                    ->schema([
                                        Forms\Components\Select::make('care')
                                            ->options([
                                                'Sent flowers to'=>'Flowers',
                                                'Messaged'=>'Messaged',
                                                'Phoned'=>'Phoned',
                                                'Visited'=>'Visited'
                                            ])
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (Set $set, ?string $state, Individual $record) => $set('details', $state . " " . $record->firstname))
                                            ->label('Care'),
                                        Forms\Components\DatePicker::make('pastoraldate')
                                            ->label('Date')
                                            ->default(now())
                                            ->native(false)
                                            ->displayFormat('Y-m-d')
                                            ->format('Y-m-d')
                                            ->required(),
                                        Forms\Components\Select::make('pastor_id')
                                            ->label('Pastor')
                                            ->options(function () {
                                                $pastors = Pastor::with('individual')->get()->sortBy('individual.firstname');
                                                $parray=[];
                                                foreach ($pastors as $pastor){
                                                    $parray[$pastor->id] = $pastor->individual->firstname . " " . $pastor->individual->surname;
                                                }
                                                return $parray;
                                            })
                                            ->default(function (){
                                                $id=Auth::user()->id;
                                                $indiv=Individual::with('pastor')->where('user_id',$id)->first();
                                                return $indiv->pastor->id;
                                            })
                                            ->required(),
                                        Forms\Components\TextInput::make('details')->required()
                                    ])
                                ])
                            ->action(function (array $data, Individual $record): void {
                                Pastoralnote::create([
                                    'pastoraldate' => $data['pastoraldate'],
                                    'details' => $data['details'],
                                    'pastor_id' => $data['pastor_id'],
                                    'pastoralnotable_type' => 'individual',
                                    'pastoralnotable_id' => $record->id
                                ]);
                            }),
                            Action::make('Add or edit household anniversaries')
                                ->url(function (Individual $record){
                                    return url('admin/people/households') . "/" . $record->household_id . "/edit?activeRelationManager=1";
                                })
                        ])
                    ]),
                    Tab::make('Groups')->schema([
                        Forms\Components\Select::make('groups')
                            ->columnSpanFull()
                            ->multiple()
                            ->relationship('groups','groupname'),
                    ]),
                    Tab::make('Admin')->schema([
                        Forms\Components\Select::make('memberstatus')
                            ->label('Membership status')
                            ->required()
                            ->options([
                                'member' => 'Member',
                                'child' => 'Child',
                                'non-member' => 'Non-member',
                            ])
                            ->default('member'),
                        Forms\Components\TextInput::make('giving')
                            ->password()
                            ->revealable(function (){
                                foreach (Auth::user()->roles as $role){
                                    if ($role->name=="Finance"){
                                        return true;
                                    }
                                }
                                return false;
                            })
                            ->maxLength(255),
                        Forms\Components\TextInput::make('user_id')
                            ->numeric(),
                        Forms\Components\TextInput::make('uid')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('notes')
                            ->columnSpanFull(),
                        Forms\Components\Checkbox::make('welcome_email'),
                        Forms\Components\Toggle::make('nametag_exclude')->label('Exclude from nametag use check'),
                        Forms\Components\DateTimePicker::make('deleted_at'),
                        ]),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('surname')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('firstname')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('household.addressee'),
                Tables\Columns\TextColumn::make('cellphone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lastseen')->label('Last seen'),
            ])
            ->filters([
                Filter::make('hide_deleted')
                    ->query(fn (Builder $query): Builder => $query->whereNull('deleted_at'))
                    ->default()
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

    public static function sendEmail($data, $indiv){
        $data['firstname'] = $indiv['firstname'];
        if ($indiv['email']){
            $template = new ChurchMail($data);
            SendEmail::dispatch($indiv['email'], $template);
        }
        Notification::make('Email sent')->title('Email sent to ' . $indiv->firstname . " " . $indiv->surname)->send();
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIndividuals::route('/'),
            'create' => Pages\CreateIndividual::route('/create'),
            'edit' => Pages\EditIndividual::route('/{record}/edit'),
        ];
    }
}
