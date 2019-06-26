<?php 
namespace app\index\Controller;
use think\facade\Session;
use think\Controller;
use think\Db;

class Order extends Controller
{
	public function uc(){
		//账号session
    	$list=Session::get('username');
    	$this->assign("list",$list);
        $uid=Session::get("userid");
        $wuliu=db("orders")->where("state",2)->where("uid",$uid)->paginate(3);
        $this->assign("wuliu",$wuliu);
		return $this->fetch("order/uc");
	}

	public function uc_address(){
		//账号session
    	$list=Session::get('username');
    	$this->assign("list",$list);
    	$uid=Session::get('userid');
    	$m=db("sit")->where('uid',$uid)->select();
    	// echo "<pre>";
    	// var_dump($m);
        // echo $uid;
    	// die();
    	$this->assign("m",$m);
		return $this->fetch("order/uc_address");
	}

	// 修改默认地址
	public function moren(){
		$id=input("moren");
		$data['moren']=1;
	$m=db("sit")->where("id",$id)->update("$data");
	var_dump($m);
	}
	//退货方法
	public function tuihuo(){
        $uid=Session::get("userid");
        if (isset($_GET['state'])){
    if($_GET['state']==6){
		$list=Session::get('username');
    	$this->assign("list",$list);
        $data['state']=$_GET['state'];
        $id=$_GET['id'];
        $m=db("orders")->where('id',$id)->update($data);
        if($m>0){
             $order=db("orders")->where("uid",$uid)->order('id','desc')->where("state",">",5)->paginate(5);
            $this->assign("order",$order);
            echo "<script>alert('商品退款申请成功!')</script>";
            return $this->fetch("order/uc_apply_refund");
        }else{
             $order=db("orders")->where("uid",$uid)->order('id','desc')->where("state",">",5)->paginate(5);
            $this->assign("order",$order);
             return $this->fetch("order/uc_order");
            }
    }else{
        $list=Session::get('username');
        $this->assign("list",$list);
        $data['state']=$_GET['state'];
        $id=$_GET['id'];
        $m=db("orders")->where('id',$id)->where('uid',$uid)->update($data); 
        if($m>0){
             $order=db("orders")->order('id','desc')->paginate(5);
            $this->assign("order",$order);
            echo "<script>alert('取消退款申请成功!')</script>";
            return $this->fetch("order/uc_order");
        }else{
             $order=db("orders")->order('id','desc')->paginate(5);
            $this->assign("order",$order);   
             return $this->fetch("order/uc_order");
            }
    }
           
        }
	}
	//退款/退货
	public function uc_apply_refund(){
		//账号session
		$uid=Session::get('userid');
    	$list=Session::get('username');
    	$this->assign("list",$list);
    	// $id=$_GET['id'];
    	// $order=db('orders')->where('uid',$uid)->where("state",">",5)->select();
    	if (isset($_GET['id'])) {
            $id=$_GET['id'];
            $order=db('orders')->where('id',$id)->where('uid',$uid)->select();
        }else{
            $order=db("orders")->where("state",">",5)->where('uid',$uid)->select();
        }
    	$this->assign("order",$order);
		return $this->fetch("order/uc_apply_refund");
		}

		public function address_add(){
			return $this->fetch("order/address_add");
		}

		public function uc_evaluate(){
			if(isset($_GET['id'])){
				$data['id']=$_GET['id'];
		}
		//账号session
    	$list=Session::get('username');
    	$this->assign("list",$list);
    	$uid=Session::get('userid');
    	$order=db('orders')->where('id',$data['id'])->select();
    	$this->assign("order",$order);
		return $this->fetch("order/uc_evaluate");
	}

