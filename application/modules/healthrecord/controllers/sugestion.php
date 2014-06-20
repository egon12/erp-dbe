<?php 

/**
 * Health Record
 *
 * @author Egon Firman <egon.firman@gmail.com>
 *
 **/

class Sugestion extends Auth_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('sugestion_model');
    }


    public function index()
    {
        $this->load->view('sugestion_view', array(
            'list' => $this->sugestion_model->getList(),
            'info' => $this->session->flashdata('info'),
            'danger' => $this->session->flashdata('danger'),
        ));
    }

    public function add()
    {
        $post = $this->input->post();
        $this->sugestion_model->add($post);
        $this->session->set_flashdata('info', $post['diagnostic'].' has been inserted');
        redirect('healthrecord/sugestion');
    }

    public function update($id)
    {
        $post = $this->input->post();
        $this->sugestion_model->update($id, $post);
        $this->session->set_flashdata('info', $post->diagnostic.' has been updated');
        redirect('healthrecord/sugestion');
    }

    public function delete($id)
    {
        $row = $this->sugestion_model->delete($id, $post);
        $this->session->set_flashdata('danger', $row->diagnostic.' has been deleted');
        redirect('healthrecord/sugestion');
    }

    public function get($name = "")
    {
        $sugestion = $this->sugestion_model->get_from_diagnostic($name);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('sugestion' => $sugestion)));
        
    }
}
