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
/*
function drop_image(event)
{
    event.preventDefault() ; //防止瀏覽器執行預設動作
    var images_container = document.getElementById('images_container') ;
    var objXhr           = new XMLHttpRequest() ;
    var files            = event.dataTransfer.files ;
    var objForm          = new FormData() ;
	var URL              = "upload/upTest.php?name=images";
	objXhr.open('POST',URL ) ;
	//objXhr.open('POST', API_URL+'upload/uploadFile?name=images') ;
	for (var i=0; i<files.length; i++) {
		var name = files[i].name ;
      	document.SCup.file.value= name ;
		objForm.append('images[]', files[i]) ;
	}
    objXhr.send(objForm) ;
	
  
}
*/
</script>
<?php
       
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
		   
		      /*
		      echo $x."+".$y.$w.$h.$id.$BgColor;
		      DrawText( "test",$x,$y,$w,$h,"12","#123456");
	          echo  ="<div ondragover='dragHandler(event)'  ondrop=drop_image(event)'  id=".$id."; class=upload-image; ";
			  echo " position:fixed;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor."; '>";
			  echo "</div>";
			  */
	   }
?>