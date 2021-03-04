<?php //cookies 
      function PubApi_getCookie(){
		         global $id;
		         global $rank;
	          	 global $colorCodes;
	             $id=$_COOKIE['IGG_id'];
	             $rank=$_COOKIE['IGG_Rank'];
				 $Defuse_sets=$_COOKIE['IGG_sets'];
	             if($id=="")$id="guest";
	         	 $colorCodes= GetColorCode();
	   }
	  function PubApi_GetArrayCookie($arr){
		        for($i=0;$i<count($arr);$i++){
				    if($_COOKIE[$arr[$i]]!=""){
				    	global $$arr[$i];
						$$arr[$i]=$_COOKIE[$arr[$i]];
					  //echo $arr[$i]."=".$_COOKIE[$arr[$i]];
					}
				}
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
	  function PubApi_DrawUserData($x,$startY){
	            global $id,$colorCodes ;
	            DrawRect("" ,"12","#ffffff",array($x+10,$startY+10,160,20), $colorCodes[0][0] );
	            DrawText("使用者",array($x+30,$startY+12,100,20),12, $colorCodes[5][2]);
		  	    DrawRect( $id,"12",$colorCodes[0][0]  ,array($x+72,$startY+12,60,16), $colorCodes[5][2]);
		        DrawLinkPic("Pics/home.png",array($startY+12,$x+10,16,16),"index.php");
			    if($id!="guest"){
				   $URL="Login20.php";
			       $pic="Pics/Logout.png";
				   $Rect=array($x+150,$startY+12,16,16);
				   $ValArray=array(array("Logout","true"));
			       sendValPic($URL,$pic,$Rect,$ValArray);
		    	}
			    if($id=="guest"){
		        DrawLinkPic("Pics/Logout.png",array($startY+12,$x+150,16,16),"Login20.php");
			    }
	  }
	  function PubApi_setcookies($CookieArray,$BackURL){
	           if($_POST['setCookie']!="true") return;
			    for($i=0;$i<count($CookieArray);$i++){
					$n= $CookieArray[$i];
				     if($_POST[$n]!=""){
						 setcookie($n , $_POST[$n], time()+78000); 
					 }
				}
		   echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";
        }
 
?>
<?php //php繪製表格 
	  function PubApi_MakeSelection($items,$selectItem,$selectName,$size){
		    $selectItemf=trim(  $selectItem);
			  $seletProject= "<select  class=form-control  style=font-size:".$size."px; color: red;  id=".$selectName."  name=".$selectName."  >";
			$seletProject=$seletProject."<option value=未定義 >未定義</option>";
			for($i=0;$i<count($items);$i++){
				//echo $items[$i];
				 $itemf=trim( $items[$i]);
			     $seletProject=$seletProject."<option value='".$items[$i];
				 if($itemf== $selectItemf){
					 $seletProject=$seletProject."' selected=true ";
				 }
				 $seletProject=$seletProject."'>".$items[$i]."</option>";
			}
			$seletProject="'".$seletProject."'</select>";
	        return $seletProject;
	   }
	  function PubApi_DrawPosPic($pic,$x,$y,$w,$h,$posType ){
	    	echo "<div style='position:".$posType; 
			echo ";  top:".$x."px;Left:".$y."px; width:".$w."px;height:".$h."px;
				   '><img src=".$pic." width=".$w." height=".$h."></div>";
	   }
	  function DrawLinkRect_newtab($msg,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor,$Link,$border){
	          echo "<div   style=' cursor:pointer ; color:".$fontColor."; " ;
			  echo $border;
			  echo " text-align:center ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo " position:absolute;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor."; ' "; 
			  echo " onClick=window.open('".$Link."','_newtab'); >";
			  echo $msg;
	          echo "</div>";
	   }
      function DrawRect($msg,$fontSize,$fontColor,$Rect,$BgColor,$Layer=0){ 
	           echo "<div  style=' color:".$fontColor."; " ;
			   echo " z-index:".$Layer."; ";
			   echo "text-align:center ; line-height:".($h)."px ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			   echo "position:absolute;  top:".$Rect[1]."px; left:".$Rect[0]."px;  width:".$Rect[2]."px;height:".$Rect[3]."px; background-color:".$BgColor."; '>";
			   echo $msg;
	           echo "</div>";
	  }
	  function DrawInputRect_size($msg,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor,$WorldAlign,$input){ 
	          echo "<div  style='font-size:".$fontSize."px; color:".$fontColor."; " ;
			  echo "text-align:".$WorldAlign." ; font-weight:bolder ;  ";
			  echo "position:absolute;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px;  '>";
			  echo  $msg.$input ;
	          echo "</div>";
	   }
	  function DrawPic($pic,$Rect,$Layer=0){
		       echo "<div    style='position:absolute;  ";
			   echo " z-index:".$Layer ."; ";
			   echo "  top:".$Rect[1]."px;Left:".$Rect[0]."px; width:".$Rect[2]."px;height:".$Rect[3]."px; '>";
			   echo "  <img src=".$pic." width=".$Rect[2]." height=".$Rect[3]."></div>";
	  }
	  function DrawIDPic($pic,$Rect,$ID,$Layer=0){
		       echo "<div  style='position:absolute;  ";
			   echo " z-index:".$Layer ."; ";
			   echo "  top:".$Rect[1]."px;Left:".$Rect[0]."px; width:".$Rect[2]."px;height:".$Rect[3]."px; '>";
			   echo "  <img id=".$ID."  src=".$pic." width=".$Rect[2]." height=".$Rect[3]."   ></div>";
	  }
	  function DrawText($text,$Rect,$Size,$Color,$Layer=0){
	           echo"<div   style=' text-align:left  ;color:".$Color."; ";
			   echo " z-index:".$Layer ."; ";
			   echo " font-family:Microsoft JhengHei; font-size:".$Size."px;
			          position:absolute; top:".$Rect[1]."px; left:".$Rect[0]."px ;width:".$Rect[2]."px;height:".$Rect[3]."px;
	                  '>".$text."</div>";
	   }
	  function DrawLinkPic($pic,$Rect,$Link,$Layer=0){
		       echo "<div  onclick=location.href='".$Link."' style='position:absolute; 
					   z-index:".$Layer .";  
				       top:".$Rect[0]."px;Left:".$Rect[1]."px; width:".$Rect[2]."px;height:".$Rect[3]."px;
				      '><img src=".$pic." width=".$Rect[2]." height=".$Rect[3]."></div>";
	   }
	  function sendValPic($URL,$pic,$Rect,$ValArray,$Layer=0){
	           echo "<form action=".$URL." method=post >";
	           for($i=0;$i<count($ValArray);$i++){
		           echo "<input type=hidden name='".$ValArray[$i][0]."' value='".$ValArray[$i][1]."' >";
		           } 
			   echo "<input type=hidden name=setCookie value=true >";
	           $submitP="<input type=image src=".$pic."   alt='Submit Form'  width=".$Rect[2]." height=".$Rect[3]." />";
		       echo "<div style= 'position:absolute;  top:".$Rect[1]."px; left:".$Rect[0]."px; 
			          z-index:".$Layer .";  
			          width:".$Rect[2]."px;   height:".$Rect[3]."px;
		            '>".$submitP."</div>";
			   echo "</form>";
	    }
	  function sendVal($URL,$ValArray,$SubmitName,$SubmitVal,$Rect,$size=12, $BgColor="#eeeeee",$fontColor="#ffffff",$setCookie=false){
		       echo "<form id=sendval action=".$URL." method=post >";
			   for($i=0;$i<count($ValArray);$i++){
			       echo "<input type=hidden name='".$ValArray[$i][0]."' value='".$ValArray[$i][1]."' >";
		       }
	           echo "<input type=hidden name=setCookie value=".$setCookie." >";
		       $submitP="<input type=submit name=".$SubmitName."   value=".$SubmitVal." 
			            style = 'width:".$Rect[2]."px; height:".$Rect[3]."px; background-color:".$BgColor." ; 
					    text-align:left;    
       	                font-size:".$size."px; font-weight:bold; border:0; color:".$fontColor.";  '/>";  
		       echo "<div style= 'position:absolute;  top:".$Rect[1]."px; left:".$Rect[0]."px;  '>".$submitP."</div>";
		       echo "</form>";
	   }  
 	  function DrawLinkRect($msg,$fontSize,$fontColor,$Rect,$BgColor,$Link,$border,$Layer){
	          echo "<div   style=' cursor:pointer ; color:".$fontColor."; " ;
			  echo $border;
			  echo " z-index:".$Layer ."; ";
			  echo " text-align:center ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo " position:absolute;  top:".$Rect[1]."px; left:".$Rect[0]."px;  width:".$Rect[2]."px;height:".$Rect[3]."px; background-color:".$BgColor."; ' "; 
			  echo " onclick=location.href='".$Link."'; >";
			  echo $msg;
	          echo "</div>";
	  }
?>
<?php //Array
	   function filterArray($BaseArray,$Num,$checkName){
                $data=array();		     
		        for($i=0;$i<count($BaseArray);$i++){
			        $strBase=trim($BaseArray[$i][$Num]);
			        $srtCheck=trim($checkName);
			        if($strBase==$srtCheck){
			           array_push($data,$BaseArray[$i]);
			          } 
			       }
	       return $data;
	   }
	   function filterArrayContainStr($BaseArray,$Num,$checkName){
                $data=array();		     
		        for($i=0;$i<count($BaseArray);$i++){
			        $strBase=trim($BaseArray[$i][$Num]);
			        $srtCheck=trim($checkName);
			       if(strpos( $strBase,  $srtCheck) !== false){ 
			           array_push($data,$BaseArray[$i]);
			          } 
			       }
	       return $data;
	   }
	   function sortArrays($BaseArray ,$ArrayNum ,$forwardBool="true"){ //排序案某表格中的數字 tt 0  aa 1
  		  $newArray=array();
		  $lastSn=  getLastSN2($BaseArray,$ArrayNum); 
		 if($forwardBool=="true"){//正向
		  	  for($i=0;$i<= $lastSn;$i++){
                 $tmpArray= GetArraySn($BaseArray, $ArrayNum ,$i);
				 if(count($tmpArray)>0)$newArray=  array_merge( $newArray,$tmpArray); 
			  } 
		  }
		  if($forwardBool=="false"){//逆向
		  	  for( $i=$lastSn;$i>0;$i--){
                 $tmpArray= GetArraySn($BaseArray, $ArrayNum ,$i );
				 if(count($tmpArray)>0)$newArray=  array_merge( $newArray,$tmpArray); 
			  } 
		  }
	      return  $newArray;
	  }
	   function PAPI_sortCodeWithGDCode($BaseArray ,$ArrayNum ,$forwardBool="true"){ //排序GDCode案某表格中的數字 tt 0  aa 1
  		  $newArray=array();
		  $lastSn= PAPI_getGDCODELastSN($BaseArray,$ArrayNum ); 
 
		 if($forwardBool=="true"){//正向
		  	  for($i=1;$i<= $lastSn;$i++){
                  $arr=  PAPI_returnGDSnArray($BaseArray, $ArrayNum ,$i );
				  array_push($newArray,$arr);
			  } 
		  }
		  if($forwardBool=="false"){//逆向
		  	  for( $i=$lastSn;$i>0;$i--){
			      $arr=  PAPI_returnGDSnArray($BaseArray, $ArrayNum ,$i );
				  array_push($newArray,$arr);
			  }
		  }
	      return  $newArray;
	  }
	   function PAPI_sortGDCodeArrays($BaseArray ,$ArrayNum ,$forwardBool="true"){ //排序GDCode案某表格中的數字 tt 0  aa 1
  		  $newArray=array();
		  $lastSn=  getLastSN2($BaseArray,$ArrayNum); 
 
		 if($forwardBool=="true"){//正向
		  	  for($i=0;$i<= $lastSn;$i++){
                 $tmpArray= GetArraySn($BaseArray, $ArrayNum ,$i);
				 if(count($tmpArray)>0)$newArray=  array_merge( $newArray,$tmpArray); 
			  } 
		  }
		  if($forwardBool=="false"){//逆向
		  	  for( $i=$lastSn;$i>0;$i--){
                 $tmpArray= GetArraySn($BaseArray, $ArrayNum ,$i );
				 
				 if(count($tmpArray)>0)$newArray=  array_merge( $newArray,$tmpArray); 
			  }
		  }
	      return  $newArray;
	  }
       function returnArraybySort($BaseArray,$num){//回傳二維陣列中第幾個陣列的陣列
	            $Ar=array();
			    for($i=0;$i<count($BaseArray);$i++){
			     	 array_push($Ar,$BaseArray[$i][$num]);
			    }
			    return $Ar;
	  }
	   function addArray($Base,$Add){//將basearry 加入另個array
	            $a=$Base;
			    for($i=0;$i<count($Add);$i++){
			        array_push($Base,$Add[$i]);
			    }
			    return $Base;
	   }
	   function RemoveArray($BaseArray,$num,$RemoveStr){
	            $returnArray=array();
				 for($i=0;$i<count($BaseArray);$i++){
			
					 $n=trim(  $BaseArray[$i][$num]);  
					 if( $n!=$RemoveStr){
						 array_push($returnArray,$BaseArray[$i] );
						 	//	 echo $BaseArray[$i][$num].">[".$RemoveStr."]";
					 }
				 }
	            return $returnArray;
	   }  
?>
<?php //function_exist
       function PAPI_returnSplitStr($str,$num){
	           $l=strlen(  $str);
			   $s=$str;
			   if($l>$num){
				  $p=str_split($str,$num);
			      $s=$p[0];
			   }	
			   if($s=="--")$s="";
               return  $s;			   
	   }
	    function PAPI_returnGDSnArray($BaseArray, $ArrayNum ,$sn ){ 
		        for($i=0;$i<count($BaseArray);$i++){
					 $s=PAPI_GDCODE2Sort($BaseArray[$i][ $ArrayNum]);
			         if( $s==$sn) return $BaseArray[$i];
			     }
			  
	   }
	   function GetArraySn($BaseArray, $ArrayNum ,$sn ){
			    $newArray=array();
		        for($i=0;$i<count($BaseArray);$i++){
			        if($BaseArray[$i][ $ArrayNum]==$sn) {
					   array_push (  $newArray,$BaseArray[$i]);
				     }  
			     }
			    return $newArray;
	   }
	   function getLastSN2($SQLData,$SnNum){
	            $lastSN=0;
		        for($i=0;$i<count($SQLData);$i++){
		           if($SQLData[$i][$SnNum]>$lastSN)$lastSN=$SQLData[$i][$SnNum];
		           }
		        return $lastSN;
	   }
	   function PAPI_GDCODE2Sort($code){
		       $sn=(int)(substr($code, -4));
			   return $sn;
	   }
	   function PAPI_getGDCODELastSN($SQLData,$SnNum){
	            $lastSN=0;
		        for($i=0;$i<count($SQLData);$i++){
					$sn=(int)(substr($SQLData[$i][$SnNum], -4));
					 if($sn>$lastSN)$lastSN=$sn;
		           }
		        return $lastSN;
	   }
?>
<?php //form
       function DrawInputRect($msg,$fontSize,$fontColor,$Rect,$BgColor,$WorldAlign,$input){ 
	            echo "<div  style='font-size:".$fontSize."px; color:".$fontColor."; " ;
			    echo "text-align:".$WorldAlign." ; font-weight:bolder ;  ";
			    echo "position:absolute;  top:".$Rect[1]."px; left:".$Rect[0]."px;  width:".$Rect[2]."px;height:".$Rect[3]."px;  '>";
			    echo  $msg.$input ;
	            echo "</div>";
	   }
       function returnDataCode( ){
	            return  date("Y-m-d-His",(time()+8*3600));
	   }
       function PAPI_returnECode( ){
	            return  date("Y-m-d-His",(time()+8*3600));
	   }
	   function upSubmitform($upFormVal,$UpHidenVal, $inputVal){
		        //=========
	            //$upFormVal ==>0/id 1/name 2/URL 
			    //$UpHidenVal=array 0/name,1/val
			    //$inputVal=0/type 1/name 2/showname 3/fontsize 4/5/6/7rect  8/bgcolor 9/fontColor 10/val 11/size
			    echo  "<form id=".$upFormVal[0]."  name=".$upFormVal[1]." action=".$upFormVal[2]." method='post' enctype='multipart/form-data'>";
			    for($i=0;$i<count($UpHidenVal);$i++){
			        echo   "<input  type=hidden id=".$UpHidenVal[$i][0]."  name=".$UpHidenVal[$i][0]." value=".$UpHidenVal[$i][1].">"; 
			    }
			    for($i=0;$i<count($inputVal);$i++){
			        $input="<input  type=".$inputVal[$i][0]."  id=".$inputVal[$i][1]."  name=".$inputVal[$i][1]."
  				           style='font-size:".$inputVal[$i][3]."px'; value='".$inputVal[$i][10]."' size=".$inputVal[$i][11]."  >";
				    $x=$inputVal[$i][4];
				    $y=$inputVal[$i][5];
				    $w=$inputVal[$i][6];
				    $h=$inputVal[$i][7];
				    $msg=$inputVal[$i][2];
				    $WorldAlign="top";
				    $fontSize=$inputVal[$i][3];
				    $fontColor=$inputVal[$i][9];
				    $BgColor=$inputVal[$i][8];
				    DrawInputRect_size($msg,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor,$WorldAlign,$input);
			  }
	 		    echo "<form>";
			 
	   }
	   function DrawPopBG($x,$y,$w,$h,$title,$fontSize,$BackURL,$PostArray=array()){
	            DrawPicBG("Pics/Black50Bg.png",$y-40,$x-40,$w+80,$h+80);
		        DrawLinkPic("Pics/Cancel.png",array($y-50,$x+$w+20,32,32),$BackURL);
		        DrawRect($title,$fontSize,"#ffffff",array($x ,$y ,$w,"20"),"#a27e7e");
	   }
	   function DrawPicBG($pic,$x,$y,$w,$h){
		        echo "<div style='position:absolute; background-image:url(".$pic.");
				      top:".$x."px;Left:".$y."px; width:".$w."px;height:".$h."px;
				      '></div>";
	   }
?>

<?php //color
       function PAPI_changeGlayColor($hex, $GlayP){
                $RGB=PAPI_hex2rgb(  $hex  );
				$nrgb=$RGB;
				//取得平均數
				$p=0;
				for( $i=0;$i<count($RGB);$i++){
					$p+=$RGB[$i];
				}
				$p=($p/3)*$GlayP;
				for( $i=0;$i<count($RGB);$i++){
				    $nrgb[$i]=(int)( ($RGB[$i]+$p)/2);
					if(  $nrgb[$i]>255)$nrgb[$i]=255;
				}
			    $hex= PAPI_rgb2hex($nrgb) ;
				return  $hex;
	   }
       function PAPI_changeColor($hex, $colorAddArr){
                $RGB=PAPI_hex2rgb(  $hex  );
				$nrgb=$RGB;
				for( $i=0;$i<count($RGB);$i++){
				    $nrgb[$i]=(int)( $RGB[$i]* $colorAddArr[$i]);
					if(  $nrgb[$i]>255)$nrgb[$i]=255;
				}
			    $hex= PAPI_rgb2hex($nrgb) ;
				return  $hex;
	   }
       function PAPI_rgb2hex( $RGB ) { 
	            $hex= sprintf("#%02x%02x%02x", $RGB[0], $RGB[1], $RGB[2]);
			    return $hex;
	   }	
	   function PAPI_hex2rgb(  $hex  ){
                list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
                return array($r ,$g ,$b);
	   }

	/*
    function PAPI_hex2rgb( $colour ) { 
         if ( $colour[0] == '#' ) $colour = substr( $colour, 1 ); 
         if ( strlen( $colour ) == 6 ) { 
            list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] ); 
            } elseif ( strlen( $colour ) == 3 ) { 
            list( $r, $g, $b ) = array( $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] ); 
            } else { 
            return false; 
           } 
          $r = hexdec( $r ); 
          $g = hexdec( $g ); 
          $b = hexdec( $b ); 
          return array( $r,  $g, $b ); 
    }
	*/
?>
<?php //Links
     function RefreshURL($URL){
		      echo " <script language='JavaScript'>window.location.replace('".$URL."')</script>";
	 }
?>