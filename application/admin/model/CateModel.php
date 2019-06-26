<?php
namespace app\admin\model;
use think\Model;
class CateModel extends Model{//对应数据库中的表xp_cate
	protected $_auto=array(
		array('path','tclm',3,'callback'),
	);
	function tclm(){
		$pid=isset($_POST['pid'])?(int)$_POST['pid']:0;
		echo ($pid);
	if($pid==0){
		$data=0;
	}else{
		$list=$this->where("id=$pid")->find();
		$data=$list['path'].'-'.$list['id'];//子类的path为父类的path加上父类的id
	}
	return $data;
	}
}
?>