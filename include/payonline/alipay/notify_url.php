<?php
require_once("alipay_notify.php");
require_once("alipay_config.php");
$alipay = new alipay_notify($partner,$security_code,$sign_type,$_input_charset,$transport);
$verify_result = $alipay->notify_verify();
if($verify_result) {

 //��ȡ֧�����ķ�������

  $dingdan=$_POST['out_trade_no'];    //��ȡ֧�������ݹ����Ķ�����
  $total=$_POST['total_fee'];    //��ȡ֧�������ݹ������ܼ۸�

    $receive_name    =$_POST['receive_name'];   //��ȡ�ջ�������
	$receive_address =$_POST['receive_address']; //��ȡ�ջ��˵�ַ
	$receive_zip     =$_POST['receive_zip'];  //��ȡ�ջ����ʱ�
	$receive_phone   =$_POST['receive_phone']; //��ȡ�ջ��˵绰
	$receive_mobile  =$_POST['receive_mobile']; //��ȡ�ջ����ֻ�


  $trade_status=$_POST['trade_status'];    //��ȡ֧��������������״̬,���ݲ�ͬ��״̬���������ݿ� WAIT_BUYER_PAY(��ʾ�ȴ���Ҹ���);WAIT_SELLER_SEND_GOODS(��ʾ��Ҹ���ɹ�,�ȴ����ҷ���);WAIT_BUYER_CONFIRM_GOODS(�����Ѿ������ȴ����ȷ��);TRADE_FINISHED(��ʾ�����Ѿ��ɹ�����)

 

if($_POST['trade_status'] == 'TRADE_FINISHED') {
   //����������Զ������,������ݲ�ͬ��trade_status���в�ͬ����

echo "success";
}
	
	log_result("verify_success"); //����֤��������ļ�	
}
else  {
	echo "fail";
	//����������Զ�����룬����������Զ������,������ݲ�ͬ��trade_status���в�ͬ����
	log_result ("verify_failed");
}
function  log_result($word) {
	$fp = fopen("log.txt","a");	
	flock($fp, LOCK_EX) ;
	fwrite($fp,$word."��ִ�����ڣ�".strftime("%Y%m%d%H%I%S",time())."\t\n");
	flock($fp, LOCK_UN); 
	fclose($fp);
}
	
?>