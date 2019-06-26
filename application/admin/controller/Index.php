<?php
namespace app\admin\controller;
use think\Controller;
use think\facade\Session;
class Index extends Controller
{
    public function index(){
        $list=Session::get('name');
        $this->assign("list",$list);
        return $this->fetch('index/index');
    }
   
    public function role_add()
    {
        return $this->fetch();
    }
    public function welcome()
    {
        $list=Session::get('name');
        // echo "<pre>";
        // var_dump($list);
        $this->assign('list',$list);
        return $this->fetch();
    }
}