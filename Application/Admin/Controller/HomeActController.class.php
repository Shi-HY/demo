<?php
namespace Admin\Controller;
use Think\Controller;
class HomeActController extends Controller {
    public function index(){
        $res = M('activity')->where(array('is_display'=>1))->order('act_id DESC')->select();

        $data=array();
        for($i=0; $i<count($res); $i++)
        {
            $data[$i]['act_id'] = $res[$i]['act_id'];
            $data[$i]['act_title'] = $res[$i]['act_title'];
            $data[$i]['banner'] = $res[$i]['banner'];
            $data[$i]['create_time'] = date('Y-m-d H:i',$res[$i]['create_time']);
            $data[$i]['update_time'] = date('Y-m-d H:i',$res[$i]['update_time']);
        }

        $this->assign('data', $data);
        $this->display();
    }

    public function view(){
        $act_id = I('get.act_id');
        $res = M('activity')->where(array('act_id'=>$act_id))->find();

        $res['create_time'] = date('Y-m-d H:i',$res['create_time']);

        $this->assign('data', $res);
        $this->display();
    }
}