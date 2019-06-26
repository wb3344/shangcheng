<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\facade\Session;

class Shop extends Controller
{

    public function cart(){
     $username=Session::get('username');
     // halt(Session::get('userid'));
    if (!isset($username)) {
        return 0;
     }else{

        if (Session::get("cid")==null) {
            return 1;
            
        }
        if (Session::get("size")==null) {
              // halt($images);
              return 2;
              
        }
        
        //获取颜色信息
        $cid=Session::get("cid");
        $images=db("image")->find($cid);
        //获取该商品该颜色的库存
        $kucun=$images['kucun'];
        if ($kucun < $_GET['num']) {
            return "库存不足";
            die;
        }
        $color=$images['color'];
         $data['color']=$color;

        $image=$images['image'];
         $data['img']=$image; 
        // halt($images);
         //获取尺码信息
        $size=Session::get("size");
        $data['size']=$size;   
        //获取商品信息
        $id=$_GET['id'];
        $brand=$_GET['brand'];
        $gname=$_GET['gname'];
        $num=$_GET['num'];
        $price=$_GET['price'];
         $data['gid']=$id;
         $data['brand']=$brand;
         $data['goodsname']=$gname;
         $data['num']=$num;
         $data['price']=$price;
         $data['addtime']=date("Y-m-d H:i:s",time());
         $data['uid']=Session::get('userid');
        // echo "<pre>";
        // print_r($data);
        // die;
         $a=db('cart')->insert($data);
         if($a>0){
            Session::set("size",null);
            Session::set("cid",null);
             return "添加成功";
            
         }else{
            return "添加失败";
            
         }
        
     }
    }




