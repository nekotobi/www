<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>FP資源索引</title>
</head>
<?php
    include('PubApi.php');
	include('mysqlApi.php');
	DefineData();
	DrawMainUI();
?>
<?php
  function DefineData(){
	    //分頁
	  	global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2,$stateType; 
		$BaseURL="ResourceData.php";
		$BackURL= $BaseURL."?Stype_1=".$Stype_1."&Stype_2=".$Stype_2;
		if($Stype_1=="")$Stype_1=0;
		if($Stype_2=="")$Stype_2=0;
	    //資料庫
      	global $tableName,$data_library,$mainData,$typeData,$typeData2; 
		$data_library= "iggtaiperd2";
		$tableName="fpschedule";
		$typeDatat = getMysqlDataArray("scheduletype");	
		$typeData= filterArray($typeDatat,0,"ResourceData");
		$typeData2= filterArray($typeDatat,0,"ResourceType");
		$mainDatat= getMysqlDataArray($tableName); 
		mainData=filterArray($mainDatat,$typeData[$Stype_1]);

  }
  function DrawMainUI(){
	    //主頁
	    DrawRect("FP資源索引","22","#ffffff","20","20","1200","30","#000000");
		//分類
        global $typeData,$typeData2;
		global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2,$stateType; 
		$x=20;$y=60;$w=100;$h=20;
		for($i=0;$i<count($typeData);$i++){
			$Link=$BaseURL."?Stype_1=".$i."&Stype_2=".$Stype_2;
			$BgColor="#000000";
			if($Stype_1==$i)$BgColor="#aa2222";
		    DrawLinkRect($typeData[$i][2],12,"#ffffff",$x,$y,$w,$h,$BgColor,$Link,"1");
			$x+=110;
		}
		$y+=10;$h=12; $w=50;
	    for($i=0;$i<count($typeData2);$i++){
		    $Link=$BaseURL."?Stype_1=".$Stype_1."&Stype_2=".$i;
			$BgColor="#000000";
			if($Stype_2==$i)$BgColor="#aa2222";
		    DrawLinkRect($typeData2[$i][2],10,"#ffffff",$x,$y,$w,$h,$BgColor,$Link,"1");
			$x+=60;
		}
	}
?>