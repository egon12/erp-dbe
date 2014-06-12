<?php

/**
 * PHPJQueryCallback
 *
 * Use this library to create json object that will be use by jQuery ajax
 *
 * @author Egon Firman<egon.firman@gmail.com>
 *
 */
class PHPJQueryCallback {
    public function log($msg) {
        if (isset($this->log)) {
            $this->log .= $msg."\n";
        } else {
            $this->log = $msg."\n";
        }
    }
    
    public function jsprint($msg) {
        $this->jsprint = $msg;
    }

    public function alert($msg) {
        $this->alert = $msg;
    }

    public function before($selector, $msg) {
        if (!isset($this->before)) {
            $this->before = array();
        } 
        $b = new stdClass();
        $b->selector = $selector;
        $b->msg = $msg;
        array_push($this->before, $b);
    }

    public function after($selector, $msg) {
        if (!isset($this->after)) {
            $this->after = array();
        } 
        $b = new stdClass();
        $b->selector = $selector;
        $b->msg = $msg;
        array_push($this->after, $b);
    }

    public function html($selector, $msg) {
        if (!isset($this->html)) {
            $this->html = array();
        } 
        $b = new stdClass();
        $b->selector = $selector;
        $b->msg = $msg;
        array_push($this->html, $b);
    }

    public function append($selector, $msg) {
        if (!isset($this->append)) {
            $this->append = array();
        } 
        $b = new stdClass();
        $b->selector = $selector;
        $b->msg = $msg;
        array_push($this->append, $b);
    }

    public function val($selector, $msg) {
        if (!isset($this->val)) {
            $this->val = array();
        } 
        $b = new stdClass();
        $b->selector = $selector;
        $b->msg = $msg;
        array_push($this->val, $b);
    }

    public function focus($selector) {
        $this->focus = $selector;
    }

    public function attr($selector, $attr, $msg) {
        if (!isset($this->attr)) {
            $this->attr = array();
        } 
        $b = new stdClass();
        $b->selector = $selector;
        $b->attr = $attr;
        $b->msg = $msg;
        array_push($this->attr, $b);
    }

    public function jseval ($script) {
        //$this->jseval = '<script type=text/javascript>';
        if (!isset( $this->jseval)) {
            $this->jseval = '';
        }
        $this->jseval .= $script;
        //$this->jseval .= '</script>';
        $this->jseval = urlencode($this->jseval);
    }

    public function redirect ($location) {
        $this->redirect = $location;
    }

    public function clone_appendTo($selector_from, $selector_to) {
        if (!isset($this->clone_appendTo)) {
            $this->clone_appendTo = array();
        }
        $b = new stdClass();
        $b->selector_from = $selector_from;
        $b->selector_to = $selector_to;
        array_push($this->clone_appendTo, $b);
    }

    public function addClass($selector, $msg) {
        if (isset ($this->addClass)) {
            $this->addClass = array();
        }
        $b = new stdClass();
        $b->selector = $selector;
        $b->msg = $msg;
        array_push($this->addClass, $b);
    }

    public function removeClass($selector, $msg) {
        if (isset ($this->removeClass)) {
            $this->removeClass = array();
        }
        $b = new stdClass();
        $b->selector = $selector;
        $b->msg = $msg;
        array_push($this->removeClass, $b);
    }

    public function template($selector, $msg) {
        if (!isset($this->template)) {
            $this->template = array();
        } 
        $b = new stdClass();
        $b->selector = $selector;
        $b->msg = $msg;
        array_push($this->template, $b);
    }

    public function callback($url) 
    {
        $this->callback = $url;
    }

    public function send()
    {
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($this);
        unset ($this);
        die();
    }

    /**
     * for CI
     */
    public function dump()
    {
        $data = json_encode($this);
        $ci = &get_instance();

        $ci->load->vars(array('data' => $data));
        $ci->load->view('json_debug');
    }
}
