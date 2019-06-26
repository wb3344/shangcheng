<?php
namespace app\admin\controller;
use think\Controller;
use think\facade\Session;

class Admin extends Controller
{
    public function admin_list(){
        $name=input("username");
        $list=db('admin')->where('name','like','%'.$name.'%')->order("id",'desc')->paginate('3',false,['query'=>request()->param()]);

        // $list1=db('admin')->where("type",'like','%'.$_GET['sousuo'].'%')->select();
        // $list2=db("admin")->where("type",'like','%'.$_GET['sousuo'].'%')->paginate(5);
        $num=db("admin")->count();
        $this->assign('num',$num);
        $this->assign('list',$list);
        $role=Session::get('role'); 
        $this->assign('role',$role);
        $name=Session::get('name');
        $this->assign('name',$name);
        return $this->fetch();
}
	public function admin_add()
    {
        return $this->fetch();
    }
    public function insert(){
    $name=input('name');
    $phone=input('phone');
    $pass=input('pass');
    $email=input('email');
        $data['name']=$name;
        $data['phone']=$phone;
        $data['pass']=md5($pass);
        $data['email']=$email;
        $data['addtime']=date("Y-m-d H:i:s",time());
        $m=db("admin")->insert($data);
        if($m>0){
            return 1;
        }else{
            return 0;
        }
}
    public function delete(){
        $id=$_GET['id'];
        $m=db("admin")->where('id',$id)->delete();
    }

    
    // public function state(){
    //     db("user")->where("");
    // }



    public function admin_cate()
    {
        return $this->fetch();
    }
    //修改密码页面
    public function admin_edit()
    {
        $id=$_GET['id'];
        $list=db("admin")->where('id',$id)->select();
        $this->assign('list',$list);
        return $this->fetch("admin/admin_edit");
    }
    //修改密码方法
    public function xiugai()
    {
        $id=input('id');
        // $name=$_POST['username'];
        // $phone=$_POST['phone'];
        $pass=$_POST['pass'];
        // $email=$_POST['email'];
        // $data['name']=$name;
        // $data['phone']=$phone;
        $data['pass']=md5($pass);
        // $data['email']=$email;
        $m=db("admin")->where("id",$id)->update($data);
        if($m>0){
           return 1;
        }else{
           return 0;
        }
        
    }
    public function admin_role()
    {
        return $this->fetch();
    }
    public function member_stop(){
        $id=$_POST['id'];
        $state=$_POST["s"];
        $m=db("admin")->where('id',$id)->update(['state' => $state]);
        if($m > 0){
            return 1;
        }else{
            return 0;
        }
    }
    public function admin_rule()
    {
        return $this->fetch();
    }
        public function logout()
    { 
       session(null);
       $this->redirect('login/index');
    }

}