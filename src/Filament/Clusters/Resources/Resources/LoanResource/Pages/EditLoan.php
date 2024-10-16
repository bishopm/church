<?php

namespace Bishopm\Church\Filament\Clusters\Resources\Resources\LoanResource\Pages;

use Bishopm\Church\Filament\Clusters\Resources\Resources\LoanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLoan extends EditRecord
{
    protected static string $resource = LoanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
