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
			  //計畫表
			  global $planDatas, $notSetPlan ,$OnScPlan,$ver;
			  global $LastLines;
			  $planDatas_T=getMysqlDataArray($tableName);
			  $planDatas= filterArray( $planDatas_T,0,"data");
			  $notSetPlan=filterArray($planDatas,2,"");
			  // $ver=RemoveArray($planDatas,6,"");
			  $OnScPlan_T=RemoveArray($planDatas,2,"");
			  $OnScPlan=filterArray($OnScPlan_T,6,"");
			  //projects;
			  global $projects,$projects_t;
			  $projects_t=filterArray( $planDatas_T,0,"project");;
			  $projects=returnArraybySort( $projects_t,5);
		      global $DefuseProject;
			  $DefuseProject_t=filterArray( $projects_t,9,"defuse");
			  $DefuseProject= $DefuseProject_t[0][5];
			  global    $projectPlans;//,$projectPlansVer;
			  $projectPlans=CollectProjectPlan($OnScPlan,$projects); 
			//  $projectPlansVer=CollectProjectPlan($ver,$projects); 
			  
			  //layout
			  global $startLoxY,$startLoX,$wid;
			  $wid=8;
			  $startLoxY=100;
              $startLoX=20;			  
			  global    $startDate;
			  $startDate=date("Y-m-1");
			  global  $typeArray,$typeVal;
			  $typeVal=$_POST["EditType"];
			  $typeArray=array(array("--","--"),array("新增","new")  );
			  if($typeVal=="")$typeVal="--";
			  DrawButtoms(20,40,$typeArray,$typeVal);
			  global    $inputsTextNames;
			  $inputsTextNames=array("DragID","target","plan","workingDays","name","type","Ecode","startDay","val","line","project");
			  SwitchEditType($typeVal);
	 }
	 function CollectProjectPlan($OnScPlan,$projects){
	          $projectPlans=array();
			  for($i=0;$i<count($projects);$i++){
			      $p=filterArray($OnScPlan,8,$projects[$i]);
				  $projectPlans[$i]=$p;
			  }
			  return   $projectPlans;
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
			  if($_POST["submit"]=="新增計畫") UpNewPlan();
	          pubUpform();//檢查共用表格上傳
			  if($_POST["line"]=="編輯"){ 
                 $code=$_POST["Ecode"];
			     DrawMysQLEdit($data_library,$tableName,$code,$URL,$typeArray,"修改".$code."表格內容");
				 return;
				 }
		      if($_POST["line"]=="刪除"){ 
				 VTDeletPlan($_POST["Ecode"]);
			     ReLoad();
			  }
		      if($typeVal=="--"){
				  global    $inputsTextNames;
			 	  VTCreatJavaForm( $URL,$tableName,$inputsTextNames);
				  CheckDrag();
				  DrawBase();
	              DrawDragUpAreas();
	            
			  }
			  if($typeVal=="new"){
				  CreatUpForm();
			  }
		
	 }
     function DrawDragUpAreas(){ //
	          global  $startLoxY;
			  $startX=20;
			  $wid=40;
			  //  array("進行中","已排程","驗證中","已完成");
			  //global $projects;
			//  DrawDragUpArea($projects,$startX  ,$startLoxY-40,$wid,"project");
           	//  $arr=array("ver");
			  //DrawDragUpArea($arr,$startX+120,$startLoxY-40,$wid,"ver");
			  $arr=array("編輯","刪除");
			  DrawDragUpArea($arr,$startX+800,$startLoxY-20,$wid,"edit");
	 }
