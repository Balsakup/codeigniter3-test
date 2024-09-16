<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    public int $id;

    public string $firstname;

    public string $lastname;

    public string $email;

    public string $address_street;

    public string $address_postcode;

    public string $address_city;

    public string $address_country;

    public string $status;

    public ?string $last_connection;

    public ?string $created_at;

    public ?string $updated_at;

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
    }

    public function create(array $data): array|bool
    {
        $errors = $this->validate($data);

        if (! empty($errors)) {
            return $errors;
        }

        foreach ($data as $key => $value) {
            $this->$key = $value;
        }

        return $this->db->insert('users', $this);
    }

    public function update(int $id, array $data): array|bool
    {
        if (empty($data)) {
            return true;
        }

        $errors = $this->validate($data, $id);

        if (! empty($errors)) {
            return $errors;
        }

        foreach ($data as $key => $value) {
            $this->db->set($key, $value);
        }

        $this->db->where('id', $id);

        return $this->db->update('users');
    }

    public function delete(int $id): bool
    {
        return $this->db->delete('users', ['id' => $id]);
    }

    public function find(int $id): ?array
    {
        return $this->db->where('id', $id)->get_where('users')->row_array();
    }

    public function paginate(
        string $basePath,
        int $page,
        int $perPage,
        ?string $orderBy = null,
        ?string $orderDir = null,
        ?string $search = null
    ): array {
        $query = $this->db->order_by($orderBy ?: 'id', $orderDir ?: 'ASC');

        if ($search) {
            $query = $query->or_like([
                'firstname' => $search,
                'lastname' => $search,
                'email' => $search,
            ]);
        }

        $total = (clone $query)->count_all_results('users');
        $items = (clone $query)
            ->limit($perPage, ($page - 1) * $perPage)
            ->get_where('users')->result_array();
        $pageCount = ceil($total / $perPage);
        $queryParams = [];

        if ($orderBy) {
            $queryParams['order_by'] = $orderBy;
        }

        if ($orderDir) {
            $queryParams['order_dir'] = $orderDir;
        }

        return [
            'total' => $total,
            'page_count' => $pageCount,
            'next_page_url' => $page >= $pageCount
                ? null
                : $basePath . '?' . http_build_query([
                    ...$queryParams,
                    'page' => $page + 1,
                ]),
            'prev_page_url' => $page <= 1
                ? null
                : $basePath . '?' . http_build_query([
                    ...$queryParams,
                    'page' => $page - 1,
                ]),
            'items' => $items,
        ];
    }

    public function countAll(): int
    {
        return $this->db->count_all('users');
    }

    public function deleteOlderThan(int $months = 36): array
    {
        $result = [
            'never_connected' => 0,
            'already_connected' => 0,
        ];

        $query = $this->db
            ->where('last_connection is null')
            ->where("TIMESTAMPDIFF(MONTH, created_at, NOW()) > $months");
        $neverConnectedDeleted = (clone $query)->count_all_results('users');

        if ((clone $query)->delete('users')) {
            $result['never_connected'] = $neverConnectedDeleted;
        }

        $this->db
            ->reset_query()
            ->where('last_connection is not null')
            ->where("TIMESTAMPDIFF(MONTH, last_connection, NOW()) > $months");
        $alreadyConnected = (clone $query)->count_all_results('users');

        if ((clone $query)->delete('users')) {
            $result['already_connected'] = $alreadyConnected;
        }

        return $result;
    }

    protected function validate(array $data, ?int $id = null): array
    {
        $this->load->library('form_validation');

        $this->form_validation->set_data($data);
        $this->form_validation->set_rules(
            'firstname',
            'Firstname',
            [
                ...($id ? [] : ['required']),
                'max_length[255]',
            ]
        );
        $this->form_validation->set_rules(
            'lastname',
            'Lastname',
            [
                ...($id ? [] : ['required']),
                'max_length[255]',
            ]
        );
        $this->form_validation->set_rules(
            'email',
            'Email',
            [
                ...($id ? [] : ['required']),
                'max_length[255]',
                'valid_email',
                [
                    'user_email_unique',
                    function (?string $email = null) use ($id) {
                        if (! $email) {
                            return true;
                        }

                        $query = $this->db->where('email', $email);

                        if ($id) {
                            $query = $query->where('id !=', $id);
                        }

                        if ($exists = ($query->count_all_results('users') > 0)) {
                            $this->form_validation->set_message(
                                'user_email_unique',
                                'The {field} field must contain a unique value.'
                            );
                        }

                        return ! $exists;
                    },
                ],
            ]
        );
        $this->form_validation->set_rules(
            'address_street',
            'Address street',
            [
                ...($id ? [] : ['required']),
                'max_length[255]',
            ]
        );
        $this->form_validation->set_rules(
            'address_postcode',
            'Address postcode',
            [
                ...($id ? [] : ['required']),
                'max_length[255]',
            ]
        );
        $this->form_validation->set_rules(
            'address_city',
            'Address city',
            [
                ...($id ? [] : ['required']),
                'max_length[255]',
            ]
        );
        $this->form_validation->set_rules(
            'address_country',
            'Address country',
            [
                ...($id ? [] : ['required']),
                'max_length[255]',
            ]
        );
        $this->form_validation->set_rules(
            'status',
            'Status',
            [
                ...($id ? [] : ['required']),
                'in_list[individual,professional]',
            ]
        );

        if (! $this->form_validation->run()) {
            return $this->form_validation->error_array();
        }

        return [];
    }
}
