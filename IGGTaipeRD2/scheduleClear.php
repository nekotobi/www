<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>工作分類排程區</title>
</head>
 
<body bgcolor="#b5c4b1">
<?php  //主控台
   include('PubApi.php');
   include('mysqlApi.php');
   defineData_schedule();
   function  defineData_schedule(){
   	       $tableName="fpschedule";
		   $MainPlanDataT=getMysqlDataArray($tableName); 
		   $MainPlanDataT2=filterArray($MainPlanDataT,0,"data"); 
		   $MainPlanDataT3=filterArray($MainPlanDataT2,5,"工項"); 
		   $mainCodeCollection=array();
		   for($i=0;$i<count($MainPlanDataT3);$i++){
			   array_push($mainCodeCollection, $MainPlanDataT3[$i][1]);
		   }
		  for($i=0;$i<count($MainPlanDataT2);$i++){
			   if($MainPlanDataT2[$i][10]!="總規劃" and $MainPlanDataT2[$i][5]!="工項" and $MainPlanDataT2[$i][5]!="目標" ){
		        if(!in_array($MainPlanDataT2[$i][3],$mainCodeCollection)){
				    echo "</br>";
					DeleteNouse($MainPlanDataT2[$i][1]);
			       echo $MainPlanDataT2[$i][1]."=".$MainPlanDataT2[$i][3]."=".$MainPlanDataT2[$i][10];
			     }
			  }
		  }
   }
   function DeleteNouse($code){
	      $data_library="iggtaiperd2";
	      $tableName="fpschedule";
		  $WHEREtable=array( "data_type", "code" );
          $WHEREData=array( "data",$code );
          $stmt= MakeDeleteStmt($tableName,$WHEREtable,$WHEREData);
		    SendCommand($stmt,$data_library);
   }
?>