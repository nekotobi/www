<script type="text/javascript">
    var upId="xx";
	var BaseColor="";
	var BGColor="";
	var startX=0;
	var StartWid=0;
	function AllowDrop(event) {
		    event.preventDefault();
			var OverID= event.currentTarget.id;

	        if(upId=="xx")upId =OverID;
			if(BGColor=="")BGColor=  document.getElementById(OverID).style.backgroundColor ;
		    if(upId!=OverID){
	            document.getElementById(upId).style.backgroundColor=BGColor;
			    upId=OverID;
				BGColor=  document.getElementById(OverID).style.backgroundColor ;
	          } 
		    document.getElementById( OverID).style.backgroundColor="#ffaaaa";
	}
 
	function Drop2Area(event) {
		event.preventDefault();
		var DragID  = event.dataTransfer.getData("text");
		var targetID =  event.currentTarget.id;
		var tmp= DragID.split("=");
	    var tmp2= targetID.split("="); 
	    document.Show.DragID.value=DragID ;
	    document.Show.target.value= targetID;
	     Show.submit();

	}
    
	function Drag(event) {
	    event.dataTransfer.setData("text", event.currentTarget.id);
	    var DragID  = event.dataTransfer.getData("text");
	    if(BaseColor=="") BaseColor= document.getElementById(DragID).style.backgroundColor;
		if(startX==0)startX= document.getElementById(DragID).style.left;
	    y=document.getElementById(event.currentTarget.id).style.top;
	   
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
        function DrawJavaDragPic($pic,$x,$y,$w,$h,$id){
		     	 echo "<div id=".$id  ;
				 echo " draggable='true' ondragstart='Drag(event)' ";
				 echo "' style='position:absolute; 
				       top:".$x."px;Left:".$y."px; width:".$w."px;height:".$h."px;
				      '><img src=".$pic." width=".$w." height=".$h."></div>";
	   }
	    function DrawJavaDragbox($msg,$x,$y,$w,$h,$fontSize,$BgColor,$fontColor,$id){
	          echo "<div  id=".$id." ";
			  	//    echo " ondragover='alert(xxx)' ";
 
			  echo " draggable='true' ondragstart='Drag(event)' ";// ondragend='leave(event)' ";
              echo " style=' " ; //align=left
			  echo "position:absolute; top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; 
			        font-size:".$fontSize."px; color:".$fontColor."; background-color:".$BgColor."; '>".$msg;
	          echo "</div>";
	    }
	    function DrawJavaDragArea($msg,$x,$y,$w,$h,$BgColor,$fontColor,$id,$fontSize=10){
	          echo "<div  id=".$id." ";
			 // echo " ondragenter='enter(event)' ";
			  echo " ondrop='Drop2Area(event)'  ondragover='AllowDrop(event)' ";//  ondragleave = 'leave(event)' ";
              echo  " style='   " ;
			  echo "position:absolute;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor."; ";
	          echo  " font-size:".$fontSize."px ; color:".$fontColor."; ";
              echo	 "'  >";
			  echo $msg;
	          echo "</div>";
	    }

?>