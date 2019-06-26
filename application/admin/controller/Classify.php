<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
class Classify extends Controller
{
	public function cate(){
		$list1=db("type")->select();
		$list1=count($list1);
		$this->assign('list1',$list1);

		$list=db("type")->order('id','desc')->paginate(5);
		$this->assign('list',$list);	
		return $this->fetch("Classify/cate");
	}

	public function add(){
		if (input('pid')==0) {
			$this->assign('pid','0');
			$this->assign('path','0');

			$this->assign('ptype','顶级分类');
			
		}else{
			$pid=input('pid');
			// halt($pid);
			// $pid=$_POST['pid'];
			$result=db('type')->where('id',$pid)->find();
			// halt($result);
			$ptype=$result['type'];
			$path=$result['path'].",".$pid;
			$this->assign('ptype',$ptype);
			$this->assign('pid',$pid);
			$this->assign('path',$path);
		}
		
		return $this->fetch();
	}

	// public function add1(){
	// 	$pid=$_POST['id'];

	// 	$result1=db('type')->where('id',$pid)->find();
	// 	// halt($result);
	// 	$this->assign('result1',$result1);
	// 	return $this->fetch("Classify/add");
	// }


	public function insert(){
			
			$pid=$_POST['pid'];
			$path=$_POST['path'];
			// halt($path);
			//子类的名称
			$type=$_POST['type'];
			 // $data['number']=substr_count($data['path'],",")-1;
			 $num=substr_count($path,",");
	
			$time=date("Y-m-d H:i:s",time());
			$m=db("type")->insert(['type'=>$type,'pid'=>$pid,'addtime'=>$time,'path'=>$path,'num'=>$num]);
			// $Id = Db::name('type')->getLastInsID($m);
			if($m){
				// $zpath = $path . "," . $Id;
				// $result = db("type")->where("id",$Id)->update(['path'=>$zpath]);
				
					return "添加成功";
				
			}else{
				return "添加失败";
			}
	}
	// public function insert(){
	// 	if (input('pid')==0) {
	// 		$data['id']=input('id');
	// 		$data['type']=input('type');
	// 		$data['path']=input('path');
	// 		$data['addtime']=date("Y-m-d H:i:s",time());
	// 		$this->assign('data',$data);
	// 		$m=db("type")->insert($data);

	// 		// echo $m;
	// 		if($m>0){
	// 			// $this->success("添加成功","Classify/cate");

	// 		}else{
	// 			$this->error("添加失败");
	// 		}
	// 	}else{
	// 		$path=input('path').','.input('id');
	// 		// halt($path);
	// 		$data['type']=input('type');
	// 		$data['pid']=input('id');
	// 		$data['path']=$path;
	// 		// $data['path']=input('path');
	// 		// halt($data['path']);
	// 		$data['addtime']=date("Y-m-d H:i:s",time());
	// 		$this->assign('data',$data);
	// 		$m=db('type')->insert($data);
	// 		if($m>0){
	// 			// $this->success("添加成功","Classify/cate");

	// 		}else{
	// 			$this->error("添加失败");
	// 		}
	// 	}
	// 	return $this->fetch("Classify/cate");

	// }

	public function edit(){
		if (input('pid')==0) {
			$id=input('id');
			$pid=input('pid');
			$list=db('type')->find($id);
			// $list1=db('type')->find($pid);
			$this->assign('list',$list);
			$this->assign('type','顶级分类');
		}else{
			$id=input('id');
			$pid=input('pid');
			$list=db('type')->find($id);
			$list1=db('type')->find($pid);
			$type=$list1['type'];

			$this->assign('list',$list);
			$this->assign('type',$type);
		}
		
		return $this->fetch();
	}

	public function update(){
		$data['id']=input('id');
		$data['type']=input('type');
		// dump($data['type']);
		$m=db("type")->where('id',input('id'))->update($data);
		// halt($m);
		if($m>0){
			// $this->success("修改成功","Classify/cate"); 
			$this->redirect("Classify/cate");
		}else{
			$this->error("修改失败");
		}
	}
	// public function childadd(){
	// 	// halt(input('id'));
	// 	$path=input('path').','.input('id');
	// 	// halt($path);
	// 	// halt($path);
	// 	// $data['id']=input('id');
	// 	$data['type']=input('type');
	// 	$data['pid']=input('id');
	// 	$data['path']=$path;
	// 	// $data['path']=input('path');
	// 	// halt($data['path']);
	// 	$data['addtime']=date("Y-m-d H:i:s",time());
	// 	// halt($data);
	// 	$m=db('type')->insert($data);
	// 	if($m>0){
	// 			$this->assign('list',$data);
	// 			$this->success("添加成功","Classify/childadd");
	// 			// $this->assign('data',$data);

	// 		}else{
	// 			$this->error("添加失败");
	// 		}
	// 	// $this->assign('data',$data);

	// 	// return $this->fetch();
	// }

	public function del(){
		$m=db("type")->where('id',input('id'))->delete();
		return $m;
	}

	public function seek(){
		// halt("1111");
		 	//搜索条件
		if (input('type')!==null) {
          session('type',input('type'));
        }
			$type=session('type');
			$list=db('type')
			->where('type','like','%'.$type.'%')
			->order('id','desc')->paginate(5);
			// $list=db("type")->order('id','desc')->paginate(5);
			//统计条数
			$list1=db('type')
			->where('type','like','%'.$type)
			->count();
		// halt($a);
			$this->assign('list',$list);
			$this->assign('list1',$list1);
	      	return $this->fetch("classify/cate1");
		// return $a;
		}
		
}


