<?php
namespace Admin\Controller;

class LabelCode
{
    //一级二级节点名字中英文转换
    public static $lbCode=array(

        //'PX_INDEX'      =>  '首页',
        'PX_INDEX'      =>  'HOME',

        'PX_ADMIN'      =>  '管理员',
        'PX_ADMINLIST'  =>  '管理员列表',
        'PX_ADMINADD'   =>  '管理员添加',
        'PX_ADMINEDIT'  =>  '管理员修改',
        'PX_ADMINDEL'   =>  '删除管理员',
        'PX_ADMIN_LOCK'     =>  '管理员禁用',
        'PX_ADMIN_ACTIVE'   =>  '管理员启用',
        'PX_ADMIN_CENTER'   =>  '个人信息',
/*        'PX_ADMIN'      =>  'ADMIN',
        'PX_ADMINLIST'  =>  'ADMIN LIST',
        'PX_ADMINADD'   =>  'ADMIN ADD',
        'PX_ADMINEDIT'  =>  'ADMIN EDIT',
        'PX_ADMINDEL'   =>  'ADMIN DELETE',
        'PX_ADMIN_LOCK'     =>  'ADMIN DISABLE',
        'PX_ADMIN_ACTIVE'   =>  'ADMIN ENABLE',
        'PX_ADMIN_CENTER'   =>  'PERSONAL INFORMATION',*/

        'PX_STORE'      =>  '门店',
        'PX_STORELIST'  =>  '门店列表',
        'PX_STOREADD'   =>  '添加门店',
        'PX_STOREEDIT'  =>  '修改门店',
        'PX_STOREDEL'   =>  '删除门店',
/*        'PX_STORE'      =>  'STORE',
        'PX_STORELIST'  =>  'STORE LIST',
        'PX_STOREADD'   =>  'STORE ADD',
        'PX_STOREEDIT'  =>  'STORE EDIT',
        'PX_STOREDEL'   =>  'STORE DELETE',*/

        'PX_CLERK'      =>  '店员',
        'PX_CLERKLIST'  =>  '店员列表',
        'PX_CLERKADD'   =>  '添加店员',
        'PX_CLERKEDIT'  =>  '修改店员',
        'PX_CLERKDEL'   =>  '删除店员',
        'PX_CLERK_LOCK'     =>  '店员禁用',
        'PX_CLERK_ACTIVE'   =>  '店员启用',
/*        'PX_CLERK'      =>  'CLERK',
        'PX_CLERKLIST'  =>  'CLERK LIST',
        'PX_CLERKADD'   =>  'CLERK ADD',
        'PX_CLERKEDIT'  =>  'CLERK EDIT',
        'PX_CLERKDEL'   =>  'CLERK DELETE',
        'PX_CLERK_LOCK'     =>  'CLERK DISABLE',
        'PX_CLERK_ACTIVE'   =>  'CLERK ENABLE',*/

        'PX_RULE'       =>  '权限',
        'PX_RULEADD'    =>  '添加权限',
        'PX_RULELIST'   =>  '权限列表',
        'PX_RULEEDIT'   =>  '修改权限',
        'PX_RULEDEL'    =>  '删除权限',
        'PX_RULE_VIEW'  =>  '权限详情',
/*        'PX_RULE'       =>  'JURISDICTION',
        'PX_RULEADD'    =>  'JURISDICTION ADD',
        'PX_RULELIST'   =>  'JURISDICTION LIST',
        'PX_RULEEDIT'   =>  'JURISDICTION EDIT',
        'PX_RULEDEL'    =>  'JURISDICTION DELETE',
        'PX_RULE_VIEW'  =>  'JURISDICTION DETAILS',*/

        'PX_ROLE'       =>  '角色',
        'PX_ROLELIST'   =>  '角色列表',
        'PX_ROLEADD'    =>  '添加角色',
        'PX_ROLEEDIT'   =>  '修改角色',
        'PX_ROLEAUTH'   =>  '分配权限',
        'PX_ROLE_LOCK'     =>  '角色禁用',
        'PX_ROLE_ACTIVE'   =>  '角色启用',
/*        'PX_ROLE'       =>  'ROLE',
        'PX_ROLELIST'   =>  'ROLE LIST',
        'PX_ROLEADD'    =>  'ROLE ADD',
        'PX_ROLEEDIT'   =>  'ROLE EDIT',
        'PX_ROLEAUTH'   =>  'ROLE DISTRIBUTION',
        'PX_ROLE_LOCK'     =>  'ROLE DISABLE',
        'PX_ROLE_ACTIVE'   =>  'ROLE ENABLE',*/

        'PX_BACK_NOW'       =>  '返现',
        'PX_BACK_NOWLIST'   =>  '返现列表',
        'PX_BACK_NOWADD'    =>  '添加返现',
        'PX_BACK_NOWDEL'    =>  '删除返现',
        'PX_BACK_NOW_EXCEL'          =>  '导出报表',
        'PX_BACK_NOW_INVALID'        =>  '返现失效',
        'PX_BACK_NOW_VIEW'        =>  '返现详情',
        'PX_BACK_NOW_ADOPT'       =>  '返现审核通过',
        'PX_BACK_NOW_BACKNOW'     =>  '确认返现',
/*        'PX_BACK_NOW'       =>  'BACK NOW',
        'PX_BACK_NOWLIST'   =>  'BACK NOW LIST',
        'PX_BACK_NOWADD'    =>  'BACK NOW ADD',
        'PX_BACK_NOWDEL'    =>  'BACK NOW DELETE',
        'PX_BACK_NOW_EXCEL'          =>  'EXPORT REPORT',
        'PX_BACK_NOW_INVALID'        =>  'BACK NOW INVALID',
        'PX_BACK_NOW_VIEW'           =>  'BACK NOW DETAILS',
        'PX_BACK_NOW_ADOPT'          =>  'BACK NOW AUDIT THROUGH',
        'PX_BACK_NOW_BACKNOW'        =>  'BACK NOW CONFIRM',*/

        'PX_ACTIVITY'       =>  '活动',
        'PX_ACTIVITYLIST'   =>  '活动列表',
        'PX_ACTIVITYADD'    =>  '添加活动',
        'PX_ACTIVITYEDIT'   =>  '修改活动',
        'PX_ACTIVITYDEL'    =>  '删除活动',
        'PX_ACTIVITY_VIEW'  =>  '活动详情',
/*        'PX_ACTIVITY'       =>  'ACTIVITY',
        'PX_ACTIVITYLIST'   =>  'ACTIVITY LIST',
        'PX_ACTIVITYADD'    =>  'ACTIVITY ADD',
        'PX_ACTIVITYEDIT'   =>  'ACTIVITY EDIT',
        'PX_ACTIVITYDEL'    =>  'ACTIVITY DELETE',
        'PX_ACTIVITY_VIEW'  =>  'ACTIVITY DETAILS',*/

        'PX_CHANNEL'       =>  '渠道',
        'PX_CHANNELLIST'   =>  '渠道列表',
        'PX_CHANNELADD'    =>  '添加渠道',
        'PX_CHANNELEDIT'   =>  '修改渠道',
        'PX_CHANNELDEL'    =>  '删除渠道',
        'PX_CHANNEL_VIEW'  =>  '渠道详情',
        'PX_CHANNEL_USER'  =>  '渠道用户列表',
        'PX_CHANNEL_PDF'   =>  '导出渠道二维码',
/*        'PX_CHANNEL'       =>  'CHANNEL',
        'PX_CHANNELLIST'   =>  'CHANNEL LIST',
        'PX_CHANNELADD'    =>  'CHANNEL ADD',
        'PX_CHANNELEDIT'   =>  'CHANNEL EDIT',
        'PX_CHANNELDEL'    =>  'CHANNEL DELETE',
        'PX_CHANNEL_VIEW'  =>  'CHANNEL DETAILS',
        'PX_CHANNEL_USER'  =>  'CHANNEL USER LIST',
        'PX_CHANNEL_PDF'   =>  'EXCEL CHANNEL QR CODE',*/

    );

    public static function getlbText($lb) {
        if (isset(self::$lbCode[$lb])) {
            return self::$lbCode[$lb];
        }else {
            return $lb;
        }
    }
}

?>