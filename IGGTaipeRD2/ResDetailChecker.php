<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>資源詳細檢查表</title>
</head>
<?php  //主控台
   include('PubApi.php');
   include('mysqlApi.php');
   setPost();
   DefineDatas();
   DrawButtons();
   DrawList();
   DrawChecker();
 ?>
 
 <?php //main
 	function setPost(){
	         global $typeVal;
			 $typeVal=array(array("type",$_POST["type"]));	
	}
   function DefineDatas(){
			global $URL;
			 global $typeVal;
			$URL="ResDetailChecker.php";
		    global  $data_library,$tableName;
            $ResTable="fpresdata";
			$tableName="reschecker";
			$data_library="iggtaiperd2";
		    $ResChecker=getMysqlDataArray($tableName);
			global $typeArray;
			global $TypeResCheck;
			$typeArray= getTypesArray($ResChecker,0);
			if( $typeVal[0][1]=="")return;
		    $TypeResCheck_T=filterArray($ResChecker,0, $typeVal[0][1]);
			$TypeResCheck=sortGDCodeArrays($TypeResCheck_T, 2 ,"true");
			global $fpResData;
			$fpResDataT=getMysqlDataArray($ResTable);
			$fpResDataT2=filterArray($fpResDataT,0,$typeVal[0][1]);
			$fpResData=sortGDCodeArrays($fpResDataT2 ,2,"true");
   }
    function DrawButtons(){
             global $typeArray ; 
			 global $URL;
			 global $typeVal;
			 $x=20;
			 $y=60;
			 $fontColor="#ffffff";
			 sendVal_v2($URL, $sendarr,"check","xx",array(1522,0,46,18),10, $BgColor );
			 for($i=0;$i<count($typeArray);$i++){
				  $BgColor="#111111";
			   if($typeVal[0][1]==$typeArray[$i])$BgColor="#aa1111";
			      $sendarr =array( array("type",$typeArray[$i]))  ;
				  sendVal_v2($URL, $sendarr,"check",$typeArray[$i],array($x+$i*50,$y,46,18),10, $BgColor );
			 }				 
	}
 ?>
 <?php
 
    function DrawList(){
	         global $fpResData;
			 $fontSize=10;
			 $fontColor="#ffffff";
			 $x=20;
			 $y=100;
			 $BgColor="#000000";
			 for($i=0;$i<count($fpResData);$i++){
				 $msg=$fpResData[$i][2].$fpResData[$i][3];
			     DrawRect($msg,$fontSize,$fontColor,$x,$y,100,20,$BgColor);
				 DrawChecks($fpResData[$i], $y);
				 $y+=22;
			 }
	}
	
	
	function DrawChecks($data, $y){
	         global $TypeResCheck;
			 global $URL;
			 $fontSize=10;
			 $fontColor="#ffffff";
			 $x=120;
			 $BgColor="#664400";
			 $SubmitName="submit";
			 $Ch=$data[15];
			 $onOff=0;
			 for($i=0;$i<count($TypeResCheck);$i++){
				 
				// $msg=$TypeResCheck[$i][1];
				 $SubmitVal =$TypeResCheck[$i][1];
			  	 $ValArray=array(array("code",$fpResData[$i][2]),
				                 array("BaseSort",$TypeResCheck[$i][4]),
								 array("OnOff", $onOff));
			 	 $Rect=array($x,$y,50,20);
			     sendVal_v2($URL,$ValArray,$SubmitName,$SubmitVal,$Rect,  $fontSize, $BgColor ,$fontColor );
				 $x+=52;
			 }
	}
    function DrawChecker(){
	         global $TypeResCheck;
			 $fontSize=10;
			 $fontColor="#ffffff";
			 $x=120;
			 $y=80;
			 $BgColor="#224400";
			 for($i=0;$i<count($TypeResCheck);$i++){
				 $msg=$TypeResCheck[$i][1];
			     DrawRect($msg,$fontSize,$fontColor,$x,$y,50,20,$BgColor);
				 $x+=52;
			 }
	}
 ?>