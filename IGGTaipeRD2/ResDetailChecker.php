<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>資源詳細檢查表</title>
</head>
<body bgcolor="#b5c4b1">
<script type="text/javascript"> //傳遞變數
    function post_to_url(path, params, method) {
    method = method || "post";  
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);
    for(var key in params) {
        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", key);
        hiddenField.setAttribute("value", params[key]);
        form.appendChild(hiddenField);
    }
    document.body.appendChild(form);  
    form.submit();
}
</script>
<?php  //主控台
   include('PubApi.php');
   
   include('mysqlApi.php');
   setPost();
   DefineDatas();
   CheckSubmit();
   DrawButtons();
   DrawList();
   DrawChecker();
   checkResData();
 ?>
 
 <?php  //post
        function JavaPost($PostArray,$URL){
			$params="{";
			for($i=0;$i<count($PostArray);$i++){
			    $params=$params."'".$PostArray[$i][0]."':'".$PostArray[$i][1]."'";
		    	if(count($PostArray)>1) $params=$params.",";
			}
			$params=$params."}";
		    $javaCom=  "post_to_url('".$URL."', ".$params.");";
            echo "<script language='JavaScript'>".$javaCom."</script>"; 
      }
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
			 global $ResTable ;
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
			global $fpResData,$fpResDataT;
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
		     //檢查按鈕
			 $sendarr =array(  array("checks",$_POST["type"]))  ;
			 sendVal_v2($URL, $sendarr,"check","xx",array(1122,0,46,18),10, $BgColor );
			 for($i=0;$i<count($typeArray);$i++){
				  $BgColor="#111111";
			   if($typeVal[0][1]==$typeArray[$i])$BgColor="#aa1111";
			      $sendarr =array( array("type",$typeArray[$i]))  ;
				  sendVal_v2($URL, $sendarr,"check",$typeArray[$i],array($x+$i*50,$y,46,18),10, $BgColor );
			 }		
	}
 ?>
 
<?php  //submit
     function CheckSubmit(){
	         if( $_POST["BaseSort"]=="")return;
			 PregressUpdate();
			   
	 }
     function PregressUpdate(){
              global  $data_library,$tableName;
			  global $URL;
              global $ResTable ;
		      $WHEREtable=array( "gdcode", "data_type" );
		      $WHEREData=array( $_POST["code"],$_POST["type"]);
			  echo ">>".$_POST["BaseSort"].">".$_POST["code"].">".$_POST["fin"];
			  $Base=array( "checker");
			  $nstr= reFinCode($_POST["BaseCode"],$_POST["BaseSort"],$_POST["fin"],$_POST["Checkcount"]);
			  $up=array(  $nstr);
			  $stmt= MakeUpdateStmt(  $data_library, $ResTable,$Base,$up,$WHEREtable,$WHEREData);
			  SendCommand($stmt,$data_library);
		      global $PostArray;
			  $PostArray=array(array("type",$_POST["type"]));	
			   ReLoad();
	 } 
     function ReLoad(){
	    	   global $PostArray,$URL;
			   JavaPost($PostArray,$URL); 
	  }
     function reFinCode($BaseFinCode,$sort,$fin,$Checkcount){
		      echo "=".$Checkcount;
	          $str=explode("_",$BaseFinCode); //根據空格切
			  $nStr="";
			  $f="1";
			  if($fin=="1") $f="0";
			  for($i=1;$i<=$Checkcount;$i++){
				  $nStr =$nStr."_".$str[$i];
			      if($i==($sort-2)){
				     $nStr=$nStr."_".$f;
				  }else{
				  
				  }
			  }
			  return $nStr;
	 }

?> 
 
 <?php //列印
 
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
				 $BgColor="#666666";
				 $SubmitVal =$TypeResCheck[$i][1];
				 $fin=checkfin($data,$TypeResCheck[$i][4],$TypeResCheck[$i][5],$i);
				 if($fin=="ResFin"){
			    	$BgColor="#227722";
					 DrawRect( $SubmitVal,$fontSize,$fontColor,$x,$y,50,20,$BgColor);
				 }
				 if($fin=="Res"){
				    $BgColor="#555555";
					DrawRect( $SubmitVal,$fontSize,$fontColor,$x,$y,50,20,$BgColor);
				 }
				 if($fin=="1" or $fin=="0"){
					 if($fin=="1")$BgColor="#226622"; 
			      	 $ValArray=array(array("code",$data [2]),
					             array("type",$_POST["type"]),
				                 array("BaseSort",$TypeResCheck[$i][4]),
						         array("BaseCode",$data[15]),
								 array("fin", $fin),
							     array("Checkcount", count($TypeResCheck)) );
			    	 $Rect=array($x,$y,50,20);
			         sendVal_v2($URL,$ValArray,$SubmitName,$SubmitVal,$Rect,  $fontSize, $BgColor ,$fontColor );
				 }
				 $x+=52;
			 }
	}
 ?>
 
  <?php //檢查
 
    function checkfin($data,$BaseSort,$ResSort,$sort){
	         global $TypeResCheck;
			 if($ResSort!=""){
				 if(strpos($data[$ResSort],'已完成') !== false){ 
			        return "ResFin";
				 }else{
				     return "Res";
				 }
			 }
			 $str=explode("_",$data[15]); //根據空格切
			 $t=$str[$sort];
			 if($t=="")$t="0";
			 return   $t;
	}
 
    function checkResData(){ //檢查英雄製作資料
	        if ($_POST["checks"]=="")return;
		        echo "xx".$_POST["checks"];
		     global $ResTable ;
	         $fpResDataT=getMysqlDataArray($ResTable);
			 $fpResDataT2=filterArray($fpResDataT,0,$_POST["checks"]);
			 $fpResData=sortGDCodeArrays($fpResDataT2 ,2,"true");
		     for($i=0;$i<count($fpResDataT2);$i++){
			     echo "</br>".$fpResDataT2[$i][3];
			  }
	}
  
 ?>