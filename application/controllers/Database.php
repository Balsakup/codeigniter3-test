<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Database extends CI_Controller
{
    public function migrate(): void
    {
        $this->load->library('migration');

        if ($this->migration->current() === false) {
            show_error($this->migration->error_string());
        }
    }

    public function seed(): void
    {
        $this->load->database();
        $this->load->model('user_model');

        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 10000; $i++) {
            $lastConnection = $faker->boolean(90)
                ? $faker->dateTimeBetween('-5 years')
                : null;

            /** @var User_model $user */
            $user = clone $this->user_model;
            $user->firstname = $faker->firstName();
            $user->lastname = $faker->lastName();
            $user->email = $faker->unique()->email();
            $user->address_street = $faker->streetAddress();
            $user->address_postcode = $faker->postcode();
            $user->address_city = $faker->city();
            $user->address_country = $faker->country();
            $user->status = $faker->randomElement(['individual', 'professional']);
            $user->last_connection = $lastConnection?->format('Y-m-d H:i:s');
            $user->created_at = $lastConnection?->modify('-3 months')->format('Y-m-d H:i:s') ?: null;

            $this->db->insert('users', $user);
        }
    }
}
