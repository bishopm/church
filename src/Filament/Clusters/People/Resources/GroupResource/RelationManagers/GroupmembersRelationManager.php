<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\GroupResource\RelationManagers;

use Bishopm\Church\Models\Groupmember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Bishopm\Church\Models\Individual;
use Filament\Tables\Actions\AssociateAction;

class GroupmembersRelationManager extends RelationManager
{
    protected static string $relationship = 'groupmembers';

    protected static ?string $title = 'Group members';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('individual_id')
                    ->label('Group member')
                    ->options(Individual::orderBy('surname')->get()->pluck('fullname', 'id'))
                    ->searchable(),
                Forms\Components\Select::make('categories')->label('Service times (if applicable)')
                    ->multiple()
                    ->options(fn () => array_combine(setting('general.services'),setting('general.services'))),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitle(fn (Groupmember $record): string => "{$record->firstname} {$record->surname}")
            ->columns([
                Tables\Columns\TextColumn::make('individual.fullname')->label('Name'),
                Tables\Columns\TextColumn::make('categories')->label('Service (if applicable)'),
            ])
            ->emptyStateHeading('No group members')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('addmember')->label('Add a group member')
                ->form([
                    Forms\Components\Grid::make('addgrid')
                        ->schema([
                            Forms\Components\Select::make('individual_id')
                                ->label('Group member')
                                ->options(Individual::orderBy('surname')->get()->pluck('fullname', 'id'))
                                ->searchable(),
                            Forms\Components\Select::make('categories')->label('Service times (if applicable)')
                                ->multiple()
                                ->options(fn () => array_combine(setting('general.services'),setting('general.services')))
                        ])->columns(2)
                ])
                ->action(function (array $data, RelationManager $livewire){
                    $group_id=$livewire->getOwnerRecord()->id;
                    Groupmember::create([
                        'individual_id'=>$data['individual_id'],
                        'group_id'=>$group_id,
                        'categories'=>$data['categories']
                    ]);
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),                    
                Tables\Actions\Action::make('view person')->url(fn ($record): string => route('filament.admin.people.resources.individuals.edit', $record))->icon('heroicon-m-eye'),
                Tables\Actions\DeleteAction::make()->label('Remove'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }
}