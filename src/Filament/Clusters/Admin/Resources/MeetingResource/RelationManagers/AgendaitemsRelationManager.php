<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\MeetingResource\RelationManagers;

use Bishopm\Church\Models\Group;
use Bishopm\Church\Models\Individual;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Livewire\Component as Livewire;

class AgendaitemsRelationManager extends RelationManager
{
    protected static string $relationship = 'agendaitems';

    protected static ?string $modelLabel = 'agenda item';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('heading')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('sortorder')
                    ->required()
                    ->default(function (Livewire $livewire) {
                        return count(($livewire->ownerRecord->agendaitems));
                    })
                    ->numeric(),
                Forms\Components\TextInput::make('level')
                    ->required()
                    ->default(1)
                    ->numeric(),
                Forms\Components\Textarea::make('minute')
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('tasks')
                    ->relationship()
                    ->hiddenOn('create')
                    ->schema([
                        Forms\Components\TextInput::make('description')->required()->label('Action'),
                        Forms\Components\Select::make('individual_id')->label('Responsibility')
                            ->options(function (){
                                $group_id=$this->getOwnerRecord()->group_id;
                                $group=Group::with('individuals')->where('id',$group_id)->first();
                                foreach ($group->individuals as $indiv){
                                    $data[$indiv->id]=$indiv->firstname . " " . $indiv->surname;
                                }
                                $secretary=Individual::find(setting('admin.church_secretary'));
                                $data[$secretary->id]=$secretary->firstname . " " . $secretary->surname;
                                asort($data);
                                return $data;
                            })
                            ->required(),
                        Forms\Components\TextInput::make('statusnote')->label('Status note'),
                        Forms\Components\DatePicker::make('duedate')->label('Due'),
                        Forms\Components\Hidden::make('agendaitem_id')
                            ->default($this->mountedTableActionRecord),
                        Forms\Components\Hidden::make('status')
                            ->default('todo'),
                        Forms\Components\Hidden::make('visibility')
                            ->default('public')
                    ])->columnSpanFull()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->recordTitleAttribute('meetingdatetime')
            ->columns([
                Tables\Columns\TextColumn::make('heading'),
                Tables\Columns\TextColumn::make('sortorder')->label('Order'),
                Tables\Columns\TextColumn::make('level'),
                Tables\Columns\TextColumn::make('tasks')
                    ->formatStateUsing(function ($state){
                        return substr_count($state,'{');
                    })
            ])
            ->filters([
                //
            ])
            ->reorderable('sortorder')
            ->defaultSort('sortorder')
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
