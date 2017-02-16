<?php
namespace Admin\Controller;

use Admin\Common\common\BaseController;
use Admin\Common\common\Prompt;
use Admin\Common\common\Tool;
use Admin\Common\common\PHPExcel;

class BackNowController extends BaseController
{
    public function index()
    {
        $back_now = M('back_now');

        //先查询是否有过期的数据 如果有就修改为过期
        $before_day = time() - 86400;
        $where = "consume_time < '$before_day' AND status = 0";
        $status['status'] = '4';
        $back_now->where($where)->save($status);

        //如果门店Id不为空, 输出当前店员所属门店的返现信息 ,  为空就输出所有返现信息
        $store_id = $_SESSION['Admin']['store_id'];
        if (!empty($store_id)) {
            $res = M('back_now')->table('p_back_now bn')
                ->field(array('a.username' => 'username', 's.store_name' => 'store_name', 'bn.*'))
                ->join('p_admin a on a.uid=bn.uid')
                ->join('`p_store` s on s.store_id=bn.store_id')
                ->where(array('bn.store_id' => $store_id))
                ->select();
        } else {
            $res = M('back_now')->table('p_back_now bn')
                ->field(array('a.username' => 'username', 's.store_name' => 'store_name', 'bn.*'))
                ->join('p_admin a on a.uid=bn.uid')
                ->join('`p_store` s on s.store_id=bn.store_id')
                ->select();
        }

        $data = array();
        for ($i = 0; $i < count($res); $i++) {
            $data[$i]['back_now_id'] = $res[$i]['back_now_id'];
            $data[$i]['passport_no'] = $res[$i]['passport_no'];
            $data[$i]['price'] = $res[$i]['price'];
            $data[$i]['back_now_price'] = $res[$i]['back_now_price'];
            $data[$i]['serial_number'] = $res[$i]['serial_number'];
            $data[$i]['status'] = $res[$i]['status'];
            $data[$i]['username'] = $res[$i]['username'];
            $data[$i]['store_name'] = $res[$i]['store_name'];
            $data[$i]['consume_time'] = date('Y-m-d H:i',$res[$i]['consume_time']);
            $data[$i]['create_time'] = date('Y-m-d H:i', $res[$i]['create_time']);
            $data[$i]['update_time'] = date('Y-m-d H:i', $res[$i]['update_time']);

            //渠道名称
            $channel_name = M('channel')->alias('c')
                ->field('c.channel_name')
                ->join('left join p_back_now as bn ON bn.channel_id=c.channel_id')
                ->where(array('c.channel_id' => $res[$i]['channel_id']))
                ->find();

            $data[$i]['channel_name'] = $channel_name['channel_name'] ? $channel_name['channel_name'] : '';
        }
        $this->assign('data', $data);
        $this->display();
    }

    public function add()
    {
//        if(IS_GET)
//        {
//            $this->display();
//        }else{
        /**
         * 添加操作
         */
        $back_now = M('back_now');
        $data = I('post.');
        //店员Id
        $act['uid'] = $_SESSION['Admin']['uid'];

        //门店Id
        $act['store_id'] = $_SESSION['Admin']['store_id'];

        $act['passport_no'] =Tool::getStrToMax(trim($data['passport_no']));
        $act['price'] = trim($data['price']);
        $act['consume_time'] = $_SERVER['REQUEST_TIME'];
        $act['create_time'] = $_SERVER['REQUEST_TIME'];
        $act['update_time'] = $_SERVER['REQUEST_TIME'];
        //序列号 5位数
        $act['serial_number'] = Tool::getRandomString(5);
        //返现金额 总金额的3%
        $act['back_now_price'] = trim($data['price']*0.03);
        //return $this->ajaxReturn($act);

        $result = $back_now->add($act);
        if ($result) {
            $response = array(
                'status' => 'a_success',
                'title' => Prompt::$BackNowPrompt['a_success'],
            );
        } else {
            $response = array(
                'status' => 'a_err',
                'title' => Prompt::$BackNowPrompt['a_err'],
            );
        }

        return $this->ajaxReturn($response);
        //}
    }

    /**
     * 删除操作
     */
    public function del()
    {
        $back_now_id = I('get.back_now_id');

        if (M('back_now')->delete($back_now_id)) {
            $response = array(
                'status' => 'd_success',
                'title' => Prompt::$BackNowPrompt['d_success'],
            );
        } else {
            $response = array(
                'status' => 'd_err',
                'title' => Prompt::$BackNowPrompt['d_err'],
            );
        }
        return $this->ajaxReturn($response);
    }

    /**
     * 审核通过操作
     */
    public function adopt() {
        if (!empty($_GET['back_now_id'])) {
            $data['back_now_id'] = $_GET['back_now_id'];
            $data['status'] = 2;                        //等待返现
            M('back_now')->save($data);
            $this->redirect('index');
        } else {
            $this->redirect('index');
        }
    }

