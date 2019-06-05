<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>上傳</title>
</head>
<?php
      include('PubApi.php');
	  include('mysqlApi.php');
      $data_library="iggtaiperd2";
      $tableName=$ProjectDataName;
 
	  $BackURL="scheduleAll.php";
      echo  ">".$FinshDate.">";
       $ProjectE=$_COOKIE['IGG_Project'];
      if ($submit=="新增計畫"){
		  AddPlanData();
		 }
	  if ($submit=="新增"){
	      BuildNewData();
	  }
	  if ($submit=="修改計畫"){
	      EditPlanData();
	  }
	  if ($submit=="刪除計畫"){
          DeleteData();
	  }  
	//  if ($submit!="新增" & $submit!="新增計畫" & $submit!="修改計畫" ){
	   //  Editdata();
	//  }
	  function DeleteData(){
		      global $epy,$epm,$epd,$epLine,$epDay,$eptype,$getplan;
			  global $Upy,$Upm,$Upd,$UpLine;
			  global $data_library ;
			  global $BackURL,$tableName;
 	          global $process,$state ,$Artprincipal,$outsourcing,$worktype,$remark,$selecttype;
			  global $SelectType,$selectnum,$totallT;
			  global $sinput0,$sinput1,$sinput2,$sinput3,$sinput4;
			  $WHEREtable=array( "year", "month","startDay","Line");
		      $WHEREData=array( $Upy,$Upm,$Upd,$UpLine );
	          $stmt= MakeDeleteStmt($tableName,$WHEREtable,$WHEREData);
		      SendCommand($stmt,$data_library);
			    echo $stmt;
				  echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";
	  }
	  
	
	  function EditPlanData(){
		      global $epy,$epm,$epd,$epLine,$epDay,$eptype,$getplan;
			  global $Upy,$Upm,$Upd,$UpLine;
			  global $data_library ;
			  global $BackURL,$tableName;
 	          global $process,$state ,$Artprincipal,$outsourcing,$worktype,$remark,$selecttype;
			  global $SelectType,$selectnum,$totallT;
			  global $sinput0,$sinput1,$sinput2,$sinput3,$sinput4;
			  $BackURL=$BackURL."?SelectType=".$selectnum;
			  $WHEREtable=array( "year", "month","startDay","Line");
		      $WHEREData=array( $Upy,$Upm,$Upd,$UpLine );
			 
			 //計算總時數
			 
              if($selectnum>0){
			    $epDay=0;
			    $epDay+=$sinput0+$sinput1+$sinput2+$sinput3+$sinput4;
				$process=$sinput0."_".$sinput1."_".$sinput2."_".$sinput3 ;
			 }
 
			  $Base=array("year", "month", "startDay", "days", "plan", "Line", "type"
			            , "process" , "state" , "Artprincipal" , "outsourcing" , "worktype" , "remark" ,"selecttype" 
						  );
			  $up=array($epy,$epm,$epd,$epDay,$getplan,$epLine,$eptype
			            ,$process,$state ,$Artprincipal,$outsourcing,$worktype,$remark,$selecttype
						);
			  
			   $stmt= MakeUpdateStmt(  $data_library,$tableName,$Base,$up,$WHEREtable,$WHEREData);
			   echo $stmt;
			   SendCommand($stmt,$data_library);
			    echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";
	  }
  
     function AddPlanData(){
		     global $data_library;
	         global $Upy,$Upm,$Upd,$ProjectDataName;
			 global $Plan,$WorkDay,$type,$Line;
			 global $BackURL;
			 global $process,$state ,$Artprincipal,$outsourcing,$worktype,$remark,$selecttype;
			 global $BackURL,$tableName;
	         global $SelectType,$selectnum;
		     $BackURL=$BackURL."?SelectType=".$selectnum;
             if($type=="Sprint"){
			   $WHEREtable=array( "`year`" , "`month`" , "`startDay`" , "`endDay`" , "`days`" , "`sprintNum`" , "`milestone`" , "`Plan`"
							  );
			   $WHEREData=array($Upy,$Upm,$Upd,"","12",$Plan,$Line,$type  );
		       $stmt= MakeNewStmt( $data_library,"sprintdata",$WHEREtable,$WHEREData);
			 
			 
			 }
			 
			 if($type!="Sprint"){
			  $WHEREtable=array("`year`","`month`","`startDay`","`days`","`plan`","`Line`","`type`"
			                  , "`process`" , "`state`" , "`Artprincipal`" , "`outsourcing`" , "`worktype`" , "`remark`" ,"`selecttype`" 
							  );
			  $WHEREData=array($Upy,$Upm,$Upd,$WorkDay,$Plan,$Line,$type
			                  ,$process,$state ,$Artprincipal,$outsourcing,$worktype,$remark,$selecttype
			                  );
			  $stmt= MakeNewStmt( $data_library,$tableName,$WHEREtable,$WHEREData);
			 
			 }
		    
		     echo $stmt;
		     SendCommand($stmt,$data_library);
			 echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";
	 }
     function Editdata(){
		     global $submit;
 	         global $data_library ,$IDmember,$OrderID,$UpSn;
	         global  $GDSn  , $GDVer  , $ProposeDate  , $FinshDate,
			 $OrderID  , $file , $name  , $info  , $reference  ,
			 $Remarks  , $sn  , $type  , $ArtStartDay  , $WorkDay , 
			 $ArtFinDay  , $ArtVer  , $Artprincipal  , $project  , $outsourcing ;
		     global  $BackURL,$tablename;
 
			 $sn=$UpSn;
 
			 echo $OrderID;
		     $WHEREtable=array( "sn","OrderID" );
		     $WHEREData=array( $sn,$OrderID  );
			 if($submit=="工單完成"){
			 $Base=array("ArtFinDay","FinshDate");
			 $ArtFinDay=date("Y")."/".date("m")."/".date("d"); 
			 $up=array($ArtFinDay,$FinshDate);
			 }
			 if($submit=="修改"){
			 $Base=array("name","info","project","file","outsourcing","WorkDay","FinshDate");
			 $up=array($name,$info,$project,$file,$outsourcing,$WorkDay,$FinshDate);
			 }
			 if($submit=="撤回"){
				 $Base=array("ArtFinDay","ArtVer");
			     $up=array("","");
			 }
			 if($submit=="結單"){
			 $Base=array("ArtVer");
			 $ArtVer="Fin";
			 $up=array($ArtVer);
			 }
			 $stmt= MakeUpdateStmt(  $data_library,$tableName,$Base,$up,$WHEREtable,$WHEREData);
			 echo $stmt;
			 SendCommand($stmt,$data_library);
             echo " <script language='JavaScript'>window.location.replace('scheduleAll.php')</script>";
 
      }	 
 function BuildNewData(){
	         global $data_library,$tableName,$IDmember;
	         global  $GDSn  , $GDVer  , $ProposeDate  , $FinshDate  ,
			 $OrderID  , $file , $name  , $info  , $reference  ,
			 $Remarks  , $sn  , $type  , $ArtStartDay  , $WorkDay , 
			 $ArtFinDay  , $ArtVer  , $Artprincipal  , $project  , $outsourcing ;
			 $WHEREtable=array(
	         "`GDSn`" , "`GDVer`" , "`ProposeDate`" , "`FinshDate`" , 
			 "`OrderID`" , "`file`" , "`name`" , "`info`" , "`reference`" , 
			 "`Remarks`" , "`sn`" , "`type`" , "`ArtStartDay`" , "`WorkDay`" ,
			 "`ArtFinDay`" , "`ArtVer`" , "`Artprincipal`" , "`project`" , "`outsourcing`" );
		  	 echo ">".$OrderID;
			 $sn=getDBLastSn( $data_library, $tableName,"sn");
             $ArtStartDay=date("Y")."/".date("m")."/".date("d"); 
			 $ProposeDate=date("Y")."/".date("m")."/".date("d"); 
			 $WorkDay=4;
			 $WHEREData=array(
			 $GDSn  , $GDVer  , $ProposeDate  , $FinshDate  ,
			 $OrderID  , $file , $name  , $info  , $reference  ,
			 $Remarks  , $sn  , $type  , $ArtStartDay  , $WorkDay , 
			 $ArtFinDay  , $ArtVer  , $Artprincipal  , $project  , $outsourcing );
		     $stmt= MakeNewStmt( $data_library,$tableName,$WHEREtable,$WHEREData);
			 echo $stmt;
	         SendCommand($stmt,$data_library);
		      echo " <script language='JavaScript'>window.location.replace('scheduleAll.php')</script>";
	  }
	  		 //0GDSn 1GDVer 2ProposeDate 3FinshDate 4progress 5file 6name 7info 8reference 
		    //9Remarks 	10sn 11type  12ArtStartDay 	13WorkDay 	14ArtFinDay 	15ArtVer 	16Artprincipal 17project 18out
?>