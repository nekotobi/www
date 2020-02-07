<script type="text/javascript">
	function AllowDrop(event) {
		event.preventDefault();
	}

	function Drag(event) {
		event.dataTransfer.setData("text", event.currentTarget.id);
		document.Show.Dragid.value =event.currentTarget.id
	    y=document.getElementById(event.currentTarget.id).style.top
	}

	function Drop(event) {
		event.preventDefault();
		var DragID  = event.dataTransfer.getData("text");
		var tagetID = 	event.currentTarget.id
		var x= document.getElementById( tagetID).style.left;
		document.getElementById( DragID).style.left=x;
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
	    function DrawJavaDragbox($msg,$x,$y,$w,$h,$fontSize,$BgColor,$fontColor,$id){
	          echo "<div  id=".$id." ";
			  echo " draggable='true' ondragstart='Drag(event)' ";
              echo " style=' " ; //align=left
			  echo "position:absolute; top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; 
			        font-size:".$fontSize."px; color:".$fontColor."; background-color:".$BgColor."; '>".$msg;
	          echo "</div>";
	    }
	    function DrawJavaDragArea($msg,$x,$y,$w,$h,$BgColor,$fontColor,$id){
	          echo "<div  id=".$id." ";
			  echo " ondrop='Drop(event)' ondragover='AllowDrop(event)' ";
              echo  " style='   " ;
			  echo "position:absolute;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor.";  '  >".$msg;
	          echo "</div>";
	    }

?>