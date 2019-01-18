<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deliver  extends CI_Controller {
    public function index(){


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
                $where_invoice_info = "(iid =".$v->id." AND billNo = '".$v->billNo."') AND (billNo LIKE '%".$like."%') AND (deliver_status = ".$sel.")";
            }else{
                $where_invoice_info = "(iid =".$v->id." AND billNo = '".$v->billNo."') AND (billNo LIKE '%".$like."%')";
            }
            $invoice_id = $v->id;
            $billNo = $v->billNo;
            $billDate = $v->billDate;
            $userName = $v->userName;
            $invoice_info = $this->db->where($where_invoice_info)->get('ci_invoice_info')->result();

            foreach ($invoice_info as $key=>$val){

                $goods[$t]['invoice_id'] = $invoice_id;
                $goods[$t]['invoice_info_id'] =$val->id;
                $goods[$t]['billNo'] =$billNo;
                $goods[$t]['billDate'] =$billDate;
                $goods[$t]['userName'] =$userName;
                $goods[$t]['good_id'] =$val->invId;
                $good = $this->db->where(['id'=>$val->invId])->get('ci_goods')->row();
                $goods[$t]['good_name'] =$good->name;
                $goods[$t]['good_num'] =substr($val->qty,1);
                $goods[$t]['issued_num'] =$val->issued_num;
                $goods[$t]['unissued_num'] =$val->unissued_num;
                $goods[$t]['deliver_status'] =$val->deliver_status;

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
        $good_name = $this->input->get('good_name');

        $data = $this->db->where(['id'=>$id])->get('ci_invoice_info')->row();
        $max_num = ((int)substr($data->qty,1))-$data->issued_num;
        $this->load->view('/settings/deliver_add',['id'=>$id,'good_name'=>$good_name,'max_num'=>$max_num]);
    }

    //新增发货信息
    public function doadd(){
        $res =[];
        $user = $this->session->userdata('jxcsys');

        $data = $this->input->post(NULL,TRUE);
        $logistics = $this->db->where(['number'=>$data['logistics']])->get('ci_logistics_list')->row();
        if(!$logistics){
            $add_1 = array(
                'number'=>$data['logistics'],
                'addtime'=>time(),
                'status'=>1,
                'checkName'=>$user['name'],
            );
            $result = $this->db->insert('ci_logistics_list',$add_1);
            $logistics_id = $this->db->insert_id();
            if(!$result){
                $res['code'] = 2;
                $res['text'] = "发货失败";
                die(json_encode($res));
            }
        }else{
            $logistics_id = $logistics->id;
        }

        $add = array(
            'invoice_info_id'=>$data['invoice_info_id'],
            'good_name'=>$data['good_name'],
            'shipping_name'=>$data['shipping_name'],
            'booking_number'=>$data['booking_number'],
            'port'=>$data['port'],
            'boxes'=>$data['boxes'],
            'box_volume'=>$data['box_volume'],
            'good_logistics'=>$data['good_logistics'],
            'content'=>json_encode($data['box']),
            'logistics'=>$data['logistics'],
            'logistics_id'=>$logistics_id,
            'start_time'=>time(),
            'logistics_remarks'=>$data['logistics_remarks'],
            'trade_remarks'=>$data['trade_remarks'],
        );
        $logistics_res = $this->db->insert('ci_logistics',$add);


            $invoice_info = $this->db->where(['id'=>$data['invoice_info_id']])->get('ci_invoice_info')->row();

            if(($invoice_info->unissued_num + $data['good_logistics']) == ((int)substr($invoice_info->qty,1))){
                $deliver_status = 3;
            }else{
                $deliver_status = 2;
            }
            $updata = $this->db->update('ci_invoice_info',array('issued_num'=>($data['good_logistics']+$invoice_info->issued_num),'unissued_num'=>((int)substr($invoice_info->qty,1) - $invoice_info->unissued_num - $data['good_logistics']),'deliver_status'=>$deliver_status),array('id'=>$data['invoice_info_id']));

        if($logistics_res && $updata){
            $res['code'] = 1;
            $res['text'] = "发货成功";
            die(json_encode($res));
        }else{
            $res['code'] = 2;
            $res['text'] = "发货失败";
            die(json_encode($res));
        }

    }

    //发货详情
    public function detail(){
        $id = $this->input->get('id');
        $data = $this->db->where(['invoice_info_id'=>$id])->get('ci_logistics')->result();

        $this->load->view('/settings/logistics_detail',['id'=>$id,'data'=>$data]);
    }


    //物流单列表
    public function logisticsList(){
        $like = $this->input->post('matchCon');
        $sel = $this->input->post('sel');
        $page_now = (int)$this->input->post('page_now');
        $page_num = (int)$this->input->post('page_num');

        if($sel && $sel != '0'){
            $where = "(number LIKE '%".$like."%' OR checkName LIKE '%".$like."%') AND (status = ".$sel.")";
        }else{
            $where = "(number LIKE '%".$like."%' OR checkName LIKE '%".$like."%')";
        }

        $total = $this->db->where($where)->count_all('ci_logistics_list');

        if(!$page_num){
            $page_num = 20;
        }

        $page_all = ceil($total/$page_num);
        if(!$page_now || $page_now == 1 || $page_now>$page_all){
            $page_now = 0 ;
        }else{
            $page_now = ($page_now-1)*$page_num;
        }
        $data = $this->db->where($where)->limit($page_num,$page_now)->get('ci_logistics_list')->result();

        $this->load->view('/settings/logistics_list',['data'=>$data,'like'=>$like,'sel'=>$sel,'page_now'=>$this->input->post('page_now'),'page_num'=>$page_num,'page_all'=>$page_all]);
    }

    //物流 商品列表
    public function logistics_list_goods(){
        $like = $this->input->post('matchCon');
        $sel = $this->input->post('sel');
        $page_now = (int)$this->input->post('page_now');
        $page_num = (int)$this->input->post('page_num');
        $logistics_id = $this->input->post('logistics_id');
        $id = $this->input->get('id');
        if(!$logistics_id){
            $logistics_id = $id;
        }
        if($sel && $sel != '0'){
            $where = "(logistics_id ='".$logistics_id."') AND (good_name LIKE '%".$like."%') AND (status = ".$sel.")";
        }else{
            $where = "(logistics_id ='".$logistics_id."') AND (good_name LIKE '%".$like."%')";
        }
        $total = $this->db->where($where)->count_all('ci_logistics');

        if(!$page_num){
            $page_num = 20;
        }

        $page_all = ceil($total/$page_num);
        if(!$page_now || $page_now == 1 || $page_now>$page_all){
            $page_now = 0 ;
        }else{
            $page_now = ($page_now-1)*$page_num;
        }
        $data = $this->db->where($where)->limit($page_num,$page_now)->get('ci_logistics')->result();


        $this->load->view('/settings/logistics_list_goods',['logistics_id'=>$logistics_id,'data'=>$data,'like'=>$like,'sel'=>$sel,'page_now'=>$this->input->post('page_now'),'page_num'=>$page_num,'page_all'=>$page_all]);

    }

    //物流 商品发货详情
    public function logistics_detail_detail(){

        $id = $this->input->get('id');

        $data = $this->db->where(['id'=>$id])->get('ci_logistics')->row();

        $this->load->view('/settings/logistics_good_detail',['id'=>$id,'data'=>$data]);
    }

    //物流 修改商品发货记录
    public function logistics_edit(){
        $data = $this->input->post(NULL,TRUE);

        $edit = array(
            'shipping_name'=>$data['shipping_name'],
            'booking_number'=>$data['booking_number'],
            'port'=>$data['port'],
            'boxes'=>$data['boxes'],
            'box_volume'=>$data['box_volume'],
            'good_logistics'=>$data['good_logistics'],
            'content'=>json_encode($data['box']),
            'logistics'=>$data['logistics'],
            'logistics_remarks'=>$data['logistics_remarks'],
            'trade_remarks'=>$data['trade_remarks'],
            'end_time'=>time(),
            'status'=>2,
        );
        $logistics_res = $this->db->update('ci_logistics',$edit,array('id'=>$data['invoice_info_id']));
        $result_1 = $this->db->where(['id'=>$data['logistics_id']])->get('ci_logistics_list')->row();


        if($result_1->end_time){

        }else{
            $result_2 = $this->db->where(['logistics_id'=>$data['logistics_id']])->get('ci_logistics')->result();
            $t = 0;
            foreach ($result_2 as $k=>$v){
                if($v->status == 1){
                    $t = 1;
                }
            }
            if($t == 0){
                $this->db->update('ci_logistics_list',array('end_time'=>time(),'status'=>3),array('id'=>$data['logistics_id']));
            }else{
                $this->db->update('ci_logistics_list',array('status'=>2),array('id'=>$data['logistics_id']));
            }
        }
        if($logistics_res){
            $res['code'] = 1;
            $res['text'] = "修改成功";
            die(json_encode($res));
        }else{
            $res['code'] = 2;
            $res['text'] = "修改失败";
            die(json_encode($res));
        }
    }
    //提醒
    public function remind(){
        $data = $this->input->post(NULL,TRUE);
        $res = [];
        foreach (json_decode($data['userName']) as $k=>$v){

            $customer = $this->db->where(['id'=>$v])->get('ci_customer')->row();
            $wechat = $this->wechat($customer->openid,$data['logistic'],'4444','2019-1-15');

            if($wechat['errcode'] != 0){
                $res['code'] = 2;
                $res['text'] = $customer->nickname."提醒错误".$wechat['errmsg'];
                die(json_encode($res));
            }
        }
        if($wechat->errcode == 0){
            $res['code'] = 1;
            $res['text'] = "通知成功";
            die(json_encode($res));

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

            if($access_tokens['errcode'] != 0){
                return $access_tokens;
            }else{
                $this->db->update('ci_accesstoken',array('accesstoken'=>$access_tokens['access_token'],'time'=>time() + 7000),array('id'=>1));

                $this->db->where(['id'=>1])->get('ci_accesstoken')->row();
            }




        }
        return "aaa";
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