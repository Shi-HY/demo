<?php
namespace Admin\Controller;

use Admin\Common\common\BaseController;
use Admin\Common\common\Prompt;
use Admin\Common\common\Pdf;

class ChannelController extends BaseController {
    public function index(){
        $res = M('channel')->select();

        $data=array();
        for($i=0; $i<count($res); $i++)
        {
            $data[$i]['channel_id'] = $res[$i]['channel_id'];
            $data[$i]['channel_name'] = $res[$i]['channel_name'];
            $data[$i]['channel_tel'] = $res[$i]['channel_tel'];
            $data[$i]['channel_qq'] = $res[$i]['channel_qq'];
            $data[$i]['create_time'] = date('Y-m-d H:i',$res[$i]['create_time']);
            $data[$i]['update_time'] = date('Y-m-d H:i',$res[$i]['update_time']);

            //渠道所属门店的名称  暂时没有这个
            //$store = M('store')->where(array('channel_id'=>$res[$i]['channel_id']))->find();
            //$data[$i]['store_name'] = $store['store_name'];
        }

        $this->assign('data', $data);
    	$this->display();
    }

    public function add()
    {
        if(IS_GET)
        {
            $this->display();
        }else{
            /**
             * 添加操作
             */
            $data = I('post.');

            $act['store_id']=2;                     //暂时默认 2
            $act['channel_name']= trim($data['channel_name']);
            $act['channel_tel']= trim($data['channel_tel']);
            $act['channel_qq']= trim($data['channel_qq']);
            $act['create_time'] = $_SERVER['REQUEST_TIME'];
            $act['update_time'] = $_SERVER['REQUEST_TIME'];

            $result = M('channel')->add($act);
            if($result){

                $channel = M('channel');
                $where = "channel_id = '$result'";
                $channel->channel_href = "http://shjppn.com/Home/BackNow/index/channel_id/".$result;

                $res = $channel->where($where)->save();
                if($res)
                {
                    $response = array(
                        'status' => 'a_success',
                        'title' => Prompt::$ChannelPrompt['a_success'],
                    );
                }else{
                    $response = array(
                        'status' => 'a_err',
                        'title' => Prompt::$ChannelPrompt['a_err'],
                    );
                }

            }else{
                $response = array(
                    'status' => 'a_err',
                    'title' => Prompt::$ChannelPrompt['a_err'],
                );
            }
            return $this->ajaxReturn($response);
        }
    }

    public function edit()
    {
        if(IS_GET)
        {
            if (empty($_GET['channel_id'])) {
                $this->redirect("index");
                exit;
            }
            $channel_id = I('get.channel_id');
            $res = M('channel')->find($channel_id);

            if (empty($res)) {
                $this->redirect('index');
                exit;
            }
            $this->assign('data', $res);
            $this->display();
        }else{
            /**
             * 修改操作
             */
            $channel = M('channel');
            $data = I('post.');

            $channel_id = $data['channel_id'];
            $where = "channel_id = '$channel_id'";

            $channel->channel_name = trim($data['channel_name']);
            $channel->channel_tel = trim($data['channel_tel']);
            $channel->channel_qq = trim($data['channel_qq']);
            $channel->update_time = $_SERVER['REQUEST_TIME'];

            $result = $channel->where($where)->save();

            if($result){
                    $response = array(
                        'status' => 'u_success',
                        'title' => Prompt::$ChannelPrompt['u_success'],
                    );
            }else{
                $response = array(
                    'status' => 'u_err',
                    'title' => Prompt::$ChannelPrompt['u_err'],
                );
            }

            return $this->ajaxReturn($response);
        }
    }

    public function del()
    {
        if (empty($_GET['channel_id'])) {
            $this->redirect("index");
            exit;
        }
        $channel_id = I('get.channel_id');

        if (M('channel')->delete($channel_id)) {
            $response = array(
                'status' => 'd_success',
                'title' => Prompt::$ChannelPrompt['d_success'],
            );
        } else {
            $response = array(
                'status' => 'd_err',
                'title' => Prompt::$ChannelPrompt['d_err'],
            );
        }
        return $this->ajaxReturn($response);
    }

    public function view()
    {
        if (empty($_GET['channel_id'])) {
            $this->redirect("index");
            exit;
        }
        $channel_id = I('get.channel_id');
        $res = M('channel')->find($channel_id);

        if (empty($res)) {
            $this->redirect('index');
            exit;
        }

        //活动所属门店的名称
//        $store = M('store')->where(array('store_id'=>$res['store_id']))->find();
//        $res['store_name'] = $store['store_name'];

        $res['create_time'] = date('Y-m-d H:i',$res['create_time']);
        $res['update_time'] = date('Y-m-d H:i',$res['update_time']);

        $this->assign('data', $res);
        $this->display();
    }

    /**
     * 通过该渠道返现的用户列表
     */
    public function user()
    {
        if (empty($_GET['channel_id'])) {
            $this->redirect("index");
            exit;
        }else{
            $channel_id = I('get.channel_id');
        }

        $res = M('user')->where(array('channel_id'=>$channel_id))->select();

        if (empty($res)) {
            $this->redirect('index');
            exit;
        }

        $arr=array();
        for($i=0; $i<count($res); $i++)
        {
            $arr[$i]['user_id'] = $res[$i]['user_id'];
            $arr[$i]['user_name'] = $res[$i]['user_name'];
            $arr[$i]['user_tel'] = $res[$i]['user_tel'];
            $arr[$i]['account_number'] = $res[$i]['account_number'];
            $arr[$i]['create_time'] = date('Y-m-d H:i',$res[$i]['create_time']);
        }

        $this->assign('data', $arr);
        $this->display();
    }

    /**
     * 导出pdf
     */
    public function pdf()
    {
        $name = $_POST['name'];
        $image = $_POST['image'];

        Pdf::ExporPdf($name,$image);
    }
}