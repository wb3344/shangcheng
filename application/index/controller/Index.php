<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\facade\Session;

class Index extends Controller
{
    public function index()
    {	
    	//无限遍历
    	$one=db('type')->where('num',0)->select();
    	$two=db('type')->where('num',1)->select();
    	$three=db('type')->where('num',2)->select(); 		
    	// halt($ls);
    	$this->assign('one',$one);
    	$this->assign('two',$two);
    	$this->assign('three',$three);

    	//轮播图
    	$bn=db('banner')->limit(5)->select();
   		$this->assign('bn',$bn);

   		//商品
   		$goods=db('goods')->where("state",1)->limit(8)->select();
   		$this->assign('goods',$goods);

        //搜索下面的小类别
        $stype=db("type")->order('num desc')->limit(5)->select();
        $this->assign('stype',$stype);

        $count=db('cart')->count();
        // halt($count);
        $this->assign("count",$count);

    	//账号session
    	$list=Session::get('username');
    	$this->assign("list",$list);
        return $this->fetch();
    }


}
