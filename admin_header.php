<?php
#
##
###
if(empty($_SESSION) || !isset($_SESSION[$sesion_suffix.'user_id']) || !isset($_SESSION[$sesion_suffix.'user_name'])){
  die('<meta http-equiv="refresh" content="0;url=/v/moder/?out" />جاري الخروج');
}
$user_id = $_SESSION[$sesion_suffix.'user_id'];
$user_lvl = $_SESSION[$sesion_suffix.'user_lvl'];
$limit_pp = 20;
$count=0;
if(!empty($_GET['page']))
{
$count=1;
$page_count=$_GET['page']-1;
$count=$page_count*$limit_pp;
}
#
##
###
function Page_num($pages, $Page, $limitpp, $pagelink, $gearcontrol = [5,3]) {
	global $lang;
	$first = $last = '';
	$start_page = 1;
	$limitpageoff = $gearcontrol[0];
	$numofpages = $pages;
	$Page = ($Page > $numofpages ? $numofpages : $Page);
	if($numofpages > 1) {
		$__page = '<div class="c col-xs-12"><ul class="pagination" >';
		$pageoff = 1;
		if($Page >= $limitpageoff)
			$pageoff = ($Page - $limitpageoff) + $gearcontrol[1];
		# just first page show isset
		if($Page != $start_page)
			if($Page >= $limitpageoff)
				$first = ' <li class=start_end><a  href="' . $pagelink . 1 . '"> الأولى </a></li> ';
		$__page .= $first;
		# paging loop link
		// $__page .= '<div class=pagingloop>';
		for($i = $pageoff; $i < ($pageoff + $limitpageoff); $i++) {
			if($i == ($numofpages + 1))
				break;
			if($i != $Page) {
				$start_page = $start_page + $limitpp;
				$showpage = ' <li><a href="' . $pagelink . $i . '">' . $i . '</a> </li> ';
			}else {
				$start_page = $start_page + $limitpp;
				$showpage = ' <li class=current> <a >' . $i . '</a> </li> ';
			}
			// print paging loop here
			$__page .= $showpage;
		}
		// $__page .= '</div>';
		# end page show isset
		if($Page != $numofpages)
			if($numofpages > $limitpageoff)
				$last = ' <li class=start_end><a  href="' . $pagelink . $numofpages . '"> الأخيرة </a></li> ';
		$__page .= $last;
		$__page .= '</ul></div>';
		return $__page;
	}
}
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
  <meta charset="utf-8">
  <title>شركة الاماني للتجارة العامة</title>
  <link rel="stylesheet" href="/v/s/common7.3.css">
  <link rel="stylesheet" href="/v/s/style.css?<?=$rand_num;?>">
  <link rel="stylesheet" href="/v/s/alertify.rtl.min.css">
  <link rel="stylesheet" href="/v/s/aos.css">
  <link rel="stylesheet" href="/v/s/choosen.css?33">
  <script src="/v/s/jquery-min.js"></script>

  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="s/favicon.ico" />
  <meta charset="utf-8">
