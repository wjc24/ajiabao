<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deliver  extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->common_model->checkpurview(207);
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
        $customer = $this->db->get('ci_customer')->result();
        $this->load->view('/settings/logistics_good_detail',['id'=>$id,'data'=>$data,'customer'=>$customer]);
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