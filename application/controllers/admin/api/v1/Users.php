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
    public function index(): CI_Output
    {
        $page = $this->input->get('page', true) ?: 1;
        $perPage = $this->input->get('per_page', true) ?: 10;
        $orderBy = $this->input->get('order_by', true);
        $orderDir = $this->input->get('order_dir', true);
        $search = $this->input->get('search', true) ?: null;

        return $this->json(
            data: $this->user_model->paginate(
                $this->uri->uri_string(),
                $page,
                $perPage,
                $orderBy,
                $orderDir,
                $search
            )
        );
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

    /** @throws JsonException */
    public function destroy(int $id): CI_Output
    {
        $user = $this->user_model->find($id);

        if (! $user) {
            return $this->json(false, 404, ['message' => "User '$id' not found."]);
        }

        $success = $this->user_model->delete($id, $this->input->input_stream());

        if ($success) {
            return $this->json();
        }

        return $this->json(false, data: [
            'message' => 'An error occurred. Please try again.',
        ]);
    }
}
