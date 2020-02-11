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
<script type="text/javascript"> //傳遞變數
    function post_to_url(path, params, method) {
    method = method || "post";  
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);
    for(var key in params) {
        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", key);
        hiddenField.setAttribute("value", params[key]);
        form.appendChild(hiddenField);
    }
    document.body.appendChild(form);  
    form.submit();
}
</script>
<?php  //post
        function JavaPost($PostArray,$URL){
			$params="{";
			for($i=0;$i<count($PostArray);$i++){
				$n=$PostArray[$i];
			    $params=$params."'".$PostArray[$i]."':'".$_POST[$PostArray[$i]]."'";
				if(count($PostArray)>1) $params=$params.",";
			}
			$params=$params."}";
		    $javaCom=  "post_to_url('".$URL."', ".$params.");";
            echo " <script language='JavaScript'>".$javaCom."</script>"; 
      }
?>
<?php  //Drag  
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