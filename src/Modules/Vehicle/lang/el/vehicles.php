<?php

return [
    'fields' => [
        'license_plate' => 'Αριθμός Κυκλοφορίας',
        'vin' => 'Αριθμός Πλαισίου',
        'make' => 'Μάρκα',
        'model' => 'Μοντέλο',
        'year' => 'Έτος',
        'color' => 'Χρώμα',
        'engine_number' => 'Αριθμός Κινητήρα',
        'engine_type' => 'Τύπος Κινητήρα',
        'transmission' => 'Κιβώτιο Ταχυτήτων',
        'mileage' => 'Χιλιόμετρα',
        'mileage_unit' => 'Μονάδα Μέτρησης',
        'notes' => 'Σημειώσεις',
        'status' => 'Κατάσταση',
        'customer_id' => 'Πελάτης',
        'documents' => 'Έγγραφα',
        'service_date' => 'Ημερομηνία Σέρβις',
        'service_type' => 'Τύπος Σέρβις',
        'description' => 'Περιγραφή',
        'technician_name' => 'Τεχνικός',
        'cost' => 'Κόστος',
        'work_order_id' => 'Εντολή Εργασίας'
    ],
    'validation' => [
        'license_plate_unique' => 'Αυτός ο αριθμός κυκλοφορίας είναι ήδη καταχωρημένος στο σύστημα.',
        'license_plate_format' => 'Ο αριθμός κυκλοφορίας πρέπει να είναι σε κυπριακή μορφή (π.χ. ABC123 ή A123).',
        'vin_unique' => 'Αυτός ο αριθμός πλαισίου είναι ήδη καταχωρημένος στο σύστημα.',
        'document_size' => 'Το μέγεθος του εγγράφου δεν πρέπει να υπερβαίνει τα 10MB',
        'document_type' => 'Το έγγραφο πρέπει να είναι PDF, DOC, DOCX, JPG, JPEG, ή PNG'
    ],
    'errors' => [
        'create_failed' => 'Αποτυχία δημιουργίας οχήματος',
        'update_failed' => 'Αποτυχία ενημέρωσης οχήματος',
        'delete_failed' => 'Αποτυχία διαγραφής οχήματος',
        'fetch_failed' => 'Αποτυχία ανάκτησης οχημάτων',
        'not_found' => 'Το όχημα δεν βρέθηκε',
        'has_related_records' => 'Δεν είναι δυνατή η διαγραφή οχήματος με συσχετισμένες εγγραφές (εντολές εργασίας ή τιμολόγια)',
        'create_service_history_failed' => 'Αποτυχία δημιουργίας ιστορικού σέρβις',
        'update_service_history_failed' => 'Αποτυχία ενημέρωσης ιστορικού σέρβις',
        'delete_service_history_failed' => 'Αποτυχία διαγραφής ιστορικού σέρβις',
        'fetch_service_history_failed' => 'Αποτυχία ανάκτησης ιστορικού σέρβις',
        'service_history_not_found' => 'Το ιστορικό σέρβις δεν βρέθηκε',
    ],
    'messages' => [
        'created' => 'Το όχημα δημιουργήθηκε με επιτυχία',
        'updated' => 'Το όχημα ενημερώθηκε με επιτυχία',
        'deleted' => 'Το όχημα διαγράφηκε με επιτυχία',
        'service_history_created' => 'Η εγγραφή σέρβις δημιουργήθηκε με επιτυχία',
        'service_history_updated' => 'Η εγγραφή σέρβις ενημερώθηκε με επιτυχία',
        'service_history_deleted' => 'Η εγγραφή σέρβις διαγράφηκε με επιτυχία'
    ],
    'status' => [
        'active' => 'Ενεργό',
        'inactive' => 'Ανενεργό',
        'sold' => 'Πωλημένο'
    ],
    'service_status' => [
        'completed' => 'Ολοκληρωμένο',
        'scheduled' => 'Προγραμματισμένο',
        'in_progress' => 'Σε Εξέλιξη',
        'cancelled' => 'Ακυρωμένο'
    ],
    'notifications' => [
        'vehicle_created' => [
            'title' => 'Νέο Όχημα',
            'body' => 'Προστέθηκε νέο όχημα: :make :model (:year)'
        ],
        'vehicle_updated' => [
            'title' => 'Ενημέρωση Οχήματος',
            'body' => 'Το όχημα :make :model (:year) ενημερώθηκε'
        ],
        'vehicle_deleted' => [
            'title' => 'Διαγραφή Οχήματος',
            'body' => 'Το όχημα :make :model (:year) διαγράφηκε'
        ],
        'service_history_created' => [
            'title' => 'Νέα Εγγραφή Σέρβις',
            'body' => 'Προστέθηκε νέα εγγραφή σέρβις για το όχημα :make :model (:license_plate)'
        ]
    ]
];
