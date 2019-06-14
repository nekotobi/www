
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>工作分類排程區</title>
</head>
<script type="text/javascript">
	function AllowDrop(event) {
	    	event.preventDefault();
		    var overID = event.currentTarget.id;
				document.Show.sendData.value= document.getElementById(overID).style.backgroundColor;
		    if (document.Show.upID.value!=overID){
				document.Show.upID.value=overID
			    document.Show.upColor.value= document.getElementById(document.Show.upID.value).style.backgroundColor;   
			}	

			document.getElementById(overID).style.backgroundColor= 'rgb(211,111,111)' //"#ef7e62"
	}

	function Drag(event) {
		   event.dataTransfer.setData("text", event.currentTarget.id);
	       document.Show.DragID.value=event.currentTarget.id;
		   var DragID  = event.currentTarget.id;
		 	if(DragID.indexOf("Scale")>-1){
			   var tmp= DragID.split("-")
	     	   document.Show.startpx.value= tmp[4];
			   }
	}
    
     function  DragLeave(event) {
          var LeaveID = event.currentTarget.id;
		      document.getElementById(LeaveID).style.backgroundColor =  document.Show.upColor.value;  
	 }
	function Drop(event) {
		event.preventDefault();
		var DragID  = event.dataTransfer.getData("text");
		var targetID = 	event.currentTarget.id
		var tmp= targetID.split("-");
		var endpos= parseInt(document.Show.Xoffset.value=tmp[2]);
		var stpos=parseInt( document.Show.startpx.value);
		    document.Show.Xoffset.value=(endpos-stpos);
	        document.Show.sendData.value="DragWorkOrder";
            document.Show.DragArea.value=targetID;
		    document.Show.DragID.value=DragID;
			Show.submit();
	}
</script>
</script>
<body bgcolor="#b5c4b1">
<?php
   $id=$_COOKIE['IGG_id'];
   include('PubApi.php');
   include('CalendarApi.php');  
   include('mysqlApi.php');
   include('scheduleApi.php');
 
     defineData_v2();   //定義基礎資料(scheduleApi)
     
     GetCalendarData(); //取得日曆資料(scheduleApi)
     DrawBaseCalendar_v2(); //列印基礎日期資料(scheduleApi)
     DrawType_v2();//進度表類型
	 DrawTypeCont();//判斷印出內容
	// DrawPlan_v2();
	 CheckinputType_v2();//判斷輸入
	 global   $BaseURL;
     DrawMembersLinkArea( 30,6,  $BaseURL); 
	 DrawOutLinkArea(30,52,$BaseURL);
	 DrawUserData( 820, 11);   //使用者資料(PubApi)
?>

