<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>外包資料表</title>
</head>
<?php
    include('PubApi.php');
	include('mysqlApi.php');
		$data_library= "iggtaiperd2";
		$tableName="outsourcing";
        DrawMainUI();
	    UpData();
  
    function DrawMainUI(){
		global $tableName;
		global $data_library;
		global $width,$TableType,$Names;
		global $BaseURL, $BackURL;
		global $BaseData;
		global $radio_1,$radio_2;
		$data_library= "iggtaiperd2";
		$tableName="outsourcing";
		global $tables;
		$tables=returnTables($data_library, $tableName );
      	$datasTmp= getMysqlDataArray($tableName); 	//0名稱 1序號 2尺寸 3類別
		$BaseData= filterArray($datasTmp,0,"data");
		$width=returnDataArray($datasTmp,0,"size")   ;
		$TableType=returnDataArray($datasTmp,0,"type")   ;
		$Names=returnDataArray($datasTmp,0,"name")   ;
		$types_1=array("角色","概念圖","模型","場景","VFX","UI");
		$radio_1=array("個人","工作室");
		$radio_2=array("差","稍差","普通","可","優");
		$x=20;
		$y=60;
	    $BaseURL="Outsourcing.php";
	     DrawRect("外包資源列表","22","#ffffff","20","20","1400","30","#000000");
		DrawTitle($Names,$x,$y,"#222222","#ffffff");
 
        DrawOutsourcings($BaseData,$y);
	}
	function DrawOutsourcings($Datas,$y){
		global $LastSn;
        $x=20;
	    $y+=30;
	    $LastSn=0;
        $colors= array("#dddddd","#eeeeee");
	    $fontColor="#000000";
		$c=0;
	     for($i=0;$i<count($Datas);$i++){
			 $color=$colors[$c];
	         DrawOutsourcing($Datas[$i],$x,$y,$color,$fontColor)  ;
		     $y+=42;
			 $c+=1;
			 if($c>=count($colors))$c=0;
	        }
	    DrawAdd($y,$LastSn);
	}
    function DrawTitle($Data,$x,$y,$color,$fontColor){
		global $width,$TableType;
        $x=20;
	    for($i=0;$i<count($Data);$i++){
			//echo $TableType[$i];
			if($TableType[$i]!="hide"){
			    $w=$width[$i];
		        DrawRect($Data[$i],"12",$fontColor,$x,$y,$w,"20",$color);
	         	$x+=$w+2;
			}
	    }
    }	
    function DrawOutsourcing($Data,$x,$y,$color,$fontColor){
	     global $width,$TableType;
		 global $BaseURL, $BackURL;
	     global $LastSn;
	     global $radio_1,$radio_2;
		 global $tables;
	   		    $x=20;
				$h=40;
				$bc=$color;
	      for($i=0;$i<count($Data);$i++){
		      if($TableType[$i]!="hide")  $w= $width[$i];
		 	  switch($TableType[$i]){
			         case $TableType[$i]=="string" :
				          DrawRect($Data[$i],"12",$fontColor,$x,$y,$w,$h,$color  );
			              break;
				     case $TableType[$i]=="radio_1" :
				          DrawRect($Data[$i],"12",$fontColor,$x,$y,$w,$h,$color);
			              break;
					 case $TableType[$i]=="radio_2" :
					 	   $pic="Pics/star.png";
						   $n=returnArrayNum($radio_2,$Data[$i]);
						   DrawRect("","12",$fontColor,$x,$y,$w,$h,$color);
						   if($n==4){
						      $pic="Pics/crow.png";
						      DrawPosPic($pic,($y+2),$x+12 ,30,30,"absolute" );
						   }else{
							  for($s=0;$s<=$n;$s++) 
							      DrawPosPic($pic,( $y+16),$x +($s*12),12,12,"absolute" );
							}
			              break;
				     case $TableType[$i]=="time" :
  						   $time_d=date("d日H時",(time()+(8+$Data[$i])*3600));
				           DrawRect( $time_d,"12",$fontColor,$x,$y,$w,$h,$color);
			              break;  
				     case $TableType[$i]=="Link" :
				          $Link=$Data[$i];
				          DrawLinkRect_newtab("Link","12",$fontColor,$x,$y,$w,$h,$color,$Link,1);
			              break; 
					case  (strpos($TableType[$i],'pic') !== false) : //$TableType[$i]=="pic" :
			        	  $pic="Outsourcing/".$tables[$i]."/".$Data[$i];
						   DrawLinkPic($pic,$y,$x,$w,$h,  $pic);
						  break;
		   		    case  (strpos($TableType[$i],'file') !== false) :  
			        	   $Link="Outsourcing/".$tables[$i]."/".$Data[$i];
						   if(file_exists($Link) && $Data[$i]!=""){
						   $pic="Pics/excel.png";
						   DrawLinkPic($pic,$y,$x,$w,$h,$Link);
						   }
						  break;
					case  $TableType[$i]=="bool" :
					      $bool=$Data[$i];
						  if($bool=="")$bool="null";
						  $color="#444444";
						  if($bool=="true")$color="#eeffee";
					  //    $Link=$BaseURL."?Edit=DNA&code=".$Data[1]."&bool=".$bool;
						  $ValArray=array(array("Edit",$tables[$i]),array("code",$Data[1]),array("bool",$bool));
						  sendVal($BaseURL,$ValArray,"submit",$tables[$i],array($x,$y,$h,$h),10,$color, "#000000");
						 // DrawLinkRect($bool,"12",$fontColor,$x,$y,$w,$h,$color,$Link,1);
						  $color=$bc;
					      break;
						  }
	    	    $x+=$w+2;
 
	         }
			 $Link=$BackURL."?Edit=form&code=".$Data[1];
			 $ValArray=array(array("Edit","form"),array("code",$Data[1]));
			 sendVal($BaseURL,$ValArray,"submit","Edit",array($x,$y,$h,$h),10,"#441122", "#ffffff");
			// DrawLinkRect("Edit","10","#ffffff",$x,$y,$h,$h,"#441122",$Link,1);
			 
    }	
	function DrawAdd($y,$LastSn){
			  global   $BaseURL;
	          $x=20;
		      $y+=30;
		      $BgColor="#881122";
	      	  $Link=$BackURL."?Add=yes";
			  
		     // DrawLinkRect("+新增外包","12","#ffffff",$x,$y,1400,"20",$color,$Link,1);
			  $ValArray=array(array("Add","yes"));
			  $Rect=array($x,$y,"1400","20");
			  sendVal($BaseURL,$ValArray,"submit","+新增外包",$Rect,12, $BgColor , "#ffffff" );
	}
