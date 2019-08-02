<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>小日曆</title>
</head>
 
<body>
 
 
<?php
     include('CalendarApi.php');  
	 //  include('PubApi.php');
      DrawSCalender(222,222);
	  DrawFrom($BackURL);
	 function DrawSCalender($startX,$startY){
		//  echo "<div id=calendar></div>";
		  //$dateArray=="";
		  DrawSmallCalendar( $startX,$startY);
	      DrawDateCont($startX,$startY);
	      DrawBtu($startX,$startY);
	 }
 
     function DrawSmallCalendar($startX,$startY){
			 $dateArray=array( date("Y"),date("m"),date("d") );
		     $startweekly=GetMonthFirstDay($dateArray[0],$dateArray[1]);
			 $MonthDay=getMonthDay($dateArray[1],$dateArray[0]);
			 $x=$startX;
			 $y=$startY;
			 $w=200;
			 $h=20;
			 $fontSize=10;
			 $fontColor="#ffffff";
			 $BgColor="#000000";
			 $msg=$dateArray[0]."-".$dateArray[1] ;
			 $y+=20;
			 $x2=$x+1;
			  $other="line-height:".($h )."px; text-align:center;   ";
			 for($i=0;$i<7;$i++){
				 $BgColor="#444444";
				 $fontColor="#ffffff";
			     if($i==0 or $i==6) $fontColor="#ffaaaa";
				 $id="w".$i;
				 $info=$i;
                DrawIDRectEvent($x2,$y,$h-1,$h-1,$id,$info, $fontSize, $fontColor,$BgColor,$other,$Event,1);
                 
				 $x2+= $h;
			 }
			  $y+=20;
               DrawCalendarBase($x,$y);
	 }
	 
     function DrawDateCont($startX,$startY){
	          $x=$startX;
			  $y=$startY;
			  $h=20;
              $w=$h*7;
			  $fontSize=12;
			  $fontColor="#ffffff";
			  $BgColor="#000000";
			  $id="TargetDate";
			  $info="";  
			  $other="line-height:".($h )."px; text-align:center;   ";
			  DrawIDRectEvent($x,$y,$w,$h,$id,$info, $fontSize,$fontColor,$BgColor,$other ,$Event,1);
	 }
	 
	 
	 function DrawBtu($startX,$startY){
		 $clickfunction="MonthLeft(+1)";
	     $value=">";	
	     $x=$startX+100;
	     $y=$startY;
		 $w=12;
		 $h=14;
		 $fontSize=10;
		 $fontColor="#ffffff";
		 $BgColor="#44aa44";
	     drawButton($clickfunction,$value,$x,$y,$w,$h,$fontSize,$fontColor,$BgColor );
		 $clickfunction="MonthLeft(-1)";
		 $value="<";
		 $x=$startX+20;
		 drawButton($clickfunction,$value,$x,$y,$w,$h,$fontSize,$fontColor,$BgColor );
	 }
 
	 

	 function DrawFrom($BackURL){
		       echo   "<form id='update'  name='Show' action='".$BackURL."' method='post'  >";
			   echo "st<input id=startDay type=text name=startDay>";
			   echo "end<input id=EndDay type=text name=EndDay>";
			   echo "days<input id=Workdays type=text name=Workdays>";
			   echo "debug<input id=debug type=text name=debug>";
			   echo   "</form>";
	 }
?>
 
