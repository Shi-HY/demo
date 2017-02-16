<?php
namespace Admin\Controller;

use Think\Controller;
use Admin\Common\common\Prompt;

class AdminAjaxController extends Controller {
    public function verName()
    {
        $admin = M('admin');
        $data = I('post.');

        $map['username'] = array('eq',$data['username']);
        $msg = $admin->where($map)->find();

        if(!empty($msg))
        {
            $response = array(
                'status' => 'existence',
                'title' => Prompt::$AdminPrompt['existence'],
            );
            return $this->ajaxReturn($response);
        }
    }
}