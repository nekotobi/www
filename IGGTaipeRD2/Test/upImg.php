<script type="text/javascript">
function dragHandler(event)
{
    event.preventDefault()  
}
function drop_image(event)
{
   event.preventDefault() ; 
   var dt = event.dataTransfer;
   var files = dt.files;
   var n = files.length;
   var id= event.currentTarget.id;
    
   for (var i = 0; i < n; i++) {
        var file = files[i];
        var fileName=file.name;
      
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onloadend =function(event){
			  
            var filedata = event.target.result;
	          document.SCup.file.value=id;
			 document.getElementById(id).appendChild("xxxxxxxxxxxxxxxxxxxxxxxxxssssssssssssssssssx");
	    //    $(document.body).append()
       //     $(document.body).append("<img src='"+filedata+"' />")
        }
   } 
}
 
</script>
<?php
       echo "xx";
	  DrawInputFileRect($x,($y+240),"300","200","drop_image","#aaaaaa");
	    echo   "<form id='SCup'  name='SCup' action='scheduleUp.php' method='post'>";
	   function DrawFormTextarea($x,$y,$w,$h, $name,$value,$color){
 	          echo "<textarea  name='".$name."'  style=' ";
			  echo "position:fixed;  top:".$y."px; left:".$x."px;   ";// cols='50'; rows='12';";
			  echo " width:".$w."px; height:".$h."px;  background-color:".$color."; "; 
			  echo " text-align:left  ;color:#000000 ; font-size:12px '>";
			  echo $value;
	          echo "</textarea>";
        }
	   function DrawSubmitRect($msg,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor,$formId,$border){
	          echo "<div onclick= document.getElementById('".$formId."').submit() ; style=' cursor:pointer ; color:".$fontColor."; " ;
			  echo $border;
			  echo " text-align:center ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo " position:fixed;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor."; '>";
			  echo $msg;
	          echo "</div>";
	   }
	   function DrawInputFileRect($x,$y,$w,$h,$id,$BgColor){
		      echo "<div  id=".$id." ";
			  echo " draggable='true'  ondragover='dragHandler(event)' ondrop='drop_image(event)'  class='upload-image' ";
              echo " style=' " ; //align=left
			  echo "text-align:center ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:12px;";
			  echo "position:absolute; top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor."; '>";
	          echo "XX";
			  echo  "</div>";
 
	   }
	   		  echo   "<input type='text' name='file' value='0' size='22'>file";
			  echo   "</form>";
?>
 