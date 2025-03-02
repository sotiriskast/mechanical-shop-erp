<?php

namespace Modules\Vehicle\src\Services;

use Illuminate\Database\Eloquent\Model;

class VehicleMediaService
{
    public function attachDocuments(Model $model, array $documents, string $collection = 'documents'): void
    {
        foreach ($documents as $document) {
            $model->addMedia($document)
                ->usingName($this->generateDocumentName($document))
                ->toMediaCollection($collection);
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
