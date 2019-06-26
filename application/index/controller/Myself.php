<?php 
namespace app\index\Controller;

use think\Controller;
use think\facade\Session;
use think\Db;
class Myself extends Controller
{
	public function uc_account(){
		$list=Session::get("username");
        $arr=db("user")->where('username',$list)->find();
        // halt($list);
        $this->assign('list',$list);
        //halt($this);
        $this->assign('arr',$arr);
		return $this->fetch("myself/uc_account");
    }

    //修改个人资料方法
    public function update(){
        if (request()->file("pic")) {
        $file=request()->file("pic");
        $info=$file->rule("uniqid")->move('uploads/');
    if($info){
        // echo "文件名:".$info->getFilename();
        $im=\think\Image::open("uploads/".$info->getFilename());
        //上传成功后对图片进行等比例缩放
        $im->thumb(123,123)->save("uploads/s_".$info->getFilename());
        $data['image']=$info->getFilename();
      }
  }
    	$username=input('username');
    	$data['name']=input('name');
    	$data['birthday']=input('birthday');
    	$data['sex']=input('sex');
    	$data['phone']=input('phone');
    	$data['address']=input('address');
    	$data['email']=input('email');
    	// $data['image']=input('image');
    	$data['addtime']=date("Y-m-d H:i:s",time());
    	$m=db("user")->where('username',$username)->update($data);
    	if($m>0){
    		return $this->success("修改成功","Myself/uc_account");
    	}else{
    		return $this->success("修改失败","Myself/uc_account");
    	}

    }
	public function uc_fav(){
        $user=Session::get('username');
        // halt($user);
        $this->assign("user",$user);
        $uid=Session::get("userid");
        $list=db("like")->where("uid",$uid)->paginate(5);
        $this->assign("list",$list);
		return $this->fetch();
	}
    public function dofavdel()
    {
        $id=$_POST['id'];
        $m=db("like")->where("id",$id)->delete();
        return $m;
    }
	public function uc_fav_shop(){
		return $this->fetch("myself/uc_fav_shop");
	}

    //查询遍历历史记录
	public function uc_footprint(){
        $list=Session::get("username");
        $this->assign("list",$list);
        $uid=Session::get("userid");
        $all=db("zuji")->where("uid",$uid)->select();
       
        foreach ($all as $v) {
           $time=time();
           $cha=$time-$v['addtime'];
            if ($cha>604800) {
                 db("zuji")->where("uid",$uid)->where("id",$v['id'])->delete();
            }
       }
       $list=Session::get('username');
        $this->assign("list",$list);
        $zuji=Db::field('g.name,g.type,g.price,g.content,g.caizhi,g.img,g.id,z.addtime,z.time,g.state')//截取表s的name列 和表a的全部
        ->table(['goods'=>'g','zuji'=>'z'])
        ->where('g.id=z.gid')//查询条件语句
        ->where('g.state',1)
        ->where("z.uid",$uid)
        ->select();
        // halt($zuji);
        $this->assign("zuji",$zuji);
        $time=time();
        $this->assign("time",$time);

		return $this->fetch("myself/uc_footprint");
	}

    //执行足迹的删除
    public function delzuji()
    {

        $uid=Session::get("userid");
        if (isset($_GET['state'])) {
            switch ($_GET['state']) {
                case '1': //删除今天的足迹
                        $all=db("zuji")->where("uid",$uid)->select();
                        foreach ($all as $v) {
                           $time=time();
                           $cha=$time-$v['addtime'];
                            if ($cha<86400) {
                                 $a=db("zuji")->where("uid",$uid)->where("id",$v['id'])->delete();
                             }

                              
                       }
                       if ($a>0) {
                                    return 1;
                                 }else{
                                    return 2;

                                 }
                    break;
                case '2': //删除昨天的足迹
                        $all=db("zuji")->where("uid",$uid)->select();
                        foreach ($all as $v) {
                           $time=time();
                           $cha=$time-$v['addtime'];
                            if ($cha>86400 && $cha<172800) {
                                 $a=db("zuji")->where("uid",$uid)->where("id",$v['id'])->delete();
                                 
                            }
                       }
                       if ($a>0) {
                                    return 1;
                                 }else{
                                    return 2;

                                 }
                    break;
                
                case '3':
                        //删除更早的足迹
                        $all=db("zuji")->where("uid",$uid)->select();
                        foreach ($all as $v) {
                           $time=time();
                           $cha=$time-$v['addtime'];
                            if ($cha>172800) {
                                 $a=db("zuji")->where("uid",$uid)->where("id",$v['id'])->delete();
                                 
                            }
                       }
                       if ($a>0) {
                                    return 1;
                                 }else{
                                    return 2;

                                 }
                    break;

                case '4':
                        //删除全部的足迹
                        
                         $a=db("zuji")->where("uid",$uid)->delete();
                         if ($a>0) {
                            return 1;
                         }else{
                            return 2;

                         }
                           
                       
                    break;
            }
        }
    }
}


 ?>