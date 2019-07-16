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
				$nowMilestone=2;
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
     
	 function collectUser($planeDatas){
		  $users=array();
          global $colorCodes;
		  
		  $c=0;
	      for($i=0;$i<count($planeDatas);$i++){
			  $u="";
		    // if (!in_array($planeDatas[$i][8], $users)) $u=$planeDatas[$i][8];
			  if (!in_array($planeDatas[$i][9], $users)) $u=$planeDatas[$i][9]; 
			  if($u!=""){
				  $n=$c%count($colorCodes[10]);
				  $ar=array("user"=>$u,"Color"=>($colorCodes[10][$n]));
			      array_push($users,$ar); 
				  $c+=1;
			  }
 
		  }
	      return $users;
	 }
     function getUserColors($planeData,$users){
		 if($planeData[9]=="" or $planeData[9]=="未定義")	  return "#ff5555";
		      for($i=0;$i<count($users);$i++){
			       if($planeData[9]==$users[$i][user])return $users[$i][Color];
				//  if($planeData[8]==$users[$i][user])return $users[$i][Color];  
		      }
			  return "#ff5555";
	 }
 
?>
