<?php

defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    /** @throws JsonException */
    public function json(bool $success = true, int $httpCode = 200, array $data = []): CI_Output
    {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header($httpCode)
            ->set_output(json_encode(['success' => $success, ...$data], JSON_THROW_ON_ERROR));
    }

    /** @throws JsonException */
    public function jsonValidationError(array $errors): CI_Output
    {
        return $this->json(false, 400, [
            'message' => 'Validation error.',
            'errors' => $errors,
        ]);
    }
}
