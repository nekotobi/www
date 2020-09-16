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
	  ListRes( );
	// saveUpdateTime("",);
   //  ListRes("hero");
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
			  global $Restype;
			  $Restype="hero";
			  $SC_nowArray= getVTSCData("mix");
			  global $Res_Array;
			  $Res_Array=getMysqlDataArray($Res_tableName);
			  global $FocusRes;
			  $FocusRes= getFocusRes();
			  global $startY;
			  $startY=100;
			
	 }
?>

<?php //List
     function ListRes( ){
	          global $FocusRes;
			  global $startY;
			  global $Restype;
			  $BgColor="#222222";
			  $fontColor="#ffffff";
			  $fontSize=10;
			  for($i=0;$i<count($FocusRes);$i++){
			      $msg=$FocusRes[$i][0];
				  $pic= returnPic($Restype,$msg);
				  DrawRect($msg,$fontSize,$fontColor,20,$startY,300,18,$BgColor);
				  DrawLinkPic($pic,$startY,30,  38,38,$pic);
                  $startY+=40; 
			  }
			
			  /*
			  $List_Array=filterArray($Res_Array,0,$type);
			  for($i=0;$i<count($List_Array);$i++){
			      echo "</br>".$List_Array[$i][2];
			  }
			   */
	 }
?>
<?php //資源排序
     function getFocusRes(){
	          $Ev=  getLaterEventData();
			  global $Restype;
			  $res=array();
	          for ($i=0;$i<count($Ev);$i++){
				   $e= returnRequestRes($Ev[$i][6],$Ev[$i][10],$Restype);
				   $res=addArray($res,$e);
	          }
			  return $res;
	 }
 
	 function returnRequestRes($ResStr,$days,$Type){
		      $filterStr="h";
			  if($Restype=="mob")$filterStr="m";
			  if($Restype=="Escene")$filterStr="e";
		      $str=explode("_",$ResStr);
			  $ResArray=array();
			  for ($i=0;$i<count($str);$i++){
				  if( strpos($str[$i], $filterStr) !== false){
				     Array_Push($ResArray,array($str[$i],$days));
				  }
	          }
			  return $ResArray;
	 }


?>