<?php
	 function defineData_v2(){
		 //基礎數值
		 global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX,$monthLoc,$showMonthNum ;
		        $StartX=20;
	            $StartY=90;
	            $MonthWidth=200;
	            $OneDayWidth=15;
	            $CurrentX= $StartX;
				$showMonthNum=8;
				$daysLoc=array();//(year,m,d,x軸位置)
                $monthLoc=array();//($y,m,x軸位置,Siz)
		 //返回資料
		 global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2,$stateType; 
		        $BaseURL="schedule.php";
                $BackURL= $BaseURL."?Stype_1=".$Stype_1."&Stype_2=".$Stype_2;
			    $SelectType_1=array("總規劃","角色","怪物","場景","UI","城建","概念","TA","其他");
				$SelectType_2=array("文案","概念","設定","建模","動作","特效","精稿");
				$stateType=array("未製作","進行中","優化","已完成","結案");
				if($Stype_1=="")$Stype_1=0;
		 //資料表
		 global $data_library,$tableName;
				$tableName="fpschedule";
			    $data_library="iggtaiperd2";
		 //共用資料表
	     global $OutsData,$memberData;
                $OutsData=getMysqlDataArray("outsourcing");	 
      	        $memberData=getMysqlDataArray("members");
	        
	 }
     function  DrawType_v2(){
		 	  global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX,$monthLoc,$showMonthNum ;
	    	  global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1;
			  global $colorCodes;
			  $y=$StartY+10;
	          for ($i=0;$i<count( $SelectType_1);$i++){
				   $x=120+ $i*70;
                   $BackURL2= $BaseURL."?Stype_1=".$i."&Stype_2=".$Stype_2;
				   //echo  $BackURL;
				   $msg=" ".$SelectType_1[$i];
				   $color= "#222222";
				   if($Stype_1==$i)$color= "#ff2212";
			       DrawLinkRect($msg,"12","#ffffff",$x,$y,"60","16",$color,$BackURL2,1);
			  }
			  DrawState();
	 }
    function DrawBaseCalendar_v2(){  //日曆格
		      global $StartX, $StartY,$OneDayWidth,$daysLoc,$monthLoc, $YearLoc; 
	          global $TargetYear,$TargetMonth,$YearRange,$MonthRange,$showMonthNum;
			  global $BaseURL,$BackURL, $Stype_1,$Stype_2;
			  global $colorCodes;
			  echo "<div   style='position: -webkit-sticky; position:sticky; top:0; z-index: 100;'>";
			  $pos="absolute";
			  for($i=0;$i<count($monthLoc);$i++){
			       DrawabsoluteRect($monthLoc[$i][1],"10","#ffffff",  $monthLoc[$i][2]-8, $StartY+40 ,  $monthLoc[$i][3]-1 ,"20",  $colorCodes[2][2],$pos, $Link );
				   DrawabsoluteRect($monthLoc[$i][0],"10","#ffffff",  $monthLoc[$i][2]-8, $StartY+20 ,  $monthLoc[$i][3]-1 ,"20",  $colorCodes[2][1],$pos, $Link);
			  } 
			  $startM=$monthLoc[0][1]-1;
			  $starty=$monthLoc[0][0];
			  for($i=0;$i<count($daysLoc);$i++){//日格
			  	  if($daysLoc[$i][2]==1){
					  $startM+=1;
					  if(  $startM==13){
					  $StartM=1;
					   $starty+=1;
					  }
				  }
			      $color=$colorCodes[2][4];
		          $Link= $BackURL."&PhpInputType=AddPlan&ed=".$daysLoc[$i][2]."&em=".$startM."&ey=".$starty."&dx=".($daysLoc[$i][3]-8)."&dy=".($StartY+60);
			      if($daysLoc[$i][4]!="0")$color=$colorCodes[1][1];
			       DrawabsoluteRect($daysLoc[$i][2],"8","#000000",  $daysLoc[$i][3]-8, $StartY+60 ,  $OneDayWidth-1 ,"20",$color,$pos, $Link);
				  }       
			  DrawSprint($StartY+80 );
              echo "</div>"	;	
			  DrawDragArea(25);
			
	 }
	    function DrawDragHorArea($height ){//橫排區
	         global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX  ; 
			 	 $w= 40;//$OneDayWidth*count($daysLoc);
			     $y= $StartY+90;
				 $x=$startX+310;
				 $h=16;
			 for($i=0;$i<$height;$i++){
				 $BGColor="#555555";
				 $id="Line-".$i;
				 $y+=20;
				 DrawRect($i+1,12,"#ffffff",$x,$y,$w,$h,$BGColor); 
			    // DrawDragRect($x,$y,$w,$h,$BGColor,$id);
			    }
		}
?>