    public function buy(){
     $username=Session::get('username');
     // halt(Session::get('userid'));
    if (!isset($username)) {
        return 0;
     }else{

        if (Session::get("cid")==null) {
            return 1;
            
        }
        if (Session::get("size")==null) {
              // halt($images);
              return 2;
              
        }
        
        //获取颜色信息
        $cid=Session::get("cid");
        $images=db("image")->find($cid);
        //获取该商品该颜色的库存
        $kucun=$images['kucun'];
        if ($kucun < $_GET['num']) {
            return 3;
            // die;
        }
        $color=$images['color'];
         $data['color']=$color;

        $image=$images['image'];
         $data['img']=$image; 
        // halt($images);
         //获取尺码信息
        $size=Session::get("size");
        $data['size']=$size;   
        //获取商品信息
        $id=$_GET['id'];

        $brand=$_GET['brand'];
        $gname=$_GET['name'];
        $num=$_GET['num'];
        $price=$_GET['price'];
        // return $gname;
        // die;
         $data['gid']=$id;
         $data['brand']=$brand;
         $data['goodsname']=$gname;
         $data['num']=$num;
         $data['price']=$price;
         $data['addtime']=date("Y-m-d H:i:s",time());
         $data['uid']=Session::get('userid');
        // echo "<pre>";
        // print_r($data);
        // die;
         $key="";
         $info=implode("@", $data);
         foreach ($data as $k => $v) {
             $key.=$k."@";
         }
         $all=$key."=".$info;
         return $all;
         
        
     }
    }
    public function goods_list()
    {   
        //无限遍历
        $one=db('type')->where('num',0)->select();
        $two=db('type')->where('num',1)->select();
        $three=db('type')->where('num',2)->select();        
        // halt($ls);
        $this->assign('one',$one);
        $this->assign('two',$two);
        $this->assign('three',$three);


        $data=$_GET['data'];
        // echo $data;
        $info=explode("@", $data);
        $key=['brand','price','caizhi'];
        $arr=array();
        
        for ($i=0; $i <count($info) ; $i++) { 
            $arr[$key[$i]]=$info[$i];
        }
        
        if ($arr['price']!=null && $arr['brand']!=null && $arr['caizhi']!=null) {
             $price=explode("-", $arr['price']);
             $goods=db('goods')->where("price",">",$price[0])->where("price","<",$price[1])->where("brand",$arr['brand'])->where("caizhi",$arr['caizhi'])->where("state",1)->select();
            $goodsall=db("goods")->where("price",">",$price[0])->where("price","<",$price[1])->where("brand",$arr['brand'])->where("caizhi",$arr['caizhi'])->where("state",1)->paginate(20);
        }elseif ($arr['brand']!=null && $arr['caizhi']!=null) {
             
             $goods=db('goods')->where("brand",$arr['brand'])->where("caizhi",$arr['caizhi'])->where("state",1)->select();
            $goodsall=db("goods")->where("brand",$arr['brand'])->where("caizhi",$arr['caizhi'])->where("state",1)->paginate(20);
        }elseif ($arr['price']!=null && $arr['caizhi']!=null) {
             $price=explode("-", $arr['price']);
             $goods=db('goods')->where("price",">",$price[0])->where("price","<",$price[1])->where("caizhi",$arr['caizhi'])->where("state",1)->select();
            $goodsall=db("goods")->where("price",">",$price[0])->where("price","<",$price[1])->where("caizhi",$arr['caizhi'])->where("state",1)->paginate(20);
        }elseif ($arr['price']!=null && $arr['brand']!=null) {

             $price=explode("-", $arr['price']);
            //  print_r($price);
            // die;
             $goods=db('goods')->where("price",">",$price[0])->where("price","<",$price[1])->where("brand",$arr['brand'])->where("state",1)->select();
            $goodsall=db("goods")->where("price",">",$price[0])->where("price","<",$price[1])->where("brand",$arr['brand'])->where("state",1)->paginate(20);
        }elseif ($arr['price']!=null) {
             $price=explode("-", $arr['price']);
             $goods=db('goods')->where("price",">",$price[0])->where("price","<",$price[1])->where("state",1)->select();
            $goodsall=db("goods")->where("price",">",$price[0])->where("price","<",$price[1])->where("state",1)->paginate(20);
        }elseif ($arr['brand']!=null) {
            $goods=db('goods')->where("brand",$arr['brand'])->where("state",1)->select();
            $goodsall=db("goods")->where("brand",$arr['brand'])->where("state",1)->paginate(20);
        }elseif ($arr['caizhi']!=null) {
             $price=explode("-", $arr['price']);
             $goods=db('goods')->where("caizhi",$arr['caizhi'])->where("state",1)->select();
            $goodsall=db("goods")->where("caizhi",$arr['caizhi'])->where("state",1)->paginate(20);
        }
    
        $this->assign('goodsall',$goodsall);
        $num=count($goods);
        $this->assign('num',$num);

        //判断是否登录
        $list=Session::get('username');
        $this->assign("list",$list);

        //搜索下面的小类别
        $stype=db("type")->order('num desc')->limit(5)->select();
        $this->assign('stype',$stype);

        //热销
         $hot=db('goods')->where("state",1)->order('sell desc')->limit(4)->select();
        // halt($a);
        $this->assign('hot',$hot);

        //渲染列表页面
        return $this->fetch("shop/list_goods");
        
    }
    public function list_goods()
    {
        //无限遍历
        $one=db('type')->where('num',0)->select();
        $two=db('type')->where('num',1)->select();
        $three=db('type')->where('num',2)->select();        
        // halt($ls);
        $this->assign('one',$one);
        $this->assign('two',$two);
        $this->assign('three',$three);

        //商品遍历
        if (isset($_GET['stype'])) {
          $goods=db('goods')->where("state",1)->where("type",$_GET['stype'])->select();
           $goodsall=db("goods")->where("state",1)->where("type",$_GET['stype'])->paginate(20);
        }elseif(isset($_GET['sousuo'])){
           $goods=db('goods')->where("state",1)->where("type",'like','%'.$_GET['sousuo'].'%')->select();
           $goodsall=db("goods")->where("state",1)->where("type",'like','%'.$_GET['sousuo'].'%')->paginate(20);
            
        }elseif(isset($_GET['xinpin'])){
            $goods=db('goods')->where("state",1)->order('id desc')->select();
            $goodsall=db("goods")->where("state",1)->order('id desc')->paginate(20);

        }elseif(isset($_GET['dijia'])){
            $goods=db('goods')->where("state",1)->where("price","<",200)->select();
            $goodsall=db("goods")->where("state",1)->where("price","<",200)->paginate(20);

        }elseif(isset($_GET['hot'])){
            $goods=db("goods")->where("state",1)->order('sell desc')->select();
            $goodsall=db("goods")->where("state",1)->order('sell desc')->paginate(20);

        }elseif(isset($_GET['brand'])){
            Session::set('brand',$_GET['brand']);
            $goods=db('goods')->where("state",1)->where("brand",$_GET['brand'])->select();
            $goodsall=db("goods")->where("state",1)->where("brand",$_GET['brand'])->paginate(20);

        }elseif(isset($_GET['xiaoliang'])){
            $goods=db('goods')->where("state",1)->order('sell desc')->select();
            $goodsall=db("goods")->where("state",1)->order('sell desc')->paginate(20);

        }elseif(isset($_GET['jiage'])){
            $goods=db('goods')->where("state",1)->order('price asc')->select();
            $goodsall=db("goods")->where("state",1)->order('price asc')->paginate(20);

        }else{
            $goods=db('goods')->where("state",1)->select();
            $goodsall=db("goods")->where("state",1)->paginate(20);
        }
        
        

        $this->assign('goodsall',$goodsall);
        $num=count($goods);
        $this->assign('num',$num);

        //判断是否登录
        $list=Session::get('username');
        $this->assign("list",$list);

        //搜索下面的小类别
        $stype=db("type")->order('num desc')->limit(5)->select();
        $this->assign('stype',$stype);

        //热销
         $hot=db('goods')->where("state",1)->order('sell desc')->limit(4)->select();
        // halt($a);
        $this->assign('hot',$hot);

        //渲染列表页面
        return $this->fetch("shop/list_goods");
    }

