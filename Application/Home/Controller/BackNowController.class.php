<?php
namespace Home\Controller;
use Think\Controller;
use Home\Common\common\Tool;

class BackNowController extends Controller {
    public function index(){
        $channel_id = I('get.channel_id');

        $this->assign('channel_id', $channel_id);
        $this->display();

    }

    public function backList(){
        $data = I('post.');

        $passport_no =Tool::getStrToMax(trim($data['passport_no']));   //护照号
        $channel_id =$data['channel_id'];           //渠道Id

        //如果渠道ID不为空 只查询出 状态等于 0
        if(!empty($channel_id))
        {
            $data =M('back_now')->where(array('passport_no'=>$passport_no,'status'=>'0'))->select();
            //返现列表
            $arr = array();
            foreach ($data as $k=>$v)
            {
                array_push($arr,$v['back_now_id']);
            }
            $back_now_id = implode(',',$arr);

            $res1 = array();
            for ($i = 0; $i < count($data); $i++) {
                $res1[$i]['back_now_id'] = $data[$i]['back_now_id'];
                $res1[$i]['passport_no'] = $data[$i]['passport_no'];
                $res1[$i]['price'] = $data[$i]['price'];
                $res1[$i]['back_now_price'] = $data[$i]['back_now_price'];
                $res1[$i]['serial_number'] = $data[$i]['serial_number'];
                $res1[$i]['status'] = $data[$i]['status'];
                $res1[$i]['username'] = $data[$i]['username'];
                $res1[$i]['store_name'] = $data[$i]['store_name'];
                $res1[$i]['consume_time'] = date('Y-m-d H:i', $data[$i]['consume_time']);
                $res1[$i]['create_time'] = date('Y-m-d H:i', $data[$i]['create_time']);
                $res1[$i]['update_time'] = date('Y-m-d H:i', $data[$i]['update_time']);
            }
            /*渠道信息*/
            $channel_data = M('channel')->where(array('channel_id'=>$channel_id))->find();
            $channel['channel_tel'] = $channel_data['channel_tel'];
            $channel['channel_qq'] = $channel_data['channel_qq'];

            $this->assign('channel', $channel);
            $this->assign('res1', $res1);
            $this->assign('back_now_id', $back_now_id);
            $this->assign('channel_id', $channel_id);
        }else{
            $where = "passport_no = '$passport_no' AND status != 0";
            $data =M('back_now')->where($where)->select();
            //查询列表

            $res2 = array();
            for ($i = 0; $i < count($data); $i++) {
                $res2[$i]['back_now_id'] = $data[$i]['back_now_id'];
                $res2[$i]['passport_no'] = $data[$i]['passport_no'];
                $res2[$i]['price'] = $data[$i]['price'];
                $res2[$i]['back_now_price'] = $data[$i]['back_now_price'];
                $res2[$i]['serial_number'] = $data[$i]['serial_number'];
                $res2[$i]['status'] = $data[$i]['status'];
                $res2[$i]['username'] = $data[$i]['username'];
                $res2[$i]['store_name'] = $data[$i]['store_name'];
                $res2[$i]['consume_time'] = date('Y-m-d H:i',$data[$i]['consume_time']);
                $res2[$i]['create_time'] = date('Y-m-d H:i', $data[$i]['create_time']);
                $res2[$i]['update_time'] = date('Y-m-d H:i', $data[$i]['update_time']);

                $channel_data = M('channel')->where(array('channel_id'=>$data[$i]['channel_id']))->find();
                $channel['channel_tel'] = $channel_data['channel_tel'];
                $channel['channel_qq'] = $channel_data['channel_qq'];
            }
            $this->assign('res2', $res2);
            $this->assign('channel', $channel);
        }
        $this->display();
    }

    public function info(){
        $data = I('post.');
        $back_now_id = $data['back_now_id'];

        $channel_id = $data['channel_id'];

        $channel = M('channel')->where(array('channel_id'=>$channel_id))->find();

        $this->assign('channel_id', $channel_id);
        $this->assign('channel', $channel);
        $this->assign('back_now_id', $back_now_id);
        $this->display();
    }

    /**
     * 返现操作
     */
    public function backNow(){
        $data = I('post.');

        //如果用户名存在 就不要再添加用户信息了
        $map['user_name'] = array('eq',trim($data['user_name']));
        $map['user_tel'] = array('eq',trim($data['user_tel']));
        $map['account_number'] = array('eq',trim($data['account_number']));
        $map['channel_id'] = array('eq',$data['channel_id']);
        $user =  M('user')->where($map)->find();

        if(!empty($user))
        {
            //用户存在 就修改返现信息
            $back_now = M('back_now');
            $back_now->user_id = $user['user_id'];
            $back_now->channel_id = $data['channel_id'];
            $back_now->pay_type = $data['pay_type'];
            $back_now->status = '1';                    //审核中

            $where['back_now_id'] = array('in',$data['back_now_id']);
            $back_res = $back_now->where($where)->save();
            if($back_res)
            {
                $this->redirect('state',array('back_now_id'=>$data['back_now_id']));
            }else{
                echo '返现失败';
                //echo '返现失败1';
            }
        }else{
            //如果用户不存在  就添加
            $act['user_name'] = trim($data['user_name']);
            $act['user_tel'] = trim($data['user_tel']);
            $act['account_number'] = trim($data['account_number']);
            $act['channel_id'] = $data['channel_id'];
            $act['create_time'] = $_SERVER['REQUEST_TIME'];

            $user_res =  M('user')->add($act);

            if($user_res)
            {
                //如果用户添加成功  在修改返现信息
                $back_now = M('back_now');
                $back_now->user_id = $user_res;
                $back_now->channel_id = $data['channel_id'];
                $back_now->pay_type = $data['pay_type'];
                $back_now->status = '1';                    //审核中

                $where['back_now_id'] = array('in',$data['back_now_id']);
                $back_res = $back_now->where($where)->save();
                if($back_res)
                {
                    $this->redirect('state',array('back_now_id'=>$data['back_now_id']));
                }else{
                    echo '返现失败';
                    //echo '返现失败2';
                }
            }else{
                echo '返现失败3';
                //echo '返现失败';
            }
        }

    }

    public function state(){
        $back_now = M('back_now');
        $data = I('get.');

        $where['back_now_id'] = array('in',$data['back_now_id']);
        $res = $back_now->where($where)->select();

        $arr=array();
        for($i=0; $i<count($res); $i++)
        {
            $arr[$i]['back_now_id'] = $res[$i]['back_now_id'];
            $arr[$i]['passport_no'] = $res[$i]['passport_no'];
            $arr[$i]['price'] = $res[$i]['price'];
            $arr[$i]['serial_number'] = $res[$i]['serial_number'];
            $arr[$i]['consume_time'] = date('Y-m-d H:i',$res[$i]['consume_time']);
            $arr[$i]['status'] = $res[$i]['status'];
            $arr[$i]['create_time'] = date('Y-m-d H:i',$res[$i]['create_time']);
            $arr[$i]['update_time'] = date('Y-m-d H:i',$res[$i]['update_time']);

            $channel_data = M('channel')->where(array('channel_id'=>$res[$i]['channel_id']))->find();
            $channel['channel_tel'] = $channel_data['channel_tel'];
            $channel['channel_qq'] = $channel_data['channel_qq'];
        }

        $this->assign('data', $arr);
        $this->assign('channel', $channel);
        $this->display();
    }

}