<?php 
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\facade\Session;

class Unicode extends Controller
{
	public function unicode()
	{  
        $uid=Session::get('userid');
         $a=Db::field('o.goodsname,o.state,o.gid,o.price,o.ornumber,p.id,p.did,o.username,p.content,p.huifu,o.addtime')//截取表s的name列 和表a的全部
        ->table(['orders'=>'o','pingjia'=>'p'])
        ->where('o.id=p.did')//查询条件语句
        ->where("o.uid",$uid)
        ->where("o.state",'>',3)
        ->paginate(5);

        // $a=db('pingjia')->order('id',"desc")->paginate(5);
        // // halt($a);
        // $dingdan=db('orders')->where('state','>','3')->select();
        // $this->assign('dingdan',$dingdan);
        $this->assign('a',$a);
		return $this->fetch();
	}

    public function del(){
        // halt(input('id'));
        $dl=db('pingjia')->where('id',input('id'))->delete();
        if ($dl) {
            return 1;
        }else{
            return 0;
        }
    }

    public function edit(){
        $id=$_GET['id'];
        $list=db('pingjia')->find($id);
        // halt($list);
        // print_r($list);
        $this->assign('list',$list);
        return $this->fetch("unicode/huifu");
    }

    public function update(){
        $uid=Session::get("userid");
        $did=$_GET['did'];
        $id=$_GET['id'];
        $huifu=$_GET['huifu'];
        // halt($_GET['huifu']);

        db('orders')->where('id',$did)->data(['state'=>'8'])->update();
        db('pingjia')->where('id',$id)->data(['huifu'=>$huifu])->update();

       $a=Db::field('o.goodsname,o.state,o.gid,o.price,o.ornumber,p.id,p.did,o.username,p.content,p.huifu,o.addtime')//截取表s的name列 和表a的全部
        ->table(['orders'=>'o','pingjia'=>'p'])
        ->where('o.id=p.did')//查询条件语句
        ->where("o.uid",$uid)
        ->where("o.state",'>',3)
        ->paginate(5);
        $this->assign('a',$a);
        // $dingdan=db('orders')->where('state','>','3')->select();
        // $this->assign('dingdan',$dingdan);
        return $this->fetch("Unicode/unicode");
    }
}


 ?>