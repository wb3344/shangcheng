<?php

namespace app\index\Controller;

use think\Controller;
use think\Request;
use think\facade\Session;


class Car extends Controller
{
	public function cart(){
		$user=Session::get('username');
        $this->assign("user",$user);
		$userid=Session::get("userid");

		$list=db('cart')->where('uid',$userid)->select();
		$sum=0;
		$this->assign('sum',$sum);
		$this->assign('list',$list);
		return $this->fetch('car/cart');
	}
	


	public function del(){
		// halt(input('id'));
		$m=db("cart")->where('id',input('id'))->delete();
		// halt($m);
		return $m;
	}

	public function sc(){
		$m=db('sit')->where('id',input('id'))->delete();
		return $m;
	}

	
	public function sell()
	{	
		if (isset($_POST['id'])) {
			return $_POST['id'];
		}else{
			return 0;
		}
		
	}
	public function confirm_order(){
        $userid=Session::get("userid");
        $user=Session::get("username");
		$this->assign('user',$user);
		
		if (isset($_GET['id'])) {
				//根据商品id获取商品信息
			$str=rtrim($_GET['id'],",");
			// return $str;
			$arr=explode(",", $str);
			$list=[];
			for ($i=0; $i <count($arr) ; $i++) { 
				$id=$arr[$i];
				$list[$i]=db('cart')->where("uid",$userid)->where("id",$id)->select();
			}
			foreach ($list as $value) {
				foreach ($value as $v) {
					$all[]=$v;
				}
				
			}
			$this->assign('list',$all);
		}
        

		$a=db('sit')->select();
		$this->assign('a',$a);

		
		$sum=0;
		$this->assign('sum',$sum);

		
		return $this->fetch();
     }
		
	public function buy(){
		$user=Session::get('username');
        // halt($list);
        $this->assign("user",$user);
		
		if (isset($_GET['data'])) {
			$all=$_GET['data'];
			// var_dump(input());
			$list=explode("=", $all);
			$k=$list[0];
			$key=explode("@", rtrim($k,"@"));
			$v=$list[1];
			$val=explode("@", $v);
			$data=array();
			for ($i=0; $i <count($key) ; $i++) { 
				$data[$key[$i]]=$val[$i];
			}
		}else{
			$data=Session::get("oneorder");
		}
		
		// print_r($data);
		// die;
		
		$userid=Session::get("userid");
        //根据商品id获取商品信息

		// $id=input('id');
		// $v=db('goods')->find($id);
		$this->assign('v',$data);
		Session::set("oneorder",$data);
		// echo "<pre>";
		// var_dump($data);
		// die;

		// $num=input('num');
		// $this->assign('num',$num);

		$a=db('sit')->select();
		$this->assign('a',$a);

		
		$sum=0;
		$this->assign('sum',$sum);

		
		return $this->fetch();
	}

	public function edit(){
		// halt($_GET['id']);
			$id=input('id');
			$ls=db('sit')->find($id);
			// halt($ls);
			// $this->assign('ls',$ls);
			return $ls;
			// return $this->fetch();

	}

	public function update(){
			$data['id']=$_POST['id'];
			$data['name']=$_POST['name'];
			$data['phone']=$_POST['phone'];
			$data['site']=$_POST['site'];
			$data['site1']=$_POST['site1'];
			$data['site2']=$_POST['site2'];
			$data['site3']=$_POST['site3'];
			$data['code']=$_POST['code'];	
			// halt($data['id']);

			$m=db("sit")->where('id',$data['id'])->update($data);
			// halt($m);
			if($m){
				$oneorder=Session::get("oneorder");
				if (isset($oneorder)) {
					$this->assign('v',$oneorder);
					$this->redirect('Car/buy');
				}else{
					$userid=Session::get("userid");
					$list=db('cart')->where("uid",$userid)->select();
					$this->assign('list',$list);
					$this->redirect('Car/confirm_order');	
				}
				
			}else{
				return "修改失败";
			}
	}

