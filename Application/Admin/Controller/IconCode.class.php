<?php

namespace Admin\Controller;

class IconCode
{
    //一级二级图标
    public static $lbCode=array(

        'PX_INDEX'      =>      'im-screen',
        'PX_ADMIN'      =>      'im-users2',
        'PX_RULE'       =>      'im-key2',
        'PX_ROLE'       =>      'en-flashlight',
        'PX_STORE'      =>      'im-home3',
        'PX_BACK_NOW'   =>      'fa-money',
        'PX_ACTIVITY'   =>      'ec-list',
        'PX_CHANNEL'    =>      'fa-random',
    );

    public static function getIconClass($lb) {
        if (isset(self::$lbCode[$lb])) {
            return self::$lbCode[$lb];
        }else {
            return 'icon';
        };
    }
}

?>