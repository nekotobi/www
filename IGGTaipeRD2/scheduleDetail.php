<?php

 function DrawSceduleDetailInfo($x,$y,$w,$h,$ScheduleSn){
	          include('formApi.php');
	          global  $ScheduleDatas;
		      global $colorCodes;
			  global $memberId;
			  DrawHelp("",$x,$y,$w,$h,"schedule.php","");
			  $title=$ScheduleDatas[$ScheduleSn][0]."-".$ScheduleDatas[$ScheduleSn][6];
			  $Detail=$ScheduleDatas[$ScheduleSn][7] ;
			  echo   "<form id='SCup'  name='SCup' action='scheduleUp.php' method='post'>";
		      
		     // DrawFormTextarea(($x ),($y+20),"300","200", "Detail",$Detail,$colorCodes[5][2]);
			  DrawRect( $title,"12","#ffffff",($x),($y),"200","20",$colorCodes[2][1]);
 
			  DrawSubmitRect("送出修改","12",$colorCodes[1][1],($x+200),$y,"100","20",$colorCodes[0][1],"SCup","");
		  
			  DrawInputFileRect($x,($y+240),"300","200","drop_image",$colorCodes[5][2]);
			  echo "<input type=hidden name=UserName value=".$memberId[$ID].">"; 
			  echo   "<input type='text' name='file' value='0' size='22'>file";
			  echo   "</form>";
	 
 
	 
	 }
?>