<script type="text/javascript">
         var dt=new Date();
		 var currentY=dt.getFullYear();
		 var currentM=dt.getMonth()+1;
		 var currentD=parseInt(dt.getDate());
		 var BaseDate=[ currentY, currentM, currentD];
		 var First_day=returnDay( new Date(currentY,currentM,0).getDay()) ;
		 var end_day=new Date(currentY,currentM,0).getDate();
		 var clickStart="";
		 var clickStartD=0;
		 var clickEnd="";
		 var Workdays=0;
		 DefuseDate();
		 UpCalendar();
	     function DefuseDate(){
		     var t = document.createElement("TargetDate");
                 t.innerHTML = currentY+"-"+currentM;
		     var s = document.getElementById("TargetDate");
			     s.appendChild(t);
		 }

        function MonthLeft(add){
		     var s = document.getElementById("TargetDate");
			 currentM+=add;
			 if(currentM>12){
				 currentM=1;
			     currentY+=1;
			 }
			 if(currentM<=0){
				 currentM=12;
			     currentY-=1;
			 }
			 end_day=new Date(currentY,currentM,0).getDate();
			 First_day=returnDay( new Date(currentY,currentM,0).getDay() ) ;
			 UpCalendar();
			 var info=currentY+"-"+currentM ;
			 s.innerHTML =info;
		}

		
		function ClickCalendar(e ){
			     e = e || window.event;
                 var elementId = (e.target || e.srcElement).id;
                 var BgColor='#44ddaa';
	        	 var o= document.getElementById(elementId) ;
				// document.getElementById("debug").value= o.;
			  	 var day=o.innerHTML;
				 if( clickEnd!=""){
				 	  clickStart="";
		              clickEnd="";
					  UpCalendar();
				 }
				 if(clickStart==""){
					 clickStartD=day;
					 clickStart= currentY+"-"+currentM+"-"+day;
					 document.getElementById("startDay").value=  clickStart;
				 }else{  
					  clickEnd=currentY+"-"+currentM+"-"+day;
			    	  BgColor='#99dd99';
				      document.getElementById("EndDay").value= clickEnd;
					  Workdays=getDays();
					  document.getElementById("Workdays").value= Workdays;
					  UpCalendarColor(clickStartD ,day);
				 }
		         o.style.backgroundColor=BgColor;
				 
		}
		function getDays(){
		        var startDayArray =clickStart.split("-");
		        var nowDayArray  =clickEnd.split("-");
			    var y= parseInt(startDayArray[0]);
			    var m= parseInt(startDayArray[1]);
			    var d=parseInt( startDayArray[2]);
			    var ny= parseInt(nowDayArray[0]);
			    var nm= parseInt(nowDayArray[1]);
			    var nd= parseInt(nowDayArray[2]);
			    var td=1; 
	       // document.getElementById("debug").value=  nd+">"+d ;
			    if( ny== y){//同一年
			       if( nowDayArray[1]> startDayArray[1]){//跨月
				       td+=new Date(y,m,0).getDate(); //getMonthDay($m,$y)-$d;
					   m+=1;
				       while(m<nm){
			                 td+=Date(y,m,0).getDate(); 
				             m+=1;
				       }
				       td+= nd;
					   return  td;
				    }
		          if( nm== startDayArray[1]){//同月
				       while(nd>d){
						     var dayw=returnDay( new Date(y,m,d).getDay()) ;
					
						     if(dayw!=0 && dayw!=6 ){
							    td+=1;
							   }
                                d+=1;
					    } 
 
					   return  td;
				  }
			  }
              return  td;
		}
        function UpCalendarColor(sd ,ed){
			     BgColor='#aaffaa';
				 		sd=parseInt(sd);
						ed=parseInt(ed);
						var log="";
		          for(var j=0;j<6;j++){
		              for(var i=0;i<7;i++){
						  var id=i+"-"+j;
						   var o= document.getElementById(id);
						   var c= parseInt( o.innerHTML);  //parseInt(o.value);
						   log+=id+":"+c;
						   if(c>=sd && c<=ed) o.style.backgroundColor=BgColor;
					     }
		           }
				    document.getElementById("debug").value= log;
		}
		
		
	    function UpCalendar(){ 
			      var w=0;
				  var s=0;
				  var BgColor='#eeeeee';
				  for(var j=0;j<6;j++){
		              for(var i=0;i<7;i++){
					      BgColor='#eeeeee';
						  if(i==0 || i==6) BgColor='#ffaaaa';
						  if(w==(currentD-1) && currentM== BaseDate[1]) BgColor='#aaffaa';
						  if(i==First_day && s==0) s=1;
						  if(s>=1) w+=1;
						  var id=i+"-"+j;
						  var o= document.getElementById(id);
						  o.innerHTML =w;
					      if(s==0 || s==2 ) BgColor='#888888';
						   o.style.backgroundColor=BgColor;
						  if(w>=end_day){
							  w=0;
							  s=2;
						  }
					 }
				  }
		}
		function returnDay(t){
	
			t-=2;
			if(t<0)t+=7;
			return t;
		}
 
 
		 
</script>
</body>

<?php

	   function DrawCalendarBase($sx,$sy){
           $BgColor="#eeeeee";
		   $fontSize=10;
		   $fontColor="#000000";
		   $h=18;
 
		   for($i=0;$i<7;$i++){
		       for($j=0;$j<6;$j++){
				   $id=$i."-".$j;
				   $x=$sx+$i*20+1;
				   $y=$sy+$j*20;
				   //echo $i;
				   $info=$i;
				   $other="line-height:".($h )."px; text-align:center;   ";
				   $BgColor="#eeeeee";
				   if($i==0 or $i==6)$BgColor="#ffaaaa";
				   $Event =" onmousedown='ClickCalendar()'; // onmousedown=alert('Hello'); ";
			       DrawIDRectEvent($x,$y,$h,$h,$id,$info, $fontSize,$fontColor,$BgColor,$other,$Event,1);
				   
			   } 
		   }
  }
?>

<?php
 
      function DrawIDRectEvent($x,$y,$w,$h,$id,$info, $fontSize,$fontColor,$BgColor,$other,$Event,$Layer ){
	          echo "<div ";
              echo $Event;
			  echo "id=".$id." ";
              echo " style='   " ;
			  echo " position:absolute;  top:".$y."px; z-index:-1; left:".$x."px;  width:".$w."px;height:".$h."px;";
			  echo  $other;
			    echo " z-index:".$Layer ."; ";
			  echo " font-size:".$fontSize."px; color:".$fontColor.";   background-color:".$BgColor.";  '  >";
	          echo $info;
			  echo "</div>";
	    }
 

     function drawButton($clickfunction,$value,$x,$y,$w,$h,$fontSize,$fontColor,$BgColor){
	          $but="<button onclick='".$clickfunction;
		      $but=$but."' style='width:".$w."px;height:".$h."px; ";
		      $but=$but." font-size:".$fontSize."px;";
		      $but=$but." color:".$fontColor."; background-color:".$BgColor.";";
		      $but=$but." '>".$value."</button>";
		      echo "<div style='position:absolute;  top:".$y."px; left:".$x."px;' >".$but ;
		      echo "</div>";
	 }
 
?>

<script type="text/javascript"> //備分
		/*
         document.body.onmousedown = function (e) {
                 e = e || window.event;
                 var elementId = (e.target || e.srcElement).id;
 
                 var BgColor='#44ddaa';
	        	 var o= document.getElementById(elementId) ;
		          o.style.backgroundColor=BgColor;
   
             recreate(elementId);
          }
		*/
</script>