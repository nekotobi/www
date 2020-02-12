<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>工單明細表</title>
</head>
 <body bgcolor="#b5c4b1"> 
<?php //主控台
    include('PubApi.php');
    include('mysqlApi.php');
	include('CalendarApi.php');
	include('javaApi.php');
	DefineDatas();
    DrawButtons();
    TypeLink();
    DrawSwitch();
 
?>

<?php //類別
      function DrawSwitch(){
	           $Rect=array(980,10,40,12);
	           $Link= "schedule.php";
		       DrawLinkRect_newtab("schedule","10","#ffffff",$Rect[0],$Rect[1],$Rect[2],$Rect[3],"#000000",$Link,"1");
	  }
      function DefineDatas(){
		       global $tasks,$tasksName;
			   global $data_library,$tableName;
			   global $URL;
			   $URL="taskDataform.php";
			   $tableName="fpschedule";
			   $data_library="iggtaiperd2";
			   //type
		       global $typeName,$typeArray,$PostArray;
			   $subNameForWard="Type";
			   $typeName=array(array("負責人",8),array("外包",9), array("大類別",10),array("類別",5) ,array("編輯",-1),array("顯示",-1));
			   $typeArray=array(); 
			   $PostArray=array();
			   for($i=0;$i<count($typeName);$i++){
				    $n=$subNameForWard.$i;
				    $s= $_POST[$n];
					if($s=="")$s="--";
			        array_push( $typeArray,array($n,$s));
					array_Push( $PostArray,$n);
			   }
			   
			   //task
		       $tasksT=getMysqlDataArray("fpschedule"); 
			   $tasksT2=filterArray( $tasksT,0,"data"); 
			   $tasks= RemoveArray( $tasksT2,5, "工項"); 
			   $tasksName=filterArray($tasksT2,5, "工項"); 
			   global  $finalTasks;
			   $finalTasks  =definTasks();
		       global $Vacationdays;
			   $Vacationdays=getMysqlDataArray("vacationdays"); 
			   global $CalendarX,$DateWid,$startY;
			   $CalendarX=330;
			   $DateWid=15;
			   $startY=180;
			   global $DateRange;
			  // getVacationDays($YearRange,$MonthRange);
			   $DateRange= getDateRange($finalTasks,2);
			 
			  
      }
      function DrawButtons(){
		       global $URL;
			   global $typeName,$typeArray;
			   $Rect=array(20,40,60,20);
			   //負責人
			   $Typestmp=getMysqlDataArray("members"); 
			   $TypeT=filterArray(  $Typestmp,3,"Art"); 
			   $Type= returnArraybySort($TypeT,1);
			   DrawButton($Type,$Rect,$URL,0,$typeArray);
			   
			   //外包
			   $Rect[1]+=22;
			   $Typestmp=getMysqlDataArray("outsourcing"); 
			   $TypeT=filterArray($Typestmp,35,"true"); 
			   $Type= returnArraybySort($TypeT,2);
			   DrawButton($Type,$Rect,$URL,1,$typeArray);
			
	           //大類
			   $Rect[1]+=22;
			   $Typestmp=getMysqlDataArray("scheduletype"); 
			   $TypeT=filterArray(  $Typestmp,0,"data"); 
			   $TypeS=sortArrays( $TypeT ,5 ,"true");
			   $Type= returnArraybySort($TypeS,2);
			   DrawButton($Type,$Rect,$URL,2,$typeArray);
		   	   
			   //工類
			   $Rect[1]+=22;
			   $TypeT=filterArray(  $Typestmp,0,"data2"); 
			   $TypeS=sortArrays( $TypeT ,5 ,"true");
			   $Type= returnArraybySort($TypeS,2);
			   DrawButton($Type,$Rect,$URL,3,$typeArray);
			   //編輯類別
			   $Rect[1]+=22;
			   $Type=array("快速新增","顯示甘特", "編輯隱藏");
			   DrawButton($Type,$Rect,$URL,4,$typeArray);
			   //顯示
			  
			   $Rect[1]+=22;
			   $Type=array("未隱藏","全部");
			   DrawButton($Type,$Rect,$URL,5,$typeArray);
	  }
	  function DrawButton($array,$Rect,$URL,$valArrayNum,$ValArray){
			   array_unshift( $array,"--");
			   $SubmitName= $ValArray[$valArrayNum][0];
		       $sa=  $ValArray[$valArrayNum][1];
	           for($i=0;$i<count($array);$i++){
				   	   $BgColor="#000000";
					   if( $sa ==$array[$i])$BgColor="#ff1212";
					   $ValArray[$valArrayNum]=array($SubmitName,$array[$i]);
				       sendVal($URL,$ValArray,$SubmitName,$array[$i],$Rect,10,$BgColor);
				       $Rect[0]+=$Rect[2]+5;
	           }
	  }
 
	  function TypeLink(){
		  global $typeName,$typeArray;
		  if ($_POST["submit"]=="新增計畫"){
			  UpPlan();
			  return;
		  } 
		  if ($_POST["submit"]=="X"){
			  DeletPlan();
			  return;
		  } 
		  if ($_POST["submit"]=="H"){
			  HidePlan();
			  return;
		  } 
		  
		  if($typeArray[4][1]=="快速新增"){
			 fastTask();
		     return;
		  }
		  if($typeArray[4][1]=="顯示甘特"){
	         DrawCallendarRange(); 
		  }
	      ListTasks();
	  }
