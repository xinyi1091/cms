<?php

if(!defined('IN_BIANMPS'))
{
	die('Access Denied');
}

function cut_str($str, $length, $start=0)
{
	global $charset;
	if(function_exists("mb_substr")) {
	    if(mb_strlen($str, $charset) <= $length) return $str;
	    $slice = mb_substr($str, $start, $length, $charset);
	} else {
		$re['utf-8']  = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		if(count($match[0]) <= $length) return $str;
		$slice = join("",array_slice($match[0], $start, $length));
	}
	return $slice;
}

function str_len($str)
{
    $length = strlen(preg_replace('/[\x00-\x7F]/', '', $str));
    if($length) {
        return strlen($str) - $length + intval($length / 3) * 2;
    } else {
        return strlen($str);
    }
}

function addslashes_deep($value)
{
	return is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
}

function stripslashes_deep($value)
{
	return is_array($value) ? array_map('stripslashes_deep', $value) : (isset($value) ? stripslashes($value) : null);
}

function htmlspecialchars_deep($value)
{
	if (empty($value))
	{
		return $value;
	}
	else
	{
		if(is_array($value))
		{
			foreach($value as $key => $v)
			{
				unset($value[$key]);

				if($htmlspecialchars==true)
				{
					$key=get_magic_quotes_gpc()? addslashes(stripslashes(htmlspecialchars($key,ENT_NOQUOTES))) : addslashes(htmlspecialchars($key,ENT_NOQUOTES));
				}
				else{
					$key=get_magic_quotes_gpc()? addslashes(stripslashes($key)) : addslashes($key);
				}

				if(is_array($v))
				{
					$value[$key]=addslashes_deep($v);
				}
				else
				{
					if($htmlspecialchars==true)
					{
						$value[$key]=get_magic_quotes_gpc()? addslashes(stripslashes(htmlspecialchars($v,ENT_NOQUOTES))) : addslashes(htmlspecialchars($v,ENT_NOQUOTES));
					}
					else{
						$value[$key]=get_magic_quotes_gpc()? addslashes(stripslashes($v)) : addslashes($v);
					}
				}
			}
		}
		else
		{
			if($htmlspecialchars==true)
			{
				$value=get_magic_quotes_gpc()? addslashes(stripslashes(htmlspecialchars($value,ENT_NOQUOTES))) : addslashes(htmlspecialchars($value,ENT_NOQUOTES));
			}
			else{
				$value=get_magic_quotes_gpc()? addslashes(stripslashes($value)) : addslashes($value);
			}
		}
		return $value;
	}
}
//�����ݽ��й���
function safe_replace($string) {
	if(is_array($string)) {
		return array_map('safe_replace', $string);
	} else {
		if(strlen($string) < 20) return $string;
		$match = array("/&#([a-z0-9]+)([;]*)/i","/\<\!\-\-([\s\S]*?)\-\-\>/","/\/\*([\s\S]*?)\*\//","/on(mouse|exit|error|click|dblclick|key|load|unload|change|move|submit|reset|cut|copy|select|start|stop|drag|touch)/i","/s[[:space:]]*c[[:space:]]*r[[:space:]]*i[[:space:]]*p[[:space:]]*t/i","/about/i","/frame/i","/link/i","/import/i","/expression/i","/meta/i","/textarea/i","/eval/i");
		$replace = array("","","","o&#110;\\1","scrip&#116;","abou&#116;","fram&#101;","lin&#107;","impor&#116;","expressio&#110;","met&#97;","textare&#97;","eva&#108;");
		return preg_replace($match, $replace, $string);
	}
}
/**
 * sql������
 *
 * @param $string
 * @return string
 */
