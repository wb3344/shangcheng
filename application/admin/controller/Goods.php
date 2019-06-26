<?php
namespace app\admin\Controller;

use think\Controller;
use think\Image;
use think\Db;

class Goods extends Controller
{     
      //商品列表页
	    public function goods_list()
    	{
        $list=db("goods")->order('id','desc')->paginate(5);
        $list1=db("goods")->select();
        $this->assign('list',$list);
        $num=count($list1);
        $this->assign("num",$num);
      	return $this->fetch();
   		}

      //商品颜色列表页
      public function goods_color()
      { 

        $list=db("image")->select();
        $this->assign("num",count($list));
         $list1=db("image")->order('gid','desc')->paginate(5);
        // $this->assign("list",$list);
        $this->assign("list1",$list1);
       

        return $this->fetch();
      }

      //商品图片搜索
      public function goods_color1()
      { 
        // $where=$_GET['where'];
        if (isset($_GET['where'])) {
          session('where',$_GET['where']);
        }
        
        // halt($where);
           
        $list1=db("image")->where('gname','like','%'.session("where").'%')->order('id','desc')->paginate(5);

        $list=db("image")->where('gname','like','%'.session("where").'%')->select();
        $this->assign('list1',$list1);
        // $this->assign('list',$list);
        $num=count($list);
        $this->assign("num",$num);
        return $this->fetch();
      }
      //搜索页
      public function goods_list1()
      {
        // $where=$_GET['where'];
        if (isset($_GET['where'])) {
          session('where',$_GET['where']);
        }
        
        // halt($where);
           
        $list=db("goods")->where('key','like','%'.session("where").'%')->order('id','desc')->paginate(5);

        $list1=db("goods")->where('key','like','%'.session("where").'%')->select();
        $this->assign('list',$list);
        $num=count($list1);
        $this->assign("num",$num);
        return $this->fetch();
      }

      //商品添加页
   		public function goods_add()
    	{    
          $list=db("type")->where('num',2)->select();
          $this->assign('list',$list);
          $color=db('color')->select();
          $this->assign('color',$color);

        	return $this->fetch();
   		}

      //执行商品添加
      public function insert()
      {    
          $file=request()->file("img");
          $name=input('name');
          $detail=input('detail');
          $caizhi=input('caizhi');
          $type=ltrim(input('type'),"-");
          // $type=$_POST['type'];
          // halt($type);
          $content=input('content');
          $price=input('price');
          $brand=input('brand');
          $key=$name."-".$type."-".$content;
          $color=input("color");
          $size=input("size");
          $allcolor="";
          $allsize="";
          if (!empty($color)) {
            foreach ($color as $v) {
            $allcolor.=$v."&";
            }
          }
          if (!empty($size)) {
            foreach ($size as $v) {
            $allsize.=$v."&";
            }
          }
          
          
         
          //将图片移动到框架应用根目录public/uploads/ 目录下
          $info=$file->rule("uniqid")->move("goods_uploads");
          // dump($info);
          if ($info) {
            $im=\think\Image::open("goods_uploads/".$info->getFilename());
            //上传成功后对图片进行等比例缩放
            $im->thumb(200,200)->save("goods_uploads/s_".$info->getFilename());
            // echo "后缀名:".$info->getExtension()."<br/>";
            // echo "带日期文件:".$info->getSaveName()."<br/>";
            $data['img']=$info->getFilename();
            $data['price']=$price;
            $data['brand']=$brand;
            $data['name']=$name;
            $data['type']=$type;
            $data['content']=$content;
            $data['detail']=$detail;
            $data['caizhi']=$caizhi;
            $data['addtime']=date("Y-m-d H:i:s",time());
            $data['key']=$key;
            $data['color']=rtrim($allcolor,"&");
            $data['size']=rtrim($allsize,"&");

            $m=db('goods')->insert($data);
            //获取本次添加的主键值
           $id = Db::name('goods')->getLastInsID();
           
            if (!empty($color)) {
              foreach ($color as $v) {
              $img['color']=$v;
               $img['gid']=$id;
               $img['gname']=$name;
               $img['addtime']=date("Y-m-d H:i:s",time());
               $n=db('image')->insert($img);

           }
            }
            
          
           // echo $id;
           // die;
            if ($m>0) {
              $this->redirect("Goods/goods_list");
            }else{
              $this->error("文件格式不正确");
            }
          }
          // return $this->fetch("Goods/goods_list");
      }
   