?>
<?php 

     function DrawBase(){
		      global $startLoxY,$startLoX,$wid;  
		      $StartY=date("Y");
			  $StartM=date("n");
			  $MRange=6;
			  $LocX=$startLoX;
			  $LocY=$startLoxY+20 ;
			  $h=15;
		      DrawRect("TaipeiRD2美術進度規畫表" ,"12","#ffffff",10,78,1000,20, "#000000");
			  //列印專案
		      global  $projects, $projectPlans;
			  global $colorCodes;
			  $y=$LocY ;
			  for($i=0;$i<count($projectPlans);$i++){
			      $LineNum= getLastSN2($projectPlans[$i] ,9 )+1 ; 
				  DrawRect( $projects[$i] ,"10","#ffffff" ,10, $y-20 ,1000, 15, $colorCodes[12][$i]);
				  VTDrawMuiltCalendarLines($StartY,$StartM,$MRange,$LocX,$y+$h,$wid,$h, $LineNum,$projects[$i]);  
				 // DrawRect( $i ,"10","#ffffff" ,0, $y ,10, ($LineNum+1)*$h, $colorCodes[0][$i]);
				  ListOnScPlan($projectPlans[$i],20,$y+$h);
				  $y+=( $LineNum+4)*$h;
			  }
			 
	          
			  $LocY+=300;
			  //列印未計畫
              ListnoPlan( $y);			  
	 }
	 
	  function ListOnScPlan($projectPlan,$startLoX,$y){
	         //  global $notSetPlan ,$OnScPlan,$ver;
			   global $startLoxY,$startLoX,$wid;
			   global $startDate;
			    
			   $h=15;
			   $fontSize=12;
			   $BgColor="#bb9999";
			   $fontColor="#ffffff";
			   for($i=0;$i<count($projectPlan);$i++){
				   DrawSingleDragPlan($projectPlan[$i],$startDate,$startLoX,$y,$wid, $BgColor,$fontColor,$i,$h);
			   }
 
			
	  }
 
	  function DrawSingleDragPlan($data,$startDate,$startLoX,$sy,$wid,$BgColor, $fontColor,$i,$h){
		          $backstr=$data[1]."=".$data[3]."=".$wid ;//0工單code.1人天 2寬度
				  $id= "S=".$backstr;
				  $show=$data[5];
				  $date=$data[2];
				  $WorkDays=$data[3];
				  $xAdd=VTreturnLocX($date,$startDate);
			   	  $sx=$startLoX+ ($xAdd-1)*$wid ;
				  $y=$sy+$i*$h;
				  if($data[9]!="")$y=$sy+$h*$data[9];
				  if($data[9]==0){
					  $BgColor="#ff7777";
				      DrawRect("","10","#ffffff" ,$sx, $y,2,$h*2,  $BgColor);
				  }
			      VTDrawJavaDragbox( $show,$sx, $y,$wid*$WorkDays , $h,10,  $BgColor, $fontColor,$id);
				  $id= "E=".$backstr ; 
				  $BgColor3="#888888";
				  $x= $sx+$wid*($data[3] );
				  VTDrawJavaDragbox( "" ,$x,$y+2,5,10,5, $BgColor3, $fontColor,$id);
	  }
	  function ListnoPlan($startY){
		      global   $notSetPlan;
			  $x=20;
			  $y=$startY;
			  $w=300;
			  $h=18;
			  $fontSize=12;
			  $BgColor="#555555";
			  $fontColor="#ffffff";
		      for($i=0;$i<count( $notSetPlan);$i++){
			      $id="code=".$notSetPlan[$i][1];
				  VTDrawJavaDragbox($notSetPlan[$i][5],$x,$y,$w,$h,$fontSize,$BgColor,$fontColor,$id);
				  $y+=20;
			  }
	  }
?>

<?php //up
   
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
				   ReLoad();
     }
     
     function CreatUpForm(){
		      $x=20;
			  $y=10;
		      global $URL;
			  global $typeName,$typeArray;
			  global $tableName;
			  global $DefuseProject;
		      $upFormVal=array("art","art",$URL);
			  $UpHidenVal=array(array("tablename",$tableName),
			                     array("code",returnDataCode( )),
								 array("data_type","data"),
								  array("project",$DefuseProject),
	                            );
		      $UpHidenVal=	addArray( $UpHidenVal,$typeArray);	
		      $inputVal=array(array("text","plan","plan",10,20,$y,400,20,$BgColor,$fontColor,"" ,30),
                              array("submit","submit","",10,260,$y,200,20,$BgColor,$fontColor,"新增計畫" ,15),
	                          );		 
		      upSubmitform($upFormVal,$UpHidenVal, $inputVal);
	 }
?>