function sql_replace($string) {
	$search = array("/union/i","/0x/i","/select([[:space:]\*\/\-])/i","/update([[:space:]\*\/])/i","/replace([[:space:]\*\/])/i","/delete([[:space:]\*\/])/i","/drop([[:space:]\*\/])/i","/outfile([[:space:]\*\/])/i","/dumpfile([[:space:]\*\/])/i","/load_file\(/i","/substring\(/i","/substr\(/i","/concat\(/i","/concat_ws\(/i","/ascii\(/i","/hex\(/i","/ord\(/i","/char\(/i");
	$replace = array('unio&#110;','0&#120;','selec&#116;\\1','updat&#101;\\1','replac&#101;\\1','delet&#101;\\1','dro&#112;\\1','outfil&#101;\\1','dumpfil&#101;\\1','load_fil&#101;(','substrin&#103;(','subst&#114;(','conca&#116;(','concat_w&#115;(','asci&#105;(','he&#120;(','or&#100;(','cha&#114;(');
	return is_array($string) ? array_map('sql_replace', $string) : preg_replace($search, $replace, $string);
}
function random($length)
{
	$chars = '0123456789ABCDEFGHIJ0123456789KLMNOPQRSTJ0123456789UVWXYZ0123456789abcdefghijJ0123456789klmnopqrstJ0123456789uvwxyz0123456789';
	$max = strlen($chars);
	mt_srand((double)microtime() * 1000000);
	for($i = 0; $i < $length; $i ++) {
		$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;
}

function is_email($email)
{
	return strlen($email) > 8 && preg_match("/^[-_+.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+([a-z]{2,4})|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i", $email);
}

function checkupfile($file)
{
	return function_exists('is_uploaded_file') && (is_uploaded_file($file) || is_uploaded_file(str_replace('\\\\', '\\', $file)));
}

function fileext($filename) 
{
	return trim(substr(strrchr($filename, '.'), 1));
}

function get_ip()
{
    static $ip = NULL;
    if($ip !== NULL){return $ip;}
    if(isset($_SERVER)) {
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($arr as $ip) {
                $ip = trim($ip);
                if($ip != 'unknown') {
                    $ip = $ip;
                    break;
                }
            }
        } elseif(isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            if(isset($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            } else {
                $ip = '0.0.0.0';
            }
        }
    } else {
        if(getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif(getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } else {
            $ip = getenv('REMOTE_ADDR');
        }
    }
    preg_match("/[\d\.]{7,15}/", $ip, $onlineip);
    $ip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';

    return $ip;
}

function encrypt($string, $password)
{
	$password = base64_encode($password);
    $count_pwd = strlen("a".$password);
    for($i = 1;$i<$count_pwd;$i++) {
		$pwd+=ord($password{$i});
    }
	$string = base64_encode($string);
    $count = strlen("a".$string);
    for($i = 0;$i<$count;$i++) {
    	$asciis.=(ord($string{$i})+$pwd)."|";
    }
    $asciis = base64_encode($asciis);
	return $asciis;
}

function decrypt($string, $password)
{
	$password = base64_encode($password);
    $count_pwd = strlen("a".$password);
    for($i = 1;$i<$count_pwd;$i++) {
    	$pwd+=ord($password{$i});
    }
    $string = base64_decode($string);
    $contents = explode("|",$string);
    $count = count($contents);
    for($i=0;$i<$count;$i++) {
    	$infos.=chr($contents[$i]-$pwd);
    }
    $asciis = base64_decode($infos);
	return $asciis;
}

function chkcode($width = 60, $height = 22, $count = 4)
{
	$randnum = "";
	if(function_exists("imagecreatetruecolor") && function_exists("imagecolorallocate") && function_exists("imagestring") && function_exists("imagepng") && function_exists("imagesetpixel") && function_exists("imagefilledrectangle") && function_exists("imagerectangle")) {
		$image  = imagecreatetruecolor($width, $height);
		$swhite = imagecolorallocate($image, 255, 255, 255);
		$sblack = imagecolorallocate($image, 0, 0, 0);
		
		//��������
		imagefilledrectangle($image, 0, 0, ($width -2), ($height -2), $swhite);
		imagerectangle($image, 0, 0, $width, $height, $sblack);
		
		//���ɸ�������
		for ($i = 0; $i < 20; $i++) {
			$sjamcolor = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
			imagesetpixel($image, rand(0, $width), rand(0, $height), $sjamcolor);
		}
		
		//�������
		for ($i = 0; $i < $count; $i++) {
			$randnum .= rand(1, 9);
		}

		//������д��ͼ��
		$widthx = floor($width / $count);
		for ($i = 0; $i < strlen($randnum); $i++) {
			$irandomcolor = imagecolorallocate($image, rand(50, 255), rand(50, 120), rand(50, 255));
			imagestring($image, 6, ($widthx * $i +rand(1, 3)), rand(1, 5), $randnum {$i }, $irandomcolor);
		}
		header("Pragma:no-cache");
		header("Cache-control:no-cache");
		header("Content-type: image/png");
		imagepng($image);
		imagedestroy($image);
	} else {
		header("Pragma:no-cache");
		header("Cache-control:no-cache");
		header("Content-type: image/png");
		if (!readfile("images/chkcode.png")) {return false;}
		$randnum = "2293";
	}
	return $randnum;
}

function check_code($checkcode)
{
	$chkcode = $_SESSION['chkcode'];
	if(empty($chkcode) || $chkcode != $checkcode)showmsg('��֤�����');
}

function page($file,$cat,$area,$count,$size=20,$page=1)
{
	global $tpl;
    $page = intval($page);
    if($page<1)$page = 1;

    $page_count = $count > 0 ? intval(ceil($count / $size)) : 1;
    $page_prev  = ($page > 1) ? $page - 1 : 1;
    $page_next  = ($page < $page_count) ? $page + 1 : $page_count;

	$pager['start']      = ($page-1)*$size;
    $pager['page']       = $page;
    $pager['size']       = $size;
    $pager['count']		 = $count;
    $pager['page_count'] = $page_count;
	
	switch ($file)
    {
        case 'category':
			$params = array('cid' => $cat, 'eid' => $area);
        break;
		
		case 'com';
			$params = array('catid' => $cat, 'eid' => $area, 'act'=>'list');
		break;

		case 'article';
			$params = array('iid' => $cat, 'act'=>'list');
		break;
		
		case 'help';
			$params = array('tid' => $cat, 'act'=>'list');
		break;
    }
	if($page_count <= '1') {
	    $pager['first'] = $pager['prev']  = $pager['next']  = $pager['last']  = '';
	} elseif($page_count > '1') {
		if($page == $page_count) {
			$pager['first'] = url_rewrite($file, $params, 1);
			$pager['prev']  = url_rewrite($file, $params, $page_prev);
			$pager['next']  = '';
			$pager['last']  = '';
		} elseif($page_prev == '1' && $page == '1') {
			$pager['first'] = '';
			$pager['prev']  = '';
			$pager['next']  = url_rewrite($file, $params, $page_next);
			$pager['last']  = url_rewrite($file, $params, $page_count);
		} else {
			$pager['first'] = url_rewrite($file, $params, 1);
			$pager['prev']  = url_rewrite($file, $params, $page_prev);
			$pager['next']  = url_rewrite($file, $params, $page_next);
			$pager['last']  = url_rewrite($file, $params, $page_count);
		}
	}
    return $pager;
}

function get_pager($url, $param, $count, $page = 1, $size = 10)
{
    $size = intval($size);
    if($size < 1)$size = 10;
    $page = intval($page);
    if($page < 1)$page = 1;
    $count = intval($count);

    $page_count = $count > 0 ? intval(ceil($count / $size)) : 1;
    if ($page > $page_count)$page = $page_count;

    $page_prev  = ($page > 1) ? $page - 1 : 1;
    $page_next  = ($page < $page_count) ? $page + 1 : $page_count;

    $param_url = '?';
    foreach ($param as $key => $value)$param_url .= $key . '=' . $value . '&';

    $pager['url']        = $url;
    $pager['start']      = ($page-1) * $size;
    $pager['page']       = $page;
    $pager['size']       = $size;
    $pager['count']		 = $count;
    $pager['page_count'] = $page_count;

	if($page_count <= '1') {
	    $pager['first'] = $pager['prev']  = $pager['next']  = $pager['last']  = '';
	} else {
		if($page == $page_count) {
			$pager['first'] = $url . $param_url . 'page=1';
			$pager['prev']  = $url . $param_url . 'page=' . $page_prev;
			$pager['next']  = '';
			$pager['last']  = '';
		} elseif($page_prev == '1' && $page == '1') {
			$pager['first'] = '';
			$pager['prev']  = '';
			$pager['next']  = $url . $param_url . 'page=' . $page_next;
			$pager['last']  = $url . $param_url . 'page=' . $page_count;
		} else {
			$pager['first'] = $url . $param_url . 'page=1';
			$pager['prev']  = $url . $param_url . 'page=' . $page_prev;
			$pager['next']  = $url . $param_url . 'page=' . $page_next;
			$pager['last']  = $url . $param_url . 'page=' . $page_count;
		}
	}
    return $pager;
}

function url_rewrite($app, $params, $page = 0, $size = 0)
{
	global $CFG;
    static $rewrite = NULL;

    if($rewrite === NULL)$rewrite = intval($CFG['rewrite']);
    $args = array('aid'=> 0,'bid'=>'0','cid'=> 0,'vid'=> 0,'eid'=> '0','tid'=>'0','hid'=>'0' );
    @extract(array_merge($args, $params));
    $uri = '';
    switch($app)
    {
        case 'category':
            if (empty($cid) && empty($eid)) {
                return false;
            } else {
                if($rewrite) {
                    $uri = 'xk_' . intval($cid);
					//if(!empty($cid))$uri .= '-id-' . $eid;
					if(!empty($eid))$uri .= '-area-' . $eid;
                    if(!empty($page))$uri .= '-page-' . $page;
                }else{
					$uri = 'category.php?';
                    if($cid && $eid) {
						$uri .= 'id=' . $cid . '&area=' . $eid;
					} elseif ($cid && !$eid) {
						$uri .= 'id=' . $cid ;
					} elseif (!$cid && $eid) {
						$uri .= 'area=' . $eid ;
					}
                    if(!empty($page)) $uri .= '&page=' . $page;
                }
            }
        break;

        case 'view':
            if(empty($vid)) {
                return false;
            }else{
                $uri = $rewrite ? 'xk_info_' . $vid : 'view.php?id=' . $vid;
            }
        break;

		case 'about':
            if(empty($aid)) {
                return false;
            }else{
                $uri = $rewrite ? 'about-' . $aid : 'about.php?id=' . $aid;
            }
        break;

		case 'help':
            if($act=='list') {
                if($rewrite) {
					$uri = 'help';
                    if($tid)$uri .= '-list-' . $tid;
					if($page)$uri .= '-page-' . $page;
                }else{
					$uri = 'help.php?act=list';
					if($tid)$uri .= '&typeid=' . $tid;
                    if($page)$uri .= '&page=' . $page;
                }
            } elseif($act=='view' && $hid) {
                if($rewrite) {
                    $uri = 'help-view-' . $hid;
                }else{
                    $uri = 'help.php?act=view&id=' . $hid;
                }
            }
        break;

		case 'article':
            if($act=='list') {
                if($rewrite) {
					$uri = 'article';
                    if($iid)$uri .= '-list-' . $iid;
					if($page)$uri .= '-page-' . $page;
                }else{
					$uri = 'article.php?act=list';
					if($iid)$uri .= '&typeid=' . $iid;
                    if($page)$uri .= '&page=' . $page;
                }
            } elseif($act=='view' && $aid) {
                if($rewrite) {
                    $uri = 'article_' . $aid.'';
                }else{
                    $uri = 'article.php?act=view&id=' . $aid;
                }
            }
        break;

		case 'com':
            if($act=='list') {
                if($rewrite) {
					$uri = 'com';
					if(!empty($catid))$uri .= '-list-' . $catid;
					if(!empty($eid))$uri .= '-area-' . $eid;
					if(!empty($page))$uri .= '-page-' . $page;
				}else{
					$uri = 'com.php?act=list';
					if(!empty($catid))$uri .= '&catid=' . $catid;
					if(!empty($eid))$uri .= '&area=' . $eid;
					if(!empty($page))$uri .= '&page=' . $page;
				}
            } elseif($act=='view' && $comid) {
                if($rewrite) {
                    $uri = 'com-view-' . $comid.'';
                }else{
                    $uri = 'com.php?act=view&id=' . $comid;
                }
            }
        break;
   
        default:
            return false;
        break;
    }
    if($rewrite)$uri .= '.html';
    return $uri;
}

function iconvs($from_encoding, $to_encoding, $str_or_array)
{
	if(!is_array($str_or_array) && empty($str_or_array)) return "";
	$from_encoding = strtolower($from_encoding);
	$to_encoding = strtolower($to_encoding);
	$converarray = array();

	if($from_encoding == $to_encoding) return $str_or_array;
	if(($from_encoding == "big5" && $to_encoding == "gb2312")||($from_encoding == "gb2312" && $to_encoding == "big5")) $flag = false;
	else $flag = true;

	if(function_exists('mb_convert_encoding') && $to_encoding != 'pinyin' && $flag) {
		if(!is_array($str_or_array)) {
			return mb_convert_encoding($str_or_array, $to_encoding, $from_encoding);
		} else {
			foreach($str_or_array as $key => $val) {
				$converarray[$key] = mb_convert_encoding($val, $to_encoding, $from_encoding);
			}
			return $converarray;
		}
	} else if(function_exists('iconv') && $to_encoding != 'pinyin' &&$flag) {
		if(!is_array($str_or_array)) {
			return iconv($from_encoding, $to_encoding."//IGNORE", $str_or_array);
		} else {
			foreach($str_or_array as $key => $val) {
				$converarray[$key] = iconv($from_encoding, $to_encoding."//IGNORE", $val);
			}
			return $converarray;
		}
	} else {
		require_once BIANMPS_ROOT."include/convert.class.php";
		$chs = new chinese();
		$charset=array("utf8","gb2312","big5","unicode","pinyin");
		if(!in_array($from_encoding,$charset)) {
			return "The codepage-".$from_encoding." is not support!";
		} else if(!in_array($to_encoding,$charset)) {
			return "The codepage-".$to_encoding." is not support!";
		} else {
			$from_encoding = strtoupper($from_encoding);
			$to_encoding = strtoupper($to_encoding);
			if(!is_array($str_or_array)) {
				return $chs->Convert($from_encoding,$to_encoding,$str_or_array);
			} else {
				foreach($str_or_array as $key => $val) {
					$converarray[$key] = $chs->Convert($from_encoding,$to_encoding,$val);
				}
				return $converarray;
			}
		}
	}
}

function enddate($date)
{
	if($date > 0) {
		if($date > time()) {
			$a = round(($date-time())/86400);
			if($a<1) $a = 1;
			$day = "<font color=red>$a</font>��";
		} else {
			$day = '�ѹ���';
		}
	} else {
		$day = '������Ч';
	}
	
	return $day;
}

function template($file)
{
	global $CFG;
	
	$compiledfile = BIANMPS_ROOT.'data/compiled/'.$file.'.php';
	$tplfile = BIANMPS_ROOT.'/templates/'.$CFG['tplname'].'/'.$file.'.htm';
	if(!file_exists($compiledfile) || @filemtime($tplfile) > @filemtime($compiledfile)) {
		template_compile($tplfile, $compiledfile);
	}
	return $compiledfile;
}

function showmsg($msg,$url='goback')
{
    include template('show');
	exit();
}

/**
 *  ���ָ����׺��ģ�建�������ļ�
 *
 * @access  public
 * @param  string     $type  Ҫ���������
 * @param  string     $ext   ��Ҫɾ�����ļ�������������׺
 *
 * @return int        ����������ļ�����
 */
function clear_caches($type = 'phpcache', $ext = '')
{
    $dirs = array();
    $tmp_dir = 'data';
    
    if ($type=='phpcache') {
        $dirs = array(BIANMPS_ROOT . $tmp_dir . '/phpcache/');
    }  elseif ($type=='sqlcache') {
        $dirs = array(BIANMPS_ROOT . $tmp_dir . '/sqlcache/');
    } elseif ($type=='compiled') {
        $dirs = array(BIANMPS_ROOT . $tmp_dir . '/compiled/');
    }
    $str_len = strlen($ext);
    $count   = 0;

    foreach ($dirs AS $dir) {
        $folder = @opendir($dir);

        if ($folder === false) {
            continue;
        }
        while ($file = readdir($folder)) {
            if ($file == '.' || $file == '..' || $file == 'index.htm' || $file == 'index.html') {
                continue;
            }
            if (is_file($dir . $file)) {
                /* ������ļ������ж��Ƿ�ƥ�� */
                $pos = strrpos($file, '.');

                if ($str_len > 0 && $pos !== false) {
                    $ext_str = substr($file, 0, $pos);

                    if ($ext_str == $ext) {
                        if (@unlink($dir . $file)) {
                            $count++;
                        }
                    }
                } else {
                    if (@unlink($dir . $file)) {
                        $count++;
                    }
                }
            }
        }
        closedir($folder);
    }
    return $count;
}

function get_cat_array()
{
	global $db, $table;
	$data = read_cache('cat_array');
	if ($data === false) {
		$sql = "select catid,catname from {$table}category order by catid ";
		$res = $db->query($sql);
		while($row=$db->fetchrow($res)) {
			$cat_array[$row['catid']] = $row['catname'];
		}
		write_cache('cat_array', $cat_array);
	} else {
		$cat_array = $data;
	}
	return $cat_array;
}

function get_parent_cat()
{
	global $db,$table;
	
	$data = read_cache('parent_cat');
	if ($data === false) {
		$sql = "select catid,catname from {$table}category where parentid = '0' ";
		$res = $db->query($sql);
		while($row=$db->fetchrow($res)) {
			$parent_cat[] = $row;
		}
		write_cache('parent_cat', $parent_cat);
	} else {
		$parent_cat = $data;
	}
	return $parent_cat;
}

function get_cat_list()
{
	global $db,$table;
	
	static $cats = NULL;
	if ($cats === NULL) {
		$data = read_cache('cat_list');
		if ($data === false) {
			$sql = "select a.catid, a.catname, a.catorder as catorder ,b.catid as childid, b.catname as childname, b.catorder as chiorder from {$table}category as a left join {$table}category as b on b.parentid = a.catid where a.parentid = '0' order by catorder,a.catid,chiorder asc";
			$res = $db->getAll($sql);

			$cats = array();
			foreach ($res as $row) {
				$cats[$row['catid']]['catid']   = $row['catid'];
				$cats[$row['catid']]['catname'] = $row['catname'];
				$cats[$row['catid']]['caturl']  = url_rewrite('category',array('cid'=>$row[catid]));

				if(!empty($row['childid'])) {
					$cats[$row['catid']]['children'][$row['childid']]['id']   = $row['childid'];
					$cats[$row['catid']]['children'][$row['childid']]['name'] = $row['childname'];
					$cats[$row['catid']]['children'][$row['childid']]['url']  = url_rewrite('category',array('cid'=>$row[childid]));
				}
			}
			write_cache('cat_list', $cats);
		} else {
			$cats = $data;
		}
	}
	return $cats;
}


function get_cat_list_2($catid='')
{
	global $db,$table;
	
	static $cats = NULL;
	if ($cats === NULL) {
		$data = read_cache('cat_list2');
		if ($data === false) {
			$sql = "select * from {$table}category  where a.catid = '$catid' order by catid asc";
			$res = $db->getAll($sql);
			$cats = array();
			foreach ($res as $row) {
				$cats[$row['catid']]['catid']   = $row['catid'];
				$cats[$row['catid']]['catname'] = $row['catname'];
				$cats[$row['catid']]['caturl']  = url_rewrite('category',array('cid'=>$row[catid]));

				
			}
			write_cache('cat_list2', $cats);
		} else {
			$cats = $data;
		}
	}
	return $cats;
}



function cat_options($selectid='',$catid='')
{
	$cats = get_cat_list();
	if($catd){$cats = $cats[$catid];}
	foreach((array)$cats as $cat) {
		$option .= "<option value=$cat[catid] style='color:red;'";
		$option .= ($selectid == $cat['catid']) ? " selected='selected'" : '';
		$option .= ">$cat[catname]</option>";

		if(!empty($cat['children'])) {
			foreach($cat['children'] as $chi) {
				$option .= "<option value=$chi[id]";
				$option .= ($selectid == $chi['id']) ? " selected='selected'" : '';
				$option .= ">&nbsp;&nbsp;|--$chi[name]</option>";
			}
		}
	}
	return $option;
}

function get_cat_children($catid,$type='int')
{
	$cats = get_cat_list();
	$cat_children = $cats[$catid]['children'];
	if(is_array($cat_children)) {
		if($type=='int') {
			foreach($cat_children as $child) {
				$id .= $child['id'].',';
			}
			$result = substr($id,0,-1);
		} elseif($type=='array') {
			$result = $cat_children;
		}
	} else {
		if($type=='int') {
			$result = $catid;
		} elseif($type=='array') {
			$result = '';
		}
	}
	return $result;
}

function get_cat_info($catid)
{
	global $db,$table;
	
	$data = read_cache('cat_'.$catid);
	if ($data === false) {
		$sql = "select * from {$table}category where catid='$catid' ";
		$cat_info = $db->getRow($sql);
		write_cache('cat_'.$catid, $cat_info);
	} else {
		$cat_info = $data;
	}
	return $cat_info;
}

function get_area_array()
{
	global $db, $table;
	$data = read_cache('area_array');
	if ($data === false) {
		$sql = "select areaid,areaname from {$table}area order by areaid ";
		$res = $db->query($sql);
		while($row=$db->fetchrow($res)) {
			$area_array[$row['areaid']] = $row['areaname'];
		}
		write_cache('area_array', $area_array);
	} else {
		$area_array = $data;
	}
	return $area_array;
}

function get_parent_area()
{
	global $db,$table;
	
	$data = read_cache('parent_area');
	if ($data === false) {
		$sql = "select areaid,areaname from {$table}area where parentid = '0' ";
		$res = $db->query($sql);
		while($row=$db->fetchrow($res)) {
			$parent_area[] = $row;
		}
		write_cache('parent_area', $parent_area);
	} else {
		$parent_area = $data;
	}
	return $parent_area;
}

function get_area_list()
{
	global $db,$table;
	
	static $areas = NULL;
	if ($areas === NULL) {
		$data = read_cache('area_list');
		if ($data === false) {
			$sql = "select a.areaid, a.areaname, a.areaorder as catorder,b.areaid as childid, b.areaname as childname, b.areaorder as chiorder from {$table}area as a left join {$table}area as b on b.parentid = a.areaid where a.parentid = '$area' order by catorder,a.areaid,chiorder asC"; 
			$res = $db->getAll($sql);

			$areas = array();
			foreach ($res as $row) {
				$areas[$row['areaid']]['areaid']   = $row['areaid'];
				$areas[$row['areaid']]['areaname'] = $row['areaname'];
				$areas[$row['areaid']]['url']  = url_rewrite('category',array('eid'=>$row[areaid]));

				if($row['childid'] != NULL) {
					$areas[$row['areaid']]['children'][$row['childid']]['id']   = $row['childid'];
					$areas[$row['areaid']]['children'][$row['childid']]['name'] = $row['childname'];
					$areas[$row['areaid']]['children'][$row['childid']]['url']  = url_rewrite('category',array('eid'=>$row[childid]));
				}
			}
			write_cache('area_list', $areas);
		} else {
			$areas = $data;
		}
	}
	return $areas;
}

function area_options($selectid='')
{
	$area = get_area_list();
	foreach($area as $area) {
		$option .= "<option value=$area[areaid] style='color:red;'";
		$option .= ($selectid == $area['areaid']) ? " selected='selected'" : '';
		$option .= ">$area[areaname]</option>";

		if(!empty($area['children'])) {
			foreach($area['children'] as $chi) {
				$option .= "<option value=$chi[id]";
				$option .= ($selectid == $chi['id']) ? " selected='selected'" : '';
				$option .= ">&nbsp;&nbsp;|--$chi[name]</option>";
			}
		}
	}
	return $option;
}

function get_area_children($areaid,$type='int')
{
	$areas = get_area_list();
	$area_children = $areas[$areaid]['children'];
	if(is_array($area_children)) {
		if($type=='int') {
			if(is_array($area_children)) {
				foreach($area_children as $child) {
					$id .= $child['id'].',';
				}
			}
			$result = substr($id,0,-1);
		} elseif($type=='array') {
			$result = $area_children;
		}
	} else {
		if($type=='int') {
			$result = $areaid;
		} elseif($type=='array') {
			$result = '';
		}
	}
	return $result;
	
}

function get_area_info($areaid)
{
	global $db,$table;
	
	$data = read_cache('area_'.$areaid);
	if ($data === false) {
		$sql = "select * from {$table}area where areaid='$areaid' ";
		$area_info = $db->getRow($sql);
		write_cache('area_'.$areaid, $area_info);
	} else {
		$area_info = $data;
	}
	return $area_info;
}

function get_config()
{
	global $db,$table;

	$data = read_cache('webconfig');
    if ($data === false) {
		$sql = "select setname,value from {$table}config";
		$res = $db->query($sql);

		while($row=$db->fetchRow($res)) {
			$config[$row['setname']] = $row['value'];
			if($row['setname']=='qq' && $row['value']) {
				$config[$row['setname']] = explode('|', $row['value']);
			}
		}
		write_cache('webconfig', $config);
	} else {
		$config = $data;
	}
	return $config;
}

function get_link_list()
{
	global $db,$table;
	
	$result['image'] = array();
	$result['txt']  = array();
	
	$data = read_cache('link');
    if ($data === false) {
		$sql = "select * from {$table}link $where order by linkorder,id";
		$row = $db->getAll($sql);
		foreach($row as $link) {
			if($link['logo']) {
				$links['image'][] = $link;
			} else {
				$links['txt'][]  = $link;
			}
		}
		write_cache('link', $links);
	} else {
		$links = $data;
	}
	return $links;
}

function get_info($cat='',$area='',$num='10',$protype='',$listtype='',$len='20',$thumb='', $dateformat='y-m-d')
{
	global $db,$table;
	
	$where = "where is_check=1 and (enddate='0' or enddate >= ".time().")";

	if(!empty($cat)) {
		$where .= " and i.catid in ($cat)";
	}
	if(!empty($area)) {
		$where .= " and i.areaid in ($area)";
	}
	if($thumb=='1') {
		$where .= " and thumb != '' ";
	}

	if(!empty($protype)) {
		switch($protype) {
			case 'pro':
				$where .= " and is_pro >=".time();
			break;
			
			case 'top':
				$where .= " and is_top >=".time();
			break;
		}
	}

	if(!empty($listtype)) {
		switch($listtype) {
			case 'date':
				$order = " order by postdate desc";
			break;
			
			case 'click':
				$order = " order by click desc, id desc ";
			break;
		}
	}
	if(empty($order)) $order = "order by postdate desc";
	$limit = " LIMIT 0,$num ";
	$sql = "select i.id,i.title,i.postdate,i.thumb,i.description,i.catid,i.phone,i.areaid,i.click,c.catname,a.areaname from {$table}info as i left join {$table}category as c on i.catid = c.catid left join {$table}area as a on a.areaid = i.areaid $where $order $limit";
	$res = $db->query($sql);
	$info = array();
	while($row=$db->fetchRow($res)) {
		$row['title']    = cut_str($row['title'], $len);
		$row['postdate'] = date('m-d', $row['postdate']);
		$row['url']      = url_rewrite('view',array('vid'=>$row['id']));
		$row['caturl']   = url_rewrite('category',array('cid'=>$row['catid']));
		$row['areaurl']  = url_rewrite('category',array('eid'=>$row['areaid']));
		$info[]          = $row;
	}
	return $info;
}

function get_flash()
{
	global $db,$table;

	$data = read_cache('flash');
    if ($data === false) {
		$sql = "select * from {$table}flash order by flaorder,id";
		$res = $db->query($sql);
		$result = array();
		while($row = $db->fetchRow($res)) {
			$image .= $row['image'] . '|';
			$url   .= $row['url'] . '|';
		}
		if(!empty($image) && !empty($url)) {
			$flash['image'] = substr($image,0,-1);
			$flash['url']   = substr($url,0,-1);
		}
		write_cache('flash', $flash);
	} else {
		$flash = $data;
	}
	return $flash;
}

function get_nav()
{
	global $db,$table;
	
	$data = read_cache('nav');
    if ($data === false) {
		$sql = "select * from {$table}nav order by navorder";
		$nav = $db->getAll($sql);
		write_cache('nav', $nav);
	} else {
		$nav = $data;
	}
	return $nav;
}

function get_fac($num='20')
{
	global $db,$table;

	$data = read_cache('fac');
    if ($data === false) {
		$sql = "select * from {$table}facilitate order by listorder desc, updatetime desc limit $num";
		$fac = $db->getAll($sql);
		write_cache('fac', $fac);
	} else {
		$fac = $data;
	}
	return $fac;
}

function get_info_custom($infoid)
{
	global $db,$table;

	$sql = "select a.cusid, a.cusname, a.unit, g.cusvalue from {$table}cus_value as g left join {$table}custom as a on a.cusid = g.cusid where g.infoid = '$infoid' order by a.listorder, a.cusid";
    $res = $db->query($sql);
	$cus = array();
    while($row = $db->fetchRow($res)) {
		$arr['name']  = $row['cusname'];
		$arr['value'] = $row['cusvalue'];
		$arr['unit']  = $row['unit'];
		$cus[] = $arr;
    }
    return $cus;
}

function get_custom_info($cusid='')
{
	global $db,$table;
	
	$data = read_cache('custom');
	//$cusid = intval($cusid);
	if($data===false) {
		$sql = "select * from {$table}custom where cusid='$cusid' ";
		$res = $db->query($sql);
		while($row = $db->fetchrow($res)) {
			$custom_info[$row['cusid']] = $row;
		}
		write_cache('custom', $custom_info);
	} else {
		$custom_info = $data;
	}
	return $custom_info[$cusid];
}

function get_cat_custom($catid)
{
	global $db,$table;
	
	$data = read_cache('cat_custom_'.$catid);
    if ($data === false) {
		
		/*
		* ���Ȳ鿴�������ã����Ƿ�����̳и���������ԣ������������������ĸ�������
		*/
		$cat_info = $db->getOne("select parentid from {$table}category where catid='$catid'");
		if($cat_info['parentid']) {
			$parentid = $cat_info['parentid'];
			$sql = "select cusid, cusname, custype, value, search, listorder, unit from {$table}custom  where  catid = '$parentid' order by catid, listorder asc";
			$parent_cat_custom = $db->getAll($sql);
		}
		$sql = "select cusid, cusname, custype, value, search, listorder, unit from {$table}custom  where  catid = '$catid' order by catid, listorder asc";
		$cat_custom = $db->getAll($sql);
		if($parent_cat_custom)$cat_custom = array_merge($parent_cat_custom, $cat_custom);
		write_cache('cat_custom_'.$catid, $cat_custom);
	} else {
		$cat_custom = $data;
	}
	
	return $cat_custom;
}

function cat_post_custom($catid,$id='')
{
	global $db,$table;

	if(empty($catid))return array();
	if(!empty($id)) {
		$sql = "select c.*,v.* from {$table}custom as c left join {$table}cus_value as v on c.cusid=v.cusid left join {$table}info as i on i.id=v.infoid where i.id='$id' ";
		$res = $db->query($sql);
		while($row=$db->fetchrow($res)) {
			$info_cus[$row[cusid]] = $row;
		}
	}
    $customs = get_cat_custom($catid);
    if(empty($customs))return false;
	
    foreach ($customs as $key => $val)  {
		$info_cus_value = $info_cus[$val['cusid']];

        if ($val['custype'] == 0) {
            $val['html'] .= "<input name='cus_value[$val[cusid]]' type='text' value='" .htmlspecialchars($info_cus_value['cusvalue']). "' size='20' /> ".$val[unit];
        } elseif ($val['custype'] == 1) {
            $val['html'] .= '<select name="cus_value['.$val['cusid'].']">';
            $val['html'] .= '<option value="">��ѡ��</option>';
            $cusvalues = explode("\n", $val['value']);
            foreach($cusvalues as $opt) {
                $opt = trim(htmlspecialchars($opt));
                $val['html'] .= ($info_cus_value['cusvalue'] != $opt) ?
                    '<option value="' . $opt . '">' . $opt . '</option>' :
                    '<option value="' . $opt . '" selected="selected">' . $opt . '</option>';
            }
            $val['html'] .= "</select>$val[unit]";
        } elseif ($val['custype'] == 2) {
            $cusvalues = explode("\n", $val['value']);
			$info_cusvalue = explode(",", $info_cus_value['cusvalue']);
			
            foreach($cusvalues as $opt) {
                $opt = trim(htmlspecialchars($opt));
				$a = in_array($opt,$info_cusvalue) ?  "checked=checked" : '';
                $val['html'] .= '<input type="checkbox" value="' . $opt . '" name="cus_value['.$val['cusid'].'][]" '.$a.' >'. $opt;
            }
        }
		$result[$val['cusid']]['cusname'] = $val['cusname'];
		$result[$val['cusid']]['html'] = $val['html'];
		$result[$val['cusid']]['unit'] = $val['unit'];
    }
    return $result;
}

function cat_search_custom($catid='')
{
	global $db,$table;

	if(empty($catid))return array();
	$customs = get_cat_custom($catid);
	foreach($customs as $row) {
		if($row['search']=='0')continue;
		
		if($row['custype'] == '1' || $row['custype'] == '2') {
			$row['value'] = str_replace("\r", '', $row['value']);
			$options = explode("\n", $row['value']);
			$cusvalue = array();
			foreach($options as $opt) {
				$cusvalue[$opt] = $opt;
			}
			$custom[] = array(
				'id' => $row['cusid'],
				'cusname' => $row['cusname'],
				'options' => $cusvalue,
				'search' => $row['search'],
				'unit' => $row['unit'],
				'type' => $row['custype']
				);
		} else {
			$custom[] = array(
				'id' => $row['cusid'],
				'cusname' => $row['cusname'],
				'search' => $row['search'],
				'unit' => $row['unit'],
				'type' => $row['custype']
				);
		}
	}
	if($custom) {
		foreach($custom as $cus) {
			if($cus['type']=='0') {
				if($cus['search']=='2') {
					$cus['html'] = '<input name=custom['.$cus['id'].'][from] value="" type=text size=5 maxlength=5 > �� <input name=custom['.$cus['id'].'][to] type=text value="" size=5 maxlength=5 >';
				} else {
					$cus['html'] = '<input name="custom['.$cus['id'].']"  type="text" size="15" maxlength="120" />';
				}
			} elseif($cus['type']=='1') {
				$cus['html'] = "<select name=custom[$cus[id]]>
				<option value=0>��ѡ��</option>";
				foreach($cus['options'] as $opt) {
					$cus['html'] .= "<option value=$opt>$opt</option>";
				}
				$cus['html'] .= '</select>';
			} elseif($cus['type']=='2') {
				foreach($cus['options'] as $opt) {
					$opt = trim(htmlspecialchars($opt));
					$cus['html'] .= ' <input type="checkbox" value="' . $opt . '" name="custom['.$cus[id].'][]" >'. $opt;
				}
			}
			$result[] = $cus;
		}
	}
    return $result;
}

function ads_list($placeid='')
{
	global $db,$table,$CFG;

	if(empty($placeid))return'';
	$weburl = $CFG['weburl'];

	$sql = "select a.*,p.width,p.height from {$table}ads as a left join {$table}ads_place as p on a.placeid=p.placeid where a.placeid = '$placeid' ";
	$res = $db->query($sql);
	$ads = array();
	while($row=$db->fetchrow($res)) {
		$adscode = '';
		switch ($row['adstype'])
		{
			case '1':
				$adscode = "<a href=$row[adsurl] target=\"_blank\">" . nl2br(htmlspecialchars(addslashes($row['adscode']))). "</a>";
			break;

			case '2':
				$src = (strpos($row['adcode'], 'http://') === false && strpos($row['adcode'], 'https://') === false) ? $weburl . "/$row[adscode]" : $row['adscode'];
				$adscode = "<a href=" .$row['adsurl']. " target=_blank>" . "<img src=" . $src . " border=0 width= " .$row['width']. " height=" . $row['height'] . "alt=" . $row['adsname'] . " /></a>";
			break;

			case '3':
				$src = (strpos($row['adscode'], 'http://') === false && strpos($row['adscode'], 'https://') === false) ? $weburl . '/' . $row['adscode'] : $row['adscode'];
				$adscode = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="'.$row['width'].'" height="'.$row['height'].'"> <param name="movie" value="'.$src.'"><param name="quality" value="high"><embed src="'.$src.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="'.$row['width'].'" height="'.$row['height'].'"></embed></object>';
			break;

			case '4':
				$adscode = $row['adscode'];
			break;
		}
		$ads[] = $adscode;
	}
	include template('ads');
}

function template_compile($tplfile,$tplcachefile)
{
	$str = file_get_contents($tplfile);
	$str = template_parse($str);
	$strlen = file_put_contents($tplcachefile, $str);
	@chmod($tplcachefile, 0777);
	return $strlen;
}

function template_parse($tpl)
{
	$tpl = preg_replace("/([\n\r]+)\t+/s","\\1",$tpl);
	$tpl = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}",$tpl);
	$tpl = preg_replace("/\{template\s+(.+)\}/","\n<?php include template(\\1); ?>\n",$tpl);
	$tpl = preg_replace("/\{include\s+(.+)\}/","\n<?php include \\1; ?>\n",$tpl);
	$tpl = preg_replace("/\{php\s+(.+)\}/","\n<?php \\1?>\n",$tpl);
	$tpl = preg_replace("/\{if\s+(.+?)\}/","<?php if(\\1) { ?>",$tpl);
	$tpl = preg_replace("/\{else\}/","<?php } else { ?>",$tpl);
	$tpl = preg_replace("/\{elseif\s+(.+?)\}/","<?php } elseif (\\1) { ?>",$tpl);
	$tpl = preg_replace("/\{\/if\}/","<?php } ?>",$tpl);
	$tpl = preg_replace("/\{loop\s+(\S+)\s+(\S+)\}/","<?php if(is_array(\\1)) foreach(\\1 AS \\2) { ?>",$tpl);
	$tpl = preg_replace("/\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}/","\n<?php if(is_array(\\1)) foreach(\\1 AS \\2 => \\3) { ?>",$tpl);
	$tpl = preg_replace("/\{\/loop\}/","\n<?php } ?>\n",$tpl);
	$tpl = preg_replace("/\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\(([^{}]*)\))\}/","<?php echo \\1;?>",$tpl);
	$tpl = preg_replace("/\{\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\(([^{}]*)\))\}/","<?php echo \\1;?>",$tpl);
	$tpl = preg_replace("/\{(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/","<?php echo \\1;?>",$tpl);
	$tpl = preg_replace("/\{(\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)\}/es", "addquote('<?php echo \\1;?>')",$tpl);
	$tpl = preg_replace("/\{([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)\}/s", "<?php echo \\1;?>",$tpl);
	$tpl = "<?php if(!defined('IN_BIANMPS'))die('Access Denied'); ?>".$tpl;
	return $tpl;
}

function addquote($var)
{
	return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
}

if (!function_exists('file_get_contents'))
{
    function file_get_contents($file)  {
        if (($fp = @fopen($file, 'rb')) === false) {
            return false;
        } else {
            $fsize = @filesize($file);
            if ($fsize) {
                $contents = fread($fp, $fsize);
            } else {
                $contents = '';
            }
            fclose($fp);

            return $contents;
        }
    }
}

if (!function_exists('file_put_contents'))
{
    define('FILE_APPEND', 'FILE_APPEND');

    function file_put_contents($file, $data, $flags = '') {
        $contents = (is_array($data)) ? implode('', $data) : $data;

        if ($flags == 'FILE_APPEND') {
            $mode = 'ab+';
        } else {
            $mode = 'wb';
        }

        if (($fp = @fopen($file, $mode)) === false) {
            return false;
        } else {
            $bytes = fwrite($fp, $contents);
            fclose($fp);

            return $bytes;
        }
    }
}

function read_cache($filename)
{
    $result = array();
    if (!empty($result[$filename])) {
        return $result[$filename];
    }
    $filepath = BIANMPS_ROOT . 'data/phpcache/' . $filename . '.php';
    if (file_exists($filepath)) {
        include_once($filepath);
        $result[$filename] = $data;
        return $result[$filename];
    } else {
        return false;
    }
}

function write_cache($filename, $val)
{
    $filepath = BIANMPS_ROOT . 'data/phpcache/' . $filename . '.php';
    $content  = "<?php\r\n";
    $content .= "\$data = " . var_export($val, true) . ";\r\n";
    $content .= "?>";
    file_put_contents($filepath, $content, LOCK_EX);
}

function get_url()
{
	$php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
	$php_domain = $_SERVER['SERVER_NAME'];
	$php_agent = $_SERVER['HTTP_USER_AGENT'];
	$php_referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
	$php_scheme = $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	$php_reuri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
	$php_port = $_SERVER['SERVER_PORT'] == '80' ? '' : ':'.$_SERVER['SERVER_PORT'];
	$host_url = $php_scheme . $php_domain . $php_port;
	$site_url = $host_url . substr($php_self, 0, strrpos($php_self, '/'));
	$site_url = str_replace('/install', '', $site_url);
	$site_url = str_replace('/admin', '', $site_url);
	return $site_url;
}

function get_domain($url)
{
	$pattern = "/[\w-]+\.[\w-]+\.(com|net|org|gov|cc|biz|info|cn)(\.(cn|hk))*/";
	preg_match($pattern, $url, $matches);

	if(count($matches) > 0)  {
		return $matches[0];
	} else {
		$rs = parse_url($url);
		$main_url = $rs["host"];
		if(!strcmp(long2ip(sprintf("%u",ip2long($main_url))),$main_url)) {
			return $main_url;
		} else {
			$arr = explode(".",$main_url);
			$count=count($arr);
			$endArr = array("com","net","org","3322");//com.cn  net.cn �����
			
			if (in_array($arr[$count-2],$endArr)) {
				$domain = $arr[$count-3].".".$arr[$count-2].".".$arr[$count-1];
			} else {
				$domain =  $arr[$count-2].".".$arr[$count-1];
			}
			return $domain;
		}
	}
}

function onlyarea($postarea)
{
	global $db,$table,$CFG;

	if(!empty($CFG['onlyarea'])) {
		$onlyarea = explode('|', $CFG['onlyarea']);
		foreach($onlyarea as $val) {
			if(strstr($postarea, $val)) $count++;
		}
		if(empty($count)) {
			showmsg("ϵͳ�ж����IP��ַ����".$CFG['onlyarea']."���������������ϵ�ͷ�");
		}
	}
}

function getPostArea($ip)
{
	global $charset;
	
	require_once BIANMPS_ROOT . 'include/ip.class.php';
	$cha = new ip();
	$address = $cha->getaddress($ip);
	$postarea = $address["area1"].$address["area2"];
	if($charset == 'utf-8') $postarea = iconvs('gb2312','utf-8', $postarea);
	return $postarea;
}

function check_words($who=array())
{
	global $CFG;

	if(!empty($CFG['banwords'])) {
		$ban = explode('|',$CFG['banwords']);
		$count = count($ban);
		for($i=0;$i<$count;$i++){
			foreach($who as $val) {
				if(strstr($val,$ban[$i])){
					showmsg('����������Ϣ����Υ������');
				}
			}
		}
	}
}

function CreateSmallImage( $OldImagePath, $NewImagePath, $NewWidth=154, $NewHeight=134) 
{
	// ȡ��ԭͼ�����ͼ����Ϣgetimagesize����˵����0(��),1(��),2(1gif/2jpg/3png),3(width="638" height="340")
	$OldImageInfo = getimagesize($OldImagePath); 
	if ( $OldImageInfo[2] == 1 ) $OldImg = @imagecreatefromgif($OldImagePath); 
	elseif ( $OldImageInfo[2] == 2 ) $OldImg = @imagecreatefromjpeg($OldImagePath); 
	else $OldImg = @imagecreatefrompng($OldImagePath);

	// ����ͼ��,imagecreate����˵������,�� 
	$NewImg = imagecreatetruecolor( $NewWidth, $NewHeight ); 

	//����ɫ��,������ͼ��,red(0-255),green(0-255),blue(0-255) 
	$black = ImageColorAllocate( $NewImg, 0, 0, 0 ); //��ɫ 
	$white = ImageColorAllocate( $NewImg, 255, 255, 255 ); //��ɫ 
	$red   = ImageColorAllocate( $NewImg, 255, 0, 0 ); //��ɫ 
	$blue  = ImageColorAllocate( $NewImg, 0, 0, 255 ); //��ɫ 
	$other = ImageColorAllocate( $NewImg, 0, 255, 0 );

	//��ͼ�θ߿��� 
	$WriteNewWidth = $NewHeight*($OldImageInfo[0] / $OldImageInfo[1]); //Ҫд��ĸ߶� 
	$WriteNewHeight = $NewWidth*($OldImageInfo[1] / $OldImageInfo[0]); //Ҫд��Ŀ�� 
	
	//��������ͼƬ������ʧ������������������
	if ($OldImageInfo[0] / $NewWidth > $org_info[1] / $NewHeight) {
		$WriteNewWidth  = $NewWidth;
		$WriteNewHeight  = $NewWidth / ($OldImageInfo[0] / $OldImageInfo[1]);
	} else {
		/* ԭʼͼƬ�Ƚϸߣ����Ը߶�Ϊ׼ */
		$WriteNewWidth  = $NewHeight * ($OldImageInfo[0] / $OldImageInfo[1]);
		$WriteNewHeight = $NewHeight;
	}
	//��$NewHeightΪ����,����¿�С�ڻ����$NewWidth,����� 
	if ( $WriteNewWidth <= $NewWidth ) {
		$WriteNewWidth = $WriteNewWidth; //���жϺ�Ĵ�С 
		$WriteNewHeight = $NewHeight; //�ù涨�Ĵ�С 
		$WriteX = floor( ($NewWidth-$WriteNewWidth) / 2 ); //����ͼƬ��д���Xλ�ü��� 
		$WriteY = 0; 
	} else { 
		$WriteNewWidth = $NewWidth; // �ù涨�Ĵ�С 
		$WriteNewHeight = $WriteNewHeight; //���жϺ�Ĵ�С 
		$WriteX = 0; 
		$WriteY = floor( ($NewHeight-$WriteNewHeight) / 2 ); //����ͼƬ��д���Xλ�ü��� 
	} 

	//��ͼ����С��,д�뵽��ͼ����(����),imagecopyresized����˵�����¾�, ��xy��xy, �¿�߾ɿ�� 
	@imagecopyresampled( $NewImg, $OldImg, $WriteX, $WriteY, 0, 0, $WriteNewWidth, $WriteNewHeight, $OldImageInfo[0], $OldImageInfo[1] ); 

	//�����ļ� 
	@imagegif( $NewImg, $NewImagePath ); 

	//����ͼ�� 
	@imagedestroy($NewImg); 
}

function splitword()
{
	echo '<script type="text/javascript">
			var laststring = "";
			function splitword(obj)
			{
				if(obj.value == "" || obj.value == laststring)
				{
					return false;
				}
				laststring = obj.value;
				document.myframe_form.string.value = obj.value;
				document.myframe_form.submit();
				return true;
			}
		 </script>
		 <iframe id="myframe" name="myframe" width="0" height="0"></iframe>
		 
		 <form name="myframe_form" action="splitword.php" method="get" target="myframe">
		  <input type="hidden" name="string" />
		  <input type="hidden" name="action" value="get_keywords" />
		  <input type="hidden" name="charset" value="gbk" />
		 </form>';
}

function login($username, $password)
{
	global $db,$table,$CFG;

	if (check_user($username, $password) > 0) {
		set_session($username);
		set_cookie($username);
		return true;
	} else {
		set_session();
		set_cookie();
		return false;
	}
}

function check_user($username, $password = '')
{
	global $db,$table,$CFG;
	if($password == '') {
		$sql = "select userid FROM {$table}member WHERE username = '$username'";
	} else {
		$sql = "select userid FROM {$table}member WHERE username = '$username' AND password ='$password'";
	}
	return $db->getOne($sql);
}

function logout()
{
	set_session();
	set_cookie();
}

function set_session ($username='')
{
	global $db,$table,$CFG;

	if (empty($username)) {
		$_SESSION['userid']   = '';
		$_SESSION['username']  = '';
		$_SESSION['password']  = '';
	} else {
		$sql = "SELECT userid, password, email , lastlogintime , sendmailtime FROM {$table}member WHERE username='$username' LIMIT 1";
		$row = $db->getRow($sql);

		if($row) {
			$_SESSION['userid']   = $row['userid'];
			$_SESSION['username']  = $username;
			$_SESSION['password']  = $row['password'];
		}
		$time = time();
		$ip = get_ip();
		$db->query("UPDATE {$table}member SET lastlogintime='$time',lastloginip='$ip' where username = '$username' ");
	}
}

function set_cookie($username='', $remember= null )
{
	if (empty($username)) {
		$time = time() - 3600;
		setcookie("userid",  '', $time);            
		setcookie("password", '', $time);

	} elseif ($remember) {
		$time = time() + 3600 * 24 * 15;

		setcookie("username", $username, $time);
		$sql = "SELECT userid, password FROM {$table}member WHERE username='$username' LIMIT 1";
		$row = $db->getRow($sql);
		if ($row) {
			setcookie("userid", $row['userid'], $time);
			setcookie("password", $row['password'], $time);
		}
	}
}

function register($username, $password, $email)
{
	global $db,$table,$CFG;

	if (check_user($username) > 0) {
		showmsg("�û��� $username �Ѿ�����");
	}
	$sql = "select userid FROM {$table}member  WHERE email = '$email'";
	if ($db->getOne($sql) > 0) {
		showmsg("���� $email �Ѿ�����");
	}

	$time = time();
	$ip = get_ip();
	$status = $CFG['reg_check'] == 1 ? 0 : 1;
	$sql = "INSERT INTO {$table}member (username,password,email,registertime,registerip,lastlogintime,status) VALUES ('$username','$password','$email','$time','$ip','$time', '$status')";
	$res = $db->query($sql);

	if($res) {
		set_session($username);
		set_cookie($username);
		return true;
	} else {
		set_session();
		set_cookie();
		return false;
	}
}

/* ����UCenter���� */
function uc_call($func, $params=null)
{
    restore_error_handler();
    if (!function_exists($func)) {
        include_once(BIANMPS_ROOT.'uc_client/client.php');
    }
    $res = call_user_func_array($func, $params);
    set_error_handler('exception_handler');
    return $res;
}

function get_ver()
{
	global $db,$table;

	$data = read_cache("ver");
	if($data === false) {
		$sql = "select * from {$table}ver order by vid";
		$res = $db->query($sql);
		while($r = $db->fetchrow($res)) {
			$ver[$r['vid']] = $r;
		}
		write_cache('ver', $ver);
	} else {
		$ver = $data;
	}
	return $ver;
}

//���ȡ����֤����
function get_one_ver()
{
	$ver = get_ver();
	$verf = array_rand($ver);
	$verf = $ver[$verf];
	return $verf;
}

// ��֤�ش����� $vid:����id $answer:�����
function check_ver($vid,$answer='')
{
	if($answer=='') showmsg('�ش���Ϊ��');
	$ver = get_ver();
	$verf = $ver[$vid];
	if($answer != $verf['answer'])showmsg('������֤�ش����');
}

function get_here($here_arr=array())
{
	$here = '<a href="'.PHPMPS_PATH.'">��ҳ</a>';
	foreach($here_arr as $val) {
		if(!empty($val['url']) && !empty($val['name'])) {
			$here .= ' -> <a href="'.$val['url'].'">' . $val['name'] . '</a>';
		} elseif (empty($val['url']) && !empty($val['name'])) {
			$here .= ' -> '. $val['name'];
		}
	}
	return $here;
}

//�������
function inserttable($tablename, $insertsqlarr, $returnid=0, $replace = false, $silent=0) 
{
	global $db,$table;

	$insertkeysql = $insertvaluesql = $comma = '';
	foreach ($insertsqlarr as $insert_key => $insert_value) {
		$insertkeysql .= $comma.'`'.$insert_key.'`';
		$insertvaluesql .= $comma.'\''.$insert_value.'\'';
		$comma = ', ';
	}
	$method = $replace?'REPLACE':'INSERT';
	$db->query($method.' INTO '.$table.$tablename.' ('.$insertkeysql.') VALUES ('.$insertvaluesql.')', $silent?'SILENT':'');
	if($returnid && !$replace) {
		return $db->insert_id();
	}
}

//��������
function updatetable($tablename, $setsqlarr, $wheresqlarr, $silent=0) 
{
	global $db,$table;

	$setsql = $comma = '';
	foreach ($setsqlarr as $set_key => $set_value) {
		if(is_array($set_value)) {
			$setsql .= $comma.'`'.$set_key.'`'.'='.$set_value[0];
		} else {
			$setsql .= $comma.'`'.$set_key.'`'.'=\''.$set_value.'\'';
		}
		$comma = ', ';
	}
	$where = $comma = '';
	if(empty($wheresqlarr)) {
		$where = '1';
	} elseif(is_array($wheresqlarr)) {
		foreach ($wheresqlarr as $key => $value) {
			$where .= $comma.'`'.$key.'`'.'=\''.$value.'\'';
			$comma = ' AND ';
		}
	} else {
		$where = $wheresqlarr;
	}
	$db->query('UPDATE '.$table.$tablename.' SET '.$setsql.' WHERE '.$where, $silent?'SILENT':'');
}

function html_select($name, $arr, $selectid='')
{
	$option = "<select name=\"$name\" id=\"$name\">";
	foreach($arr as $key=>$val) {
		$option .= "<option value=$key";
		$option .= ($selectid == $key) ? " selected='selected'" : '';
		$option .= ">$val</option>";
	}
	$option .= "</select>";
	return $option;
}

function member_info($data,$type='1')
{
	global $db,$table;
	
	if($type=='1') {
		$userid = intval($data);
		$info = $db->getRow("select * from {$table}member where userid='$userid' ");
	} elseif($type=='2') {
		$username = trim($data);
		$info = $db->getRow("select * from {$table}member where username='$username' ");
	}
	return $info;
}

function send_pwd_email($userid, $username, $email, $code)
{
    if (empty($userid) || empty($username) || empty($email) || empty($code)) {
        header("Location: member.php?act=get_password\n");
        exit;
    }
    $reset_email = PHPMPS_PATH . 'member.php?act=get_password&userid=' . $userid . '&code=' . $code;

	$send_date = date('Y-m-d', time());
	$content = "{$username}���ã�<br><br>���Ѿ��������������õĲ�����������������(���߸��Ƶ����������):<br><br><a href=".$reset_email." target=\"_blank\">".$reset_email."</a><br><br>��ȷ���������������ò�����<br><br>".$send_date;
	require_once BIANMPS_ROOT.'include/mail.inc.php';
    if (sendmail($email, $CFG['webname'].'-�����һ��ʼ�', $content)) {
        return true;
    } else {
        return false;
    }
}

function get_pay_setting()
{
	global $db,$table;
	$data = read_cache('pay_setting');
	if($data === false) {
		$sql = "select * from {$table}payment order by id";
		$res = $db->query($sql);
		while($row = $db->fetchrow($res)) {
			$pay[$row['paycenter']] = $row;
		}
		write_cache('pay_setting', $pay);
	} else {
		$pay = $data;
	}
	return $pay;
}

function get_about()
{
	global $db,$table;
	$sql = "SELECT id,title FROM {$table}about WHERE is_show=1 ORDER BY id ASC";
	$res = $db->query($sql);
	while($row = $db->fetchrow($res)) {
		$row['url'] = url_rewrite('about', array('aid'=>$row['id']));
		$abouts[] = $row;
	}
	return $abouts;
}

function getInfo($infoid)
{
	global $db, $table;
	$infoid = intval($infoid);
	if(empty($infoid)) return '';
	$data = $db->getRow("SELECT * FROM {$table}info WHERE id='$infoid'");
	return $data;
}

function addInfo($items, $cusvalue)
{
	global $db, $table;
	if(empty($items)) return '';
	$id = inserttable('info', $items, 1);

	if(isset($cusvalue)) {
		$infoid = $id;
        $cus_value_list = array();

        foreach((array)$cusvalue as $key => $val) {
			if(is_array($val)) {
				$cus_value = implode(",", $val);
			} else {
				$cus_value = $val;
			}
            if(!empty($cus_value)) {
                $cus_value_list[$key] = $cus_value;
            }
        }
        foreach($cus_value_list as $cusid => $cus_value) {
			$db->query("INSERT INTO {$table}cus_value (cusid, infoid, cusvalue) VALUES ('$cusid', '$infoid', '$cus_value')");
        }
    }
	return $id;
}

function editInfo($items, $cusvalue, $id)
{
	global $db, $table;
	if(empty($items)) return '';
	$id = updatetable('info', $items, " id='$id'");
	
	//����������
	if (!empty($cusvalue)) {

		$cus_value_list = array();
		$res = $db->query("SELECT * FROM {$table}cus_value WHERE infoid = '$id'");
		while($row = $db->fetchRow($res)) {
			$cus_value_list[$row['cusid']][$row['cusvalue']] = array('query' => 'delete', 'id' => $row['id']);
		}

		foreach((array)$cusvalue AS $key => $val) {
			
			if(is_array($val)) $val=implode(",", $val);
			$cusvalue = $val;

			if(!empty($cusvalue)) {
				if (isset($cus_value_list[$key][$cusvalue])) {
					$cus_value_list[$key][$cusvalue]['query'] = 'update';
				} else {
					$cus_value_list[$key][$cusvalue]['query'] = 'insert';
				}
			}
		}

		foreach ((array)$cus_value_list as $cusid => $value_list) {
			foreach ((array)$value_list as $cusvalue => $infos) {
				if ($infos['query'] == 'insert') {
				   $sql = "INSERT INTO {$table}cus_value (cusid, infoid, cusvalue) VALUES ('$cusid', '$infoid', '$cusvalue')";
				} elseif ($infos['query'] == 'delete') {
					$sql = "DELETE FROM {$table}cus_value WHERE id = '$infos[id]' LIMIT 1";
				} elseif ($infos['query'] == 'update') {
					$sql = "UPDATE {$table}cus_value SET cusvalue='$cusvalue' WHERE id='$infos[id]' ";
				}
				$db->query($sql);
			}
		}
	}
	return true;
}

function delInfo($id)
{
	global $db, $table;

	if(empty($id)) showmsg('ȱ�ٲ���');
	$db->query("DELETE FROM {$table}comment WHERE infoid IN ($id)");
	
	$thumb = $db->getAll("SELECT thumb FROM {$table}info WHERE id IN ($id)");
	foreach((array)$thumb AS $val){
		if($val != '' && is_file(BIANMPS_ROOT.$val['thumb'])){
			@unlink(BIANMPS_ROOT.$val['thumb']);
		}
	}
	$image = $db->getAll("SELECT path FROM {$table}info_image WHERE infoid IN ($id)");
	foreach((array)$image AS $val){
		if($val != '' && is_file(BIANMPS_ROOT.$val['path'])){
			@unlink(BIANMPS_ROOT.$val['path']);
		}
	}
	$db->query("DELETE FROM {$table}info_image WHERE infoid IN ($id)");
	$db->query("DELETE FROM {$table}cus_value WHERE infoid IN ($id)");
	$db->query("DELETE FROM {$table}report WHERE info IN ($id)");
	$db->query("DELETE FROM {$table}info WHERE id IN ($id)");

	return true;
}

function getUserGlod($userid)
{
	global $db, $table;
	$sql = "SELECT glod FROM {$table}member WHERE userid='$userid'";
	$glod = $db->getOne($sql);
	return $glod;
}

function getCreditTimes($username, $type)
{
	global $db, $table;
	$credit_times = $db->getOne("SELECT COUNT(*) FROM {$table}pay_exchange WHERE username='$_username' AND addtime>".mktime(0,0,0)." AND note='$type' ");
	return $credit_times;
}

function get_new_comment($num='5')
{
	global $db,$table;
	
	$sql = "select c.*,i.title from {$table}comment as c left join {$table}info as i on i.id=c.infoid where c.is_check=1 group by infoid order by c.id desc,c.postdate desc limit $num";
	$res = $db->query($sql);
	while($row=$db->fetchrow($res)) {
		$row[linkurl] = "comment.php?id=$row[id]";
		$row[title] = cut_str($row[title],'15');
		$row[content] = cut_str($row[content],'15').'...';
		$row[username] = $row[username]?$row[username]:'��վ����';
		$row[url] = url_rewrite('view',array('vid'=>$row['infoid']));
		$comments[] = $row;
	}
	return $comments;
}

function get_index_help($num='5')
{
	global $db,$table;

	$sql = "select id,title from {$table}help where is_index=1 order by addtime asc limit $num ";
	$helps = $db->getAll($sql);
	$result = array();
	foreach((array)$helps as $row) {
		$row['url'] = url_rewrite('help',array('act'=>'view','hid'=>$row['id']));
		$result[] = $row;
	}
	return $result;
}

function get_index_com($num='7')
{
	global $db,$table;
	$sql = "select * from {$table}com where thumb!='' order by comid desc limit $num";
	$res = $db->query($sql);
	while($row=$db->fetchrow($res)) {
		$row['sname'] = cut_str($row['comname'],12);
		$row['sintroduce'] = cut_str($row['introduce'],22);
		$row['postdate'] = date('m-d', $row['postdate']);
		$row['address'] = cut_str($row['address'],16);
		$row['url'] = url_rewrite('com', array('act'=>'view', 'comid'=>$row['comid']));
		$data[] = $row;	
	}
	return $data;
}



function get_index_article($num='5')
{
	global $db,$table;
	
	$sql = "select * from {$table}article  order by id desc limit $num";
	$res = $db->query($sql);
	while($row=$db->fetchrow($res)) {
		$row['ctitle'] = cut_str($row['title'], 19);
		$row['pdate'] = date('m-d', $row['addtime']);
		$row['pdate2'] = date('y-m-d', $row['addtime']);
		$row['url'] = url_rewrite('article', array('aid'=>$row['id'],'act'=>'view'));
		$data[] = $row;
	}
	return $data;
}


function get_top_info($catids, $top_type)
{ global $db,$table;
if(empty($catids)) return '';
$sql = "select id,title,postdate,enddate,c.catname,a.areaname,thumb,i.description,i.click from {$table}info as i left join {$table}category as c on c.catid=i.catid left join {$table}area as a on a.areaid=i.areaid where is_top>=".time()." and top_type='$top_type' and is_check=1 and i.catid in ($catids) order by postdate desc ";
$res = $db->query($sql);
$top_info = array();
while($row=$db->fetchrow($res)) {
$row['url'] = url_rewrite('view',array('vid'=>$row['id']));
$row['postdate'] = date('y��m��d��', $row['postdate']);
$row['intro'] = cut_str($row['description'], 50);
$row['lastdate'] = enddate($row['enddate']);
$top_info[$row['id']] = $row;
 }

 if($top_info) {
foreach($top_info as $article) {
 $infoid .= $article['id'].',';
}
 $infoid = substr($infoid,0,-1);
 $info_custom = get_infos_custom($infoid);
foreach($top_info as $key=>$article) {
$top_info[$key]['custom'] = is_array($info_custom[$key]) ? $info_custom[$key] : array();
}
}
return $top_info;
}
//ȡ�ö����Ϣ�ĸ�������
function get_infos_custom($infoid)
{
	global $db, $table;

	$sql = "select c.cusname,v.cusid,v.infoid,v.cusvalue from {$table}custom as c left join {$table}cus_value as v on v.cusid=c.cusid where v.infoid in ($infoid)";
	$res = $db->query($sql);
	while($row=$db->fetchrow($res)) {
		$data[$row['infoid']][$row['cusid']]['cusname'] = $row['cusname'];
		$data[$row['infoid']][$row['cusid']]['cusvalue'] = $row['cusvalue'];
	}
	return $data;
}

//�ж��ύ�Ƿ���ȷ
function submitcheck($var) {
	if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST[$var])) {
		if((empty($_SERVER['HTTP_REFERER']) || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST']))) {
			return true;
		} else {
			die('post error');
		}
	} else {
		return false;
	}
}