?>

<?php //內容
	 function findtaskChild($code){
	          global $tasks;
			  $arr=array();
			  for ($i=0;$i<count($tasks);$i++){
			       if($tasks[$i][3]==$code)array_push($arr,$tasks[$i]);
			  }
			  return $arr;
	 }
     function   definTasks(){
	         global $tasks,$tasksName;
		     global $typeName,$typeArray;
			 if($typeArray[4][1]=="編輯隱藏"){
			    $finalTasks=filterArray($tasksName,10,$typeArray[2][1] );
				if($typeArray[5][1]=="全部"){
				 $SortNameArr=array("","g1");
			     $f=SortArraybyNameArray($finalTasks,$SortNameArr,18);
				return $f ;
				}
			    $f=filterArray($finalTasks,18,"" );
				return $f ;
			 }

			 $finalTasks=RemoveArray($tasks,7,"已完成"); 
			 for($i=0;$i<4;$i++){
				 $s=$typeName[$i][1];
				 $n=$typeArray[$i][1];
		      	 echo $s.">".$n;
			     if($typeArray[$i][1]!="--")$finalTasks=filterArray( $finalTasks,$s,$n); 
			 }
			$SortNameArr=array("進行中","已排程","未定義","已完成");
			$finalTasks= SortArraybyNameArray($finalTasks,$SortNameArr,7);
	        return $finalTasks;
	 }

	 function   ListTasks(){
		    global $typeArray;
		    global $finalTasks;
			global $CalendarX;
			global $startY;
			global $DateWid;
            global $URL;
			$taskArray=$finalTasks;
		    $x=20;
			$y= $startY;
			$h=20;
			$fontColor="#ffffff";
			$BgColor="#000000";
			DrawRect("總計X".count($taskArray),10,$fontColor,$x,$y,255,$h,$BgColor);
            $allChildArr=array();
			global $user;
			$user=array();
		    for($i=0;$i<count($taskArray);$i++){
			    $y+=22;
				$x=20;
				if($typeArray[4][1]=="編輯隱藏"){
			           $name=$taskArray[$i][3];
					   $Line=$taskArray[$i][4];
				       $bool=$taskArray[$i][18];
					   $code= $taskArray[$i][1];
					   $color="#eeeeee";
					   $Rect=array($x-10,$y,19,$h);
					   //echo $bool;
					   if($bool=="g1"){
						   $color="#aaaaaa";
					       $bool="";
					   }else{
					      $bool="g1";
					   }
					   $add=array(array("code", $code),array("bool",$bool));
					   $ValArray=addArray($typeArray,$add);
					   sendVal($URL,$ValArray,"submit","X" ,$Rect,10,"#ffaaaa", "#000000");
					   
					   $x+=20;
				       $Rect=array($x-10,$y,19,$h);
				       sendVal($URL,$ValArray,"submit","H" ,$Rect,10,"#aaffff", "#000000");
			 
					   $x+=22;
					   DrawRect($Line,10,"#000000",$x,$y ,20,$h,$color);
					   $x+=20;
					   DrawRect($name,10,"#000000",$x,$y ,149,$h,$color);
					   $x+=150;
					   $arr=findtaskChild($taskArray[$i][1]);
					   array_Push( $allChildArr,$arr);
					   DrawRect(count($arr),10,$fontColor,$x,$y ,19,$h,$BgColor);
					   $x+=20;
					   DrawChildTask($x,$y,$arr);
					   //重新排列
					   
				}
				
				if($typeArray[4][1]!="編輯隱藏"){
				$code=$taskArray[$i][3];
				//工單名
				$fin=$taskArray[$i][7];
		        $name=getTaskName($code);
				$BgColor="#006600";
				if($fin=="未定義")$BgColor="#660000";
				if($fin=="進行中")$BgColor="#006600";
				if($fin=="已排程")$BgColor="#007700";
			    if($fin=="已完成")$BgColor="#000000";
				DrawRect($name,10,$fontColor,$x,$y ,149,$h,$BgColor);
				 $x+=150;
				//負責人
				 $BgColor="#777777";
				$principal=$taskArray[$i][9];
				if($principal=="未定義")$principal=$taskArray[$i][8];
				
				DrawRect($principal,10,$fontColor,$x,$y,49,$h,$BgColor);
				//時間
				$x+=50;
				$type=$taskArray[$i][2];
				DrawRect($type,10,$fontColor,$x,$y,49,$h,$BgColor);
				//小類
				$type2=$taskArray[$i][5];
				$x+=50;
				DrawRect($type2,10,$fontColor,$x,$y,49,$h,$BgColor);
				$x+=50;
				//完成
				/*
				$fin=$taskArray[$i][7];
				DrawRect($fin,10,$fontColor,$x,$y,49,$h,$BgColor);
				$x+=50;
				*/
			    //工作時間
				 if($typeArray[4][1]=="顯示甘特"){
				    DrawGantt($taskArray,$i,$y);
				 }
				}
		    }		
			if($typeArray[4][1]=="編輯隱藏"){
			  // $ValArray=addArray($typeArray,$add);
			  $Rect=array(200,$startY,100,18);
			   sendVal($URL,$typeArray,"submit","重新排列" ,$Rect,10,"#aaaaaa", "#000000");
			   if($_POST["submit"]=="重新排列")ReSortTask($taskArray,$allChildArr);
			   
			}
	 } 
 	 function DrawChildTask($x,$y,$Tasks){
              global $user;	      
		      for($i=0;$i<count($Tasks);$i++){
			 	$t=$Tasks[$i][5];
				$n=$Tasks[$i][8];
				if($n=="未定義")$n=$Tasks[$i][9];
			    if (!in_array($n,$user ))array_Push($user,$n);
					
				$BgColor="#ffaaaa";
				$state=$Tasks[$i][7];
				if($state=="已完成")$BgColor="#aaaaaa";
			    DrawRect($n,10,$fontColor,$x,$y ,49,$h,$BgColor);
			    $x+=50;
		   	    DrawRect($t,10,$fontColor,$x,$y ,49,$h,$BgColor);
			
	
			}
	 }
	 function   DrawGantt($taskArray,$i,$y){
		        global $DateWid;
				global $DateRange;
				global $CalendarX;
	            $fontColor="#ffffff";
		     	$BgColor="#000000";
	            $nd= explode("_",$taskArray[$i][2]);//= returnposX($taskArray[$i][2]);
				$s=array($DateRange[0],$DateRange[1],1);
			    $passDay= getPassDays(array($DateRange[0],$DateRange[1],1), $nd);
				$xx= $CalendarX+$passDay*$DateWid;
				$ww= $taskArray[$i][6]*$DateWid;
				$id="ar".$i;
				$BgColor="#22aaaa";
				$h=18;
				$msg=$taskArray[$i][6];
				DrawJavaDragbox($msg,$xx,$y,$ww,$h,10, $BgColor, $fontColor,$id);
			//	DrawRect($taskArray[$i][6],10,$fontColor,$xx,$y,$ww,$h,"#22aaaa");
	 
	 }
	 function   getTaskName($code){
	        global  $tasksName;
			for($i=0;$i<count($tasksName);$i++){
			    if($tasksName[$i][1]==$code){
					return $tasksName[$i][3];
				}
			}
			return "ss";
	 } 
