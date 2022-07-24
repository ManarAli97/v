<?php
# هذا سطر يبقى في البداية دائما
if(!isset($_SERVER['HTTP_USER_AGENT']))
	die('o_0');
define('LOCAL', (PHP_OS == 'WINNT'));
# الغاء جميع الأخطاء
if(!LOCAL) {
	error_reporting(0);
	ini_set('display_errors', '0');
}
#
##
### شويه ثوابت على شويه متغيرات مفيدة تأتي مباشرة من الإعداد
define('ajax', (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND 'XMLHttpRequest' == $_SERVER['HTTP_X_REQUESTED_WITH']));
define('post', (!empty($_SERVER['REQUEST_METHOD']) AND 'POST' == $_SERVER['REQUEST_METHOD']));
define('ws_name', '//' . $_SERVER['SERVER_NAME']);
$uri = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
// متغيرات محددة مسبقا مثلا نتائج كل صفحة وغيره
$limit_pp = 10; // عدد نتائج كل صفحة
$prnt = ''; // تصفير هذا المتغير للعمل كمتغير خالي
$page_title = '';
$page_breadcrumb = '';
# نحدد وقت المنطقة الزمنية حتى لا يكون السكربت ضارب اثناء العمل واضافة الوقت
date_default_timezone_set('Asia/Baghdad');
define('uts', time());
#تعريف متغيرات الرابط
$cat_id = isset($_GET['cat_id']) ?$_GET['cat_id'] : '';
$section_id = isset($_GET['section_id']) ? strip_tags(htmlentities(trim($_GET['section_id']))) : '';
$staticpage = isset($_GET['s_page']) ? strip_tags(htmlentities(trim($_GET['s_page']))) : '';
$id = isset($_GET['id']) ? (int) $_GET['id'] : '';
$moder = isset($_GET['moder']) ? strip_tags(htmlentities(trim($_GET['moder']))) : '';

$sesion_suffix = 'amanivote_'; # تحديد اسم الكلية لاضفاته الى السشن حته يتميز عن بقية اللوحات
$rand_num = 20002; # رقم عشوائي يستخدم لمسح الكاش لاي ملف css,js....
$html_meta = '';

#
##
###
$mobile = array('APPLE','SAMSUNG', 'ITEL','LENOVO','HUAWEI','IPAD','SONY','LG','NOKIA','HTC','XIAOMI');
$se = array('all','Note','s','Mate','A','J','Grand','P','Honor','G','Y','Nova','Max',
'Mi','Redmi','X','C','Z','Tab','D','V','Ipad','M','Iphone');