function checkInfoUser($id, $password) {
	global $db, $table, $_userid;
	if(empty($_userid)) {
		$password = trim($password);
		$pass = $db->getOne("SELECT password FROM {$table}info WHERE id='$id' LIMIT 1");
		if(empty($pass))showmsg('����Ϣû���������룬����ϵ�ͷ�ɾ����');
		if($password != $pass)showmsg('�������');
	} else {
		$sql = "SELECT userid FROM {$table}info WHERE id='$id' ";
		$infouserid = $db->getOne($sql);
		if($infouserid!=$_userid)showmsg('����Ϣ������������');
	}
	
}

/* ȡ��Υ������ */
function get_censor()
{
	global $CFG;
	$censors = $banned = $banwords = array();
	
	$data = read_cache('censor');
	if ($data === false) {
		//ȡ������
		$censorarr = explode("|", $CFG['banwords']);
		foreach($censorarr as $censor) {

			$censor = trim($censor);
			if(empty($censor)) continue;
			
			//����ÿ��������滻
			if(strstr($censor, '=')) {
				list($find, $replace) = explode('=', $censor);
			} else {
				$find = $censor;
				$replace = '*';
			}
			$findword = $find;
			//����{}���滻���������ʽ
			$find = preg_replace("/\\\{(\d+)\\\}/", ".{0,\\1}", preg_quote($find, '/'));
			switch($replace) {
				case '{BANNED}':
					$banwords[] = preg_replace("/\\\{(\d+)\\\}/", "*", preg_quote($findword, '/'));
					$banned[] = $find;
					break;

				default:
					$censors['filter']['find'][] = '/'.$find.'/i';
					$censors['filter']['replace'][] = $replace;
					break;
			}
		}
		//��ֹ����ʱ�����Ĵ���
		if($banned) {
			$censors['banned'] = '/('.implode('|', $banned).')/i';
			$censors['banword'] = implode(', ', $banwords);
		}
	
		write_cache('censor', $censors);
	} else {
		$censors = $data;
	}
	return $censors;
}

