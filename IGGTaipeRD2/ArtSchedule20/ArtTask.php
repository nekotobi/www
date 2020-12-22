<?PHP
      require_once('/Apis/PubApi20.php');
	  function CheckCookies(){
		       global $URL;
			   $URL="ArtTask.php";
			   global $CookieArray;
			   $CookieArray=array("selectProject","tmp");
	           PubApi_setcookies($CookieArray, $URL);
			   
	  }
	  CheckCookies();
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>美術工單排程表2.0</title>
</head>
<body bgcolor="#b5c4b1"> 
<script type="text/javascript">
function Drop2Area(event) {
		event.preventDefault();
		var DragID  = event.dataTransfer.getData("text");
		var targetID =  event.currentTarget.id;
	    var tx= document.getElementById( targetID).style.left;
	    var x=tx.split("px");
	    var DragID_tmp= DragID.split("=");
	    var targetID_tmp= targetID.split("="); 
		var SID;
	    document.Show.DragID.value=  DragID;
	    document.Show.target.value=  targetID;
		if(DragID_tmp.length>0){
		   document.Show.ECode.value=DragID_tmp[1];
		   document.Show.remark.value=DragID_tmp[2]; 
		   var SID=  new String( "code="+DragID_tmp[1]+"=startTime="+DragID_tmp[3]); 
		}
		if(targetID_tmp.length<2)return;
		   document.Show.DataName.value= targetID_tmp[0];
		   document.Show.Val.value= targetID_tmp[1];
	       document.Show.Etype.value="update";
		   if(DragID_tmp[2]=="workingDays"){
		      document.Show.DataName.value= "workingDays";
		      var sidx= document.getElementById(SID).style.left ;
		      var sidwx=sidx.split("px");
		      var x3=(parseInt(x[0]))-parseInt(sidwx[0]);
		      document.Show.Val.value =  parseInt(x3/ DragID_tmp[3])  ; 
		   }
	       Show.submit();
	}

</script>
<?php //主控台
      require_once('/Apis/PubApi20.php');
	  require_once('/Apis/mysqlApi20.php');
	  require_once('/Apis/PubJavaApi.php');
	  require_once('/Apis/CalendarApi20.php');
	  getCookie();
	  DefineBaseData();
	  PubApi_DrawUserData(800,0);
     //  echo ">".$_POST["viewGroup"];
      DrawAllButtons();
	  SwitchType();
?>

