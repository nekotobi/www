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
			   global   $nowSc,$oldSc;
		      /// $nowSc="_now";
			   $nowSc="";
			   $oldSc="_old";
			   global $Sc_now;
			   $Sc_now_T =getMysqlDataArray( $tableName.$nowSc);
			   $Sc_now=filterArray(   $Sc_now_T,0,"data"); 
			   
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
	  
	  function moveTasks( $colectSCs){
		  	   global  $data_library,$tableName;
		       global   $nowSc,$oldSc;
		       $now_table=$tableName.$nowSc;
			   $old_table=$tableName.$oldSc;
		       $tables=returnTables($data_library,$tableName);
			   echo "</br>一共有".count($colectSCs)."單要搬移";
	            for($i=0;$i<count($colectSCs);$i++){
			  // for($i=0;$i<1;$i++){
			       AddTask($colectSCs[$i],$old_table, $tables);
				   RemoveTask ($colectSCs[$i],$now_table);
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
			   //echo "</br>";
			  // echo $stmt;
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
	           if( $_POST["submit"]!="移動")return; 
			 //  if($_POST["startY"]=="" )return; 
		       colectionTasks($_POST["startY"],$_POST["startM"]);
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
	                          );		 
		      upSubmitform($upFormVal,$UpHidenVal, $inputVal);
	 }
?>