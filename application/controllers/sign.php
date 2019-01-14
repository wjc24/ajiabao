<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sign extends CI_Controller {
    public function index(){
        $this->load->view('/settings/vip_card');
    }
}