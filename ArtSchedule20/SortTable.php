 
<!DOCTYPE html>
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>表單重新排序</title>
</head>
<body bgcolor="#b5c4b1">
<script type="text/javascript">
 function Drop2Area(event) {
		event.preventDefault();
		var DragID  = event.dataTransfer.getData("text");
		var targetID =  event.currentTarget.id;
	    var tx= document.getElementById( targetID).style.left;
	    var x=tx.split("px");
	    document.Show.DragID.value=  DragID;
	    document.Show.target.value=  targetID;
	   // Show.submit();
	}

</script>
<?php
      require_once('/Apis/PubApi20.php');
	  require_once('/Apis/mysqlApi20.php');
	  require_once('/Apis/ProjectApi.php');
	  require_once('/Apis/PubJavaApi.php');
	
	  defineData();
	  ListForm();
   //   setJavaForm();//java表單一定要最後
	
	  echo ">".$_POST["selectable"];
	    echo $_POST["submit"];

?>
<?php 
    function defineData(){
            global $data_library;  
			global $tables;
		    $data_library="iggtaiperd2"; 
			$tables= MAPI_getlibraryTables($data_library);
			global $URL;
			$URL="SortTable.php";
		    global $WebSendVal;
		    $WebSendVal=array(array("selectable",$_POST["selectable"]),
			                   array("selectableNum",$_POST["selectableNum"] ) ,
							   array("selectval",$_POST["selectval"] ) ,
                        );	
 			print_r($WebSendVal)	;	   
	}
	
 	function ListForm(){
		    global $data_library;  
            global $URL;
		 	global $tables;
			global $selectable;

			$selectable=$_POST["selectable"];
		    $upFormVal=array("SortForms","SortForms",$URL);
		    $UpHidenVal=array();
		    $inputVal=array(); 
		    $x=200;
			$y=20;
			$w=100;
			$h=20;
		    $submit=array("submit","submit" ,"","10", $x,$y,$w,$h, "#ffffff", "#fffff", "變更",20);
			//array_push($inputVal,$select);
			array_push($inputVal, $submit);
			$selectVal=array(); 
			$select=	PubApi_MakeSelection($tables,$selectable,"selectable",10);
			$Rect=array(20,20,200,20);
			array_push($selectVal,array( $select,$Rect));
		     upSubmitform($upFormVal,$UpHidenVal, $inputVal,$selectVal);
		  // upSubmitform($upFormVal,$UpHidenVal, $inputVal );
			if( $_POST["selectable"]!="")ListTableDatas();
	}
 function ListTableDatas(){
			 $selectable= $_POST['selectable'];
	         global $data_library; 
			 $tableDatas=getMysqlDataArray($selectable); 
			 $msg="選擇表單[".$selectable."]";
			 $x=20;
			 $y=60;
			 $w=200;
			 $h=14; 
			 $x+=$w+2;
			 DrawRect($data_library,10,"#ffffff",$x,$y,$w,$h,"#000000");
		     $field=  returnTables($data_library ,$selectable);
			 $y+=32;
			 $w=100;
			 $x=20;
			 for( $i=0;$i<count($field);$i++){
			       DrawRect($field[$i],10,"#ffffff",array($x,$y,$w,$h),"#000000");
				   $x+= $w+2;
			 }
			 $x=20;
			 $y+=22;
			 for( $i=0;$i<count($tableDatas);$i++){
				  $x=20;
			      $y+=22;
				  DrawLine($tableDatas[$i],$x,$y,$w,$h);
			 }
 
			 
	}
    function DrawLine($tableData,$x,$y,$w,$h){
	        for( $i=0;$i<count($tableData);$i++){
				DrawRect($tableData[$i],10,"#000000",array($x,$y,$w,$h),"#eeeeee");
				$x+= $w+2;
			}
	}
?>
<?php
      function setJavaForm(){
		       global $URL;
			   global $ResdataBase,$typeDatabase;
			   global $inputsTextNames ;
			   global $WebSendVal;
			   global $selectable;
			   $ResdataBase= $selectable;
			   echo $selectable;
			   $x=600;
			   $y=60;
			   $inputsTextNames=array("DragID","target");
	           JAPI_CreatJavaForm( $URL, $ResdataBase,$inputsTextNames,$WebSendVal,$x,  $y );
			   
		
	 
			 
	  }
      
?>