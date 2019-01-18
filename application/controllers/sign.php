<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sign extends CI_Controller {
    public function index(){

        $user = $this->session->userdata('jxcsys');
        $like = $this->input->post('matchCon');
        $sel = $this->input->post('sel');
        $page_now = $this->input->post('page_now');
        $page_num = $this->input->post('page_num');
        if($sel == 0){
            $time =0;
        }elseif ($sel == 1){
            $time = strtotime("-1 month",time());
        }elseif ($sel == 2){
            $time = strtotime("-6 month",time());
        }elseif ($sel == 3){
            $time = strtotime("-12 months",time());
        }

        if($sel && $sel != 0){
            $where = "(username LIKE '%".$like."%') AND (time >".$time." AND time <".time().")";
        }else{
            $where = "(username LIKE '%".$like."%')";
        }

        $total =  $this->db->where($where)->count_all('ci_sign');
        if(!$page_num){
            $page_num = 20;
        }

        $page_all = ceil($total/$page_num);
        if(!$page_now || $page_now == 1 || $page_now>$page_all){
            $page_now = 0 ;
        }else{
            $page_now = ($page_now-1)*$page_num;
        }

        $data = $this->db->where($where)->limit($page_num,$page_now)->order_by('time DESC')->get('ci_sign')->result();

        $this->load->view('/settings/sign_list',['data'=>$data,'like'=>$like,'sel'=>$sel,'page_now'=>$this->input->post('page_now'),'page_num'=>$page_num,'page_all'=>$page_all]);
    }
}