?>
<?php //重新排列
     function ReSortTask($tasks,$allChildArr){
		      global $user;
			  $n=0;
			  $sortArr=array();
              for($i=0;$i<count($user);$i++){
			       $sortArr= returnTask($tasks,$allChildArr,$user[$i],$sortArr);
				  // if($task==null) array_Push(  $nullArr,$task);
			  }
			  echo "</br></br></br></br></br></br></br></br></br></br></br></br></br></br>";
			  echo "</br></br></br></br></br></br></br></br></br></br></br></br></br></br>";  
          
              $sortArray =array();
			  $tmp=$tasks;
			  for($i=0;$i<count( $sortArr);$i++){
				   array_Push($sortArray,$tasks[$sortArr[$i]]);
				 
				  unset($tmp[$sortArr[$i]]);
		      }
		       sort($tmp);
			   for($i=0;$i<count( $tmp);$i++){
				          array_Push( $sortArray,$tmp[$i]) ;
				  }
			   $postSort=array();
			   for($i=0;$i<count( $sortArray );$i++){
				    array_Push( $postSort, $sortArray[$i][1] );
			   }
	           UpResort($postSort);
	 }
     function UpResort($postSort){
		      global $URL;
			  global $data_library,$tableName,$MainPlanData;
		      global $PostArray;
	          for($i=0;$i<count($postSort);$i++){
			     $WHEREtable=array( "data_type", "code" );
		         $WHEREData=array( "data",$postSort[$i]);
			     $Base=array("Line");
			     $up=array($i+1);
			     $stmt= MakeUpdateStmt(  $data_library,$tableName,$Base,$up,$WHEREtable,$WHEREData);
			     echo $stmt;
			     SendCommand($stmt,$data_library);		
			   }
			   JavaPost($PostArray,$URL); 
	 }
