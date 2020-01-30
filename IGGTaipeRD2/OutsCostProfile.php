<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>外包成本概況</title>
</head>
 
<body bgcolor="#b5c4b1"> 
<?php
    $id=$_COOKIE['IGG_id'];
    include('PubApi.php');
    include('mysqlApi.php');
	DefineDatas();
	TypeButton();
    view();
	Edit();
    upData();
	function DefineDatas(){
		  	 global  $BassClass,$ClassArray,$emptyClass;
		     global $outs,$outsourcing, $Outsdetail;
		     $OutsdetailT=getMysqlDataArray("outsdetail"); 
			 $Outsdetail=filterArray( $OutsdetailT,0,"outs"); 
			 $outsT=getMysqlDataArray("fpoutsourcingcost"); 
	         $outs=filterArray($outsT,0,"cost");
		     $emptyClass=filterArray($Outsdetail,12,"");
		     $BassClassT=filterArray($OutsdetailT,0,"class");
	         $BassClass=  ReturnArrayBySort( $BassClassT,3);
		     $ClassArray= ArrayClassSort($emptyClass,3);
			 $outsourcing=getMysqlDataArray("outsourcing"); 
			 getCurrencys();
			 
	}
	function getCurrencys(){
	         global $outs,$outsourcing ;
			 global $SnCurrencys;
			 $SnCurrencys=array();
             for($i=0;$i<count($outs);$i++){
				 $outsCode=$outs[$i][15];
				 $sn=$outs[$i][1];
				 $t=filterArray($outsourcing,1,$outsCode);
				 $SnCurrencys[$sn]=$t[0][30];
	         }
	}
    function TypeButton(){
		    global $viewType;
		    $URL="OutsCostProfile.php";
			$Rect=array(20,20,100,20);
			$viewType =$_POST["viewType"];
			$BgColor="#000000";
			$SubmitName="viewType";
			$SubmitVal="View";
			if($viewType=="View" or $viewType=="")$BgColor="#ee2222";
			$ValArray=array(array("viewType","View"));
	        sendVal($URL,$ValArray,$SubmitName,$SubmitVal,$Rect,12, $BgColor ) ;
			$SubmitVal="Edit";
			$BgColor="#000000";
			$ValArray=array(array("viewType","Edit"));
			if(  $viewType=="Edit")$BgColor="#ee2222";
			$Rect[0]+=110;
		    sendVal($URL,$ValArray,$SubmitName,$SubmitVal,$Rect,12, $BgColor ) ;	
	}
	function View(){
	    global $viewType;
	    if($viewType!="View"  )return;
		ViewList();
	}
	function Edit(){
	    global $viewType;
	    if(  $viewType!="Edit")return;
		 ListClass( );
 
	}
?>

<?php //view
      function ViewList(){
		     global   $BassClass,$ClassArray,$emptyClass;
	    	 global   $Outsdetail;
			 echo "</br></br></br></br></br></br>";
			 echo "<table border=1  border-collapse: collapse>";
			 echo "<tr><td >类型</td><td>内部设计数理</td><td>外包数量</td><td>外包金额</td><td>外包金额(nt)</td>";
			 for($i=0;$i<count($BassClass);$i++){
			     echo "<tr><td>";
				 echo  $BassClass[$i];
				 echo "</td>";
				 echo "<td></td>";
			     $t= Collect($BassClass[$i], $Outsdetail);
				 echo "<td>";
				 echo $t[0];
				 echo "</td>";
				  echo "<td>";
				 echo $t[1]*0.23;
				 echo "</td>";
				 echo "<td>";
				 echo $t[1];
				 echo "</td>";
				 echo  "</tr>";
			 }
			 echo "</table>";
	  }
      function Collect($type,$BaseData){
		  
		   $Ar=array();
		   $a=filterArray($BaseData,12,$type);
		   $total=0;
		   $totalNum=0;
	       for($i=0;$i<count($a);$i++){
			   $totalNum+=$a[$i][6];
			   $cost= returnCost( $a[$i][8],$a[$i][1])  ;
			   $total+= $cost  ;
	       }
		   return array( $totalNum,$total);
	  }
      function returnCost($cost,$sn){
	          global $SnCurrencys; 
			  $Currencys=$SnCurrencys[$sn];
			  switch ( $Currencys){
				  case "美金":
				  return $cost*30.29;
				  case "人民幣":
				  return $cost*4.36;
			  }
			  return $cost;
	  }
	  
?>
 
<?php //Edit
    function ListClass( ){
	     global  $BassClass,$ClassArray,$emptyClass;
	     $x=10;
		 $y=100;
		 $w=100;
		 $h=20;
		 $fontColor="#ffffff";
		 $BgColor="#000000";
		 $BaseURL="OutsCostProfile.php";
	     echo "<form action=".$BaseURL." method=post >";
		 echo count($ClassArray);
	     for($i=0;$i<count($ClassArray);$i++){
			 DrawRect($ClassArray[$i],12,$fontColor,$x,$y,$w,$h,$BgColor); 
		     $input=	MakeSelectionV2($BassClass,$s,"Ch".$i,10);
			 DrawInputRect("對應類別",10,"#222222",$x+100,$y , 300,$h,$BgColor,$WorldAlign,$input);
		    // echo "</br>".$ClassArray[$i];
			$y+=22;
		 }
		  $submitP="<input type=submit name=submit value=送出 style= font-size:10px; >";
	      DrawInputRect("",10 ,"#ffffff",$x,$y,$w,$h, $colorCodes[4][2],"top",$submitP);
		  echo "</form>";
	}
?>
<?php //up
      function upData(){
		  echo $_POST['submit'];
	      if($_POST['submit']!="送出")return;	  
		  
             global  $BassClass,$ClassArray,$emptyClass;
			  for($i=0;$i<count($ClassArray);$i++){
				  $n="Ch".$i;
			      if($_POST[$n]!="未定義"){
                     UpData2( $ClassArray[$i],$_POST[$n]);
			    	 echo  $ClassArray[$i] .">".$_POST[$n];
				}
			  }
	  }
      function UpData2($type,$class){
		   global   $emptyClass;
		   	 $data_library="iggtaiperd2"; 
			 $table="outsdetail";
		   for($i=0;$i<count($emptyClass);$i++){
		       if($emptyClass[$i][3]==$type){
				  $OutsSn=$emptyClass[$i][1];
				  $sn=$emptyClass[$i][2];
			      $WHEREtable=array( "data_type","OutsSn","sn");
		          $WHEREData=array( "outs",$OutsSn,$sn);
		          $Base=array("class");
		          $up=array($class);
		          $stmt= MakeUpdateStmt(  $data_library, $table,$Base,$up,$WHEREtable,$WHEREData);
		          echo $stmt;
		           SendCommand($stmt,$data_library);
			   }
		   }
	  }
	  
?>


<?php
    function ArrayClassSort($array,$sort){
	         $Ra=array();
			 for($i=0;$i<count($array);$i++){
			     if (!in_array($array[$i][$sort],  $Ra)) {
					Array_push( $Ra,$array[$i][$sort]);
				 }
			 }
			 return $Ra;
	}
?>