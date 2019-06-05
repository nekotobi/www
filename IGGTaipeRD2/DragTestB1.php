
<script type="text/javascript">
	function AllowDrop(event) {
		event.preventDefault();
	}

	function Drag(event) {
		event.dataTransfer.setData("text", event.currentTarget.id);
	}

	function Drop(event) {
		event.preventDefault();
		var data = event.dataTransfer.getData("text");
		event.currentTarget.appendChild(document.getElementById(data));
		//document.Show.MouseX.value = tempX
	}
}
</script>
 
 
<?php
 
		function Drawrect(){
				for ($i=1;$i<=20;$i++){
			        $x=$i*55;
		         	$id="ar".$i;
		             DrawDragArea($x,100,50,50,"#2ea123", $id);
		       }
		}
	
	   function DrawDragbox($x,$y,$w,$h,$BgColor,$id){
	          echo "<div  id=".$id." ";
			  echo " draggable='true' ondragstart='Drag(event)' ";
              echo  " style='" ;
			  echo "position:absolute;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor."; '> ";
	          echo "</div>";
	   }
	   function DrawDragArea($x,$y,$w,$h,$BgColor,$id){
	          echo "<div  id=".$id." ";
			  echo " ondrop='Drop(event)' ondragover='AllowDrop(event)' ";
              echo  " style='" ;
			  echo "position:absolute;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor."; '>ss";
	          echo "</div>";
	   }

?>
</style>
<form name="Show">
<p align="center"><input type="text" name="MouseX" value="0" size="4">X2 <input type="text" name="MouseY" value="0" size="4">Y </form>    