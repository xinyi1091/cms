<?php
defined('IN_PHPMPS') or exit('Access Denied');
require_once PHPMPS_ROOT.'include/payonline/'.$paycenter.'/alipay_notify.php';
require_once PHPMPS_ROOT.'include/payonline/'.$paycenter.'/alipay_config.php';

//���շ��صĲ���
$pay['notify_type']=$notify_type;			//��������
$pay['notify_id']=$notify_id;				//֧������ˮ��
$pay['notify_time']=$notify_time;			//֪ͨʱ��
$pay['sign']=$sign; 						//�����ַ���
$pay['sign_type']=$sing_type;				//���ܷ�ʽ
$pay['out_trade_no']=$out_trade_no;			//����ID��
$pay['subject']=$subject;					//��Ʒ����
$pay['body']=$body;							//��Ʒ����
$pay['total_fee']=$total_fee;				//�ɽ���
$pay['payment_type']=$payment_type;			//֧������
$pay['seller_email']=$seller_email;			//����EMIAL
$pay['seller_id']=$seller_id ;				//����ID
$pay['buyer_id']=$buyer_id;					//���ID
$pay['buyer_email']=$buyer_email;			//����EMAIL
$pay['trade_status']=$trade_status;			//����״̬
$pay['exterface'] = 'create_direct_pay_by_user';
$pay['is_success']=$is_success;
$pay['trade_no']=$trade_no;

$alipay = new alipay_notify($_GET,$security_code,$sign_type,$_input_charset,$transport);//��URL���
$verify_result = $alipay->return_verify();//MD5��֤

if($verify_result)
{
	
	$info = dopay($out_trade_no, $total_fee,'');
    if($info)
	{
		$paystatus = 1;
		extract($info, EXTR_OVERWRITE);
	}
	else
	{
		$paystatus = 2;
	}
}
else
{
	$paystatus = 0;
}
?>