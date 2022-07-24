<?php
if('ajax-process.php' == basename($_SERVER['PHP_SELF'])){
  die('ERR#404');
}
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
// print_r($_POST);
// print_r($_FILES);
// print_r($_FILES);

if(!empty($_SESSION)){
	$user_id = $_SESSION[$sesion_suffix.'user_id'];
}else{
	$user_id = 0;
}
#
##
###
#
##
###
if(isset($_POST['type']) && "get-phone" == $_POST['type']){

// print_r($_POST);
// die;
$limit = $_POST['limit_pp'];
$count_limit=0;
if(!empty($_POST['page'])){
  $count_limit=1;
  $page_count=$_POST['page']-1;
  $count_limit=$page_count*$limit;
}

$whr = '';

if(isset($_POST['vote_section']) && '' != $_POST['vote_section']){
  if(0 != $_POST['vote_section']) { $whr .= ' and `p1`.`fkvote`='.$_POST['vote_section'].' '; }
}else {
  die();
}
#
if(isset($_POST['vote'])){
  if("0" == $_POST['vote']) { $whr .= ' and `p1`.`voted`=0 '; }
  if("1" == $_POST['vote']) { $whr .= ' and `p1`.`voted`!=0 '; }
}
#
if(isset($_POST['result'])){
  if("1" == $_POST['result']) { $whr .= ' and `p1`.`vote`=1 '; }
  if("2" == $_POST['result']) { $whr .= ' and `p1`.`vote`=2 '; }
  if("3" == $_POST['result']) { $whr .= ' and `p1`.`vote`=3 '; }
}
#
if(isset($_POST['note'])){
  if("0" == $_POST['note']) { $whr .= ' and `p1`.`note`="" '; }
  if("1" == $_POST['note']) { $whr .= ' and `p1`.`note`!="" '; }
}
#
if(isset($_POST['start_date']) && !empty($_POST['start_date']) && isset($_POST['end_date']) && !empty($_POST['end_date'])){
  $start = ($_POST['start_date']);
  $end = ($_POST['end_date']);
  $whr .= ' and from_unixtime(`p1`.`voted`, "%Y-%m-%d") >= "'.$start.'" and from_unixtime(`p1`.`voted`, "%Y-%m-%d") <= "'.$end.'" ';

}
// echo $whr; die;
$res = $DB->query("Select `p1`.`id`,`p1`.`phone`,`p1`.`vote`,`p1`.`voted`,`p1`.`note`,`p1`.`created`,`p1`.`admin_note`,`v`.`title`
from `phone` `p1` inner join `vote` `v` on
`p1`.`fkvote` = `v`.`id` and
`p1`.`deleted` = 0 and `v`.`deleted` = 0 ".$whr."
order by `p1`.`id` desc limit $count_limit,$limit");

$phones = [];
foreach ($res as $key => $value) {
  $phones[] = [
    'id'=>$value['id'],
    'phone'=>$value['phone'],
    'note'=>$value['note'],
    'vote'=>$value['vote'],
    'voted'=>(0!=$value['voted']?date('Y-m-d H:i',$value['voted']):''),
    'created'=>date('Y-m-d',$value['created']),
    'admin_note'=>$value['admin_note'],
  ];
}

echo json_encode($phones);

}
#
##
###
if(isset($_POST['type']) && "insert-phone" == $_POST['type']){

  if('group' == $_POST['t']){
    $phones = explode('%0A', $_POST['group_phone']);
    $names = explode('%0A', $_POST['group_name']);

   }else{
     $phones = explode('%0A', $_POST['single_phone']);
     $names = explode('%0A', $_POST['single_name']);
    }
    #
    #
    $votes = $DB->row("Select `msg`,`with_name`,`with_url` from `vote` WHERE `id` = ? and `deleted` = 0",[$_POST['vote']]);
    #
    foreach ($_POST['send_type'] as $key_type => $tvalue) {

      if('sms' == $tvalue){

        foreach ($phones as $key => $value) {

          $phonesRow = $DB->row("Select * from `phone` WHERE `phone` = ? and `fkvote`=? and `voted` = 0 and `deleted` = 0
          order by `id` desc",[(int)$value ,$_POST['vote']]);
          if(empty($phonesRow)){

            $date = time();
            if(1 == $votes['with_name']){
              $DB->query("INSERT INTO `phone`(`fkvote`,`phone`,`name`,`created`) VALUES(?,?,?,?)",
              [$_POST['vote'],$value,$names[$key],$date]);
            }else{
              $DB->query("INSERT INTO `phone`(`fkvote`,`phone`,`name`,`created`) VALUES(?,?,?,?)",
              [$_POST['vote'],$value,'',$date]);
            }

          }
        }

      }
      // ##########
      // ##########
      if('whatsapp' == $tvalue){

        foreach ($phones as $key => $value) {

          $phonesRow = $DB->row("Select * from `phone_whats` WHERE `phone` = ? and `fkvote`=? and `deleted` = 0
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

          }

        }

      }
      // ##########
      // ##########
    }





  echo json_encode(array('result' =>true));


}
#
##
###
if(isset($_POST['type']) && "admin-note-change" == $_POST['type']){

  $DB->query("UPDATE `phone` SET `admin_note`=? WHERE `id`=?",
  [$_POST['val'],$_POST['id']]);

  echo json_encode(array('result' =>true));

}
#
##
###
if(isset($_POST['new-vote']) && "new-vote" == $_POST['new-vote']){

  $date = time();
  $with_url = (isset($_POST['with_url']) ? 1 : 0);
  $with_name = (isset($_POST['with_name']) ? 1 : 0);

  $DB->query("INSERT INTO `vote`(`title`,`msg`,`home_msg`,`with_url`,`with_name`,`created`)
  VALUES(?,?,?,?,?,?)",
  [$_POST['title'],$_POST['msg'],$_POST['home_msg'],$with_url,$with_name,$date]);

  echo json_encode(array('result' =>true));

}
#
##
###
if(isset($_POST['edit-vote']) && "edit-vote" == $_POST['edit-vote']){

    $date = time();
    $with_url = (isset($_POST['with_url']) ? 1 : 0);
    $with_name = (isset($_POST['with_name']) ? 1 : 0);

    $res = $DB->query("UPDATE `vote` SET `title`=?,`msg`=?,`home_msg`=?,`with_url`=?,`with_name`=? WHERE `id`=?",
    [$_POST['title'],$_POST['msg'],$_POST['home_msg'],$with_url,$with_name,$_POST['i']]);
    echo json_encode(array('result' =>true));

}
#
##
###
if(isset($_POST['type']) && "delete-vote" == $_POST['type']){

  $date = time();
  $DB->query("UPDATE `vote` SET `deleted` = ? WHERE `id` = ?",[$date ,$_POST['id']]);
  echo json_encode(array('result' =>true));

}

die;
