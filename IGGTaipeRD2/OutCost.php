<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>外包發費表</title>
</head>
 
<body bgcolor="#b5c4b1">
<?php  //主控台
    $id=$_COOKIE['IGG_id'];
    include('PubApi.php');
    defineData();
    DrawTitle();
    DrawContents();

?>

<?php //初始資料
    function defineData(){
		       global $ListNames,$ListSize,$OutCosts;
	         $tableName="fpoutsourcingcost";
			 $data_library="iggtaiperd2"; 
			 $MainPlanDataT=getMysqlDataArray($tableName); 
			 $ListNames=filterArray($MainPlanDataT,0,"title");
			 $ListSize=filterArray($MainPlanDataT,0,"size");
			 $OutCosts=filterArray($MainPlanDataT,3,"FP");
	}



?>

<?php //列印資料
     function DrawTitle(){
		      global $ListNames,$ListSize,$OutCosts;
	          $x=20;
			  $y=20;
			  $h=20;
			  for($i=1;$i<count($ListNames[0]);$i++){
				  $w= $ListSize[0][$i];
				  if($w!=""){
			         DrawRect($ListNames[0][$i],10,"#FFFFFF",$x,$y,$w,$h,"#000000");
					 $x+=$w+2;
				  }
			  }
	 }
     function DrawContents(){
		      global $OutCosts,$ListSize;
	          for($i=0;$i<count($OutCosts);$i++){
				 DrawLines($OutCosts[$i],($i+2)*22);   
			  }
	 }
	 function DrawLines($Data,$y){
		      global  $ListSize;
			  $x=20;
			  $h=20;
		      for($i=1;$i<count($Data);$i++){
				  $w= $ListSize[0][$i];
				  if($w!=""){
			         DrawRect($Data[$i],10,"#000000",$x,$y,$w,$h,"#DDDDDD");
					 $x+=$w+2;
				  }
			  }
	 }
?>