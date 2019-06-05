 
<script type="text/javascript">
	function AllowDrop(event) {
		event.preventDefault();
	}

	function Drag(event) {
		event.dataTransfer.setData("text", event.currentTarget.id);
		document.Show.Dragid.value =event.currentTarget.id
	    y=document.getElementById(event.currentTarget.id).style.top
		//document.setElementById("ar3").offsetTop=425; 
		//for(i=1;i<20;i++){
		    //  var testid= "ar"+i;
			//  document.Show.testid.value=testid
		    //  document.getElementById(testid).style.top=y
	  //   }
	 	//var tmp= event.currentTarget.id
		//document.setElementById("ar3").offsetTop=425; 
	}

	function Drop(event) {
		event.preventDefault();
		var DragID  = event.dataTransfer.getData("text");
		var tagetID = 	event.currentTarget.id
		var x= document.getElementById( tagetID).style.left;
		document.getElementById( DragID).style.left=x;
		
		  //document.Show.Dragar.value = 	event.currentTarget.id
		//  event.currentTarget.appendChild(document.getElementById(data)) 
        //  document.Show.artop.value = 	document.getElementById(event.currentTarget.id).offsetTop
	   //   document.Show.boxtop.value = 	document.getElementById(data).offsetTop
	    //document.Show.id.value =event.currentTarget.id
		//var id= event.currentTarget.id
		// document.Show.offsetTop.value=document.getElementById(id).offsetTop; 
		// document.Show.offsetLeft.value=document.getElementById(id).offsetLeft; 
		//var  offsetTop=document.getElementById(id).offsetTop; 
	}
     document.captureEvents(Event.MOUSEMOVE)
     document.onmousemove = getMouseXY;
     function getMouseXY(e) {
         document.Show.MouseX.value = e.pageX
         document.Show.MouseY.value = e.pageY
         return true
     }
</script>
<?php
 
		 Drawrect();
        for($i=0;$i<6;$i++){
	     DrawDragbox(55,100+($i*55),122,45,"#eea123","box".$i);
		}
	    for($i=0;$i<6;$i++){
	     DrawDragbox(55,100+($i*55),122,45,"#eea123","scale".$i);
		}
	 
 
  
		function Drawrect(){
				for ($i=1;$i<=20;$i++){
			      $x=$i*55;
		      	  $id="ar".$i;
		          DrawDragArea($x,100,50,330,"#eeeeee", $id);
				}
		}
		 function DrawDragbox($x,$y,$w,$h,$BgColor,$id){
	          echo "<div  id=".$id." ";
			  echo " draggable='true' ondragstart='Drag(event)' ";
              echo " style=' " ; //align=left
			  echo "position:absolute; top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor."; '>cc ";
	          echo "</div>";
	   }
	    function DrawDragArea($x,$y,$w,$h,$BgColor,$id){
	          echo "<div  id=".$id." ";
			  echo " ondrop='Drop(event)' ondragover='AllowDrop(event)' ";
              echo  " style='   " ;
			  echo "position:absolute;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor.";  '  >qq";
	          echo "</div>";
	   }

?>

 
<form name="Show">
<p align="center">
<input type="text" name="artop" value="0" size="4">artop
<input type="text" name="boxtop" value="0" size="4">boxtop
<input type="text" name="y" value="0" size="4">y 
<input type="text" name="offsetLeft" value="0" size="4">Y 
<input type="text" name="id" value="0" size="12">X 
</form>
