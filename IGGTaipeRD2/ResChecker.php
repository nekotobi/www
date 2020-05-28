<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>資源檢查區</title>
</head>
<?php  //主控台
   $id=$_COOKIE['IGG_id'];
   include('PubApi.php');
   include('mysqlApi.php');
   include('ResCheckerjavaApi.php');
   setPost();
   DefineDatas();
   DrawButtons();
   DrawCheckers();
   fastTask();
   checkSubmit();
 ?>
 
 <?php //預設資料`
    function DefineDatas(){
			 global $URL;
			 $URL="ResChecker.php";
			 global $typeVal;
			 global $ResChecker;
			 global  $data_library,$tableName;
             $tableName="reschecker";
			  $data_library="iggtaiperd2";
		     $ResChecker=getMysqlDataArray($tableName);
			 global $typeArray;
			 $typeArray= getTypesArray($ResChecker,0);
			 global $TypeResCheck;
             if( $typeVal[0][1]!=""){
			    $TypeResCheck=filterArray($ResChecker,0, $typeVal[0][1]);
			 }
	}
	function setPost(){
	         global $typeVal;
			 $typeVal=array(array("type",$_POST["type"]));
	}
    function DrawCheckers(){
	         global $TypeResCheck;
			 $x=20;
			 $y=60;
			 $w=60;
			 $h=20;
			 $fontSize=10;
			 $fontColor="#ffffff";
			 $BgColor="#222222";
			 for($i=0;$i<count($TypeResCheck);$i++){
				 $msg=$TypeResCheck[$i][1];
				 $id=$i;
		         DrawJavaDragbox($msg,$x,$y,$w,$h,$fontSize,$BgColor,$fontColor,$id);
				  $x+=$w+2;
			 }
	}
   function fastTask(){
	   	      global $typeArray;
			  global $TypeResCheck;
	          $x=20+count($typeArray)*65;
			  $y=40;
			  if($_POST["type"]!="")$y=60;
			  if($_POST["type"]!="")$x=20+count($TypeResCheck)*62;
			  global $URL;
		      global $typeVal;
			  $BgColor="#ffffff";
			  $fontColor="#000000";
			  $upFormVal=array("add","add",$URL);
			  $UpHidenVal=array(array("tablename","reschecker"),
							    );			
			  $UpHidenVal=		addArray( $UpHidenVal,$typeVal);			
			  $inputVal=array(array("text","addChecker","",10,$x,$y,120,20,$BgColor,$fontColor,"" ,10),
                              array("submit","submit","",10,$x+90,$y,50,20,$BgColor,$fontColor,"新增" ,20),
			                  );					  
			  upSubmitform($upFormVal,$UpHidenVal, $inputVal);
	 }
	 function  checkSubmit(){
	           global $typeVal; 
	           echo $_POST["addChecker"];
			   echo $typeVal[0][1];
			   if($submit=="新增"){

				   FastAddMysQLData($data_library,$tableName,$code,$URL,$sendVal);
			   }
	 }
 ?>
 
 <?php
     function DrawButtons(){
             global $typeArray ; 
			 global $URL;
			 $x=20;
			 $y=40;
		     $type=$_POST["type"];
			 	 $fontColor="#ffffff";
			 for($i=0;$i<count($typeArray);$i++){
				 $BgColor="#111111";
				  if( $type==$typeArray[$i])$BgColor="#aa1111";
			     $sendarr =array( array("type",$typeArray[$i]))  ;
			     sendVal($URL, $sendarr,"change",$typeArray[$i],array($x+$i*50,$y,46,18),10,$BgColor, $fontColor);
				   
			 }				 
	}
	function DrawButton($typeName ,$x,$y){
			 $type=$_POST[$typeName];
			 global $URL;
		     global $typeVal;
			 $sendarr=$typeVal;
			 $fontColor="#ffffff";
	         for($i=0;$i<count($typeRangeArray) ;$i++){
			      $BgColor="#111111";
				  if($typeVal[$tn][1]==$typeRangeArray[$i])$BgColor="#aa1111";
				   $sendarr[$tn]= array($typeName,$typeRangeArray[$i])  ;
				   sendVal($URL, $sendarr,"change",$typeRangeArray[$i],array($x,$y,46,18),10,$BgColor, $fontColor);
				   $x+=50;
			 }				 
	}
 ?>
 