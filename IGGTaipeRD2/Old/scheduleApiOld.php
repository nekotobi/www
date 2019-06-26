<?php //繪製
     function DrawWorkOrde( $ScheduleDatas,$projects){
		    global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX,$monthLoc ; 
			global $colorCodes;
		    //0GDSn 1GDVer 2ProposeDate 3FinshDate 4OrderID 5file 6name 7info 8reference 
		    //9Remarks 	10sn 11type  12ArtStartDay 	13WorkDay 	14ArtFinDay 	15ArtVer 	16Artprincipal 17project 18out
		       echo "</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>";
                $c=-1;
		       $y=$StartY+425;
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
			       if(  $Artprincipal!=$upart){$c+=1;$upart=$Artprincipal;}
				    $n=$c%count($colorCodes);
			        $BGColor= $colorCodes[$n][2]; 
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
	 function DrawProjectRect( $ProjectNum, $projects){
		    global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX,$monthLoc ; 
		 	global $colorCodes;
		    $y=$StartY+425;
			$c=-1;
		 for($i=0;$i<count( $ProjectNum);$i++){
			 if ($ProjectNum[$i][1]!=0){
				 $c+=1;
			     $BgColor= $colorCodes[ $c%count($colorCodes)][2];
			     $h= $ProjectNum[$i][1]*22;
				 $cid=getmemberCID($ProjectNum[$i][0]);
				 $info= $cid."[". $ProjectNum[$i][1]."]";
			     DrawRect( $info,12,"#ffffff",$StartX-10,$y,"80",$h,$BgColor);
				 $y+=$h;
			     DrawRect( "",12,"#ffffff",$StartX-10,$y-5,"1200","1",$BgColor);
			  }
		    // DrawMemberRect($ProjectNum[$i][0],"11","#ffffff", $x+2,$y, "60",$h,$color,"","P".$i);
		 }
	 }
     function DrawBaseCalendar(){  //日曆格
		      global $StartX, $StartY,$OneDayWidth,$daysLoc,$monthLoc, $YearLoc; 
	          global $TargetYear,$TargetMonth,$YearRange,$MonthRange,$showMonthNum;
			  global $SelectType;
			  global $colorCodes;
			  $LinkURL="scheduleAll.php?SelectType=".$SelectType."&PhpInputType=AddPlan";
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
		          $Link= $LinkURL."&ed=".$daysLoc[$i][2]."&em=".$startM."&ey=".$starty."&dx=".($daysLoc[$i][3]-8)."&dy=".($StartY+60);
			      if($daysLoc[$i][4]!="0")$color=$colorCodes[1][1];
			       DrawabsoluteRect($daysLoc[$i][2],"8","#000000",  $daysLoc[$i][3]-8, $StartY+60 ,  $OneDayWidth-1 ,"20",$color,$pos, $Link);
				  }       
			  DrawSprint($StartY+80 );
              echo "</div>"	;		  
	 }


?>
<?php  //上傳

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
?>
<?php //popMenu
  	 function DrawOrder($x,$y,$w,$h){
		  global $Order,$UpSn,$Etype;
	      if ($Order=="")return;
		  include('scheduleOrder.php');
		  inputOrder($x,$y,$w,$h,$Order,$OrderID, $UpSn,$Etype);
	 }
?>
<?php  //判斷輸入
     function CheckinputType(){
	       global $epy,$epm,$epd,$epLine,$epDay,$eptype;
	       global $ed,$em,$ey,$dx,$dy;
		   global $PhpInputType;
		   if($PhpInputType=="")return;
		   include('scheduleOrder.php');
		   switch ($PhpInputType){
		        case $PhpInputType=="AddPlan":
			         AddPlanEditor($dx,$dy+120,"400","20",$ey,$em,$ed); 
			    break;
		        case $PhpInputType=="EditPlan":
			        EditPlan($epy,$epm,$epd,$epLine,$epDay,$eptype);
			    break;
		   }
	 
	 }
     function DrawType(){
		 	  global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX,$monthLoc ;
	          global $SelectScheduleType, $SelectType,$State,$SelectScheduleType2;
			  global $colorCodes;
			  global $LinkURL;
		  	  $SelectScheduleType=array("總規劃","角色","怪物","場景","UI");
			  $SelectScheduleType2=array(array(""),array("設定","模型","動作","特效") ,array("設定","模型","動作","特效")
			                      ,array("設定","物件","InGame"));
		  
	          for ($i=0;$i<count($SelectScheduleType);$i++){
				   $x=120+ $i*110;
				   $y=80;
                   $Link="scheduleAll.php?SelectType=".$i ;
				   $msg="　".$SelectScheduleType[$i];
				   $color= "#222222";
				   if($SelectType==$i)$color= "#ff2212";
			       DrawLinkRect($msg,"12","#ffffff",$x,$y,"100","20",$color,$Link,1);
			  }
			  DrawState();
	 }
 
