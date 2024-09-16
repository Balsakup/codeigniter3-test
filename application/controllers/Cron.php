<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cron extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
    }

    public function delete_old_users(): void
    {
        [
            'never_connected' => $neverConnected,
            'already_connected' => $alreadyConnected,
        ] = $this->user_model->deleteOlderThan();

        echo $neverConnected . ' user(s) never connected deleted since 36 months', PHP_EOL;
        echo $alreadyConnected . ' user(s) last connected deleted since 36 months', PHP_EOL;
    }
}