?>
<?php //隱藏編號

	 function returnTask($tasks,$allChildArr,$user,$sortArr){
	            for($i=0;$i<count($tasks);$i++){
					if($allChildArr[$i][0][8]==$user)array_Push( $sortArr,$i) ;
			        if($allChildArr[$i][0][9]==$user)array_Push( $sortArr,$i) ;
				}
				return $sortArr;
	 }
	 function DrawBool($Data,$i,$tables,$Rect){
	                   global $URL;
	                   $bool=$Data[$i][18];
					   $color="#444444";
					   if($bool=="g1")$color="#eeffee";
					   $ValArray=array(array("Edit",$tables[$i]),array("code",$Data[1]),array("bool",$bool));
					   sendVal($URL,$ValArray,"submit",$tables ,$Rect,10,$color, "#000000");
	}
 
?>

<?php
     function DrawCallendarRange(){
		      global $finalTasks;
			  global $CalendarX, $startY;
			  global $DateWid;
			  global $DateRange;
			  echo count($finalTasks);
              //print_r( $DateRange);
		      $y=$DateRange[0];
			  $m=$DateRange[1];
			  $sdate="";
			  $fdate=$DateRange[2]."_".$DateRange[3];
			  $LocX=  $CalendarX;
			  $LocY=  $startY;
			  $BgColor="#555555";
			  $fontColor="#ffffff";
			  $t=0;
			  while ($sdate!=$fdate){
				     $days=getMonthDay($m,$y);
					 DrawRect($m,10,$fontColor,$LocX,$LocY-20,$days* $DateWid-1,20,$BgColor);
					 $arr=  ReturnVacationDays($y,$m,$VacationDays);
					 DrawDays($days,$LocX,$LocY ,$DateWid,count($finalTasks), $arr);
					 $sdate=$y."_".$m;
					 if($sdate==$fdate)break;
					 $m+=1;
					 if($m>12){
						 $y+=1;
					 $m=1;
					 }
			        $t++;
					if($t>12)break;
					$LocX+=$days* $DateWid;
			  }
	 }
	 function DrawDays($days,$LocX,$LocY,$w,$h,$arr){
		      global $Vacationdays;
		      $x=$LocX;
			  $BgColor="#aaaaaa";
			  $fontColor="#ffffff";
	
	          for($i=1;$i<=$days;$i++){
				  $BgColor="#aaaaaa";
				  if ($arr[$i]==1)     $BgColor="#bbaaaa";
				  if ($arr[$i]==2)     $BgColor="#bb6666";
			      DrawRect($i,10,$fontColor,$x,$LocY,$w-1,20,$BgColor);
				  $id="box".$i;
				  DrawJavaDragArea("",$x,$LocY+22,$w-1,$h*22,$BgColor,$fontColor,$id);
				  //DrawRect("",10,"#cccccc",$x,$LocY+22,$w-1,$h*22,$BgColor);
				  $x+=$w;
			  }
	 }
?>
<?php //function
     function collectMonth($datas,$TargetY,$TargetM){
           $ar=array();	      
		  for($i=0;$i<count($datas);$i++){
		           $d= explode("_",$datas[$i][2]);
				   if($d[0]==$TargetY && $d[1]==$TargetM){
				        array_push(  $ar,$datas[$i]);
				   }
		   }
		   return $ar;
	 }
?>

