 
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
			document.Show.info.value=DragID 
		if(DragID.indexOf("box")>-1){
		   document.getElementById( DragID).style.left=x;
		   
		   
		}else{
		     var tmp= DragID.split("scale")
	         var BaseID="box"+tmp[1]
			 
		     var w=  document.getElementById(DragID).style.left ;
			 	 document.Show.info.value=w
		     if(w<0)w=50
		
		     document.getElementById( BaseID).style.width= x;
	 	     document.getElementById( DragID).style.left=x;
			 
		}
		
		
		
 
	}
</script>
<?php
 
		Drawrect();
        for($i=0;$i<6;$i++){
		    $x=50;
	        DrawDragbox($x,100+($i*55),122,45,"#eea123","box".$i);
		    DrawDragbox($x+50,100+($i*55),20,45,"#1ea123","scale".$i);
		}

		function Drawrect(){
				for ($i=1;$i<=20;$i++){
			      $x=$i*50;
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
<input type="text" name="info" value="0" size="12">info
</form>
