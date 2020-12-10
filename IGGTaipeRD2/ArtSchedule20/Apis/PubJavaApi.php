<script type="text/javascript"> //common
    var upId="xx";
	var BaseColor="";
	var BGColor="";
	var startX=0;
    document.captureEvents(Event.MOUSEMOVE)
    document.onmousemove = getMouseXY;
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
<?php //         DrawJavaDragArea
	    function JAPI_DrawJavaDragArea($msg,$x,$y,$w,$h,$BgColor,$fontColor,$id,$fontSize=10){
	             echo "<div  id=".$id." ";
			     echo " ondrop='Drop2Area(event)'  ondragover='AllowDrop(event)' ";//  ondragleave = 'leave(event)' ";
                 echo " style='   " ;
			     echo "position:absolute;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor."; ";
	             echo " font-size:".$fontSize."px ; color:".$fontColor."; ";
                 echo "'  >";
			     echo $msg;
	             echo "</div>";
	    }
	    function JAPI_DrawJavaDragbox($msg,$x,$y,$w,$h,$fontSize,$BgColor,$fontColor,$id){
	             echo "<div  id=".$id." ";
			     echo " draggable='true' ondragstart='Drag(event)' ";// ondragend='leave(event)' ";
                 echo " style=' " ; //align=left
			     echo "position:absolute; top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; 
			           font-size:".$fontSize."px; color:".$fontColor."; background-color:".$BgColor."; '>".$msg;
	             echo "</div>";
	    }
        function JAPI_CreatJavaForm( $URL,$tableName,$inputsTextNames,$RecWebPostArr ){
		         $x=20;
			     $y=10;
			     $lastUpdate=date("Y_j_n_H_i_s");
		         $upFormVal=array("Show","Show",$URL);
			     $UpHidenVal=array(array("tablename",$tableName),
			                    array("data_type","data"),
								array("Send","sendjava" ),
								array("Restype2",$Restype ),
								array("Restype",$Restype ),
	                            );	
			     for($i=0;$i<count($RecWebPostArr);$i++){
					// echo $RecWebPostArr[$i][0].">".$RecWebPostArr[$i][1];
			        array_Push( $UpHidenVal,array($RecWebPostArr[$i][0],$RecWebPostArr[$i][1] ));
			     }				
			     $inputVal=array();     
                 $sx=1000;		
                 $w=100;	 			  
			     for($i=0;$i<count( $inputsTextNames);$i++){
			         $a=array("text",$inputsTextNames[$i],$inputsTextNames[$i],10,$sx ,$y, $w,20,$BgColor,$fontColor,"" ,10);
					 $sx+= $w;
					 array_push( $inputVal,$a);
			     }		  
		         upSubmitform($upFormVal,$UpHidenVal, $inputVal);
	   }
	    function DrawDateInfo(){
	             $id="DateInfo";
			     JAPI_DrawJavaDragbox( "info"  ,1024,0,100,8,6,"#333333", "#ffffff",$id);
	    }
 
?>

<?php //
        function  JAPI_ReLoad($PostArray,$URL){
		         $params="{";
		   	     for($i=0;$i<count($PostArray);$i++){
			         $params=$params."'".$PostArray[$i][0]."':'".$PostArray[$i][1]."'";
			          if(count($PostArray)>1) $params=$params.",";
			     }
		    	  $params=$params."}";
		          $javaCom=  "post_to_url('".$URL."', ".$params.");";
				  //echo $javaCom;
                  echo " <script language='JavaScript'>".$javaCom."</script>"; 
        }
 
?>