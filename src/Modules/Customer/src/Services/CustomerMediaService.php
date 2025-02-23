<?php

namespace Modules\Customer\src\Services;

use Modules\Customer\src\Models\Customer;

class CustomerMediaService
{
    public function attachDocuments(Customer $customer, array $documents): void
    {
        foreach ($documents as $document) {
            $customer->addMedia($document)
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
