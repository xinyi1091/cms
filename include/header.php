<?php
define('IN_BIANMPS', true);

$nav = get_nav(); //����
$cats_list  = get_cat_list();
$areas_list = get_area_list();

$area_option = area_options(); //���������˵�
$cat_option  = cat_options(); //���������˵�
$about = get_about();
?>