<?php //Base
      function DefineBaseData(){
		       global $data_library,$MaintableName,$tableName;
			   $MaintableName="maintask";
			   $data_library="iggtaiperd2";
			   global $CookieArray;
			   //選擇專案
		       global $ProjectTypes,$selectProject ;
			   $ProjectTypes=array("vt","zombie");//,"All"); 
			   $selectProject=$_COOKIE['selectProject'];
			   if( $selectProject=="")$selectProject=$ProjectTypes[1];
			   $tableName=  $selectProject."_tasks";
		     //  if( $selectProject=="All")  $tableName=$ProjectTypes[0]."_tasks";
			   //基本配置
			   global $URL;
			   //網頁選項資料
		       DefinetypeData();  
			   //日期資訊
			   global $StartY,$StartM,$MRange;
		       global $LocX,$LocY,$wid,$taskHeight;
               $StartY=date("Y");
			   $StartM=date("n");
			   $MRange=2;
               $LocX=380;
               $LocY=160;
               $wid=10;
               $taskHeight=16;
			   //java傳遞欄位
			   global  $inputsTextNames ;
               $inputsTextNames=array("DragID","target","Etype","ECode","DataName","Val","remark");
			   DefineDate_Task();
 
	  } 
	  function DefineDate_Task(){
		       //進度資訊
			   global $data_library,$tableName;
			   global $ProjectTypes,$selectProject ;
			   global $newTask,$type_newTask,$OnScTask;
			   $taskDataBaseName= $selectProject."_tasks";
			   $taskDataBase = getMysqlDataArray( $taskDataBaseName);
			   $taskDataBase_T= filterArray( $taskDataBase,0,"data");
			   $taskDataBase_T2 = RemoveArray (   $taskDataBase_T,8,"已完成");    //移除完成
			   //總規劃
			   global $plan;
			   $plan=filterArray(  $taskDataBase_T2,15,"ver");	
			   //定義工單
			   global $typeArray,  $typeName;
			   global $typeTask;
			   $typeTask_T=  $taskDataBase_T2;
			   for($i=0;$i<4;$i++){ 
			     if($typeArray[$i][1]!="--"){
				    $ColumnName=$typeName[$i][1];
					$num= MAPI_returnTableColumnSort($data_library,$tableName,$ColumnName);
				    $typeTask_T=filterArray(    $typeTask_T,$num,$typeArray[$i][1]);
				  }
			   }
			   $typeTask= RemoveArray ($typeTask_T,12,"");
			   $newTask= filterArray( $typeTask_T,12,"");
			   //如果是觀看相關任務
			    if( $typeArray[5][1]!="--"){  
			     $RootTaskCode=filterArray( $typeTask_T,2,$typeArray[5][1]);
				 echo   $RootTaskCode[0][1];
			     $typeTask=  filterArray(   $taskDataBase_T,3,$RootTaskCode[0][1]);
				 //$typeTask=addArray($RootTaskCode,);
				}
		
	  }
      function DefinetypeData(){//網頁選項資料
		       global $typeName,$typeArray,$PostArray;
			   $subNameForWard="Type";
			   $typeName=array(array("負責人","principal"),array("外包","outsourcing"),
  			                   array("大類別","RootType"),array("子類別","ChildType")
							  ,array("編輯",-1) 
							  ,array("群組","Group"));
			   $typeArray=array(); 
			   $nc=0;
			   for($i=0;$i<count($typeName);$i++){
				    $n=$subNameForWard.$i;
				    $s= $_POST[$n];
 
					if($s=="" ){//&& $i!=0 ){
						$s="--";
					    $nc++;
					}
			        array_push( $typeArray,array($n,$s));
			   }
			   if( $nc>4){
			        $typeArray[4][1]="顯示甘特";
					//$typeArray[5][1]="內部";
			   }
	  }
 
	  function SwitchType(){
		       global $URL,$data_library,$tableName;
			   global $inputsTextNames;
		       global $typeName,$typeArray,$PostArray;
			   //判斷新增工單
			   if($typeArray[4][1]=="新增工單" ){
				  CreatUpForm();
			   }
			   MAPI_pubUpform( );// MAPI_upMysQLEdit($data_library,$tableName,$code,$URL,$PostArray );
			   if($_POST["submit"]=="修改工單"){
			   echo "XXX";
			   }
	 
			   if($_POST["submit"]=="新增工單" ){
			      MAPi_UpNewTask($data_library,$tableName);
			   }
		       if($typeArray[4][1]=="顯示甘特" ){
				  JAPI_CreatJavaForm( $URL,$tableName,$inputsTextNames,$typeArray);
			      DrawCalendar( );
				  global $LocY,$taskHeight;
				  global $typeTask;
				  $LocYs=$LocY+count($typeTask)*$taskHeight+60;
				  ListTasks();
				  ListnewTasks($LocYs);
			   }
			   //java拖曳
			   if($_POST["DragID"]=="")return;
	           if($_POST["Val"]=="刪除"){
				  DeletPlan( $_POST["ECode"]  );
				  return;
			   }	
			   //設定子任務
			   if($_POST["DataName"]=="setChild"){ 
			      $Base=array("RootTaskCode");
			      $up=array($_POST["Val"]);
				  EditPlan( $_POST["ECode"],$Base,$up );
				  return;
			   }
			   if($_POST["Val"]=="主任務"){
			      $Base=array("remark");
			      $up=array("root");
				  EditPlan( $_POST["ECode"],$Base,$up );
				  return;
			   }
			   if($_POST["Val"]=="版本"){
			      $Base=array("remark");
			      $up=array("ver");
				  EditPlan( $_POST["ECode"],$Base,$up );
				  return;
			   }
	           if($_POST["Val"]=="編輯"){
				  $code=$_POST["ECode"] ;
			      MAPI_DrawMysQLEdit($data_library,$tableName,$code,$URL,$typeArray,"修改".$code."表格內容");
				  return;
			   }			   
			   if($_POST["Etype"]=="update"){  
			      $Base=array($_POST["DataName"]);
			      $up=array($_POST["Val"]);
				  if($_POST["remark"]=="new"){
				     array_Push($Base,"state");
				     array_Push($up,"預排程");
					 array_Push($Base,"workingDays");
				     array_Push($up,3);
				  }
				  EditPlan( $_POST["ECode"],$Base,$up );
			   }
	  }
 
	  function DeletPlan($ECode  ){
	           global $data_library,$tableName;
			   $WHEREtable=array( "EData", "ECode");
		       $WHEREData=array( "data",$ECode  );
			   $stmt= MakeDeleteStmt($tableName,$WHEREtable,$WHEREData);
			   SendCommand($stmt,$data_library);	
			   $ar=return_WebPostArray($typeArray,0);
               JAPI_ReLoad($ar,$URL);			   
	  }
	  function EditPlan($ECode,$Base,$up ){
			   global $data_library,$tableName;
		       $WHEREtable=array( "EData", "ECode");
		       $WHEREData=array( "data",$ECode  );
			   $stmt=MAPI_MakeUpdateStmt($tableName,$Base,$up,$WHEREtable,$WHEREData);
			//   echo $stmt;
			  // return;
               SendCommand($stmt,$data_library);		
			   global $typeArray,$URL;
		 	   $ar=return_WebPostArray($typeArray,0);
			   JAPI_ReLoad($ar,$URL);
	  }
	  function return_WebPostArray($arr,$i){
	           $ar=array();
			   for($i=0;$i<count($arr);$i++){
			       array_Push($ar,array($arr[$i][0],$_POST[$arr[$i][0]]));
			   }
			   return $ar;
	  }
