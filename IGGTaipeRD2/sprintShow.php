
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>Sprint訊息</title>
</head>
<script type="text/javascript">
	function AllowDrop(event) {
	    	event.preventDefault();
		    var overID = event.currentTarget.id;
				document.Show.sendData.value= document.getElementById(overID).style.backgroundColor;
		    if (document.Show.upID.value!=overID){
				document.Show.upID.value=overID
			    document.Show.upColor.value= document.getElementById(document.Show.upID.value).style.backgroundColor;   
			}	

			document.getElementById(overID).style.backgroundColor= 'rgb(211,111,111)' //"#ef7e62"
	}

	function Drag(event) {
		   event.dataTransfer.setData("text", event.currentTarget.id);
	       document.Show.DragID.value=event.currentTarget.id;
		   var DragID  = event.currentTarget.id;
		 	if(DragID.indexOf("Scale")>-1){
			   var tmp= DragID.split("-")
	     	   document.Show.startpx.value= tmp[4];
			   }
	}
    
     function  DragLeave(event) {
          var LeaveID = event.currentTarget.id;
		      document.getElementById(LeaveID).style.backgroundColor =  document.Show.upColor.value;  
	 }
	function Drop(event) {
		event.preventDefault();
		var DragID  = event.dataTransfer.getData("text");
		var targetID = 	event.currentTarget.id
		var tmp= targetID.split("-");
		var endpos= parseInt(document.Show.Xoffset.value=tmp[2]);
		var stpos=parseInt( document.Show.startpx.value);
		    document.Show.Xoffset.value=(endpos-stpos);
	        document.Show.sendData.value="DragWorkOrder";
            document.Show.DragArea.value=targetID;
		    document.Show.DragID.value=DragID;
			Show.submit();
	}
</script>
</script>
<body bgcolor="#b5c4b1">

<?php
      include('PubApi.php');
	  include('mysqlApi.php');
	  include('CalendarApi.php');
      DrawMainUI();     
      DrawList();
	   
?>

<?php //main
    function DrawMainUI(){
		global $data_library,$tableName;
        global $ey,$ed,$em,$edays,$enum,$emil;
		global $SprintStartDay,$SprintEndDay;
	    $data_library= "iggtaiperd2";
		$tableName="fpschedule";
		$x=20;
		$y=60;
	    $SprintStartDay=Array($ey,$em,$ed);
		$SprintEndDay =getPassDaysDay( $SprintStartDay,$edays);
	     echo "</br>".$i.">".$SprintEndDay[0]."-".$SprintEndDay[1]."-".$SprintEndDay[2] ;
		 echo "</br>";
	    $info="M".$emil."-Sprint".$enum."工單總覽";
	  //  DrawRect(  $info,"22","#ffffff","20","20","1400","30","#000000");
	}
	function DrawList(){
		global $data_library,$tableName;
        global $ey,$ed,$em,$edays,$enum,$emil;
      	$datasTmp= getMysqlDataArray($tableName); 
        $ScheduleArray= filterArray($datasTmp,0,"data");	
        getRangeArray($ScheduleArray );
	}	
	function getRangeArray($ScheduleArray ){
	    global $SprintStartDay,$SprintEndDay;
	    $fillerArray=Array();
		for($i=0;$i<count($ScheduleArray);$i++){
			$StartDay=explode("_",$ScheduleArray[$i][2]);
			$EndDay=getPassDaysDay($StartDay,$ScheduleArray[$i][6]);
			 if (isDateinDate($StartDay,$EndDay)=="true"){
 				 Array_Push($fillerArray ,$ScheduleArray[$i] );
			 }
		}
		$ListArray= Array("角色","怪物","UI");
	    ReSortArray($fillerArray,$ListArray,10);
		 
	}
	function ReSortArray($BaseArray,$SortArray,$num){
		// $rArray=array();
		 for($i=0;$i<count($SortArray);$i++){
			 echo "</br>".$SortArray[$i];
		     $tmp=filterArray($BaseArray,$num,$SortArray[$i]);	
		     ListAtmp( $tmp);
		   //  Array_Push($rArray,tmp);
		 }
		// return $rArray();
	}
	function ListAtmp($tmp){
		 for($i=0;$i<count($tmp);$i++){
			 $codeA=returnDataArray( $tmp,1,$tmp[$i][1]);//取得主資料array
			 $NameAdd= "[".$codeA[3] ;
		     echo "</br>".$NameAdd."-".$tmp[$i][1]."-";
		 }
	}
	function isDateinDate($StartDay,$EndDay){
		 global $SprintStartDay,$SprintEndDay;
		 $in="false";
		 if($StartDay[0]==$SprintStartDay[0]){//同年
		    //sprint開始結束同月
		    if($SprintStartDay[1]==$SprintEndDay[1]){
			  //如果開始同月
		      if($StartDay[1]==$SprintStartDay[1]){
			      if($StartDay[2]<=$SprintStartDay[2] && $EndDay[2]<=$SprintEndDay[2]  ){ //如果開始日小於s日
					   $in="true";
				  }
				 
			  }
		   }
		  }
		 
		 return $in;
	}
 
?>
