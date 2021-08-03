 
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>美術test</title>
</head>
<body bgcolor="#b5c4b1">
<script type="text/javascript">
function Drop2Area(event) {
		event.preventDefault();
		var DragID  = event.dataTransfer.getData("text");
		var targetID =  event.currentTarget.id;
	    var tx= document.getElementById( targetID).style.left;
	    var x=tx.split("px");
	    var DragID_tmp= DragID.split("=");
	    var targetID_tmp= targetID.split("="); 
		var SID;
	    document.Show.DragID.value=  DragID;
	    document.Show.target.value=  targetID;
			        Show.submit();
		if(DragID_tmp.length>0){
		   document.Show.ECode.value=DragID_tmp[1];
		   document.Show.remark.value=DragID_tmp[2]; 
		   var SID=  new String( "code="+DragID_tmp[1]+"=startTime="+DragID_tmp[3]); 
		}
		if(targetID_tmp.length<2)return;
		   document.Show.DataName.value= targetID_tmp[0];
		   document.Show.Val.value= targetID_tmp[1];
	       document.Show.Etype.value="update";
		   if(DragID_tmp[2]=="workingDays"){
		      document.Show.DataName.value= "workingDays";
		      var sidx= document.getElementById(SID).style.left ;
		      var sidwx=sidx.split("px");
		      var x3=(parseInt(x[0]))-parseInt(sidwx[0]);
		      document.Show.Val.value =  parseInt(x3/ DragID_tmp[3])  ; 
		   }

	}

</script>
<?php
      require_once('/Apis/PubApi20.php');
	  $gd= "h0001";
	  $sn=(int) substr($gd, -3);
	  echo $sn;
	 // echo PAPI_changeColor("#aabbcc","0.4");
	   /*
	  require_once('/Apis/mysqlApi20.php');
	  require_once('/Apis/PubJavaApi.php');
	  require_once('/Apis/CalendarApi20.php');
   	  require_once('/Apis/ProjectApi.php');
	  ListCalendar();
	  defineData();
	   echo $_POST["DragID"]."<";
	   */
?>
<?php
  function defineData(){
	         global $inputsTextNames ;
		     global $URL;
			 global $ResdataBase,$typeDatabase;
			 global $inputsTextNames ;
			 global $WebSendVal;
		 	 $WebSendVal=array(array("xx","11"));
             $inputsTextNames=array("DragID","target","Etype","ECode","DataName","Val","remark");
			 $URL="ResTest.php";
			 $ResdataBase="xx";
			 JAPI_CreatJavaForm( $URL, $ResdataBase,$inputsTextNames,$WebSendVal );
  }
 
      function ListCalendar(){
		       global $ResPregresList;
		       global $Resdatas;
			   $startDate="2021-1-1";
			   $DateRange=2;
		       $LocX=225;
			   $LocY=80;
			   $wid=14;
			    $h=100;
			  // $h= count($Resdatas)* count($Resdatas)*10;
	           CAPI_DrawBaseCalendar($startDate,$DateRange,$LocX,$LocY,$wid,$h);
			   $x=30;
			   $y=40;
			   $w=100;
			   $h=20;
			   $id="ZZRes";
		       JAPI_DrawJavaDragbox( "1",$x,$y,$w,$h,8,"#ffcccc", "#cc8888",$id);
	  }
?>
