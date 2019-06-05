 <!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>上傳資料</title>
</head>
<body bgcolor="#999999">
<?php
      include('PubApi.php');
	  include('mysqlApi.php');
      $data_library="iggtaiperd2";
      $table="calendardata";
      getIDmember();
	  
      if ($submit=="修改"){
	      upDateData();
	  }
	  if ($submit=="刪除"){
	      DeleteData();
	  }  
	  if ($submit=="新增"){
	      BuildNewData();
	  }  
	   echo " <script language='JavaScript'>window.location.replace('Calendar.php')</script>";
      function getIDmember(){
		     global $IDmember;
	         $members=getMysqlDataArray("members");
			 $memberId=array();
			 for($i=0;$i<count($members);$i++){
				 $memberId[$members[$i][0]]=$members[$i][1] ;
				 $IDmember[$members[$i][1]]=$members[$i][0] ;
			 }
	   }
	  
	  function DeleteData(){
		     global $data_library,$table,$UserName,$IDmember;;
	         global $ID, $CstartDay,$Csn ,$CworkDays,$CWork,$Project;
			 $WHEREtable=array("ID","sn");
		     $WHEREData=array($IDmember[$UserName],$Csn);
	         $stmt =MakeDeleteStmt($table,$WHEREtable,$WHEREData);
	         SendCommand($stmt,$data_library);
			
	  }
	  
	  function upDateData(){
		      global $data_library,$table;
	          global $ID, $CstartDay,$Csn ,$CworkDays,$CWork,$Project,$UserName,$IDmember;
			  $Base=array("Day","WorkDay","Info","Type","ID");
		      $up=array($CstartDay,$CworkDays,$CWork,$Project,$IDmember[$UserName]);
			  $WHEREtable=array("ID","sn");
			  $WHEREData=array($ID,$Csn);
			  $stmt= MakeUpdateStmt(  $data_library,$table,$Base,$up,$WHEREtable,$WHEREData);
			  SendCommand($stmt,$data_library);
	  }
	  function BuildNewData(){
	         global $data_library,$table,$IDmember;
	         global  $Year,$ID, $CstartDay,$Csn ,$CworkDays,$CWork,$Project,$CY,$Month,$UserName;
			 $WHEREtable=array("`Year`" ,"`Month`" ,"`Day`" ,"`WorkDay`" ,"`Info`" ,"`Type`" ,"`ID`" ,"`sn`" );
			// $sn=getLastSn( $data_library, $table,"sn");
		  	 $sn=getDBLastSn( $data_library, $table,"sn");
		     $WHEREData=array($Year,$Month,$CstartDay,$CworkDays,$CWork,$Project,$IDmember[$UserName],$sn);
	         $stmt= MakeNewStmt( $data_library,$table,$WHEREtable,$WHEREData);
	          SendCommand($stmt,$data_library);
			  setcookie("IGG_Project",$Project,time()+36000,"/");
		   //   echo " <script language='JavaScript'>window.location.replace('Calendar.php')</script>";
	  }

 


 
?>