<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources;

use Bishopm\Church\Filament\Clusters\Admin;
use Bishopm\Church\Filament\Clusters\Admin\Resources\TaskResource\Pages;
use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TaskResource extends Resource
{
    protected static ?int $navigationSort = 4;

    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-numbered-list';

    protected static ?string $cluster = Admin::class;

    public static function form(Form $form): Form

    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\Select::make('individual_id')
                    ->label('Assigned to')
                    ->options(Individual::orderBy('firstname')->get()->pluck('fullname', 'id'))
                    ->searchable(),
                Forms\Components\DatePicker::make('duedate'),
                Forms\Components\Select::make('status')
                    ->options([
                        'todo'=>'To do',
                        'doing'=>'Underway',
                        'someday'=>'Some day',
                        'done'=>'Done'
                    ])
                    ->placeholder('')
                    ->required()
                    ->default('todo'),
                Forms\Components\Textarea::make('statusnote')
                    ->label('Status note (optional, appears in meeting minutes)'),
                Forms\Components\Select::make('tags')->label('Project')
                    ->relationship('tags','name',modifyQueryUsing: fn (Builder $query) => $query->where('type','task'))
                    ->multiple()
                    ->createOptionForm([
                        Forms\Components\Grid::make()
                            ->columns(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                    ->required(),
                                Forms\Components\TextInput::make('type')
                                    ->default('task')
                                    ->readonly()
                                    ->required(),
                                Forms\Components\TextInput::make('slug')
                                    ->required(),
                            ])
                    ]),
                Forms\Components\Select::make('visibility')
                    ->options([
                        'public'=>'Public',
                        'private'=>'Private'
                    ])
                    ->placeholder('')
                    ->required()
                    ->default('public'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->wrap(),
                Tables\Columns\TextColumn::make('individual.fullname')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('duedate')
                    ->date()
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'todo'=>'To do',
                        'doing'=>'Underway',
                        'someday'=>'Some day',
                        'done'=>'Done'
                    ]),
                Tables\Columns\TextColumn::make('tags.name')
                    ->badge()
                    ->label('Project')
                    ->forceSearchCaseInsensitive(true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('visibility')
                    ->searchable()
            ])
            ->filters([
                SelectFilter::make('status')->label('Status')
                    ->options([
                        'todo'=>'To do',
                        'doing'=>'Underway',
                        'someday'=>'Some day',
                        'done'=>'Done'
                    ]),
                SelectFilter::make('individual_id')->label('Assigned to')
                    ->options(function () {
                        $indivs=Individual::whereHas('tasks')->groupBy('id','firstname','surname')->select('id','firstname','surname')->get();
                        foreach ($indivs as $indiv){
                            $data[$indiv->id]=$indiv->firstname . " " . $indiv->surname;
                        }
                        return $data;
                    })
                    ->default(function (){
                        return Individual::where('user_id',Auth::user()->id)->first()->id;
                    }),
                Filter::make('hide_completed')
                    ->query(fn (Builder $query): Builder => $query->where('status', '<>', 'done'))
                    ->default(),
                TrashedFilter::make()
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
            'taskboard' => Pages\TaskBoard::route('/taskboard')
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
