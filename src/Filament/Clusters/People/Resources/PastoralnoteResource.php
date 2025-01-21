<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources;

use Bishopm\Church\Filament\Clusters\People;
use Bishopm\Church\Filament\Clusters\People\Resources\PastoralnoteResource\Pages;
use Bishopm\Church\Filament\Clusters\People\Resources\PastoralnoteResource\RelationManagers;
use Bishopm\Church\Models\Household;
use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Pastor;
use Bishopm\Church\Models\Pastoralnote;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class PastoralnoteResource extends Resource
{
    protected static ?string $model = Pastoralnote::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $modelLabel = 'Pastoral note';

    protected static ?string $cluster = People::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('pastoraldate')
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
                    ->required(),
                Forms\Components\Textarea::make('details')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\MorphToSelect::make('pastoralnotable')->label('Individual or Household')
                ->types([
                    Forms\Components\MorphToSelect\Type::make(Individual::class)
                        ->getSearchResultsUsing(fn (string $search): array => Individual::where('firstname', 'like', "%{$search}%")->orWhere('surname', 'like', "%{$search}%")
                        ->get()->pluck('fullname','id')->toArray())
                        ->getOptionLabelFromRecordUsing(function ($state){
                            $indiv=Individual::find($state->id);
                            return $indiv->firstname . " " . $indiv->surname;
                        })
                        ->titleAttribute('id'),
                    Forms\Components\MorphToSelect\Type::make(Household::class)
                        ->titleAttribute('addressee'),
                    ])
                ->searchable()
                ->model(Pastoralnote::class),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pastoralnotable_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pastoraldate')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pastor.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('pastoralnotable_type')
                    ->searchable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListPastoralnotes::route('/'),
            'create' => Pages\CreatePastoralnote::route('/create'),
            'edit' => Pages\EditPastoralnote::route('/{record}/edit'),
        ];
    }
}
