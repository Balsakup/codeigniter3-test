<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Users extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
    }

    /** @throws JsonException */
    public function store(): CI_Output
    {
        $success = $this->user_model->create($this->input->post());

        if (is_array($success)) {
            return $this->jsonValidationError($success);
        }

        if ($success) {
            return $this->json();
        }

        return $this->json(false, data: [
            'message' => 'An error occurred. Please try again.',
        ]);
    }

    /** @throws JsonException */
    public function update(int $id): CI_Output
    {
        $user = $this->user_model->find($id);

        if (! $user) {
            return $this->json(false, 404, ['message' => "User '$id' not found."]);
        }

        $success = $this->user_model->update($id, $this->input->input_stream());

        if (is_array($success)) {
            return $this->jsonValidationError($success);
        }

        if ($success) {
            return $this->json();
        }

        return $this->json(false, data: [
            'message' => 'An error occurred. Please try again.',
        ]);
    }
}
