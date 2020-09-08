<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 
   <title>VT資源索引</title>
</head>
<body bgcolor="#b5c4b1"> 
<?php //主控台
     require_once('PubApi.php');
	 require_once('mysqlApi.php');
	 require_once('scheduleApi.php');
     require_once('VTApi.php');
     DefineBaseData();
	// saveUpdateTime("",);
     ListRes("hero");
	// gettaskName( );
?>

<?php
     function DefineBaseData(){
			  DefineVTTableName();
	          DefineArrayDatas();
     }
	 function DefineArrayDatas(){
	          global $data_library;
	          global $SC_tableName;
	          global $Res_tableName;
			  global $SC_tableName_now,$SC_tableName_old,$SC_tableName_Merge;
			  global $SC_nowArray;
			  $SC_nowArray= getVTSCData("mix");
			  global $Res_Array;
			  $Res_Array=getMysqlDataArray($Res_tableName);
	 }
?>

<?php //List
     function ListRes($type){
	          global $Res_Array;
			  $List_Array=filterArray($Res_Array,0,$type);
			  for($i=0;$i<count($List_Array);$i++){
			      echo "</br>".$List_Array[$i][2];
			  }
			 
	 }
?>

