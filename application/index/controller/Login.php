<?php 
namespace app\index\Controller;

use think\Controller;
use think\facade\Session;

class Login extends Controller
{
	public function index(){
		return $this->fetch("login/login");
	}


	public function login(){
		$username=input('username');
		// echo $username;
		// $phone=$_POST['phone'];
		$pass=input('pass');
		$list=db('user')->where('username',$username)->find();
		// var_dump($list);
		if($list['username']==$username || $list['phone']==$username || $list['email']==$username){
			if($list['pass']==md5($pass)){
				if($list['state']==2){
				echo "<script>alert('该账户已被停用!')</script>";
					return $this->fetch("login/login");
				}else{
				Session::set('username',$username);
				Session::set('userid',$list['id']);
				$shopid=Session::get('shopid');
				if($shopid!=null){
					return $this->redirect('Shop/goods_detail');
				}else{
					return $this->redirect('Index/index');
				}
			}
				
			}else{
				return $this->success('密码错误','Login/index');
			}
		}else{
			return $this->success('账号不存在','Login/index');
		}
	}



	public function username(){
		$username=$_GET['username'];
		$list = db('user')->where('username',$username)->find();
		if($list==null){
			return "no";
		}else{
			return "yes";
		}
	}
		

	public function code(){
	//生成一个随机的验证码
	$code=rand(0000,9999);
	//接收手机号
	$phone=$_GET['phone'];
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
    // dump($arr);
    	if($arr['Message'] == 'OK') {
    		return 1;
    	}else{
    		return 0;
    	}
	}


	public function xyb(){
    $phone=input('phone');
    Session::set('phone',$phone);
    $code=input('code');
    if($code==Session::get('code')){
        return $this->success('验证成功','Login/reset_pwd');
    }else{
        return $this->success('验证失败','Login/forgot_pwd');
    }
}
	//完成
    public function wc(){
        $phone=Session::get('phone');
        $pass=md5($_POST['pass']);
        $repass=md5($_POST['repass']);
        $data['pass']=$pass;
        if($data['pass']==null){
            return $this->success('密码不能为空','Login/reset_pwd');
        }else{
        if($repass==$data['pass']){
        $m=db("user")->where("phone",$phone)->update($data);
        if($m>0){
            return $this->success('修改密码成功','login/index');
        }else{
            return $this->success('密码不能与原密码一致','login/reset_pwd');
        }
    }else{
        return $this->success('两次密码不一致','Login/reset_pwd');
    }
    }
}
	public function sign(){
		return $this->fetch("login/sign");
	}

//前台注册
	public function insert(){
		// dump($_POST);
		// die();
	$username=$_POST['username'];
	$phone=$_POST['phone'];
	$pass=$_POST['pass'];
	$cid=$_POST['cid'];
		Session::set('username',$username);
		$data['username']=$username;
		$data['phone']=$phone;
		$data['pass']=md5($pass);
		$list = db('user')->where('username',$username)->find();
		if($username==$list['username']){
			return $this->success('用户名已存在','Login/sign');
		}else{
			if($phone==Session::get('phone')){
		$m=db("user")->insert($data);
		if($m>0){
			return $this->success('注册成功','Login/index');
		}else{
			return $this->success('注册失败','Login/sign');
		}
	}else{
		return $this->success('手机号或验证码有误!','Login/sign');
		}
	}
	}
	

	public function forgot_pwd(){
		return $this->fetch("login/forgot_pwd");
	}


	public function forgot(){
		$phone=input('phone');
		$code=input('code');
		if($phone == Session::get('phone') || $code == Session::get('code'))
		{
			$data['phone']=$phone;
			$data['code']=$code;

			return 1;
		}else{
			return 0;
		}

	}



	public function reset_pwd(){
		return $this->fetch("login/reset_pwd");
	}



	public function reset(){
		$data['phone']=$_POST['pass'];
		$rephone=$_POST['repass'];
		// $m=db('user')update($data);
	}
	public function logout()
	{ 
	   session(null);
	   $this->redirect('Index/index');
	}

}

 ?>