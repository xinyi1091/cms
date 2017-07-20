<?php


function tpl($file)
{
	$file = BIANMPS_ROOT.'wap/templates/'.$file.'.htm';
    return $file;
}

function encode_output($str)
{
	global $charset;

    if ($charset != 'gb2312')
    {
        $str = iconvs($charset, 'gb2312', $str);
    }
    return strip_tags($str);
}


function show_m($msg,$url='goback')
{
	include tpl('show');
	exit();
}



?>