?>

<?php //Buttons
      function DrawAllButtons(){
	           DrawProjectButtoms();
			   DrawButtons();
			   DrawProcessDragArea();
	  }
      function DrawButtons(){
		       global $URL;
			   global $typeName,$typeArray;
			   global $UserColor;
			   global $principals,$Outs;
			   global $Bigtypes;
			   $Rect=array(20,40,50,18);
			   //負責人
			   $Typestmp=getMysqlDataArray("members"); 
			   $TypeT=filterArray(  $Typestmp,3,"Art"); 
			   $principals= returnArraybySort($TypeT,1);
			   DrawButton($principals,$Rect,$URL,0,$typeArray,"principal",12);
			   //外包
			   $Rect[1]+=20;
			   $Typestmp=getMysqlDataArray("outsourcing"); 
			   $TypeT=filterArray($Typestmp,35,"true"); 
			   $Outs= returnArraybySort($TypeT,2);
			   DrawButton($Outs,$Rect,$URL,1,$typeArray,"outsourcing",11);
	           //大類
			   $Rect[1]+=20;
			   $Typestmp=getMysqlDataArray("scheduletype"); 
			   $TypeT=filterArray($Typestmp,0,"data"); 
			   $TypeS=sortArrays( $TypeT ,5 ,"true");
			   $Type= returnArraybySort($TypeS,2);
			   $Bigtypes=$Type;
			   DrawButton($Type,$Rect,$URL,2,$typeArray,"RootType");
			   //工類
			   $Rect[1]+=20;
			   $TypeT=filterArray(  $Typestmp,0,"data2"); 
			   $TypeS=sortArrays( $TypeT ,5 ,"true");
			   $Type= returnArraybySort($TypeS,2);
			   DrawButton($Type,$Rect,$URL,3,$typeArray,"ChildType");
			    //編輯類別
			   $Rect[1]+=20;
			   $Type=array("新增工單","顯示甘特");//, "編輯隱藏","整理隱藏");
			   DrawButton($Type,$Rect,$URL,4,$typeArray);
			   //顯示群組
			   global $typeTask;
			   $Rect[1]+=20;
			   $RootsTasks=filterArray($typeTask,15,"root");
			   $Type= returnArraybySort($RootsTasks,2);
		       DrawButton($Type,$Rect,$URL,5,$typeArray,"Group"); 
	  }
	  function DrawButton($array,$Rect,$URL,$valArrayNum,$ValArray,$Type="",$ColorN=-1){
		       global    $colorCodes;
			   array_unshift( $array,"--");
			   $SubmitName= $ValArray[$valArrayNum][0];
		       $sa=  $ValArray[$valArrayNum][1];
	           for($i=0;$i<count($array);$i++){
				   	   $BgColor="#000000";
					   if($ColorN!=-1){
						   $n=$i%9;
						   $BgColor=$colorCodes[$ColorN][$n];
					   }
					   if( $sa ==$array[$i])$BgColor="#ff1212";
					   $ValArray[$valArrayNum]=array($SubmitName,$array[$i]);
				       sendVal($URL,$ValArray,$SubmitName,$array[$i],$Rect,10,$BgColor);
				       $Rect[0]+=$Rect[2]+5;
					   if(  $valArrayNum!=4)  
					   DrawTypeDragArea($Type,$array[$i] ,$Rect );
	           }
	  } 
	  function DrawTypeDragArea($Type,$name,$Rect,$add="" ){
		       $id= $Type."=".$name."=".$add;
			   $x=$Rect[0]-$Rect[2]-2 ;
			   $y=$Rect[1]+4;
			   JAPI_DrawJavaDragArea($i,$x,$y,8,12,"#155555","#555555",$id,5);	
	  }
	  function DrawProjectButtoms(){
		       global $ProjectTypes,$selectProject;
			   global $startY;
			   global $URL;
			   $BgColor="#111111";
			   $SubmitName="submit";
			   for($i=0;$i<count( $ProjectTypes);$i++){
				   $ArrayVal=array(array("selectProject",$ProjectTypes[$i]));
				   $BgColor="#111111";
				   if($selectProject==$ProjectTypes[$i]){ $BgColor="#aa1111";}
				   $Rect=array(400+$i*60,10,58,12);
				   $SubmitVal=$ProjectTypes[$i];
			       sendVal($URL,$ArrayVal,$SubmitName,$SubmitVal,$Rect,10, $BgColor , "#ffffff","true");
			   } 
	  }
 	  function DrawProcessDragArea(){
			   $x=20;
			   $y= 160;
			   $BgColor="#224444";
			   $fontColor="#ffffff";
			   $Typestmp=getMysqlDataArray("scheduletype"); 
	           $arrT=filterArray( $Typestmp,0,"data3");//  array("進行中","已排程","驗證中","已完成");
			   $arr=returnArraybySort($arrT,2);
			   array_Push( $arr,"刪除");
			   array_Push( $arr,"編輯");
			   array_Push( $arr,"主任務");
			   array_Push( $arr,"版本");
			   for($i=0;$i<count($arr);$i++){
				   $id="state=".$arr[$i];
				   JAPI_DrawJavaDragArea($arr[$i],$x,$y,34,18,$BgColor,$fontColor,$id,9);
				   $x+=35;
				}
	  }	 
