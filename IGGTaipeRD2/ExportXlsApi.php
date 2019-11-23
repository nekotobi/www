<?php //預設資料
require_once dirname(dirname(__FILE__)) .'/phpexcel/Classes/PHPExcel.php';

?>
<?php
  function Exporxls($Arrays,$fileName){
	   $objPHPExcel = new PHPExcel();
       $objPHPExcel->setActiveSheetIndex(0)  ;
	   global $h;
	   $h=0;
	   for($i=0;$i<count($Arrays);$i++){
		   
		  Exporxlsingle($objPHPExcel,$Arrays[$i] );
	   }
	   saveExcel($objPHPExcel,$fileName."xls");
	 }
   function Exporxlsingle($objPHPExcel,$data ){
	   global $h;
     for($i=0;$i<count(  $data);$i++){
		//  $area= chr(65+$i).$h;
		  //$msg=$data[$i];
	      Exporxlsingle2($objPHPExcel,$data[$i] );
	       $h+=1;
	 }
   }
   function Exporxlsingle2($objPHPExcel,$data ){
	   echo "</br>";
	     global $h;
     for($i=0;$i<count(  $data);$i++){
		  $area= chr(65+$i).$h;
		  $msg=$data[$i];
		  echo $area.$msg."/";
	     setCellStyle($objPHPExcel,$msg,$area,"20",'center','',$area);
	 }
   }
?>
<?php //api
  function setCellStyle($objPHPExcel,$txt,$area,$size,$Alig,$merge,$LineArea){
	    if($merge!="") 
			$objPHPExcel->getActiveSheet()->mergeCells($merge); 
            $objPHPExcel->getActiveSheet()->setCellValue( $area, $txt ) ;
			$objPHPExcel->getActiveSheet()->getStyle($area)->getFont()->setSize($size);
		if($Alig=="center"){
           $objPHPExcel->getActiveSheet()->getStyle($area)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER ); 
		   $objPHPExcel->getActiveSheet()->getStyle($area)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		}
		//線
		if($LineArea!=""){
		   $styleArray = array(
           'borders' => array(
           'outline' => array(
           'style' => PHPExcel_Style_Border::BORDER_THIN,
           'color' => array('argb' => '00000000'),
           ),
           ),
           );
		 $objPHPExcel->getActiveSheet()->getStyle($LineArea)->applyFromArray($styleArray);
		}
}
        function saveExcel($objPHPExcel,$Filename){
		header("Content-type: text/html; charset=charset=unicode");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$Filename);
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
}
?>