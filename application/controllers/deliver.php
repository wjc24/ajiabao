<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deliver  extends CI_Controller {
    public function index(){

        $user = $this->session->userdata('jxcsys');
        $like = $this->input->post('matchCon');
        $sel = $this->input->post('sel');
        $page_now = (int)$this->input->post('page_now');
        $page_num = (int)$this->input->post('page_num');

        $where = "(billType ='SALE' AND checked ='1')";

        $invoice = $this->db->where($where)->get('ci_invoice')->result();
        $goods = [];
        $t = 0;

        foreach ($invoice as $k=>$v){
            if($sel && $sel != '0'){
                $where_invoice_info = "(iid =".$v->id." AND billNo = '".$v->billNo."') AND (billNo LIKE '%".$like."%' OR booking_number LIKE '%".$like."%' OR container_number LIKE '%".$like."%' OR box_number LIKE '%".$like."%' OR shipping_name LIKE '%".$like."%') AND (STATUS = ".$sel.")";
            }else{
                $where_invoice_info = "(iid =".$v->id." AND billNo = '".$v->billNo."') AND (billNo LIKE '%".$like."%' OR booking_number LIKE '%".$like."%' OR container_number LIKE '%".$like."%' OR box_number LIKE '%".$like."%' OR shipping_name LIKE '%".$like."%')";
            }

            $invoice_info = $this->db->where($where_invoice_info)->get('ci_invoice_info')->result();
            foreach ($invoice_info as $key=>$val){
                $goods[$t]['invoice_id'] =$v->id;
                $goods[$t]['invoice_info_id'] =$val->id;
                $goods[$t]['billNo'] =$v->billNo;
                $goods[$t]['billDate'] =$v->billDate;
                $goods[$t]['userName'] =$v->userName;
                $goods[$t]['good_id'] =$val->invId;
                $good = $this->db->where(['id'=>$val->invId])->get('ci_goods')->row();
                $goods[$t]['good_name'] =$good->name;
                $goods[$t]['good_num'] =substr($val->qty,1);
                $goods[$t]['shipping_name'] =$val->shipping_name;
                $goods[$t]['booking_number'] =$val->booking_number;
                $goods[$t]['container_number'] =$val->container_number;
                $goods[$t]['tray_number'] =$val->tray_number;
                $goods[$t]['box_number'] =$val->box_number;
                $goods[$t]['boxes'] =$val->boxes;
                $goods[$t]['box_volume'] =$val->box_volume;
                $goods[$t]['port'] =$val->port;
                $goods[$t]['start_time'] =$val->start_time;
                $goods[$t]['end_time'] =$val->end_time;
                $goods[$t]['status'] =$val->status;
                $t++;
            }
        }

        $total = count($goods);

        if(!$page_num){
            $page_num = 20;
        }

        $page_all = ceil($total/$page_num);
        if(!$page_now || $page_now == 1 || $page_now>$page_all){
            $page_now = 0 ;
        }else{
            $page_now = ($page_now-1)*$page_num;
        }

        $new_goods = [];
        foreach ($goods as $k=>$v){
            if($page_now == 0){
                if($k>=$page_num){
                    continue;
                }else{
                    $new_goods[$k] = $v;
                }
            }else{
                if($k>=$page_now && $k <($page_now+$page_num)){
                    $new_goods[$k] = $v;
                }else{
                    continue;
                }
            }
        }


        $this->load->view('/settings/deliver',['data'=>$new_goods,'like'=>$like,'sel'=>$sel,'page_now'=>$this->input->post('page_now'),'page_num'=>$page_num,'page_all'=>$page_all]);
    }

    //添加出仓信息
    public function add(){
        $id = $this->input->get('id');

        $invoice = $this->db->where(['iid'=>$id])->get('ci_invoice_info')->result();
        $data = [];

        foreach ($invoice as $k=>$v){
            $good = $this->db->where(['id'=>$v->invId])->get('ci_goods')->row();
            $data[$k]['good_id'] = $good->id;
            $data[$k]['name'] = $good->name;
            $data[$k]['num'] = substr($v->qty, 1) ;
            $data[$k]['billNo'] = $v->billNo;
            $data[$k]['shipping_name'] = $v->shipping_name;
            $data[$k]['booking_number'] = $v->booking_number;
            $data[$k]['container_number'] = $v->container_number;
            $data[$k]['tray_number'] = $v->tray_number;
            $data[$k]['box_number'] = $v->box_number;
            $data[$k]['boxes'] = $v->boxes;
            $data[$k]['long'] = $v->long;
            $data[$k]['wide'] = $v->wide;
            $data[$k]['high'] = $v->high;
            $data[$k]['box_volume'] = $v->box_volume;
            $data[$k]['port'] = $v->port;
            $data[$k]['start_time'] = $v->start_time;
            $data[$k]['end_time'] = $v->end_time;
            $data[$k]['status'] = $v->status;

        }

        $this->load->view('/settings/deliver_detail',['id'=>$id,'data'=>$data]);
    }

    //添加修改发货信息
    public function doadd(){
        $res =[];
        $data = str_enhtml($this->input->post(NULL,TRUE));
        $edit = $this->db->update('ci_invoice_info',array('shipping_name'=>$data['shipping_name'],'booking_number'=>$data['booking_number'],'container_number'=>$data['container_number'], 'tray_number'=>$data['tray_number'],'box_number'=>$data['box_number'],'boxes'=>$data['boxes'],'long'=>$data['long'],'wide'=>$data['wide'],'high'=>$data['high'],'box_volume'=>$data['box_volume'],'port'=>$data['port'],),array('id'=>$data['invoice_info_id']));

        if($edit){
            $res['code'] = 1;
            $res['text'] = "修改成功";
            die(json_encode($res));
        }else{
            $res['code'] = 2;
            $res['text'] = "修改失败";
            die(json_encode($res));
        }

    }
// 开始出仓
    public function start(){
        $id = $this->input->post('id');
        $res =[];
        $edit = $this->db->update('ci_invoice_info',array('status'=>2,'start_time'=>time()),array('id'=>$id));
        if($edit){
            $res['code'] = 1;
            $res['text'] = "正在出仓中";
            die(json_encode($res));
        }else{
            $res['code'] = 2;
            $res['text'] = "出仓失败";
            die(json_encode($res));
        }
    }

    // 出仓完成
    public function end(){
        $id = $this->input->post('id');
        $res =[];
        $edit = $this->db->update('ci_invoice_info',array('status'=>3,'end_time'=>time()),array('id'=>$id));
        if($edit){
            $res['code'] = 1;
            $res['text'] = "出仓完成";
            die(json_encode($res));
        }else{
            $res['code'] = 2;
            $res['text'] = "出仓失败";
            die(json_encode($res));
        }
    }

}