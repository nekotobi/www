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
	    var tx= document.getElementById( targetID).style.left;
	    var x=tx.split("px");
	    var tmp= DragID.split("=");
	    var tmp2= targetID.split("="); 
	     if( tmp[0]=="S"){
			 var E= new String( "E="+tmp[1]+"="+tmp[2]+"="+tmp[3]);
			 document.Show.target.value=DragID;
			 var x3=(parseInt(x[0])+  parseInt(tmp[2])*parseInt(tmp[3]))+"px";
			 document.getElementById( DragID).style.left=tx;
			 document.getElementById(E).style.left=x3 ;
	      // document.Show.target.value=targetID;
			 document.Show.DragID.value=tmp[1];
			 document.Show.startDay.value=tmp2[1];
			 if(tmp[4]=="未定義"){
			  document.Show.state.value= "預排程";
			 }
			
		 }
		  if( tmp[0]=="E"){
			  document.getElementById( DragID).style.left=tx;
			  var SID= new String( "S="+tmp[1]+"="+tmp[2]+"="+tmp[3]);
			  var sidx= document.getElementById( SID).style.left ;
		      var sidwx=sidx.split("px");
		   	  var x3=(parseInt(x[0])-parseInt(sidwx[0]));
		      document.getElementById(SID).style.width = x3+"px";
              document.Show.DragID.value=tmp[1];
	          document.Show.workingDays.value=x3/tmp[3];
		 }

		 switch(tmp2[0]){
		        case  "state":
			    document.Show.state.value=tmp2[1];
			    break;
				 case  "principal":
			    document.Show.principal.value=tmp2[1];
			    break;
				 case  "outsourcing":
			    document.Show.outsourcing.value=tmp2[1];
			    break;
				 case  "type":
			    document.Show.type.value=tmp2[1];
			    break;
		 
		 }
 
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