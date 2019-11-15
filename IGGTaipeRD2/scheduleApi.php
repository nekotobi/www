<?php //基礎日曆

	 function DrawBaseCalendar_v2(){  //日曆格
		      global $StartX, $StartY,$OneDayWidth,$daysLoc,$monthLoc, $YearLoc ,$LineHeight ; 
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
			  DrawDragArea($LineHeight);
	 }
     function DrawSprint( $sy){  //sprint
		    global $colorCodes,$daysLoc,$OneDayWidth;
	        $SprintData= getMysqlDataArray("sprintdata");
			for($i=1;$i<count($SprintData);$i++){
				$dd="";
			    if($SprintData[$i][2]<10)$dd="0" ;
			    $d=$SprintData[$i][0]."/".$SprintData[$i][1]."/".$dd.$SprintData[$i][2];
			    $x=RetrunXpos($daysLoc,$d);		
				$info="Sprint".$SprintData[$i][5];
				$color="#cccccc";
				$nowMilestone=3;
				if( $SprintData[$i][6]==$nowMilestone)	$color="#123451";
				$w= $SprintData[$i][4]*$OneDayWidth;
			   // DrawabsoluteRect($info,"10","#ffffff", $x-8,  $sy ,$w ,"20", $color,  "absolute" ,"");
				$Link="sprintShow.php?ey=".$SprintData[$i][0]."&em=".$SprintData[$i][1].
				"&ed=".$SprintData[$i][2]."&edays=".$SprintData[$i][4]."&enum=".$SprintData[$i][5]."&emil=".$SprintData[$i][6];
				DrawLinkRect($info,"10","#ffffff",$x-8,  $sy ,$w ,"20", $color,$Link,"");
			}
  	 }
	 function DrawDragArea($height ){//拖曳灰區
		       global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX  ; 
			   global $DeadLine;
			   $PassColor="#999999";
	           for($i=0;$i<count($daysLoc);$i++){
				   $d=$daysLoc[$i][2];
				   if($d<10)$d="0".$d;
				   $id="Area-".$daysLoc[$i][0]."/".$daysLoc[$i][1]."/".$d."-".$daysLoc[$i][3];
				   $x=$daysLoc[$i][3];
				   $h=($height+1)*20 ;
                   $BGColor= $PassColor;
				   if($daysLoc[$i][1]==  $DeadLine[0] && $daysLoc[$i][2]== $DeadLine[1] ){
				    $BGColor="#a27e7e";
				
				   }
				   if($daysLoc[$i][1]==date("m") && $daysLoc[$i][2]==date("d") ){
				    $BGColor="#C99899";
						$PassColor="#aaaaaa";
				   }
				   	$Rect=array($x, $StartY+80,($OneDayWidth-2),$h);
				    DrawRect_Layer("",$fontSize,$fontColor,$Rect,$BGColor,-100); 
			         //DrawDragRect($x, $StartY+80,($OneDayWidth-2),$h,$BGColor,$id);
			  }  
	 
	 }
?>
<?php //V2 Use
      function GetCalendarData(){
	          global $StartX, $StartY,$OneDayWidth,$daysLoc,$monthLoc, $YearLoc; 
	          global $TargetYear,$TargetMonth,$YearRange,$MonthRange,$showMonthNum;
			  global $colorCodes;
			  global $VacationDays; 
	          SetCalendarRange("","");
			  $MonthTotalWidth=0;
			  for($i=0;$i<count($MonthRange);$i++){
			       getDaysLoc( $YearRange[$i], $MonthRange[$i]);
			  }
			  $daysLoc= getDayLocVacationDays($daysLoc);
			  $VacationDays= getVacationDays($YearRange,$MonthRange);
	  }
	  function getDaysLoc($y,$m){ //取得日期資料$daysLo[]=$daydata=array($y年,$m月,$i日,$CurrentX位置,$假期0 否 1假期)
	            global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX,$monthLoc ;
				$MonthEnd=getMonthDay($m,$y);
				$sx=$CurrentX+$OneDayWidth;
				for($i=1;$i<=$MonthEnd;$i++){
				    $CurrentX+=$OneDayWidth;
					$daydata=array($y,$m,$i,$CurrentX,0);
				    array_push(   $daysLoc,$daydata);
				}
		     	$monthData=array($y,$m,$sx,$CurrentX-$sx+$OneDayWidth);
			    array_push( $monthLoc,$monthData);
	 }
 