	public function insert(){
			// halt(input('name'));

			$data['name']=$_POST['name'];
			$data['phone']=$_POST['phone'];
			$data['site']=$_POST['site'];
			$data['site1']=$_POST['site1'];
			$data['site2']=$_POST['site2'];
			$data['site3']=$_POST['site3'];
			$data['code']=$_POST['code'];

			$m=db("sit")->insert($data);
			// halt($m);
			if($m){
				return "添加成功";		
			}else{
				return "添加失败";
			}
	}
	public function sit(){
		$id=input("id");
		$sit=db('sit')->find($id);
		$sit1=$sit['site'].$sit['site1'].$sit['site2'].$sit['site3'];
		Session::set("sit",$sit1);
		Session::set("sitname",$sit['name']);
		Session::set("sitphone",$sit['phone']);
	}
	public function pay1(){
		

		// halt($data['phone']);
		$userid=Session::get("userid");
		$user=Session::get("username");
		$this->assign('user',$user);
		// $id=input('id');
		//查看购物车的值
		$cart=db('cart')->where("uid",$userid)->select();
		
		$data=array();
		Session::set("orid",time().rand(00000000,99999999));
		$orid=$data['ornumber']=Session::get("orid");
		
		
		foreach ($cart as $v) {
			//接收地址表里的值
		
		$userid=Session::get("userid");

		// $sit=db('sit')->find($id);
			$data['site']=Session::get("sit");
			$data['name']=Session::get("sitname");
			$data['phone']=Session::get("sitphone");
		
		// $data['site']=$sit['site'].$sit['site1'].$sit['site2'].$sit['site3'];
			$carid=$v['id'];
			// $data['name']=$sit['name'];
			// $data['phone']=$sit['phone'];
			
			$data['gid']=$v['gid'];
			$data['uid']=$userid;
			$data['goodsname']=$v['goodsname'];
			$data['price']=$v['price'];
			$data['size']=$v['size'];
			$data['color']=$v['color'];
			$data['brand']=$v['brand'];
			$data['num']=$v['num'];
			$data['sum']=$v['num']*$v['price'];
			$data['img']=$v['img'];
			$data['state']=5;
			$data['username']=Session::get('username');
			$data['addtime']=date("Y-m-d H:i:s",time());
			$aa=db('orders')->insert($data);

			//下单成功后，商品库存减少
			$gid=$v['gid'];
			$color=$v['color'];
			$img=db("image")->where("gid",$gid)->where("color",$color)->select();
			$kc=$img[0]['kucun'];
			$kucun=$kc-$v['num'];
			$k['kucun']=$kucun;
			db("image")->where("gid",$gid)->where("color",$color)->update($k);

			//销量增加
			$s=db("goods")->where("id",$gid)->select();
			$sel=$s[0]['sell'];
			$sell=$sel+$v['num'];
			$se['sell']=$sell;
			db("goods")->where("id",$gid)->update($se);
			//删除购物车里面的东西
			db("cart")->where('id',$carid)->where('uid',$userid)->delete();
		}
			
		$userid=Session::get("userid");
		// $list=array();
		// for ($i=0; $i <count($orid) ; $i++) { 
		$orid=Session::get("orid");
		$list=db('orders')->where('state',5)->select();
			
		// echo "<pre>";
		// 	print_r($list);
		// echo $orid;
		// 	die;
		$this->assign('list',$list);
		return $this->fetch("car/pay");
	}

	public function buypay()
	{	
		$user=Session::get('username');
        $this->assign("user",$user);
		//接收地址表里的值
		$id=input('id');
		$userid=Session::get("userid");
		//根据地址id查询
		$sit=db('sit')->find($id);
		$data['site']=$sit['site'].$sit['site1'].$sit['site2'].$sit['site3'];
		$data['name']=$sit['name'];
		$data['phone']=$sit['phone'];
		$data['username']=Session::get("username");
		//获取商品所有信息
		$goods=$_GET;
		$data['uid']=$goods['uid'];
		$data['gid']=$goods['gid'];
		$data['goodsname']=$goods['goodsname'];
		$data['size']=$goods['size'];
		$data['color']=$goods['color'];
		$data['brand']=$goods['brand'];
		$data['price']=$goods['price'];
		$data['num']=$goods['num'];
		$data['img']=$goods['img'];
		$data['sum']=$goods['num']*$goods['price'];
		$data['addtime']=date("Y-m-d H:i:s",time());
		$data['ornumber']=time().rand(00000000,99999999);
		// print_r($data);
		$aa=db('orders')->insert($data);

		//下单成功后，商品库存减少
			$gid=$goods['gid'];
			$color=$goods['color'];
			$img=db("image")->where("gid",$gid)->where("color",$color)->select();
			$kc=$img[0]['kucun'];
			$kucun=$kc-$goods['num'];
			$k['kucun']=$kucun;
			db("image")->where("gid",$gid)->where("color",$color)->update($k);

			//销量增加
			$s=db("goods")->where("id",$gid)->select();
			$sel=$s[0]['sell'];
			$sell=$sel+$goods['num'];
			$se['sell']=$sell;
			db("goods")->where("id",$gid)->update($se);
		$oid=db("orders")->order('id desc')->limit(1)->select();
		// print_r($oid);
		// die;
		// $oid=select ident_current('orders');
		$oid=$oid[0]['id'];
		$list=db("orders")->where("id",$oid)->select();
		$this->assign('list',$list);
		if ($aa>0) {
			return $this->fetch("car/buypay");
		}
	}

	// public function pay(){
	// 	//读取session里的uid 然后通过uid判断查询这个账号购买的物品
	// 	$userid=Session::get("userid");
	// 	$list=db('orders')->where('uid',$userid)->select();
	// 	$this->assign('list',$list);

	// 	// $sum=0;
	// 	// $this->assign('sum',$sum);
	// 	// echo $list;
	// 	// halt($list);
	// 	return $this->fetch("car/pay");
	// 	// return 1;
	// }


	public function over(){
		$user=Session::get('username');
		$this->assign("user",$user);
		$uid=Session::get('userid');
		$data['state']=1;
		$a=db("orders")->where("uid",$uid)->where("state",5)->update($data);
        if ($a>0) {
        	return $this->fetch("car/over");
        }
		
	}

}


 ?>