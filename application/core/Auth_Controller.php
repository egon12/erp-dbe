<?php

class Auth_Controller extends MY_Controller
{
    public function __construct() {

        parent::__construct();

        $this->load->library('auth/ion_auth');

        $this->lang->load('auth/ion_auth');

        /** uncoment this if you wnat profiler in ajax */
        //$this->output->enable_profiler(TRUE);

        if($this->ion_auth->logged_in()) {

            if (!$this->input->is_ajax_request()) {
                //$this->output->enable_profiler(TRUE);

                $this->the_user = $this->ion_auth->user()->row();
                $data['the_user'] = $this->the_user;
                $data['the_user_group'] = $this->ion_auth->get_users_groups()->row()->name;

                $this->load->vars($data);

                // todo for ajax security
            }
        }
        else {
            if (!$this->input->is_ajax_request()) {
                $this->session->set_flashdata('referer', $this->uri->uri_string());
                redirect('auth/login');
            } else {
                echo $this->lang->line('not_login');
                die();
            }
        }
    }
}
