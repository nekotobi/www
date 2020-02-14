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
			   $tasks= RemoveArray( $tasks,5, "目標"); 
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
			   global    $colorCodes;
			   $colorCodes= GetColorCode();
			   
      }
      function DrawButtons(){
		       global $URL;
			   global $typeName,$typeArray;
			   global $UserColor;
			   $Rect=array(20,40,60,20);
			   $UserColor=array();
			   //負責人
			   $Typestmp=getMysqlDataArray("members"); 
			   $TypeT=filterArray(  $Typestmp,3,"Art"); 
			   $Type= returnArraybySort($TypeT,1);
			   DrawButton($Type,$Rect,$URL,0,$typeArray,11);
			   array_Push( $UserColor,$Type);
			   //外包
			   $Rect[1]+=22;
			   $Typestmp=getMysqlDataArray("outsourcing"); 
			   $TypeT=filterArray($Typestmp,35,"true"); 
			   $Type= returnArraybySort($TypeT,2);
			   DrawButton($Type,$Rect,$URL,1,$typeArray,11);
		       array_Push( $UserColor,$Type);
 
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
			   $Type=array("快速新增","顯示甘特", "編輯隱藏","整理隱藏");
			   DrawButton($Type,$Rect,$URL,4,$typeArray);
			   //顯示
			   if($typeArray[4][1]== "編輯隱藏"){   
			       $Rect[1]+=22;
			       $Type=array("未隱藏","全部");
			       DrawButton($Type,$Rect,$URL,5,$typeArray);
			  }
		        if($typeArray[4][1]== "顯示甘特"){   
			    
			       DrawDragFin();
			  }
	  }
	  function DrawButton($array,$Rect,$URL,$valArrayNum,$ValArray,$ColorN=-1){
		       global    $colorCodes;
			
			   array_unshift( $array,"--");
			   $SubmitName= $ValArray[$valArrayNum][0];
		       $sa=  $ValArray[$valArrayNum][1];
	           for($i=0;$i<count($array);$i++){
				   	   $BgColor="#000000";
					   if($ColorN!=-1)  $BgColor=$colorCodes[$ColorN][$i];
			      
			    
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
			  $Ecode=$_POST["code"];
			  $bool=$_POST["bool"];
			  HidePlan($Ecode, $bool);
	          ReLoad();
			  return;
		  } 
		  if ($_POST["Send"]=="sendjava"){
			  CheckDrag();
		  }
		  if($typeArray[4][1]=="快速新增"){
			 fastTask();
		     return;
		  }
		  if($typeArray[4][1]=="顯示甘特"){
	         DrawCallendarRange();
             CreatJavaForm();			 
		  }
	      ListTasks();
	  }
	  function CheckDrag(){
	           $Ecode=$_POST["DragID"];
			   $target=$_POST["target"]	;
			   $workingDays=$_POST["workingDays"]	;
			   $state=$_POST["state"] ;
			   $Base=array( );
			    $up=array();
			   if($workingDays!="w"){
			   	  $Base=array("workingDays" );
		          $up=array( $workingDays  );
			   }
		       if($target!="target"){
			   	  $Base=array("startDay" );
		          $up=array( $target	 );
			   }
			    if($state!="state"){
			   	  $Base=array("state" );
		          $up=array( $state );
			   } 
			   ChangePlan($Ecode,$Base,$up);
			    ReLoad();
		     //  return;
	  }
	  function ReLoad(){
	    	   global $PostArray,$URL;
			   JavaPost($PostArray,$URL); 
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
			 if($typeArray[4][1]=="整理隱藏"){
			       setAllHide();
				   return;
			   }
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
		      	 //echo $s.">".$n;
			     if($typeArray[$i][1]!="--")$finalTasks=filterArray( $finalTasks,$s,$n); 
			 }
			 $SortNameArr=CollectUser($finalTasks,9);
			//$SortNameArr=array("進行中","已排程","未定義","已完成");
			 $finalTasks= SortArraybyNameArray($finalTasks,$SortNameArr,9);
		      $SortNameArr=CollectUser($finalTasks,8);
			 $finalTasks= SortArraybyNameArray($finalTasks,$SortNameArr,8);
	        return $finalTasks;
	 }
     function   CollectUser($finalTasks,$num){
		      $arr= array();
	          for($i=0;$i<count($finalTasks);$i++){
			//	 echo $finalTasks[$i][1];
				 if(!in_array($finalTasks[$i][$num],$arr))array_Push($arr,$finalTasks[$i][$num]);
				 
			 }
			 return $arr;
	 }
	 function   ListTasks(){
		    global $typeArray;
		    global $finalTasks;
			global $CalendarX;
			global $startY;
			global $DateWid;
            global $URL;
			global $colorCodes;
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
			    $RootTask=getRootTask($code);
		        $name =$RootTask[3];
							$BgColor="#006600";
				if($name=="ss")$name=$taskArray[$i][1];
				if($fin=="未定義")$BgColor="#664444";
				if($fin=="進行中")$BgColor="#006600";
				if($fin=="已排程")$BgColor="#227733";
				if($fin=="驗證中")$BgColor="#224499";
			    if($fin=="已完成")$BgColor="#000000";
				DrawRect($name,10,$fontColor,$x,$y ,149,$h,$BgColor);
				 $x+=150;
				//負責人
				$n=$taskArray[$i][9];
			
				if($n=="未定義" or $n=="")    $n=$taskArray[$i][8];
				$c= returnNum($n);
				$BgColor2= $colorCodes[$c[0]][$c[1]];
				$principal=$taskArray[$i][8]."-".$taskArray[$i][9];
				//if($principal=="未定義")$principal=$taskArray[$i][8];
				DrawRect($n,10,$fontColor,$x,$y,69,$h,$BgColor2);
				//jila
				$x+=70;
				$jila=$taskArray[$i][12];
				if($jila=="")$jila=$RootTask[12];
				$JilaLink="http://bzbfzjira.iggcn.com/browse/FP-".$jila  ;
				DrawLinkRect_newtab($jila,"10","#ffffff"  ,$x,$y,29,$h,"#aa8888",$JilaLink,"1" );
				//DrawRect($type,10,$fontColor,$x,$y,49,$h,$BgColor);
				//完成
	
				$type2=$fin;
				$x+=30;
				DrawRect($type2,10,$fontColor,$x,$y,39,$h,$BgColor);
				$x+=50;
	 
			    //工作時間
				 if($typeArray[4][1]=="顯示甘特"){
				    DrawGantt($taskArray,$i,$y, $name,$BgColor2);
				 }
				}
		    }		
			if($typeArray[4][1]=="編輯隱藏"){
			  // $ValArray=addArray($typeArray,$add);
			   $Rect=array(200,$startY,100,18);
			   sendVal($URL,$typeArray,"submit","重新排列" ,$Rect,10,"#aaaaaa", "#000000");
			   if($_POST["submit"]=="重新排列")ReSortTask($taskArray,$allChildArr);
			   $Rect[0]+=110;
			   sendVal($URL,$typeArray,"submit","隱藏完成" ,$Rect,10,"#aaaaaa", "#000000");
			   if($_POST["submit"]=="隱藏完成")SetFinHide($taskArray,$allChildArr);
			   
			}
	 } 
     function   returnNum($name){
		     global $UserColor ;
			 for($i=0;$i<count($UserColor[0]);$i++){
			     if($name==$UserColor[0][$i])return array(11,$i+1);
			 }
		     for($i=0;$i<count($UserColor[1]);$i++){
			    if($name==$UserColor[1][$i])return array(11,$i+1);
			 }
			 return array(0,0);
	 }
 	 function   DrawChildTask($x,$y,$Tasks){
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
	 function   DrawGantt($taskArray,$i,$y, $name,$BgColor){
		        global $DateWid;
				global $DateRange;
				global $CalendarX;
	            $fontColor="#ffffff";
	            $nd= explode("_",$taskArray[$i][2]);//= returnposX($taskArray[$i][2]);
				$s=array($DateRange[0],$DateRange[1],1);
			    $passDay= getPassDays(array($DateRange[0],$DateRange[1],1), $nd);
				$xx= $CalendarX+$passDay*$DateWid;
				$ww= $taskArray[$i][6]*$DateWid;
				$id= "S=".$taskArray[$i][1]."=".$taskArray[$i][6]."=".$DateWid ;
				$h=12;
				$msg=$taskArray[$i][6];
				DrawJavaDragbox($msg,$xx,$y+4,$ww,$h,10, $BgColor, $fontColor,$id);
				$id= "E=".$taskArray[$i][1]."=".$taskArray[$i][6]."=".$DateWid ;
				$BgColor="#cccccc";
				DrawJavaDragbox("",$xx+$ww,$y+4,5,$h,5, $BgColor, $fontColor,$id);
	 }
	 function   DrawDragFin(){
		        $x=20;
				$y=160;
				 $BgColor="#228888";
				 $fontColor="#ffffff";
	            $arr=array("進行中","已排程","驗證中","已完成");
			    for($i=0;$i<count($arr);$i++){
				    $id="state=".$arr[$i];
				    DrawJavaDragArea($arr[$i],$x,$y,39,18,$BgColor,$fontColor,$id);
					$x+=40;
				}
	  }
	 function   getRootTask($code){
	        global  $tasksName;
			for($i=0;$i<count($tasksName);$i++){
			    if($tasksName[$i][1]==$code){
					return $tasksName[$i] ;
				}
			}
			return "ss";
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
			   global $PostArray,$URL;
			   JavaPost($PostArray,$URL); 
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
     function SetFinHide($RootTasks, $allChildArr){
              $hideRoot=array();
			  $hideChild=array();
			  for($i=0;$i<count($RootTasks);$i++){
				  $bool=isChildFin( $allChildArr[$i]);
				  if( $bool )
					  {
					   HidePlan($RootTasks[$i][1],"g1");
					   foreach($hs as   $allChildArr[$i]) 
				            HidePlan($hs[0][1], "g1");
				      }
			  }
			   ReLoad();
	 }
	 function isChildFin( $childTask){
		     $bool=true;
			 if(count($childTask)==0)$bool=false;
		     for($i=0;$i<count( $childTask);$i++){
			     if( $childTask[$i][7]!="已完成") $bool=false;
			 }
			 return $bool;
	 }
     function setAllHide(){
		      global $typeName,$typeArray;
			  if($typeArray[2][1]=="--")return;
			     echo $typeArray[2][1];
	          $tasksT=getMysqlDataArray("fpschedule"); 
			  $tasksc=filterArray( $tasksT,10,$typeArray[2][1]);
			  $tasksc2=filterArray( $tasksT,18,"");
			 
			  $tasksti=filterArray( $tasksc,5, "工項");
			  $tasksH=filterArray( $tasksti,18,"g1");
			  $hideCodes= returnArraybySort($tasksH,1);
			  for($i=0;$i<count(  $hideCodes);$i++){
				 // echo $hideCodes[$i];
				 $hs=filterArray( $tasksc2,3, $hideCodes[$i]);
				// print_r($hs);
				 foreach($hs as  $task) 
				       HidePlan($hs[0][1], "g1");
			  }
 
	 }
   
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
					 DrawDays($days,$LocX,$LocY ,$DateWid,count($finalTasks), $arr,$y."_".$m);
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
	 function DrawDays($days,$LocX,$LocY,$w,$h,$arr,$ym){
		      global $Vacationdays;
		      $x=$LocX;
			  $BgColor="#aaaaaa";
			  $fontColor="#ffffff";
	          
	          for($i=1;$i<=$days;$i++){
				  $BgColor="#aaaaaa";
				  if ($arr[$i]==1)     $BgColor="#bbaaaa";
				  if ($arr[$i]==2)     $BgColor="#bb6666";
			      DrawRect($i,10,$fontColor,$x,$LocY,$w-1,20,$BgColor);
				  $id=$ym."_".$i;
				  
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
     function CreatJavaForm(){
		      $x=20;
			  $y=10;
		      global $URL;
			  global $typeName,$typeArray;
		      $upFormVal=array("Show","Show",$URL);
			  $UpHidenVal=array(array("tablename","fpschedule"),
			                    array("data_type","data"),
								array( "Send","sendjava" ),
						       // array( "DragID","DragID" ),
                        	 //   array( "workingDays","workingDays" ),
						    //    array( "target","target" ),
							//	array( "state","state" ),
	                            );
		      $UpHidenVal=	addArray( $UpHidenVal,$typeArray);	
			//  $inputVal=array();
			 
		      $inputVal=array(array("text","DragID","DragID",10,520,$y,200,20,$BgColor,$fontColor,"DragIDs" ,10),
			                   array("text","target","target",10,670,$y,200,20,$BgColor,$fontColor,"target" ,10),
						       array("text","workingDays","workingDays",10,820,$y,200,20,$BgColor,$fontColor,"w" ,3),
							  array("text","state","state",10,920,$y,200,20,$BgColor,$fontColor,"state" ,3),
			                //  array("submit","submit","",10,620,$y,100,20,$BgColor,$fontColor,"上傳java" ,20),
	                          );
							 
		      upSubmitform($upFormVal,$UpHidenVal, $inputVal);
	 }
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
								array("principal", $typeArray[0][1] ),
							    array("outsourcing", $typeArray[1][1] ),
							    array("selecttype", $typeArray[2][1] ),
							    array("type", $typeArray[3][1] ),
							    );			
			  $UpHidenVal=		addArray( $UpHidenVal,$typeArray);			
			  $inputVal=array(array("text","plan","計畫",10,20,$y,320,20,$BgColor,$fontColor,"" ,40),
			                  array("text","remark","jila單號",10,320,$y,100,20,$BgColor,$fontColor,"" ,5),
							//  array("text","line","行",10,420,$y,60,20,$BgColor,$fontColor,"" ,2),
						      array("text","workingDays","天數",10,520,$y,60,20,$BgColor,$fontColor,"" ,2),
                              array("submit","submit","",10,620,$y,100,20,$BgColor,$fontColor,"新增計畫" ,20),
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
				  $last= returntLine();
			       $tables=returnTables($data_library,$tableName);
				   $WHEREtable=array();
				   $WHEREData=array();
		           for($i=0;$i<count( $tables);$i++){
				        array_push($WHEREtable, $tables[$i] );
						$data=$_POST[$tables[$i]];
						if($tables[$i]=="type")$data="工項";
			     		if($tables[$i]=="line")$data=  $last;
					    array_push($WHEREData,$data);
					    echo  "</br>".$tables[$i].">".$_POST[$tables[$i]]."]";
		              }
				   $stmt=  MakeNewStmtv2($tableName,$WHEREtable,$WHEREData);
				   echo $stmt;
				   SendCommand($stmt,$data_library);
				  //子單
				   $bool=true;
				   echo "</br>";
				   
				   if($_POST["type"]=="--") $bool=false; 
				   if($bool){
				      echo "子工項";
					  $p=$_POST["principal"];
					  $o=$_POST["outsourcing"];
					  $w=$_POST["workingDays"];
				      if($p=="--") $p="未定義";
                      if($o=="--") $o="未定義";
                      if($w=="--")	$w=1; 
					   $WHEREData[8]= $p; 
				       $WHEREData[9]=$o; 
					   $WHEREData[6]=$w; 
					   $WHEREData[5]=$_POST["type"];
				       $WHEREData[1]="f".$_POST["code"];
			           $WHEREData[3]= $_POST["code"];
					   $WHEREData[12]="";
					   $WHEREData[7]="未定義";
			          $stmt=  MakeNewStmtv2($tableName,$WHEREtable,$WHEREData); 
					   echo $stmt;
					      SendCommand($stmt,$data_library);
				   }
			   
	 }
	 function returntLine(){
		       global $typeArray;
		      $tasksT=getMysqlDataArray("fpschedule"); 
			  $tasksT2=filterArray( $tasksT,10,$typeArray[2][1]); 
			  $tasksT3=filterArray( $tasksT2,18,""); 
			  $tasksT4=filterArray( $tasksT3,5,"工項");
              $last=0;
			  for($i=0;$i<count($tasksT4);$i++){
				if( $tasksT4[$i][4]>$last)$last=$tasksT4[$i][4];
			  }
			  return $last+1;
	 }
	 function HidePlan($Ecode,$bool){
	          global $URL;
			  global $data_library,$tableName,$MainPlanData;
			  $WHEREtable=array( "data_type", "code" );
		      $WHEREData=array( "data",$Ecode );
			  $Base=array("hide");
			  $up=array($bool);
			  $stmt= MakeUpdateStmt(  $data_library,$tableName,$Base,$up,$WHEREtable,$WHEREData);
			  echo $stmt;
			  SendCommand($stmt,$data_library);		
			 
	 }
	 function ChangePlan($Ecode,$Base,$up){
	          global $URL;
			  global $data_library,$tableName,$MainPlanData;
			  $WHEREtable=array( "data_type", "code" );
		      $WHEREData=array( "data",$Ecode );
			 
			  $stmt= MakeUpdateStmt(  $data_library,$tableName,$Base,$up,$WHEREtable,$WHEREData);
			   echo $stmt;
			    SendCommand($stmt,$data_library);		
			 
	 }
?>

