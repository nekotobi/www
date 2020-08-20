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
	include('TaskDataFormJavaApi.php');
	DefineDatas();
    DrawButtons();
    TypeLink();
    DrawSwitch();
?>
<?php //類別
      function DrawSwitch(){
	           $Rect=array(980,30,40,12);
	           $Link= "taskSearch.php";
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
			   $nc=0;
			   for($i=0;$i<count($typeName);$i++){
				    $n=$subNameForWard.$i;
				    $s= $_POST[$n];
					if($s=="" && $i!=0 ){
						$s="--";
					    $nc++;
					}
			        array_push( $typeArray,array($n,$s));
					array_Push( $PostArray,$n);
			   }
			   if( $nc>4){
			        $typeArray[4][1]="顯示甘特";
					$typeArray[5][1]="內部";
			   }
			   //task
		       $tasksT=getMysqlDataArray("fpschedule"); 
			   $tasksT2=filterArray( $tasksT,0,"data"); 
			   $tasks= RemoveArray( $tasksT2,5, "工項"); 
			   $tasks= RemoveArray( $tasks,5, "目標"); 
			   $tasksName=filterArray($tasksT2,5, "工項"); 

			   global  $finalTasks, $finalTasksT;
			   $finalTasks  =definTasks();
			   $finalTasksT=RemoveArray( $finalTasks  ,7,"未定義"); 
		       global $undefineTasks;
		       $undefineTasks =filterArray(  $finalTasks,7,"未定義"); 
			   
		       global $Vacationdays;
			   $Vacationdays=getMysqlDataArray("vacationdays"); 
			   global $CalendarX,$DateWid,$startY;
			   $CalendarX=430;
			   $DateWid=12;
			   $startY=200;
			   global $DateRange;
			   $DateRange= getDateRange($finalTasks,2);
			  // print_r($DateRange);
			   global    $colorCodes;
			   $colorCodes= GetColorCode();
			 
			   global $target;
			   $targetT=filterArray($tasksT,5, "目標");
			   $startDate=date("Y_n_1");
			   $target= filterDate( $targetT,2,$startDate,$dateRange);
      } 
      function DrawButtons(){
		       global $URL;
			   global $typeName,$typeArray;
			   global $UserColor;
			   global $principals,$Outs;
			   global $Bigtypes;
			   $Rect=array(20,40,60,20);
			 //  $UserColor=array();
			   //負責人
			   $Typestmp=getMysqlDataArray("members"); 
			   $TypeT=filterArray(  $Typestmp,3,"Art"); 
			   $principals= returnArraybySort($TypeT,1);
			   DrawButton($principals,$Rect,$URL,0,$typeArray,"principal",12);
			 //  array_Push( $UserColor,$Type);
			   //外包
			   $Rect[1]+=22;
			   $Typestmp=getMysqlDataArray("outsourcing"); 
			   $TypeT=filterArray($Typestmp,35,"true"); 
			   $Outs= returnArraybySort($TypeT,2);
			   DrawButton($Outs,$Rect,$URL,1,$typeArray,"outsourcing",11);
		     //  array_Push( $UserColor,$Type);
 
	           //大類
			   $Rect[1]+=22;
			   $Typestmp=getMysqlDataArray("scheduletype"); 
			   $TypeT=filterArray(  $Typestmp,0,"data"); 
			   $TypeS=sortArrays( $TypeT ,5 ,"true");
			   $Type= returnArraybySort($TypeS,2);
			   $Bigtypes=$Type;
			   DrawButton($Type,$Rect,$URL,2,$typeArray,"selecttype");
		   	   
			   //工類
			   $Rect[1]+=22;
			   $TypeT=filterArray(  $Typestmp,0,"data2"); 
			   $TypeS=sortArrays( $TypeT ,5 ,"true");
			   $Type= returnArraybySort($TypeS,2);
			   DrawButton($Type,$Rect,$URL,3,$typeArray,"type");
			   //編輯類別
			    $Rect[1]+=22;
			   $Type=array("快速新增","顯示甘特");//, "編輯隱藏","整理隱藏");
			   DrawButton($Type,$Rect,$URL,4,$typeArray);
			   $Rect[1]+=22;
			   if($typeArray[4][1]== "編輯隱藏"){   
			   $Type=array("未隱藏","全部","不顯示子任務");
			   DrawButton($Type,$Rect,$URL,5,$typeArray);
			   } 
		       if($typeArray[4][1]== "顯示甘特"){   
			     $Type=array("日期","內部","外部","內未定義","外未定義","type1未分類");
				 DrawButton($Type,$Rect,$URL,5,$typeArray);
			     DrawDragArea();
			     }
	  }
	  function DrawButton($array,$Rect,$URL,$valArrayNum,$ValArray,$Type="",$ColorN=-1){
		       global    $colorCodes;
			   array_unshift( $array,"--");
			   $SubmitName= $ValArray[$valArrayNum][0];
		       $sa=  $ValArray[$valArrayNum][1];
			   
	           for($i=0;$i<count($array);$i++){
				   	   $BgColor="#000000";
					   if($ColorN!=-1){
						   $n=$i%9;
						   $BgColor=$colorCodes[$ColorN][$n];
					   }
					   if( $sa ==$array[$i])$BgColor="#ff1212";
					   $ValArray[$valArrayNum]=array($SubmitName,$array[$i]);
				       sendVal($URL,$ValArray,$SubmitName,$array[$i],$Rect,10,$BgColor);
				       $Rect[0]+=$Rect[2]+5;
					 
						  if(  $valArrayNum!=4)  
					   DrawTypeDragArea($Type,$array[$i] ,$Rect);

	           }
	  } 
	  function DrawTypeDragArea($Type,$name,$Rect){
		       $id= $Type."=".$name;
			   $x=$Rect[0]-$Rect[2]-2 ;
			   $y=$Rect[1]+4;
			                // ($msg,$x,$y,$w,$h,$BgColor,$fontColor,$id,$fontSize=10)l
			   DrawJavaDragArea($i,$x,$y,8,12,"#155555","#555555",$id,5);	
		         
	  }
	  function TypeLink(){
		       global $typeName,$typeArray;
		       global $noread;
		       global $data_library,$tableName;
			   global $URL;
		       if ($_POST["submit2"]!=""){
			 	   printSc();
				   return;
			   }
		  if(		$noread=="true")return;
		  if( $_POST["submitUp"]=="E"){
			
			  $code=$_POST["EditCode"];
			  DrawMysQLEdit($data_library,$tableName,$code,$URL,$typeArray,"修改".$code."表格內容");
			  return;
		  }
		  if ($_POST["submit"]=="修改表單"){
			  $code=$_POST["code"];
			  upMysQLEdit($data_library,$tableName,$code,$URL,$PostArray );
		      ReLoad();
		  }
		  if ($_POST["submit"]=="新增計畫"){
			  UpPlan();
			   	 ReLoad();
		  } 
		  if ($_POST["submit"]=="X"){
			  DeletPlan( $_POST["code"]);
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
		   global $finalTasksT,$undefineTasks;
	      ListTasks( $finalTasksT,"defined");
	      ListTasks( $undefineTasks,"undefined");
	  }
	  function CheckDrag(){
	           $Ecode=$_POST["DragID"];
			   $target=$_POST["target"]	;
	           $CheckArr= array("startDay","workingDays" ,"principal","outsourcing","type","state","selecttype");
			   $Base=array( );
			   $up=array();
			   echoBr(22);
			   if($_POST["state"]=="刪除"){
				   DeletPlan($Ecode);
				   ReLoad();
				   return;
			   }
			       $Base=array( );
				   $up=array( );
			   for($i=0;$i<count($CheckArr);$i++){
				 //  echo "[".$CheckArr[$i]."=".$_POST[$CheckArr[$i]];
		       
				  // if($_POST[$CheckArr[$i]]!=$CheckArr[$i]){
		           if($_POST[$CheckArr[$i]]!=""){
					 
					   array_push( $Base,$CheckArr[$i]);
					   array_push( $up,$_POST[$CheckArr[$i]] );
					   echo  $CheckArr[$i].">".$_POST[$CheckArr[$i]] ;
				    //  $Base=array($CheckArr[$i]);
		             // $up=array( $_POST[$CheckArr[$i]]);
				   }
			   }
			   echo "xxxxxxxxx";
			   print_r( $up);
	           ChangePlan($Ecode,$Base,$up);
		       ReLoad();
	  }
	  function ReLoad(){
	    	   global $PostArray,$URL;
			   JavaPost($PostArray,$URL); 
	  }
?>
<?php //內容
	 function   findtaskChild($code){
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
				if($typeArray[5][1]=="全部" or $typeArray[5][1]=="不顯示子任務" ){
				 $SortNameArr=array("","g1");
			     $f=SortArraybyNameArray($finalTasks,$SortNameArr,18);
				return $f ;
				}
			    $f=filterArray($finalTasks,18,"" );
				return $f ;
			 }
			 $finalTasks=RemoveArray($tasks,7,"已完成"); 
		     //$finalTasks=RemoveArray($finalTasks,7,"未定義"); 
			 for($i=0;$i<4;$i++){
				 $s=$typeName[$i][1];
				 $n=$typeArray[$i][1];
			     if($typeArray[$i][1]!="--")$finalTasks=filterArray( $finalTasks,$s,$n); 
			 }
			 $finalTasks=sortTask($finalTasks,$typeArray[5][1]);
	         return $finalTasks;
	 }
	 function   sortTask($finalTasks,$sortby){
                 $finalTasks= SortArrayDate($finalTasks,2);
				if($sortby=="日期"){
					return  $finalTasks;
				}
				if($sortby=="內部"){
				    $SortNameArr=CollectUser($finalTasks,8);
			        $finalTasks= Sort2DArraybyNameArray($finalTasks,$SortNameArr,8);
					return $finalTasks;
                }
		     	if($sortby=="外部"){
					$finalTasks= RemoveArray ($finalTasks,8,"");
				    $SortNameArr=CollectUser($finalTasks,9);
				    $finalTasks= SortArraybyNameArray($finalTasks,$SortNameArr,9);
					return $finalTasks;
			    }
			 	if($sortby=="內未定義"){  
				    $finalTasks= filterArray($finalTasks,8,"未定義");
					return $finalTasks;
			    }
			    if($sortby=="外未定義"){  
				    $finalTasks= filterArray($finalTasks,9,"未定義");
					return $finalTasks;
			    }
				if($sortby=="type1未分類"){
				   $finalTasks= filterArray($finalTasks,10,"--");
				}
				return $finalTasks;
	 }
	 function   Sort2DArraybyNameArray($baseArr,$SortNameArr,$Num){//二維 重新排列
		        $arr=array();
	            for($i=0;$i<count($SortNameArr);$i++){
					$ar = filterArray($baseArr,$Num,$SortNameArr[$i]);
				    $Arrc=array("","未定義");
					$Arrc2=CollectUser($ar,9);
					$Arrc= addArray($Arrc,$Arrc2);
				    $sr= SortArraybyNameArray($ar,$Arrc,9);
				    $arr= addArray($arr,$sr);
				}
				return $arr;
	 }
     function   CollectUser($finalTasks,$num){
		         $arr= array();
	             for($i=0;$i<count($finalTasks);$i++){
					 if($finalTasks[$i][$num]!="未定義" && $finalTasks[$i][$num]!=""){
					  if(!in_array($finalTasks[$i][$num],$arr))array_Push($arr,$finalTasks[$i][$num]);
					 }
			    }
			    return $arr;
	 }
	 function   ListTasks($taskArray,$undefine){
		    global $typeArray;
			global $CalendarX;
			global $startY;
			global $DateWid;
            global $URL;
			global $colorCodes;
		    $x=20;
			$y= $startY;
			$h=20;
			$fontColor="#ffffff";
			$BgColor="#000000";
			if($undefine!="undefined")
			   DrawRect("總計X".count($taskArray),10,$fontColor,$x,$y,$CalendarX-20,$h,$BgColor);
            $allChildArr=array();
			global $user;
			$user=array();
		    for($i=0;$i<count($taskArray);$i++){
			    $y+=22;
				$startY+=22;
				$x=20;
				/*
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
					   if($typeArray[5][1]!="不顯示子任務"){
					   	   $arr=findtaskChild($taskArray[$i][1]);
					       array_Push( $allChildArr,$arr);
					       DrawRect(count($arr),10,$fontColor,$x,$y ,19,$h,$BgColor);
					       $x+=20;
					   DrawChildTask($x,$y,$arr);
					   }
					   //重新排列`
				}
				*/
				if($typeArray[4][1]!="編輯隱藏"){
				$code=$taskArray[$i][3];
				//工單名
				$fin=$taskArray[$i][7];
			    $RootTask=getRootTask($code);
		        $name =$RootTask[3];
				if($name=="ss")$name=$taskArray[$i][1];
	            if($taskArray[$i][14]!="")$name=$taskArray[$i][14];
				$BgColor=getProgressColor($fin);
				$w=$CalendarX-120;
				DrawRect($name,10,$fontColor,$x,$y ,$w,$h,$BgColor);
			    $sendarr=addArray($typeArray,array(array("EditCode",$code)));
				//root
			 	$Rect=array($x-22,$y-4,4,17);
                sendVal($URL,$sendarr,"submitUp","E",$Rect,4,"#997777", $fontColor);
				//childTask
				$sendarr=addArray($typeArray,array(array("EditCode",$taskArray[$i][1])));
				$Rect=array($x+$w-20 ,$y+1,4,6);
                sendVal($URL,$sendarr,"submitUp","E",$Rect,4,"#779977", $fontColor);
				//jila
				$jila=$taskArray[$i][12];
				if($jila=="")$jila=$RootTask[12];
				if($jila!=""){
				$JilaLink="http://bzbfzjira.iggcn.com/browse/FP-".$jila  ;
				DrawLinkRect_newtab($jila,"8","#ffffff"  ,$x,$y+4,20,12,"#aa8888",$JilaLink,"1" );
				}
				// $x+=$w;
			    //類別
			 //    DrawRect($taskArray[$i][5],10,$fontColor,$x ,$y,29,$h,$BgColor);
				$x+=$w;
				//負責人
				$nameColor=returnNameColor($taskArray[$i],$typeArray[5][1] );
				$name=$nameColor[0];
				$BgColor2= $nameColor[1];
				DrawRect($name,9,$fontColor,$x,$y,69,$h,$BgColor2);
				$x+=70;

				//完成
				$type2=$fin;
				//$x+=25;
				DrawRect($type2,8,$fontColor,$x,$y,29,$h,$BgColor);
				$x+=30;
			    //工作時間
				 if($typeArray[4][1]=="顯示甘特"){
				    DrawGantt($taskArray,$i,$y, $name,$BgColor2,$undefine);
				 }
				}
		    }		
			if($typeArray[4][1]=="編輯隱藏"){
			   $Rect=array(200,$startY,100,18);
			   sendVal($URL,$typeArray,"submit","重新排列" ,$Rect,10,"#aaaaaa", "#000000");
			   if($_POST["submit"]=="重新排列")ReSortTask($taskArray,$allChildArr);
			   $Rect[0]+=110;
			   sendVal($URL,$typeArray,"submit","隱藏完成" ,$Rect,10,"#aaaaaa", "#000000");
			   if($_POST["submit"]=="隱藏完成")SetFinHide($taskArray,$allChildArr);
			}
	 } 
	 function   getProgressColor($fin){
	        	$BgColor= "#006600";
	 			if($fin=="未定義")$BgColor="#555555";
				if($fin=="進行中")$BgColor="#548C00";
				if($fin=="已排程")$BgColor="#516C00";
				if($fin=="驗證中")$BgColor="#111111";
				if($fin=="預排程")$BgColor="#515130";
			    if($fin=="已完成")$BgColor="#000000";
				return $BgColor;
	 }
 
	 function   returnNameColor($Task,$SortType ){
		 	    global $principals,$Outs; 
			    global $colorCodes;
				$PorO=9;

				if($SortType=="內部" or $SortType=="內未定義" ){
				   $PorO=8;
				}
				if($SortType=="外部" or $SortType=="外未定義" ){
				   $PorO=9;
				}
			    if($Task[9]=="未定義" or $Task[9]=="") { 
				   $PorO=8;
				}
			    $name=$Task[$PorO];
				$arr=$Outs;
		        $color=$colorCodes[11];
				if($PorO==8){
					$arr=$principals;
				    $color=$colorCodes[12];
				    if($Task[9]!="未定義" & $Task[9]!="") $color=$colorCodes[11];
				}
				$c="#ff4444";
			    for($i=0;$i<count($arr);$i++){
				    if($arr[$i]==$name){
						$n=($i+1)%(count($color)-1);
						$c= $color[$n];
					}
				}
		        if($PorO==8){
				     if($Task[9]!="未定義" & $Task[9]!="")
				         $name=$Task[9]."[".$Task[8]."]"; 
				 }
				if($PorO==9  ){
				    if($Task[8]!="未定義" & $Task[8]!="") 
						$name=$Task[9]."[".$Task[8]."]";
				 }
				
			 
		        return array($name,$c);
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
			    DrawRect($n,10,$fontColor,$x,$y , 49,$h,$BgColor);
			    $x+=50;
		   	    DrawRect($t,10,$fontColor,$x,$y ,49,$h,$BgColor);
			}
	 }
	 function   DrawGantt($taskArray,$i,$y, $name,$BgColor,$undefine){
		        global $DateWid;
				global $DateRange;
				global $CalendarX;
	            $fontColor="#ffffff";
                
				if($undefine!="undefined"){
		           $nd= explode("_",$taskArray[$i][2]);//= returnposX($taskArray[$i][2]);
				 }else{
				   $nd=array(date("Y"),date("n"),date("j")); 
				   $BgColor="#999999";
				 }
			    $s=array($DateRange[0],$DateRange[1],1);
			    $passDay= getPassDays(array($DateRange[0],$DateRange[1],1), $nd);
					if($undefine=="undefined")$passDay+=7;
				$xx= $CalendarX+$passDay*$DateWid;
				$ww= $taskArray[$i][6]*$DateWid;
				$id= "S=".$taskArray[$i][1]."=".$taskArray[$i][6]."=".$DateWid ."=".$taskArray[$i][7];
				$h=12;
				$msg=$taskArray[$i][6];
				DrawJavaDragbox($msg,$xx,$y+4,$ww,$h,10, $BgColor, $fontColor,$id);
				$id= "E=".$taskArray[$i][1]."=".$taskArray[$i][6]."=".$DateWid  ."=".$taskArray[$i][7];
				$BgColor="#cccccc";
				DrawJavaDragbox("",$xx+$ww,$y+4,5,$h,5, $BgColor, $fontColor,$id);
	 }
	 function   DrawDragArea(){
		        global $startY;
				$x=20;
				$y=$startY-20;
			    $BgColor="#224444";
			    $fontColor="#ffffff";
			    $Typestmp=getMysqlDataArray("scheduletype"); 
	            $arrT=filterArray( $Typestmp,0,"data3");//  array("進行中","已排程","驗證中","已完成");
			    $arr=returnArraybySort($arrT,2);
				array_Push( $arr,"刪除");
			    for($i=0;$i<count($arr);$i++){
				    $id="state=".$arr[$i];
				    DrawJavaDragArea($arr[$i],$x,$y,34,18,$BgColor,$fontColor,$id,9);
					$x+=35;
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
		      echo "</br></br></br></br></br></br></br></br></br></br></br></br></br></br>";
			  echo "</br></br></br></br></br></br></br></br></br></br></br></br></br></br>";  
			  echo "</br></br></br></br></br></br></br></br></br></br></br></br></br></br>";  
		
			  $n=0;
			  $sortArr=array();
              for($i=0;$i<count($user);$i++){
			       $sortArr= returnTask($tasks,$allChildArr,$user[$i],$sortArr);
				  // if($task==null) array_Push(  $nullArr,$task);
			  }
		      for($i=0;$i<count($sortArr);$i++){
			     echo "<br>".$i.">".$sortArr[$i] ;
			  }
              $sortArray =array();
			  $tmp=$tasks;
			  for($i=0;$i<count($sortArr);$i++){
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
			   ReLoad();
	 }
     function UpResort($postSort){
		      global $URL;
			  global $data_library,$tableName,$MainPlanData;
		      global $PostArray;
	          for($i=0;$i<count($postSort);$i++){
				//  echo "</br>".$i."_".$postSort[$i];
			     $WHEREtable=array( "data_type", "code" );
		         $WHEREData=array( "data",$postSort[$i]);
			     $Base=array("Line");
			     $up=array($i+1);
			     $stmt= MakeUpdateStmt(  $data_library,$tableName,$Base,$up,$WHEREtable,$WHEREData);
			   //  echo $stmt;
			     SendCommand($stmt,$data_library);		
			   }
			 //  JavaPost($PostArray,$URL); 
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
				 $hs=filterArray( $tasksc2,3, $hideCodes[$i]);
				 foreach($hs as  $task) 
				       HidePlan($hs[0][1], "g1");
			  }
 
	 }
	 function returnTask($tasks,$allChildArr,$user,$sortArr){
		        
	            for($i=0;$i<count($tasks);$i++){
					if($allChildArr[$i][0][8]==$user){
						if (!in_array($i,$sortArr))	array_Push( $sortArr,$i) ;
					}
			        if($allChildArr[$i][0][9]==$user){
				    	if (!in_array($i,$sortArr)) array_Push( $sortArr,$i) ;
					}
				
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
<?php //列印區間完成
     function printSc(){
	 		  // $tasksT=getMysqlDataArray("fpschedule"); 
			 //  $tasksT2=filterArray( $tasksT,0,"data"); 
			 //  $tasks= RemoveArray( $tasksT2,5, "工項"); 
			  // $tasks= RemoveArray( $tasks,5, "目標"); 
			   echoBr(12);
			   global $tasks, $tasksName;
			   global  $Bigtypes;
               $WeekDateEnd= $_POST["viewSc"];			   
 
			   $Range=ReturnDateRange($WeekDateEnd);
			   $Rangetasks=returnTaskInRang($tasks,$Range);
               for($i=0;$i<count($Bigtypes);$i++){
			        printType($Bigtypes[$i],$Rangetasks) ;
			   }
		
			   
	 }
     function printType($type,$Rangetasks){
	 	      $bool=false;
			  for($i=0;$i<count($Rangetasks);$i++){
				  if($Rangetasks[$i][10]==$type){
				     $code=$Rangetasks[$i][3];
			         $task= getRootTask($code);
					 if(!$bool){
					     echo "</br>"."[".$type."]" ;
						 $bool=true;
					 }
					  echo "</br>";
					 //if($type=="角色")
						 echo"[". $Rangetasks[$i][5]."]";
				     echo $task[3] ;
				  }
			   }
			   echo "</br>";
	 }
    

?>


<?php //日曆
     function DrawCallendarRange(){
		      global $finalTasks, $finalTasksT;
			  global $CalendarX, $startY;
			  global $DateWid;
			  global $DateRange;//開始 結束
			  global $Vacationdays;
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
					 $arr=  ReturnVacationDays($y,$m,$Vacationdays);
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
			  DrawTarget();
	 }
     function DrawTarget(){
	          global $target,$DateRange;
			  global  $CalendarX,$DateWid;
			  $y=190;
			  $fontColor="#ffffff";
			  $BgColor="#ff5555";
			  $Bg2="#5566ff";
			  $w=100;
			  for($i=0;$i<count($target);$i++){
				  $nd= explode("_",$target[$i][2]);
			      $passDay= getPassDays(array($DateRange[0],$DateRange[1],1), $nd);
				  $LocX= $CalendarX+$passDay*$DateWid;
				   $LocXup= $CalendarX+($passDay-7)*$DateWid;
				  $m=$target[$i][3];
				  //前推
				  DrawRect("",10,$fontColor,$LocXup,$y+12,$DateWid*7,1,$Bg2);
				  DrawRect("",10,$fontColor,$LocXup,$y+12,2,80,$Bg2);
				  DrawRect($m,10,$fontColor,$LocX,$y,$w,12,$BgColor);
				  DrawRect("",10,$fontColor,$LocX,$y+12,2,20,$BgColor);
				 
			  }
	 
	 }
	 function DrawDays($days,$LocX,$LocY,$w,$h,$arr,$ym){
		      global $URL;
		      //global $ValArray;
		      $x=$LocX;
			  $BgColor="#aaaaaa";
			  $fontColor="#ffffff";
	          for($i=1;$i<=$days;$i++){
				  $BgColor="#aaaaaa";
				  if ($arr[$i]==1)     $BgColor="#bbaaaa";
				  if ($arr[$i]==2)     $BgColor="#bb6666";
			      $ValArray= array(array("viewSc",$ym."_".$i));
				  
				  sendVal($URL,$ValArray,"submit2",$i,array($x,$LocY-10,$w-1,10),8,"#222222","#222222");
			      DrawRect($i,8,$fontColor,$x,$LocY+5,$w-1,15,$BgColor);
				  $id="startDay=".$ym."_".$i;
				  DrawJavaDragArea("",$x,$LocY+22,$w-1,$h*22,$BgColor,$fontColor,$id);
				  //DrawRect("",10,"#cccccc",$x,$LocY+22,$w-1,$h*22,$BgColor);
				  $x+=$w;
			  }
	 }
 
	 function filterDate($data,$dateNum,$startDate,$dateRange){ //static
		      $ar=array();
			  for($i=0;$i<count($data);$i++){
				  $d= returnPassDay($data[$i][2],$startDate);
				  if($d>0){
				   array_Push($ar,$data[$i]);
				  }
			  }
              return $ar;
	 }
	 function returnPassDay($date_1,$date_2){
              $Ds=explode("_",$date_1);
			  $De=explode("_",$date_2);
              $d1=mktime(0,0,0,$Ds[1],$Ds[2],$Ds[0]);
              $d2=mktime(0,0,0,$De[1],$De[2],$De[0]);
              $Days=round(($d1-$d2)/3600/24);
			  return $Days;
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
			 
		      $inputVal=array(array("text","DragID","DragID",10,420,$y,300,20,$BgColor,$fontColor,"" ,15),
			                   array("text","target","target",10,570,$y,200,20,$BgColor,$fontColor,"" ,20),
						       array("text","workingDays","workingDays",10,820,$y,200,20,$BgColor,$fontColor,"" ,6),
							   array("text","state","state",10,920,$y,200,20,$BgColor,$fontColor,"" ,6),
							   array("text","principal","principal",10,1020,$y,200,20,$BgColor,$fontColor,"" ,6),
							   array("text","outsourcing","outsourcing",10,1120,$y,200,20,$BgColor,$fontColor,"" ,6),
							   array("text","type","type",10,1220,$y,200,20,$BgColor,$fontColor,"" ,6),
							   array("text","selecttype","selecttype",10,1420,$y,200,20,$BgColor,$fontColor,"" ,6),
							   array("text","startDay","startDay",10,1320,$y,200,20,$BgColor,$fontColor,"" ,6),
 
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
			  $UpHidenVal=	addArray( $UpHidenVal,$typeArray);			
			  $inputVal=array(array("text","plan","計畫",10,20,$y,320,20,$BgColor,$fontColor,"" ,40),
			                  array("text","remark","jila單號",10,320,$y,100,20,$BgColor,$fontColor,"" ,5),
							//  array("text","line","行",10,420,$y,60,20,$BgColor,$fontColor,"" ,2),
						      array("text","workingDays","天數",10,520,$y,60,20,$BgColor,$fontColor,"" ,2),
                              array("submit","submit","",10,620,$y,100,20,$BgColor,$fontColor,"新增計畫" ,20),
			                  );		
							  
			  upSubmitform($upFormVal,$UpHidenVal, $inputVal);
	 }
	 function DeletPlan($Ecode){
		    
		      global $data_library,$tableName;
	          global $PostArray;
			  global $URL;
			  //RootTask
		      $WHEREtable=array( "data_type", "code" );
		      $WHEREData=array( "data",$Ecode );
			  $stmt=   MakeDeleteStmt($tableName,$WHEREtable,$WHEREData);
			  SendCommand($stmt,$data_library);
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
			 
				   echo "</br>";
							 
				      echo "子工項";
					  $p=$_POST["principal"];
					  $o=$_POST["outsourcing"];
					  $type=$_POST["type"];
			       	  if( $type=="--") $type="製作";
				      if($p=="--") $p="未定義";
                      if($o=="--") $o="未定義";
                      if($w=="")	$w=1; 
					   $WHEREData[8]= $p; 
				       $WHEREData[9]=$o; 
					   $WHEREData[6]=$w; 
					   $WHEREData[5]=$type;
				       $WHEREData[1]="f".$_POST["code"];
			           $WHEREData[3]= $_POST["code"];
					   $WHEREData[12]="";
					   $WHEREData[7]="未定義";
			          $stmt=  MakeNewStmtv2($tableName,$WHEREtable,$WHEREData); 
					   echo $stmt;
					      SendCommand($stmt,$data_library);
				
			   
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
			 // echoBr(4);
			  $stmt= MakeUpdateStmt(  $data_library,$tableName,$Base,$up,$WHEREtable,$WHEREData);
			   echo $stmt;
		     SendCommand($stmt,$data_library);		
			 
	 }
?>
<?php //bak
/*
 function CheckDrag_b(){
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
				  if ($state=="刪除"){
				  $Base=array("hide" );
		          $up=array( "g1" );
				  }
			   } 
			   if($_POST["principal"]!="state"){
			   }
			   ChangePlan($Ecode,$Base,$up);
			    ReLoad();
		     //  return;
	  }
	  */
?>
