<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>美術工作計畫表</title>
</head>
 <body bgcolor="#b5c4b1"> 
<?php //主控台
      require_once('PubApi.php');
      require_once('mysqlApi.php');
      require_once('CalendarApi.php');
	  require_once('ArtScheduleJavaApi.php');
	  require_once('VTApi.php');
      DefineBaseData();
	  DrawBase();
	  DrawDragUpAreas();
	  ListOnScPlan();
	  // CreatUpForm();
	 // 
	
?>
<?php //define
     function DefineBaseData(){
		      global $URL;
			  $URL="ArtSchedule.php";
			  global $tableName,$data_library;
			  $tableName="artplan";
			  $data_library="iggtaiperd2";
	          global $id;
			  global $rank;
			  global $planDatas, $notSetPlan ,$OnScPlan,$ver;
			  $planDatas_T=getMysqlDataArray($tableName);
			  $planDatas= filterArray( $planDatas_T,0,"plan");
			  $notSetPlan=filterArray( $planDatas_T,2,"");
			  $ver=RemoveArray($planDatas_T,6,"");
			  $OnScPlan_T=RemoveArray($planDatas_T,2,"");
			  $OnScPlan=filterArray($OnScPlan_T,6,"");
			  //layout
			  global $startLoxY,$startLoX,$wid;
			  $wid=8;
			  $startLoxY=100;
              $startLoX=20;			  
			  global    $startDate;
			  $startDate=date("Y-m-1");
			  global  $typeArray,$typeVal;
			  $typeVal=$_POST["EditType"];
			  $typeArray=array(array("--","--"),array("新增","new") );
			  if($typeVal=="")$typeVal="--";
			  DrawButtoms(20,40,$typeArray,$typeVal);
			  global    $inputsTextNames;
			  $inputsTextNames=array("DragID","target","plan","workingDays","name","type","Ecode","startDay","val");
			  SwitchEditType($typeVal);
	 }
	 function DrawButtoms($x,$y,$typeArray,$typeVal){
			  global $URL;
			  for($i=0;$i<count( $typeArray);$i++){
				 $BgColor="#111111";
				 if($typeVal==$typeArray[$i][1])$BgColor="#aa1111";
			       $sendarr =array( array("EditType",$typeArray[$i][1]))  ;
				   sendVal_v2($URL, $sendarr,"check",$typeArray[$i][0],array($x+$i*50,$y,46,18),10, $BgColor );
			  }
	 }
	 function SwitchEditType($typeVal){
		      global $tableName,$data_library;
			  global $URL;
			  CheckSubmit();
		      if($typeVal=="--"){
				  global    $inputsTextNames;
			 	  VTCreatJavaForm( $URL,$tableName,$inputsTextNames);
				  CheckDrag();
			  }
			  if($typeVal=="new"){
				  CreatUpForm();
			  }
	 }
     function DrawDragUpAreas(){ //
	          global  $startLoxY;
			  $startX=20;
			  $wid=35;
			  //  array("進行中","已排程","驗證中","已完成");
           	  $arr=array("ver","mileston");
			  DrawDragUpArea($arr,$startX,$startLoxY-40,$wid,"ver");
	 }
?>
<?php 

     function DrawBase(){
		      global $startLoxY,$startLoX,$wid;  
		      $StartY=date("Y");
			  $StartM=date("n");
			  $MRange=6;
			  $LocX=$startLoX;
			  $LocY=$startLoxY ;
			  $h=100;
		      DrawRect("TaipeiRD2美術進度規畫表" ,"12","#ffffff",18,78,900,20, "#000000");
	          DrawBaseCalendar($StartY,$StartM,$MRange,$LocX,$LocY,$wid,$h);  
			  $LocY+=100;
              ListPlan($LocY);			  
	 }
	  function ListOnScPlan(){
	           global $notSetPlan ,$OnScPlan,$ver;
			   global $startLoxY,$startLoX,$wid;
			   global $startDate;
			   $y=$startLoxY+40;
			   $h=15;
			   $fontSize=12;
			   $BgColor="#ffaaaa";
			   $fontColor="#ffffff";
			   //ver 
			   for($i=0;$i<count($ver);$i++){
				   DrawSingleDragPlan($ver[$i],$startDate,$startLoX,$y-20,$wid, "#ee5555", $fontColor);
			   }
			   	  
			   //一般
			   for($i=0;$i<count($OnScPlan);$i++){
				   DrawSingleDragPlan($OnScPlan[$i],$startDate,$startLoX,$y,$wid,  $BgColor, $fontColor);
				   $y+=20;
			   }
	  }
 
	  function DrawSingleDragPlan($data,$startDate,$startLoX,$y,$wid,$BgColor, $fontColor){
		          $backstr=$data[1]."=".$data[3]."=".$wid ;//0工單code.1人天 2寬度
				  $id= "S=".$backstr;
				  $show=$data[5];
				  $date=$data[2];
				  $WorkDays=$data[3];
				  $xAdd=VTreturnLocX($date,$startDate);
			   	  $sx=$startLoX+ $xAdd*$wid ;
			      VTDrawJavaDragbox( $show,$sx, $y,$wid*$WorkDays, $h,10,  $BgColor, $fontColor,$id);
				  $id= "E=".$backstr ; 
				  $BgColor3="#888888";
				  $x= $sx+$wid*($data[3] );
				  VTDrawJavaDragbox( "" ,$x,$y+2,5,10,5, $BgColor3, $fontColor,$id);
	  }
	  function ListPlan($startY){
		      global $planDatas, $notSetPlan;
			  $x=20;
			  $y=$startY;
			  $w=300;
			  $h=18;
			  $fontSize=12;
			  $BgColor="#aaaaaa";
			  $fontColor="#ffffff";
		      for($i=0;$i<count( $notSetPlan);$i++){
			      $id="code=".$notSetPlan[$i][1];
				  VTDrawJavaDragbox($notSetPlan[$i][5],$x,$y,$w,$h,$fontSize,$BgColor,$fontColor,$id);
				  $y+=20;
			  }
	  }
?>

<?php //up
     function CheckSubmit(){
                if($_POST["submit"]=="新增計畫") UpNewPlan();
				
     }
     function UpNewPlan(){
		      global $data_library,$tableName;
			       $tables=returnTables($data_library,$tableName);
				   $WHEREtable=array();
				   $WHEREData=array();
		           for($i=0;$i<count( $tables);$i++){
				        array_push($WHEREtable, $tables[$i] );
						$data=$_POST[$tables[$i]];
					    array_push($WHEREData,$data);
					    echo  "</br>".$tables[$i].">".$_POST[$tables[$i]]."]";
		              }
				   $stmt=  MakeNewStmtv2($tableName,$WHEREtable,$WHEREData);
				   echo $stmt;
				   SendCommand($stmt,$data_library);
     }
     function CreatUpForm(){
		      $x=20;
			  $y=10;
		      global $URL;
			  global $typeName,$typeArray;
			  global $tableName;
		      $upFormVal=array("art","art",$URL);
			  $UpHidenVal=array(array("tablename",$tableName),
			                     array("code",returnDataCode( )),
								 array("data_type","plan"),
	                            );
		      $UpHidenVal=	addArray( $UpHidenVal,$typeArray);	
		      $inputVal=array(array("text","plan","plan",10,20,$y,400,20,$BgColor,$fontColor,"" ,30),
                              array("submit","submit","",10,260,$y,200,20,$BgColor,$fontColor,"新增計畫" ,15),
	                          );		 
		      upSubmitform($upFormVal,$UpHidenVal, $inputVal);
	 }
?>