    /**
     * 返现操作
     */
    public function backNow() {
        $arr = I('post.');

        $data['back_now_id'] = $arr['back_now_id'];
        $data['status'] = 3;                        //已返现
        if(M('back_now')->save($data))
        {
            $response = array(
                'status' => 'bn_success',
                'title' => Prompt::$BackNowPrompt['bn_success'],
            );
        }else{
            $response = array(
                'status' => 'bn_err',
                'title' => Prompt::$BackNowPrompt['bn_err'],
            );
        }
        return $this->ajaxReturn($response);
    }

    /**
     * 失效操作
     */
    public function invalid() {
        if (!empty($_GET['back_now_id'])) {
            $back_now_id = $_GET['back_now_id'];
            $data['status'] = 5;                        //失效
            M('back_now')->where(array('back_now_id'=>$back_now_id))->save($data);

            $this->redirect('index');
        } else {
            $this->redirect('index');
        }
    }

    /**
     * 返现详情
     */
    public function view()
    {
        $back_now_id = I('get.back_now_id');
        $data = M('back_now')->table('p_back_now bn')
            ->field(array('a.username'=>'username','s.store_name'=>'store_name','u.user_name'=>'user_name','u.user_tel'=>'user_tel','ch.channel_name'=>'channel_name','bn.*'))
            ->join('p_admin a on a.uid=bn.uid')
            ->join('`p_store` s on s.store_id=bn.store_id')
            ->join('`p_user` u on u.user_id=bn.user_id')
            ->join('`p_channel` ch on ch.channel_id=bn.channel_id')
            ->where(array('bn.back_now_id'=>$back_now_id))
            ->find();

        $this->assign('data', $data);
        $this->display();
    }

    /**
     * 导出excel
     */
    public function excel()
    {
        $back_now_ids = I('post.back_now_ids');

       // $where = 'bn.status = 3 && bn.back_now_id in'." ($back_now_ids)";
        $where = 'bn.back_now_id in'." ($back_now_ids)";
        $res = M('back_now')->table('p_back_now bn')
                            ->field(array('a.username'=>'username','s.store_name'=>'store_name','u.user_name'=>'user_name','u.user_tel'=>'user_tel','ch.channel_name'=>'channel_name','bn.*'))
                            ->join('p_admin a on a.uid=bn.uid')
                            ->join('`p_store` s on s.store_id=bn.store_id')
                            ->join('`p_user` u on u.user_id=bn.user_id')
                            ->join('`p_channel` ch on ch.channel_id=bn.channel_id')
                            ->where($where)
                            ->select();

        // var_dump(M('back_now')->getlastsql());die;

        $data = array();
        for ($i = 0; $i < count($res); $i++) {
            $data[$i]['back_now_id'] = $res[$i]['back_now_id'];
            $data[$i]['serial_number'] = $res[$i]['serial_number'];
            $data[$i]['passport_no'] = $res[$i]['passport_no'];

            $data[$i]['user_name'] = $res[$i]['user_name'];
            $data[$i]['user_tel'] = $res[$i]['user_tel'];

            $data[$i]['price'] = $res[$i]['price'];
            $data[$i]['back_now_price'] = $res[$i]['back_now_price'];
            $data[$i]['consume_time'] = date('Y-m-d H:i',$res[$i]['consume_time']);

            if($res[$i]['pay_type'] == '0')
            {
                $data[$i]['pay_type'] ='银行卡';
            }elseif ($res[$i]['pay_type'] == '1')
            {
                $data[$i]['pay_type'] ='支付宝';
            }elseif ($res[$i]['pay_type'] == '2')
            {
                $data[$i]['pay_type'] ='微信';
            }

            $data[$i]['channel_name'] = $res[$i]['channel_name'] ? $res[$i]['channel_name'] : '';
            $data[$i]['username'] = $res[$i]['username'];
            $data[$i]['store_name'] = $res[$i]['store_name'];

            if($res[$i]['status'] == '0')
            {
                $data[$i]['status'] ='等待认领';
            }elseif ($res[$i]['status'] == '1')
            {
                $data[$i]['status'] ='审核中';
            }elseif ($res[$i]['status'] == '2')
            {
                $data[$i]['status'] ='等待返现';
            }elseif ($res[$i]['status'] == '3')
            {
                $data[$i]['status'] ='已返现';
            }elseif ($res[$i]['status'] == '4')
            {
                $data[$i]['status'] ='过期';
            }elseif ($res[$i]['status'] == '5')
            {
                $data[$i]['status'] ='失效';
            }
        }

        //var_dump($res);die;

        //文件名
        $filename = date('Y-m-d H:i:s').'返现报表';

        PHPExcel::ExportExcel($data,array('编号',
                                        '序列号',
                                        '护照号',
                                        '客户姓名',
                                        '客户电话',
                                        '消费金额',
                                        '返现金额',
                                        '消费时间',
                                        '返现方式',
                                        '渠道名称',
                                        '操作员',
                                        '商户',
                                        '状态',
                                    ),$filename);
    }
}