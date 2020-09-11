<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>VT工單整理</title>
</head>
<body bgcolor="#b5c4b1"> 
<?php //主控台
     require_once('PubApi.php');
	 require_once('mysqlApi.php');
	 require_once('scheduleApi.php');
	 require_once('CalendarApi.php');
     require_once('VTApi.php');
	 require_once('javaApi.php');
	 
     DefineBaseData();
 	 DrawButtons();
	 ListTask();
	  CheckSubmit();
?>

<?php //基礎
      function AutoSwitch(){
	           global $typeVal;
			   global $typeArray,$typeRangeArray;
			   echo $typeVal[0][1];
			    echo $typeVal[1][1];
	           if($typeVal[0][1]!=""  ){
				   if($typeVal[0][1]=="type_5")$typeVal[1][1]=="製作";
				   if($typeVal[0][1]=="selecttype_10")$typeVal[1][1]=="--";
	           }
			      
	  }
      function DefineBaseData(){
		       global $URL;
			   $URL="setTaskType.php";
			   global $typeArray,$typeRangeArray;
	           global $typeVal;
			   		  $typeArray =array("type","state","time" );
			          $typeRangeArray =array(array("type_5","selecttype_10","principal_8"),
			                           array("製作","--","未定義"),
									   array("-30","-15","+0","+15","+30"),
									   array("未定義","--"),
			                           );
			   global $typeVal;
			   for($i=0;$i<count($typeArray);$i++){
			       $typeVal[$i]=array($typeArray[$i],$_POST[$typeArray[$i]]);
				   if($_POST[$typeArray[$i]]=="")  $typeVal[$i]=array($typeArray[$i],$defuseData[$i]);
			   }
			  // AutoSwitch();
	           $scDataB=getVTSCData("now");
               $s1T= filterArray($scDataB,0,"data");
			   $TaskNames= filterArray($scDataB,5,"工項");
			   $s1=  RemoveArray( $s1T,5,"工項");
			   //時間範圍
		       $Date2= date("Y-m-d");
		       $startDate=   returnPassDate( date("Y-m-d") ,$typeVal[2][1]);
			   $n=explode("_", $typeVal[0][1]);
			   $s2= filterArray($s1,$n[1],$typeVal[1][1]);   
		       $Range=array(0,1);
		       global $Tasks;
		       $Tasks =  getSCRange($s2, $startDate,$Range,10);
			   //取得選擇
               global $selectype;
               $type1="data2";
               if($n[0]=="selecttype") $type1="data";		   
			   $selectype= getSCTypes($type1);
		   	   global $startY;
			    $startY=120;
	  }
	  
	  function DrawButtons(){
               global $typeArray,$typeRangeArray; 
			    $y=40;
			    for($i=0;$i<count($typeArray) ;$i++){
			         DrawButton($typeArray[$i],$i,$typeRangeArray[$i],20,$y+$i*20);
			     }	 	
				 
   	  } 
	  function DrawButton($typeName,$tn,$typeRangeArray,$x,$y){
			 $type=$_POST[$typeName];
			 global $URL;
		     global $typeVal;
			 $sendarr=$typeVal;
			 $fontColor="#ffffff";
	         for($i=0;$i<count($typeRangeArray) ;$i++){
			      $BgColor="#111111";
				  if($typeVal[$tn][1]==$typeRangeArray[$i])$BgColor="#aa1111";
				     $sendarr[$tn]= array($typeName,$typeRangeArray[$i])  ;
				     sendVal($URL, $sendarr,"change",$typeRangeArray[$i],array($x,$y,46,18),10,$BgColor, $fontColor);
				     $x+=50;
			 }				 
	}
	
?>
<?php //上傳
      function CheckSubmit(){
	           if($_POST["taskCode"]!="")Change();
	  }
	  function Change(){
		       global $SC_tableName_now,$SC_tableName_old,$SC_tableName_merge;
	           DefineVTTableName() ;
		       $tableName=	 $SC_tableName_now;
		       $WHEREtable=array("data_type", "plan");					 
               $WHEREData=array("data",$_POST["taskCode"]);	
			   $n=explode("_",$_POST["type"]);
			   $Base=array( $n[0]);
			   $up=array($_POST["selectype"]);
	           $stmt= MakeUpdateStmtv2(  $tableName,$Base,$up,$WHEREtable,$WHEREData);	
			   SendCommand($stmt,$data_library);
			   saveUpdateTime("","");
			   ReLoad();
	  }
      function ReLoad(){
	    	    global  $URL;
			    global $typeVal;
				JavaPostArray($typeVal,$URL); 
	}
?>


<?php //列印
       function ListTask(){ 
		         global $startY;
				 global $Tasks;
		         for($i=0;$i<count($Tasks);$i++){
				     $title= findTaskTitle($Tasks[$i][3])."[".$Tasks[$i][3]."]".$Tasks[$i][10];
				     DrawRect($title,"10","#ffffff","20", $startY,"500","18","#000000");
					  ListTypeButtom($Tasks[$i][3], $startY);
					  $startY+=20;
			    }
				
	   }
	   function ListTypeButtom($code,$y){
	            global $selectype;
				global $URL;
				global $typeVal;
				$sendarr=$typeVal;
				$BgColor="#99aa66";
				$x=520;
			    for($i=1;$i<count($selectype) ;$i++){
				    $sendarr[3]= array("taskCode",$code);
				    $sendarr[4]= array("selectype",$selectype[$i]);
    			    sendVal($URL, $sendarr,"change",$selectype[$i],array($x,$y,46,18),10,$BgColor, $fontColor);
					$x+=50;
				}
				
	   }
?>
 