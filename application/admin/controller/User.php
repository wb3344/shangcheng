<?php
namespace app\admin\controller;
use think\Controller;
use think\facade\Session;

class User extends Controller
{
	public function member_add()
    {
        return $this->fetch();
    }
    public function insert(){
    $data['email']=$_POST['email'];
    $data['pass']=md5($_POST['pass']);
    $data['name']=$_POST['name'];
    $data['addtime']=date("Y-m-d H:i:s",time());
        $m=db("user")->insert($data);
        if($m>0){
            $this->success("添加成功","Index/index");
        }else{
            $this->success("添加失败","Index/sign");
        }
}
    public function delete(){
        $id=$_GET['id'];
        $m=db("user")->where('id',$id)->delete();
    }
    public function member_del()
    {
        return $this->fetch();
    }
    public function member_edit()
    {
        return $this->fetch();
    }
    public function member_list()
    {
        $name=input("username");
        $list=db('user')->where('username','like','%'.$name.'%')->order("id",'desc')->paginate('2',false,['query'=>request()->param()]);
        $this->assign('list',$list);
        $num=db("user")->count();
        $this->assign('num',$num);
        $role=Session::get('role');
        $this->assign('role',$role);
        return $this->fetch();
    }
    public function member_stop(){
        $id=$_POST['id'];
        $state=$_POST["s"];
        $m=db("user")->where('id',$id)->update(['state' => $state]);
        if($m > 0){
            return 1;
        }else{
            return 0;
        }
    }
    public function member_password()
    {
        return $this->fetch();
    }
}