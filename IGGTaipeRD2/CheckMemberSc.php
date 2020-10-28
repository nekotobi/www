<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>人員工作表</title>
</head>
 <body bgcolor="#b5c4b1"> 
<?php //主控
     require_once('PubApi.php');
	 require_once('mysqlApi.php');
	 require_once('scheduleApi.php');
     require_once('VTApi.php');
	 require_once('ResChecker2JavaApi.php');

DrawCalendar();
?>

<?php
  	function DefineDatas(){
		     $tableName=returnScName("now");
		     $data_library="iggtaiperd2";
			 global $Members;
			 $Members_t=getMysqlDataArray(members);
			 $Members=filterArray( $Members_t);
	}

?>
<?php
    function DrawCalendar(){
			 $StartY=date("Y");
			 $StartM=date("n");
			 $MRange=2;
			 $LocX=20;
			 $LocY=100;
			 $wid=15;
			 $h=18;
	         DrawBaseCalendar($StartY,$StartM,$MRange,$LocX,$LocY,$wid,$h);
	}
   
   
?>