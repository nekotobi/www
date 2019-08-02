<!DOCTYPE HTML>

<html>
<head>
<meta http-equiv="Content-Type"content="txt/html;charset=utf-8">
<title>tianzige</title>
</head>
<style>
#prat{
   
}
 
</style>
</head>

<body>
 <input type="button" value="我是按鈕" style="width:120px;height:40px;border:3px #000000 double;background-color:pink;">
 
<?php
       echo "<div id=date></div>";
 
      //  $input ="<input id=b type=button value=RUN onclick=test(); />";
		$x=120;
		$y=100;
		$w=100;
		$h=20;
		$BgColor="#442244";
		$WorldAlign="top";
		$onclick="onclick=test()";
		$borderColor="#555555";
		$b="2";
		$value=">>";
		$fontColor="#ff1234";
   
	    DrawButtom($value,$x,$y,$w,$h,$b,$borderColor,$BgColor,$onclick,$fontColor);
        function DrawButtom($value,$x,$y,$w,$h,$b,$borderColor,$BgColor,$onclick,$fontColor){
	       $but="<input type='button' value='".$value."' ";
		   $but=$but."style='width:".$w."px;height:".$h."px;";
		   $but=$but." color:".$fontColor."; border:".$b."px #000000 double;background-color:".$BgColor.";";
		   $but=$but."; onclick=".$onclick."'>";
		   echo "<div style='position:absolute;  top:".$y."px; left:".$x."px;' >".$but ;
		   echo "</div>";
		}
 
?>
</body>
<script type="text/javascript">
       var basey=40;
  
	   var x=20;
	  // DrawDiv();
 
       function run() {
          var c = document.getElementById("content");
          var n = document.createElement("p");
          var message = "Hello, JavaScript!!!!";
          n.appendChild(document.createTextNode(message));
          c.appendChild(n);
        }

	   function test(){
		   basey+=20;
		   
		  // document.getElementById("area").innerHTML= basey;
		   DrawDiv();
		  
	   }
	   function DrawDiv(){
		        var div="";
	        for(var i=1;i<19;i++){
                x+=30;		   
                div+=DrawRect();//"<div class='prat1'></div>";
                }
                document.getElementById("prat").innerHTML=div;
	   }
	   function DrawRect(){ 
		    var txt="<div style=' ";
			    txt+=" position:absolute; ";
				txt+=" top:"+ basey+"px; ";
		     	txt+=" left:"+x+"px; ";
			    txt+= "width:50px;  height:50px; background:orange; border:1px solid black; float:left;'>";
		        txt+=x;
		        txt+="</div>";
		   return txt;
	   }
 
</script>
</html>
<script type="text/javascript">

</script>

