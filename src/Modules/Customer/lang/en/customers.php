<?php

return [
    'fields' => [
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'mobile' => 'Mobile',
        'company_name' => 'Company Name',
        'vat_number' => 'VAT Number',
        'tax_office' => 'Tax Office',
        'billing_address' => 'Billing Address',
        'shipping_address' => 'Shipping Address',
        'city' => 'City',
        'postal_code' => 'Postal Code',
        'country' => 'Country',
        'notes' => 'Notes',
        'is_active' => 'Status',
        'documents' => 'Documents'
    ],
    'validation' => [
        'vat_number_format' => 'The VAT number must be in Cyprus format (e.g., CY123456789X)',
        'phone_number_format' => 'The phone number must be in Cyprus format (+35712345678)',
        'postal_code_format' => 'The postal code must be in Cyprus format (4 digits)',
        'document_size' => 'Document size must not exceed 10MB',
        'document_type' => 'Document must be a PDF, DOC, DOCX, JPG, JPEG, or PNG file'
    ],
    'errors' => [
        'create_failed' => 'Failed to create customer',
        'update_failed' => 'Failed to update customer',
        'delete_failed' => 'Failed to delete customer',
        'fetch_failed' => 'Failed to fetch customers',
        'not_found' => 'Customer not found',
        'has_related_records' => 'Cannot delete customer with related records (vehicles, work orders, or invoices)'
    ],
    'messages' => [
        'created' => 'Customer created successfully',
        'updated' => 'Customer updated successfully',
        'deleted' => 'Customer deleted successfully'
    ],
    'status' => [
        'active' => 'Active',
        'inactive' => 'Inactive'
    ]
];