<?php //繪製計畫

      function DrawTypeCont(){
		       global $List,$Stype_1,$Stype_2 ;
			   if($List!=""){
				   DrawMemberWorks();
			       return;
			   }
			   DrawPlan_v2();
			  
      }
      function DrawMemberWorks(){
	  	      global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2;
		      global $data_library,$tableName;
			  global $user,$List;
			  //global $memberId;
			  global $OutsData,$memberData;
			  $plansTmp=getMysqlDataArray($tableName); 
			  
			   
              if($List=="ArtWork"){
			      $idtmp=returnDataArray( $memberData,0,$user);
				  $id=$idtmp[1];
				  $plans= filterArray($plansTmp,8,$id);
			  }
			  if($List=="Out"){
			      $idtmp=returnDataArray( $OutsData,1,$user);
				  $id=$idtmp[2];
				  $plans= filterArray($plansTmp,9,$id);
			  }
              $JobsArray=array( );
		      for($i=0;$i<count($plans);$i++){
			       DrawPlanBar($color_num,$y,$plans[$i] );
			       $color_num+=1;
			       if( $color_num>7)$color_num=3;
				   $codeA=returnDataArray( $plansTmp,1,$plans[$i][3]);//取得主資料array
				   $job=$codeA[3]."[".$plans[$i][5]."][".$plans[$i][7]."]".$plans[$i][6]."天";
				   array_push($JobsArray,$job);
		          }
			  DrawUserInfo( $idtmp,$JobsArray);
	  }
      function DrawUserInfo($UserArray,$JobsArray ){
		       global $List; 
			   global $BackURL;
		       $ex=20;
	           $ey=260;
			   $w=130;
			   $h=200;
			   $title="";
			   if( $List=="ArtWork"){
			       $title=$UserArray[1]."排程";
			   }
		       if( $List=="Out"){
			       $title=$UserArray[2]."排程";
			   }
			   
			   DrawPopBG($ex,$ey,$w,$h,$title ,"12",$BackURL);
	           for($i=0;$i<count( $JobsArray);$i++){
				    echo $i;
				    $ey+=20;
					$info=$i.".". $JobsArray[$i];
				    DrawRect($info,"11","#322222",$ex-10,$ey,150 ,"20","#ffffff");
			   }
	  }
	  
	  
      function  DrawPlan_v2(){
	       global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2;
		   global $data_library,$tableName;
		   $plansTmp=getMysqlDataArray($tableName);
		   $type1=$SelectType_1[$Stype_1];
           
		   $plansTmp2 =  filterArray( $plansTmp ,10, $type1);
	       $plans= filterArray($plansTmp2,0,"data");
		   
		   $plansLine= filterArray($plansTmp2,5,"工項");
		   $color_num=3;
           DrawBgLine($plansLine);
		   if($type1=="總規劃"){
		      $plansLine2= filterArray($plansTmp2,5,"目標");
			  for($i=0;$i<count($plansLine2);$i++){
				  DrawTargetLine($plansLine2[$i]);
			  }
		   }
 
		   
		   for($i=0;$i<count($plans);$i++){
			  DrawPlanBar($color_num,$y,$plans[$i] );
			  $color_num+=1;
			  if( $color_num>7)$color_num=3;
		   }
	  }
	  function DrawTargetLine($plansLine2){
		  	   global $daysLoc, $StartY;
		       $startDay=explode("_",$plansLine2[2]);
		       $d=returnDateString($startDay[0],$startDay[1],$startDay[2]);
			   $x=RetrunXpos($daysLoc,$d);
			   $y= $StartY+90+$plansLine2[4]*20;
	           $color="#772233";
			   $fontColor="#eeeeee";
			   $line=$plansLine2[4];
			   DrawabsoluteRect("","0",$fontColor, $x, $y-20*$line,"2" ,$line*20 , $color,  "absolute", $Link );
			   DrawLinkRect($info,"10",$fontColor,$x,$y,$w ,"16", $color,$Link,"1");
	  }
	  
	  function DrawBgLine($plansLine){
		  	   global $daysLoc, $StartY;
	  		   for($i=0;$i<count( $plansLine);$i++){
			    if($plansLine[$i][10]!="總規劃"){
				   $startDay=explode("_",$plansLine[$i][2]);
		           $d=returnDateString($startDay[0],$startDay[1],$startDay[2]);
			       $x=RetrunXpos($daysLoc,$d);
				   $y= $StartY+90+$plansLine[$i][4]*20;
				   DrawabsoluteRect("","0","", $x-20, $y+9,"300" ,1 ,"#666666",  "absolute", "" );
				}
		   }
	  }
      function DrawPlanBar( $color_num,$y,$plansArray ){
		       global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX,$monthLoc,$showMonthNum ;
		       global $colorCodes;
			   global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1, $SelectType_2;
			   global $user,$List;
			   global $data_library,$tableName;
		       $fontColor="#222222";
			   $startDay=explode("_",$plansArray[2]);
		       $d=returnDateString($startDay[0],$startDay[1],$startDay[2]);
			   $x=RetrunXpos($daysLoc,$d);
			   $y= $StartY+90+$plansArray[4]*20;
			   $line=$plansArray[4];
			   $w= $OneDayWidth*$plansArray[6];
			   $color= $colorCodes[$color_num][1];
			   $info=$plansArray[3] ;
			   $plan_type=$plansArray[5];//工項/目標
			   $type=$plansArray[10];//總規劃//腳色分類
			   $Link=$BackURL."&PhpInputType=EditPlan&Ecode=".$plansArray[1];
			   if($type=="總規劃"){
			      switch($plan_type){
				         case "工項":
						 DrawLinkRect($info,"10",$fontColor,$x,$y,$w ,"16", $color,$Link,"1");
				         break;
						 
						 case "目標":
						 $color="#772233";
				         $fontColor="#eeeeee";
						 //DrawabsoluteRect("","0",$fontColor, $x, $y-20*$line,"2" ,$line*20 , $color,  "absolute", $Link );
						 DrawLinkRect($info,"10",$fontColor,$x,$y,$w ,"16", $color,$Link,"1");
						 break;
						 
				  }
				  return;
			   }
			   if ($plan_type=="工項"){
				    $sx=$x-$w-20;
					DrawLinkRect("　".$info,"10","#ffffff",$sx,$y,$w ,"16", "#666666",$Link,"1");
					$Link=$BackURL."&PhpInputType=AddPlanType&Ecode=".$plansArray[1];
					DrawLinkRect("+","10","#ffffff",$x-20,$y+2,"12" ,"12", "#555555",$Link,"1");
					return;
				}
 
				$Link=$BackURL."&PhpInputType=DrawEditPlanType&Ecode=".$plansArray[1];
				$color=$colorCodes[9][0];
				
			    for($i=0;$i<count($SelectType_2);$i++){
					if($SelectType_2[$i]==$plan_type){
						$color=$colorCodes[9][$i];
						   if($plansArray[7]=="已完成")	$color=$colorCodes[10][$i];
							 
					}
				}
				$NameAdd="";
				$NameBackAdd="";
			 	if($List==""){ 
				    $NameBackAdd= "[".$plansArray[9]."]";
				   if($plansArray[9]=="" or $plansArray[9]=="未定義"){
					     $NameBackAdd="[".$plansArray[8]."]"; 
				   }
				
				  
				}
				if($List!=""){//列印名單工項
				   $plansTmp=getMysqlDataArray($tableName); 
				   $codeA=returnDataArray( $plansTmp,1,$plansArray[3] );//取得主資料array
				   $NameAdd= "[".$codeA[3] ;
				}
                DrawLinkRectAutoLength($NameAdd.">".$plan_type.$NameBackAdd,"10","#000000",$x,$y,$w ,"16", $color,$Link,"1");
				//狀態圖
			   DrawStatePics($plansArray,$x,$y);
			
	  }
	  function DrawStatePics($plansArray,$x,$y){
		  		  global $OutsData,$memberData;
				 $pic="";
			     if($plansArray[7]=="" or $plansArray[7]=="未定義")$pic="Pics/question";
				 if($plansArray[7]=="已完成")$pic="Pics/finish";
				 if($plansArray[7]=="進行中")$pic="Pics/construction";
				 //狀態問題
				  $startDayArray=explode("_",$plansArray[2]);
				  $nowDayArray=array(date(Y),date(m),date(d));
				  $passDays= getPassDays($startDayArray,$nowDayArray);
	              if($passDays>$plansArray[6] && $plansArray[7]!="已完成"){
					  $pic="Pics/warring.gif";
				  }
				 if( $pic!="")
			       DrawPosPic($pic, $y,$x-6,16,16,"absolute" );

	  
	  }
	  
	  
	  function DrawWorkDetail($ex,$ey,$w,$h){
	          global $data_library,$tableName;   
	          global $colorCodes;
		      global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$stateType;
			  global $Ecode;
			  $planstmp=getMysqlDataArray($tableName);
			  $plansArray=returnDataArray($planstmp,1,$Ecode);
			  $rootName= returnDataArray($planstmp,1,$plansArray[3]);
	  		  $title="[".$rootName[3]."] [".$plansArray[5]."]開始日:".$plansArray[2].">".$plansArray[6]."人天";
		      DrawPopBG($ex,$ey,$w,$h,$title ,"12",$BackURL);
			  $Link=$BackURL."&PhpInputType=EditPlanType&Ecode=".$plansArray[1];
			  DrawLinkRect("　Edit","10","#ffffff",$ex+333,$ey+2,42 ,"16","#000000",$Link,"1");
			  $fontColor="#000000";
			  $BgColor="#eeeeee";
			  $out="外包負責人:".$plansArray[9];
			  $ey+=30;
			  DrawRect($out,12,$fontColor,$ex,$ey,200,16,$BgColor);
			  $out="內部負責人:".$plansArray[8];
			  $ey+=30;
			  DrawRect($out,12,$fontColor,$ex,$ey,200,16,$BgColor);
			  $info="狀態".$plansArray[7];
			  $ey+=30;
			  DrawRect($info,12,$fontColor,$ex,$ey,200,16,$BgColor);
	  }

	  
