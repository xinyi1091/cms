<?php
define('IN_BIANMPS', true);
require dirname(__FILE__) . '/include/common.php';
require BIANMPS_ROOT . 'include/pay.fun.php';

extract($_REQUEST);
$payonline_setting = get_pay_setting();
$paycenter = 'alipay';
array_key_exists($paycenter, $payonline_setting) or showmsg('֧������');
@extract($payonline_setting[$paycenter]);
require BIANMPS_ROOT.'include/payonline/'.$paycenter.'/receive.php';

$total_amount = $amount + $trade_fee;
$seo['title'] = '֧��������Ϣ';
include template('payreceive');
?>