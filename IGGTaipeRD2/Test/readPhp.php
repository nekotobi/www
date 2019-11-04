 
<?php
 require_once dirname(dirname(dirname(__FILE__))) .'/phpexcel/Classes/PHPExcel.php';
 require_once dirname(dirname(dirname(__FILE__))) .'/phpexcel/Classes/PHPExcel/Writer/Excel2007.php';
 require_once dirname(dirname(dirname(__FILE__))) .'/PHPExcel/Classes/PHPExcel/IOFactory.php';
//require_once 'xlsApiv2.php';
//require_once dirname(dirname(dirname(__FILE__))) .'/phpExcelReader/Excel/reader.php';
echo "x1";
ReadExecl();
function ReadExecl(){
      	echo "x1x";
	    $file="test.xlsx";
	    $objPHPExcel = PHPExcel_IOFactory::load($file);
		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	    echo "<h2>列印每一行的資料</h2>";
        foreach($sheetData as $key => $col)
        {
         echo "行{$key}: ";
          foreach ($col as $colkey => $colvalue) {
            echo "{$colvalue}, ";
        } 
        echo "<br/>";
    }
}




?>
 