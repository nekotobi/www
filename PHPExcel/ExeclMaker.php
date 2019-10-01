<?php
//require_once './phpexcel/Classes/PHPExcel.php';
//require_once './Classes/PHPExcel.php';



?>



<?php
	 //test4();
	 //makeDoc();
	 function makeDoc(){
	      $filename ="test.doc";
	      ob_end_clean();
          header("Content-type: text/html; charset=utf-8");
          header("Content-Type: application/ms-word");
          header("Content-Disposition: attachment;filename=".$filename);
		   $contents = "number \t 網頁現在都是採用 \t 職務 \t \n";
		 $contents2  = iconv("UTF-8","BIG5", $contents);
         echo $contents2;
	 }
	 function makeExecl(){
	     $filename ="test4.xls";
	     ob_end_clean();
         header("Content-type: text/html; charset=utf-8");
         header("Content-Type: application/vnd.ms-excel");
         header("Content-Disposition: attachment;filename=".$filename);
		 $contents = "number \t 網頁現在都是採用 \t 職務 \t \n";
		 $contents2  = iconv("UTF-8","BIG5", $contents);
         echo $contents2;
       //  exit;
	 }
	 
	 
	 
	 
	 function test3(){
     $filename ="test3.xls";
      ob_end_clean();
      header("Content-type: text/html; charset=utf-8");
      header("Content-Type: application/vnd.ms-excel");
	  header('Content-Disposition: attachment; filename='.$filename);
	  
      $contents = "number \t 網頁現在都是採用 \t 職務 \t \n";

      $contents = $contents." 1 \t 小明 \t 董事長 \t \n";

      $contents = $contents." 2 \t 小華 \t 總經理 \t \n";
 

 //     header('Content-type: application/ms-excel ; charset=utf-8');
      
      

      echo $contents;
 
	}		
    function test(){
    //Including PHPExcel library and creation of its object
    require('PHPExcel.php');
    $phpExcel = new PHPExcel;
    // Setting font to Arial Black
    $phpExcel->getDefaultStyle()->getFont()->setName('Arial Black');
    // Setting font size to 14
    $phpExcel->getDefaultStyle()->getFont()->setSize(14);
    //Setting description, creator and title
    $phpExcel ->getProperties()->setTitle("Vendor list");
    $phpExcel ->getProperties()->setCreator("Robert");
    $phpExcel ->getProperties()->setDescription("Excel SpreadSheet in PHP");
    // Creating PHPExcel spreadsheet writer object
    // We will create xlsx file (Excel 2007 and above)
    $writer = PHPExcel_IOFactory::createWriter($phpExcel, "Excel2007");
    // When creating the writer object, the first sheet is also created
    // We will get the already created sheet
    $sheet = $phpExcel ->getActiveSheet();
    // Setting title of the sheet
    $sheet->setTitle('My product list');
    // Creating spreadsheet header
    $sheet ->getCell('A1')->setValue('Vendor');
    $sheet ->getCell('B1')->setValue('Amount');
    $sheet ->getCell('C1')->setValue('Cost');
    // Making headers text bold and larger
    $sheet->getStyle('A1:D1')->getFont()->setBold(true)->setSize(14);
    // Insert product data
    // Autosize the columns
    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet->getColumnDimension('B')->setAutoSize(true);
    $sheet->getColumnDimension('C')->setAutoSize(true);
    // Save the spreadsheet
    $writer->save('products.xlsx');
	}
?>

<?php
function test2(){
	
  $xls = new COM("Excel.sheet") or die("Did not connect");
  $xlsFile = "C:\\tmp\\byPhp.xls";
  $workbook = $xls->Application->Workbooks->Open($xlsFile) 
         or die("Failed to Open Workbook");
  $xls->Application->Visible = 1;
 
  $worksheet = $workbook->Worksheets("Sheet1");
  $worksheet->activate;
  $worksheet->Cells(1, 1)->value = 100;
  $worksheet->Cells(2, 1)->value = 20;
  $worksheet->Cells(3, 1)->formula = "=A1 + A2";
  print "100 + 20 = " . $worksheet->Cells(3, 1)->value;
  $workbook->Saved = 0;
  $xls->Application->Quit(); 
  unset($xls);
}
?>