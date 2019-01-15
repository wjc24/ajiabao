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
                $goods[$t]['remind'] =$val->remind;
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
        $customer = $this->db->get('ci_customer')->result();

        $this->load->view('/settings/deliver',['data'=>$new_goods,'like'=>$like,'sel'=>$sel,'page_now'=>$this->input->post('page_now'),'page_num'=>$page_num,'page_all'=>$page_all,'customer'=>$customer]);
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

    //提醒
    public function remind(){
        $data = $this->input->post(NULL,TRUE);
        $res = [];
        foreach (json_decode($data['userName']) as $k=>$v){

            $customer = $this->db->where(['id'=>$v])->get('ci_customer')->row();
            $wechat = $this->wechat($customer->openid,'111','4444','2019-1-15');

            if($wechat->errcode != 0){
                $res['code'] = 2;
                $res['text'] = $customer->nickname."提醒错误".$wechat->errmsg;
                die(json_encode($res));
            }
        }
        if($wechat->errcode == 0){
            $edit = $this->db->update('ci_invoice_info',array('remind'=>2),array('id'=>$data['invoice_info_id']));
            if($edit){
                $res['code'] = 1;
                $res['text'] = "通知成功";
                die(json_encode($res));
            }else{
                $res['code'] = 2;
                $res['text'] = "通知失败";
                die(json_encode($res));
            }

        }
    }

    public function wechat($openid,$uid,$actual_total,$time){

        $appid = "wx753a3c4c7a501de8";
        $appsecret = "7237bb051936fca47440cb9c545dba96";

        $access_token =  $this->db->where(['id'=>1])->get('ci_accesstoken')->row();

        if(!$access_token || $access_token->time < time()){

            //获取$access_token
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $appsecret . "";

            $result = $this->curl_post($url);

            $access_tokens = json_decode($result, true);

            $this->db->update('ci_accesstoken',array('accesstoken'=>$access_tokens['access_token'],'time'=>time() + 7000),array('id'=>1));

            $access_token =  $this->db->where(['id'=>1])->get('ci_accesstoken')->row();


        }
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token->accesstoken;
        $data = '{
                   "touser":"'.$openid.'",
                    "template_id":"DHXPsozgUeznmQ7qqwzYUTsGy-SIdAIwkWfFgDD9zqI",
                    "url":"http://www.baidu.com",           
                   "data":{
                        "first": {
                            "value":"您有一笔订单需确认！",
                               "color":"#173177"
                           },
                           "keyword1":{
                            "value":"'.$uid.'",
                               "color":"#173177"
                           },
                           "keyword2": {
                            "value":1,
                               "color":"#173177"
                           },
                           "keyword3": {
                            "value":"'.$actual_total.'元",
                               "color":"#173177"
                           },
                           "keyword4": {
                            "value":"'.date('Y-m-d H:i',$time).'",
                               "color":"#173177"
                           },
                           "remark":{
                            "value":"请点击详情确认订单！",
                               "color":"#173177"
                           }
                   }
                }';
        $re = $this->curlPost($url,$data);
        return $re;
    }

    public static function curlPost($url = '', $postData = '', $options = array())
    {
        if (is_array($postData)) {
            $postData = http_build_query($postData);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public function curl_post($url, array $params = array())
    {

        $data_string = json_encode($params);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json'
            )
        );
        $data = curl_exec($ch);

        curl_close($ch);
        return ($data);
    }
}