?>
<?php //Data
    function UpData(){
		$Edit=$_POST['Edit'];
	 
		$Add=$_POST['Add'];
        $submit=$_POST['submit'];
		global  $BackURL;
	    global $submit;
		if( $Add=="yes")   AddData();
		if($Edit!=""){
			if($Edit=="form") EditData();
		    if($Edit=="NDA") Changebool("NDA",$_POST["bool"],$_POST["code"]);
			if($Edit=="Active") Changebool("Active",$_POST["bool"],$_POST["code"]);
		}
		if($submit=="修改"){
		 UpEdit();
		}
	}
	function Changebool($Table,$bool,$code ){
		      echo ">".$Table;
		      global	 $BaseURL,  $tableName,$data_library;
			  $WHEREtable=array("data_type", "Code");					 
              $WHEREData=array("data",$code);	
			  $upbool="false";
			  if($bool=="null" or $bool=="false" )$upbool="true";
			  $Base=array($Table);
			  $up=array($upbool);
			  $stmt= MakeUpdateStmtv2(  $tableName,$Base,$up,$WHEREtable,$WHEREData);	
			  echo $stmt;
			  SendCommand($stmt,$data_library);
			  echo " <script language='JavaScript'>window.location.replace('".$BaseURL."')</script>";
	}		
	function UpEdit(){
		      global $tableName,$data_library;
			  global $BaseURL, $BackURL;
			  global $BaseData;
			  global $tables;
		     // $tables=returnTables($data_library, $tableName );
			  $Base=array();
			  $t= count( $tables);
		      for($i=0;$i<$t;$i++){
	       	      global $$tables[$i];
				  $tmp=$tables[$i];
				  array_push($Base, $tmp);
		         }
			  $code=$$tables[1];
		      $up=array();
			  $file="works";
			  $data= returnDataArray($BaseData,1,$Code)   ;
	          for($i=0;$i<$t;$i++){
				    $d=$$tables[$i];
				    if($d=="")$d=$data[$i];
					if($_FILES[$tables[$i]]["name"]!=""){
						$d=$_FILES[$tables[$i]]["name"];
					    $ext = explode(".",$d);
					    $upFile= "Outsourcing/".$tables[$i]."/".$code.".".$ext[1];
						echo $upFile;
					    move_uploaded_file($_FILES[$tables[$i]]["tmp_name"], $upFile);//$upFloder.$_FILES[$tables[$i]]["name"]);
					    $d=$code.".".$ext[1];
					}
				    array_push($up,$d);
		          }
		     $WHEREtable=array("data_type", "Code");					 
             $WHEREData=array($$tables[0],$$tables[1]);	
	         $stmt= MakeUpdateStmtv2(  $tableName,$Base,$up,$WHEREtable,$WHEREData);	
			 SendCommand($stmt,$data_library);
		     echo " <script language='JavaScript'>window.location.replace('".$BaseURL."')</script>";
	}
	
	function EditData(){
	      global $tableName,$data_library;
           $code=$_POST["code"];	
		  global $BaseURL;
		  global $colorCodes;
		  global $BaseData;
	      global $width,$TableType,$Names;
		  global $radio_1,$radio_2;
		  
		  $x=300;
		  $y=100;
		  $w=800;
		  $h=400+count( $Names)*20;
	      DrawPopBG($x,$y,$w,$h,"修正外包資料" ,"12",$BaseURL);
		  $data= returnDataArray($BaseData,1,$code)   ;
		  $tables=returnTables($data_library, $tableName );
		  $y-=60;
          echo   "<form id='EditOut'  name='Show' action='Outsourcing.php'  method='post'  enctype='multipart/form-data'>";
		  for($i=0;$i<count( $tables);$i++){
			   $y+=32;
			      switch ($TableType[$i]){
				        case $TableType[$i]=="string" :
				             $input="<input type=text name=".$tables[$i]." 	value='".$data[$i]."' style= font-size:10px;  size=50  >";
				             DrawInputRect($Names[$i]." ","12","#ffffff",($x ),$y,420,20, $colorCodes[4][2],"top", $input);
				        break;
					    case $TableType[$i]=="radio_1" :
						     DrawRadio($radio_1,$data[$i],$tables[$i],$x,$y,100,20);
						break;
					    case $TableType[$i]=="radio_2" :
						     DrawText($Names[$i],$x,$y,100,20,12,"#ffffff");
						     DrawRadio($radio_2,$data[$i],$tables[$i],$x+70,$y,50,20);
						break;
						case $TableType[$i]=="Link" :
						     $input="<input type=text name=".$tables[$i]." 	value='".$data[$i]."'  size=60   >";
				             DrawInputRect($Names[$i]." ","12","#ffffff",($x ),$y,420,20, $colorCodes[4][2],"top", $input);
						break;
						case (strpos($TableType[$i],'pic') !== false):
						      $input="<input type=file name=".$tables[$i]." 	id=".$tables[$i]."  size=60   >";
							  DrawInputRect($Names[$i]." ","12","#ffffff",($x ),$y,420,20, $colorCodes[4][2],"top", $input);
						break;
						case (strpos($TableType[$i],'file') !== false):
						      $input="<input type=file name=".$tables[$i]." 	id=".$tables[$i]."  size=60   >";
							  DrawInputRect($Names[$i]." ","12","#ffffff",($x ),$y,420,20, $colorCodes[4][2],"top", $input);
						break;
					    case $TableType[$i]=="time" :
						       $input="<input type=text name=".$tables[$i]." 	value='".$data[$i]."'  size=50  >";
				             DrawInputRect($Names[$i]." ","12","#ffffff",($x ),$y,420,20, $colorCodes[4][2],"top", $input);
						break;
					    case $TableType[$i]=="bool" :
						     $input="<input type=text name=".$tables[$i]." 	value='".$data[$i]."'  size=5  >";
				             DrawInputRect($Names[$i]." ","12","#ffffff",($x ),$y,420,20, $colorCodes[4][2],"top", $input);
						break;
				  }
				  if($TableType[$i]=="hide" or $TableType[$i]=="type"){
					  echo "<input type=hidden name=".$tables[$i]." 	value='".$data[$i]."'   >";   
				  }
				  
			  }
		  $submitP="<input type=submit name=submit value=修改>";
	      DrawInputRect("","12","#ffffff",($x+350),$y ,120,18, $colorCodes[4][2],"top",$submitP);
	}
	function DrawRadio($radio,$Base,$table,$x,$y,$w,$h){
	         for($j=0;$j<count($radio);$j++){
				 $isCheck="";
				 if( $radio[$j]==$Base) $isCheck="checked=true";
				  $input="<input type=radio name=".$table." value=".$radio[$j]." ".$isCheck.">";
				  DrawInputRect($radio[$j]." ","12","#ffffff",($x+$j*$w ),$y,$w,$h, $colorCodes[4][2],"top", $input);
			  }   
	}
    function AddData(){
		 global $tableName,$data_library;
         global $Add;	
		 global $BaseURL, $BackURL;
		 $sn=returnDataCode( ); //getDBLastSn( $data_library, $tableName,1);
		 $tables=returnTables($data_library ,$tableName);
		 $WHEREtable=array();
		 $WHEREData=array();
		 for($i=0;$i<count($tables);$i++){
		     array_push( $WHEREtable,$tables[$i]);
			 if($i==0)array_push($WHEREData,"data");
			 if($i==1)array_push($WHEREData,$sn);
		     if($i>1)array_push($WHEREData,"");
		 }

	     $stmt= MakeNewStmtv2($tableName,$WHEREtable,$WHEREData);
		 SendCommand($stmt,$data_library);
	     echo $stmt;
		 echo " <script language='JavaScript'>window.location.replace('".$BaseURL."')</script>";
	}
?>
