<?php

namespace Modules\Customer\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Customer\src\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // Check if we already have customers
        if (Customer::count() > 0) {
            // If we already have customers, don't try to create more
            return;
        }

        // Use a counter to ensure truly unique phone numbers
        $counter = 10000;

        // Create Cypriot customers
        for ($i = 0; $i < 20; $i++) {
            Customer::create([
                'first_name' => $this->getCypriotFirstName(),
                'last_name' => $this->getCypriotLastName(),
                'email' => $this->faker()->unique()->safeEmail(),
                'phone' => '+35725' . (341000 + $counter),  // Ensure unique phone
                'mobile' => '+35799' . (262000 + $counter), // Ensure unique mobile
                'company_name' => $this->getCypriotCompanyName(),
                'vat_number' => 'CY' . rand(10000000, 99999999) . 'X',
                'tax_office' => $this->getCypriotDistrict(),
                'billing_address' => $this->getCypriotAddress(),
                'shipping_address' => null,
                'city' => $this->getCypriotCity(),
                'postal_code' => rand(1000, 9999),
                'country' => 'Cyprus',
                'notes' => $this->faker()->paragraph(),
                'is_active' => rand(0, 1),
                'uuid' => \Str::uuid(),
                'code' => 'CUS-' . str_pad(($i + 1), 6, '0', STR_PAD_LEFT),
            ]);

            $counter++; // Increment counter for next unique number
        }
    }

    // Your helper methods for generating Cypriot names, etc...
    private function faker()
    {
        return \Faker\Factory::create();
    }

    private function getCypriotFirstName()
    {
        $names = ['Ανδρέας', 'Γιώργος', 'Αντώνης', 'Μιχάλης', 'Νίκος', 'Κώστας', 'Μάριος', 'Χρίστος', 'Χαράλαμπος', 'Αγαθοκλής'];
        return $names[array_rand($names)];
    }

    private function getCypriotLastName()
    {
        $names = ['Παπαδόπουλος', 'Γεωργίου', 'Αντωνίου', 'Μιχαήλ', 'Νικολάου', 'Κωνσταντίνου', 'Χριστοδούλου', 'Χαραλάμπους', 'Ιωάννου'];
        return $names[array_rand($names)];
    }

    private function getCypriotCompanyName()
    {
        $prefixes = ['Αδελφοί', 'Μανώλη', 'Σταύρου', 'Αντώνη'];
        $suffixes = ['ΛΤΔ', 'και Υιοι', 'Μάμα', 'Επιχειρήσεις'];
        return $prefixes[array_rand($prefixes)] . '-' . $suffixes[array_rand($suffixes)];
    }

    private function getCypriotCity()
    {
        $cities = ['Λευκωσία', 'Λεμεσός', 'Λάρνακα', 'Πάφος', 'Αμμόχωστος'];
        return $cities[array_rand($cities)];
    }

    private function getCypriotDistrict()
    {
        $districts = ['Nicosia', 'Limassol', 'Larnaca', 'Paphos', 'Famagusta'];
        return $districts[array_rand($districts)];
    }

    private function getCypriotAddress()
    {
        $streets = ['Λεωφόρος Μακαρίου', 'Όδος Λήδρας', 'Όδος Ευαγγέλου', 'Λεωφόρος Αρχιεπισκόπου'];
        $street = $streets[array_rand($streets)];
        $number = rand(1, 100);
        $postal = rand(1000, 9999);
        $city = $this->getCypriotCity();
        return $street . ', ' . $number . ' ' . $postal . ' ' . $city;
    }
}