      //修改商品页
   		public function goods_edit()
    	{   


        $type=db("type")->where('num',2)->select();
          $this->assign('type',$type);
          $color=db('color')->select();
          $this->assign('color',$color);

          
          $id=$_GET['id'];
          // halt($id);
          $list=db("goods")->find($id);
          $this->assign('list',$list);


        	return $this->fetch();
   		}

      //执行修改
      public function update()
      { 
        $file=request()->file("img");
        $info=$file->rule("uniqid")->move("goods_uploads");
        if ($info) {
            //修改时，将原来的商品图片也删除
            $img=input('img');
            $path="goods_uploads/".$img;
            $path1="goods_uploads/s_".$img;
            // return $path;
            if (file_exists($path)) {

            unlink($path);//删除文件

            };

            if(file_exists($path1)){
              unlink($path1);//删除文件
            };

            $im=\think\Image::open("goods_uploads/".$info->getFilename());
            //上传成功后对图片进行等比例缩放
            $im->thumb(200,200)->save("goods_uploads/s_".$info->getFilename());
            // echo "后缀名:".$info->getExtension()."<br/>";
            // echo "带日期文件:".$info->getSaveName()."<br/>";
            $data['img']=$info->getFilename();
            // $data['id']=input('id');
            $data['name']=input('name');
            $data['type']=ltrim(input('type'),"-");

            $data['content']=input('content');
            $data['price']=input('price');
            $data['detail']=input('detail');
            $data['addtime']=$data['addtime']=date("Y-m-d H:i:s",time());
          //颜色，尺码拆分
          $color=input("color");
          $size=input("size");
          $allcolor="";
          $allsize="";
          if (!empty($color)) {
            foreach ($color as $v) {
            $allcolor.=$v."&";
            }
          }
          if (!empty($size)) {
            foreach ($size as $v) {
            $allsize.=$v."&";
            }
          }
           $data['color']=rtrim($allcolor,"&");
          $data['size']=rtrim($allsize,"&");
            $m=db("goods")->where('id',input('id'))->update($data);
            if($m>0){
              // 修改之后删除image中gid为id的数据，重新添加
               $gid=input('id');
               $n=db("image")->where('gid',$gid)->delete();
               // print_r(input('color'));
               
               if (!empty($color)) {
                 foreach ($color as $v) {
                 $aaa['color']=$v;
                 $aaa['gid']=$gid;
                 $aaa['gname']=input('name');
                 $aaa['addtime']=date("Y-m-d H:i:s",time());
                 $n=db('image')->insert($aaa);

           }
               }
              // die; 
            // $this->success("修改成功","Classify/cate"); 
            $this->redirect("Goods/goods_list");
            }else{
              $this->error("修改失败");
            }
          }
        
      }

      //自行删除
      public function del(){
      // halt(input('id'));
      $list=db("goods")->where("id",input('id'))->find();
      // halt($list);
      $img=$list['img'];
      $id=$list['id'];
      $path="goods_uploads/".$img;
      $path1="goods_uploads/s_".$img;
      // return $path;
      if (file_exists($path)) {

            unlink($path);//删除文件

        };

        if(file_exists($path1)){
          unlink($path1);//删除文件
        };

      $m=db("goods")->where('id',input('id'))->delete();
      if ($m>0) {
        db("image")->where('gid',$id)->delete();
        
          $this->redirect("Goods/goods_list");
       
        
      }
      
    
  }

