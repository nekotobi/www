<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>資源檢查區</title>
</head>
<?php  //主控台
   $id=$_COOKIE['IGG_id'];
 
   include('PubApi.php');
   include('mysqlApi.php');
   setPost();
   DefineDatas();
   CreatJavaForm();
   include('ResCheckerjavaApi.php');
   checkSubmit();
   DrawButtons();
   DrawCheckers();
   fastTask();
    
 
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
			    $TypeResCheck_T=filterArray($ResChecker,0, $typeVal[0][1]);
				$TypeResCheck=sortGDCodeArrays($TypeResCheck_T, 2 ,"true");
			 }
	}
	function setPost(){
	         global $typeVal;
			// echo ">>".$_POST["type"];
			 $typeVal=array(array("type",$_POST["type"]));	
	}
    function DrawCheckers(){
	         global $TypeResCheck;
			 $x=20;
			 $y=80;
			 $w=60;
			 $h=16;
			 $fontSize=10;
			 $fontColor="#ffffff";
			 $BgColor="#222222";
			 $BgColor2="#225533";
			 for($i=0;$i<count($TypeResCheck);$i++){
				 $msg=$TypeResCheck[$i][1];
				 $id="S=".($i+1)."=".$TypeResCheck[$i][0]."=".$TypeResCheck[$i][1];
		         DrawJavaDragbox($msg ,$x,$y,$w,$h,$fontSize,$BgColor,$fontColor,$id);
				 $id="E=".($i+1)."=".$TypeResCheck[$i][0]."=".$TypeResCheck[$i][1];
				 DrawJavaDragArea($i,$x,$y+20,$w,$h,$BgColor2,$fontColor,$id);
				 $x+=$w+2;
			 }
	}
    function fastTask(){
	   	      global $typeArray;
			  global $TypeResCheck;
	          $x=20+count($typeArray)*65;
			  $y=60;
			  if($_POST["type"]!=""){
				  $y=80;
			      $x=20+count($TypeResCheck)*62;
			  }
			  global $URL;
		      global $typeVal;
			  $BgColor="#ffffff";
			  $fontColor="#000000";
			  $upFormVal=array("add","add",$URL);
			  $UpHidenVal=array(array("tablename","reschecker"),
			                    array("type",$typeVal[0][1]),
							    );			
			  $UpHidenVal=		addArray( $UpHidenVal,$typeVal);			
			  $inputVal=array(array("text","addChecker","",10,$x,$y,120,20,$BgColor,$fontColor,"" ,10),
                              array("submit","submit","",10,$x+90,$y,50,20,$BgColor,$fontColor,"新增" ,20),
			                  );	
              if($_POST["type"]==""){
			     array_push( $inputVal,array("text","addtype","",10,$x ,$y+20,120,20,$BgColor,$fontColor,"" ,10));
			  }				   
			  upSubmitform($upFormVal,$UpHidenVal, $inputVal);
	 }
 
     function CreatJavaForm(){
		      $x=20;
			  $y=10;
		      global $URL;
			  global $typeName,$typeArray;
			  global $typeVal;
		      $upFormVal=array("Show","Show",$URL);
		 
			  $UpHidenVal=array(array("tablename","reschecker"),
			                    array("data_type","data"),
								array( "Send","sendjava" ),
								array( "type",$typeVal[0][1]),
								array("changeSort","true"),
								//array( "type2",$typeVal[0][1]),
	                            );
		      $UpHidenVal=	addArray( $UpHidenVal,$typeArray);	
		      $inputVal=array(array("text","DragID","DragID",10,520,$y,200,20,$BgColor,$fontColor,"" ,12),
			                   array("text","target","target",10,670,$y,200,20,$BgColor,$fontColor,"" ,12),
	                          );		 
		      upSubmitform($upFormVal,$UpHidenVal, $inputVal);

	 }
	function checkSubmit(){
		       global $URL;
	           global $typeVal; 
		       global  $data_library,$tableName;
			   global $TypeResCheck;
			   $lastSn=getLastGDSN($TypeResCheck,2 ) ; 
               $lastSn+=1;
			   $code=returnDataCode( );
			 //  echo $_POST["submit"];
			 //  echo "</br></br></br></br></br>";
			  // echo ">".$typeVal[0][1].">".$_POST["addChecker"];
			   $datat=$typeVal[0][1];
			   $name=$_POST["addChecker"];
	           $sort= $lastSn;
	 
			   if($typeVal[0][1]==""){
				   $datat=$_POST["addChecker"];
			       $name=$_POST["addtype"];
			   }
			   if($_POST["submit"]=="新增"){
                   $sendVal=array(data_type=> $datat,
				                  name=>$name,
								  sort=>$sort,
								  code=>returnDataCode( ),
								  BaseSort=>$lastSn
								  );
				   FastAddMysQLDataV2($data_library,$tableName,$URL,$sendVal);
				  global $PostArray;
				  $PostArray=array("type");
				  ReLoad();
			   }
		        if($_POST["changeSort"]!=""){
				  $BaseD= explode("=",$_POST["DragID"]);  
			      $TargetD= explode("=",$_POST["target"]);
				  //base
				  $WHEREtable=array( "data_type", "name" );
		          $WHEREData=array( $BaseD[2],$BaseD[3]);
			      $Base=array("sort");
			      $up=array($TargetD[1]);
                  $stmt=   MakeUpdateStmtv2($tableName,$Base,$up,$WHEREtable,$WHEREData);
				  echo $stmt;
				  echo "</br>";
				   SendCommand($stmt,$data_library);	
				 
				  //target
				  $WHEREtable=array( "data_type", "name");
		          $WHEREData=array($TargetD[2],$TargetD[3]);
			      $Base=array("sort");
			      $up=array($BaseD[1]);
                  $stmt=   MakeUpdateStmtv2($tableName,$Base,$up,$WHEREtable,$WHEREData);
				 	  echo $stmt;
				   SendCommand($stmt,$data_library);	
				  echo "</br>";	
                  				  
				  global $PostArray;
				  $PostArray=array("type");
				   ReLoad();
			   }
			   
	 }
 ?>
 
 <?php
    function ReLoad(){
	    	   global $PostArray,$URL;
			  
			    JavaPost($PostArray,$URL); 
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
			   //  sendVal($URL, $sendarr,"change",$typeArray[$i],array($x+$i*50,$y,46,18),10,$BgColor, $fontColor); 
			 }				 
	}
	/*
    function DrawButtons(){
             global $typeArray ; 
			 global $URL;
			 global $typeVal;
			 $x=20;
			 $y=60;
		     //$type=$_POST["type"];
			 $fontColor="#ffffff";
			 
			 for($i=0;$i<count($typeArray);$i++){
				  $BgColor="#111111";
				  if($typeVal[0][1]==$typeArray[$i])$BgColor="#aa1111";
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
	*/
 ?>
 