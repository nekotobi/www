
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>工單排程區</title>
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
	 /*
	function DropMember(event){
	         event.preventDefault();
		     var DragID  = event.dataTransfer.getData("text");
	       	 var targetID = event.currentTarget.id
			 document.Show.Xoffset.value=targetID;
	}
	*/
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
<body bgcolor="#b5c4b1">
<?php
     $id=$_COOKIE['IGG_id'];
     include('PubApi.php');
	 include('CalendarApi.php');  
	 include('mysqlApi.php');
     $type=array("UI","設定","3D角色","3D場景");
     $array=array("cbartschedule","");
	 $data_library="iggtaiperd2";
	 defineData();
	 DrawUserData( 25, 0);
	 $DeadLine=array("1","8");
	 if($ProjectDataName=="") $ProjectDataName="rpgartschedule";
	 if($sendData=="DragWorkOrder"){
	    upData();
	  }
	   GetCalendarData();
	   DrawScheduleData();
	   DrawBaseCalendar();
	   CreatForm();
       DrawMembersDragArea( 30,32);
	  // showDetail();
	   AddOder(35,75);
	   DrawOrder(70,150,400,500);
	   
	   
	   
	 /*  
	 function showDetail(){
		   include('scheduleDetail.php');
		   global $ScDetail;
		   if($ScDetail==null)return;
	       DrawSceduleDetailInfo(200,120,800,600,$ScDetail);
	 }		 
	   */
	 function FixShowSize(){
	          
	 
	 }
     function upData(){
	       global $DragID, $DragArea, $data_library,$ProjectDataName;
		   global $Xoffset,$OneDayWidth;
	       $DragDate = explode( "-", $DragArea);
	       $mysqlData=  explode( "-", $DragID);
		   if($DragDate[0]=="Area"){
		    if ($mysqlData[0]=="WorkOrder" or $mysqlData[0]=="Art"  ){
			    $Base=array("ArtStartDay");
		        $up=array($DragDate[1]);
	            $WHEREtable=array("GDSn","sn");
		        $WHEREData=array($mysqlData[1],$mysqlData[2]);
	            $stmt= MakeUpdateStmt(  $data_library,$ProjectDataName,$Base,$up,$WHEREtable,$WHEREData);
			    SendCommand($stmt,$data_library);
		       }
		    if ($mysqlData[0]=="Scale"){
				$startDay=$mysqlData[3];
				$EndDay=$DragDate[1];
				$workDay=  $mysqlData[3]+($Xoffset/$OneDayWidth);
			    if($workDay>=1){
			      $Base=array("WorkDay");
		          $up=array($workDay);
	              $WHEREtable=array("GDSn","sn");
		          $WHEREData=array($mysqlData[1],$mysqlData[2]);
				  $stmt= MakeUpdateStmt(  $data_library,$ProjectDataName,$Base,$up,$WHEREtable,$WHEREData);
			      SendCommand($stmt,$data_library);
			    } 
			 }
		   }
		   if($DragDate[0]=="User"){
		        $Base=array("Artprincipal");
		        $up=array($DragDate[1]);
	            $WHEREtable=array("GDSn","sn");
		        $WHEREData=array($mysqlData[1],$mysqlData[2]);
	            $stmt= MakeUpdateStmt(  $data_library,$ProjectDataName,$Base,$up,$WHEREtable,$WHEREData);
			    SendCommand($stmt,$data_library);
		   }		   
	 }
	 function DrawOrder($x,$y,$w,$h){
		  global $Order,$UpSn,$Etype;
	      if ($Order=="")return;
		  include('scheduleOrder.php');
		  inputOrder($x,$y,$w,$h,$Order,$OrderID, $UpSn,$Etype);
	 }
     function AddOder($x,$y){
		     if($_COOKIE['IGG_id']==""){
			   DrawLinkRect("需登入才能新增工單","14","#ffffff",$x,$y,"200","20","#222222","schedule.php ","");
				 return;
				 }
	          DrawLinkRect("新增工單+","14","#ffffff",$x,$y,"200","20","#000000","schedule.php?Order=new","");
	 }
	 function defineData(){
		 global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX,$monthLoc ;
         global $LockProject;
                $LockProject="RPG";
	            $StartX=20;
	            $StartY=80;
	            $MonthWidth=200;
	            $OneDayWidth=15;
	            $CurrentX= $StartX;
	            $daysLoc=array();//(year,m,d,x軸位置)
                $monthLoc=array();//($y,m,x軸位置,Siz)
	 }
	 function  DrawScheduleData(){
	          global   $ProjectDataName,$ScheduleDatas;
	         // $ScheduleDatas= getMysqlDataArray($ProjectDataName);
			  $ScheduleDataTmp= getMysqlDataArray($ProjectDataName);
			  $projects= getMysqlDataArray("projectdata");
			  $ScheduleDatas =SortOrders($ScheduleDataTmp,"17",$projects,"0");
			  $ProjectNum= returnOrdersNumArray($ScheduleDataTmp,"17",$projects,"0");
			   DrawDragArea(count($ScheduleDatas)+6);
			   DrawProjectRect( $ProjectNum,$projects);
			   DrawWorkOrde( $ScheduleDatas,$projects);
			 
	 }
	  
	 function DrawProjectRect( $ProjectNum, $projects){
		 global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX,$monthLoc ; 
		 	global $colorCodes;
		     $y=$StartY+85;
		 for($i=0;$i<count( $ProjectNum);$i++){
			 if ($ProjectNum[$i][1]!=0){
				 $n=returnProjectColor($projects,$ProjectNum[$i][0]);
			     $BgColor= $colorCodes[$n][1];
			     $h= $ProjectNum[$i][1]*22;
				 $info= $ProjectNum[$i][0]."[". $ProjectNum[$i][1]."]";
			     DrawRect( $info,12,"#ffffff",$StartX-10,$y,"80",$h,$BgColor);
				 $y+=$h;
			     DrawRect( "",12,"#ffffff",$StartX-10,$y-5,"1200","1",$BgColor);
			  }
		    // DrawMemberRect($ProjectNum[$i][0],"11","#ffffff", $x+2,$y, "60",$h,$color,"","P".$i);
		 }
	 }
	 function DrawWorkOrde( $ScheduleDatas,$projects){
		    global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX,$monthLoc ; 
			global $colorCodes;
		    //0GDSn 1GDVer 2ProposeDate 3FinshDate 4OrderID 5file 6name 7info 8reference 
		    //9Remarks 	10sn 11type  12ArtStartDay 	13WorkDay 	14ArtFinDay 	15ArtVer 	16Artprincipal 17project 18out
		       echo "</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>";
		       $y=$StartY+85;
	           for($i=0;$i< count($ScheduleDatas);$i++){
				   $Artprincipal=$ScheduleDatas[$i][16];
				   $info= "　".$ScheduleDatas[$i][6] ;
				   $ArtStartDay=$ScheduleDatas[$i][12];
				   $WorkDay=$ScheduleDatas[$i][13];
				   $ArtFinDay =$ScheduleDatas[$i][14]; 
				   $x=RetrunXpos($daysLoc,$ArtStartDay);
				   $wid=$WorkDay*$OneDayWidth;
				   $ArtVer=$ScheduleDatas[$i][15];
				   $id="WorkOrder-".$ScheduleDatas[$i][0]."-".$ScheduleDatas[$i][10];
				   $n=returnProjectColor($projects,$ScheduleDatas[$i][17]);
				   //echo $ScheduleDatas[$i][17].">".$n;
			        $BGColor= $colorCodes[$n][1]; 
				   if(   $Artprincipal=="" or $ArtVer =="Fin")  $BGColor="#bbbbbb";
				   //內容
				   $Link="schedule.php?Order=".$i."&OrderID=".$ScheduleDatas[$i][4]."&UpSn=".$ScheduleDatas[$i][10]; 
				   $xl=0;
				 
				   if($WorkDay< strlen($info))
				   {
					   DrawLinkRect("","12","#111111",$x,$y,$wid,"18",$BGColor,$Link,"");
					   $xl=strlen($info)*8;
				       $wid=strlen($info)*8-10;
					   DrawLinkRect("","12","#111111",$x-$xl+20,$y+6,$wid,"4",$BGColor,$Link,"");
				   }
				   DrawLinkRect( $info,"12","#111111",$x-$xl,$y,$wid,"16",$BGColor,$Link,"");
				   
				   
				   $swide=($WorkDay-1)*$OneDayWidth ;
				   $scaleID="Scale-".$ScheduleDatas[$i][0]."-".$ScheduleDatas[$i][10]."-".$WorkDay."-".($x+$swide);
				   if($ArtVer !="Fin") DrawDragbox($x-5+$swide+10,$y,"10" ,"18"   , $colorCodes[$n][0], $scaleID, "","2");
			       
			       $id="Art-".$ScheduleDatas[$i][0]."-".$ScheduleDatas[$i][10];
				   $pic="Pics/Members/".$Artprincipal.".png";
				   if($Artprincipal==""){
					   $pic="Pics/Members/member.png";
				    }
				   DrawPicwithID($pic,$y,$x-2,"20","20",$id);
				   if($ArtFinDay!=""){
					   if($ArtVer!="Fin"){
					      $CheckPic="Pics/Check.gif"; 
					      DrawLinkPic($CheckPic,$y+2,$x-10,"18","18","");
					   }
				 	   if($ArtVer =="Fin"){
					      $CheckPic="Pics/Finish.png"; 
					      DrawLinkPic($CheckPic,$y+2,$x-10,"50","12","");
					   }
				   }
				   $y+=22;
			   }
	 }
      function DrawDragArea($height ){
		       global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX  ; 
			   global $DeadLine;
	           for($i=0;$i<count($daysLoc);$i++){
				   $d=$daysLoc[$i][2];
				   if($d<10)$d="0".$d;
				   $id="Area-".$daysLoc[$i][0]."/".$daysLoc[$i][1]."/".$d."-".$daysLoc[$i][3];
				   $x=$daysLoc[$i][3];
				   $h=($height+1)*20 ;
				   $BGColor="#aaaaaa";
				   if($daysLoc[$i][1]==  $DeadLine[0] && $daysLoc[$i][2]== $DeadLine[1] ){
				    $BGColor="#a27e7e";
				   }
				   if($daysLoc[$i][1]==date("m") && $daysLoc[$i][2]==date("d") ){
				    $BGColor="#C99899";
				   }
			       DrawDragRect($x, $StartY+80,($OneDayWidth-2),$h,$BGColor,$id);
			  }  
	 
	 }
	 function checkCalendarData(){
		   global   $ProjectDataName, $data_library;
	       ReSortSn( $data_library,$ProjectDataName);
		   $d=date("Y/m/d");
	       SetMysqldefineData($data_library,$ProjectDataName,"ArtStartDay",$d,false);
	       SetMysqldefineData($data_library,$ProjectDataName,"WorkDay","5",false);
	 }
	 
     function RetrunXpos($daysLoc,$date){
		       global $StartX;
		       for($i=0;$i<count($daysLoc);$i++){
				    $dd=$daysLoc[$i][2];
					if($dd<10)$dd="0".$daysLoc[$i][2];
		            $d=$daysLoc[$i][0]."/".$daysLoc[$i][1]."/".$dd;
			        if($d==$date){  
				       return $daysLoc[$i][3];
			          }
			   }
		      return $StartX;
     }

	 function getDaysLoc($y,$m){
	            global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX,$monthLoc ; 
				$MonthEnd=getMonthDay($m,$y);
				$sx=$CurrentX+$OneDayWidth;
				for($i=1;$i<=$MonthEnd;$i++){
				    $CurrentX+=$OneDayWidth;
					$daydata=array($y,$m,$i,$CurrentX);
				    array_push(   $daysLoc,$daydata);
				}
		     	$monthData=array($y,$m,$sx,$CurrentX-$sx+$OneDayWidth);
			    array_push( $monthLoc,$monthData);
		 
	 }
	 function GetCalendarData(){
	          global $StartX, $StartY,$OneDayWidth,$daysLoc,$monthLoc, $YearLoc; 
	          global $TargetYear,$TargetMonth,$YearRange,$MonthRange,$showMonthNum;
			  global  $colorCodes;
	          SetCalendarRange("","");
			  $MonthTotalWidth=0;
			  for($i=0;$i<count($MonthRange);$i++){
			       getDaysLoc( $YearRange[$i], $MonthRange[$i]);
			  }
	 }
     function DrawBaseCalendar(){
		       global $StartX, $StartY,$OneDayWidth,$daysLoc,$monthLoc, $YearLoc; 
	          global $TargetYear,$TargetMonth,$YearRange,$MonthRange,$showMonthNum;
			  global  $colorCodes;
			  $pos="fixed";
			  for($i=0;$i<count($daysLoc);$i++){
			     DrawabsoluteRect($daysLoc[$i][2],"8","#000000",  $daysLoc[$i][3], $StartY+60 ,  $OneDayWidth-1 ,"20",  $colorCodes[2][4],$pos);
			  }  
			  
			   for($i=0;$i<count($monthLoc);$i++){
			       DrawabsoluteRect($monthLoc[$i][1],"10","#ffffff",  $monthLoc[$i][2], $StartY+40 ,  $monthLoc[$i][3]-1 ,"20",  $colorCodes[2][2],$pos);
				   DrawabsoluteRect($monthLoc[$i][0],"10","#ffffff",  $monthLoc[$i][2], $StartY+20 ,  $monthLoc[$i][3]-1 ,"20",  $colorCodes[2][1],$pos);
			  }  
	 }
     function returnYearMonthNum($YearRange ){
	         $t=1;
			 $upY=$YearRange[0];
			 $ry=array();
			 for($i=1;$i<count($YearRange);$i++){
			      if($YearRange[$i]!=$UpY){
					  array_push( $ry,array($UpY,$t));
				      $UpY=$YearRange[$i];
					  $t=1;
				  }
				  if($i==count($YearRange)-1){
				   array_push( $ry,array($UpY,$t));
				  }
				  		 $t+=1;
			 }
			 return $ry;
	 }
	 function CreatForm(){
		 global   $data_library , $ProjectDataName ;
	       echo   "<form id='Show'  name='Show' action='schedule.php' method='post'>";
	       echo   "<input type='hidden' name='data_library' value=".$data_library." >";
	       echo   "<input type='hidden' name='ProjectDataName' value=".$ProjectDataName." >";
	       echo   "<input type='hidden' name='DragArea' value=".$DragArea.">" ;
	       echo   "<input type='hidden' name='DragID' value=".$DragID.">" ;
		   echo   "<input type='hidden' name='startpx' value=".$startpx.">" ;
		   echo   "<input type='hidden' name='Xoffset' value=".$Xoffset.">" ;
           echo   "<input type='hidden' name='upID' value=".$upID.">" ;
		   echo   "<input type='hidden' name='upColor' value=".$upColor.">" ;
		   echo   "<input type='hidden' name='sendData' value=".$sendData.">" ;
		  // echo   "<input type='text' name='upColor' value='0' size='22'>upColor";
 
           echo   "</form>";
	 }
?>

 </body>
 </html>
 
