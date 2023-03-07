<?php

namespace Database\Seeders;

use Woo\Base\Supports\BaseSeeder;
use Woo\Ecommerce\Models\Address;
use Woo\Ecommerce\Models\Customer;
use Faker\Factory;

class CustomerSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('customers');

        $faker = Factory::create();

        Customer::truncate();
        Address::truncate();

        $customers = [
            'customer@Woo.com',
            'vendor@Woo.com',
        ];

        foreach ($customers as $item) {
            $customer = Customer::create([
                'name' => $faker->name(),
                'email' => $item,
                'password' => bcrypt('12345678'),
                'phone' => $faker->e164PhoneNumber(),
                'avatar' => 'customers/' . $faker->numberBetween(1, 10) . '.jpg',
                'dob' => now()->subYears(rand(20, 50))->subDays(rand(1, 30)),
            ]);

            $customer->confirmed_at = now();
            $customer->save();

            Address::create([
                'name' => $customer->name,
                'phone' => $faker->e164PhoneNumber(),
                'email' => $customer->email,
                'country' => $faker->countryCode(),
                'state' => $faker->state(),
                'city' => $faker->city(),
                'address' => $faker->streetAddress(),
                'zip_code' => $faker->postcode(),
                'customer_id' => $customer->id,
                'is_default' => true,
            ]);

            Address::create([
                'name' => $customer->name,
                'phone' => $faker->e164PhoneNumber(),
                'email' => $customer->email,
                'country' => $faker->countryCode(),
                'state' => $faker->state(),
                'city' => $faker->city(),
                'address' => $faker->streetAddress(),
                'zip_code' => $faker->postcode(),
                'customer_id' => $customer->id,
                'is_default' => false,
            ]);
        }

        for ($i = 0; $i < 8; $i++) {
            $customer = Customer::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => bcrypt('12345678'),
                'phone' => $faker->e164PhoneNumber(),
                'avatar' => 'customers/' . ($i + 1) . '.jpg',
                'dob' => now()->subYears(rand(20, 50))->subDays(rand(1, 30)),
            ]);

            $customer->confirmed_at = now();
            $customer->save();

            Address::create([
                'name' => $customer->name,
                'phone' => $faker->e164PhoneNumber(),
                'email' => $customer->email,
                'country' => $faker->countryCode(),
                'state' => $faker->state(),
                'city' => $faker->city(),
                'address' => $faker->streetAddress(),
                'zip_code' => $faker->postcode(),
                'customer_id' => $customer->id,
                'is_default' => true,
            ]);
        }
    }
}
