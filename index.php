<?php
// $time_start = microtime(true);
#
##
### ادراج الاعدادات الضرورية من هذا الملف لجميع الصفحات
require __DIR__ . '/config-one.php';
#
#
### بدء تشغيل معلومات قاعدة البيانات من هنا
require __DIR__  .'/class.pdo.php';
if(LOCAL) {
	$DB = new Db('localhost','amani_sms','root', 'OJOmail1');
}else{
	$DB = new Db('localhost','amani_sms','sms', '*AtHuNJ2*$tn@^');
}
session_start();
#
##
###
if(empty($_GET['pagespeed'])){
	$_GET['pagespeed'] = 'off';
}
#
##
###
if(ajax)
	require __DIR__ . '/ajax-process.php';

if('api' == $cat_id){
	require __DIR__ . '/ajax-process.php';
}
#
##
### الأقسام والمواضيع معاً
// print_r($_GET);
#
##
###
if('export' == $cat_id)
	require __DIR__ . '/export.php';
#
##
###
if('moder' == $staticpage ||'cp' == $staticpage || !empty($moder)){ //$moder = هو رمز يوضح انه الرابط هو للوحة التحكم

	require __DIR__ . '/admin_setup.php';
}else {
	#
	##
	### الأقسام والمواضيع معاً
	require __DIR__ . '/page_home.php';

}



$time_end = microtime(true);
// $execution_time = ($time_end - $time_start);
//execution time of the script
// echo '<b>Total Execution Time:</b> '.(($execution_time)/60).' Mins';
// echo '<br><b>Total Execution Time:</b> '.$execution_time.' Second';
die;
