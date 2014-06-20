<?php
/**
 * Health Record
 *
 * @author Egon Firman <egon.firman@gmail.com>
 *
 **/

class HealthRecord extends Auth_Controller
{
    /**
     * index, show the input
     **/
    public function index()
    {
        //$this->load->model('flow/flow_model');
        $this->load->model('sugestion_model');

        /**
         * Ok, maybe this is some of another tools?
         *
         */
        //$this->flow_model->get_list(1);

        //$this->customer_model->get_list_today();

        $this->load->model('customer_model');

        $this->load->vars(array(
            'customers' => $this->customer_model->get(),
            'new_customers' => $this->customer_model->get_new(),
            'sugestion' => $this->sugestion_model->get_from_diagnostic(),
            'alert_success' => $this->session->flashdata('alert_success'),
            'alert_danger' => $this->session->flashdata('alert_danger'),
        ));

        $this->load->view('healthrecord_view');
    }

    public function add($type)
    {
        $this->load->model('healthrecord_model');
        $this->load->model('customer_model');
        $post = $this->input->post();
        try {
            $this->healthrecord_model->insert($type, $post);
            $customer_name = $this->customer_model->get_name($post['customer_id']);
            $this->session->set_flashdata('alert_success', 'Record for '.$customer_name.' is saved');
        } catch (Exception $e) {
            $this->session->set_flashdata('alert_danger', $e->getMessage());
        }
        redirect('healthrecord');
    }

    public function picture_view()
    {
        $this->load->model('healthrecord_model');
        $this->load->model('customer_model');

        $this->load->vars(array(
            'records' => $this->healthrecord_model->get('disease', 2319)
        ));
        $this->load->view('picture_view.php');
    }

    public function get_data($customer_id, $table = "general")
    {
        $this->load->model('healthrecord_model');
        $data = $this->healthrecord_model->get($table, $customer_id);
        $this->output->set_content_type('javascript');
        $this->output->set_output(json_encode($data));
    }

    public function get_print()
    {
        $this->load->model('healthrecord_model');
        $customer_id = $this->input->get('customer_id');
        $date_timestamp = $this->input->get('date_timestamp');
        if (!$date_timestamp) {
            $date_timestamp = date('Y-m-d');
        }

        $tables = array('general');

        $data = array();
        foreach ($tables as $table) {
            $data[$table] = $this->healthrecord_model->get($table, $customer_id);
        }

        $this->load->view('print_view', array('all_data' => $data));
    }

    public function search_customer()
    {
        $this->load->library('PHPJQueryCallback');
        $callback = new PHPJqueryCallback();
        $datalist = '';

        $this->load->model('customer_model');


        // get list
        $query = $this->input->get('query');
        if ($query) {
        $customers = $this->customer_model->search($query);
        } else {
            $customers = $this->customer_model->get_new();
        }

        foreach ($customers as $customer) {
            $datalist .= '<option value="'.$customer->id.'">'.$customer->id.'  |  '.$customer->name.'</option>';
        }

        $callback->html('#customer_list', $datalist);
        $callback->send();
    }
}