    public function goods_detail()
    {   
        //获取当前url地址，并存到session中  
        $url=request()->url(true);
         session('url',$url);
        
         //获取商品id
        $id=input('id');
        $uid=Session::get("userid");
        //获取商品id存入我的足迹
        $zuji=db("zuji")->where("uid",$uid)->where("gid",$id)->select();
        // halt($zuji);
        if (count($zuji)>0) {
           $info['addtime']=time();
           $info['time']=date("Y-m-d H:i:s",time());
           db("zuji")->where("uid",$uid)->where("gid",$id)->update($info);
        }else{
            $info['gid']=$id;
            $info['uid']=$uid;
            $info['addtime']=time();
            $info['time']=date("Y-m-d H:i:s",time());
            db("zuji")->insert($info);
        }
        
        //查询商品信息
        $a=db('goods')->where("state",1)->where('id',$id)->select();
       foreach ($a as $value) {
            if (!empty($value['size'])) {
                $size=explode(
                "&",$value['size'] );
                $this->assign('size',$size);
            }
        //获取收藏量
        $like=db("like")->where("gid",$id)->select();
        $likenum=count($like);
        $this->assign('likenum',$likenum);

        //获取6个销量最好的本类商品
        $nice=db("goods")->where("state",1)->where("type",$a[0]['type'])->order('sell desc')->limit(2)->select();
        // echo "<pre>";
        // print_r($nice);
        // die;
        $this->assign('nice',$nice);
        $uid=Session::get("userid");
        $q=db("like")->where("uid",$uid)->where("gid",$id)->select();
          if ($q==null) {
                $this->assign('like',"收藏");
            }else{
                $this->assign('like',"取消收藏");
            }  
            
        }

        
        
        $this->assign('a',$a);
         $b=db('image')->where('gid',$id)->select();
        // halt($a);
        $this->assign('b',$b);
        //热销
         $hot=db('goods')->where("state",1)->order('sell desc')->limit(4)->select();
        // halt($a);
        $this->assign('hot',$hot);
        // foreach ($b as $value) {
        //    echo $value['image'];
        // }
        // die;
        // halt($b);

        
        $list=Session::get('username');
        // halt($list);
        $this->assign("list",$list);
        // $img=Db::table('image')
        // ->alias('a')
        // ->join('goods s','a.goods_id=s.id')
        // ->select();
        // halt($img);
        //详情评价遍历
        // $n=db('orders')->select();
        // $ornumber=$n[0]['ornumber'];
        $pingjia=db("pingjia")->where('gid',$id)->select();
        $this->assign('pingjia',$pingjia);
        
    	return $this->fetch("shop/goods_detail");
    }
    //添加收藏
    public function like()
    {   
        $username=Session::get('username');
        $uid=Session::get('userid');
        // return $uid;
     if (!isset($username)) {
         
        return 0;
     }else{
        //获取商品id
         $gid=$_POST['id'];
         $addtime=date("Y-m-d H:i:s",time());
         //获取用户id跟用户名
        // return $gid;
         $data['gid']=$gid;
         $goods=db("goods")->where("state",1)->find($gid);
         $data['goodsname']=$goods['name'];
         $data['price']=$goods['price'];
         $data['img']=$goods['img'];
         $data['state']=$goods['state'];
         // return $uid;
         $data['uid']=$uid;
         $data['addtime']=$addtime;
         // halt($data['uid']);
         //查询该用户是否已经收藏
         $q=db("like")->where("uid",$uid)->where("gid",$gid)->select();
         if ($q==null) {
             $a=db('like')->insert($data);
            if($a>0){
                 return "收藏成功";
                 
             }else{
                 
                 return "收藏失败";
             }
         }else{
            $q=db("like")->where("uid",$uid)->where("gid",$gid)->delete();
            if($q>0){
                 return "取消成功";
                 
             }else{
                 
                 return "取消失败";
             }
         }
         
         
         // return $this->fetch('car/cart');
     }


    }
    //点击颜色框
    public function col()
    {   
        
        if (!isset($_GET['cid'])) {
            return "请选择颜色";
            
        }else{
        //image表中的id    
        Session::set("cid",$_GET['cid']);
        $id=$_GET['cid'];
        $data=db("image")->find($id);
        $kucun=$data['kucun'];
          return $kucun;         
        }

    }
    //点击尺码框
    public function size()
    {   
        
      if (!isset($_GET['size'])) {
            return "请选择尺码";
            
        }else{
        //image表中的id
        // Session::set("size",null);

        Session::set("size",$_GET['size']);
            // return 1;                
        } 

    }

    public function brand()
    {   
        $val=array();
        if (isset($_GET['type'])) {
            Session::set("type",$_GET['type']);
            if ($_GET['type']=="清空选项") {
            Session::set("type",null);
        }
        }
       $val['type']=Session::get("type");
        
        if (isset($_GET['price'])) {
            Session::set("price",$_GET['price']);
            if ($_GET['price']=='清空选项') {
            Session::set("price",null);
            }
        }
        $val['price']=Session::get("price");

        if (isset($_GET['caizhi'])) {
            Session::set("caizhi",$_GET['caizhi']);
            if ($_GET['caizhi']=='清空选项') {
            Session::set("caizhi",null);
            }
        }
        $val['caizhi']=Session::get("caizhi");
        $data=implode("@", $val);
       return $data;


        
    }
}
