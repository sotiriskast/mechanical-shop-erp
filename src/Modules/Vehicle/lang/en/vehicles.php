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
        'documents' => 'Documents'
    ],
    'validation' => [
        'license_plate_unique' => 'The license plate has already been taken',
        'vin_unique' => 'The VIN has already been taken',
        'document_size' => 'Document size must not exceed 10MB',
        'document_type' => 'Document must be a PDF, DOC, DOCX, JPG, JPEG, or PNG file'
    ],
    'errors' => [
        'create_failed' => 'Failed to create vehicle',
        'update_failed' => 'Failed to update vehicle',
        'delete_failed' => 'Failed to delete vehicle',
        'fetch_failed' => 'Failed to fetch vehicles',
        'search_failed' => 'Failed to search vehicles',
        'not_found' => 'Vehicle not found',
        'license_plate_exists' => 'A vehicle with this license plate already exists',
        'vin_exists' => 'A vehicle with this VIN already exists',
        'has_related_work_orders' => 'Cannot delete vehicle with related work orders',
        'vehicle_not_found' => 'Vehicle not found',
        'service_history' => [
            'create_failed' => 'Failed to create service history',
            'update_failed' => 'Failed to update service history',
            'delete_failed' => 'Failed to delete service history',
            'fetch_failed' => 'Failed to fetch service history',
            'not_found' => 'Service history not found',
        ]
    ],
    'messages' => [
        'created' => 'Vehicle created successfully',
        'updated' => 'Vehicle updated successfully',
        'deleted' => 'Vehicle deleted successfully',
        'service_history' => [
            'created' => 'Service history created successfully',
            'updated' => 'Service history updated successfully',
            'deleted' => 'Service history deleted successfully',
        ]
    ],
    'status' => [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'sold' => 'Sold'
    ],
    'service_history' => [
        'fields' => [
            'vehicle_id' => 'Vehicle',
            'service_date' => 'Service Date',
            'service_type' => 'Service Type',
            'description' => 'Description',
            'mileage' => 'Mileage',
            'mileage_unit' => 'Mileage Unit',
            'technician_name' => 'Technician Name',
            'cost' => 'Cost',
            'status' => 'Status',
            'notes' => 'Notes',
            'work_order_id' => 'Work Order',
            'documents' => 'Documents'
        ],
        'status' => [
            'completed' => 'Completed',
            'scheduled' => 'Scheduled',
            'in_progress' => 'In Progress',
            'cancelled' => 'Cancelled'
        ]
    ],
    'notifications' => [
        'vehicle_created' => [
            'title' => 'New Vehicle',
            'body' => 'A new vehicle has been added: :make :model (:license_plate)'
        ],
        'vehicle_updated' => [
            'title' => 'Vehicle Updated',
            'body' => 'Vehicle :make :model (:license_plate) has been updated'
        ],
        'vehicle_deleted' => [
            'title' => 'Vehicle Deleted',
            'body' => 'Vehicle :make :model (:license_plate) has been deleted'
        ],
        'service_history_created' => [
            'title' => 'New Service Record',
            'body' => 'A new service record has been added for vehicle :license_plate'
        ],
        'service_history_updated' => [
            'title' => 'Service Record Updated',
            'body' => 'Service record has been updated for vehicle :license_plate'
        ]
    ]
];
