<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>排程資料整理</title>
</head>
 <body bgcolor="#b5c4b1"> 
<?php //主控台
    include('PubApi.php');
    include('mysqlApi.php');
	include('CalendarApi.php');
    DefineDatas();
    CreatInputForm();
	submit_cont();
?>

<?php
      function DefineDatas(){
		       global $URL;
			   global  $data_library,$tableName;
			   $URL="scheduleSort.php";
		       $data_library="iggtaiperd2";
		       $tableName="fpschedule";
			   global  $tableName,$mergeSC,$nowSc,$oldSc;
			   defineName();
			  // $mergeSC =  $tableName."_merge";
		     //  $nowSc=  $tableName."_now";
			 //  $oldSc=  $tableName."_old";
			   global $Sc_now,$SC_Merge;
			   $SC_Merge=getMysqlDataArray( $tableName);
			   $Sc_now_T =getMysqlDataArray(  $nowSc);
			   $Sc_now=filterArray(   $Sc_now_T,0,"data"); 
			   global  $tables;
			   $tables=returnTables($data_library,$tableName);
	  }
	  function colectionTasks($end_y,$end_m){
	  		   global $Sc_now;
			   $colectSCs=array();
	           for($i=0;$i<count($Sc_now);$i++){
				   if(iSDateBefor($Sc_now[$i],$end_y,$end_m)){
				   array_push($colectSCs,$Sc_now[$i]);
				   }
				}
				moveTasks( $colectSCs);
	  }
	  function iSDateBefor($Sc,$end_y,$end_m){
		       $st=explode("_", $Sc[2]);
			   if($st[0]<=$end_y){
			      if( $st[1]<$end_m)return true;
			   }
			   return false;
	  }
	  function mergeTasks(){
	           global  $data_library,$tableName;
			   	   global   $mergeSC,$nowSc,$oldSc;
               $joinTables=array($nowSc,$oldSc);
               mergeTableData($data_library, $mergeSC,$joinTables);
	  }
	  function moveTasks( $colectSCs){
		  	   global  $data_library,$tableName;
			   global   $mergeSC,$nowSc,$oldSc;
			   global  $tables;
			   echo "</br>一共有".count($colectSCs)."單要搬移";
	           for($i=0;$i<count($colectSCs);$i++){
			       AddTask($colectSCs[$i],$oldSc, $tables);
				   RemoveTask ($colectSCs[$i],$nowSc);
				   echo "<br/>"."正在搬移:no.".$i.">>".$colectSCs[$i][1];
			   }
	  }
	  function AddTask($task,$tableName, $tables){
			   $WHEREtable=array();
			   $WHEREData=array();
			   for($i=0;$i<count( $tables);$i++){
				        array_push($WHEREtable, $tables[$i] );
					    array_push($WHEREData,$task[$i]);
		              }
			   $stmt=  MakeNewStmtv2($tableName,$WHEREtable,$WHEREData);
			   SendCommand($stmt,$data_library);
	  }
	  function RemoveTask ($task,$tableName){
		       $WHEREtable=array( "data_type", "code" );
		       $WHEREData=array( "data",$task[1] );
			   $stmt=MakeDeleteStmt($tableName,$WHEREtable,$WHEREData);
			   SendCommand($stmt,$data_library);
	  }
	  

?>

<?php
   

      function submit_cont(){
	           if( $_POST["submit"]=="移動")  colectionTasks($_POST["startY"],$_POST["startM"]);
			       if( $_POST["submit"]=="合併主資料")  mergeTasks(); 
		  	 //  if($_POST["startY"]=="" )return; 
		     
               //
	  }
	  
      function CreatInputForm(){
		      $x=20;
			  $y=10;
		      global $URL;
		      $upFormVal=array("up","up",$URL);
		      global  $data_library,$tableName;
			  
			  $UpHidenVal=array(array("tablename","fpschedule"),
			                    array("data_type","data"),
	                            );
		      $UpHidenVal=	addArray( $UpHidenVal,$typeArray);	
		      $inputVal=array(array("text","startY","startY",10,220,$y,300,20,$BgColor,$fontColor,"" ,4),
			                  array("text","startM","startM",10,320,$y,300,20,$BgColor,$fontColor,"" ,4),
							  array("submit","submit","submit",10,420,$y,300,20,$BgColor,$fontColor,"移動" ,4),
							  array("submit","submit","submit",10,520,$y,300,20,$BgColor,$fontColor,"合併主資料" ,4),
	                          );		 
		      upSubmitform($upFormVal,$UpHidenVal, $inputVal);
	 }
?>