<?php

if('moder' == $staticpage){
  require __DIR__ . '/' . 'page_login.php';

}elseif('cp' == $moder && empty($cat_id)){

  die('<meta http-equiv="refresh" content="0;url=/v/cp/user/" />جاري الخروج');

}elseif(!empty($moder)) {

  require __DIR__ . '/admin_header.php';
  require __DIR__ . '/' . 'admin_' . $cat_id . '.php';
  require __DIR__ . '/admin_footer.php';

}