?>

<?php //輸入
      function CheckinputType_v2(){
	       global $epy,$epm,$epd,$epLine,$epDay,$eptype;
	       global $ed,$em,$ey,$dx,$dy;
		   global $PhpInputType;
		   if($PhpInputType=="")return;
		   include('scheduleOrder.php');
		   switch ($PhpInputType){
			    case $PhpInputType=="AddPlanType":
			         AddPlanTypeEditor_v2("400","260","400","120",$ey,$em,$ed); 
			    break;
		        case $PhpInputType=="AddPlan":
			     	 DrawDragHorArea(25 );
			         AddPlanEditor_v2("400","260","400","120",$ey,$em,$ed); 
			    break;
		        case $PhpInputType=="EditPlan":
					 DrawDragHorArea(25 );
			          EditPlan_v2("400","260","400","120" );
			    break;
			    case $PhpInputType=="upAdd":
			    	 AddData( );
			    break;
			    case $PhpInputType=="upEdit":
			    	 UpEditData( );
			    break;
				case $PhpInputType=="upAddType":
			    	 AddTypeData( );
			    break;
				case $PhpInputType=="EditPlanType":
			    	 EditPlanTypeEditor_v2("400","260","400","170");
			    break;
			    case $PhpInputType=="upEditPlanType":
			    	 UpEditData( );
			    break;
				case $PhpInputType=="DrawEditPlanType":
			         DrawWorkDetail("400","260","400","170");
			    break;
		   }
	 
	 }
