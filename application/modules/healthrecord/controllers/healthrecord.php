<?php
/**
 * Health Record
 *
 * @author Egon Firman <egon.firman@gmail.com>
 *
 * todo bikin customer_finder kaya di pos
 * todo bikin insert
 * todo bikin viewer
 * todo bikin flow
 **/

class HealthRecord extends Auth_Controller
{
    /**
     * index, show the input
     **/
    public function index()
    {
        $this->load->model('flow/flow_model');

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

    public function get_view($customer_id, $table = "general")
    {
        $this->load->model('healthrecord_model');
        if ($this->input->is_ajax_request())
        {
            $this->load->view('healthrecord_'.$table.'_view', array (
                'data' => $this->healthrecord_model->get($table, $customer_id)

            ));
        }
    }

    public function search_customer()
    {
        $this->load->library('PHPJQueryCallback');
        $callback = new PHPJqueryCallback();
        $datalist = '';


        $this->load->model('customer_model');
        $customers = $this->customer_model->search($this->input->get('query'));
        foreach ($customers as $customer) {
            $datalist .= '<option value="'.$customer->id.'">'.$customer->name.'</option>';
        }

        $callback->html('#customer_list', $datalist);
        $callback->send();
    }
}
