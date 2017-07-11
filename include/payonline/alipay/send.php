<?php
defined('IN_PHPMPS') or exit('Access Denied');
require_once PHPMPS_ROOT.'include/payonline/'.$paycenter.'/alipay_service.php';
require_once PHPMPS_ROOT.'include/payonline/'.$paycenter.'/alipay_config.php';

$parameter = array(
	'service' => 'create_direct_pay_by_user',	//�������ͣ�����ʵ�ｻ�ף�trade_create_by_buyer����Ҫ��д������ ������Ʒ���ף�create_digital_goods_trade_p
	'partner' =>$partner,					//�����̻���
	'return_url' =>$return_url,				//ͬ������
	'notify_url' =>$notify_url,				//�첽����
	'_input_charset' => $_input_charset,	//�ַ�����Ĭ��ΪGBK
	'subject' => '���߳�ֵ',	//��Ʒ���ƣ�����
	'body' => '֧������',      //��Ʒ����������
	'out_trade_no' => $orderid,             //��Ʒ�ⲿ���׺ţ�����,ÿ�β��Զ����޸�
	'logistics_fee'=> '0',//�������ͷ���
	'logistics_payment'=> 'SELLER_PAY', // �������ͷ��ø��ʽ��SELLER_PAY(����֧��)��BUYER_PAY(���֧��)��BUYER_PAY_AFTER_RECEIVE(��������)
	'logistics_type'=> 'POST',	// �������ͷ�ʽ��POST(ƽ��)��EMS(EMS)��EXPRESS(�������)
	'price' => $amount,							//��Ʒ���ۣ�����
	'payment_type'=>'1',						// Ĭ��Ϊ1,����Ҫ�޸�
	'quantity' => '1',							//��Ʒ����������
	'show_url' => $show_url,					//��Ʒ�����վ
	'seller_email' => $seller_email             //�������䣬����
);
//��URL���
$alipay = new alipay_service($parameter,$security_code,$sign_type);
$link=$alipay->create_url();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$charset?>">
<title>������ת��֧��ƽ̨...</title>
<meta http-equiv="refresh" content="0;URL=<?=$link?>">
</head>
<body>
</body>
</html>