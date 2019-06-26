<?php
namespace app\admin\controller;
use think\Controller;
use think\facade\Session;

class Order extends Controller
{
	public function order_list()
    {
    	//账号session
    	$list=Session::get('username');
    	$this->assign("list",$list);
    	$uid=Session::get('userid');
    	// var_dump($uid);
    	// die();
        // $ornumber=input("ornumber");
        if (isset($_GET['ornumber'])) {
            $order=db('orders')->where('ornumber','like','%'.$_GET['ornumber'].'%')->order("id",'desc')->paginate('9',false,['query'=>request()->param()]);
        }else{
            $order=db('orders')->order("id",'desc')->paginate('9',false,['query'=>request()->param()]);
        }
    	
    	// var_dump($order);
    	// die();
        $num1=db("orders")->count();
        $this->assign('num1',$num1);
    	$this->assign("order",$order);
        return $this->fetch();
    }
	public function order_add()
    {
        return $this->fetch();
    }

    //点击确认退货
    public function tuihuo(){
        if (isset($_GET['state'])) {
            $data['state']=$_GET['state'];
        $m=db("orders")->where('id',$_GET['id'])->update($data);
            if($m>0){
            $order=db("orders")->order('id','desc')->paginate(5);
            $num1=db("orders")->count();
            $this->assign('num1',$num1);
            $this->assign("order",$order);   
            return $this->fetch("order/order_list");
            }else{
            $order=db("orders")->order('id','desc')->paginate(5);
            $num1=db("orders")->count();
            $this->assign('num1',$num1);
            $this->assign("order",$order);   
            return $this->fetch("order/order_list");
            }
        }
    }

    //点击发货
    public function fahuo(){
    	// $id=$_POST['orderid'];
     //    $data['state']=2;
    	// $m=db("order")->where(id,$id)->update($data);
    	// if($m>0){
    	// 	return "1";
    	// }else{
    	// 	return "0";
    	// }
        if (isset($_GET['state'])) {
            $data['state']=$_GET['state'];
            
        $m=db("orders")->where('id',$_GET['id'])->update($data);
            if($m>0){
             $order=db("orders")->order('id','desc')->paginate(5);
            $num1=db("orders")->count();
            $this->assign('num1',$num1);
            $this->assign("order",$order);   
            return $this->fetch("order/order_list");
            }else{
              $order=db("orders")->order('id','desc')->paginate(5);
            $this->assign("order",$order);   
             return $this->fetch("order/order_list");
            }
        }
    }

}