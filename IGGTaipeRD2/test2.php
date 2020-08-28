<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>FP資源索引v2</title>
</head>
<?php //主控台
    include('PubApi.php');
	include('mysqlApi.php');
    include('scheduleApi.php');
	include('CalendarApi.php'); 
    $data_library="iggtaiperd2";
    $tableName="fpschedule";
	$joinTables=array("fpschedule_now","fpschedule_old");
    mergeTableData($data_library,"fpschedule",$joinTables);
	///ClearAllData($data_library,"fpschedule_merge");
?>
<?php
   function mergeTableData($data_library,$mergeTable,$joinTables){
            ClearAllData($data_library,$mergeTable);
			for ($i=0;$i<count($joinTables);$i++){
			    JoinTableData($data_library,$mergeTable,$joinTables[$i]);
			}
		
 
   }


    function test1(){
		/*
	$Sc  =getMysqlDataArray( $tableName);
		$Sc2  =getMysqlDataArray( $tableName."_old");
		$scmix=addArray($Sc,$Sc2);
	echo count($scmix);
	*/
	}
?>