	public function uc_allevaluate()
	{   
        $uid=Session::get('userid');
		//账号session
    	$list=Session::get('username');
    	$this->assign("list",$list);
        $order=Db::field('o.goodsname,o.state,o.gid,o.price,o.ornumber,p.id,p.did,o.username,p.content,p.huifu,o.addtime')//截取表s的name列 和表a的全部
        ->table(['orders'=>'o','pingjia'=>'p'])
        ->where('o.id=p.did')//查询条件语句
        ->where("o.uid",$uid)
        ->where("o.state",">",2)
        ->where("o.state","<",5)
        ->paginate(2);
    	// $order=db("pingjia")->where("uid",$uid)->paginate(4);
        $this->assign("order",$order);
		return $this->fetch("order/uc_allevaluate");
	}
	public function uc_msg(){
		//账号session
    	$list=Session::get('username');
    	$this->assign("list",$list);
		return $this->fetch("order/uc_msg");
	}

	public function uc_order(){
		//账号session
    	$list=Session::get('username');
    	$this->assign("list",$list);
    	$uid=Session::get('userid');
        if (isset($_GET['state'])) {
            $state=$_GET['state'];
            $order=db('orders')->where("state",$state)->where('uid',$uid)->paginate(4);
        }else{
            $order=db('orders')->where('uid',$uid)->paginate(4);
        }
    	
    	$this->assign("order",$order);
		return $this->fetch("order/uc_order");
	}


	public function uc_order_detail(){
		//账号session
    	$list=Session::get('username');
    	$this->assign("list",$list);
    	$uid=Session::get('userid');
    	// var_dump($uid);
    	// die();
    	$order=db('orders')->where('uid',$uid)->select();
    	// var_dump($order);
    	// die();
    	$this->assign("order",$order);
		return $this->fetch("order/uc_order_detail");
	}

	public function uc_refund(){
		//账号session
    	$list=Session::get('username');
    	$this->assign("list",$list);
		return $this->fetch("order/uc_refund");
	}
	//添加评价
	public function pingjia()
    {   
        $uid=Session::get("userid");
        //执行删除
        if (isset($_GET['del'])) {
            $pid=$_GET['del'];
            db("pingjia")->where("id",$pid)->delete();
                $order=db('orders')->where('uid',$uid)->paginate(4);
                $this->assign("order",$order);
                $list=Session::get('username');
                $this->assign("list",$list);
                return $this->fetch("order/uc_order");
        }else{
           $data['goodsname']=$_POST['goodsname'];
            $data['username']=$_POST['username'];
            $data['ornumber']=$_POST['ornumber'];
            $data['content']=$_POST['content'];
            $data['gid']=$_POST['gid'];
            $data['uid']=$uid;
            $data['did']=$_POST['did'];
            $data['addtime']=date("Y-m-d H:i:s",time());
            $m=db("pingjia")->insert($data);

            if($m>0){
                // echo "添加成功!";
                $info['state']=4;
                db('orders')->where('id',$_POST['did'])->update($info);
                $order=db('orders')->where('uid',$uid)->paginate(4);
                $this->assign("order",$order);
                $list=Session::get('username');
                $this->assign("list",$list);
                return $this->fetch("order/uc_order");
            }else{
                // echo "添加失败!";
                $order=db('orders')->where('uid',$uid)->paginate(4);
                $this->assign("order",$order);
                $list=Session::get('username');
                $this->assign("list",$list);
                return $this->fetch("order/uc_order");
            } 
        }
        
	}
	//删除订单
	public function shanchu(){
		$list=Session::get('username');
    	$this->assign("list",$list);
		$id=$_POST['id'];
        $m=db("orders")->where('id',input('id'))->delete();
	}
    public function del(){
        // halt(input('id'));
        $dl=db('orders')->where('id',input('id'))->delete();
        if ($dl) {
            return 1;
        }else{
            return 0;
        }
    }

	//确认收货
	public function queren(){
		    if (isset($_GET['state'])) {
            $data['state']=$_GET['state']; 
        $m=db("orders")->where('id',$_GET['id'])->update($data);
            if($m>0){
            echo "<script>alert('确认收货成功!')</script>";
            $order=db("orders")->order('id','desc')->paginate(5);
            $list=Session::get('username');
    		$this->assign("list",$list);
            $this->assign("order",$order);   
             return $this->fetch("order/uc_order");
            }else{
            $order=db("orders")->order('id','desc')->paginate(5);
            $this->assign("order",$order);   
             return $this->fetch("order/uc_order");
            }
        }
    }
}

 ?>