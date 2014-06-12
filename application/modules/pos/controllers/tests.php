<?php

class UnitTesting extends MY_Controller
{

    function index()
    {
        $this->load->library('unit_test');

        /**
         * first are pos_session
         */
        $this->load->model('pos_session');

        $user_id = 0;
            



        // start
        $id = $this->pos_session_model->start($user_id) ;
        $this->unit->run($id, 'is_string');

        // check true
        $test = $this->pos_session_model->check ($id);
        $this->unit->run($test, 'is_true');

        // check false
        $test = $this->pos_session_model->check ('asbsd');
        $this->unit->run($test, 'is_false');

        // add
        $test = $this->pos_session_model->add($id, 'main', $parameter) ;
        $this->unit->run($test, 'is_object');
        $test = $this->pos_session_model->last($id) ;
        $this->unit->run($test, 'main');

        $test = $this->pos_session_model->end($id);
        $data = $this->db->get_where ('pos_session_model', array('session_id' => $id))->result();
        $this->unit->run($test, $data);


        $receipt = array (
            'user_id' => 0,
            'customer_id' => 2000,
            'lines' => array (
                array (
                    'code' => 2001,
                    'quantity' => 2
                ),
                array (
                    'code' => 1001,
                    'quantity' => 1
                ),
            ),
            'discount' => 20000,
            'method' => 'card',
            'payment' => 200000
        );

        $id = $this->pos_session_model->start($receipt['user_id']) ;
        $this->pos_session_model->set_customer($id, $receipt['customer_id']);
        $this->pos_session_model->set_product($id, $receipt['lines'][0]['code']);
        $this->pos_session_model->set_quantity($id,  $receipt['lines'][0]['quantity']);
        $this->pos_session_model->set_product($id, $receipt['lines'][1]['code']);
        $this->pos_session_model->set_quantity($id,  $receipt['lines'][1]['quantity']);
        $this->pos_session_model->set_discount($id, $receipt['discount']);
        $this->pos_session_model->set_method($id, $receipt['method']);
        $this->pos_session_model->set_payment($id, $receipt['payment']);
        $test = $this->pos_session_model->get_receipt($id);
        $this->unit->run($test, $receipt);

        /*
        $test = $this->pos_session_model->check_line($id) ;
        $this->unit->run($test, 'is_string');

        $test = $this->pos_session_model->to_receipt($result);
        $this->unit->run($test, 'is_string');


        $test = $this->pos_session_model->subtotal ($id);
        $this->unit->run($test, 'is_string');

        $test = $this->pos_session_model->total ($id);
        $this->unit->run($test, 'is_string');
         */



    }

}
