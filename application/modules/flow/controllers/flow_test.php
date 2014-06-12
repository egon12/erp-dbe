<?php

class Flow_Test extends Admin_Controller
{
    public function index()
    {
        $this->load->library('unit_test');

        $this->load->model('flow_model');

        $this->flow_model->open(2001);

        $obj = new stdClass();
        $obj->customer_id = 2001;
        $obj->name = 'Ny Nancy';

        
        $list = array($obj);
        $this->unit->run($this->flow_model->get_list(), $list, 'list test');


        $this->flow_model->make_order(2001, array());

        $this->flow_model->delete_order(2001, array());

        $this->flow_model->make_order(2001, array());

        $this->flow_model->lock_order(2001, array());

        $this->flow_model->make_invoice(2001);

        $this->flow_model->make_receipt(2001);

        $this->flow_model->close(2001);




        echo $this->unit->report();

    }
}