</head>
<style media="screen">
body{background-color: #add1fc;}
.add-div{
  background-color: #006bea;
  font-size: 18px;
  color: #fff;
  line-height: 1.4;
  border-radius: 5px;
  box-shadow: 0 0px 40px 0px rgba(0, 0, 0, 0.15);
}
.table-input{
  border: none;
  background-color: transparent;
  width: 100px;
}
/* start pagination */
ul.pagination{
  margin: 20px 0;
}
ul.pagination li{
  display: inline-block;
  background-color: #ffc107;
  border-radius: 50px;
}
ul.pagination li.current{
  background-color: red;
}
ul.pagination li a{
  color: #000;
  font-size: 18px;
  display: block;
  /* font-weight: 900; */
}
/* end pagination */
.responsive-table{
  width: 100%;
  padding:5px;
}
.responsive-table li {
  border-radius: 3px;
  padding: 10px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 5px;
}
.responsive-table .table-header {
font-family: 'Droid Arabic kufi','kufi',arial!important;
background-color: #95A5A6;
font-size: 18px;
text-transform: uppercase;
letter-spacing: 0.03em;
}
.responsive-table .table-row {
background-color: #ffffff;
box-shadow: 0px 0px 9px 0px rgba(0, 0, 0, 0.1);
}
@media all and (max-width: 767px) {
.responsive-table .table-header {
  display: none;
}
.responsive-table li {
  display: block;
  height: auto!important;
}
.responsive-table .col {
  flex-basis: 100%;
}
.responsive-table .col {
  display: flex;
  padding: 10px 0;
}
.responsive-table li.accept-div{
  color: #fff!important;
  background-color: green!important;
}
.responsive-table .col:before {
  color: #6C7A89;
  padding-right: 10px;
  content: attr(data-label);
  flex-basis: 50%;
  text-align: right;
}
}

</style>
<style media="screen">

/*  */
/*  */
/*  */
/*Style for the first level menu bar*/
ul.menu-nav{
/*
position:fixed;
top:0; */
width:100%;
height:3em;
margin-bottom: 50px;
padding:0 10px;
background:#006bea;
color:#eee;
}

ul.menu-nav > li{
float:right;
list-style-type:none;
position:relative;
}

ul.menu-nav li ,ul.menu-nav label{
position:relative;
display:block;
padding:0 18px 0 12px;
line-height:3em;
transition: background 0.3s;
cursor:pointer;
color: #fff;
font-size: 15px;
}
ul.menu-nav li a{
  color: inherit;
  display: block;
  padding: 0 10px;
}
ul.menu-nav label:after{
content:"";
position:absolute;
display:block;
top:50%;
right:5px;
width:0;
height:0;
border-top:4px solid rgba(255,255,255,.5);
border-bottom:0 solid rgba(255,255,255,.5);
border-left:4px solid transparent;
border-right:4px solid transparent;
transition:border-bottom .1s, border-top .1s .1s;
}

ul.menu-nav li a:hover, ul.menu-nav label:hover,
ul.menu-nav input:checked ~ ul.menu-nav label{background:rgba(0,0,0,.3);}

ul.menu-nav input:checked ~ ul.menu-nav label:after{
border-top:0 solid rgba(255,255,255,.5);
border-bottom:4px solid rgba(255,255,255,.5);
transition:border-top .1s, border-bottom .1s .1s;
}

/*hide the inputs*/
ul.menu-nav input{display:none}

/*show the second levele menu of the selected voice*/
ul.menu-nav input:checked ~ ul.submenu{
max-height:300px;
transition:max-height 0.1s ease-in;
}

/*style for the second level menu*/
ul.menu-nav ul.submenu{
max-height:0;
z-index: 9;
padding:0;
overflow:hidden;
list-style-type:none;
background:#444;
box-shadow:0 0 1px rgba(0,0,0,.3);
transition:max-height 0.1s ease-out;
position:absolute;
min-width:100%;
}

ul.menu-nav li.red-li{float: left;}
ul.menu-nav li.red-li a{background: #f44336}
ul.menu-nav li.red-li a:hover{background: #f44336ad;}
ul.menu-nav ul.submenu li a{
display:block;
padding:12px;
color:#ddd;
text-decoration:none;
box-shadow:0 -1px rgba(0,0,0,.5) inset;
transition:background .3s;
white-space:nowrap;
}

ul.menu-nav ul.submenu li a:hover{
background:rgba(0,0,0,.3);
}
/*  */
/*  */
/*  */

/**
 * Checkboxes
 */
.checkbox {
  cursor: pointer;
  -webkit-tap-highlight-color: transparent;
  -webkit-user-select: none;
  -moz-user-select: none;
  user-select: none;
  /* font-size: 25px; */
}
.checkbox > input[type="checkbox"] {
  position: absolute;
  opacity: 0;
  z-index: -1;
}

.checkbox__icon {
  display: inline-block;
  /* Default State */
  color: #999;
  /* Active State */
}
input[type="checkbox"]:checked ~ .checkbox__icon {
  color: #2A7DEA;
}

/* IE6-8 Fallback */
@media \0screen\,screen\9 {
  .checkbox__icon {
    display: none;
  }

  .checkbox > input[type="checkbox"] {
    position: static;
  }
}
/****************************
 ****************************
 ****************************
 * Helpers
 */
.checkbox__icon:before {
  font-family: "icons";
  speak: none;
  font-style: normal;
  font-weight: normal;
  font-variant: normal;
  text-transform: none;
  /* Better Font Rendering =========== */
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

.icon--check:before, input[type="checkbox"]:checked ~ .checkbox__icon:before {
  content: "\e601";
}

.icon--check-empty:before, .checkbox__icon:before {
  content: "\e600";
}

@font-face {
  font-family: "icons";
  font-weight: normal;
  font-style: normal;
  src: url("data:application/x-font-woff;charset=utf-8;base64,d09GRk9UVE8AAAR4AAoAAAAABDAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABDRkYgAAAA9AAAAPgAAAD4fZUAVE9TLzIAAAHsAAAAYAAAAGAIIvy3Y21hcAAAAkwAAABMAAAATBpVzFhnYXNwAAACmAAAAAgAAAAIAAAAEGhlYWQAAAKgAAAANgAAADYAeswzaGhlYQAAAtgAAAAkAAAAJAPiAedobXR4AAAC/AAAABgAAAAYBQAAAG1heHAAAAMUAAAABgAAAAYABlAAbmFtZQAAAxwAAAE5AAABOUQYtNZwb3N0AAAEWAAAACAAAAAgAAMAAAEABAQAAQEBCGljb21vb24AAQIAAQA6+BwC+BsD+BgEHgoAGVP/i4seCgAZU/+LiwwHi2v4lPh0BR0AAAB8Dx0AAACBER0AAAAJHQAAAO8SAAcBAQgPERMWGyBpY29tb29uaWNvbW9vbnUwdTF1MjB1RTYwMHVFNjAxAAACAYkABAAGAQEEBwoNL2X8lA78lA78lA77lA6L+HQVi/yU+JSLi/iU/JSLBd83Fffsi4v77Pvsi4v37AUOi/h0FYv8lPiUi4v33zc3i/s3++yLi/fs9zeL398F9wCFFftN+05JzUdI9xr7GveR95FHzwUO+JQU+JQViwwKAAMCAAGQAAUAAAFMAWYAAABHAUwBZgAAAPUAGQCEAAAAAAAAAAAAAAAAAAAAARAAAAAAAAAAAAAAAAAAAAAAQAAA5gEB4P/g/+AB4AAgAAAAAQAAAAAAAAAAAAAAIAAAAAAAAgAAAAMAAAAUAAMAAQAAABQABAA4AAAACgAIAAIAAgABACDmAf/9//8AAAAAACDmAP/9//8AAf/jGgQAAwABAAAAAAAAAAAAAAABAAH//wAPAAEAAAAAAACkYCfgXw889QALAgAAAAAAz65FuwAAAADPrkW7AAD/4AIAAeAAAAAIAAIAAAAAAAAAAQAAAeD/4AAAAgAAAAAAAgAAAQAAAAAAAAAAAAAAAAAAAAYAAAAAAAAAAAAAAAABAAAAAgAAAAIAAAAAAFAAAAYAAAAAAA4ArgABAAAAAAABAA4AAAABAAAAAAACAA4ARwABAAAAAAADAA4AJAABAAAAAAAEAA4AVQABAAAAAAAFABYADgABAAAAAAAGAAcAMgABAAAAAAAKACgAYwADAAEECQABAA4AAAADAAEECQACAA4ARwADAAEECQADAA4AJAADAAEECQAEAA4AVQADAAEECQAFABYADgADAAEECQAGAA4AOQADAAEECQAKACgAYwBpAGMAbwBtAG8AbwBuAFYAZQByAHMAaQBvAG4AIAAxAC4AMABpAGMAbwBtAG8AbwBuaWNvbW9vbgBpAGMAbwBtAG8AbwBuAFIAZQBnAHUAbABhAHIAaQBjAG8AbQBvAG8AbgBHAGUAbgBlAHIAYQB0AGUAZAAgAGIAeQAgAEkAYwBvAE0AbwBvAG4AAAAAAwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA==") format("woff");
}

/*  */
/*  */
/*  */
.red-c{
  position: relative;
}
.red-c::after{
  content: '';
  position: absolute;
  width: 15px;
  height: 15px;
  border-radius: 50px;
  background-color: red;
  top: 20px;
  right: -5px;
}
.green-c{
  position: relative;
}
.green-c::after{
  content: '';
  position: absolute;
  width: 15px;
  height: 15px;
  border-radius: 50px;
  background-color: green;
  top: 20px;
  right: -5px;
}
.h0{height:0;overflow:hidden;}
</style>
<style media="screen">
  .cust-print{
    /* border: 2px solid #000; */
  }
</style>
<body>


<?php
#
##
###
if(!empty($id) && 'print' == $section_id){
  $cust = $DB->row("Select * from `cust` WHERE `deleted` = 0 and `id`=? ",[$id]);
?>
<div class="container">
  <br><br><br>
  <div class="row justify-content-center">
    <div class="col-md-4 cust-print p-5">
      <h2 style="font-size:25px;" class=c>شركة الأماني للتجارة العامة</h2>
      <h1 style="font-size:25px;" class=c><?=$id?></h1>
      <h1 style="font-size:15px;" dir=ltr class=c>( <?=date('Y-m-d h:i:s a',$cust['created'])?> )</h1>
    </div>
  </div>
</div>
<script> window.print(); </script>

<?php die; } ?>

<ul class="menu-nav" id="menu">
  <li> <a href="/v/cp/">شركة الاماني</a> </li>
  <li class="red-li"><a  href="/v/moder/?out" >تسجيل الخروج</a></li>
  <li title="" class="red-li"><?=$_SESSION[$sesion_suffix.'user_title']?></li>
</ul>
