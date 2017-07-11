DROP TABLE IF EXISTS `zj_about`;
CREATE TABLE `zj_about` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(100) NOT NULL,
  `keywords` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `postdate` int(11) NOT NULL,
  `url` varchar(100) NOT NULL,
  `aboutorder` smallint(5) NOT NULL default '0',
  `is_show` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `is_show` (`is_show`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `zj_admin`;
CREATE TABLE `zj_admin` (
  `userid` smallint(5) unsigned NOT NULL auto_increment,
  `username` varchar(30) NOT NULL,
  `password` varchar(32) NOT NULL,
  `truename` varchar(30) NOT NULL,
  `email` varchar(35) NOT NULL,
  `purview` text NOT NULL,
  `is_admin` tinyint(1) NOT NULL, 
  `lastip` varchar(15) NOT NULL,
  `lastlogin` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`userid`),
  KEY `username` (`username`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `zj_admin_log`;
CREATE TABLE `zj_admin_log` (
  `logid` int(10) unsigned NOT NULL auto_increment,
  `adminname` varchar(32) NOT NULL,
  `logdate` int(10) unsigned NOT NULL,
  `logtype` varchar(255) NOT NULL,
  `logip` varchar(15) NOT NULL,
  PRIMARY KEY  (`logid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `zj_ads`;
CREATE TABLE IF NOT EXISTS `zj_ads` (
  `adsid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `placeid` smallint(5) unsigned NOT NULL,
  `adsname` varchar(32) NOT NULL,
  `adstype` tinyint(3) NOT NULL,
  `adsurl` varchar(150) NOT NULL,
  `adscode` text NOT NULL,
  `addtime` int(11) unsigned NOT NULL,
  `updatetime` int(11) NOT NULL,
  `linkman` varchar(32) NOT NULL,
  PRIMARY KEY (`adsid`),
  KEY `adsname` (`adsname`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `zj_ads_place`;
CREATE TABLE IF NOT EXISTS `zj_ads_place` (
  `placeid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `placename` varchar(32) NOT NULL,
  `width` smallint(5) unsigned NOT NULL,
  `height` smallint(5) unsigned NOT NULL,
  `introduce` varchar(100) NOT NULL,
  PRIMARY KEY (`placeid`),
  KEY `placename` (`placename`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `zj_area`;
CREATE TABLE `zj_area` (
  `areaid` mediumint(6) NOT NULL auto_increment,
  `areaname` varchar(32) NOT NULL,
  `parentid` int(11) unsigned NOT NULL,
  `areaorder` smallint(6) NOT NULL,
  PRIMARY KEY  (`areaid`),
  KEY `areaname` (`areaname`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `zj_article`;
CREATE TABLE IF NOT EXISTS `zj_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `typeid` smallint(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  `keywords` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `thumb` varchar(50) NOT NULL,
  `listorder` smallint(5) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL,
  `is_index` tinyint(1) unsigned NOT NULL,
  `is_pro` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `is_index` (`is_index`),
  KEY `addtime` (`addtime`),
  KEY `is_pro` (`is_pro`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `zj_category`;
CREATE TABLE `zj_category` (
  `catid` mediumint(6) NOT NULL auto_increment,
  `catname` varchar(32) NOT NULL,
  `keywords` varchar(255) default NULL,
  `description` varchar(255) default NULL,
  `parentid` int(11) default NULL,
  `catorder` smallint(6) NOT NULL,
  `cattplname` varchar(30) NOT NULL,
  `viewtplname` varchar(30) NOT NULL,
  PRIMARY KEY  (`catid`),
  KEY `parentid` (`parentid`),
  KEY `catname` (`catname`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `zj_com`;
CREATE TABLE IF NOT EXISTS `zj_com` (
  `comid` mediumint(6) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `catid` smallint(5) unsigned NOT NULL,
  `areaid` smallint(5) unsigned NOT NULL,
  `comname` varchar(100) NOT NULL,
  `keywords` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `thumb` varchar(50) NOT NULL,
  `introduce` text,
  `phone` varchar(15) NOT NULL,
  `linkman` varchar(32) NOT NULL,
  `qq` varchar(15) NOT NULL,
  `msn` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `fax` varchar(15) NOT NULL,
  `address` varchar(100) NOT NULL,
  `hours` varchar(50) NOT NULL,
  `mappoint` varchar(16) NOT NULL,
  `is_check` tinyint(1) unsigned NOT NULL,
  `click` int(11) NOT NULL,
  `postdate` int(11) unsigned NOT NULL,
  PRIMARY KEY (`comid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `zj_comment`;
CREATE TABLE `zj_comment` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `infoid` mediumint(8) unsigned NOT NULL default '0',
  `userid` int(11) unsigned NOT NULL,
  `username` varchar(60) NOT NULL,
  `content` text NOT NULL,
  `is_check` tinyint(1) unsigned NOT NULL,
  `postdate` int(10) unsigned NOT NULL default '0',
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `infoid` (`infoid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `zj_com_cat`;
CREATE TABLE IF NOT EXISTS `zj_com_cat` (
  `catid` mediumint(6) NOT NULL AUTO_INCREMENT,
  `catname` varchar(32) NOT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `parentid` int(11) DEFAULT NULL,
  `catorder` smallint(6) NOT NULL,
  PRIMARY KEY (`catid`),
  KEY `parentid` (`parentid`),
  KEY `catname` (`catname`)
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `zj_com_image`;
CREATE TABLE IF NOT EXISTS `zj_com_image` (
  `imgid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `comid` int(11) unsigned NOT NULL,
  `path` varchar(100) NOT NULL,
  PRIMARY KEY (`imgid`),
  KEY `infoid` (`comid`)
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `zj_config`;
CREATE TABLE `zj_config` (
  `setname` varchar(100) NOT NULL,
  `value` text,
  KEY `setname` (`setname`)
) TYPE=MyISAM ;

INSERT INTO `zj_config` (`setname`, `value`) VALUES
('webname', '�Ϻ��ڼҷ�����Ϣ'),
('weburl', ''),
('keywords', ''),
('copyright', '�Ϻ��ڼҷ�����Ϣ'),
('description', ''),
('banwords', ''),
('icp', ''),
('qq', ''),
('post_check', ''),
('comment_check', ''),
('count', ''),
('tplname', 'phpmps'),
('crypt', ''),
('maxpost', '15'),
('annouce', ''),
('rewrite', '0'),
('onlyarea', ''),
('map', ''),
('del_m_info', '1'),
('del_m_comment', '1'),
('pagesize', '20'),
('uc', '0'),
('uc_api', 'http://localhost/ucenter'),
('uc_appid', '1'),
('uc_key', 'phpmps'),
('uc_dbhost', 'localhost'),
('uc_dbname', 'ucenter'),
('uc_dbuser', 'root'),
('uc_dbpwd', ''),
('uc_dbpre', 'uc_'),
('uc_charset', 'gbk'),
('expired_view', '0'),
('visitor_post', '1'),
('visitor_view', '1'),
('visitor_comment', '1'),
('closesystem', '0'),
('close_tips', '��վά������ʱ�رգ����Ժ���ʡ�'),
('postfile', 'post.php'),
('sendmailtype', ''),
('smtphost', ''),
('smtpuser', ''),
('smtppass', ''),
('smtpport', ''),
('info_top_gold', '1'),
('info_refer_gold', '1'),
('max_login_credit', '2'),
('register_credit', '1'),
('login_credit', '1'),
('post_info_credit', '2'),
('post_comment_credit', '1'),
('credit2gold', '20'),
('money2gold', '1'),
('max_comment_credit', '5'),
('max_info_credit', '5'),
('qqun', ''),
('phone', ''),
('email', ''),
('close_register', '0'),
('reg_check', '0'),
('wap', '1'),
('com_pagesize', '18'),
('mapapi', 'http://api.map.baidu.com/api?v=1.1'),
('mapflag', 'baidu'),
('map_view_level', '15'),
('mapapi_charset', ''),
('com_thumbwidth', '200'),
('com_thumbheight', '80'),
('testemail', '');

DROP TABLE IF EXISTS `zj_custom`;
CREATE TABLE IF NOT EXISTS `zj_custom` (
  `cusid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `cusname` varchar(60) NOT NULL,
  `custype` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `value` text NOT NULL,
  `unit` varchar(32) NOT NULL,
  `search` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cusid`),
  KEY `catid` (`catid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `zj_cus_value`;
CREATE TABLE IF NOT EXISTS `zj_cus_value` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `infoid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `cusid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `cusvalue` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `infoid` (`infoid`),
  KEY `cusid` (`cusid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `zj_facilitate`;
CREATE TABLE IF NOT EXISTS `zj_facilitate` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL,
  `phone` varchar(13) NOT NULL,
  `introduce` varchar(255) NOT NULL,
  `listorder` smallint(5) unsigned NOT NULL,
  `updatetime` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `number` (`phone`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `zj_flash`;
CREATE TABLE `zj_flash` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `image` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `flaorder` smallint(5) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `image` (`image`),
  KEY `url` (`url`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `zj_help`;
CREATE TABLE IF NOT EXISTS `zj_help` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `typeid` smallint(5) NOT NULL,
  `keywords` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `listorder` smallint(5) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL,
  `is_index` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `is_index` (`is_index`),
  KEY `addtime` (`addtime`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

INSERT INTO `zj_help` (`id`, `title`, `typeid`, `keywords`, `description`, `content`, `listorder`, `addtime`, `is_index`) VALUES
(1, '���ע���Ϊ��վ��Ա', 1, '���ע���Ϊ��վ��Ա', '���ע���Ϊ��վ��Ա', '<p><br />\r\n�����վ������ע�����ӽ�����дע����Ϣҳ�棬��д�û�����������ʼ�,���ע�ᰴť��</p>\r\n<p>ע�⣺ 1. �û���ע���Ժ��ǲ��ܸ��ĵ�&nbsp; 2. ע���õ����������������һصģ������뾡����д��ʵ���䡣</p>', 1, 1283498679, 1),
(2, '��ε�¼��վ', 1, '��ε�¼��վ', '��ε�¼��վ', '<p><br />\r\n���ͷ���� [��½] ���ӽ����¼ҳ���,��д�û�����������е�¼��</p>', 2, 1283499260, 0),
(3, '����޸�����', 1, '����޸�����', '����޸�����', '<p>�������ڸ��˹��������е�����޸����롿�����ӽ����޸�����ҳ������޸ġ�</p>', 3, 1283499376, 0),
(4, '����һ�����', 1, '����һ�����', '����һ�����', '<p><br />\r\n������������������ʹ���һ��������<br />\r\n<br />\r\n�һ����벽�裺<br />\r\n��һ��������һ��������ӽ�����д�û���������ҳ�档<br />\r\n�ڶ�������д��ע��ʱ������û����������ַ���������������ʼ���<br />\r\n�������������������ţ�����ʼ��е�&ldquo;��������&rdquo;���ӣ�������������ҳ��<br />\r\n���Ĳ������������������룬����һ����������Ȼ�����Ϳ���ʹ���������¼�ˡ�</p>', 4, 1283499417, 0),
(5, '����޸ĸ�������', 1, '����޸ĸ�������', '����޸ĸ�������', '<p><br />\r\n�������ڸ��˹��������е��&ldquo;�޸�����&rdquo;���ӽ����޸�����ҳ������޸ġ�</p>', 5, 1283499492, 1),
(6, 'ʲô����������', 2, 'ʲô����������', 'ʲô����������', '<p>�����������ǲ���Ҫ��¼ע�ᣬֱ�ӷ�����</p>\r\n<p>������������Ϣ��Ψһ���޸�ƾ֤���Ƿ�����Ϣʱ����д�����룬���μ�������롣</p>', 6, 1283499666, 0),
(7, '����޸���Ϣ����', 2, '����޸���Ϣ����', '����޸���Ϣ����', '<p>1.ƾ�����޸�<br />\r\n������Ϣ��ʱ������û�����һ����Ϣ�������룬����������Ϣ����ҳ���������������޸�<br />\r\n2.��½��Ա�����޸�<br />\r\n�����Ϣ�����ڵ�½״̬�·����ģ���ô�����Ե�½��Ա���ģ�������ҵ���Ϣ���б��ҵ��������Ϣ�����޸ģ�<br />\r\nҲ���Ե���Ϣ����ҳ���㡾�༭�������޸ġ�</p>', 7, 1283499832, 0),
(8, 'ʲô��һ��������Ϣ', 2, 'ʲô��һ��������Ϣ', 'ʲô��һ��������Ϣ', '<p>һ��������Ϣ����ʹ������Ϣ��Ȼ������Ϣ����ǰ��<br />\r\n�����Ϣ����ҳ�ġ�������ť������������һ��������Ϣ������Ϣ�ң������뿴<a href=\'member.php?act=credit_rule\'>member.php?act=credit_rule</a></p>', 8, 1283500716, 1),
(9, '����ö���Ϣ', 2, '����ö���Ϣ', '����ö���Ϣ', '<p>������Ϣ��ʱ�����ѡ����Ϣ�ö���Ҳ���Խ����Ա���ĵ���Ϣ���������Ϣ�Ҳ��&ldquo;�ö�&rdquo;���ӽ����ö�����Ϣ�ö�������Ϣ�ҡ�</p>', 9, 1283500968, 1),
(10, 'ʲô�ǻ��֣��к��ô�', 3, 'ʲô�ǻ��֣��к��ô�', 'ʲô�ǻ��֣��к��ô�', '<p>�����Ƕ��û�ע�ᡢ��½��������Ϣ���������۵�һ�ֽ����������û��ֶһ���Ϣ�ң����û�Ǯ�ö���ˢ����Ϣ��</p>', 10, 1283501184, 1),
(11, 'ʲô����Ϣ��', 3, 'ʲô����Ϣ��', 'ʲô����Ϣ��', '<p>��Ϣ���Ǳ�վһ��������ң�������������Ϣ����ˢ�£��ö���</p>\r\n<p>�����ַ�ʽ�����Ϣ�ң�һ����Ǯ������Ϣ�ң������û��ֶһ���Ϣ�ҡ�</p>', 11, 1283501243, 0),
(12, '���֡���Ϣ�Ⱥ��ʽ����ת����', 3, '���֡���Ϣ�Ⱥ��ʽ����ת����', '���֡���Ϣ�Ⱥ��ʽ����ת����', '<p>��������ת�ã�ֻ�ܹ���Ա�Լ�ʹ�á�</p>', 12, 1283501493, 0);

DROP TABLE IF EXISTS `zj_info`;
CREATE TABLE IF NOT EXISTS `zj_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `catid` mediumint(6) unsigned NOT NULL,
  `areaid` smallint(5) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `thumb` varchar(50) NOT NULL,
  `keywords` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `linkman` varchar(32) NOT NULL,
  `email` varchar(50) NOT NULL,
  `qq` varchar(15) NOT NULL,
  `phone` varchar(13) NOT NULL,
  `address` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `mappoint` varchar(20) NOT NULL,
  `postarea` varchar(32) NOT NULL,
  `postdate` int(11) NOT NULL,
  `enddate` int(11) unsigned NOT NULL,
  `ip` varchar(15) NOT NULL,
  `click` smallint(6) unsigned NOT NULL DEFAULT '0',
  `is_pro` int(11) unsigned NOT NULL ,
  `is_top` int(11) unsigned NOT NULL ,
  `top_type` tinyint(1) unsigned NOT NULL,
  `is_check` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`),
  KEY `areaid` (`areaid`),
  KEY `postdate` (`postdate`),
  KEY `click` (`click`,`postdate`),
  KEY `is_check` (`is_check`),
  FULLTEXT KEY `keywords` (`keywords`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `zj_info_image;
CREATE TABLE `zj_info_image` (
  `imgid` int(11) unsigned NOT NULL auto_increment,
  `infoid` int(11) unsigned NOT NULL,
  `path` varchar(100) NOT NULL,
  PRIMARY KEY  (`imgid`),
  KEY `infoid` (`infoid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `zj_link`;
CREATE TABLE `zj_link` (
  `id` int(11) NOT NULL auto_increment,
  `webname` varchar(30) NOT NULL,
  `url` varchar(50) NOT NULL,
  `linkorder` mediumint(6) NOT NULL,
  `logo` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `webname` (`webname`),
  KEY `url` (`url`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

INSERT INTO `zj_link` (`id`, `webname`, `url`, `linkorder`, `logo`) VALUES 
(1, '', '', 1, '');

DROP TABLE IF EXISTS `zj_member`;
CREATE TABLE IF NOT EXISTS `zj_member` (
  `userid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL,
  `username` varchar(32) NOT NULL,
  `email` varchar(60) NOT NULL,
  `password` varchar(32) NOT NULL,
  `registertime` int(11) unsigned NOT NULL,
  `registerip` varchar(15) NOT NULL,
  `lastlogintime` int(11) unsigned NOT NULL,
  `lastloginip` varchar(15) NOT NULL,
  `sendmailtime` int(11) NOT NULL,
  `qq` varchar(15) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` varchar(100) NOT NULL,
  `mappoint` varchar(50) NOT NULL,
  `money` float NOT NULL,
  `gold` smallint(5) unsigned NOT NULL,
  `credit` smallint(5) unsigned NOT NULL,
  `lastposttime` int(10) unsigned NOT NULL,
  `status` TINYINT( 1 ) UNSIGNED NOT NULL,
  PRIMARY KEY (`userid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `zj_nav`;
CREATE TABLE `zj_nav` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `navname` varchar(32) NOT NULL,
  `url` varchar(100) NOT NULL,
  `target` varchar(6) NOT NULL,
  `navorder` smallint(5) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `name` (`navname`),
  KEY `url` (`url`),
  KEY `navorder` (`navorder`)
) TYPE=MyISAM AUTO_INCREMENT=4 ;

INSERT INTO `zj_nav` (`id`, `navname`, `url`, `target`, `navorder`) VALUES 
(1, '��ҳ', 'index.php', '_self', 1),
(2, '��ҳ', 'com.php', '_self', 2),
(3, '��Ѷ', 'article.php', '_self', 3);

DROP TABLE IF EXISTS `zj_pay`;
CREATE TABLE IF NOT EXISTS `zj_pay` (
  `payid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `typeid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `note` char(200) NOT NULL DEFAULT '',
  `paytype` char(20) NOT NULL DEFAULT '',
  `amount` float NOT NULL DEFAULT '0',
  `balance` float NOT NULL DEFAULT '0',
  `year` smallint(4) unsigned NOT NULL DEFAULT '0',
  `month` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `username` char(30) NOT NULL DEFAULT '',
  `ip` char(15) NOT NULL DEFAULT '',
  `inputer` char(30) NOT NULL DEFAULT '',
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`payid`),
  KEY `type` (`typeid`,`year`,`month`,`date`),
  KEY `username` (`username`)
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `zj_payment`;
CREATE TABLE IF NOT EXISTS `zj_payment` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `paycenter` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `sendurl` varchar(100) NOT NULL,
  `receiveurl` varchar(100) NOT NULL,
  `partnerid` varchar(100) NOT NULL,
  `keycode` varchar(100) NOT NULL,
  `percent` float unsigned NOT NULL DEFAULT '0',
  `email` varchar(60) NOT NULL,
  `enable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;

INSERT INTO `zj_payment` (`id`, `paycenter`, `name`, `logo`, `sendurl`, `receiveurl`, `partnerid`, `keycode`, `percent`, `email`, `enable`) VALUES
(1, 'alipay', '֧����', 'http://img.alipay.com/img/logo/logo_126x37.gif', 'http://www.alipay.com/cooperate/gateway.do', '','202020202020', 'abcde', '1','xookee@qq.com', 1);

DROP TABLE IF EXISTS `zj_pay_exchange`;
CREATE TABLE IF NOT EXISTS `zj_pay_exchange` (
  `exchangeid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL DEFAULT '',
  `type` enum('money','gold','credit') NOT NULL DEFAULT 'money',
  `value` float NOT NULL DEFAULT '0',
  `note` varchar(255) NOT NULL,
  `addtime` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`exchangeid`),
  KEY `username` (`username`,`type`)
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `zj_pay_online`;
CREATE TABLE IF NOT EXISTS `zj_pay_online` (
  `payid` int(11) NOT NULL AUTO_INCREMENT,
  `paycenter` varchar(50) NOT NULL DEFAULT '',
  `username` varchar(30) NOT NULL DEFAULT '',
  `orderid` varchar(64) NOT NULL DEFAULT '',
  `moneytype` varchar(10) NOT NULL DEFAULT '0',
  `amount` double NOT NULL DEFAULT '0',
  `trade_fee` double NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `contactname` varchar(50) NOT NULL DEFAULT '',
  `telephone` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `sendtime` int(11) NOT NULL DEFAULT '0',
  `receivetime` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`payid`),
  KEY `username` (`username`,`orderid`,`status`),
  KEY `orderid` (`orderid`)
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `zj_report`;
CREATE TABLE `zj_report` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `info` int(11) unsigned NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `ip` varchar(15) NOT NULL,
  `postdate` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ip` (`ip`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `zj_type`;
CREATE TABLE IF NOT EXISTS `zj_type` (
  `typeid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `typename` varchar(32) NOT NULL,
  `listorder` smallint(5) NOT NULL,
  `keywords` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `module` char(10) NOT NULL,
  PRIMARY KEY (`typeid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

INSERT INTO `zj_type` (`typeid`, `typename`, `listorder`, `keywords`, `description`, `module`) VALUES
(1, 'ע�����½', 1, 'ע�����½', 'ע�����½', 'help'),
(2, '��Ϣ���', 2, '��Ϣ���', '��Ϣ���', 'help'),
(3, '��������Ϣ�����', 3, '��������Ϣ�����', '��������Ϣ�����', 'help');

DROP TABLE IF EXISTS `zj_ver`;
CREATE TABLE IF NOT EXISTS `zj_ver` (
  `vid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `answer` varchar(50) NOT NULL,
  PRIMARY KEY (`vid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;

INSERT INTO `zj_ver` (`vid`, `question`, `answer`) VALUES
(1, '100+99=', '199'),
(2, '200-49=', '151'),
(3, '26x5=', '130');
