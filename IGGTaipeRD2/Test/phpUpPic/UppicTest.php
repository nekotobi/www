<script type="text/javascript">
        function dragoverHandler(evt) {
            evt.preventDefault();
        }
        function dropHandler(evt) {//evt 為 DragEvent 物件
            evt.preventDefault();
            var files = evt.dataTransfer.files;//由DataTransfer物件的files屬性取得檔案物件
            var fd = new FormData();
            var xhr = new XMLHttpRequest();
            var up_progress = document.getElementById('up_progress');
            xhr.open('POST', 'upload.php');//上傳到upload.php
            xhr.onload = function() {  //上傳完成
                up_progress.innerHTML = '100 %, xx上傳完成';
            };
            xhr.upload.onprogress = function (evt) {    //上傳進度
              if (evt.lengthComputable) {
                var complete = (evt.loaded / evt.total * 100 | 0);
                if(100==complete){
                    complete=99.9;
                }
                up_progress.innerHTML = complete + ' %';
              }
            }
 
         
            for (var i in files) {
                if (files[i].type == 'image/jpeg') {  //將圖片在頁面預覽
                    var fr = new FileReader();
                    fr.onload = openfile;
                    fr.readAsDataURL(files[i]);
					//files[i].name="baga";
				   var	f=files[i];
				   f.name="baga";
               //    fd.append('ff[]', files[i]);       //新增上傳檔案，上傳後名稱為 ff 的陣列
			         fd.append('ff[]', f);
                }
            }
            xhr.send(fd);//開始上傳
        }
        function openfile(evt) {
            var img = evt.target.result;
            var imgx = document.createElement('img');
            imgx.style.margin = "10px";
            imgx.src = img;
            document.getElementById('imgDIV').appendChild(imgx);
        }    

</script>

<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>上傳圖片測試</title>
</head>
<body bgcolor="#b5c4b1"> 
<?php
     echo "xxx";
     $id="dropDIV";
	 $msg= " 拖曳圖片到此處上傳";
     // DrawUpPicRect($x,$y,300,200,$id, ,$BgColor );
	 $Rect=array(100,100,300,200);
	 $BgColor="#333333";
	  DrawJavaDragPicArea($msg,$Rect,$BgColor,$fontColor,$id,$fontSize=10);
 
 


?>

       <div id="up_progress"></div>
      </div>
      <div id="imgDIV"></div>
	  <input type="file" id="file-uploader" data-target="file-uploader" accept="image/*" multiple="multiple"/>
	
<?php //function 
	    function DrawJavaDragPicArea($msg,$Rect,$BgColor,$fontColor,$id,$fontSize=10){
	          echo "<div  id=".$id." ";
			  echo " ondrop='dropHandler(event)'  ondragover='dragoverHandler(event)' "; 
              echo " style='   " ;
			  echo "position:absolute;  top:".$Rect[0]."px; left:".$Rect[1]."px;  width:".$Rect[2]."px;height:".$Rect[3]."px; background-color:".$BgColor."; ";
	          echo " font-size:".$fontSize."px ; color:".$fontColor."; ";
              echo "'  >";
			  echo $msg;
	          echo "</div>";
	    }
       function DrawUpPicRect($x,$y,$w,$h,$id,$BgColor){
		        echo "<div  id=".$id." ondragover='dragoverHandler(event)' ondrop='dropHandler(event)' ";
				echo " style='   " ;
				echo "text-align: center; ";
				echo " width: ".$w."px; height: ".$h."px; margin: auto; border: dashed 2px gray;  >";
				//echo "   img{ max-height:200px; max-width:300px; }";
		        echo "</div>";
	   }
       function DrawUpPicRectb($x,$y,$w,$h,$id,$info, $fontSize,$fontColor,$BgColor,$other){
	          echo "<div  id=".$id." ";
              echo " style='   " ;
			  echo " position:absolute;  top:".$y."px; z-index:-1; left:".$x."px;  width:".$w."px;height:".$h."px;";
			  echo  $other;
			  echo " font-size:".$fontSize."px; color:".$fontColor.";   background-color:".$BgColor.";  '  >";
	          echo $info;
			  echo "</div>";
	    }
		/*
		  <form method="post" enctype="multipart/form-data" action="upload.php">
      <input type="file" name="my_file">
      <input type="submit" value="Upload">
      </form>
	  */
?>