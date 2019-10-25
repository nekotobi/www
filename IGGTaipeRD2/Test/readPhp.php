<?php
 require_once dirname(dirname(dirname(__FILE__))) .'/phpexcel/Classes/PHPExcel.php';
 require_once dirname(dirname(dirname(__FILE__))) .'/phpexcel/Classes/PHPExcel/Writer/Excel2007.php';
 require_once dirname(dirname(dirname(__FILE__))) .'/PHPExcel/Classes/PHPExcel/IOFactory.php';
//require_once 'xlsApiv2.php';
//require_once dirname(dirname(dirname(__FILE__))) .'/phpExcelReader/Excel/reader.php';
ReadExecl();
function ReadExecl(){
      	echo "x";
	     $file="test.xlsx";
	    $objPHPExcel = PHPExcel_IOFactory::load($file);
}




?>1