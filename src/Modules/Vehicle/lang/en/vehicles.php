<?php

return [
    'fields' => [
        'license_plate' => 'License Plate',
        'vin' => 'VIN',
        'make' => 'Make',
        'model' => 'Model',
        'year' => 'Year',
        'color' => 'Color',
        'engine_number' => 'Engine Number',
        'engine_type' => 'Engine Type',
        'transmission' => 'Transmission',
        'mileage' => 'Mileage',
        'mileage_unit' => 'Mileage Unit',
        'notes' => 'Notes',
        'status' => 'Status',
        'customer_id' => 'Customer',
        'documents' => 'Documents',
        'service_date' => 'Service Date',
        'service_type' => 'Service Type',
        'description' => 'Description',
        'technician_name' => 'Technician',
        'cost' => 'Cost',
        'work_order_id' => 'Work Order'
    ],
    'validation' => [
        'license_plate_unique' => 'This license plate is already registered in the system.',
        'license_plate_format' => 'The license plate must be in Cyprus format (eg. ABC123 or A123).',
        'vin_unique' => 'This VIN is already registered in the system.',
        'document_size' => 'Document size must not exceed 10MB',
        'document_type' => 'Document must be a PDF, DOC, DOCX, JPG, JPEG, or PNG file'
    ],
    'errors' => [
        'create_failed' => 'Failed to create vehicle',
        'update_failed' => 'Failed to update vehicle',
        'delete_failed' => 'Failed to delete vehicle',
        'fetch_failed' => 'Failed to fetch vehicles',
        'not_found' => 'Vehicle not found',
        'has_related_records' => 'Cannot delete vehicle with related records (work orders or invoices)',
        'create_service_history_failed' => 'Failed to create service history',
        'update_service_history_failed' => 'Failed to update service history',
        'delete_service_history_failed' => 'Failed to delete service history',
        'fetch_service_history_failed' => 'Failed to fetch service history',
        'service_history_not_found' => 'Service history not found',
    ],
    'messages' => [
        'created' => 'Vehicle created successfully',
        'updated' => 'Vehicle updated successfully',
        'deleted' => 'Vehicle deleted successfully',
        'service_history_created' => 'Service record created successfully',
        'service_history_updated' => 'Service record updated successfully',
        'service_history_deleted' => 'Service record deleted successfully'
    ],
    'status' => [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'sold' => 'Sold'
    ],
    'service_status' => [
        'completed' => 'Completed',
        'scheduled' => 'Scheduled',
        'in_progress' => 'In Progress',
        'cancelled' => 'Cancelled'
    ],
    'notifications' => [
        'vehicle_created' => [
            'title' => 'New Vehicle',
            'body' => 'A new vehicle has been added: :make :model (:year)'
        ],
        'vehicle_updated' => [
            'title' => 'Vehicle Updated',
            'body' => 'The vehicle :make :model (:year) has been updated'
        ],
        'vehicle_deleted' => [
            'title' => 'Vehicle Deleted',
            'body' => 'The vehicle :make :model (:year) has been deleted'
        ],
        'service_history_created' => [
            'title' => 'New Service Record',
            'body' => 'A new service record has been added for vehicle :make :model (:license_plate)'
        ]
    ]
];
