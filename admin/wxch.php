<?php
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
$exc = new exchange($ecs->table("wxch_config"), $db, 'id', '');
/* act操作项的初始化 */
if (empty($_REQUEST['act']))
{
    $_REQUEST['act'] = 'wxconfig';
}
else
{
    $_REQUEST['act'] = trim($_REQUEST['act']);
}
if($_REQUEST['act']=='wxconfig'){
	$sql = 'SELECT * FROM ' . $ecs->table('wxch_config') . ' where 1';
    $result = $db->getRow($sql);
    $wxchdata = array(
    array('title'=>'Token','cfg_name'=>'token','cfg_value'=>$result['token']),
    array('title'=>'Appid','cfg_name'=>'appid','cfg_value'=>$result['appid']),
    array('title'=>'Appsecret','cfg_name'=>'appsecret','cfg_value'=>$result['appsecret'])
    );
	$smarty->assign('wxchdata',$wxchdata);
	$smarty->display('wxch_config.html');
}
elseif($_REQUEST['act']=='update'){
	
    $token = !empty($_POST['token']) ? trim($_POST['token']) : '' ;
    $appid = !empty($_POST['appid']) ? trim($_POST['appid']) : '' ;
    $appsecret = !empty($_POST['appsecret']) ? trim($_POST['appsecret']) : '' ;
    if (!empty($token))
    {
        $param = " token = '$token' ";
    }
    if (!empty($appid))
    {
        $param .= ", appid = '$appid' ";
    }
    if (!empty($appsecret))
    {
        $param .= ", appsecret = '$appsecret' ";
    }

    if ($exc->edit($param,  1))
    {
        /* 清除缓存 */
        clear_cache_files(); 
        $link[0]['text'] = '返回配置页面';
        $link[0]['href'] = 'wxch.php?act=wxconfig';
        sys_msg('设置成功', 0, $link);
    }
    else
    {
        die($db->error());
    }
}