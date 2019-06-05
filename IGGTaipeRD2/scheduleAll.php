
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>工作排程區V2</title>
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
<body bgcolor="#b5c4b1">
<?php
   $id=$_COOKIE['IGG_id'];
   include('PubApi.php');
   include('CalendarApi.php');  
   include('mysqlApi.php');
   include('scheduleApi.php');
   $data_library="iggtaiperd2";
   if($ProjectDataName=="") $ProjectDataName="rpgartschedule";
   defineData();   //定義基礎資料(scheduleApi)
   DrawUserData( 25, 0);   //使用者資料(PubApi)
   DrawMembersDragArea( 30,32); //美術群資料(PubApi)
   GetCalendarData(); //取得日曆資料(scheduleApi)
   DrawBaseCalendar(); //列印基礎日期資料(scheduleApi)
   DrawType();//進度表類型
   if($sendData=="DragWorkOrder")upData();//上傳表單
   ShowType();//依類別顯示
   CheckinputType();//判斷輸入
   
?>



 </body>
 </html>
 