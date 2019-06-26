<?php 
namespace app\index\Controller;

use think\Controller;
use think\facade\Session;

class Safe extends Controller
{
	public function uc_safe(){
        $list=Session::get("username");
        $this->assign("list",$list);
		return $this->fetch("safe/uc_safe");
	}
	public function code(){
	//生成一个随机的验证码
	$code=rand(0000,9999);
	//接收手机号
	$phone=$_POST['phone'];
	// return $phone;
	Session::set("phone",$phone);
	Session::set("code",$code);
 	$host = "https://fesms.market.alicloudapi.com";//api访问链接
    $path = "/sms/";//API访问后缀
    $method = "GET";
    $appcode = "f0c5f0541b5c4b74bd57040c39ee4df0";//替换成自己的阿里云appcode
    $headers = array();
    array_push($headers, "Authorization:APPCODE " . $appcode);
    $querys = "code={$code}&phone={$phone}&skin=1&sign=1";  //参数写在这里, 自定义skin编号请找客服申请
    $bodys = "";
    $url = $host . $path . "?" . $querys;//url拼接

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_FAILONERROR, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    // curl_setopt($curl, CURLOPT_HEADER, true); 如不输出json, 请打开这行代码，打印调试头部状态码。
    // 状态码: 200 正常；400 URL无效；401 appCode错误； 403 次数用完； 500 API网管错误
    if (1 == strpos("$".$host, "https://"))
    {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    }
    $json = curl_exec($curl);
    $arr = json_decode($json,true);
    dump($arr);
    	if($arr['Code'] == 'OK') {
    		return 1;
    	}else{
    		return 0;
    	}
	}
	public function uc_verify(){
		$username=Session::get('username');
		$list=db("user")->where("username",$username)->find();
		$phone=$list['phone'];
		$phones=substr_replace($phone, '****', 3, 4);
		$this->assign('list',$list);
		$this->assign('phones',$phones);
		$this->assign('phone',$phone);
		return $this->fetch("safe/uc_verify");
	}
    //下一步
    public function xyb(){
        $phone=input('phone');
        $code=input('code');
        if($code==Session::get('code')){
            return $this->success('验证成功','Safe/uc_verify2');
        }else{
            return $this->success('验证失败','Safe/uc_verify');
        }
    }
    //第二步
    public function deb(){
        $username=Session::get('username');
        $pass=md5($_POST['pass']);
        $repass=md5($_POST['repass']);
        $data['pass']=$pass;

        if($data['pass']==null){
            return $this->success('密码不能为空','Safe/uc_verify2');
        }else{
        if($repass==$data['pass']){
        $m=db("user")->where("username",$username)->update($data);
        if($m>0){
            return $this->success('修改密码成功','Safe/uc_verify3');
        }else{
            echo "哈哈";
        }
    }else{
        return $this->success('两次密码不一致','Safe/uc_verify2');
    }
    }
}
	public function uc_verify2(){
		return $this->fetch("safe/uc_verify2");
	}

	public function uc_verify3(){
		return $this->fetch("safe/uc_verify3");
	}
}

 ?>