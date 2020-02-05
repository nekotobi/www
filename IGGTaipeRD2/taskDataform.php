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
    DrawButtons();
	DefineDatas();
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
			   $tableName="fpschedule";
			   $data_library="iggtaiperd2";
		       $tasksT=getMysqlDataArray("fpschedule"); 
			   $tasksT2=filterArray( $tasksT,0,"data"); 
			   $tasks= RemoveArray( $tasksT2,5, "工項"); 
			   $tasksName=filterArray(  $tasksT2,5, "工項"); 
      }
 
      function DrawButtons(){
		       global $URL;
			   $URL="taskDataform.php";
			   global $typeName,$typeArray;
			   $subNameForWard="Type";
			   $typeName=array(array("負責人",8),array("大類別",10),array("類別",5) ,array("編輯",-1));
			   $typeArray=array(); 
			   for($i=0;$i<count($typeName);$i++){
				   $n=$subNameForWard.$i;
				    $s= $_POST[$n];
			       array_push( $typeArray,array($n,$s));
			   }
			   $Rect=array(20,40,60,20);
			   //工作人
			   $Typestmp=getMysqlDataArray("members"); 
			   $TypeT=filterArray(  $Typestmp,3,"Art"); 
			   $Type= returnArraybySort($TypeT,1);
			   DrawButton($Type,$Rect,$URL,0,$typeArray);
			
	           //大類
			   $Rect[1]+=22;
			   $Typestmp=getMysqlDataArray("scheduletype"); 
			   $TypeT=filterArray(  $Typestmp,0,"data"); 
			   $TypeS=sortArrays( $TypeT ,5 ,"true");
			   $Type= returnArraybySort($TypeS,2);
			   DrawButton($Type,$Rect,$URL,1,$typeArray);
		   	   
			   //工類
			   $Rect[1]+=22;
			   $TypeT=filterArray(  $Typestmp,0,"data2"); 
			   $TypeS=sortArrays( $TypeT ,5 ,"true");
			   $Type= returnArraybySort($TypeS,2);
			   DrawButton($Type,$Rect,$URL,2,$typeArray);
			   //編輯類別
			   $Rect[1]+=22;
			   $Type=array("快速新增","編輯隱藏");
			   DrawButton($Type,$Rect,$URL,3,$typeArray);
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
		  if($typeArray[3][1]=="快速新增"){
			 fastTask();
		     return;
		  }
	      ListTasks();
          ListTask();
	      DrawCallendar(); 
	     
	  }
?>

<?php //內容
     function ListTasks(){
	         global $tasks;
		     global $typeName,$typeArray;
			 global $finalTasks;
			 $finalTasks=$tasks;
			 for($i=0;$i<3;$i++){
				 $s=$typeName[$i][1];
				 $n=$typeArray[$i][1];
			     if($typeArray[$i][1]!="--")$finalTasks=filterArray( $finalTasks,$s,$n); 
			 }
	 }
	 function ListTask(){
		    global $finalTasks;
			global $CalendarX;
			$taskArray=$finalTasks;
		    $x=20;
			$y=140;
			$h=20;
			$fontColor="#ffffff";
			$BgColor="#000000";
			DrawRect("總計X".count($taskArray),10,$fontColor,$x,$y,255,$h,$BgColor);
		    for($i=0;$i<count($taskArray);$i++){
			    $y+=22;
				$x=20;
				$code=$taskArray[$i][3];
				//工單名
		        $name=getTaskName($code);
				$BgColor="#000000";
			 
			  
			    DrawRect($name,10,$fontColor,$x,$y ,149,$h,$BgColor);
				   //隱藏
			    DrawHide($code,$taskArray[$i][18],$x,$y);
			
			
				$x+=150;
				//負責人
					$BgColor="#777777";
				$principal=$taskArray[$i][8];
				DrawRect($principal,10,$fontColor,$x,$y,49,$h,$BgColor);
				//大類
				$x+=50;
				$type=$taskArray[$i][10];
				DrawRect($type,10,$fontColor,$x,$y,49,$h,$BgColor);
				//小類
				$type2=$taskArray[$i][5];
				$x+=50;
				DrawRect($type2,10,$fontColor,$x,$y,49,$h,$BgColor);
				$x+=50;
				$CalendarX=$x;
			    //工作時間
				DrawTime($taskArray,$i,$y);
				
		    }
			
	 }
	 function DrawHide($code,$hide,$x,$y){
		      global  $typeArray;
			  global  $URL;
			  $Rect=array($x+2,$y+2,5,5);
			  
			  $fontColor="#552222";
			  $BgColor="#224422";
			  if($hide=="g1")$BgColor="#444444";
			  
		      $ValArray= addArray($typeArray,array("hide",$code));
	          sendVal($URL,$ValArray,"Submit","_",$Rect,8,$BgColor);
	 }
	 function DrawTime($taskArray,$i,$y){
	            $fontColor="#ffffff";
		     	$BgColor="#000000";
				$xx= returnposX($taskArray[$i][2]);
				$ww= $taskArray[$i][6];
				if($ww<5)$ww=5;
				DrawRect($taskArray[$i][6],10,$fontColor,$xx,$y,$ww,$h,"#22aaaa");
	 }
	 function DrawCallendar( ){
		 	  global $CalendarX;
		      global $finalTasks;
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
	 function returnposX($date){
		   global $CalendarX;
	 
	     $d= explode("_",$date);
		 $y=  $d[0]-2019;
		 $m=$d[1];
	   
		 return $CalendarX+ $y*30*12+($m-1)*30+$d[2];
	 }
	 function getTaskName($code){
 
	        global  $tasksName;
			 
			for($i=0;$i<count($tasksName);$i++){
			    if($tasksName[$i][1]==$code){
					return $tasksName[$i][3];
				}
			}
			return "ss";
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
	 function addArray($Base,$Add){
	         $a=$Base;
			 for($i=0;$i<count($Add);$i++){
			     array_push($Base,$Add[$i]);
			 }
			 return $Base;
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
				   $stmt=   MakeNewStmtv2($tableName,$WHEREtable,$WHEREData);
				   echo $stmt;
				   SendCommand($stmt,$data_library);
			      // echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";
		    
	 
	 }
?>