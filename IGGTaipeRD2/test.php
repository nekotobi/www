 
<?php
    include('PubApi.php');
    include('CalendarApi.php');  
    include('mysqlApi.php');
    include('scheduleApi.php');
	include('javaApi.php');
	global $data_type;
  //  testNewAdd();
    $hexStr="#aaaaaa";
   $h= ColorDarker($hexStr,1.2);
	echo    $h;
  //  $rgb=array( 244, 222, 222);
	// echo rgb_to_Hex($rgb );
	//print_r( hex2RGB($hexStr )) ;
?>
<?php
    function testNewAdd(){
	            $data_library="iggtaiperd2";
			    $tableName="vtevent";
			 	$code=returnDataCode( );
				$sendVal=array("EventSerialNum"=>"4","code"=>$code,"EventName"=>"未定","data_type"=>"ver");
				FastAddMysQLData($data_library,$tableName,$code,$URL,$sendVal);
	}
    function TestSortGD(){
		global $HeroRes;
		     $data_library="iggtaiperd2";
			 $ResData=getMysqlDataArray("fpresdata");
			 $HeroResT=filterArray($ResData,0,"hero");
			 $HeroResT2=filterArray( $HeroResT,9,"");
			 $HeroRes= sortGDCodeArrays($HeroResT2 ,2 ,"true");
			 $remove=array("h0008","h0035");
			 for($i=0;$i<count($HeroRes);$i++){
				 if(in_array( $HeroRes[$i][2],  $remove)){
				    unset($HeroRes[$i]);
             		// array_delete($HeroRes,$HeroRes[$i]); 
				 }
			 }
		    $HeroRes=	 array_values($HeroRes);
			 	 for($i=0;$i<count($HeroRes);$i++){
			         echo "</br>".$i.">".$HeroRes[$i][2];
				 }
		/*
	      $Arr=array(array(1,"h006"),array(2,"h005"),array(2,"h001"));
		  //$LastSN=getLastGDSN($Arr,1);
		  $sort= sortGDCodeArrays($Arr ,1 ,"true");
		  print_r($sort);
		  //echo $LastSN;
		  */
	 }
     function printSc(){
	 		   $tasksT=getMysqlDataArray("fpschedule"); 
			   $tasksT2=filterArray( $tasksT,0,"data"); 
			   $tasks= RemoveArray( $tasksT2,5, "工項"); 
			   $tasks= RemoveArray( $tasks,5, "目標"); 
			   global  $tasksName;
			   $tasksName=filterArray($tasksT2,5, "工項"); 
	
			   $WeekDateEnd="2020_2_26";
			   $Range=ReturnDateRange($WeekDateEnd);
			   $Rangetasks=returnTaskInRang($tasks,$Range);
 
				
		 
			   for($i=0;$i<count($Rangetasks);$i++){
				   $code=$Rangetasks[$i][3];
			       $task= getRootTask($code);
				   echo "</br>"."[".$Rangetasks[$i][5]."]".$task[3] ;
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
	 
?>
<?php
   function testBut(){
	   echo  "val=".$_POST['val'];
	   echo "</br>";
       echo  "val2=".$_POST['val2'];
	   echo $_POST["submit"];
	   $URL="test.php";
	   $ValArray=array(array("val","txxt2"),array("val2","vvvxxt3"));
	   $Rect=array(420,50,100,20);
       sendVal($URL,$ValArray,"submit","testjava",$Rect, 12,  "#888888", "#ffffff" );
   }
   function Post2Java(){
        	if($_POST["submit"]=="")return;
			$PostArray=array("val","val2");
		    $URL="test.php";
			JavaPost($PostArray,$URL);
   }

   function TestCalendarRangeFunction(){
	      

   }
   function TestDayFunction(){
				  $startDay=array(2020,1,15);
			      $nowDayArray=array(2020,2,3);
				  $passDays= getPassDays($startDay,$nowDayArray);
				  echo $passDays;
				  $VacationDays= getVacationDays(array(2019,2020),array(12,1,2));
				  $realDays=ReturnWorkDaysV2($startDay[0],$startDay[1],$startDay[2],3,$VacationDays);
				  echo "=".$realDays;
   }
?>
<?php
   function updata2(){
	         $data_library="iggtaiperd2";
		     $tableName="fpschedule";
	          //global $data_library,$tableName;
 
				   $tables=returnTables($data_library,$tableName);
	               $t= count( $tables);
				   $WHEREtable=array();
				   $WHEREData=array();
		           for($i=0;$i<$t;$i++){
	       	            global $$tables[$i];
				        array_push($WHEREtable, $tables[$i] );
					    array_push($WHEREData,$$tables[$i]);
					    echo  "</br>".$i.">".$tables[$i].">".$$tables[$i]."]";
		              }
					$stmt=   MakeNewStmtv2($tableName,$WHEREtable,$WHEREData);
				    SendCommand($stmt,$data_library);
			//     echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";
		      	  echo $stmt;
   }

   function updata(){
	      $data_library="iggtaiperd2";
	       global $data_library,$tableName;
   $stmt=" INSERT INTO `fpschedule` ( `data_type` , `code` , `startDay` , `plan` , `line` , `type` , `workingDays` , `state` , `principal` , `outsourcing` , `selecttype` , `lastUpdate` , `remark` , `log` , `finLink` , `milestone` , `remark2` , `Price` , `group` )
VALUES (
'test2', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''
);";
    SendCommand($stmt,$data_library);
	echo $stmt;
   }
?>



<?php //排序測試
    function SortArray1(){
	        //$MainPlanDataT=getMysqlDataArray($tableName); 
		   ///$MainPlanData=filterArray($MainPlanDataT,0,"data");
	         $p="h0001dapco";
			  $a=  preg_replace('/\D/', '', $p); 
		//	$a= filter_var( $p, FILTERSANITIZENUMBERINT); 
               	echo $a;
	}


?>
<?php  //上傳縮圖
    function UpPic(){
	     global $submit;
	         if($submit=="")return; 
             echo $submit;			 
			 echo "xx";
			 echo $_FILES["file"]["name"];
			 echo $_FILES["file"]["tmp_name"];
			 $path="ResourceData/test.png";
			 $spicpath="ResourceData/tests.png";
			 move_uploaded_file($_FILES["file"]["tmp_name"],$path);
			 $cmd="convert    $path    -flatten -resize 64  $spicpath";
			 exec($cmd);
	}
    function UpPicEdit(){
		     global $submit;
			 if($submit!="")return;
		     $BackURL="test.php";
		     $x=200;
		     $y=100;
			 $w=300;
			 $h=300;
	         DrawPopBG($x,$y,$w,$h,"測試上傳資料" ,"12",$BackURL);
	         echo   "<form id='EditRes'  name='Show' action='".$BackURL."' method='post'  enctype='multipart/form-data'>";
			 $input="<input type=file name=file	id=file  size=60   >";
			 $y+=30;
		     DrawInputRect("圖檔","12","#ffffff", $x  ,$y,420,20, $colorCodes[4][2],"top", $input);
			 
			 
	         $submit ="<input type=submit name=submit value=上傳>";
			 DrawInputRect("","12","#ffffff",($x+150),$y+100 ,120,18, $colorCodes[4][2],"top",$submit );
			 
			 
	         echo "</form>";
	}



?>



<?php  //暫時

     function    testWarring(){//測試公式
		          global $VacationDays; //年 月 日
	              $startDay=array(2019,6,19);
				  $nowDayArray=array(2019,7,1);
				  $passDays= getPassDays($startDay,$nowDayArray);
				  echo   $passDays;
	
	 }
?>