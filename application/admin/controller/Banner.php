<?php
namespace app\admin\controller;

use think\Controller;
use think\Image;

class Banner extends Controller
{
	public function banner()
	{	
		$list=db("banner")->order('id','desc')->paginate(2);
		$list1=db("banner")->select();
		$num=count($list1);
		$this->assign("num",$num);
		$this->assign('list',$list);
		return $this->fetch();
		// return $this->fetch();
	}
	public function member_add()
	{
		return $this->fetch();
	}

	public function upfile(){
		//获取要上传的文件信息
		$file=request()->file("pic");
		$des=input('des');
		$bname=input('bname');
		// dump($file);
		//将图片移动到框架应用根目录public/uploads/ 目录下
		$info=$file->rule("uniqid")->move("uploads");
		// dump($info);
		if($info){


			$im=\think\Image::open("uploads/".$info->getFilename());
			// dump($im);
			//上传成功后对图片进行等比例缩放
			$im->thumb(200,200)->save("uploads/s_".$info->getFilename());

			// echo "后缀名:".$info->getExtension()."<br/>";
			// echo "带日期文件:".$info->getSaveName()."<br/>";
			$data['image']=$info->getFilename();
			$data['des']=$des;
			$data['bname']=$bname;
			$data['addtime']=date("Y-m-d H:i:s",time());
			// $filename=$info->getFilename();
			// $addtime=date("Y-m-d H:i:s",time());
			$m=db("banner")->insert($data);
			if($m>0){
			$this->redirect("Banner/banner");
			// $this->success("添加成功");
			}else{
				$this->error("文件格式不正确失败");
			}
			// echo $addtime;
			// echo "文件名:".$info->getFilename()."<br/>";

		}

	}

	public function del(){
		$list=db("banner")->where("id",input('id'))->find();
		// halt($list);
		$img=$list['image'];
		$path="uploads/".$img;
		$path1="uploads/s_".$img;
		// return $path;
		if (file_exists($path)) {

            unlink($path);//删除文件

        };

        if(file_exists($path1)){
        	unlink($path1);//删除文件
        };
		
		$m=db("banner")->where('id',input('id'))->delete();
		return $m;
		// if($m>0){
		// 	//$this->redirect("控制器/方法)----重定向输出
		// 	$this->redirect("Banner/banner");
		// }else{
		// 	$this->error("修改失败");
		// }
	}
	// public function delall(){
	// 	$m=db("banner")->delete("id",[]);
	// }
}