?>

<?php //Updata
     
     function UpEditData( ){
		       global $data_library,$tableName;
			   global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1;
			   global $year,$month,$day;
			   global $submit;
			   global $del;
			   $p=$tableName;
			   $tables=returnTables($data_library,$p);
	           $t= count( $tables);
			   
			   $Base=array();
			   $up=array();
		       for($i=0;$i<$t;$i++){
	       	       global $$tables[$i];
				   		  $startDay=$year."_".$month."_".$day;
				          array_push($Base,$tables[$i]);
                          array_push($up,$$tables[$i]);
				         // echo  "</br>".$tables[$i].">".$$tables[$i];
		       }
			   
			   $WHEREtable=array( "data_type", "code" );
		       $WHEREData=array( "data",$code );
			   if($submit=="修改計畫"){
			    $stmt= MakeUpdateStmt(  $data_library,$tableName,$Base,$up,$WHEREtable,$WHEREData);
                SendCommand($stmt,$data_library);			   
			   }
		       if($submit=="送出修改"){
			    $stmt= MakeUpdateStmt(  $data_library,$tableName,$Base,$up,$WHEREtable,$WHEREData);
                SendCommand($stmt,$data_library);			   
			   }   
			   if($submit=="刪除計畫"){
			      if($del!="") $stmt= MakeDeleteStmt($tableName,$WHEREtable,$WHEREData); 
				     SendCommand($stmt,$data_library);
			   }
			 //  SendCommand($stmt,$data_library);
			  echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";
	 }
     function AddData( ){
		       global $data_library,$tableName;
			   global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1;
			       $p=$tableName;
				   $tables=returnTables($data_library,$p);
	               $t= count( $tables);
			      
				   $WHEREtable=array();
				   $WHEREData=array();
		           for($i=0;$i<$t;$i++){
	       	            global $$tables[$i];
				        array_push($WHEREtable,$tables[$i]);
					    array_push($WHEREData,$$tables[$i]);
					 //   echo  "</br>".$tables[$i].">".$$tables[$i];
		              }
					$stmt=   MakeNewStmtv2($tableName,$WHEREtable,$WHEREData);
				    SendCommand($stmt,$data_library);
				    echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";
		      	// echo $stmt;
	 }
       function AddTypeData( ){
		       global $data_library,$tableName;
			   global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1;
			    global $year,$month,$day;
			       $p=$tableName;
				   $tables=returnTables($data_library,$p);
	               $t= count( $tables);
			      
				   $WHEREtable=array();
				   $WHEREData=array();
		           for($i=0;$i<$t;$i++){
	       	            global $$tables[$i];
					    $startDay=$year."_".$month."_".$day;
					 
				        array_push($WHEREtable,$tables[$i]);
					    array_push($WHEREData,$$tables[$i]);
					 //   echo  "</br>".$tables[$i].">".$$tables[$i];
		              }
					$stmt=   MakeNewStmtv2($tableName,$WHEREtable,$WHEREData);
				    SendCommand($stmt,$data_library);
			    echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";
		      	  echo $stmt;
	 }
?>



