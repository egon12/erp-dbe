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
        $this->load->model('customer_model');

        $this->load->vars(array(
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
        $customer_name = $this->customer_model->get_name($post['customer_id']);
        try {
            $this->healthrecord_model->insert($type, $post);
            $this->session->set_flashdata('alert_success', 'Record for '.$customer_name.' is saved');
        } catch (Exception $e) {
            $this->session->set_flashdata('alert_danger', "Error when inserted $customer_name. " . $e->getMessage());
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

    public function get_card($customer_id)
    {
        $this->load->model('healthrecord_model');
        try {
            $data = $this->healthrecord_model->getByCustomer('general', $customer_id);
        } catch (Exception $e) {
            $data = array();
        }
        $this->load->view('card_view', array('all_data' => $data));
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

        /*
        $data = array();
        foreach ($tables as $table) {
            $data[$table] = $this->healthrecord_model->get_perdate($table, $customer_id);
        }
         */

        $data = $this->healthrecord_model->get_all_perdate('general', $customer_id);
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

    public function get_update_form($type, $id)
    {
        $this->load->model('healthrecord_model');
        $this->load->model('customer_model');
        try {
            // get data first and then delete it
            $healthrecord = $this->healthrecord_model->getById($type, $id);

        } catch (Exception $e) {
            // pass
        }

        $data = array (
            'customer_name' => $this->customer_model->get_name($healthrecord->customer_id),
            'row' => $healthrecord,
        );

        $this->load->view('healthrecord_general_edit_view', $data);
    }

    public function update($type, $id)
    {
        // error check
        $post = $this->input->post();
        if ($id != $post['id']) {
            $this->session->set_flashdata('alert_danger', 'Failed to update please retry');
            redirect('healthrecord');
        }

        $this->load->model(array('healthrecord_model', 'customer_model'));

        $this->healthrecord_model->update($type, $post);
        $customer_name = $this->customer_model->get_name($post['customer_id']);
        $this->session->set_flashdata('alert_success', "Update for $customer_name Success");
        redirect('healthrecord');
    }

    public function delete($type, $id)
    {
        $this->load->model('healthrecord_model');
        $this->load->model('customer_model');
        try {
            // get data first and then delete it
            $healthrecord = $this->healthrecord_model->getById($type, $id);

            $this->healthrecord_model->delete($type, $id);

            $customer_name = $this->customer_model->get_name($healthrecord->customer_id);
            $date = $healthrecord->date;

            $msg = "Record $customer_name at $date is deleted!";

            $this->session->set_flashdata('alert_success', $msg);
        } catch (Exception $e) {
            $this->session->set_flashdata('alert_danger', "Error when Delete data: " . $e->getMessage());
        }
        redirect('healthrecord');
    }
}
