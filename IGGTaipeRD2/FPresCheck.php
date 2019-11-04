<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>FP資源索引v2</title>
</head>
<?php //主控台
    include('PubApi.php');
   DefineBaseData();
   CookieSet();
   ShowButton();
   ListContent();
?>

<?php //title
     function CookieSet(){
		      global $BaseURL;
		      global  $CookieArray,$MysQlArray;
		 	  $CookieArray=array("type1","type2");
			  $MysQlArray=array(0,12);
			  setcookies($CookieArray,$BaseURL);
	          SetGlobalcookieData( $CookieArray);
			 // CheckCookie($CookieArray);
			  
	 }
     function DefineBaseData(){
		      global $BaseURL;
		      $BaseURL="FPresCheck.php";
	          $data_library= "iggtaiperd2";
		      $tableName="fpresdata"; 
			  //$stmp= getMysqlDataArray("fpschedule");	
			  global $ResData;
			  $ResData=getMysqlDataArray( $tableName);	
		      //$ScheduleData  =  filterArray($stmp,0,"data");
		      //$ScheduleDataTitle=filterArray($ScheduleData,5,"工項");
			  global $type1Title;
			  $type1Title=array( 
			           array("英雄","hero"),
					   array("怪物","mob"),
					   array("魔王","boss")
			   );
			  global $type2Title;
			   $type2Title=array( 
			           array("m1","m1"),
					   array("m2","m2"),
					   array("m3","m3"),
					   array("m4","m4"),
					   array("all","all"),
			   );;
			  global $CookieArray;
		
			
	 } 
     function ShowButton(){
		      global $type1Title,$type2Title;
			  $Rect=array(20,40,100,20);
			  DrawButton($Rect,$type1Title,"type1" );
			  $Rect=array(20,70,100,20);
		      DrawButton($Rect,$type2Title,"type2" );
	 }
	 function DrawButton($Rect,$btArray,$arraytype,$BgColor="#000000",  $fontColor="#ffffff"){
		    global $BaseURL;
		    for($i=0;$i<count( $btArray);$i++){
			      $BGC=$BgColor;
			      if($_COOKIE[$arraytype]==$btArray[$i][1])$BGC="#ee0000";
				  $valArray=array(array($arraytype,  $btArray[$i][1]));
				  $SubmitName="submit";
				  $SubmitVal= $btArray[$i][0] ;
				  $Rect[0]+=110;
			 	  sendVal($BaseURL,$valArray,$SubmitName,$SubmitVal,$Rect,10, $BGC,$fontColor,"true");
			  }
	 
	 }
?>

<?php //List
     function ListContent(){
	          global  $CookieArray,$MysQlArray;
              for($i=0;$i<count($CookieArray);$i++)  if($_COOKIE[$CookieArray[$i]]=="")return;
			  global $ResData;
			  $t1=$_COOKIE[$CookieArray[0]];
			  $t2=$_COOKIE[$CookieArray[1]];
			  $data=filterArray( $ResData,0,$t1);
			  if($t2!="all") $data=filterArray( $data,$MysQlArray[1],$t2);
			   $data= SortList( $data,3);
			  $size= filterArray( $ResData,0,"size");
			  $title= filterArray( $ResData,0,"name");
			  $ListArray=array(2,3,4);
			  $Rect=array(20,100,100,20);
 		      for($i=0;$i<count($data);$i++){
			     DrawSingle($data[$i],$Rect,$ListArray,$size[0]);
				 $Rect[1]+=22;
			  }
	 }
	 function DrawSingle($Base,$Rect,$ListArray,$size){
		    for($i=0;$i<count($ListArray);$i++){
				 $s=$ListArray[$i];
 
				 $Rect[2]=$size[$s];
				 $fontColor="#000000";
				 $BgColor="#ffffff";
	             DrawRect_Layer($Base[$s],12,$fontColor,$Rect,$BgColor,$Layer);
				 $Rect[0]+=($Rect[2]+2);
		       } 
	 }

?>