  //商品详情页
   public function goods_detail()
       {
        $gid=$_GET['gid'];
        $list=db("goods")->find($gid);
        $this->assign("list",$list);
        $color=explode("&",$list['color']);
        $this->assign("color",$color);
        $size=explode("&",$list['size']);
        $this->assign("size",$size);
        // print_r($color);
        // die;
        return $this->fetch();
   }
 
  //添加图片页面
   public function goods_addimg()
     {  
        $gid=$_GET['gid'];
        $gname=$_GET['gname'];
        $color=$_GET['color'];
        $id=$_GET['id'];
        // halt($gname);
        $this->assign("gname",$gname);
        $this->assign("color",$color);
        $this->assign("gid",$gid);
        $this->assign("id",$id);
        return $this->fetch();
     }

  //执行商品图片添加
  public function doaddimg()
  {
          $file=request()->file("img");
          $color=input("color");
          //将图片移动到框架应用根目录public/uploads/ 目录下
          $info=$file->rule("uniqid")->move("goods_uploads");
          // dump($info);
          if ($info) {
            $im=\think\Image::open("goods_uploads/".$info->getFilename());
            //上传成功后对图片进行等比例缩放
            $im->thumb(50,50)->save("goods_uploads/c_".$info->getFilename());
            // echo "后缀名:".$info->getExtension()."<br/>";
            // echo "带日期文件:".$info->getSaveName()."<br/>";
            //获取图片名字
            $data['image']=$info->getFilename();
            $data['addtime']=date("Y-m-d H:i:s",time());
             $m=db("image")->where('id',input('id'))->update($data);
            if ($m>0) {
              $this->redirect("Goods/goods_color");
            }else{
              $this->error("文件格式不正确");
            }
          }
          // return $this->fetch("Goods/goods_list");
  }

  //商品颜色图片修改页面
  public function img_edit()
  { 
    $id=$_GET['id'];
    $gid=$_GET['gid'];
    $color=$_GET['color'];
    $gname=$_GET['gname'];
    $kucun=$_GET['kucun'];
    $img=$_GET['img'];
    // echo $id;
    // die;
    $this->assign("id",$id);
    $this->assign("color",$color);
    $this->assign("gname",$gname);
    $this->assign("img",$img);
    $this->assign("gid",$gid);
    $this->assign("kucun",$kucun);
    return $this->fetch();
  }

