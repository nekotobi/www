<?php
setContent();

function setContent(){
	require_once dirname(dirname(__FILE__)) .'/phpexcel/Classes/PHPExcel.php';
	$objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0)             //設置第一個內置表（一個xls文件裏可以有多個表）爲活動的
            ->setCellValue( 'A1', 'Hello' )         //給表的單元格設置數據
            ->setCellValue( 'B2', 'world!' )      //數據格式可以爲字符串
            ->setCellValue( 'C1', 12)            //數字型
            ->setCellValue( 'D2', 12)            //
            ->setCellValue( 'D3', true )           //布爾型
            ->setCellValue( 'D4', '=SUM(C1:D2)' );//公式
	
	saveExcel($objPHPExcel);
}


function saveExcel($objPHPExcel){
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="01simple.xls"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}
?>





<?php
function BaseFunction(){
	require_once dirname(dirname(__FILE__)) .'/phpexcel/Classes/PHPExcel.php';
$objPHPExcel = new PHPExcel();
// 設置文件的一些屬性，在xls文件——>屬性——>詳細信息裏可以看到這些值，xml表格裏是沒有這些值的
$objPHPExcel
      ->getProperties()  //獲得文件屬性對象，給下文提供設置資源
      ->setCreator( "Maarten Balliauw")                 //設置文件的創建者
      ->setLastModifiedBy( "Maarten Balliauw")          //設置最後修改者
      ->setTitle( "Office 2007 XLSX Test Document" )    //設置標題
      ->setSubject( "Office 2007 XLSX Test Document" )  //設置主題
      ->setDescription( "Test document for Office 2007 XLSX, generated using PHP classes.") //設置備註
      ->setKeywords( "office 2007 openxml php")        //設置標記
      ->setCategory( "Test result file");                //設置類別
// 位置aaa  *爲下文代碼位置提供錨
// 給表格添加數據
$objPHPExcel->setActiveSheetIndex(0)             //設置第一個內置表（一個xls文件裏可以有多個表）爲活動的
            ->setCellValue( 'A1', 'Hello' )         //給表的單元格設置數據
            ->setCellValue( 'B2', 'world!' )      //數據格式可以爲字符串
            ->setCellValue( 'C1', 12)            //數字型
            ->setCellValue( 'D2', 12)            //
            ->setCellValue( 'D3', true )           //布爾型
            ->setCellValue( 'D4', '=SUM(C1:D2)' );//公式
			
 $objActSheet = $objPHPExcel->getActiveSheet();
 $objActSheet->setTitle('Simple2222');

//下載文件
//excel 2003 .xls
// 生成2003excel格式的xls文件
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="01simple.xls"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
}

 
?>