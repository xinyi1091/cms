<?php
defined('IN_BIANMPS') or exit('Access Denied');
$partner = $partnerid;			//�������ID
$security_code = $keycode;		//��ȫ������
$seller_email = $email;	        //��������
$_input_charset = 'GBK';		//�ַ������ʽ  Ŀǰ֧�� GBK �� utf-8
$sign_type = 'MD5';				//���ܷ�ʽ  ϵͳĬ��(��Ҫ�޸�)
$transport= 'https';			//����ģʽ,����Ը����Լ��ķ������Ƿ�֧��ssl���ʶ�ѡ��http�Լ�https����ģʽ(ϵͳĬ��,��Ҫ�޸�)
$notify_url = $receiveurl;		// �첽���ص�ַ
$return_url = $receiveurl;		//ͬ�����ص�ַ
$show_url   = $CFG['weburl'];		//����վ��Ʒ��չʾ��ַ,����Ϊ��

/** ��ʾ����λ�ȡ��ȫУ����ͺ���ID
1.���� www.alipay.com��Ȼ���½�����ʻ�($seller_email).
  2.������Ͻǵġ��̼ҹ��ߡ�.
  3.����վ����Ŀ¼�£�ѡ���ʺ����Ľ��׷�ʽ��Ȼ�����������.
  4.��д�������񣬵����һ���������Կ���һ��32λ���ַ��������ǰ�ȫУ��($security_code).
  5.����ID�ڰ�ȫУ�����·�($partner).
*/
?>