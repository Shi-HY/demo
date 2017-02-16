<?php
namespace Admin\Controller;

use Admin\Common\common\BaseController;
use Admin\Common\common\Prompt;
use Admin\Common\common\Tool;

class ActivityController extends BaseController {
    public function index(){
        $res = M('activity')->select();

        $data=array();
        for($i=0; $i<count($res); $i++)
        {
            $data[$i]['act_id'] = $res[$i]['act_id'];
            $data[$i]['act_title'] = $res[$i]['act_title'];
            $data[$i]['banner'] = $res[$i]['banner'];
            $data[$i]['link'] = $res[$i]['link'];
            $data[$i]['is_display'] = $res[$i]['is_display']==1?'YES':'NO';
            $data[$i]['create_time'] = date('Y-m-d H:i',$res[$i]['create_time']);
            $data[$i]['update_time'] = date('Y-m-d H:i',$res[$i]['update_time']);

            //活动所属门店的名称
            $store = M('store')->where(array('store_id'=>$res[$i]['store_id']))->find();
            $data[$i]['store_name'] = $store['store_name'];
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
            if(!empty($_FILES)) {
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize   =     5*1024*1024;// 设置附件上传大小
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath  =      './Upload/'; // 设置附件上传根目录
                $upload->savePath  =      'Image/'; // 设置附件上传（子）目录

                //上传文件
                $info   =   $upload->uploadOne($_FILES['myFileName']);

               // $Param = I('get.');
                if(!$info) {
                    // 上传错误提示错误信息
                    echo "error|上传失败";
                }else{
                    // 上传成功 获取上传文件信息
                    //$imgUrl="http://www.shjppn.com/Upload/".$info['savepath'].$info['savename'];
                    $imgUrl="http://localhost/PaNa/Upload/".$info['savepath'].$info['savename'];
                    echo $imgUrl;
                }
            }else{
                /**
                 * 执行数据添加操作
                 */
                $data = I('post.');

                $act=array();
                $act['store_id']=2;                     //暂时默认 2
                $act['act_title']= trim($data['act_title']);
                $act['content']=$data['editor_html'];
                $act['banner']=$data['banner'];
                $act['is_display']=$data['is_display'];
                $act['create_time'] = $_SERVER['REQUEST_TIME'];
                $act['update_time'] = $_SERVER['REQUEST_TIME'];

                $link  = "http://www.shjppn.com/Home/Act/view".Tool::getRandomString(5); //url 短链接 默认5位数
                $act['link'] = $link;

                $result = M('activity')->add($act);

                if($result){
                    $response = array(
                        'status' => 'a_success',
                        'title' => Prompt::$ActPrompt['a_success'],
                    );

                }else{
                    $response = array(
                        'status' => 'a_err',
                        'title' => Prompt::$ActPrompt['a_err'],
                    );
                }
                return $this->ajaxReturn($response);
            }
        }
    }

    public function edit()
    {
        if(IS_GET)
        {
            if (empty($_GET['act_id'])) {
                $this->redirect("index");
                exit;
            }
            $act_id = I('get.act_id');
            $res = M('activity')->find($act_id);

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
            $activity = M('activity');
            $data = I('post.');

            $act_id = $data['act_id'];
            $store_id = $data['store_id'];

            $where = "act_id = '$act_id' && store_id = '$store_id' ";

            $activity->act_title = trim($data['act_title']);
            $activity->content = $data['editor_html'];
            $activity->banner = $data['banner'];
            $activity->is_display = $data['is_display'];
            $activity->update_time = $_SERVER['REQUEST_TIME'];

            $result = $activity->where($where)->save();
            if($result){
                $response = array(
                     'status' => 'u_success',
                     'title' => Prompt::$ActPrompt['u_success'],
                );
            }else{
                $response = array(
                    'status' => 'u_err',
                    'title' => Prompt::$ActPrompt['u_err'],
                );
            }

            return $this->ajaxReturn($response);
        }
    }

    public function del()
    {
        if (empty($_GET['act_id'])) {
            $this->redirect("index");
            exit;
        }
        $act_id = I('get.act_id');

        if (M('activity')->delete($act_id)) {
            $response = array(
                'status' => 'd_success',
                'title' => Prompt::$ActPrompt['d_success'],
            );
        } else {
            $response = array(
                'status' => 'd_err',
                'title' => Prompt::$ActPrompt['d_err'],
            );
        }
        return $this->ajaxReturn($response);
    }

    public function view()
    {
        if (empty($_GET['act_id'])) {
            $this->redirect("index");
            exit;
        }
        $act_id = I('get.act_id');
        $res = M('activity')->find($act_id);

        if (empty($res)) {
            $this->redirect('index');
            exit;
        }

        //活动所属门店的名称
        $store = M('store')->where(array('store_id'=>$res['store_id']))->find();
        $res['store_name'] = $store['store_name'];

        $res['create_time'] = date('Y-m-d H:i',$res['create_time']);
        $res['update_time'] = date('Y-m-d H:i',$res['update_time']);
        $res['is_display'] = $res['is_display']==1?'YES':'NO';

        $this->assign('data', $res);
        $this->display();

    }
}