  //执行商品图片修改
  public function doimgedit()
  {
    
    $file=request()->file("img");

    if (!isset($file)) {
          $kucun=input('kucun');
          $gid=input('gid');
          $id=input('id');
          $k=db('image')->where('gid',$gid)->select();
          $o=db('image')->where('id',$id)->select();
        // halt($o);
          //$i为原有的库存
          $i=$o['0']['kucun'];  
          // halt($i); 
              $ck=0;
              foreach ($k as $value) {
                $ck+=$value['kucun'];
              }
              $l=$ck-$i+$kucun;
                // halt($ck);
              
          if ($l>0) {
            // echo $kucun;
            $t['state']=1;
            // $t['addtime']=date("Y-m-d H:i:s",time());
            $m=db("goods")->where('id',$gid)->update($t);
            // print_r($t);
          }else{
              
                // halt($ck);
                $t['state']=2;
                $t['addtime']=date("Y-m-d H:i:s",time());
                $m=db("goods")->where('id',$gid)->update($t);
              
            
          }
          // die;
          $data['kucun']=$kucun;
          $data['addtime']=date("Y-m-d H:i:s",time());
          $m=db("image")->where('id',input('id'))->update($data);
           if ($m>0) {
                  $this->redirect("Goods/goods_color");
                }else{
                  $this->error("修改失败");
                }
    }else{
              $kucun=input('kucun');
              $gid=input('gid');
              $id=input('id');
          $k=db('image')->where('gid',$gid)->select();
          $o=db('image')->where('id',$id)->select();
        // halt($o);
          //$i为原有的库存
          $i=$o['0']['kucun'];  
          // halt($i); 
              $ck=0;
              foreach ($k as $value) {
                $ck+=$value['kucun'];
              }
              $l=$ck-$i+$kucun;
                // halt($ck);
              
          if ($l>0) {
            // echo $kucun;
            $t['state']=1;
            // $t['addtime']=date("Y-m-d H:i:s",time());
            $m=db("goods")->where('id',$gid)->update($t);
            // print_r($t);
          }else{
              
                // halt($ck);
                $t['state']=2;
                // $t['addtime']=date("Y-m-d H:i:s",time());
                $m=db("goods")->where('id',$gid)->update($t);
              
            
          }     
         
    $id=input('id');
    // $list=db('image')->find($id);
    // $img=$list['image'];
    if (!empty(input('img'))) {
            //获取文件信息
            $file=request()->file("img");
            //将图片移动到框架应用根目录public/uploads/ 目录下
            $info=$file->rule("uniqid")->move("goods_uploads");
            if ($info) {

                //修改时，将原来的商品图片也删除
                $img=input('img');
                
                $path="goods_uploads/".$img;
                $path1="goods_uploads/c_".$img;
                // return $path;
                if (file_exists($path)) {

                unlink($path);//删除文件

                };

                if(file_exists($path1)){
                  unlink($path1);//删除文件
                };
                $im=\think\Image::open("goods_uploads/".$info->getFilename());
                //上传成功后对图片进行等比例缩放
                $im->thumb(50,50)->save("goods_uploads/c_".$info->getFilename());
                // echo "后缀名:".$info->getExtension()."<br/>";
                // echo "带日期文件:".$info->getSaveName()."<br/>";
                //获取图片名字
                $data['image']=$info->getFilename();
                $data['kucun']=$kucun;
                $data['addtime']=date("Y-m-d H:i:s",time());
                 $m=db("image")->where('id',input('id'))->update($data);

 
                if ($m>0) {
                  $this->redirect("Goods/goods_color");
                }else{
                  $this->error("文件格式不正确");
                }
              }
    }else{
        //获取文件信息
        $file=request()->file("img");
        //将图片移动到框架应用根目录public/uploads/ 目录下
        $info=$file->rule("uniqid")->move("goods_uploads");
        if ($info) {

                //修改时，本来没有图，所以不用删除
                $img=input('img');
               
                $im=\think\Image::open("goods_uploads/".$info->getFilename());
                //上传成功后对图片进行等比例缩放
                $im->thumb(50,50)->save("goods_uploads/c_".$info->getFilename());
                // echo "后缀名:".$info->getExtension()."<br/>";
                // echo "带日期文件:".$info->getSaveName()."<br/>";
                //获取图片名字
                $data['image']=$info->getFilename();
                $data['kucun']=$kucun;
                $data['addtime']=date("Y-m-d H:i:s",time());
                 $m=db("image")->where('id',input('id'))->update($data);
                  
                if ($m>0) {
                  $this->redirect("Goods/goods_color");
                }else{
                  $this->error("文件格式不正确");
                }
              }        
    }
    
    }
    
  }


  //执行删除商品图片
  public function doimgdel()
  {
    $id=input('id');
    // echo $id;
    // die;
    $list=db('image')->find($id);
    $gid=$list['gid'];
    $color=$list['color'];
     // halt($list);
    if ($list['image']!==null) {
       $img=$list['image'];
      $path="goods_uploads/".$img;
      $path1="goods_uploads/c_".$img;
      // return $path;
      if (file_exists($path)) {

            unlink($path);//删除文件

        };

        if(file_exists($path1)){
          unlink($path1);//删除文件
        };
    }
     
      $m=db("image")->where('id',input('id'))->delete();

      $this->redirect("Goods/goods_color");
    
  }

}