/* 
 * ��֤�ֻ��滻�������Ƿ����Υ������
 * @param string $string Ҫ��֤���滻���ַ���
 * @return bool
*/
function censor($string) {
	$censor = get_censor();
	if($censor) {
		//��������
		if($censor['banned'] && preg_match($censor['banned'], $string)) {
			$string = false;
		} else {
			$string = empty($censor['filter']) ? $string : @preg_replace($censor['filter']['find'], $censor['filter']['replace'], $string);
		}
	}
	return $string;
}

//����ͷ��
function avatar($uid, $size='small', $returnsrc = FALSE) {
	global $CFG;
	
	include BIANMPS_ROOT.'include/uc.inc.php';
	$size = in_array($size, array('big', 'middle', 'small')) ? $size : 'small';
	$avatarfile = avatar_file($uid, $size);
	return $returnsrc ? UC_API.'/data/avatar/'.$avatarfile : '<img src="'.UC_API.'/data/avatar/'.$avatarfile.'" onerror="this.onerror=null;this.src=\''.UC_API.'/images/noavatar_'.$size.'.gif\'">';
}

//�õ�ͷ��
function avatar_file($uid, $size) {
	global $CFG;

	$type = empty($CFG['avatarreal'])?'virtual':'real';
	$var = "avatarfile_{$uid}_{$size}_{$type}";
	if(empty($_SGLOBAL[$var])) {
		$uid = abs(intval($uid));
		$uid = sprintf("%09d", $uid);
		$dir1 = substr($uid, 0, 3);
		$dir2 = substr($uid, 3, 2);
		$dir3 = substr($uid, 5, 2);
		$typeadd = $type == 'real' ? '_real' : '';
		$_SGLOBAL[$var] = $dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).$typeadd."_avatar_$size.jpg";
	}
	return $_SGLOBAL[$var];
}
?>