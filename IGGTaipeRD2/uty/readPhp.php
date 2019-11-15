<?php
 if ($_POST['submit']!=""){
	 importExeclApi();
      ReadExecl();
 }
 if ($_POST['submit']==""){
	 importPubApi();
	 inputXls();
 }
 
function importExeclApi(){
     require_once dirname(dirname(dirname(__FILE__))) .'/phpexcel/Classes/PHPExcel.php';
     require_once dirname(dirname(dirname(__FILE__))) .'/phpexcel/Classes/PHPExcel/Writer/Excel2007.php';
     require_once dirname(dirname(dirname(__FILE__))) .'/PHPExcel/Classes/PHPExcel/IOFactory.php';
}
function importPubApi(){
     
        require_once   dirname(dirname(__FILE__))  .'/pubApi.php';
}
function DefineData(){
        global $BaseURL;
		$BackURL="readPhp.php";
}
function ReadExecl(){
       $file=$_FILES["xls"]["tmp_name"];
	   $objPHPExcel = PHPExcel_IOFactory::load($file);
	   $sheetNames =  $objPHPExcel ->getSheetNames();
       for($i=0;$i<count( $sheetNames);$i++){
	   echo $sheetNames[$i];
	   }
	   
 
}

function ReadExecl_sample(){
		$file=$_FILES["xls"]["tmp_name"];
	    $objPHPExcel = PHPExcel_IOFactory::load($file);
		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	    echo "<h2>列印每一行的資料</h2>";
        foreach($sheetData as $key => $col){
            echo "行{$key}: ";
            foreach ($col as $colkey => $colvalue) {
            echo "{$colvalue}, ";
        } 
        echo "<br/>";
    }
}
 
?>
<?php
function inputXls(){
	     if ($_POST['submit']!="")return;
         global $BaseURL;
		 $a1=array("msg"=>"xls","value"=>"","name"=>"xls","type"=>"file","x"=>0,"y"=>0, "w"=>200, "h"=>20 ,"size"=>20);
	     $a2=array("msg"=>"","value"=>"上傳","name"=>"submit","type"=>"submit","x"=>220,"y"=>0, "w"=>100, "h"=>20,"size"=>10);
	     $inputArray=array($a1,$a2) ;
		 $BaseRect=array(40,40,100,100);
         DrawinputForm($BaseRect,"upxls",$BaseURL,$inputArray,10);
		 
}
function DrawinputForm($BaseRect,$formName,$BaseURL,$inputArray,$fontSize){
         echo   "<form   name='".$formName."' action='".$BaseURL."' method='post'  enctype='multipart/form-data'>";
		 for($i=0;$i<count($inputArray);$i++){
			 $data=$inputArray[$i];
		     $input="<input type=".$data["type"]." name=".$data["name"]."	value=".$data["value"]."  size=".$data["size"]."   >";
			 $x=$data["x"]+=$BaseRect[0];
			 $y=$data["y"]+=$BaseRect[1];
		     DrawInputRect($data["msg"]." ", $fontSize,"#ffffff",$x ,$y,$data["w"],$data["h"] ,"#000000","top", $input);
		 }
		 echo   "</form>";
}
?>