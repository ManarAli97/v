<?php
if('page_home.php' == basename($_SERVER['PHP_SELF'])){
  die('ERR#404');
}
$pid = isset($_GET['pid']) ? (int) $_GET['pid'] : '';
?>

<!DOCTYPE html>
<html lang="ar" dir="ar">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Language" content="ar-iq">
    <title>شركة الاماني للتجارة العامة</title>
    <link rel="stylesheet" href="/v/s/common7.3.css">
    <link rel="stylesheet" href="/v/s/style.css?500">
    <script src="/v/s/jquery-min.js"></script>

  </head>
  <style media="screen">

  </style>
<body>

<main style="overflow-x: hidden;">
<style media="screen">
body{
  background-color: #041549;
}
.btn-warning {
  background-color: #ffcd36;
  border-color: #ffcd36;
}
.title{
  color: #fff;
  border: 2px solid #fff;
  border-radius: 50px;
  padding: 10px 10px;
}
.title h4{
  font-size: 25px;
  font-weight: 900;
  /* font-family: 'hura' !important; */
}
.title::before{
  content: '';
  position: absolute;
  background-image: url(/v/s/pattren.png?111);
  width: 50px;
  height: 80px;
  background-size: 100%;
  top: -25px;
  left: -35px;
}
.title h4 span{color: #ffcd36;}
.vote-btn p{
  background-image: url(/v/s/txt-bg.png?111);
  width: 90px;
  height: 40px;
  background-size: 100%;
  background-repeat: no-repeat;
  background-position: center;
  position: relative;
  display: block;
  margin: auto;
  line-height: 1.8;
  font-size: 20px;
  font-weight: 900;
  margin-top: 10px;
}
.vote-btn{
  margin-left: 2rem;
  margin-right: 2rem;
}
.vote-btn .img{
  width: 100px;
  height: 100px;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
  position: relative;
  display: block;
  margin: auto;
  /* border-radius: 50px; */
}
.vote-btn input{opacity: 0;}
.checkedVote {
  /* animation: pulse .8s infinite; */
  width: 110px!important;
  height: 110px!important;
  /* margin-top: -30px !important; */
}

@keyframes pulse {
  100% {
    -moz-box-shadow: 0 0 0 0 rgba(204,169,44, 0.4);
    box-shadow: 0 0 0 0 rgba(204,169,44, 0.8);
  }
  30% {
      -moz-box-shadow: 0 0 0 10px rgba(204,169,44, 0);
      box-shadow: 0 0 0 10px rgba(204,169,44, 0);
  }
  0% {
      -moz-box-shadow: 0 0 0 0 rgba(204,169,44, 0);
      box-shadow: 0 0 0 0 rgba(204,169,44, 0);
  }
}


.logo{
  animation: bounceIn 0.6s;
  transform: rotate(0deg) scale(1) translateZ(0);
  transition: all 0.4s cubic-bezier(.8,1.8,.75,.75);
}
/* .logo:hover {transform: rotate(10deg) scale(1.1);} */
@keyframes bounceIn {
  0% {
    opacity: 1;
    transform: scale(.3);
  }
  50% {
    opacity: 1;
    transform: scale(1.05);
  }
  70% {
    opacity: 1;
    transform: scale(.9);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

@media only screen and (max-width: 760px) {
  .vote-btn{
    margin-left: 0.8rem;
    margin-right: 0.8rem;
  }
  .vote-btn .img{
    width: 75px;
    height: 75px;
  }
  .vote-btn p{width: 75px;}
  .title h4{font-size: 14px;}
  .title {margin: 15px;}
  .title::before {
    top: -40px;
    left: -15px;
  }
}

.input{
  width: 100%;
  height: 50px;
  border: 2px solid #eeeeee;
  padding: 10px;
  font-size: 30px;
  height:77px;
}
.num ,.big-btn{
  font-size: 30px;
  padding: 15px 30px;
}
</style>


<?php if('phone' == $cat_id){

$action_result = '';
if(isset($_POST['phone-submit'])){

  $date = time();
  $DB->query("INSERT INTO `phone_cust`(`phone`,`created`) VALUES(?,?)",
  [$_POST['phone'],$date]);
  $action_result = '<div class="c alert alert-success"><h3>شكراً لك</h3></div><br>';

}
?>

  <div class="row d-flex justify-content-start">
    <div class="col-md-4 c p-3">
      <img class="logo" width="300" src="/v/s/logo.png?1005" >
    </div>
  </div>
  <br>
  <br>
  <div class="container">
    <div class="row d-flex justify-content-center">
      <div class="col-md-6"><?=$action_result?></div>
    </div>
    <?php if(!empty($action_result)){ die('<meta http-equiv="refresh" content="2;url=/v/phone/" />'); } ?>
    <div class="row d-flex justify-content-center">
      <div class="title col-md-8 c">
        <h4>شكراً لكَ على قضاء بعض الوقت معنا... برجاء إدخال رقم هاتفك</h4>
      </div>
    </div>
    <form class="" method="post">
      <div class="mt-5 row d-flex justify-content-center">
        <div class="col-md-7">
          <input autofocus required dir=ltr autocomplete="off"
          class="c input input-phone" name="phone">
        </div>
        <div class="mt-3 col-md-7 d-flex justify-content-center">
          <button class="mx-2 btn num" data-num=3 type="button" >3</button>
          <button class="mx-2 btn num" data-num=2 type="button" >2</button>
          <button class="mx-2 btn num" data-num=1 type="button" >1</button>
        </div>
        <div class="mt-3 col-md-7 d-flex justify-content-center">
          <button class="mx-2 btn num" data-num=6 type="button" >6</button>
          <button class="mx-2 btn num" data-num=5 type="button" >5</button>
          <button class="mx-2 btn num" data-num=4 type="button" >4</button>
        </div>
        <div class="mt-3 col-md-7 d-flex justify-content-center">
          <button class="mx-2 btn num" data-num=9 type="button" >9</button>
          <button class="mx-2 btn num" data-num=8 type="button" >8</button>
          <button class="mx-2 btn num" data-num=7 type="button" >7</button>
        </div>
        <div class="mt-3 col-md-7 d-flex justify-content-center">
          <button class="mx-2 btn btn-danger empty-phone"  type="button" ><img src="/v/s/del.png?30" width="50"></button>
          <button class="mx-2 btn num" data-num=0 type="button" >0</button>
          <button class="mx-2 btn num" type="button"  style="opacity:0;visibility: hidden;">0</button>
        </div>
        <!-- <div class="mt-3 col-md-7 d-flex justify-content-center">
          <button style="font-size: 30px;padding: 10px 6px;" class="btn btn-danger empty-phone"  type="button" >
            <img src="/v/s/del.png?30" width="65">
           </button>
          <button style="margin-left:23px!important" class="mx-2 btn num" data-num=0 type="button" >0</button>
          <div class="mx-5"></div>
        </div> -->
      </div>
      <br>
      <div class="row justify-content-center">
        <div class="col-md-4 c">
          <button type="submit" class="w200 btn btn-warning" name="phone-submit">متابعة</button>
        </div>
      </div>
    </form>
  </div>
  <br><br>
<script type="text/javascript">

$(document).ready(function() {
  $(".num").click(function(){
   var n = $(this).attr('data-num');
   $('.input-phone').val($('.input-phone').val() + n);
  });

  $(".empty-phone").click(function(){
    var $myInput = $('.input-phone');
    $myInput.val($myInput.val().slice(0, -1));
  });

});
</script>
<?php } ?>
<!--  -->
<!--  -->
<!--  -->

<?php if(!empty($pid)){
$phones = $DB->row("Select * from `phone` WHERE `phone` = ? and `voted` = 0 and `deleted` = 0  order by `id` desc",[$pid]);
if(empty($phones)){
  die;
}
#
##
###
if(isset($_POST['vote-submit'])){
  $date = time();
  $vote = $_POST['vote'];
  $res = $DB->query("UPDATE `phone` SET `vote`=?,`note`=?,`voted`=? WHERE `id`=?",
  [$vote,$_POST['note'],$date,$phones['id']]);
  die('<meta http-equiv="refresh" content="1;url=http://alamani.iq" /><h3 style="color:#fff;" class=c>شكراً لك ... تم إكمال عملية التصويت</h3>');
}
#
##
###

$phones = $DB->row("Select `v`.`home_msg`
from `phone` `p1` inner join `vote` `v` on
`p1`.`fkvote` = `v`.`id` and
`p1`.`deleted` = 0 and `v`.`deleted` = 0 and `p1`.`phone`=? and `v`.`id`=? ",[$pid,$id]);
#
if(empty($phones)){
  die;
}else{
  $msg = $phones['home_msg'];

}
?>
<div class="row d-flex justify-content-start">
  <div class="col-md-4 c p-3">
    <img class="logo" width="300" src="/v/s/logo.png?1005" >
  </div>
</div>
<br>
<div class="container">
  <div class="row d-flex justify-content-center">
    <div class="title col-md-8 c">
      <!-- <h4> اضغط على احد الاوجه لتقييم مستوى رضاك عن الخدمة التي حصلت عليها من <span>شركة الاماني</span> </h4> -->
      <h4> <?=$msg?> </h4>
    </div>
  </div>
  <br>
  <form class="" method="post">
    <div class="row d-flex justify-content-center">

    <label  class="c vote-btn" for="best">
      <span style="background-image: url('/v/s/1.png?111');" class="img"></span>
      <p>سعيد</p>
      <input required id="best" type="radio" name="vote" value="1">
    </label>

    <label  class="c vote-btn" for="good">
      <span style="background-image: url('/v/s/2.png?111');" class="img"></span>
      <p>راضي</p>
      <input required id="good" type="radio" name="vote" value="2">
    </label>

    <label  class="c vote-btn" for="bad">
      <span style="background-image: url('/v/s/3.png?111');" class="img"></span>
      <p>حزين</p>
      <input required id="bad" type="radio" name="vote" value="3">
    </label>

    </div>
    <div class="row justify-content-center">
      <div class="col-md-4 c">
        <input class="form-control" name="note" placeholder="أكتب ملاحظة">
      </div>
    </div>
    <br>
    <div class="row justify-content-center">
      <div class="col-md-3 c">
        <button type="submit" class="w200 btn btn-warning" name="vote-submit">إرسال</button>
      </div>
    </div>
  </form>
</div>
<?php } ?>
<script type="text/javascript">
$('.vote-btn .img').click(function(){
  $('.vote-btn .img').removeClass('checkedVote');
  $(this).toggleClass('checkedVote');
});
</script>



</body>
</html>
