<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\FormResource\Pages;

use Bishopm\Church\Filament\Clusters\Admin\Resources\FormResource;
use Bishopm\Church\Models\Form;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditForm extends EditRecord
{
    protected static string $resource = FormResource::class;

    public string $pdfUrl;

    protected $listeners = ['form-items-updated' => 'updatePdfUrl'];

    public function mount($record): void
    {
        parent::mount($record);
        $this->pdfUrl = url('/') . '/admin/reports/form/' . $this->record->id;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function updatePdfUrl()
    {
        $this->pdfUrl = url('/') . '/admin/reports/form/' . $this->record->id . '?t=' . now()->timestamp;
    }
}
