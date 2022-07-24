<?php
if('admin_user.php' == basename($_SERVER['PHP_SELF'])){
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
#
##
###
if('admin' != $_SESSION[$sesion_suffix.'user_lvl'] ){
  die('<meta http-equiv="refresh" content="0;url=/v/cp/moder/" />لا تملك صلاحية الدخول لهذه الصفحة');
}
#
##
###
if(isset($_POST['new-user'])){
  $date = time();
  $__pwd = secure_pass($_POST['pass']);
  $DB->query("INSERT INTO `user`(`name`,`title`,`pass`,`lvl`,`created`) VALUES(?,?,?,?,?)",
  [$_POST['name'],$_POST['title'],$__pwd,$_POST['lvl'],$date]);
}
#
##
###
if(isset($_POST['new-vote']) && LOCAL){
  $date = time();
  $with_url = (isset($_POST['with_url']) ? 1 : 0);
  $with_name = (isset($_POST['with_name']) ? 1 : 0);

  $DB->query("INSERT INTO `vote`(`title`,`msg`,`home_msg`,`with_url`,`with_name`,`created`)
  VALUES(?,?,?,?,?,?)",
  [$_POST['title'],$_POST['msg'],$_POST['home_msg'],$with_url,$with_name,$date]);
}
#
##
###
$action_result = '';
if(isset($_POST['new-phone'])){


ini_set('max_execution_time', 60 * 60);
set_time_limit(0);
ini_set('memory_limit', '20000M');

$names = [];
if('group' == $_POST['type']){
  $phones = explode(PHP_EOL, $_POST['group_phone']);
  $names = explode(PHP_EOL, $_POST['group_name']);
}
#
else{
  $phones = explode(PHP_EOL, $_POST['single_phone']);
  $names = explode(PHP_EOL, $_POST['single_name']);
}
#
$votes = $DB->row("Select `msg`,`with_name`,`with_url` from `vote` WHERE `id` = ? and `deleted` = 0",[$_POST['vote']]);
#

foreach ($_POST['send_type'] as $key_type => $tvalue) {

  if('sms' == $tvalue){

    foreach ($phones as $key => $value) {

      $phonesRow = $DB->row("Select * from `phone` WHERE `phone` = ? and `fkvote`=? and `voted` = 0 and
     `deleted` = 0  order by `id` desc",[(int)$value ,$_POST['vote']]);

      if(empty($phonesRow)){

        $date = time();
        if(1 == $votes['with_name']){
          $DB->query("INSERT INTO `phone`(`fkvote`,`phone`,`name`,`created`) VALUES(?,?,?,?)",
          [$_POST['vote'],$value,$names[$key],$date]);
        }else{
          $DB->query("INSERT INTO `phone`(`fkvote`,`phone`,`name`,`created`) VALUES(?,?,?,?)",
          [$_POST['vote'],$value,$names[0],$date]);
        }

        echo '<div class="alert alert-warning c">تم إرسال الى : '.$value.'</div>';

        $urlExt = $nameExt = '';
        if(1 == $votes['with_name']){ $nameExt = 'عزيزي الزبون '.$names[$key].PHP_EOL; }

        if(1 == $votes['with_url']){ $urlExt = 'https://alamani.iq/v/'.$_POST['vote'].'/'.$value; }

        $msg = $nameExt.$votes['msg'].$urlExt;
        // echo $msg.'<br>';
        ob_flush();
        flush();
        sleep(3);

        $ch = curl_init('http://'.$_POST['url'].':8766/?number='.$value.'&message='.urlencode($msg));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        curl_close($ch);

      }else{
        $action_result .= '<div class="alert alert-warning c"> '.$value.' موجود سابقاً في رسائل SMS</div>';
      }

    }

  }
  // ############
  // ############
  if('whatsapp' == $tvalue){
    $limit = count($phones);
    foreach ($phones as $key => $value) {

      $phonesRow = $DB->row("Select * from `phone_whats` WHERE `phone` = ? and `fkvote`=? and `deleted` = 0 and send_status = 0
      order by `id` desc",[(int)$value ,$_POST['vote']]);


      if(empty($phonesRow)){

        $date = time();

        $urlExt = $nameExt = '';
        // if(1 == $votes['with_name']){ $nameExt = 'عزيزي الزبون '.$names[$key].PHP_EOL; }
        if(1 == $votes['with_name']){ $nameExt = ' '.$names[$key].PHP_EOL; }
        if(1 == $votes['with_url']){ $urlExt = 'https://alamani.iq/v/'.$_POST['vote'].'/'.$value; }

        $msg = $nameExt.$votes['msg'].$urlExt;
        // echo $msg.'<br>';

        $DB->query("INSERT INTO `phone_whats`(`fkvote`,`phone`,`msg`,`created`) VALUES(?,?,?,?)",
        [$_POST['vote'],$value,$msg,$date]);

      }else{
        $action_result .= '<div class="alert alert-warning c">'.$value.' موجود سابقاً في رسائل الواتساب </div>';
      }

    }

    $command = escapeshellcmd('C:/Python27/python.exe c:/xampp/htdocs/v/pywhatsapp.py');
    $output = shell_exec($command);

      echo '<div class="alert alert-warning c"> المجموع الكلي للرسائل: '.$limit.'   </div>';
      $checksend = $DB->query("SELECT * from `phone_whats` WHERE `deleted` = 0
      order by `id` desc limit ? ",[$limit]);
      foreach ($checksend as $key => $row){
      if($row['send_status'] == 1){
        echo '<div class="alert alert-warning c">تم الارسال الى : '.$row['phone'].'</div>';

      }else{
        echo '<div class="alert alert-warning c">  لم يتم الارسال  : '.$row['phone'].'</div>';

      }
    }
    exit(' ');


  }
  // ############
  // ############

}





}
#
##
###
if(isset($_POST['sms-all'])){

#
$votes = $DB->row("Select `msg` from `vote` WHERE `id` = ? and `deleted` = 0",[$_POST['vote']]);
#
$phones = $DB->query("Select `phone` from `phone` WHERE `deleted` = 0 and `fkvote`=? ",[$_POST['vote']]);
foreach ($phones as $key => $value) {

  echo '<div class="alert alert-warning c">تم إرسال الى : '.$value['phone'].'</div>';
  $msg = $votes['msg'].'https://alamani.iq/v/'.$_POST['vote'].'/'.$value['phone'];

  ob_flush();
  flush();
  sleep(3);

  $ch = curl_init('http://'.$_POST['url'].':8766/?number='.(int)$value['phone'].'&message='.urlencode($msg));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  $data = curl_exec($ch);
  curl_close($ch);

}


}
#
##
###
if(isset($_POST['sms-select'])){

#
$votes = $DB->row("Select `msg` from `vote` WHERE `id` = ? and `deleted` = 0",[$_POST['vote']]);
#
$phones = explode(',', $_POST['phone']);
// echo '<pre>';
// print_r($phones);

foreach ($phones as $key => $value) {
  if(!empty($value)){

    echo '<div class="alert alert-warning c">تم إرسال الى : '.$value.'</div>';
    $msg = $votes['msg'].'https://alamani.iq/v/'.$_POST['vote'].'/'.$value;

    ob_flush();
    flush();
    sleep(3);

    $ch = curl_init('http://'.$_POST['url'].':8766/?number='.(int)$value.'&message='.urlencode($msg));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $data = curl_exec($ch);
    curl_close($ch);

  }
}


}
#
##
###
if(isset($_POST['edit-user'])){
    $date = time();

    if(empty($_POST['pass'])){

      $res = $DB->query("UPDATE `user` SET `name`=?,`title`=?,`lvl`=? WHERE `id`=?",
      [$_POST['name'],$_POST['title'],$_POST['lvl'],$_POST['i']]);

    }else {

      $__pwd = secure_pass($_POST['pass']);
      $res = $DB->query("UPDATE `user` SET `name`=?,`title`=?,`lvl`=?,`pass`=? WHERE `id`=?",
      [$_POST['name'],$_POST['title'],$_POST['lvl'],$__pwd,$_POST['i']]);

    }
    die('<meta http-equiv="refresh" content="0;url=/v/cp/user/edit/'.$_POST['i'].'/" />');
}
#
##
###
if(isset($_POST['edit-vote'])){
    $date = time();
    $with_url = (isset($_POST['with_url']) ? 1 : 0);
    $with_name = (isset($_POST['with_name']) ? 1 : 0);

    $res = $DB->query("UPDATE `vote` SET `title`=?,`msg`=?,`home_msg`=?,`with_url`=?,`with_name`=? WHERE `id`=?",
    [$_POST['title'],$_POST['msg'],$_POST['home_msg'],$with_url,$with_name,$_POST['i']]);
    die('<meta http-equiv="refresh" content="0;url=/v/cp/user/show-vote/" />');
}
#
##
###
if(!empty($section_id) && 'del' == $section_id){
  $date = time();
  $DB->query("UPDATE `user` SET `deleted` = ? WHERE `id` = ?",[$date ,$id]);
  die('<meta http-equiv="refresh" content="0;url=/v/cp/user/" />');

}
#
##
###
if(!empty($section_id) && 'delete-vote' == $section_id && LOCAL){
  $date = time();
  $DB->query("UPDATE `vote` SET `deleted` = ? WHERE `id` = ?",[$date ,$id]);
  die('<meta http-equiv="refresh" content="0;url=/v/cp/user/show-vote/" />');

}
#
##
###
$users = $DB->query("Select * from `user` WHERE `deleted` = 0  order by `id` desc");
?>

<div class="container">
  <div class="row d-flex justify-content-center">
    <a class="btn btn-warning w160 m-2" href="/v/cp/user/">عرض المستخدمين</a>
    <a class="btn btn-warning w150 m-2" href="/v/cp/user/new-user/">اضافة جديد</a>
    |
    <a class="btn btn-warning w150 m-2" href="/v/cp/user/report-phone/">تقرير التصويت</a>
    <a class="btn btn-warning w150 m-2" href="/v/cp/user/show-vote/">أقسام التصويت</a>
    <a class="btn btn-warning w150 m-2" href="/v/cp/user/new-phone/">اضافة رقم هاتف</a>
  </div>
</div>
<br>
<?=$action_result?>
<br>

<?php if('new-user' == $section_id){ ?>
<div class="container">
  <h3>اضافة مستخدم</h3>
  <div class="add-div p-5 row d-flex justify-content-right">
    <div class="col-md-12">
      <form class="user-form" method="post">
        <fieldset >
        <div class="form-group">
          <div class="col-md-12">
          <label class="control-label" >اسم المستخدم</label>
          <input autocomplete="off"  name="title" type="text" placeholder="اسم المستخدم" class="form-control" required>
          </div>
        </div>

        <div class="form-group">
          <div class="col-md-12">
          <label class="control-label" >اسم تسجيل الدخول</label>
          <input autocomplete="off" name="name" type="text" placeholder="اسم تسجيل الدخول"  class="form-control" required>
          </div>
        </div>

      <div class="form-group">
        <div class="col-md-12">
        <label class="control-label" >الرمز السري</label>
        <input autocomplete="off" name="pass" type="password" placeholder="الرمز السري"  class="form-control" required>
            <span class="bar"></span>
        </div>
      </div>

      <input value="admin" type="hidden" name='lvl' >

        <div class="form-group">
          <div class="col-md-12">
            <input type="hidden" name="new-user" value="new-user">
            <button data-target="user-form" type="button" class="confirm-submit btn btn-block btn-warning">حفظ</button>
          </div>
        </div>
        </fieldset>
      </form>
    </div>
  </div>
</div>
<?php  } ?>

<?php if('edit-user' == $section_id){
$user = $DB->row("Select * from `user` WHERE `deleted`=0 and `id`=? order by `id` desc",[$id]); ?>

<div class="container">
  <h3>تعديل مستخدم</h3>
  <div class="add-div p-5 row d-flex justify-content-right">
    <div class="col-md-12">
      <form class="user-form" method="post">
        <fieldset >
        <div class="form-group">
          <div class="col-md-12">
          <label class="control-label" >اسم المستخدم</label>
          <input autocomplete="off" value="<?=$user['title'];?>" name="title" type="text" placeholder="اسم المستخدم" class="form-control" required>
              <span class="bar"></span>
          </div>
        </div>

        <div class="form-group">
          <div class="col-md-12">
          <label class="control-label" >اسم تسجيل الدخول</label>
          <input autocomplete="off" value="<?=$user['name'];?>" name="name" type="text" placeholder="اسم تسجيل الدخول"  class="form-control" required>              <span class="bar"></span>
          </div>
        </div>

      <div class="form-group">
        <div class="col-md-12">
        <label class="control-label" >الرمز السري</label>
        <input autocomplete="off" name="pass" type="password" placeholder="الرمز السري"  class="form-control" >
        </div>
      </div>

        <input value="admin" type="hidden" name='lvl' >
        <input type="hidden" name="i" value="<?=$user['id']?>">
        <div class="form-group">
          <div class="col-md-12">
            <input type="hidden" name="edit-user" value="edit-user">
            <button data-target="user-form" type="button" class="confirm-submit btn btn-block btn-warning">حفظ</button>
          </div>
        </div>
        </fieldset>
      </form>
    </div>
  </div>
</div>
<?php  } ?>


<?php if(empty($section_id)){ ?>
<div class="container">
  <h3>المستخدمين</h3>
  <div class="row d-flex justify-content-center">
    <ul class="responsive-table">
    <li class="table-header">
      <div class="col col-md-4">الاسم الثلاثي</div>
      <div class="col col-md-4">التاريخ</div>
      <div class="col col-md-4">ادوات</div>
    </li>
    <?php foreach ($users as $key => $value){
    ?>
      <li data-aos="fade-up" data-aos-duration="<?=($key+2)?>00" class="table-row">
        <div class="col col-md-4" data-label="الاسم الثلاثي"><?=$value['name']?></div>
        <div class="col col-md-4" data-label="التاريخ"><?=date('Y-m-d',$value['created'])?></div>
        <div class="col col-md-4" data-label="ادوات">
          <a class="btn btn-primary btn-sm" href="/v/cp/user/edit-user/<?=$value['id']?>/">تعديل</a>
          <button data-target="/v/cp/user/del/<?=$value['id']?>/" type="button" class="confirm-a btn btn-danger btn-sm">حذف</button>
        </div>
      </li>
    <?php } ?>
  </ul>
  </div>
</div>
<?php } ?>

<!--  -->
<!--  -->
<!--  -->
<!--  -->



<?php if('show-vote' == $section_id){ ?>
<div class="container">
  <h3>أقسام التصويت</h3>
  <div class="row d-flex justify-content-center">
    <ul class="responsive-table">
    <li class="table-header">
      <div class="col col-md-2">عنوان التصويت</div>
      <div class="col col-md-3">رسالة الرئيسية</div>
      <div class="col col-md-3">رسالة SMS</div>
      <div class="col col-md-1">الخيارات</div>
      <div class="col col-md-2">ادوات</div>
    </li>
    <form class="vote-form" method="post">
      <li data-aos="fade-up" data-aos-duration="100" class="bg04 table-row">
        <div class="col col-md-2" data-label="عنوان التصويت"> <input required placeholder="عنوان التصويت " name="title" class="form-control"> </div>
        <div class="col col-md-3" data-label="رسالة الرئيسية"> <textarea required name="home_msg" rows="4" cols="10" class="form-control"></textarea> </div>
        <div class="col col-md-3" data-label="رسالة SMS"> <textarea required name="msg" rows="4" cols="10" class="form-control"></textarea> </div>
        <div class="col col-md-1" data-label="الخيارات">
          <div class="form-group">
            <label class="btn btn-sm btn-light ">
              <input type="checkbox" name="with_url" value="1">
              مع الرابط؟
            </label>
            <label class="btn btn-sm btn-light ">
              <input type="checkbox" name="with_name" value="1">
              مع الاسم؟
            </label>
          </div>
        </div>
        <div class="col col-md-2" data-label="ادوات">
          <input type="hidden" name="new-vote" value="new-vote">
          <button class="new-vote btn btn-warning btn-sm" type="button">إضافة</button>
        </div>
      </li>
    </form>
    <?php
    $vote = $DB->query("Select * from `vote` WHERE `deleted` = 0 order by `id` desc");
    foreach ($vote as $key => $value){
      $with_url = (1 == $value['with_url'] ? 'checked' : '');
      $with_name = (1 == $value['with_name'] ? 'checked' : '');
    ?>
      <form class="edit-vote-form<?=$value['id']?>" method="post">
        <li data-aos="fade-up" data-aos-duration="<?=($key+2)?>00" class="table-row">
          <div class="col col-md-2" data-label="عنوان التصويت"> <input required name="title" class="form-control" value="<?=$value['title']?>"> </div>
          <div class="col col-md-3" data-label="رسالة الرئيسية"> <textarea required name="home_msg" rows="4" cols="10" class="form-control"><?=$value['home_msg']?></textarea> </div>
          <div class="col col-md-3" data-label="رسالة SMS"> <textarea required name="msg" rows="4" cols="10" class="form-control"><?=$value['msg']?></textarea> </div>
          <div class="col col-md-1" data-label="الخيارات">
            <div class="form-group">
              <label class="btn btn-sm btn-primary ">
                <input <?=$with_url?> type="checkbox" name="with_url" value="1">
                مع الرابط؟
              </label>
              <label class="btn btn-sm btn-primary ">
                <input <?=$with_name?> type="checkbox" name="with_name" value="1">
                مع الاسم؟
              </label>
            </div>
          </div>
          <div class="col col-md-2" data-label="ادوات">
            <input type="hidden" name="i" value="<?=$value['id']?>">
            <input type="hidden" name="edit-vote" value="edit-vote">
            <button data-id="<?=$value['id']?>" class="edit-vote btn btn-primary btn-sm" type="button">تعديل</button>
            <button data-id="<?=$value['id']?>" class="delete-vote btn btn-danger btn-sm" type="button">حذف</button>
          </div>
        </li>
      </form>
    <?php } ?>
  </ul>
  </div>
</div>
<script type="text/javascript">

$(document).on('click','.new-vote',function(){

  var data = $(".vote-form").serialize();

  alertify.confirm(
    'تأكيد العملية',
     'هل تريد المتابعة؟',
      function(){

          if($('.vote-form')[0].checkValidity()) {
            $.post("https://alamani.iq/v/api",data,function(result){
            // $.post("/v/api",data,function(result){
              console.log(result);
              result = JSON.parse(result);

              if(result.result == true){
                $('.vote-form').submit();
              }else{
                alertify.error('تاكد من الإتصال بالإنترنت');
              }
            }).done(function(msg){ })
            .fail(function(xhr, status, error) {
              alertify.error('تاكد من الإتصال بالإنترنت');
            });

          }else {
           alertify.error('الرجاء اكمال جميع الحقول')
          }
       }
      , function(){
         // alertify.error('Cancel')
       }
     );
});
//#
//##
//###
$(document).on('click','.edit-vote',function(){

  var id = $(this).attr('data-id');
  var data = $(".edit-vote-form"+id).serialize();

  alertify.confirm(
    'تأكيد العملية',
     'هل تريد المتابعة؟',
      function(){

          if($('.edit-vote-form'+id)[0].checkValidity()) {
            $.post("https://alamani.iq/v/api",data,function(result){
            // $.post("/v/api",data,function(result){
              console.log(result);
              result = JSON.parse(result);

              if(result.result == true){
                $('.edit-vote-form'+id).submit();
              }else{
                alertify.error('تاكد من الإتصال بالإنترنت');
              }
            }).done(function(msg){ })
            .fail(function(xhr, status, error) {
              alertify.error('تاكد من الإتصال بالإنترنت');
            });

          }else {
           alertify.error('الرجاء اكمال جميع الحقول')
          }
       }
      , function(){
         // alertify.error('Cancel')
       }
     );
});
//#
//##
//###
$(document).on('click','.delete-vote',function(){

  var id = $(this).attr('data-id');

  alertify.confirm(
    'تأكيد العملية',
     'هل تريد المتابعة؟',
      function(){

            $.post("https://alamani.iq/v/api",{type:'delete-vote',id:id},function(result){
            // $.post("/v/api",{type:'delete-vote',id:id},function(result){
              // console.log(result);
              result = JSON.parse(result);

              if(result.result == true){
                var a = <?=(LOCAL)?>;
                  if(a){
                    location.replace('/v/cp/user/delete-vote/'+id+'/');
                  }
              }else{
                alertify.error('تاكد من الإتصال بالإنترنت');
              }
            }).done(function(msg){ })
            .fail(function(xhr, status, error) {
              alertify.error('تاكد من الإتصال بالإنترنت');
            });

       }
      , function(){
         // alertify.error('Cancel')
       }
     );
});
</script>
<?php  } ?>


<?php if('report-phone' == $section_id){ ?>
<div class="container">
  <h3>تقرير التصويت</h3>
    <div class="row d-flex justify-content-center">
      <div class="bg-light col-md-12">
      <form class="row d-flex justify-content-start search-form" method="post">

        <div class="col-md-3">
        <div class="form-group">
          <label class="control-label" >نتيجة التصويت</label><br>
          <label class="btn-sm btn btn-success mx-1"><input value="1" type="radio" name="result"><span>سعيد </span></label>
          <label class="btn-sm btn btn-warning mx-1"><input value="2" type="radio" name="result"><span> راضي </span></label>
          <label class="btn-sm btn btn-danger mx-1"><input value="3" type="radio" name="result"><span> حزين </span></label>
          </div>
        </div>

        <div class="col-md-3">
        <div class="form-group">
          <label class="control-label" >التصويت</label><br>
          <label class="btn-sm btn btn-primary mx-1"><input value="1" type="radio" name="vote"><span> تم التصويت </span></label>
          <label class="btn-sm btn btn-danger mx-1"><input value="0" type="radio" name="vote"><span> لم يتم التصويت </span></label>
          </div>
        </div>

        <div class="col-md-4">
        <div class="form-group">
          <label class="control-label" >الملاحظة</label><br>
          <label class="btn-sm btn btn-primary mx-1"><input value="1" type="radio" name="note"><span>مع الملاحظة </span></label>
          <label class="btn-sm btn btn-danger mx-1"><input value="0" type="radio" name="note"><span> بدون الملاحظة </span></label>
          </div>
        </div>

        <div class="col-md-6">
        <div class="form-group">
          <label class="control-label" >تاريخ التصويت</label><br>
          <label class="btn-sm btn bg04 mx-1"><span> تاريخ البداية </span><input type="date" name="start_date"></label>
          <label class="btn-sm btn bg04 mx-1"><span> تاريخ النهاية </span><input type="date" name="end_date"></label>
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
          <label class="control-label" >قسم التصويت</label>
          <select class="form-control vote_section chosen-select" name="vote_section">
            <?php $vote = $DB->query("Select * from `vote` WHERE `deleted` = 0 order by `id` desc"); ?>
            <option hidden></option>
            <?php foreach ($vote as $key => $value): ?>
              <option value="<?=$value['id']?>"><?=$value['title']?></option>
            <?php endforeach; ?>
          </select>
          </div>
        </div>

      <div class="form-group">
      <input type="hidden" name="type" value="get-phone">
      <input type="hidden" name="limit_pp" value="<?=$limit_pp?>">
      <input type="hidden" id="search-page" name="page" value="0">
      <input type="hidden" name="phone-search" value="phone-search">
      </div>

      </form>
      </div>
    </div>
  <br>
  <div class="row d-flex justify-content-start">
    <button class="w100 btn-sm btn btn-warning mx-1 search">بحث</button>
    <button class="w150 btn-sm btn btn-primary mx-1 export">إكسل للنتائج</button>
    <a href="/v/export/?t=phone" class="w150 btn-sm btn btn-primary mx-1">إكسل لإرقام الهاتف</a>
    <button class="w150 btn-sm btn btn-light mx-1 sms-all">إرسال رسالة للكل</button>
    <button class="w200 btn-sm btn btn-light mx-1 sms-select">إرسال رسالة للمحدد</button>
  </div>
  <br>
  <div class="row d-flex justify-content-center">
    <ul class="phone-div responsive-table">
    <li class="table-header">
      <div class="col col-md-3">رقم الهاتف</div>
      <div class="col col-md-2">تاريخ التصويت</div>
      <div class="col col-md-2">التصويت</div>
      <div class="col col-md-3">الملاحظة</div>
      <div class="col col-md-2">ملاحظة المدير</div>
    </li>


  </ul>
  </div>
  <br>
</div>


<script type="text/javascript">
$(document).ready(function() {
  var page = 0;
  // getPhone(page,<?=$limit_pp?>);
});

function getPhone(page ,limit_pp){

  $.post("https://alamani.iq/v/api",{page:page,limit_pp:limit_pp,type:"get-phone"},function(result){
  // $.post("/v/api",{page:page,limit_pp:limit_pp,type:"get-phone"},function(result){
    result = JSON.parse(result);
    // console.log(result);

    for (var i = 0; i < result.length; i++) {
      var html = '<li data-aos="fade-up" data-aos-duration="'+(i+2)+'00" class="table-row">'+
          '<div class="col col-md-3" data-label="رقم الهاتف">';
          if('' == result[i]['voted']){
            html+='<input name="phone-select" value="'+result[i]['phone']+'" type=checkbox> ';
          }
          html+=result[i]['phone']+'</div>'+
          '<div class="col col-md-2" dir=ltr data-label="تاريخ التصويت"> '+result[i]['voted']+'</div>'+
          '<div class="col col-md-2" data-label="التصويت">'+
            '<img class="'+(result[i]['vote']!=''?'':'disn')+'" width=50 src="/v/s/'+result[i]['vote']+'.png" >'+
          '</div>'+
          '<div class="col col-md-3" data-label="الملاحظة">'+result[i]['note']+'</div>'+
          '<div class="col col-md-2" data-label="ملاحظة المدير">'+
            '<textarea class="admin-note'+result[i]['id']+' form-control" name="admin_note" rows="3" cols="7">'+result[i]['admin_note']+'</textarea>'+
            '<button data-id="'+result[i]['id']+'" class="btn-sm btn btn-link admin-note-change">حفظ</button>'+
          '</div>'+
        '</li>';

      $('.phone-div').append(html);

      if(true == result[i]['is_finished']){
        $('.divpagination').hide();
      }
    }

  }).done(function(msg){ })
  .fail(function(xhr, status, error) {
    alertify.error('تاكد من الإتصال بالإنترنت');
  });

}
</script>
<script type="text/javascript">

var page = 0;

$(document).on('click','.search',function(){

  page = 0;
  $('#search-page').val(page);
  var data = $(".search-form").serialize();

  $.post("https://alamani.iq/v/api",data,function(result){
  // $.post("/v/api",data,function(result){
    result = JSON.parse(result);

    $('.phone-div').html(''+
    '<li class="table-header">'+
      '<div class="col col-md-3">رقم الهاتف</div>'+
      '<div class="col col-md-2">تاريخ التصويت</div>'+
      '<div class="col col-md-2">التصويت</div>'+
      '<div class="col col-md-3">الملاحظة</div>'+
      '<div class="col col-md-2">ملاحظة المدير</div>'+
    '</li>');


    for (var i = 0; i < result.length; i++) {
      var html = '<li data-aos="fade-up" data-aos-duration="'+(i+2)+'00" class="table-row">'+
          '<div class="col col-md-3" data-label="رقم الهاتف">';
          if('' == result[i]['voted']){
            html+='<input name="phone-select" value="'+result[i]['phone']+'" type=checkbox> ';
          }
          html+=result[i]['phone']+'</div>'+
          '<div class="col col-md-2" dir=ltr data-label="تاريخ التصويت"> '+result[i]['voted']+'</div>'+
          '<div class="col col-md-2" data-label="التصويت">'+
            '<img class="'+(result[i]['vote']!=''?'':'disn')+'" width=50 src="/v/s/'+result[i]['vote']+'.png" >'+
          '</div>'+
          '<div class="col col-md-3" data-label="الملاحظة">'+result[i]['note']+'</div>'+
          '<div class="col col-md-2" data-label="ملاحظة المدير">'+
            '<textarea class="admin-note'+result[i]['id']+' form-control" name="admin_note" rows="3" cols="7">'+result[i]['admin_note']+'</textarea>'+
            '<button data-id="'+result[i]['id']+'" class="btn-sm btn btn-link admin-note-change">حفظ</button>'+
          '</div>'+
        '</li>';

      $('.phone-div').append(html);

    }
    page = page + 1;

  }).done(function(msg){  })
  .fail(function(xhr, status, error) {
    alertify.error('تاكد من الإتصال بالإنترنت');
  });

});

$(window).scroll(function() {
    if($(window).scrollTop() == $(document).height() - $(window).height()) {
      var v = $('.vote_section').val();
      if(0 != v){
        loadMoreData();
      }
    }
});


function loadMoreData(){

  if(0 == page || 1 == page){
    page = 2;
  }
  $('#search-page').val(page);
  var data = $(".search-form").serialize();

  $.post("https://alamani.iq/v/api",data,function(result){
  // $.post("/v/api",data,function(result){
    result = JSON.parse(result);

    for (var i = 0; i < result.length; i++) {
      var html = '<li data-aos="fade-up" data-aos-duration="'+(i+2)+'00" class="table-row">'+
          '<div class="col col-md-3" data-label="رقم الهاتف">';
          if('' == result[i]['voted']){
            html+='<input name="phone-select" value="'+result[i]['phone']+'" type=checkbox> ';
          }
          html+=result[i]['phone']+'</div>'+
          '<div class="col col-md-2" dir=ltr data-label="تاريخ التصويت"> '+result[i]['voted']+'</div>'+
          '<div class="col col-md-2" data-label="التصويت">'+
            '<img class="'+(result[i]['vote']!=''?'':'disn')+'" width=50 src="/v/s/'+result[i]['vote']+'.png" >'+
          '</div>'+
          '<div class="col col-md-3" data-label="الملاحظة">'+result[i]['note']+'</div>'+
          '<div class="col col-md-2" data-label="ملاحظة المدير">'+
            '<textarea class="admin-note'+result[i]['id']+' form-control" name="admin_note" rows="3" cols="7">'+result[i]['admin_note']+'</textarea>'+
            '<button data-id="'+result[i]['id']+'" class="btn-sm btn btn-link admin-note-change">حفظ</button>'+
          '</div>'+
        '</li>';

      $('.phone-div').append(html);

    }
      page = page + 1;

  }).done(function(msg){  })
  .fail(function(xhr, status, error) {
    alertify.error('تاكد من الإتصال بالإنترنت');
  });

}
//-*-*-*-*--*-*-
//-*-*-*-*--*-*-
//-*-*-*-*--*-*-
$(document).on('click','.export',function(){
  var data = $(".search-form").serialize();
  data = data + '&t=phone-search-export';
  location.replace('https://alamani.iq/v/export/?'+data);
  // location.replace('/v/export/?'+data);
});
//-*-*-*-*--*-*-
//-*-*-*-*--*-*-
//-*-*-*-*--*-*-
$(document).on('click','.admin-note-change',function(){

  var id = $(this).attr('data-id');
  var val = $('.admin-note'+id).val();

  $.post("https://alamani.iq/v/api",{type:'admin-note-change',id:id,val:val},function(result){
  // $.post("/v/api",{type:'admin-note-change',id:id,val:val},function(result){
    result = JSON.parse(result);
    // console.log(result);
    if(true == result['result']){
      alertify.success('تم الحفظ');
    }

  }).done(function(msg){  })
  .fail(function(xhr, status, error) {
    alertify.error('تاكد من الإتصال بالإنترنت');
  });

});
//-*-*-*-*--*-*-
//-*-*-*-*--*-*-
//-*-*-*-*--*-*-
$(document).on('click','.sms-all',function(){

var html = '';
html += '<div class="p-5 row d-flex justify-content-right">';
  html += '<div class="col-md-12">';
    html += '<form class="sms-phone-form" method="post"> ';

      html += '<div class="form-group">';
        html += '<div class="col-md-12">';
          html += '<label class="control-label" >رابط تطبيق الموبايل</label>';
          html += '<input name="url" placeholder="مثال : 192.168.0.113" class="form-control" required>';
        html += '</div>';
      html += '</div>';

      html += '<div class="form-group">';
        html += '<div class="col-md-12">';
        html += '<label class="control-label" >قسم التصويت</label>';
        html += '<select class="form-control" required name="vote">';
          <?php $vote = $DB->query("Select * from `vote` WHERE `deleted` = 0 order by `id` desc"); ?>
          <?php foreach ($vote as $key => $value): ?>
            html += '<option value="<?=$value['id']?>"><?=$value['title']?></option>';
          <?php endforeach; ?>
        html += '</select>';
        html += '</div>';
      html += '</div>';

    html += '<div class="form-group">';
      html += '<input type="hidden" name="sms-all" value="sms-all">';
    html += '</div>';

    html += '</form>';
  html += '</div>';
html += '</div>';

  alertify.confirm(
    'تأكيد العملية',
     html,
      function(){

          if($('.sms-phone-form')[0].checkValidity()) {
            $('.sms-phone-form').submit();
          }else {
           alertify.error('الرجاء اكمال جميع الحقول')
          }
         // alertify.success('Ok')
       }
      , function(){
         // alertify.error('Cancel')
       }
     );
});

// -*-*-*-*-*-
// -*-*-*-*-*-

$(document).on('click','.sms-select',function(){

  // var array = [];
  var val = '';
  var checkboxes = document.querySelectorAll('input[name=phone-select]:checked');

  for (var i = 0; i < checkboxes.length; i++) {
    val = val + ','+checkboxes[i].value
  }

  var v = $('.vote_section').val();
  if(0 == v){
    alertify.error('الرجاء تحديد قسم التصويت');
    return 0;
  }


  var n = checkboxes.length;
  if(0 == n){
    alertify.error('الرجاء تحديد رقم هاتف واحد على الأقل');
    return 0;
  }else{

    var html = '';
    html += '<h4>تم تحديد "'+n+'" رقم</h4>';
    html += '<div class="p-5 row d-flex justify-content-right">';
      html += '<div class="col-md-12">';
        html += '<form class="select-phone-form" method="post"> ';

          html += '<div class="form-group">';
            html += '<div class="col-md-12">';
              html += '<label class="control-label" >رابط تطبيق الموبايل</label>';
              html += '<input name="url" placeholder="مثال : 192.168.0.113" class="form-control" required>';
            html += '<input name="phone" value="'+val+'" type="hidden">';
            html += '</div>';
          html += '</div>';

        html += '<div class="form-group">';
          html += '<input type="hidden" name="vote" value="'+v+'">';
          html += '<input type="hidden" name="sms-select" value="sms-select">';
        html += '</div>';

        html += '</form>';
      html += '</div>';
    html += '</div>';

    alertify.confirm(
      'تأكيد العملية',
       html,
        function(){
          if($('.select-phone-form')[0].checkValidity()) {
            $('.select-phone-form').submit();
          }else {
           alertify.error('الرجاء اكمال جميع الحقول')
          }
         }
        , function(){

         }
       );

  }

});
</script>
<?php } ?>


<?php if('new-phone' == $section_id){ ?>
<div class="container">
  <h3>اضافة تصويت</h3>
  <div class="add-div p-5 row d-flex justify-content-right">
    <div class="col-md-12">
      <form class="phone-form" method="post">
        <fieldset >

          <div class="form-group">
            <div class="col-md-12">
            <label class="control-label" >قسم التصويت</label>
            <select onchange="changeVoteSelect(this.value);" class="form-control vote-select chosen-select" required name="vote">
              <?php $vote = $DB->query("Select * from `vote` WHERE `deleted` = 0 order by `id` desc"); ?>
              <option hidden></option>
              <?php foreach ($vote as $key => $value): ?>
                <option value="<?=$value['id']?>"><?=$value['title']?></option>
              <?php endforeach; ?>
            </select>
            </div>
          </div>

          <div class="form-group m-b-0">
           <label class="col-md-4 control-label" >نوع الاضافة</label>
            <div class="col-md-12">
               <label class="btn btn-dark">
                <input class="phone-type" onclick="$('.single-phone').show();$('.group-phone').hide();" name="type" value="single" type="radio"  checked><span> رقم واحد </span>
              </label>
               <label class="btn btn-dark">
                <input class="phone-type" onclick="$('.group-phone').show();$('.single-phone').hide();" name="type" value="group"  type="radio"  ><span> مجموعة </span>
              </label>
            </div>
          </div>

          <div class="single-phone">
            <div class="row justify-content-center">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label" >رقم الهاتف</label>
                  <input id="single-phone" name="single_phone" placeholder="مثال : 07707388119" class="form-control" >
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label" >اسم المستخدم</label>
                  <input id="single-name" name="single_name" class="form-control" >
                </div>
              </div>
            </div>
          </div>
          <br>

        <div class="disn group-phone">
          <div class="row justify-content-center">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label" >مجموعة الارقام</label>
                <textarea id="group-phone" name="group_phone" class="form-control" rows="8" cols="80" ></textarea>
              </div>
            </div>
            <div class="col-md-6">
              <label class="control-label" >مجموعة الاسماء</label>
              <textarea id="group-name" name="group_name" class="form-control" rows="8" cols="80" ></textarea>
            </div>
          </div>
        </div>


        <div class="form-group">
          <div class="col-md-12">
          <label class="control-label" >رابط تطبيق الموبايل</label>
          <input name="url" placeholder="مثال : 192.168.0.113" class="form-control" required>
          </div>
        </div>

        <div class="form-group m-b-0">
         <label class="col-md-4 control-label" >نوع الإرسال</label>
          <div class="col-md-12">
             <label class="btn btn-dark">
              <input name="send_type[]" value="sms" type="checkbox" checked><span> SMS </span>
            </label>
             <label class="btn btn-dark">
              <input name="send_type[]" value="whatsapp" type="checkbox"  ><span> Whatsapp </span>
            </label>
          </div>
        </div>

        <br>
        <div class="form-group">
          <div class="row justify-content-center">
            <input type="hidden" name="new-phone" value="new-phone">
            <button data-target="phone-form" type="button" class="mx-3 confirm-phone btn btn-warning w250">إرسال</button>
          </div>
        </div>
        </fieldset>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">

<?php
echo 'var voteData = [';
    foreach ($vote as $key => $value) {
        echo '
        {
            "id" : "'.$value['id'].'",
            "with_name" : "'.$value['with_name'].'",
        },
        ';
    }
    echo '];';
?>



 function changeVoteSelect(v){

   var is_name;
   for(i=0 ; i < voteData.length;i++){
     if(v == voteData[i]['id']){
       is_name = voteData[i]['with_name'];
       break;
     }
   }
   // -*-*-*-*-*-*
   if(1 == is_name){

     $('.single-phone div').children().eq(1).show();
     $('.single-phone div').children().eq(0).addClass('col-md-6').removeClass('col-md-12');

     $('.group-phone div').children().eq(1).show();
     $('.group-phone div').children().eq(0).addClass('col-md-6').removeClass('col-md-12');

   }else{

     $('.single-phone div').children().eq(1).hide();
     $('.single-phone div').children().eq(0).addClass('col-md-12').removeClass('col-md-6');

     $('.group-phone div').children().eq(1).hide();
     $('.group-phone div').children().eq(0).addClass('col-md-12').removeClass('col-md-6');

   }
   // -*-*-*-*-*-*
 }

$(document).on('click','.confirm-phone',function(){

  var target = $(this).attr('data-target');
  // $('.'+target).submit();

  var phone1 = encodeURI($('#single-phone').val());
  var phone2 = encodeURI($('#group-phone').val());

  var name1 = encodeURI($('#single-name').val());
  var name2 = encodeURI($('#group-name').val());

  var t = $("input[name=type]:checked").val();
  var vote = $(".vote-select").val();

  var send_type = $("input:checkbox[name='send_type[]']:checked")
              .map(function(){return $(this).val();}).get();

  alertify.confirm(
    'تأكيد العملية',
     'هل تريد المتابعة؟',
      function(){

          if($('.'+target)[0].checkValidity()) {
            $.post("https://alamani.iq/v/api",{send_type:send_type,vote:vote,single_phone:phone1,group_phone:phone2,single_name:name1,group_name:name2,t:t,type:"insert-phone"},function(result){
            // $.post("/v/api",{send_type:send_type,vote:vote,single_phone:phone1,group_phone:phone2,single_name:name1,group_name:name2,t:t,type:"insert-phone"},function(result){
              // console.log(result);
              result = JSON.parse(result);

              if(result.result == true){
                $('.'+target).submit();
              }else{
                alertify.error('حدثت مشكلة أثناء المزامنة');
              }
            }).done(function(msg){ })
            .fail(function(xhr, status, error) {
              alertify.error('تاكد من الإتصال بالإنترنت');
            });

          }else {
           alertify.error('الرجاء اكمال جميع الحقول')
          }
       }
      , function(){
         // alertify.error('Cancel')
       }
     );
});
</script>
<?php  } ?>
