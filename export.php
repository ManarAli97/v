<?php
// if(empty($_SESSION) || !isset($_SESSION[$sesion_suffix.'user_id']) || !isset($_SESSION[$sesion_suffix.'user_name'])){
//   die('<meta http-equiv="refresh" content="0;url=/v/moder/?out" />جاري الخروج');
// }
#
##
###
if(isset($_GET['t']) && 'phone-search-export' == $_GET['t']){


    require __DIR__ . '/class.excel.php';
    $excel = new ExportDataExcel('browser');
    $excel->filename = "phone-".date('Y-m-d_h:i_A').".xls";
    $excel->initialize();

    $whr = '';

    if(isset($_GET['vote_section'])){
      if(0 != $_GET['vote_section']) { $whr .= ' and `p`.`fkvote`='.$_GET['vote_section'].' '; }
    }
    #
    if(isset($_GET['vote'])){
      if("0" == $_GET['vote']) { $whr .= ' and `p`.`voted`=0 '; }
      if("1" == $_GET['vote']) { $whr .= ' and `p`.`voted`!=0 '; }
    }
    #
    if(isset($_GET['result'])){
      if("1" == $_GET['result']) { $whr .= ' and `p`.`vote`=1 '; }
      if("2" == $_GET['result']) { $whr .= ' and `p`.`vote`=2 '; }
      if("3" == $_GET['result']) { $whr .= ' and `p`.`vote`=3 '; }
    }
    #
    if(isset($_GET['note'])){
      if("0" == $_GET['note']) { $whr .= ' and `p`.`note`="" '; }
      if("1" == $_GET['note']) { $whr .= ' and `p`.`note`!="" '; }
    }
    #
    if(isset($_GET['start_date']) && !empty($_GET['start_date']) && isset($_GET['end_date']) && !empty($_GET['end_date'])){
      $start = ($_GET['start_date']);
      $end = ($_GET['end_date']);
      $whr .= ' and from_unixtime(`v`.`voted`, "%Y-%m-%d") >= "'.$start.'" and from_unixtime(`v`.`voted`, "%Y-%m-%d") <= "'.$end.'" ';

    }


    $phones = $DB->query("Select `p`.`phone`,`p`.`vote`,`p`.`voted`,`p`.`note`,`p`.`created`,`v`.`title`
    from `phone` `P` inner join `vote` `v` on
    `p`.`fkvote` = `v`.`id` and
    `p`.`deleted` = 0 and `v`.`deleted` = 0 ".$whr."
    order by `p`.`id` desc ");

    $r = array(
    'رقم الهاتف',
    'تاريخ التصويت',
    'التصويت',
    'الملاحظة',
    'تاريخ الإضافة',
    'قسم التصويت',
    );
    $excel->addRow($r);
    //***********************************

      foreach ($phones as $key => $value) {

        $r = array(
        $value['phone'],
        (0!=$value['voted']?date('Y-m-d H:i',$value['voted']):''),
        $value['vote'],
        $value['note'],
        date('Y-m-d',$value['created']),
        $value['vtitle'],
      );
        $excel->addRow($r);
      }

    //***********************************
    $excel->finalize();

  die;

}
#
##
###
if(isset($_GET['t']) && 'phone' == $_GET['t']){


   require __DIR__ . '/class.excel.php';
    $excel = new ExportDataExcel('browser');
    $excel->filename = "phone-".date('Y-m-d_h:i_A').".xls";
    $excel->initialize();

    $phones = $DB->query("Select * from `phone_cust` ");
    // print_r($phones);
    // die;


    $r = array(
    'رقم الهاتف',
    );
    $excel->addRow($r);
    //***********************************

      foreach ($phones as $key => $value) {


        $r = array(
        $value['phone'],
      );
        $excel->addRow($r);
      }

    //***********************************
    $excel->finalize();
}

die;