?>

<?php //List
      function DrawCalendar( ){
		       global $StartY,$StartM,$MRange;
		       global $LocX,$LocY,$wid,$taskHeight;
			   global $typeTask;
			   $range=  CAPI_getDateRange( $typeTask,12,13);
			   $StartY=$range[0];
			   $StartM=$range[1];
	           CAPI_DrawBaseCalendar($StartY,$StartM,$MRange,$LocX,$LocY,$wid,(count($typeTask)+1)*$taskHeight+2);
			   DrawVer();
	  }
	  function DrawVer(){
		  	   global $StartY,$StartM,$MRange;
	           global $plan;
			   global $LocX,$LocY,$wid,$taskHeight;
			   $startDate=$StartY."-".$StartM."-1";
			   for($i=0;$i<count($plan);$i++){
				  $date= $plan[$i][12];
				  $xAdd=CAPI_returnLocX($date, $startDate )-1;
				  $w=$plan[$i][13]*$wid;
				  $sx=$LocX+$xAdd*$wid;
				  $ex=$LocX+$xAdd*$wid+$w;
				  DrawRect("",8,"#8899ff",array(  $sx, $LocY+12 ,2,70),"#8899ff");
			      DrawRect("",8,"#8899ff",array(   $sx, $LocY+12 ,$w,2),"#8899ff");
				  DrawRect("",8,"#8899ff",array( $ex, $LocY+12,2,70),"#ee5555");
				  DrawRect($plan[$i][2],8,"#ffffff",array( $ex, $LocY+1 ,40,12),"#ee5555");
			   }
			   
	  }
	  function ListTasks(){
	           global $typeTask;
			   global $StartY,$StartM,$MRange;
			   global $LocX,$LocY,$wid,$taskHeight;;
			   $startDate=$StartY."-".$StartM."-1";
			   $fontSize=12;
			   $BgColor="#553333";
			   $fontColor="#ffffff";
	           DrawRect("工單",12,$fontColor,array( 20, $LocY+18 ,280,16),"#000000");
			   DrawRect("負責人",12,$fontColor,array( 290, $LocY+18 ,80,16),"#222222");
			   for($i=0;$i<count($typeTask);$i++){    
			      $y=$LocY+35+$i*$taskHeight;;
				  DrawSingleDragPlan($typeTask[$i],$startDate,$LocX,$y,$wid, $BgColor,$fontColor,$i,$taskHeight);
			   }
	  }
	  function DrawTaskTitle($data, $y ,$h){
		       $name=$data[2];
			   $x=20;
			   $w=270;
			   $fontColor="#ffffff";
			   $BgColor="#666666";
			   //工單名稱
			   if($data[8]=="進行中")  $BgColor="#55aa55";
	           DrawRect($name,10,$fontColor,array($x,$y ,$w,$h-1),$BgColor);
			   //工單負責人
			   $Principal_Out=returnPrincipal_Out($data);
			   $BgColor="#226655";
			   DrawRect( $Principal_Out,8,$fontColor,array($x+$w,$y ,80,$h-1),$BgColor);
			   //jila
			   $jila=$data[5];
			   //if($jila=="")$jila=$RootTask[12];
			   if($jila!=""){
			   $JilaLink="http://bzbfzjira.iggcn.com/browse/FP-".$jila  ;
			   DrawLinkRect_newtab($jila,"8","#ffffff"  ,$x+1,$y,20,12,"#aa8888",$JilaLink,"1" );
			   }
			   //大分類rootType
			   if( $data[6]!="" or $data[6]!="--"){ 
                  $str= PAPI_returnSplitStr($data[6],4);		   
			      DrawRect( $str,7,$fontColor,array($x+252,$y ,10,$h-3),"#444444");
			   }
			    //大分類childType
			   if( $data[7]!="" or $data[7]!="--"){ 
                  $str= PAPI_returnSplitStr($data[7],3);			   
			      DrawRect( $str ,7,$fontColor,array($x+263,$y ,10,$h-3),"#444444");
			   }
			   //主任務
			   if( $data[15]=="root"){
				  // global  $typeArray;
				 //  $AddArrays=array(array("viewGroup",$data[1]));
			       //$valArray=   addArray( $AddArrays,$typeArray);
				  // $SubmitName="CheckRoot";
				 //  $Rect=array(5,$y-4 ,15,$h);
			       //sendVal($URL,   $valArray,$SubmitName,"C",$Rect,10,"#bb4444");
				   $Type="setChild";
				   $name=$data[1];
				   $Rect=array(20,$y-4 ,10,$h-4);
			       DrawTypeDragArea($Type,$name,$Rect );
			   }				   
	  }
	  function returnPrincipal_Out($data){
			   $p=$data[10];//PAPI_returnSplitStr( $data[10],12);
			   $o=$data[11];//PAPI_returnSplitStr( $data[11],12);
			   $str="[未排定]";
			   if($p!="" &&  $o!="" )   $str=$o."[".$p."]";
			   if($p!="" &&  $o=="" )   $str=$p ;
			   if($p=="" &&  $o!="" )   $str=$o ;
			  
			   return $str;
			   
	  }
 	  function DrawSingleDragPlan($data,$startDay,$startLoX,$sy,$wid,$BgColor, $fontColor,$i,$h){
		       global $wid;
			    $id="code=".$data[1]."=startTime=".$wid;
			    $show=$data[2];
				$workingDays= $data[13];
				if($workingDays=="")$workingDays=1;
				$date= $data[12];
				$xAdd=CAPI_returnLocX($date,$startDay );
			    $sx=$startLoX+ ($xAdd-1)*$wid ;
			    $y=$sy; 
			    DrawTaskTitle($data,  $y  ,$h);
			    JAPI_DrawJavaDragbox( $workingDays ,$sx, $y,$wid*$workingDays , $h-3,10,  $BgColor, "#cc8888",$id);
				//end:
	            $id="code=".$data[1]."=workingDays=".$wid;
			    $BgColor3="#888888";
			    $x= $sx+$wid*($workingDays );
			    JAPI_DrawJavaDragbox( "" ,$x,$y,5,$h-3,5, $BgColor3, $fontColor,$id);
		  /*
 
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
				  if( $data[10]!="" && $data[10]!= $data[7]  ){ //pm不同
				     DrawRect($data[10],"8","#ffffff" ,$sx+$wid*$WorkDays-2, $y+2,40,10, "#55aabb");
				  }
				  */
	  }
	  function ListnewTasks($startY){
		       global $newTask;
			   if(count($newTask)==0)return;
			   global $id;
			   $x=20;
			   $y=$startY;
			   $w=355;
			   $h=12;
			   $fontSize=10;
			   $BgColor="#665555";
			   $fontColor="#ffffff";
			   DrawRect( "未排定工單",12,$fontColor,array( $x,$y- 14 , $w,$h+2),"#000000");
		       for($i=0;$i<count($newTask);$i++){
			       $id2="code=".$newTask[$i][1]."=new";
				   $name=returnNewTaskName($newTask[$i]);
				   JAPI_DrawJavaDragbox(   $name,$x,$y,$w,$h,$fontSize,$BgColor,$fontColor,$id2);
				 //  if($newTask[$i][7]!="Kou" & $newTask[$i][7]!=$id ){
				    //  DrawRect($notSetPlan[$i][7] ,"10","#ffffff"  ,$x+$w-20,$y+2,60,$h-4,"#333333");
				 //  }
				   $y+= $h+1;
			  }
	  }
	  function returnNewTaskName($data){
		       $arr=array(5,2,6,7,10,11);
			   $str="";
			   for($i=0;$i<count($arr);$i++){
			    $str=$str."[".$data[$arr[$i]]."]";
			   }
			   return $str;
	
	          
	  }