<?php //快速表單
     function fastTask(){
	          $x=20;
			  $y=10;
			  global $URL;
			  global $typeName,$typeArray;
			  $BgColor="#ffffff";
			  $fontColor="#000000";
			  $upFormVal=array("AddPlan","AddPlan",$URL);
			  $UpHidenVal=array(array("tablename","fpschedule"),
			                    array("data_type","data"),
								array("startDay",date("Y_n_j")),
								array("milestone", "m5" ),
							    array("code", returnDataCode( ) ),
								array("type", "工項" ),
							    array("selecttype", $typeArray[1][1] )
							    );			
			  $UpHidenVal=		addArray( $UpHidenVal,$typeArray);			
			  $inputVal=array(array("text","plan","計畫",10,20,$y,320,20,$BgColor,$fontColor,"" ,40),
			                  array("text","remark","jila單號",10,320,$y,100,20,$BgColor,$fontColor,"" ,5),
							  array("text","line","行",10,420,$y,60,20,$BgColor,$fontColor,"" ,2),
                              array("submit","submit","",10,520,$y,100,20,$BgColor,$fontColor,"新增計畫" ,20),
			                  );					 
			  upSubmitform($upFormVal,$UpHidenVal, $inputVal);
	 }
	 function DeletPlan(){
		      global $data_library,$tableName;
	          global $PostArray;
			  global $URL;
	          $Ecode= $_POST["code"];
		      $WHEREtable=array( "data_type", "code" );
		      $WHEREData=array( "data",$Ecode );
			  $stmt=   MakeDeleteStmt($tableName,$WHEREtable,$WHEREData);
			  SendCommand($stmt,$data_library);
		      JavaPost($PostArray,$URL); 
	 }
	 function UpPlan(){
		        global $data_library,$tableName;
			       $tables=returnTables($data_library,$tableName);
				   $WHEREtable=array();
				   $WHEREData=array();
		           for($i=0;$i<count( $tables);$i++){
				        array_push($WHEREtable, $tables[$i] );
					    array_push($WHEREData,$_POST[$tables[$i]]);
					    echo  "</br>".$tables[$i].">".$_POST[$tables[$i]]."]";
		              }
				   $stmt=  MakeNewStmtv2($tableName,$WHEREtable,$WHEREData);
				   echo $stmt;
				   SendCommand($stmt,$data_library);
			      // echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";		    
	 
	 }
	 function HidePlan(){
		      $Ecode=$_POST["code"];
			  $bool=$_POST["bool"];
	          global $URL;
			  global $data_library,$tableName,$MainPlanData;
		      global $PostArray;
			  $WHEREtable=array( "data_type", "code" );
		      $WHEREData=array( "data",$Ecode );
			  $Base=array("hide");
			  $up=array($bool);
			  $stmt= MakeUpdateStmt(  $data_library,$tableName,$Base,$up,$WHEREtable,$WHEREData);
			  echo $stmt;
			  SendCommand($stmt,$data_library);		
			  JavaPost($PostArray,$URL); 
	 }
?>


<?php //bak
/*
	 function DrawCallendar(){
		 	  global $CalendarX;
		      global $finalTasks;
			  global $DateWid;
			  global $URL;
	          $startY=2019;
			  $StartM=1;
			  $EndY=date("Y");
			  $EndM=date("m");
			  $y=$startY;
			  $m=1;
			  $x= $CalendarX;
			  $y=140;
			  $h=20;
			  $BgColor="#aaaaaa";
			  $fontColor="#ffffff";
			  
              for($i=0;$i<count($finalTasks);$i+=20){
				  $sy=$startY;
				  $m=1;
			      $x= $CalendarX;
				  $ValArray=$typeArray;
			      while( $sy<=$EndY  ){
				       while($m<=12  ){
						     $yy=$y+$i*22;
					         if($i==0){
				             $ValArray=array();
						     array_push($ValArray,array("Type3",$m));
						     sendVal($URL,$ValArray,"Type3",$m,array($x,$yy,29,$h),10, $BgColor ,$fontColor  ) ;
					         }
					        if($i!=0){   
					           DrawRect($m,10,$fontColor,$x,$yy,29,$h,$BgColor);
					          }
				           $x+=30;
					       $m+=1;
					   }
					  $m=1;
				      $sy+=1; 
			  }			  
			  }
			  
	 }
	 function returnposX_b($date){
		 global $CalendarX;
	     $d= explode("_",$date);
		 $y=  $d[0]-2019;
		 $m=$d[1];
	   
		 return $CalendarX+ $y*30*12+($m-1)*30+$d[2];
	 }
	 */
?>