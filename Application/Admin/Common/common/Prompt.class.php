<?php
namespace Admin\Common\Common;

Class Prompt {
    /**
     * 用户
     */
    public static $AdminPrompt = array (
        'existence' => 'Name already exists, please re-enter',
        'a_err'     => 'Add success',
        'a_success' => 'Add failed',
        'u_err'     => 'Modify failed',
        'u_success' => 'Modify success',
        'd_err'     => 'Delete failed',
        'd_success' => 'Delete success',
        'd_this'    => 'Cannot delete oneself',
        'u_again'   => 'Modify successfully, please login again',
    );

    /**
     * 登录
     */
    public static $LoginPrompt = array (
        'user_null' => 'user does not exist',
        'pwd_err'   => 'Password input error, please re-enter',
        'disable'   => 'You have been disabled',
        'success'   => 'Login success',
    );

    /**
     * 角色
     */
    public static $AuthGroupPrompt = array (
        //'existence' => '角色名称已存在',
        'a_err'                     => 'Add failed',
        'a_success'                 => 'Add success',
        'u_err'                     => 'Modify failed',
        'u_success'                 => 'Modify success',
        'authorization_success'     => 'Distribution success',
        'authorization_err'         => 'allocation failed',
    );

    /**
     * 权限
     */
    public static $AuthRulePrompt = array (
        'a_err'     => 'Add failed',
        'a_success' => 'Add success',
        'u_err'     => 'Modify failed',
        'u_success' => 'Modify success',
        'd_err'     => 'Delete failed',
        'd_success' => 'Delete success',
    );

    /**
     * 门店
     */
    public static $StorePrompt = array (
        'a_err'     => 'Add failed',
        'a_success' => 'Add success',
        'u_err'     => 'Modify failed',
        'u_success' => 'Modify success',
        'd_err'     => 'Delete failed',
        'd_success' => 'Delete success',
    );

    /**
     * 店员
     */
    public static $ClerkPrompt = array (
        'existence' => 'The store name already exists, please re-enter',
        'a_err'     => 'Add failed',
        'a_success' => 'Add success',
        'u_err'     => 'Modify failed',
        'u_success' => 'Modify success',
        'd_err'     => 'Delete failed',
        'd_success' => 'Delete success',
        'record'    => 'The clerk has returned to the operating record, delete the failure',
    );

    /**
     * 返现
     */
    public static $BackNowPrompt = array (
        'a_err'         => 'Add failed',
        'a_success'     => 'Add success',
        'u_err'         => 'Modify failed',
        'u_success'     => 'Modify success',
        'd_err'         => 'Delete failed',
        'd_success'     => 'Delete success',
        'bn_success'    => 'Return to success',
        'bn_err'        => 'Failure to return',
    );

    /**
     * 活动
     */
    public static $ActPrompt = array (
        'a_err'     => 'Add failed',
        'a_success' => 'Add success',
        'u_err'     => 'Modify failed',
        'u_success' => 'Modify success',
        'd_err'     => 'Delete failed',
        'd_success' => 'Delete success',
    );

    /**
     * 渠道
     */
    public static $ChannelPrompt = array (
        'a_err'     => 'Add failed',
        'a_success' => 'Add success',
        'u_err'     => 'Modify failed',
        'u_success' => 'Modify success',
        'd_err'     => 'Delete failed',
        'd_success' => 'Delete success',
    );

}