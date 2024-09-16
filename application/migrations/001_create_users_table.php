<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_create_users_table extends CI_Migration
{
    public function up(): void
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'firstname' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'lastname' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => true,
            ],
            'address_street' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'address_postcode' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'address_city' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'address_country' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'status' => [
                'type' => 'ENUM("individual", "professional")',
                'null' => false,
            ],
            'last_connection' => [
                'type' => 'timestamp',
                'null' => true,
            ],
            'created_at TIMESTAMP NOT NULL DEFAULT NOW()',
            'updated_at TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW()',
        ]);

        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('users', true);
    }
}
