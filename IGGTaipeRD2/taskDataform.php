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
	ListTasks();
	DrawCallendar();
    ListTask();
?>

<?php //類別
      function DefineDatas(){
		       global $tasks,$tasksName;
		       $tasksT=getMysqlDataArray("fpschedule"); 
			   $tasksT2=filterArray( $tasksT,0,"data"); 
			   $tasks= RemoveArray( $tasksT2,5, "工項"); 
		       
			   $tasksName=filterArray(  $tasksT2,5, "工項"); 
      }
	  function DrawMonth(){
	          
	  }
      function DrawButtons(){
		       global $URL;
			   $URL="taskDataform.php";
			   global $typeName,$typeArray;
			   $subNameForWard="Type";
			   $typeName=array(array("負責人",8),array("大類別",10),array("類別",5) ,array("月份",-1));
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
				// echo $s.">".$n;
			     if($typeArray[$i][1]!="--")$finalTasks=filterArray( $finalTasks,$s,$n); 
			 }
	 }
	 function ListTask(){
		    global $finalTasks;
			$taskArray=$finalTasks;
		    $x=20;
			$y=140;
			$h=20;
			$fontColor="#ffffff";
			$BgColor="#000000";
			DrawRect("總計X".count($taskArray),10,$fontColor,$x,$y,205,$h,$BgColor);
		    for($i=0;$i<count($taskArray);$i++){
			    $y+=22;
				$x=20;
				$code=$taskArray[$i][3];
		        $name=getTaskName($code);
				$BgColor="#000000";
			    DrawRect($name,10,$fontColor,$x,$y ,100,$h,$BgColor);
				$x+=105;
				$type=$taskArray[$i][10];
				$BgColor="#777777";
				DrawRect($type,10,$fontColor,$x,$y,100,$h,$BgColor);
			    //工作時間
				$xx= returnposX($taskArray[$i][2]);
				$ww= $taskArray[$i][6];
				if($ww<5)$ww=5;
				DrawRect($taskArray[$i][6],10,$fontColor,$xx,$y,$ww,$h,"#22aaaa");
				//	 echo $name.">".$taskArray[$i][2]."</br>";
		    }
	 }
	 function DrawCallendar( ){
		      global $finalTasks;
			  global $URL;
	          $startY=2019;
			  $StartM=1;
			  $EndY=date("Y");
			  $EndM=date("m");
			  $y=$startY;
			  $m=1;
			  $x=230;
			  $y=140;
			  $h=20;
			  $BgColor="#aaaaaa";
			  $fontColor="#ffffff";
			  
              for($i=0;$i<count($finalTasks);$i+=20){
				  $sy=$startY;
				  $m=1;
			      $x=230;
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
		 $sx=230;
	     $d= explode("_",$date);
		 $y=  $d[0]-2019;
		 $m=$d[1];
	   
		 return $sx+ $y*30*12+($m-1)*30+$d[2];
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
	      
	 }
?>