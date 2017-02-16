<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Controller\LabelCode;
use Admin\Controller\IconCode;

class SidebarController extends Controller {
    public function setSidebar()
    {
        //seeeion id
        $session_uid=$_SESSION['Admin']['uid'];

        //获得当前用户的角色信息
        $uid = M('auth_group_access')->field('group_id')->where(array('uid'=>$session_uid))->find();

        $rules = M('auth_group')->field('rules')->where(array('id'=>$uid['group_id']))->find();
        //var_dump($rules);die;

        //获得当前角色信息的权限信息,$root根节点
        $sql = 'select * from '.C('DB_PREFIX').'auth_rule where id in ('.$rules['rules'].') and level = 1 Order By sort';
        $root = D()->query($sql);

        $sql = 'select * from '.C('DB_PREFIX').'auth_rule where id in ('.$rules['rules'].') and level = 2 Order By sort ASC';
        $node = D()->query($sql);

        // var_dump($root);
        // echo "--------------------------------";
        // var_dump($node);die;

        $menu = array();
        for($i = 0; $i < count($root); $i++) {
            $menu[$i]["n"] = LabelCode::getlbText($root[$i]["title"]);
//          $menu[$i]["link"] = U($root[$i]["p_c"].'/'.$root[$i]["p_a"]);
            $menu[$i]["class"] = IconCode::getIconClass($root[$i]["title"]);
            $menu[$i]["submenu"] = array();
            $menu[$i]["showsub"] = false;
           // var_dump($menu);die;

            if(strtolower($menu[$i]['p_c']) == strtolower(CONTROLLER_NAME)){
                $menu[$i]["active"] = true;
            }else{
                $menu[$i]["active"] = false;
            }

            for($j = 0; $j < count($node); $j++) {
                if($node[$j]["pid"] == $root[$i]["id"]) {
                    $menu[$i]["showsub"] = true;
                    $submenu["n"] = LabelCode::getlbText($node[$j]["title"]);
                    $submenu["link"] = U($node[$j]["p_c"].'/'.$node[$j]["p_a"]);
                    $submenu["class"] = IconCode::getIconClass($node[$j]["title"]);
                    if(strtolower($node[$j]['p_c']) == strtolower(CONTROLLER_NAME)){
                        $menu[$i]["active"] = true;
                    }else{
                        $menu[$i]["active"] = false;
                    }
                    array_push($menu[$i]['submenu'],$submenu);
                }
            }
            if(!empty($menu[$i]['submenu']))
                $menu[$i]["link"] = "#";
            else
                $menu[$i]["link"] = U($root[$i]["p_c"].'/'.$root[$i]["p_a"]);
        }
        $this->assign('sidebar', $menu);
    }
}