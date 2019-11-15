<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>工作分類排程區</title>
</head>


<?php
DrawList();
       function DrawList(){
		 echo "<p id=p2>Hello World!</p>";
		   $x=100;
		   $y=100;
		   $w=100;
		   $h=1000;
		   $Rect=array(100,100,100,1000);
		   $fontColor="#ffffff";
		   $BgColor="#000000";
           DrawRect("11111",22,$fontColor,$x,$y,2000,2000,"#aaeeee");
		   $msg="bbb";
		   DrawLinkRect_LayerPos($msg,12,$fontColor,$Rect,$BgColor,$Link,$border,-1);
	   }
	    function DrawLinkRect_LayerPos($msg,$fontSize,$fontColor,$Rect,$BgColor,$Link ,$Layer){
	          echo "<div id=LockX  style='LockX :pointer ; color:".$fontColor."; " ;
 
			  echo " z-index:".$Layer ."; ";
			  echo " text-align:center ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo " position:fixed ; ";
 
              echo "top:".$Rect[1]."px; left:".$Rect[0]."px; width:".$Rect[2]."px;height:".$Rect[3]."px; background-color:".$BgColor."; ' "; 
			  echo " onclick=location.href='".$Link."'; >";
			  echo $msg;
	          echo "</div>";
	   }
	   	    function DrawRect($msg,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor){ 
	          echo "<div  style=' color:".$fontColor."; " ;
			  echo "text-align:center ; line-height:".($h)."px ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo "position:absolute;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor."; '>";
			  echo $msg;
	          echo "</div>";
	   }
?>

<script type="text/javascript">
    var a= document.getElementById("LockX");
    a.innerHTML ="xxxx";
     var baseh=parseInt( a.style.top);
	 a.innerHTML =baseh;
	 window.onscroll = function(){
		   var f=baseh+document.body.scrollTop;
　         a.innerHTML =f  ;
           a.style.top=baseh-document.body.scrollTop ;
		 
　　 }
  

</script>
<!DOCTYPE html>
<html>
<body bgcolor="#b5c4b1">
</html>