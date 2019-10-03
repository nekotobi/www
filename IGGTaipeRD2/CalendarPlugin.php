
<?php

	 function DrawSCalender($startX,$startY,$type){
		  DrawSmallCalendar( $startX,$startY);
	      DrawDateTitle($startX,$startY);
	      DrawBtu($startX,$startY);
		  DrawCalendarBase($startX,$startY+40);
		 // DrawOtherButton($startX,$startY);
		//  echo "<script> DefuseDate(".$type.");</script> "; 
		  echo "<script> DefuseDate('".$type."');</script> "; 
	 }
	 function DrawOtherButton($startX,$startY){
	         $x=$startX;
			 $y=$startY+20;
			 $h=20;
			 $fontSize=10;
			 $fontColor="#ffffff";
			 $BgColor="#000000";
			 $msg="delay";
             $other="line-height:".($h )."px; text-align:center;   ";
			 $Event=" onmousedown='MonthLeft(+1)'";
			 $id="delay";
			 DrawIDRectEvent($x+120,$y,$h-1,$h-1,$id,$info, $fontSize, $fontColor,$BgColor,$other,$Event,1);

	 }
	 function DrawSmallCalendar($startX,$startY){
			 $x=$startX;
			 $y=$startY+20;
			 $h=20;
			 $fontSize=10;
			 $fontColor="#ffffff";
			 $BgColor="#000000";
			 $msg="Calendar";
             $other="line-height:".($h )."px; text-align:center;   ";
			 $x2=$x+1;
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
          
	 }
	 function DrawDateTitle($startX,$startY){
	           $x=$startX;
			   $y=$startY;
			   $h=20;
               $w=$h*7;
			   $fontSize=12;
			   $fontColor="#ffffff";
			   $BgColor="#000000";
			   $id="TargetDate";
			   $info="TargetDate";  
			   $other="line-height:".($h )."px; text-align:center;   ";
			   DrawIDRectEvent($x,$y,$w,$h,$id,$info, $fontSize,$fontColor,$BgColor,$other ,$Event,1);
	 }
	 function DrawBtu($startX,$startY){
		      $Event=" onmousedown='MonthLeft(+1)'";
	          $x=$startX+110;
	          $y=$startY+2;
		      $w=12;
		      $h=14;
		      $fontSize=10;
		      $fontColor="#ffffff";
		      $BgColor="#44aa44";
			  $id="Left";
			  $info=">";
		      $other="line-height:".($h )."px; text-align:center;   ";
			  DrawIDRectEvent($x,$y,$w,$h,$id,$info, $fontSize,$fontColor,$BgColor,$other ,$Event,1);
		      $Event=" onmousedown='MonthLeft(-1)'";
		      $id="Right";
			  $info="<";
		      $x=$startX+20;
			  DrawIDRectEvent($x,$y,$w,$h,$id,$info, $fontSize,$fontColor,$BgColor,$other ,$Event,1);
	 }
	 function DrawCalendarBase($startX,$startY){
           $BgColor="#eeeeee";
		   $fontSize=10;
		   $fontColor="#000000";
		   $h=18;
		   for($i=0;$i<7;$i++){
		       for($j=0;$j<6;$j++){
				   $id=$i."-".$j;
				   $x=$startX+$i*20+1;
				   $y=$startY+$j*20;
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
?>
 <script type="text/javascript" src="CalendarPlugin.js" charset="UTF-8"></script>