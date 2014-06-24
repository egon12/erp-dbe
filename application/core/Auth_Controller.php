<?php

class Auth_Controller extends MY_Controller
{
    public function __construct()
    {

        parent::__construct();

        $this->load->library('auth/ion_auth');

        $this->lang->load('auth/ion_auth');

        /** uncoment this if you wnat profiler in ajax */
        //$this->output->enable_profiler(TRUE);
        if (!$this->getPermission()) {
            $this->redirectToDefault();
        }

        if ($this->ion_auth->logged_in()) {

            if (!$this->input->is_ajax_request()) {
                //$this->output->enable_profiler(TRUE);

                $this->the_user = $this->ion_auth->user()->row();
                $data['the_user'] = $this->the_user;
                $data['the_user_group'] = $this->ion_auth->get_users_groups()->row()->name;

                $this->load->vars($data);

                // todo for ajax security
            }
        } else {
            if (!$this->input->is_ajax_request()) {
                $this->session->set_flashdata('referer', $this->uri->uri_string());
                redirect('auth/login');
            } else {
                echo $this->lang->line('not_login');
                die();
            }
        }
    }

    private function getPermission()
    {
        if ($this->ion_auth->is_admin()) {
            return true;
        }
        // todo move all permision to here
        $module = $this->router->fetch_module();

        // only medical allow to see healthrecord
        if ($module == 'healthrecord' and !$this->ion_auth->in_group(array('medical'))) {
            return false;
        };

        // todo temporary, only medical allowed to see healthrecord
        if ($this->ion_auth->in_group('medical') and $module != 'healthrecord') {
            return false;
        };

        return true;
    }

    private function redirectToDefault()
    {
        if ($this->ion_auth->in_group('medical')) {
            redirect('healthrecord');
        } else {
            redirect('/');
        }
    }
}
