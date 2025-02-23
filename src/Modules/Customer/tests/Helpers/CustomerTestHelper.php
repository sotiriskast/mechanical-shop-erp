<?php


namespace Modules\Customer\tests\Helpers;

class CustomerTestHelper
{
    public static function generateTestEmail(): string
    {
        return 'test_' . uniqid() . '@example.com';
    }

    public static function generateTestPhone(): string
    {
        return '+' . random_int(1000000000, 9999999999);
    }

    public static function getValidCustomerData(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => self::generateTestEmail(),
            'phone' => self::generateTestPhone(),
            'is_active' => true,
        ], $overrides);
    }

    public static function getInvalidCustomerData(): array
    {
        return [
            'missing_required_fields' => [
                'first_name' => '',
                'last_name' => '',
                'email' => '',
            ],
            'invalid_email' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'invalid-email',
            ],
            'duplicate_email' => [
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'email' => 'duplicate@example.com',
            ],
        ];
    }
}
