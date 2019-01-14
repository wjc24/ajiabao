<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
		$this->jxcsys = $this->session->userdata('jxcsys');
    }
	
	public function index(){
	    //add by michen 20170820 for 修改登录异常 begin
	    if(!strstr($_SERVER['REQUEST_URI'], 'home/index'))
	        redirect('home/index', 'refresh');
	    //add by michen 20170820 for 修改登录异常 end
	    $data['uid']      = $this->jxcsys['uid'];
		$data['name']     = $this->jxcsys['name'];
		$data['roleid']   = 0;
		$data['username'] = $this->jxcsys['username']; 
        $data['system']   = $this->common_model->get_option('system'); 
		$data['rights']   = $this->common_model->get_admin_rights();
        $exchange = $this->db->order_by('id DESC')->get('ci_exchange')->row();
        $data['CNYtoJPY'] = $exchange->CNYtoJPY;
        $data['JPYtoCNY'] = $exchange->JPYtoCNY;
        $data['time'] = date('Y-m-d H:i:s',$exchange->time);
        
        $user = $this->session->userdata('jxcsys');
        $where = "(user_id =".$user['uid'].") AND (sign_time ='".date('Y-m-d',time())."')";
        $sign = $this->db->where($where)->get('ci_sign')->row();

        if($sign){
            $this->load->view('index',$data);
        }else{
            $this->load->view('/settings/sign',['user_id'=>$user['uid'],'username'=>$user['username']]);
        }


	}
	//签到
    public function sign(){
        $user_id = $this->input->post('user_id');
        $username = $this->input->post('username');
        $last_sign = strtotime("- 1 day",time());
        $sign = $this->db->where(['user_id'=>$user_id,'sign_time'=>date('Y-m-d',$last_sign)])->get('ci_sign')->row();
        if($sign){
            $add = array(
                'user_id' => $user_id,
                'username' => $username,
                'time' => time(),
                'sign_time' => date('Y-m-d',time()),
                'continuity' =>$sign->continuity +1,
                'sign_all' =>$sign->sign_all +1,
            );
        }else{
            $add = array(
                'user_id' => $user_id,
                'username' => $username,
                'time' => time(),
                'sign_time' => date('Y-m-d',time()),
                'continuity' =>1,
                'sign_all' =>$sign->sign_all +1,
            );
        }

        $sign_res = $this->db->insert('ci_sign',$add);
        die(json_encode($sign_res));
    }

    //签到初始化
    public function signStart(){
        $user_id = $this->input->post('user_id');

        $month_start =  mktime ( 0, 0, 0, date ( "m" ), 1, date ( "Y" ) );
        $month_end = mktime ( 23, 59, 59, date ( "m" ), date ( "t" ), date ( "Y" ) );
        $where = "(user_id =".$user_id.") AND (time >".$month_start."  AND time < ".$month_end.")";
        $sing_data =$this->db->where($where)->order_by('time DESC')->get('ci_sign')->result();

        die(json_encode($sing_data));
    }


    /**
     * 定时读取汇率
     */
    public function getExchange(){
        header('Content-type:text/html;charset=utf-8');
        $appkey = "f3b4653b51cc7969f299faf540952776";

        $url = "http://op.juhe.cn/onebox/exchange/currency";
        $params = array(
            "from" => "CNY",//转换汇率前的货币代码
            "to" => "JPY",//转换汇率成的货币代码
            "key" => $appkey,//应用APPKEY(应用详细页查询)
        );
        $paramstring = http_build_query($params);
        $content = $this->juhecurl($url,$paramstring);
        $result = json_decode($content,true);
        if($result){
            if($result['error_code']=='0'){
                $CNYtoJPY = $result['result'][0]['exchange'];
                $JPYtoCNY = $result['result'][1]['exchange'];
                $time = time();
                $arr = array(
                    'CNYtoJPY' => $CNYtoJPY,
                    'JPYtoCNY' => $JPYtoCNY,
                    'time'     => $time
                );
                $this->db->insert('ci_exchange',$arr);
//                $data['exchange'] = $CNYtoJPY;
            }else{
//                $data['exchange'] = $result['error_code'].":".$result['reason'];
            }
        }else{
//            $data['exchange'] = "请求失败";
        }
    }


    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    function juhecurl($url,$params=false,$ispost=0){
        $httpInfo = array();
        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_USERAGENT , 'JuheData' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if( $ispost )
        {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
        }
        else
        {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        return $response;
    }
	
>>>>>>> 08f98a6fcddb8fbcbd3a3f5731467cda5afd4316
	public function main(){
		$this->load->view('main');
	}
	

	public function set_password(){
		$data = str_enhtml($this->input->post(NULL,TRUE));
		if (is_array($data)&&count($data)>0) {
			$info['userpwd'] = md5($data['newPassword']);
			$info['mobile']  = $data['buyerMobile'];
			$info['name']    = $data['buyerName'];
			$this->mysql_model->get_count('admin','(uid<>'.$this->jxcsys['uid'].') and mobile='.$info['mobile'].'') >0 && str_alert(-1,'该手机号已被使用,请更换手机号码'); 
		    $sql = $this->mysql_model->update('admin',$info,'(uid='.$this->jxcsys['uid'].')');
			if ($sql) {
				$this->common_model->logs('密码修改成功 UID：'.$this->jxcsys['uid'].' 真实姓名改为：'.$info['name']);
				str_alert(200,'密码修改成功');
			}
			str_alert(-1,'设置独立密码失败，请稍候重试！');
		} else {
		    $data = $this->mysql_model->get_rows('admin','(uid='.$this->jxcsys['uid'].')');    
		    $this->load->view('set_password',$data);
		}
	}
	

	public function Services(){
		die('jQuery110202928952066617039_1430920204305({"status":200,"msg":"success","data":[{"msgid":"20000000122"
,"msglinkcolor":"d9254a","msglink":"","msgtitle":"售后热线服务时间临时调整通知（5.6-5.8）>>"},{"msgid":"20000000119"
,"msglinkcolor":"d9254a","msglink":"index.html","msgtitle"
:"推荐送ipad mini，购买就返利>>"},{"msgid":"20000000115","msglinkcolor":"d9254a","msglink":"","msgtitle":">>"},{"msgid":"20000000068","msglinkcolor":"d9254a","msglink":"","msgtitle":">
>"}]})');
		 
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */