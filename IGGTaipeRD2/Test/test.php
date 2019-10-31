<?php
 
 // require_once  dirname(dirname(__FILE__)) .'/PubApi.php';
test();
  function test(){
	   $URL="test.php";
  $ValArray=array(array('testCookie','nekot'));
  $SubmitName="submit";
  $SubmitVal="送出";
  $Rect=array(220,220,100,20);
  $size=12;
  $BgColor="#333333";
  $fontColor="#ffffff";
  $CookieArray=array('testCookie');
  setcookies($CookieArray,"test.php");
   echo ">".$_COOKIE['testCookie'];
   sendVal($URL,$ValArray,$SubmitName,"nekot",$Rect,$size=12, $BgColor ,$fontColor ,"true" ); 
   $Rect=array(220,320,100,20);
   $ValArray=array(array('testCookie','kk'));
   sendVal($URL,$ValArray,$SubmitName,"kk",$Rect,$size=12, $BgColor ,$fontColor ,"true" ); 
  }
  
?>






<?php
 


function setcookies($CookieArray,$BackURL){
	if($_POST['setCookie']!="true") return;

		for($i=0;$i<count($CookieArray);$i++){
			$n=$CookieArray[$i];
		    setcookie($n , $_POST['$n'], time()+3600); 
		}
		 echo ">".$_POST['testCookie'];
    echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";	 
}

 function sendVal($URL,$ValArray,$SubmitName,$SubmitVal,$Rect,$size=12, $BgColor="#eeeeee",$fontColor="#ffffff",$setCookie=false){
		   echo "<form action=".$URL." method=post >";
		   for($i=0;$i<count($ValArray);$i++){
			   echo "<input type=hidden name='".$ValArray[$i][0]."' value='".$ValArray[$i][1]."' >";
		   }
	        echo "<input type=hidden name=setCookie value=".$setCookie." >";
		    $submitP="<input type=submit name=submit   value=".$SubmitVal." 
			           style = 'width:".$Rect[2]."px; height:".$Rect[3]."px; background-color:".$BgColor." ;
       	               font-size:".$size."px; font-weight:bold; border:0; color:".$fontColor.";  '/>";  
		   echo "<div style= 'position:absolute;  top:".$Rect[1]."px; left:".$Rect[0]."px;  '>".$submitP."</div>";
		   echo "</form>";
	   }  

?>