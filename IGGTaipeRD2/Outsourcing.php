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
  	    //returnTables($data_library,$tableName,"outsourcing");
        DrawMainUI();
	    UpData();

    function DrawMainUI(){
		global $tableName;
		global $data_library;
		global $width,$TableType,$Names;
		global $BackURL;
		global $BaseData;
		global $radio_1,$radio_2;
		$data_library= "iggtaiperd2";
		$tableName="outsourcing";
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
	    $BackURL="Outsourcing.php";
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
	     global $LastSn;
	   		    $x=20;
				$h=40;
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
				           DrawRect($Data[$i],"12",$fontColor,$x,$y,$w,$h,$color);
			              break;
				     case $TableType[$i]=="time" :
  						   $time_d=date("d日H時",(time()+(8+$Data[$i])*3600));
				           DrawRect( $time_d,"12",$fontColor,$x,$y,$w,$h,$color);
			              break;  
				     case $TableType[$i]=="Link" :
				          $Link=$Data[$i];
				          DrawLinkRect("Link","12",$fontColor,$x,$y,$w,$h,$color,$Link,1);
			              break; 
					case  $TableType[$i]=="pic" :
			        	  $pic="Outsourcing/pic/".$Data[$i];
					      DrawPosPic($pic, $y,$x,$h,$h,"fixed" );
					       
						  break;
		        	}
	    	    $x+=$w+2;
				
				
	         }
			 $Link=$BackURL."?Edit=".$Data[1];
			 DrawLinkRect("Edit","10","#ffffff",$x,$y,$h,$h,"#441122",$Link,1);
    }
	
	function DrawAdd($y,$LastSn){
			  global  $BackURL;
	          $x=20;
		      $y+=30;
		      $color="#881122";
	      	  $Link=$BackURL."?Add=yes";
		      DrawLinkRect("+新增外包","12","#ffffff",$x,$y,1400,"20",$color,$Link,1);
	}
	

	
?>
<?php //Data
    function UpData(){
		global  $Add,$Edit;
		global  $BackURL;
	    global $submit;
     	if($Add!=""){
		   AddData();
	    }
		if($Edit!=""){
		   EditData();
		}
		if($submit=="修改"){
		 UpEdit();
		}
	}
	function UpEdit(){
		      global $tableName,$data_library;
			  global $BackURL;
			  	  global $BaseData;
		      $tables=returnTables($data_library, $tableName );
			  $Base=array();
			  $t= count( $tables);
		      for($i=0;$i<$t;$i++){
	       	      global $$tables[$i];
				  array_push($Base,$tables[$i]);
		         }
		      $up=array();
			  $file="works";
			  $data= returnDataArray($BaseData,1,$Code)   ;
	          for($i=0;$i<$t;$i++){
				    $d=$$tables[$i];
					if($_FILES[$tables[$i]]["name"]!=""){
						$d=$_FILES[$tables[$i]]["name"];
					  //  move_uploaded_file($_FILES[$file]["tmp_name"],"Outsourcing/pic/".$_FILES[$file]["name"]);
					      move_uploaded_file($_FILES[$tables[$i]]["tmp_name"],"Outsourcing/pic/".$_FILES[$tables[$i]]["name"]);
					}
					
				    if($d=="")$d=$data[$i];
					//echo "[".$d."=".$data[$i]."]";
				    array_push($up,$d);
		          }
			 
		  
			 
		     $WHEREtable=array("data_type", "Code");					 
             $WHEREData=array($$tables[0],$$tables[1]);	
			// $stmt="UPDATE `outsourcing` SET `name` = 'aaa1' WHERE CONVERT( `outsourcing`.`data_type` USING utf8 ) = 'data' AND CONVERT( `outsourcing`.`Code` USING utf8 ) = '2019-06-03-180645'  LIMIT 1 ;";
	         $stmt= MakeUpdateStmtv2(  $tableName,$Base,$up,$WHEREtable,$WHEREData);	
			 SendCommand($stmt,$data_library);
		     // echo $stmt;
			  echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";
	}
	
	function EditData(){
	      global $tableName,$data_library;
          global $Edit;	
		  global $BackURL;
		  global $colorCodes;
		  global $BaseData;
	      global $width,$TableType,$Names;
		  global $radio_1,$radio_2;
		  $x=600;
		  $y=100;
		  $w=400;
		  $h=100+count( $Names)*20;
	      DrawPopBG($x,$y,$w,$h,"修正外包資料" ,"12",$BackURL);
		  $data= returnDataArray($BaseData,1,$Edit)   ;
		  $tables=returnTables($data_library, $tableName );
		  $y-=60;
          echo   "<form id='EditOut'  name='Show' action='Outsourcing.php'  method='post'  enctype='multipart/form-data'>";
		  for($i=0;$i<count( $tables);$i++){
			   $y+=30;
			      switch ($TableType[$i]){
				        case $TableType[$i]=="string" :
				             $input="<input type=text name=".$tables[$i]." 	value='".$data[$i]."'  size=50  >";
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
						case $TableType[$i]=="pic" :
						     $input="<input type=file name=".$tables[$i]." 	id=".$tables[$i]."  size=60   >";
							  DrawInputRect($Names[$i]." ","12","#ffffff",($x ),$y,420,20, $colorCodes[4][2],"top", $input);
						break;
					    case $TableType[$i]=="time" :
						       $input="<input type=text name=".$tables[$i]." 	value='".$data[$i]."'  size=50  >";
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
		 global  $BackURL;
  
		 $sn=returnDataCode( ); //getDBLastSn( $data_library, $tableName,1);
	     $WHEREtable=array("`data_type`", "`Code`","`name`","`cost`","`country`","`time_def`","`studio`","`business`","`cost_evaluate`","`quality_evaluate`","`cooperation_evaluate`","`speed_evaluate`","`Link`","`works`","`feedback`");					 
         $WHEREData=array("data", $sn, " " , " ", " " , " " , " "
                		 , " ", " " ," "," "," " ," "," "," ");							 
	     $stmt= MakeNewStmtv2($tableName,$WHEREtable,$WHEREData);
		 SendCommand($stmt,$data_library);
	     echo $stmt;
		  echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";
	}
?>
