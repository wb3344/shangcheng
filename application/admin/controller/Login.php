<?php
namespace app\admin\Controller;
use think\Controller;
use think\facade\Session;
class Login extends Controller
{
	public function index(){
		return $this->fetch("Login/login");
    }
    public function login(){
    //接受传参
    $name = input('name');
    $password = md5(input('password'));

    //查询数据
    $list = db('admin')->where("name",$name)->find();
    Session::set('name',$name);
    Session::set('role',$list['role']);
    Session::set('userid',$list['id']);
    if($list){
        if ($password == $list['pass']){
            if($list['state'] == 1){
                return 1;
            }else{
                return 3;
            }
               
        }else{
            return 0;
        }
        }else{
            return 2;
        }
          
    }
}