?>
<?php //function
      function ReturnDateRange($tasks){
	           
	  }
?>
<?php //up
      function CreatUpForm(){ //新增工單
		      $x=20;
			  $y=10;
		      global $URL;
			  global $typeName,$typeArray;
			  global $tableName;
			  global $DefuseProject;
			  global $id;
			  global $tableName;
			  global $ProjectTypes,$selectProject;
		      $upFormVal=array("art","art",$URL);
			  $UpHidenVal=array( array("ECode",returnDataCode()),
								 array("EData","data"),
								 array("project",$DefuseProject),
								 array("state","新工單"),
								 array("pm",$id),
	                            );
			  for($i=0;$i<count($typeArray);$i++){
				//  echo $typeName[$i][2].">".$typeArray[$i][1]."]";
 
			       array_Push( $UpHidenVal,array($typeName[$i][1], $typeArray[$i][1] ));
			  }
		      $UpHidenVal=	addArray( $UpHidenVal,$typeArray);	
		      $inputVal=array(array("text","taskName","taskName",10,20,$y,400,20,$BgColor,$fontColor,"" ,30),
			                  array("text","jila","jila",10,240,$y,400,20,$BgColor,$fontColor,"" ,5),
                              array("submit","submit","",10,300,$y,200,20,$BgColor,$fontColor,"新增工單" ,15),
	                          );		 
		      upSubmitform($upFormVal,$UpHidenVal, $inputVal);
	 }

?>