?>

<?php //繪製
	 function DrawState(){
	          global $SelectScheduleType, $SelectType,$State,$SelectScheduleType2;
		      global $colorCodes;
		      global $LinkURL;
	          for ($i=0;$i<count($SelectScheduleType2[$SelectType]);$i++){
		 		   $x=220+ $i*60+count( $SelectScheduleType)*100;
				   $y=80;
				   $color= $colorCodes[9][$i+1];
			       DrawLinkRect($SelectScheduleType2[$SelectType][$i],"10","#000000",$x,$y,"50","18",$color,$Link,1);
			  }
	 }
?>

<?php //日期/轉座標資料
	 function isvday($vacDays,$y,$m,$d){
	      $isv="1";
		  for($i=0;$i<count($vacDays);$i++){
		    $vy=$vacDays[$i][0];
			$vm=$vacDays[$i][1];
			$vd=$vacDays[$i][2];
			if ($vy==$y and  $vm==$m  and $vd==$d){
			   $isv=$vacDays[$i][4];
			}
		  }
		  return $isv;
	 
	 }
	 function getDayLocVacationDays($daysLoc){ //填入假期資料
	         global $data_library;
		   // echo ">>>".count($daysLoc);
		    $y=$daysLoc[0][0];
		    $m=$daysLoc[0][1];
		    $weekStart=GetMonthFirstDay($y,$m);
	        $vacDays= getMysqlArray($data_library,"vacationdays");
			for($i=0;$i<count($daysLoc);$i++){
				$vday=0;
				if ($weekStart==6 or $weekStart==7)$vday=1;
				$daysLoc[$i][4]=$vday;
				$weekStart+=1;
				if ($weekStart>7)$weekStart=1;
				$y=$daysLoc[$i][0];
				$m=$daysLoc[$i][1];
				$d=$daysLoc[$i][2];
				$isv=isvday($vacDays,$y,$m,$d);
				if($isv=="")	$vday=1;
				if($isv=="-1")	$vday=0;
				$daysLoc[$i][4]=$vday;
				 // echo "<br>";
			    //echo $daysLoc[$i][1].">".$daysLoc[$i][2].">".$isv.">".$y.">".$m.">".$d;
			}
			return $daysLoc;
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
     function returnDateString($y,$m,$d){
	          $dd="";
			  if($d<10)$dd="0" ;
              $d=$y."/".$m."/".$dd.$d;
			  return $d;
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
?>

<?php //圖檔放置查找
     function getResfilePath($gdnamet,$typename ){
 
		  $gdname=trim($gdnamet);
		  $Gd=substr($gdname, 0, 5);
	      $respath=returnResDirbyGDname($gdname);
	      $typeDir=returntypeDir($typename);
		  $paths=array();
 
		 
		  for($i=0;$i<count($typeDir);$i++){
			  $ex="png";
			  if($typeDir[$i]=="psd")$ex="psd";
			    if($typeDir[$i]=="model")$ex="rar";
				   if($typeDir[$i]=="animation")$ex="rar";
		  	      $path="ResourceData/".$respath."/". $typeDir[$i]."/".$Gd.".".$ex;
				//  echo $path;
			   array_push($paths,$path);
		  }
	      return $paths;
	 }
     function returnResDirbyGDname($gdname){
			  $type=substr($gdname, 0, 1);
			  $typepath="";
 
	          switch ($type){
			         case "h":
					  $typepath="hero";
					 break;
					  case "b":
					 $typepath="boss";
					 break;
			         case "m":
					 $typepath="mob";
					 break;
					 case "s":
					 $typepath="summon";
					 break;
			   }
			   return  $typepath;
	 }
	 function returntypeDir($typename){
	          switch ($typename){
			          case "設定":
					  return array("psd","pic","spic");
					  break;
					  case "建模":
					  return array("model");
					  break;
					  case "動作":
					  return array("animation");
					  break;
					  case "立繪":
					  return array("Promotionalpsd","Promotionalpic","Promotionalspic");
					  break; 
			  }				  
	          return null;
	  }
	 
?>

<?php //上傳檔案
	 function  getTypes(){
		  global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2,$stateType,$EditHide;
		 		$sTypeTmp= getMysqlDataArray("scheduletype");	
				$SelectType_1tmp= filterArray($sTypeTmp ,0,"data");
				$SelectType_1sort=sortArrays($SelectType_1tmp ,5 ,"true");
			    $SelectType_1=   returnArraybySort($SelectType_1sort,2);
				//
			    $SelectType_2tmp= filterArray($sTypeTmp ,0,"data2");
				$SelectType_2=   returnArraybySort($SelectType_2tmp,2);
				$stateTypetmp= filterArray($sTypeTmp ,0,"data3");
				$stateType=   returnArraybySort($stateTypetmp,2);
				if($Stype_1=="")$Stype_1=0;
	 }

     function EditPlan_v2($ex,$ey,$w,$h){
	        global $data_library,$tableName, $milestoneSelect;    
			global $Ecode;
		    global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1;
						
			$planstmp=getMysqlDataArray($tableName);
			$plansArray=returnDataArray($planstmp,1,$Ecode);
		    echo   "<form id='EditPlan'  name='Show' action='".$BackURL."' method='post'>";
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
			 if($Stype_1==0 or  $Stype_1==""){
		        $workDayinput="<input type=text name=workingDays  value='5'  size=2   >";
	            DrawInputRect("天數","12","#ffffff",($ex+240),$ey+40,120,18, $colorCodes[4][2],"top",$workDayinput);
		     }else{
		    	$jirainput="<input type=text name=remark  value='".$plansArray[12]."'  size=4   >";
	            DrawInputRect("jila單","12","#ffffff",($ex+240),$ey+40,120,18, $colorCodes[4][2],"top",$jirainput);
		     }
 
		 
		    $Lineinput="<input type=text name=line value='".$plansArray[4]."'  size=2   >";
     	    DrawInputRect("行數","12","#ffffff",($ex+240),$ey+70,120,18, $colorCodes[4][2],"top", $Lineinput);
	        
		    $types=array("工項","目標","Sprint");
	        $select=MakeSelectionV2($types,$plansArray[5],"type",14);
	        DrawInputRect("類型","10","#ffffff",($ex ),$ey+70,120,18, $colorCodes[4][2],"top",  $select);
			
 	 	    //milestone
	   	    $select2=MakeSelectionV2( $milestoneSelect,$plansArray[15],"milestone",14);
	        DrawInputRect("Milestone","10","#ffffff",($ex ),$ey+110,120,18, $colorCodes[4][2],"top",  $select2);
			
		    $submitP="<input type=submit name=submit value=修改計畫>";
	        DrawInputRect("",$ey-60 ,"#ffffff",($ex+320),60,120,18, $colorCodes[4][2],"top",$submitP);

             
			//刪除
	        $input="<input type=text name=del value=''  size=3>";
	        DrawInputRect("輸入刪除碼","12","#ffffff",($ex+222),$ey+130 ,220,16, $colorCodes[4][2],"top",$input);	
			
		    $submitP="<input type=submit name=submit value=刪除計畫>";
	        DrawInputRect("",$ey+60 ,"#ffffff",($ex+320),60,120,18, $colorCodes[4][2],"top",$submitP);
	 }
     function EditPlanTypeEditor_v2($ex,$ey,$w,$h){
            global $data_library,$tableName;   
	        global $colorCodes;
		    global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$stateType;
			global $Ecode;
            //echo  ">>>>>>>>>>>>".$Ecode;
	     //   echo $Ecode.">".$tableName.">";
			
			$planstmp=getMysqlDataArray($tableName);
	   //	 echo  count($planstmp).">";
			$plansArray=returnDataArray($planstmp,1,$Ecode);
		//	echo  count($plansArray );
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
 
			echo   "<input type=hidden name=code value=".$Ecode.">";
		   	echo   "<input type=hidden name=PhpInputType value=upEditPlanType >";	
			$lastUpdate=date(Y_m_d_H_i,time()+(8*3600));
		    echo   "<input type=hidden name=lastUpdate value=".$lastUpdate.">"; 
			$ey+=20;
			 //年
	         $input="<input id=year type=text name=year value='".$startDay[0]."'  style=font-size:10px; size=4>年";
	         DrawInputRect("開始","12","#ffffff",($ex),$ey ,120,16, $colorCodes[4][2],"top",$input);
	         //月
	         $input="<input id=month type=text name=month value='".$startDay[1]."' style=font-size:10px;  size=2>月";
	         DrawInputRect("","12","#ffffff",($ex+80),$ey ,60,16, $colorCodes[4][2],"top",$input);
		     //日
	         $input="<input id=day type=text name=day value='".$startDay[2]."' style=font-size:10px; size=2>日";
	         DrawInputRect("","14","#ffffff",($ex+130),$ey ,60,16, $colorCodes[4][2],"top",$input);
			 //天數
			 $workDayinput="<input id=workingDays type=text name=workingDays  value='".$plansArray[6]."' style=font-size:10px; size=2   >";
	         DrawInputRect("天數","12","#ffffff",($ex+190),$ey ,120,18, $colorCodes[4][2],"top",$workDayinput);
	        //JilaLink
		     $jirainput="<input type=text name=remark  value='".$plansArray[12]."' style=font-size:10px; size=4   >";
	         DrawInputRect("副jila單","12","#ffffff",($ex+280),$ey ,120,18, $colorCodes[4][2],"top",$jirainput);			 
			 $ey+=40;
			 //外包負責
			 $OutsDatatmp=getMysqlDataArray("outsourcing");
	         $OutsDatatmp2=filterArray($OutsDatatmp,0,"data");
	         $OutsData=returnArraybySort( $OutsDatatmp2,2);
			 $selectTable= MakeSelectionV2($OutsData,$plansArray[9] ,"outsourcing",10);
		     DrawInputRect( "選擇負責外包","10","#ffffff",($ex+250),$ey ,220,16, $colorCodes[4][2],"top", $selectTable);
			 //負責人
			 $principaltmp=getMysqlDataArray("members");
			 $principalData=returnArraybySort( $principaltmp,1);
			 $selectTable= MakeSelectionV2( $principalData,$plansArray[8],"principal" ,10);
			 DrawInputRect( "選擇內部負責","10","#ffffff",($ex+250),$ey+30 ,220,16, $colorCodes[4][2],"top", $selectTable);
	         	  global $stateType;
			  if(count($stateType)==0)gettypes();
        	//狀態
			 $selectTable= MakeSelectionV2( $stateType,$plansArray[7],"state" ,10);
			 DrawInputRect( "目前狀態","10","#ffffff",($ex+250),$ey+60 ,220,16, $colorCodes[4][2],"top", $selectTable);
		
			 //送出
		     $submitP="<input type=submit name=submit value=送出修改 style=font-size:10px; >";
	         DrawInputRect("",$ey-120 ,"#ffffff",($ex+320),60,120,18, $colorCodes[4][2],"top",$submitP);
			 //圖檔
			 $ey+=150;
			 $input="<input type=file name=file 	id=file   style=font-size:10px;  size=60   >";
		     DrawInputRect("上傳完成檔案","12","#ffffff", ($ex ),$ey ,320,16, $colorCodes[4][2],"top", $input);
			 $ey+=30;
			 $fininput="<input type=text name=finLink  value='".$plansArray[14]."'  size=50   >";
	         DrawInputRect("完成連結","12","#ffffff",($ex ),$ey  ,420,18, $colorCodes[4][2],"top",$fininput);
			 
			 //備註
			 $ey+=30;
			 $fininput2="<input type=text name=remark2   value='".$plansArray[16]."'  size=50   >";
	         DrawInputRect("備註","12","#ffffff",($ex ),$ey  ,420,18, $colorCodes[4][2],"top",$fininput2);
			 
			 //費用
			 $ey+=30;
			 $ininput="<input type=text name=Price   value='".$plansArray[17]."'  size=10   >";
	         DrawInputRect("費用(美金)","12","#ffffff",($ex ),$ey  ,220,18, $colorCodes[4][2],"top",$ininput);
			 
			 //刪除
	         $input="<input type=text name=del value=''  style=font-size:10px; size=3>";
	         DrawInputRect("輸入刪除碼","12","#ffffff",($ex+200),$ey ,220,16, $colorCodes[4][2],"top",$input);	
		     $submitP="<input type=submit name=submit value=刪除計畫>";
	         DrawInputRect("",$ey-40  ,"#ffffff",($ex+320),$ey-635,120,18, $colorCodes[4][2],"top",$submitP);
			  //載入小日曆
		     include('CalendarPlugin.php');
		  	 DrawSCalender(420,300,"Edit");
   }
	 function  UpFiles_Res($Etype,$Ecode,$file){
			   $gdname=trim($Ecode);
			   $typepath=returnResDirbyGDname($gdname);
			
		       if($typepath=="")return;
			   $Gd=substr($gdname, 0, 5);
			   $temp = explode(".", $_FILES["file"]["name"]);
			   if($temp[1]=="")return;
			   $dirs=returntypeDir($Etype);
			   echo $dirs[0];
			   if($dirs=="")return;
			   for($i=0;$i<count($dirs);$i++){
				   $ex=$temp[1];
				   if($i>0)$ex="png";
				   $path[$i]="ResourceData/". $typepath."/".$dirs[$i]."/".$Gd.".".$ex;
				  // echo $path[$i];
				   if($i==0){
				     move_uploaded_file($_FILES["file"]["tmp_name"], $path[0]);  
				   }
				   if($i==1){
				     $cmd="convert      $path[0]    -flatten   $path[1] ";
					  exec($cmd);
				   }
				   if($i==2){
				     $cmd="convert      $path[1]    -flatten -resize 128  $path[2] ";
					  exec($cmd);
				   }
			   }	 
	 }
	 function  UpFiles($datas,$gdnamet){
			   $gdname=trim($gdnamet);
			   $typepath=returnResDirbyGDname($gdname);
		       if($typepath=="")return;
			   $Gd=substr($gdname, 0, 5);
			   $temp = explode(".", $_FILES["file"]["name"]);
			   if($temp[1]=="")return;
			   $dirs=returntypeDir($datas[5]);
			   if($dirs=="")return;
			   for($i=0;$i<count($dirs);$i++){
				   $ex=$temp[1];
				   if($i>0)$ex="png";
				   $path[$i]="ResourceData/". $typepath."/".$dirs[$i]."/".$Gd.".".$ex;
				   if($i==0){
				     move_uploaded_file($_FILES["file"]["tmp_name"], $path[0]);  
				   }
				   if($i==1){
				     $cmd="convert      $path[0]    -flatten   $path[1] ";
					   exec($cmd);
				   }
				   if($i==2){
				     $cmd="convert      $path[1]    -flatten -resize 128  $path[2] ";
					   exec($cmd);
				   }
			   }	 
	 }
	  function UpEditData( ){
		
		       global $data_library,$tableName,$MainPlanData;
			   global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1;
			   global $year,$month,$day;
			   global $submit;
			     echo $submit;
			   global $del;
			   $p=$tableName;
			   $tables=returnTables($data_library,$p);
			   $plansArray=returnDataArray($MainPlanData,1,$Ecode);
	           $t= count( $tables);
			   $Base=array();
			   $up=array();
			   global $state;
			   if($state=="廢棄"){
				  echo "xxxxxxxx";
				  global $type;
				  $type=$type."_廢棄";
			   }
		       for($i=0;$i<$t;$i++){
	       	       global $$tables[$i];
				   		  $startDay=$year."_".$month."_".$day;
				          array_push($Base,$tables[$i]);
                          array_push($up,$$tables[$i]);
		       }
			   //變更屬性
	 
			   $WHEREtable=array( "data_type", "code" );
		       $WHEREData=array( "data",$code );
			   if($submit=="修改計畫"){
			    $stmt= MakeUpdateStmt(  $data_library,$tableName,$Base,$up,$WHEREtable,$WHEREData);
                SendCommand($stmt,$data_library);		
 	            echo $stmt;
			   }
		       if($submit=="送出修改"){
				   $stmt= MakeUpdateStmt(  $data_library,$tableName,$Base,$up,$WHEREtable,$WHEREData);
                   SendCommand($stmt,$data_library);			
				   //上傳檔案
				   $plansArray=returnDataArray($MainPlanData,1,$up[3]);
				   UpFiles($up,$plansArray[3]);
                   echo $stmt;
			      
			   }   
			   if($submit=="刪除計畫"){
			      if($del!="") $stmt= MakeDeleteStmt($tableName,$WHEREtable,$WHEREData); 
				     SendCommand($stmt,$data_library);
			   }
	        echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";
	 }
?>


<?php  //
     function SortbyDate($BaseData){
		 global $daysLoc;
		 $sortLocs=array();
		 for($i=0;$i<count($BaseData);$i++){
            $startDay=explode("_",$BaseData[$i][2]);
		    $d=returnDateString($startDay[0],$startDay[1],$startDay[2]);
			 $x=RetrunXpos($daysLoc,$d);
			 $BaseData[$i]['DayLoc']=$x;
		     array_push($sortLocs,$x); 
		 
		 }
	     $score = array();
         foreach ($BaseData as $user) {
                  $score[] = $user['DayLoc'];
                  }
		 array_multisort($score, SORT_ASC, $BaseData);
		 return $BaseData;
	 }
     function SortbyUser($BaseData,$users){
		  $sortUsers=array();
		  for($i=0;$i<count($users);$i++){
			//  echo $i."=". $users[$i][user];
			  $u=trim($users[$i][user]);
		       for($j=0;$j<count($BaseData);$j++){
				     $ch=0;
				   if($BaseData[$j][8]== $u) $ch=1;
				   if($BaseData[$j][9]== $u) $ch=1;
				    if($ch==1)array_push( $sortUsers,$BaseData[$j]);
			   }
		  }
		  return $sortUsers;
	 }
	  
	 function collectUser($planeDatas){
		  $users=array();
          global $colorCodes;
		  $c=0;
	      for($i=0;$i<count($planeDatas);$i++){
			  $out=trim($planeDatas[$i][9]);
			  if(in_array($out, $users)) $out="";
			  if($out!="")array_push($users,$out); 
		  }
		  $usersf=array();
		   for($i=0;$i<count($users);$i++){
		          $ar=array("user"=> $users[$i],"Color"=>(getRandoColor(65,70)));
				    array_push($usersf,$ar); 
				  
		   }
			   
		  
	      return $usersf;
	 }
     function getUserColors($planeData,$users){
		 global $List;
		   if($List=="ArtWork")return "#ffccaa";
		 if($planeData[9]=="" or $planeData[9]=="未定義")	  return "#ff5555";
		      for($i=0;$i<count($users);$i++){
			       if($planeData[9]==$users[$i][user])return $users[$i][Color];
				//  if($planeData[8]==$users[$i][user])return $users[$i][Color];  
		      }
			  return "#ff5555";
	 }
     function getRandoColor($s,$e){
	          $str="#";
			  for($i=0;$i<6;$i++){
			      $c=rand($s,$e);
				   $b=chr($c);
				  $str=$str.$b;
			  }
			  return $str;
	 }
?>

<?php
	  function sortMainPlaneCode($PlaneCodes){
		       $sortString=array("h","m","b");
			   $sortArray=array();
			   for ($i=0;$i<count($sortString);$i++){
				    $add="000";
				    for($j=0;$j<100;$j++){
					   if($j>10)$add="00";
					   $str=$sortString[$i].$add.$j;
				       $tmp=isname($fillerType,$str,$num);
	                   if($tmp!=null){
					      array_push( $sortArray,$tmp);
					     }
				   }
			   } 
		  
	  }
      function isname($fillerType,$str,$num){
		       echo $str;
              for ($i=0;$i<count($fillerType);$i++){
				   if(strpos($fillerType[$i][$num],$str) !== false   ) {
					   return $fillerType[$i];
				   }
			   } 
			   return null;
	  }
?>


