<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>工作資源排程區</title>
</head>
<?php  //主控台
   $id=$_COOKIE['IGG_id'];
   include('PubApi.php');
   include('CalendarApi.php');  
   include('mysqlApi.php');
   include('scheduleApi.php');
   
    defineData_schedule();   //定義基礎資料 
    GetCalendarData(); //取得日曆資料(scheduleApi)
    DrawBaseCalendar_v2(); //列印基礎日期資料(scheduleApi)
    DrawOrders();
?>
<?php //主要資料
 	 function  defineData_schedule(){
		  global $BaseURL;
		  $BaseURL="ResSchedule.php";
		 //基礎數值
		 global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX,$monthLoc,$showMonthNum,$LineHeight,$LineRec ;
	     global $UpMonth;
		        $StartX=20;
	            $StartY=90;
	            $MonthWidth=200;
	            $MonthWidth=200;
	            $OneDayWidth=15;
				$LineHeight=40;
	            $CurrentX= $StartX;
				$showMonthNum=8;
				$daysLoc=array();//(year,m,d,x軸位置)
                $monthLoc=array();//($y,m,x軸位置,Siz)
				$LineRec=array();//紀錄哪行有排列
		 //資料表
		 global $data_library,$tableName,$MainPlanData;
		 global $EditHide; 
				$tableName="fpschedule";
			    $data_library="iggtaiperd2";
				$MainPlanDataT=getMysqlDataArray($tableName); 
				$MainPlanDataT2=filterArray($MainPlanDataT,0,"data"); 
				$MainPlanData=$MainPlanDataT2;
				if($EditHide!="showAll")$MainPlanData=filterArray($MainPlanDataT2,18,""); 
		 //共用資料表
	     global $OutsData,$memberData, $milestoneSelect;
		 global $WarringDatas;
			    $WarringDatas= array();
                $OutsData=getMysqlDataArray("outsourcing");	 
      	        $memberData=getMysqlDataArray("members");
				$mt=getMysqlDataArray( "scheduletype"); 
	            $mt2=filterArray($mt,0,"milestone"); 
			    $milestoneSelect=returnArraybySort($mt2,2);
	 }
?>
<?php //List
        function DrawOrders(){
		         global  $MainPlanData;
				 $typesOrderT= filterArray($MainPlanData,5,"工項");
				 $typesOrder= filterArray( $typesOrderT,10,"UI");
                 $Rect=array(20,200,300,20);	
                 $fontColor="#ffffff";
		         $BgColor=array("#333333","#000000");			
                 echo "<div id=LockX style='LockX :pointer ; color:#ffaabb;  z-index:-4; position:fixed ; ";
				 echo "top:".$Rect[1]."px; left:".$Rect[0]."px; width:".$Rect[2]."px; height:1222px; background-color:#000000; ";
                // echo "top:".$Rect[1]."px; left:".$Rect[0]."px; width:".$Rect[2]."px;height:133px; background-color:#000000; ' "; 
				 echo " >aa"  ; 	 
				 for($i=0;$i<count($typesOrder);$i++){
				     $a= $i % 2;
					 $msg=  $typesOrder[$i][3];
					 $BGc=$BgColor[$a];
					  DrawLinkRect_LayerPos($msg,10,$fontColor,$Rect, $BGc,$Link,$border,1);
				     $Rect[1]+=10;
				 }
				   echo "</div>";
		}
?>
<?php
      
       function DrawList(){
		   $x=100;
		   $y=100;
		   $w=100;
		   $h=1000;
		   $Rect=array(100,100,100,1000);
		   $fontColor="#ffffff";
		   $BgColor="#000000";
		   $msg="bbb";
		   DrawLinkRect_LayerPos($msg,12,$fontColor,$Rect,$BgColor,$Link,$border,-1);
	   }
	    function DrawLinkRect_LayerPos($msg,$fontSize,$fontColor,$Rect,$BgColor,$Link ,$Layer){
	          echo "<div id=LockX2 style='LockX :pointer ; color:".$fontColor."; " ;
			  echo " z-index:".$Layer ."; ";
			  echo " text-align:center ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo " position:static; ";
              echo "top:".$Rect[1]."px; left:".$Rect[0]."px; width:".$Rect[2]."px;height:".$Rect[3]."px; background-color:".$BgColor."; ' "; 
			  echo " onclick=location.href='".$Link."'; >";
			  echo $msg;
	          echo "</div>";
	   }
	    function DrawLinkRect_LayerPos_bak($msg,$fontSize,$fontColor,$Rect,$BgColor,$Link ,$Layer){
	          echo "<div id=LockX  style='LockX :pointer ; color:".$fontColor."; " ;
			  echo " z-index:".$Layer ."; ";
			  echo " text-align:center ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo " position:fixed ; ";
 
              echo "top:".$Rect[1]."px; left:".$Rect[0]."px; width:".$Rect[2]."px;height:".$Rect[3]."px; background-color:".$BgColor."; ' "; 
			  echo " onclick=location.href='".$Link."'; >";
			  echo $msg;
	          echo "</div>";
	   }
 
?>
<body bgcolor="#b5c4b1">
 <script type="text/javascript">
     var a= document.getElementById("LockX");
     a.innerHTML ="xxxx";
     var baseh=parseInt( a.style.top);
	 a.innerHTML =baseh;
	 window.onscroll = function(){
		   var f=baseh+document.body.scrollTop;
　         a.innerHTML =f  ;
           a.style.top=baseh-document.body.scrollTop ;
　　 }
</script>