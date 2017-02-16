<?php
namespace Admin\Common\Common;
use Think\Controller;
use Admin\Controller\SidebarController;

Class BaseController extends Controller {
    public function _initialize(){
        if(empty($_SESSION['Admin'])){
            //$this->redirect('Admin/HomeAct/index');
            $this->redirect('Admin/Login/login');
        }

        $AUTH = new \Think\Auth();
        //类库位置应该位于ThinkPHP\Library\Think\AUTH.CLASS.PHP
        if(!$AUTH->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME, $_SESSION['Admin']['uid'])){
           $this->error('没有权限',U('Admin/Index/index'),1);
        }

         SidebarController::setSidebar();
     }

    function _Empty() {
        $this->redirect('Index/index');
    }
}