?>
<?php //依類別排列
     function ShowType(){
	          global $SelectScheduleType, $SelectType,$State;
			  global $StartY;
		      DrawPlan($StartY+120); 
         	  DrawScheduleData(); 
	 }
	 
	 function  DrawScheduleData(){
	           global   $ProjectDataName,$ScheduleDatas;
	         // $ScheduleDatas= getMysqlDataArray($ProjectDataName);
			   $ScheduleDataTmp1= getMysqlDataArray($ProjectDataName);
			   $ScheduleDataTmp=filterArray($ScheduleDataTmp1,15,"");
			   $membersTmp=getMysqlDataArray("members");
			   $projects= getMysqlDataArray("projectdata");
			   $ScheduleDatas =SortOrders($ScheduleDataTmp,"16",$membersTmp,"0");
			   $ProjectNum= returnOrdersNumArray($ScheduleDataTmp,"16",$membersTmp,"0");
			   DrawDragArea(count($ScheduleDatas)+22);
			 //  DrawProjectRect( $ProjectNum,$projects);
			 //  DrawWorkOrde( $ScheduleDatas,$projects);
	 }

?>
<?php  //基本資料

	 function defineData(){
		 global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX,$monthLoc,$showMonthNum ;
		 global $SelectScheduleType, $SelectType,$State;
         global $LockProject;
		 global $BackURL,$tablename;
		        $BackURL="scheduleAll.php?SelectType=".$SelectType;
				$tablename="rpgartschedule";
				$State=array("未製作","優化","進行中","已完成","最終版完成");
                $LockProject="RPG";
	            $StartX=20;
	            $StartY=80;
	            $MonthWidth=200;
	            $OneDayWidth=15;
	            $CurrentX= $StartX;
	            $daysLoc=array();//(year,m,d,x軸位置)
                $monthLoc=array();//($y,m,x軸位置,Siz)
				$showMonthNum=8;
	 }
	
?> 

<?php  //類別總規劃
    function  DrawPlan($sy){
		      global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX,$monthLoc ; 
			  global $colorCodes;
			  global $SelectScheduleType, $SelectType,$State,$SelectScheduleType2;
			  global $LinkURL;
			  if($SelectType=="")$SelectType=0;
	          $plansTmp=getMysqlDataArray("rpgartschedule");
			  $plansTmp2=  filterArray( $plansTmp ,14,$SelectScheduleType[$SelectType]);
			  $plans= sortArrays( $plansTmp2 ,5 ,"false");
			  $ColorJump=array(0,0,0,0,0);
			  $LinkURL="scheduleAll.php?PhpInputType=EditPlan&SelectType=".$SelectType;
			  $color_num=2;
			  for($i=0;$i<count($plans);$i++){
			      $d=returnDateString($plans[$i][0],$plans[$i][1],$plans[$i][2]);
				  $x=RetrunXpos($daysLoc,$d);	
	              $info= $plans[$i][4];	
				  $line=$plans[$i][5];
                  $color= $colorCodes[$color_num][$ColorJump[$line]+1];	
				  $fontColor="#222222";
		          $process=$plans[$i][8];
				  if(  $plans[$i][7]=="目標" ){
				  $color="#772233";
				  $fontColor="#eeeeee";
				  $Link=$LinkURL."&epy=".$plans[$i][0]."&epm=".$plans[$i][1]."&epd=".$plans[$i][2].
				   "&epLine=".$line."&epDay=".$plans[$i][3]."&eptype=".$plans[$i][6];
				   DrawabsoluteRect("","0",$fontColor, $x, $sy-20,"2" ,$line*20+38, $color,  "absolute", $Link );
				  }
                  $w= $plans[$i][3]*$OneDayWidth;
				  $yadd=$plans[$i][5]*20;
				  $y=$sy+$yadd;
				  $Link=$LinkURL."&epy=".$plans[$i][0]."&epm=".$plans[$i][1]."&epd=".$plans[$i][2].
				       "&epLine=".$line."&epDay=".$plans[$i][3]."&eptype=".$plans[$i][6];
			      $startDayArray=array($plans[$i][0],$plans[$i][1],$plans[$i][2]);
				  $nowDayArray=array(date(Y),date(m),date(d));
				  $passDays= getPassDays($startDayArray,$nowDayArray);
				  $nowState=0;
				  
				  //進度=====================================================
		          if($SelectType>0){ 
					  $ssx=$x;
				      $ps= explode("_",$process);
					  $totallDays=0;
					    $st=$info.">>";
				      for($j=0;$j<count($ps);$j++){
						  $w2= $ps[$j]*$OneDayWidth;
						  $totallDays+=$ps[$j];
						  $color2=$colorCodes[9][$j+1];
						  if($ps[$j]!=0 ){
							  $st=$st.$SelectScheduleType2[$SelectType][$j];
							  DrawLinkRect($st,"12",$fontColor, $ssx, $y,$w2 ,"20", $color2,$Link,"1");
						  }  					 
					
						  $ssx+=$w2;
						  if( $passDays>$totallDays  )$nowState=$j+1;
						  if($ps[$j]==0 )$nowState=$j+1;
						  if($ps[$j]!=0 ) $st="";
				     }
					 if($nowState>=count($ps))$nowState=count($ps)-1;
					
				  $color=$colorCodes[9][$color_num];
				  $state=$SelectScheduleType2[$SelectType][$nowState];
				  if($state!=$plans[$i][9]) $color="#ff0000";
				  if ($passDays==0)$color="#cccccc";
				  if($plans[$i][9]=="完成")$color="#999999";
				  $info=$info.">>".$state ;
				  }
			      if($SelectType==0)
				  DrawLinkRect($info,"10",$fontColor,$x, $y,$w ,"16", $color,$Link,"1");
				  if($ColorJump[$line]==0){
					   $ColorJump[$line]=1;
				   }else{
				   $ColorJump[$line]=0;
				   }
				   $color_num+=1;
				   if( $color_num>7)$color_num=3;
			  }
			 
	 }
	


?>