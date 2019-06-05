 <!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>????</title>
</head>
<body bgcolor="#999999">
<?php
	   $id=$_COOKIE['IGG_id'];
	   	if($id=="")$id="guset";
	   $colorCodes= GetColorCode();
       function IDCheck(){
 
	   }
	   function DrawUserData($startY){
	        global $id,$colorCodes,$startY;
	        DrawRect("" ,"12","#ffffff",10,$startY+10,160,20, $colorCodes[0][0]);
	        DrawText("???",20,$startY+12,100,20,12, $colorCodes[5][2]);
			DrawRect( $id,"12",$colorCodes[0][0]  ,62,$startY+12,60,16, $colorCodes[5][2]);
			if($id!="??"){
			DrawLinkPic("Pics/Logout.png",$startY+12,140,16,16,"Login.php?Logout=true");
			}
			if($id=="??"){
			DrawLinkPic("Pics/Logout.png",$startY+12,140,16,16,"Login.php");
			}
	  }
	  function DrawText($text,$x,$y,$width,$height,$Size,$Color){
	      echo"<div   style=' text-align:left  ;color:".$Color.";
			  font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$Size."px;
			  position:absolute; top:".$y."px; left:".$x."px ;width:".$width."px;height:".$height."px;
	          '>".$text."</div>";
	  }
      function GetColorCode(){
	          $all_num= getAll_num( "colorcodes");
	          $t=mysql_num_rows($all_num); 
			  $ColorCodes=array();
			  for($i=0;$i<$t;$i++){
			      $code =mysql_result(  $all_num,$i,'ColorKey');
			      $set =mysql_result(  $all_num,$i,'set');
			      $sn=mysql_result(  $all_num,$i,'sn');
				  $ColorCodes[$set][$sn]= $code ;
			  }
	          return $ColorCodes;
	  }		 
 
      function  getMysqlDataArray($name){
	            $all_num= getAll_num( $name );
				$fieldnum=mysql_num_fields( $all_num);
				$fName=array();
				for ($x=0 ;$x<$fieldnum;$x++)	array_push($fName, mysql_field_name($all_num,$x));
				$returnData=array();
				 $t=mysql_num_rows($all_num); 
				for($i=0;$i<$t;$i++){
				    $data=array();
					for ($x=0 ;$x<count($fName);$x++){
						 $d=mysql_result(  $all_num,$i,$fName[$x]);
						 array_push($data,$d);
					}
					 array_push($returnData,$data);
				}
				return $returnData;
	  }
 	   function DrawMemberRect($Name,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor,$Job){
		      //color
	          echo "<div  style=' color:".$fontColor."; " ;
			  echo "text-align:center ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo "position:absolute;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor."; '>";
			  echo  $Job;
	          echo "</div>";
			  
			  echo "<div  style=' color:#000000; " ;
			  echo "text-align:center ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo "position:absolute;  top:".($y+20)."px; left:". ($x+3) ."px;  width:".($w-6)."px;height:".($h-42)."px; background-color:#eeeeee; '>";
			  echo $Name;
	          echo "</div>";
	   }
	   
	   function DrawPicBG($pic,$x,$y,$w,$h){
		       echo "<div style='position:absolute; background-image:url(".$pic.");
				      top:".$x."px;Left:".$y."px; width:".$w."px;height:".$h."px;
				      '></div>";
	   }
	   function DrawLinkPic($pic,$x,$y,$w,$h,$Link){
		     	echo "<div  onclick=location.href='".$Link."' style='position:absolute; 
				       top:".$x."px;Left:".$y."px; width:".$w."px;height:".$h."px;
				      '><img src=".$pic." width=".$w." height=".$h."></div>";
	   }
	   
	    function DrawLinkRect($msg,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor,$Link,$border){
	          echo "<div onclick=location.href='".$Link."' style=' color:".$fontColor."; " ;
			  echo $border;
			  echo " text-align:center ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo " position:absolute;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor."; '>";
			  echo $msg;
	          echo "</div>";
	   }
	   function DrawRect($msg,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor){
	          echo "<div  style=' color:".$fontColor."; " ;
			  echo "text-align:center ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo "position:absolute;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor."; '>";
			  echo $msg;
	          echo "</div>";
	   }
 	   function DrawInputRect($msg,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor,$WorldAlign,$input){
	          echo "<div  style=' color:".$fontColor."; " ;
			  echo "text-align:".$WorldAlign." ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo "position:absolute;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px;  '>";
			  echo  $msg.$input ;
	          echo "</div>";
	   }
	  function getAll_num($SElectTable){
		  $data_library="IGGTaipeRD2";
	      $db = mysql_connect("localhost","root","1406");
	      mysql_select_db( $data_library,$db);
          mysql_query("SET NAMES 'utf8'");
	      return  mysql_query("SELECT * FROM ".$SElectTable,$db);	  
	  }
?>