<?php //Oder
   function EditPlanTypeEditor_v2($ex,$ey,$w,$h){
            global $data_library,$tableName;   
	        global $colorCodes;
		    global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$stateType;
			global $Ecode;
			 $planstmp=getMysqlDataArray($tableName);
			 $plansArray=returnDataArray($planstmp,1,$Ecode);
			 $rootName= returnDataArray($planstmp,1,$plansArray[3]);
			 $title="修改 [". $rootName[3]."] [".$plansArray[5]."]內容";
		     DrawPopBG($ex,$ey,$w,$h,$title ,"12",$BackURL);
		     $startDay=explode("_",$plansArray[2]);
			 //From
		      echo   "<form id='AddPlan'  name='Show' action='".$BackURL."' method='post'  enctype='multipart/form-data'>";
			  //基礎資料
			  $p=$tableName;
			  $tables=returnTables($data_library,$p);
			  for($i=0;$i<count($tables);$i++){
			      echo   "<input type=hidden name=".$tables[$i]." value=".$plansArray[$i].">";
			  }
			  //Log
			  echo   "<input type=hidden name=code value=".$Ecode.">";
		   	  echo   "<input type=hidden name=PhpInputType value=upEditPlanType >";
			 
		    //  echo   "<input type=hidden name=plan value=".$plansArray[5].">"; 	
			  $lastUpdate=date(Y_m_d_H_i,time()+(8*3600));
		      echo   "<input type=hidden name=lastUpdate value=".$lastUpdate.">"; 
			  $ey+=20;
			 //年
	         $input="<input type=text name=year value='".$startDay[0]."'  size=4>年";
	         DrawInputRect("開始","12","#ffffff",($ex),$ey ,120,16, $colorCodes[4][2],"top",$input);
	         //月
	         $input="<input type=text name=month value='".$startDay[1]."'  size=2>月";
	         DrawInputRect("","12","#ffffff",($ex+80),$ey ,120,16, $colorCodes[4][2],"top",$input);
		     //日
	         $input="<input type=text name=day value='".$startDay[2]."'  size=2>日";
	         DrawInputRect("","14","#ffffff",($ex+130),$ey ,220,16, $colorCodes[4][2],"top",$input);
			 //天數
			 $workDayinput="<input type=text name=workingDays  value='".$plansArray[6]."'  size=2   >";
	         DrawInputRect("天數","12","#ffffff",($ex+190),$ey ,120,18, $colorCodes[4][2],"top",$workDayinput); 
			 $ey+=40;
			 //外包負責
			 $OutsDatatmp=getMysqlDataArray("outsourcing");
	         $OutsDatatmp2=filterArray($OutsDatatmp,0,"data");
	         $OutsData=returnArraybySort( $OutsDatatmp2,2);
			 $selectTable= MakeSelectionV2($OutsData,$plansArray[9] ,"outsourcing",10);
		     DrawInputRect( "選擇負責外包","10","#ffffff",($ex),$ey ,120,16, $colorCodes[4][2],"top", $selectTable);
			 //負責人
			 $principaltmp=getMysqlDataArray("members");
			 $principalData=returnArraybySort( $principaltmp,1);
			 $selectTable= MakeSelectionV2( $principalData,$plansArray[8],"principal" ,10);
			 DrawInputRect( "選擇內部負責","10","#ffffff",($ex+120),$ey ,120,16, $colorCodes[4][2],"top", $selectTable);
			 //狀態
			 $selectTable= MakeSelectionV2( $stateType,$plansArray[7],"state" ,10);
			 DrawInputRect( "目前狀態","10","#ffffff",($ex+250),$ey ,120,16, $colorCodes[4][2],"top", $selectTable);
			 
			 //送出
		     $submitP="<input type=submit name=submit value=送出修改>";
	         DrawInputRect("",$ey-120 ,"#ffffff",($ex+320),60,120,18, $colorCodes[4][2],"top",$submitP);
			 //圖檔
			 $ey+=50;
			 $input="<input type=text name=remark value='".$plansArray[12]."'  size=32>";
	         DrawInputRect("輸入完成圖檔連結","12","#ffffff",($ex ),$ey ,320,16, $colorCodes[4][2],"top",$input);
			 
			 
			 //刪除
	         $input="<input type=text name=del value=''  size=3>";
	         DrawInputRect("輸入刪除碼","12","#ffffff",($ex+222),$ey+50,220,16, $colorCodes[4][2],"top",$input);	
		     $submitP="<input type=submit name=submit value=刪除計畫>";
	         DrawInputRect("",$ey-20 ,"#ffffff",($ex+320),60,120,18, $colorCodes[4][2],"top",$submitP);
   }

 function AddPlanTypeEditor_v2($ex,$ey,$w,$h,$y,$m,$d){
         global $data_library,$tableName;   
	     global $colorCodes;
		 global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2;
		 global $Ecode;
		 
         DrawPopBG($ex,$ey,$w,$h,$title ,"12",$BackURL);
		 $planstmp=getMysqlDataArray($tableName);
		 $plansArray=returnDataArray($planstmp,1,$Ecode);
		 $startDay=explode("_",$plansArray[2]);
		 //From
		 echo   "<form id='AddPlan'  name='Show' action='".$BackURL."' method='post'>";
		 $code=returnDataCode( );
		 echo   "<input type=hidden name=code value=".$code.">";
	 	 echo   "<input type=hidden name=plan value=".$Ecode.">"; 		 
		 echo   "<input type=hidden name=PhpInputType value=upAddType >";
		 echo   "<input type=hidden name=line value=".$plansArray[4]." >";
		// echo   "<input type=hidden name=plan value=".$plansArray[3]." >";
		 echo   "<input type=hidden name=data_type value=data>"; 
		  $selecttype= $SelectType_1[$Stype_1];
		 echo   "<input type=hidden name=selecttype value=".$selecttype.">"; 
		  $lastUpdate=date(Y_m_d_H_i,time()+(8*3600));
		 echo   "<input type=hidden name=lastUpdate value=".$lastUpdate.">"; 
		 // echo   "<input type=hidden name=type value=細分>"; 
		 //年
	     $input="<input type=text name=year value='".$startDay[0]."'  size=4>年";
	     DrawInputRect("新增","12","#ffffff",($ex),$ey ,120,16, $colorCodes[4][2],"top",$input);
	     //月
	     $input="<input type=text name=month value='".$startDay[1]."'  size=2>月";
	     DrawInputRect("","12","#ffffff",($ex+80),$ey ,120,16, $colorCodes[4][2],"top",$input);
		 //日
	     $input="<input type=text name=day value='".$startDay[2]."'  size=2>日".$plansArray[3];
	     DrawInputRect("","14","#ffffff",($ex+130),$ey ,220,16, $colorCodes[4][2],"top",$input);
		 //類別計畫
 
	     $select=MakeSelectionV2($SelectType_2,"設定","type",140);
	     DrawInputRect("","14","#ffffff",($ex+310 ),$ey,140,18, $colorCodes[4][2],"top",  $select."計畫");
	     $workDayinput="<input type=text name=workingDays  value='5'  size=2   >";
	     DrawInputRect("天數","12","#ffffff",($ex+240),$ey+40,120,18, $colorCodes[4][2],"top",$workDayinput);
		 $submitP="<input type=submit name=submit value=新增規畫>";
	     DrawInputRect("",$ey-22 ,"#ffffff",($ex+320),60,120,18, $colorCodes[4][2],"top",$submitP);
 
 }
 function AddPlanEditor_v2($ex,$ey,$w,$h,$y,$m,$d){
	     global $data_library,$tableName;   
	     global $colorCodes;
		 global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1;
	     if($Stype_1=="")$Stype_1=0;
  	     $title="新增".$y."年".$m."月".$d."日" .$SelectType_1[$Stype_1]."計畫";
		//  echo $BackURL;
         DrawPopBG($ex,$ey,$w,$h,$title ,"12",$BackURL);
		 //From
		 echo   "<form id='AddPlan'  name='Show' action='".$BackURL."' method='post'>";
		 //Hides
		// echo   "<input type=hidden name=BackURL  value=".$BackURL.">"; 
		 echo   "<input type=hidden name=tablename value=".$tableName.">"; 
		 echo   "<input type=hidden name=data_type value=data>"; 
	  	 echo   "<input type=hidden name=PhpInputType value=upAdd >"; 
		 $startDay=$y."_".$m."_".$d;
		 echo   "<input type=hidden name=startDay value=".$startDay.">"; 
		 $lastUpdate=date(Y_m_d_H_i,time()+(8*3600));
		 echo   "<input type=hidden name=lastUpdate value=".$lastUpdate.">"; 
		 $code=returnDataCode( );
		 echo   "<input type=hidden name=code value=".$code.">"; 
		 $selecttype= $SelectType_1[$Stype_1];
		 echo   "<input type=hidden name=selecttype value=".$selecttype.">"; 
		 
		 //input
	     $Planinput="<input type=text name=plan value='".$plan."'  size=30 >";
	     DrawInputRect("計畫","12","#ffffff",($ex),$ey+40,300,18, $colorCodes[4][2],"top",$Planinput);
		 //
	     $workDayinput="<input type=text name=workingDays  value='5'  size=2   >";
	     DrawInputRect("天數","12","#ffffff",($ex+240),$ey+40,120,18, $colorCodes[4][2],"top",$workDayinput);
		 
		 $Lineinput="<input type=text name=line value='1'  size=2   >";
     	 DrawInputRect("行數","12","#ffffff",($ex+240),$ey+70,120,18, $colorCodes[4][2],"top", $Lineinput);
	
		 $types=array("工項","目標","Sprint");
	     $select=MakeSelectionV2($types,"工項","type",160);
	     DrawInputRect("類型","10","#ffffff",($ex ),$ey+70,120,18, $colorCodes[4][2],"top",  $select);
 	 
		 $submitP="<input type=submit name=submit value=新增計畫>";
	     DrawInputRect("",$ey-20 ,"#ffffff",($ex+320),60,120,18, $colorCodes[4][2],"top",$submitP);

  }
