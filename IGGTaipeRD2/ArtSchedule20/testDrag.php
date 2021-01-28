<script type="text/javascript">
     document.captureEvents(Event.MOUSEMOVE)
     document.onmousemove = getMouseXY;
	 function Drop2Area(event) {
	     document.Show.DragID.value=  "xss";
	 }
	 function Drag(event){
		     document.Show.DragID.value=  "xss";
           event.dataTransfer.setData("text",event.currentTarget.id);
   }
    function Drop(event){
		    document.Show.DragID.value=  "xss";
         event.preventDefault();
         var data=event.dataTransfer.getData("text");
        
		 // event.currentTarget.appendChild(document.getElementById(data));
     }
	 function AllowDrop(event){
    event.preventDefault();
}
	     function getMouseXY(e) {
         document.Show.MouseX.value = e.pageX
         document.Show.MouseY.value = e.pageY
         return true
     }
</script>



<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>Drtest</title>
</head>
<body bgcolor="#b5c4b1">
<?php
  //require_once('/Apis/PubJavaApi20.php');
  creatForm();

?>

<?php
     function creatForm(){
		      $URL="testDrag.php";
	          echo  "<form id=Show method=post enctype=multipart/form-data action=".$URL.">";
			  echo "<input  type=text id=DragID name=DragID value='123'   >";
			  echo "</form>";
			  $msg=1;
			  $fontColor="#ffffff";
			  $BgColor="#aaaaaa";
			  $id="drag1";
			  JAPI_DrawJavaDragArea($id,10,200,100,50,$BgColor,$fontColor,$id);
			  $id="move1";
			  	  $BgColor="#aaaaff";
			  JAPI_DrawJavaDragbox($id,10,110,100,50,10,$BgColor,$fontColor,$id);
	 }
?>
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
		?>