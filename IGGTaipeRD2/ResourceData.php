<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>FP資源索引</title>
</head>
<?php
    include('PubApi.php');
	include('mysqlApi.php');
	 DrawMainUI();
?>
<?php
  function DrawMainUI(){
		global $tableName;
		global $data_library;
		global $width,$TableType,$Names;
		global $BackURL;
		global $BaseData;
		global $radio_1,$radio_2;
		$data_library= "iggtaiperd2";
		$tableName="outsourcing";
      	$datasTmp= getMysqlDataArray($tableName); 	//0名稱 1序號 2尺寸 3類別
		$BaseData= filterArray($datasTmp,0,"data");
		$width=returnDataArray($datasTmp,0,"size")   ;
		$TableType=returnDataArray($datasTmp,0,"type")   ;
		$Names=returnDataArray($datasTmp,0,"name")   ;
		$types_1=array("角色","概念圖","模型","場景","VFX","UI");
		$radio_1=array("個人","工作室");
		$radio_2=array("差","稍差","普通","可","優");
		$x=20;
		$y=60;
	    $BackURL="Outsourcing.php";
	    DrawRect("外包資源列表","22","#ffffff","20","20","1400","30","#000000");
		DrawTitle($Names,$x,$y,"#222222","#ffffff");
 
        DrawOutsourcings($BaseData,$y);
	}
?>