function EditPlan_v2($ex,$ey,$w,$h){
	        global $data_library,$tableName;   
			global $Ecode;
		    global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1;
		 
			$planstmp=getMysqlDataArray($tableName);
			$plansArray=returnDataArray($planstmp,1,$Ecode);
		//	for($i=0;$i<count($plansArray);$i++)
		    	//echo $codeData[$i];
	        //From
			 // echo $BackURL;
		    echo   "<form id='EditPlan'  name='Show' action='".$BackURL."' method='post'>";
		   //Hides
	       // echo   "<input type=hidden name=BackURL  value=".$BackURL.">"; 
			echo   "<input type=hidden name=data_type value=data>"; 
		    echo   "<input type=hidden name=tablename value=".$tablename.">"; 
		 
	  	    echo   "<input type=hidden name=PhpInputType value=upEdit >"; 
		    $lastUpdate=date(Y_m_d_H_i,time()+(8*3600));
		    echo   "<input type=hidden name=lastUpdate value=".$lastUpdate.">"; 
		    echo   "<input type=hidden name=code value=".$Ecode.">"; 
		    $selecttype= $SelectType_1[$Stype_1];
		    echo   "<input type=hidden name=selecttype value=".$selecttype.">"; 
		    DrawPopBG($ex,$ey,$w,$h,"" ,"12",$BackURL);
			//年
			$startDay=explode("_",$plansArray[2]);
			$input="<input type=text name=year value='".$startDay[0]."'  size=4>年";
	        DrawInputRect("修改","12","#ffffff",($ex),$ey ,120,16, $colorCodes[4][2],"top",$input);
			//月
		    $input="<input type=text name=month value='".$startDay[1]."'  size=2>月";
	        DrawInputRect("","12","#ffffff",($ex+80),$ey ,120,16, $colorCodes[4][2],"top",$input);
			//日
		    $input="<input type=text name=day value='".$startDay[2]."'  size=2>日計畫";
	        DrawInputRect("","12","#ffffff",($ex+130),$ey ,120,16, $colorCodes[4][2],"top",$input);
			
	        $Planinput="<input type=text name=plan value='".$plansArray[3]."'  size=30 >";
	        DrawInputRect("計畫","12","#ffffff",($ex),$ey+40,300,18, $colorCodes[4][2],"top",$Planinput);
		    //
	        $workDayinput="<input type=text name=workingDays  value='".$plansArray[6]."'  size=2   >";
	        DrawInputRect("天數","12","#ffffff",($ex+240),$ey+40,120,18, $colorCodes[4][2],"top",$workDayinput);
		 
		    $Lineinput="<input type=text name=line value='".$plansArray[4]."'  size=2   >";
     	    DrawInputRect("行數","12","#ffffff",($ex+240),$ey+70,120,18, $colorCodes[4][2],"top", $Lineinput);
	
		    $types=array("工項","目標","Sprint");
	        $select=MakeSelectionV2($types,$plansArray[5],"type",160);
	        DrawInputRect("類型","10","#ffffff",($ex ),$ey+70,120,18, $colorCodes[4][2],"top",  $select);
 	 
		    $submitP="<input type=submit name=submit value=修改計畫>";
	        DrawInputRect("",$ey-60 ,"#ffffff",($ex+320),60,120,18, $colorCodes[4][2],"top",$submitP);
				
             
			//刪除
	        $input="<input type=text name=del value=''  size=3>";
	        DrawInputRect("輸入刪除碼","12","#ffffff",($ex+222),$ey+130 ,220,16, $colorCodes[4][2],"top",$input);	
			
		    $submitP="<input type=submit name=submit value=刪除計畫>";
	        DrawInputRect("",$ey+60 ,"#ffffff",($ex+320),60,120,18, $colorCodes[4][2],"top",$submitP);
	 }
?>
