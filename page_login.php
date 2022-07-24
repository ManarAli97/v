<?php
if('page_login.php' == basename($_SERVER['PHP_SELF'])){
  die('ERR#404');
}

function secure_pass($c){
$c = MD5(base64_encode(MD5(base64_encode(SHA1($c)))));
$c = chunk_split($c,'5','-');
$c = substr($c,'0','45');
$c = rtrim($c, "-");
return $c;
}
#
##
###
if(isset($_GET['out'])){

unset($_SESSION[$sesion_suffix."user_id"]);
unset($_SESSION[$sesion_suffix."user_name"]);
unset($_SESSION[$sesion_suffix."user_lvl"]);
session_destroy();
 die('<meta http-equiv="refresh" content="0;url=/v/moder/" /><h2 style="text-align:center">تم تسجيل الخروج<h2>');

}


$action_result='';
## login
if(post && isset($_POST['login']) && isset($_POST['val']) && isset($_POST['pwd'])){
// print_r($_POST);
  $__pwd = secure_pass($_POST['pwd']);

    $res = $DB->row("SELECT `title`,`pass`,`name`,`id`,`lvl` FROM `user` WHERE `name`=? and `pass`=?",array($_POST['val'],$__pwd));
    if($res && !empty($res)){

      $_SESSION[$sesion_suffix.'user_name'] = $res['name'];
      $_SESSION[$sesion_suffix.'user_title'] = $res['title'];
      $_SESSION[$sesion_suffix.'user_id'] = $res['id'];
      $_SESSION[$sesion_suffix.'user_lvl'] = $res['lvl'];

        die('<meta http-equiv="refresh" content="0;url=/v/cp/user/" /> <h2 style="text-align:center"> تم تسجيل الدخول بنجاح<h2> ');



    }else {
      $action_result='خطا في المعلومات حاول مرة اخرى';
    }



}
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
  <meta charset="utf-8">
  <title>لوحة التحكم</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width,initial-scale=1">

  <link rel="stylesheet" href="/v/s/common7.3.css">
  <link rel="stylesheet" href="/v/s/style.css?<?=$rand_num;?>">
  <link rel="stylesheet" href="/v/s/aos.css">

  <meta charset="utf-8">
</head>
<style>


.login-page {
  width: 360px;
  padding: 8% 0 0;
  margin: auto;
}
.form {
  position: relative;
  z-index: 1;
  background: #FFFFFF;
  max-width: 360px;
  margin: 0 auto 100px;
  padding: 45px;
  text-align: center;
  box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
}
.form select{
  height: 58px !important;
  background-color: #e8f0fe;
  border: none;
}
.form input  {
  outline: 0;
  background: #f2f2f2;
  width: 100%;
  border: 0;
  margin: 0 0 15px;
  padding: 15px;
  box-sizing: border-box;
  font-size: 14px;
}
.form .buttonSubmit {
  text-transform: uppercase;
  outline: 0;
  background: #4CAF50;
  width: 100%;
  border: 0;
  padding: 15px;
  color: #FFFFFF;
  font-size: 14px;
  -webkit-transition: all 0.3 ease;
  transition: all 0.3 ease;
  cursor: pointer;
}
.form .buttonSubmit:hover,.form .buttonSubmit:active,.form .buttonSubmit:focus {
  background: #43A047;
}
.form .message {
	cursor: pointer;
  margin: 15px 0 0;
  color: #b3b3b3;
  font-size: 12px;
}
.form .message span {
  color: #4CAF50;
  text-decoration: none;
}
.form .register-form {
  display: none;
}
.container2 {
  position: relative;
  z-index: 1;
  max-width: 300px;
  margin: 0 auto;
}
.container2:before, .container2:after {
  content: "";
  display: block;
  clear: both;
}
.container2 .info {
  margin: 50px auto;
  text-align: center;
}
.container2 .info h1 {
  margin: 0 0 15px;
  padding: 0;
  font-size: 36px;
  font-weight: 300;
  color: #1a1a1a;
}
.container2 .info span {
  color: #4d4d4d;
  font-size: 12px;
}
.container2 .info span a {
  color: #000000;
  text-decoration: none;
}
.container2 .info span .fa {
  color: #EF3B3A;
}
</style>
<body>
<div class="login-page">
  <div class="form">
    <form data-aos="fade-up" data-aos-duration="400" method="POST"  class="login-form">
      <input data-aos="fade-up" data-aos-duration="650" type="text" required  autocomplete="off"  name="val" placeholder="اسم المستخدم" />
      <input data-aos="fade-up" data-aos-duration="800" type="password" required  autocomplete="off" name="pwd" placeholder="الرمز السري" />
      <button data-aos="fade-up" data-aos-duration="950" class="btn btn-warning" type="submit" name="login">تسجيل الدخول</button>
      </p>
    </form>

    <br>
    <?php if(!empty($action_result)){ ?>
    <a href="/v/moder/" class="alert alert-danger"><?=$action_result;?></a>
    <?php } ?>
  </div>
</div>


  </div>
</div>

<script src="/v/s/aos.js"></script>
<script>
  AOS.init();
</script>
</body>
</html>
