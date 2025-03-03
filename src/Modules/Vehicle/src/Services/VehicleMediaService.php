<?php

namespace Modules\Vehicle\src\Services;

use Modules\Vehicle\src\Models\Vehicle;
use Modules\Vehicle\src\Models\ServiceHistory;

class VehicleMediaService
{
    public function attachDocuments(Vehicle|ServiceHistory $model, array $documents): void
    {
        foreach ($documents as $document) {
            $model->addMedia($document)
                ->usingName($this->generateDocumentName($document))
                ->toMediaCollection('documents');
        }
    }

    private function generateDocumentName($document): string
    {
        $originalName = $document->getClientOriginalName();
        $extension = $document->getClientOriginalExtension();
        $timestamp = now()->format('Ymd_His');

        return sprintf('%s_%s.%s',
            pathinfo($originalName, PATHINFO_FILENAME),
            $timestamp,
            $extension
        );
    }
}
