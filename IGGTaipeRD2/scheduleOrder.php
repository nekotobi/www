 
<?php
  function EditPlan($epy,$epm,$epd,$epLine,$epDay,$eptype){
     global $ProjectDataName;
	 global $colorCodes;
	 global  $BackURL,$tablename;
	 global  $SelectScheduleType, $SelectType,$SelectScheduleType2;
	  // include('PubApi.php');
  	 $title="編輯".$epy."年".$epm."月".$epd."日"."計畫";
	 $ex=400;
	 $ey=300;
	 $w=400;
	 $h=200;
	//c="scheduleAll.php";
	 $DataBase=getMysqlDataArray($tablename);
 
     $getplan= getMysqlSortData($DataBase,2,$epd,5,$epLine,4);
	 $eptype= getMysqlSortData($DataBase,2,$epd,5,$epLine,7);
      //echo ">".$getplan;
     DrawPopBG($ex,$ey-60,$w,$h,$title ,"12",$BackURL);
	// echo $SelectScheduleType[$SelectType];
     echo   "<form id='AddPlan'  name='Show' action='scheduleUp.php' method='post'>";
     echo   "<input type=hidden name=ProjectDataName value=".$ProjectDataName.">"; 
	 echo   "<input type=hidden name=Upy value=".$epy.">"; 
     echo   "<input type=hidden name=Upm value=".$epm.">"; 
     echo   "<input type=hidden name=Upd value=".$epd.">"; 
	 echo   "<input type=hidden name=UpLine value=".$epLine.">"; 
     echo   "<input type=hidden name=selecttype value=".$SelectScheduleType[$SelectType].">";
	 echo   "<input type=hidden name=selectnum value=".$SelectType.">"; 
	 $workyinput="<input type=text name=epy  value='".$epy."'  size=4   >";
	 DrawInputRect("年","12","#ffffff",($ex ),$ey-20,120,18, $colorCodes[4][2],"top", $workyinput);
	 
	 $workminput="<input type=text name=epm  value='".$epm."'  size=4   >";
	 DrawInputRect("月","12","#ffffff",($ex +60),$ey-20,120,18, $colorCodes[4][2],"top", $workminput);
	 
	 $workdinput="<input type=text name=epd  value='".$epd."'  size=4   >";
	 DrawInputRect("日","12","#ffffff",($ex +120),$ey-20,120,18, $colorCodes[4][2],"top", $workdinput);
	 
     $Planinput="<input type=text name=getplan value='".$getplan."'  size=34  >";
	 DrawInputRect("計畫","12","#ffffff",($ex),$ey,220,18, $colorCodes[4][2],"top",$Planinput);
	 
     $workDayinput="<input type=text name=epDay  value='".$epDay."'  size=2   >";
	 DrawInputRect("天數","12","#ffffff",($ex+220),$ey,120,18, $colorCodes[4][2],"top",$workDayinput);
	 
	 $Typeinput="<input type=text name=eptype  value='".$eptype."'  size=6   >";
	 DrawInputRect("類型","12","#ffffff",($ex+280),$ey,120,18, $colorCodes[4][2],"top", $Typeinput);
	 
	 $Lineinput="<input type=text name=epLine value='".$epLine."'  size=2   >";
	 DrawInputRect("行數","12","#ffffff",($ex+280),$ey+20,120,18, $colorCodes[4][2],"top", $Lineinput);
	 
	 $submitP="<input type=submit name=submit value=刪除計畫>";
	 DrawInputRect("","12","#ffffff",($ex+350),$ey+20,120,18, $colorCodes[4][2],"top",$submitP);
	 
	 $submitP="<input type=submit name=submit value=修改計畫>";
	 DrawInputRect("","12","#ffffff",($ex+350),$ey,120,18, $colorCodes[4][2],"top",$submitP);
     //腳色:
	 if(count($SelectScheduleType2[ $SelectType])==1)return;
	 $processData = getMysqlSortData($DataBase,2,$epd,5,$epLine,8); 
	 $processarr=explode("_",$processData); //explode($clipStr ,$string)
	 echo  "<input type=hidden name=totallT value=".count($SelectScheduleType2[ $SelectType]).">"; 
	 $stateCheck= getMysqlSortData($DataBase,2,$epd,5,$epLine,9); 
	 $isCheck="";

	 for($i=0;$i<count($SelectScheduleType2[ $SelectType]);$i++){
	     $sinput="<input type=text name=sinput".$i."  value='". $processarr[$i]."'  size=2   >";
		 $msg=$SelectScheduleType2[ $SelectType][$i]." " ;
	     DrawInputRect( $msg,"12","#ffffff",($ex+40),$ey+60+$i*20,120,18, $colorCodes[4][2],"top",$sinput);
		 //確認:
 
		 if( $stateCheck==$msg) $isCheck="checked=true";
	     $inputp="<input type=radio name=state value=".$msg." ".$isCheck.">";
	     DrawInputRect( ">>","12","#ffffff",($ex+220),$ey+60+$i*20,120,18, $colorCodes[4][2],"top",  $inputp);
	 }
	  $isCheck="";
	  if( $stateCheck==$msg) $isCheck="checked=true";
      $inputp="<input type=radio name=state value=完成 ".$isCheck.">";
	  DrawInputRect( "完成","12","#ffffff",($ex+120),$ey+60+count($SelectScheduleType2[ $SelectType])*20,120,18, $colorCodes[4][2],"top",  $inputp);
     
  
  }
  
  
  function AddPlanEditor($ex,$ey,$w,$h,$y,$m,$d){
	 global $ProjectDataName;
	 global $colorCodes;
	 global  $SelectScheduleType,$SelectType;
	 if($SelectType=="")$SelectType=0;
  	 $title="新增".$y."年".$m."月".$d."日" .$SelectScheduleType[$SelectType]."計畫";
	 $BackURL="scheduleAll.php";
     DrawPopBG($ex,"40",$w,$h,$title ,"12",$BackURL);
     echo   "<form id='AddPlan'  name='Show' action='scheduleUp.php' method='post'>";
     echo   "<input type=hidden name=ProjectDataName value=".$ProjectDataName.">"; 
	 echo   "<input type=hidden name=Upy value=".$y.">"; 
     echo   "<input type=hidden name=Upm value=".$m.">"; 
     echo   "<input type=hidden name=Upd value=".$d.">"; 
	 echo   "<input type=hidden name=selecttype value=".$SelectScheduleType[$SelectType].">"; 
	 echo   "<input type=hidden name=selectnum value=".$SelectType.">"; 
	 echo   "<input type=hidden name=process value=5_8_10_6>"; 
     $Planinput="<input type=text name=Plan value='".$Plan."'  size=34  >";
	 DrawInputRect("計畫","12","#ffffff",($ex),60,220,18, $colorCodes[4][2],"top",$Planinput);
     $WorkDay=5;
     $workDayinput="<input type=text name=WorkDay  value='".$WorkDay."'  size=2   >";
	 DrawInputRect("天數","12","#ffffff",($ex+220),80,120,18, $colorCodes[4][2],"top",$workDayinput);

	 $types=array("工項","目標","Sprint");
	 $select=MakeSelectionV2($types,"工項","type",160);
	 DrawInputRect("類型","10","#ffffff",($ex+220),60,120,18, $colorCodes[4][2],"top",  $select);
	 $Line=2;
	 $Lineinput="<input type=text name=Line value='".$Line."'  size=2   >";
	 DrawInputRect("行數","12","#ffffff",($ex+280),80,120,18, $colorCodes[4][2],"top", $Lineinput);
	 
	 $submitP="<input type=submit name=submit value=新增計畫>";
	 DrawInputRect("","12","#ffffff",($ex+350),60,120,18, $colorCodes[4][2],"top",$submitP);
 
  }


  function inputOrder ($x,$y,$w,$h,$Order,$OrderID,$UpSn,$Etype ){
	 global $ScheduleDatas;
	 global $colorCodes;
	 global $memberId,$id,$rank,$OrderID;
	 global $ProjectDataName;
			$memberId=getmemberID( );
 
	 if ($rank=="")return;
	 $title="新增工單";
	 $submitName="新增";
     if($Order!="new"){
		  		 //0GDSn 1GDVer 2ProposeDate 3FinshDate 4progress 5file 6name 7info 8reference 
		    //9Remarks 	10sn 11type  12ArtStartDay 	13WorkDay 	14ArtFinDay 	15ArtVer 	16Artprincipal 17project 18out
		 $OrderN= returnSsn($UpSn,6);
		 $title= "工單內容[".$OrderN."]";
		 $submitName="修改";
		 $name=$ScheduleDatas[$Order][6];
		 $info=$ScheduleDatas[$Order][7];
		 $file=$ScheduleDatas[$Order][5];
		 $project=$ScheduleDatas[$Order][17];
		 $FinshDate=$ScheduleDatas[$Order][3];
		 $ArtFinDay=$ScheduleDatas[$Order][14];
		 $outsourcing=$ScheduleDatas[$Order][18];
		 $ArtVer=$ScheduleDatas[$Order][15];
		 $WorkDay=$ScheduleDatas[$Order][13];
	 }
     if($Order=="new")$OrderID= $id;

	 DrawPopBG($x,$y,$w,$h,$title.$s,"12");
	 $ProjectsTmp=getMysqlDataArray("projectdata");
	 $Projects=filterArray($ProjectsTmp,3,"");
	 $workTypes=getMysqlDataArray("WorkType");


	 if($Etype!="Edit" &&  $submitName!="新增"){
         //DrawRect("所屬專案","12","#ffffff",$x+200,$y+40,"100","20","#000000");
	//	 DrawRect( $project,"12","#000000",$x+300,$y+40,"100","20",$colorCodes[4][2]); 
		 DrawRect("工單名稱","12","#ffffff",$x,$y+40,"100","20","#000000");
		 $editLink="schedule.php?Order=".$Order."&OrderID=".$OrderID."&UpSn=".$UpSn."&Etype=Edit"; 
		 DrawLinkPic("pics/Edit.png",$y+40,$x,"18","18", $editLink);
	     DrawRect($name,"12","#000000",$x+100,$y+40,"100","20",$colorCodes[4][2]);
		 DrawRect("工單內容","12","#ffffff",$x,$y+70,"400","20","#000000");
		 DrawRect($info,"12","#000000",$x,$y+90,"400","200",$colorCodes[4][2]);
		 DrawText("工單提出者: ".$memberId[$OrderID],$x,$y+360,120,20,12,"#12d321");
		 DrawLinkRect("工單補充連結","12","#ffffff",$x+300,$y+71,"96","16",$colorCodes[1][2],$file,"");
		 if($FinshDate!="")
		 DrawLinkRect("完成連結","12","#ffffff",$x+260,$y+312,"96","18",$colorCodes[6][0],$FinshDate,"");
	     if($outsourcing!=""){
	        DrawRect("外包對象","12","#ffffff",$x,$y+312,"100","20","#000000");
		    DrawRect($outsourcing,"12","#000000",$x+100,$y+312,"100","20",$colorCodes[4][2]);
		 }
	     if($ArtFinDay && $rank<4 ){
			 
			      echo   "<form id='UpOrder'  name='Show' action='scheduleUp.php' method='post'>";
				  echo "<input type=hidden name=ProjectDataName value=".$ProjectDataName.">"; 
			  	  echo "<input type=hidden name=UpSn value=".$UpSn.">";//哪個sn
				  echo "<input type=hidden name=OrderID value=".$OrderID.">";//哪個sn  
				  if($ArtVer!="Fin" && $rank<3 ){
				      $submit3="<input type=submit name=submit value=結單>";
					  DrawInputRect("","12","#ffffff",$x+350,$y+330,120,18, $colorCodes[4][2],"top", $submit3);
				  }
				      $submit3="<input type=submit name=submit value=撤回>";
					  DrawInputRect("","12","#ffffff",$x+350,$y+310,120,18, $colorCodes[4][2],"top", $submit3);
		          echo   "</form>";
		 }
		// DrawInputRect("補充連結","12","#ffffff",$x,$y+300,"200","20",$colorCodes[4][2],"top", $inputLink);
	  	// DrawInputRect("工單名稱","12","#ffffff",$x,$y+20,"200","20",$colorCodes[4][2],"top",$inputOrderTitle);
	 
	 return;
	 }
	 
     echo   "<form id='UpOrder'  name='Show' action='scheduleUp.php' method='post'>";

	 $inputOrderTitle="<input type=text name=name  value='".$name."'  size=22   >";
	 DrawInputRect("工單名稱","12","#ffffff",$x,$y+20,"120","20",$colorCodes[4][2],"top",$inputOrderTitle);
	 
	//  $seletProject=MakeSelection($Projects,0,$CProject[0][0],"'project'");
	$seletProject=MakeSelection($Projects,0,"RPG","'project'");
	 DrawInputRect( "專案","12","#ffffff" ,$x+160,$y+20,"100","20", $colorCodes[4][2],"top", $seletProject);
	 
	 $workDayinput="<input type=text name=WorkDay  value='".$WorkDay."'  size=2   >";
	 DrawInputRect( "工作天","12","#ffffff" ,$x+340,$y+20,"40","20", $colorCodes[4][2],"top",  $workDayinput);
	 
	  
	 $WorkTypeSelect=MakeSelection( $workTypes,0, $project,"'type'");
	 DrawInputRect( "類別","12","#ffffff" ,$x+270,$y+20,"40","20", $colorCodes[4][2],"top", $WorkTypeSelect);
	  
	 $OrderDetail="<textarea name='info'  rows='10' cols='44'>".$info."</textarea>";
	 DrawInputRect("工單敘述","12","#ffffff",$x,$y+60,"200","100",$colorCodes[4][2],"top",$OrderDetail);
	 
	 $inputLink="<input type=text name=file  value='".$file."'  size=60   >";
	 DrawInputRect("補充連結","12","#ffffff",$x,$y+300,"200","20",$colorCodes[4][2],"top", $inputLink);
	 
	
	 
	 echo "<input type=hidden name=ProjectDataName value=".$ProjectDataName.">";
 
     DrawText("工單提出者: ".$OrderID,$x,$y+420,120,20,12,"#12d321");
	  
	 $submit="<input type=submit name=submit value=".$submitName." >";
	 DrawInputRect("","12","#ffffff",$x+340,$y+420,120,20, $colorCodes[4][2],"top", $submit);
	 	  echo "<input type=hidden name=UpSn value=".$UpSn.">";//哪個sn
		  echo "<input type=hidden name=scNum value=".$Order.">";//哪個表單
		  echo "<input type=hidden name=OrderID value=".$OrderID.">";//哪個sn
     if($Order!="new"){
		  
     $outLink="<input type=text name=outsourcing  value='".$outsourcing."'  size=60   >";
	 DrawInputRect("外包對象","12","#ffffff",$x,$y+380,"200","20",$colorCodes[4][2],"top",  $outLink);
	
	
	     // $submit2="<input type=submit name=submit value=刪除工單 >";
 	   //   DrawInputRect("","12","#ffffff",$x+120,$y+300,120,20, $colorCodes[4][2],"top", $submit2);
	  if($rank<4){
		  $FinLink="<input type=text name=FinshDate  value='".$FinshDate."'  size=60   >";
	      DrawInputRect("完成連結","12","#ffffff",$x,$y+340,"200","20",$colorCodes[4][2],"top",  $FinLink);
		  
		  if($ArtFinDay==""){ 
	      $submit3="<input type=submit name=submit value=工單完成>";
	      DrawInputRect("","12","#ffffff",$x+220,$y+420,120,20, $colorCodes[4][2],"top", $submit3);
		  }
		 // if($ArtFinDay!="" && $rank<3){ 
	     // $submit3="<input type=submit name=submit value=結單>";
	     // DrawInputRect("","12","#ffffff",$x+220,$y+380,120,20, $colorCodes[4][2],"top", $submit3);
		 // }
	  }
	  }
	 
	 echo   "</form>";
 }
  function returnSsn($num,$length){
	$add="P";
	//echo "</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>";
	 $n= strlen($num);
	 $s=$length-$n; 
	for( $i=0;$i<$s;$i++){
		$add=$add."0";
	}
	$add=$add.$num;
	return $add;
}

?>