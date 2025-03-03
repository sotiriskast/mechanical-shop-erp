<?php

return [
    'fields' => [
        'license_plate' => 'Αριθμός Κυκλοφορίας',
        'vin' => 'Αριθμός Πλαισίου',
        'make' => 'Κατασκευαστής',
        'model' => 'Μοντέλο',
        'year' => 'Έτος',
        'color' => 'Χρώμα',
        'engine_number' => 'Αριθμός Κινητήρα',
        'engine_type' => 'Τύπος Κινητήρα',
        'transmission' => 'Κιβώτιο Ταχυτήτων',
        'mileage' => 'Χιλιόμετρα',
        'mileage_unit' => 'Μονάδα Χιλιομέτρων',
        'notes' => 'Σημειώσεις',
        'status' => 'Κατάσταση',
        'customer_id' => 'Πελάτης',
        'documents' => 'Έγγραφα'
    ],
    'validation' => [
        'license_plate_unique' => 'Ο αριθμός κυκλοφορίας χρησιμοποιείται ήδη',
        'vin_unique' => 'Ο αριθμός πλαισίου χρησιμοποιείται ήδη',
        'document_size' => 'Το μέγεθος του εγγράφου δεν πρέπει να υπερβαίνει τα 10MB',
        'document_type' => 'Το έγγραφο πρέπει να είναι PDF, DOC, DOCX, JPG, JPEG, ή PNG'
    ],
    'errors' => [
        'create_failed' => 'Αποτυχία δημιουργίας οχήματος',
        'update_failed' => 'Αποτυχία ενημέρωσης οχήματος',
        'delete_failed' => 'Αποτυχία διαγραφής οχήματος',
        'fetch_failed' => 'Αποτυχία ανάκτησης οχημάτων',
        'search_failed' => 'Αποτυχία αναζήτησης οχημάτων',
        'not_found' => 'Το όχημα δεν βρέθηκε',
        'license_plate_exists' => 'Υπάρχει ήδη όχημα με αυτόν τον αριθμό κυκλοφορίας',
        'vin_exists' => 'Υπάρχει ήδη όχημα με αυτόν τον αριθμό πλαισίου',
        'has_related_work_orders' => 'Δεν είναι δυνατή η διαγραφή οχήματος με συνδεδεμένες εντολές εργασίας',
        'vehicle_not_found' => 'Το όχημα δεν βρέθηκε',
        'service_history' => [
            'create_failed' => 'Αποτυχία δημιουργίας ιστορικού συντήρησης',
            'update_failed' => 'Αποτυχία ενημέρωσης ιστορικού συντήρησης',
            'delete_failed' => 'Αποτυχία διαγραφής ιστορικού συντήρησης',
            'fetch_failed' => 'Αποτυχία ανάκτησης ιστορικού συντήρησης',
            'not_found' => 'Το ιστορικό συντήρησης δεν βρέθηκε',
        ]
    ],
    'messages' => [
        'created' => 'Το όχημα δημιουργήθηκε με επιτυχία',
        'updated' => 'Το όχημα ενημερώθηκε με επιτυχία',
        'deleted' => 'Το όχημα διαγράφηκε με επιτυχία',
        'service_history' => [
            'created' => 'Το ιστορικό συντήρησης δημιουργήθηκε με επιτυχία',
            'updated' => 'Το ιστορικό συντήρησης ενημερώθηκε με επιτυχία',
            'deleted' => 'Το ιστορικό συντήρησης διαγράφηκε με επιτυχία',
        ]
    ],
    'status' => [
        'active' => 'Ενεργό',
        'inactive' => 'Ανενεργό',
        'sold' => 'Πωλημένο'
    ],
    'service_history' => [
        'fields' => [
            'vehicle_id' => 'Όχημα',
            'service_date' => 'Ημερομηνία Συντήρησης',
            'service_type' => 'Τύπος Συντήρησης',
            'description' => 'Περιγραφή',
            'mileage' => 'Χιλιόμετρα',
            'mileage_unit' => 'Μονάδα Χιλιομέτρων',
            'technician_name' => 'Όνομα Τεχνικού',
            'cost' => 'Κόστος',
            'status' => 'Κατάσταση',
            'notes' => 'Σημειώσεις',
            'work_order_id' => 'Εντολή Εργασίας',
            'documents' => 'Έγγραφα'
        ],
        'status' => [
            'completed' => 'Ολοκληρωμένη',
            'scheduled' => 'Προγραμματισμένη',
            'in_progress' => 'Σε Εξέλιξη',
            'cancelled' => 'Ακυρωμένη'
        ]
    ],
    'notifications' => [
        'vehicle_created' => [
            'title' => 'Νέο Όχημα',
            'body' => 'Ένα νέο όχημα προστέθηκε: :make :model (:license_plate)'
        ],
        'vehicle_updated' => [
            'title' => 'Ενημέρωση Οχήματος',
            'body' => 'Το όχημα :make :model (:license_plate) ενημερώθηκε'
        ],
        'vehicle_deleted' => [
            'title' => 'Διαγραφή Οχήματος',
            'body' => 'Το όχημα :make :model (:license_plate) διαγράφηκε'
        ],
        'service_history_created' => [
            'title' => 'Νέα Καταχώρηση Συντήρησης',
            'body' => 'Μια νέα καταχώρηση συντήρησης προστέθηκε για το όχημα :license_plate'
        ],
        'service_history_updated' => [
            'title' => 'Ενημέρωση Καταχώρησης Συντήρησης',
            'body' => 'Η καταχώρηση συντήρησης ενημερώθηκε για το όχημα :license_plate'
        ]
    ]
];
