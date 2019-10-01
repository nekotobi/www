<?php
require_once dirname(dirname(dirname(__FILE__))) .'/phpexcel/Classes/PHPExcel.php';
     // require_once('Php2ExcelApi.php');
	   DefineData();
       creatMat3();
function DefineData(){
	     global $baseData,$demand;
		 $baseData=array(
		     "project"=>"FP",
		     "principal"=>"高玲",
		     "startDay"=>"2019/9/27",
			 "currency"=>"人民幣",
			 "Outsourcing"=>"Suheb Mohammed Khalid",
			 "hourprice"=>125,
			 "tex"=>"0.0%"
		 );
		 $demand=array(
		      array(
			   "type"=>"2D",
			    "content"=>"情境圖完稿",
				"number"=>"3",
				"workingHours"=>"112",
				"valuation"=>5600,
				"starDate"=>"2019/08/11",
				"EndDate"=>"2019/08/31",
              ),
		      array(
			    "type"=>"2D",
			    "content"=>"情境圖完稿333",
				"number"=>"3",
				"workingHours"=>"332",
				"valuation"=>5100,
			    "starDate"=>"2019/09/11",
				"EndDate"=>"2019/10/31",
              ),
		 );
	 

}
function creatMat3(){
      global $baseData,$demand;
	  $objPHPExcel = new PHPExcel();
      $objPHPExcel->setActiveSheetIndex(0)  ;
	  //設定欄寬
	  $ColWidth=array(
	  array('A',11),
	   array('B',41),
	    array('C',8),
		 array('D',8),
		  array('E',8),
		   array('F',8),
		    array('G',15),
			  array('H',15),
	  );
	  for($i=0;$i<count($ColWidth);$i++){
	     	$objPHPExcel->getActiveSheet()->getColumnDimension($ColWidth[$i][0])->setWidth($ColWidth[$i][1]); 
	  }
	  $title=array(
	  array('A',"制作类型","type"),
	   array('B',"内容","content"),
	    array('C',"数量","number"),
		 array('D',"工时（小时）","workingHours"),
		  array('E',"工时单价","hourprice"),
		   array('F',"合计","Total"),
		    array('G',"开始时间","starDate"),
			  array('H',"完成时间","EndDate"),
	  );
	  //第一行
	  for($i=0;$i<count( $title);$i++){
		  $area=($title[$i][0]."1");
	     	  setCellStyle($objPHPExcel,$title[$i][1],$area,"10",'center',"",$area);
			  $objPHPExcel->getActiveSheet()->getStyle($area)->getFont()->getColor()->setARGB('FFFFFFFF'); 
			  $objPHPExcel->getActiveSheet()
			  ->getStyle($area)
			  ->getFill()
			  ->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' =>'000000'))); 
	  }
	  //詳細資料
	  $Nstart=2;
	  for($i=0;$i<count( $demand);$i++){
		    for($j=0;$j<count( $title);$j++){
				 $area=($title[$j][0].$Nstart);
				 $info=$demand[$i][$title[$j][2]];
				 if ($info!=""){
				     setCellStyle($objPHPExcel, $info,$area,"10",'center',"",$area);
				 }
		        
			}
	      $Nstart+=1;
	  }
	   saveExcel($objPHPExcel);
	  
	  
}
function creatMat1(){
	global $baseData,$demand;
	$ProjectTitle="《".$baseData[project].'》';
	$Currency="估价（".$baseData[currency]."）";
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0)  ;
	//設定欄寬
	foreach(range('A','G') as $col){
		$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setWidth(12); 
	}  
    setCellStyle($objPHPExcel,'项目外包需求申请单','A1',"20",'center','A1:G1',"");
    //3
	setCellStyle($objPHPExcel,'项目','A3',"10",'center',$merge,'A3');
	setCellStyle($objPHPExcel,$ProjectTitle,'B3',"10",'center','B3:C3','B3:C3');
	setCellStyle($objPHPExcel,'申请人','D3',"10",'center','','D3');
	setCellStyle($objPHPExcel,$baseData[principal],'E3',"10",'center','','E3');
	setCellStyle($objPHPExcel,'时间','F3',"10",'center','','F3');
	setCellStyle($objPHPExcel,$baseData[startDay],'G3',"10",'center','','G3');
	//4
	$objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(40);
	setCellStyle($objPHPExcel,'事由','A4',"10",'center','','A4');
	setCellStyle($objPHPExcel,$ProjectTitle.'项目美术外包需求申请单','B4',"10",'center','B4:G4','B4:G4');

	//5
	setCellStyle($objPHPExcel,'制作类型','B5',"10",'center',$merge,'B5');
	setCellStyle($objPHPExcel,'内容','C5',"10",'center',$merge,'C5');
	setCellStyle($objPHPExcel,'数量','D5',"10",'center',$merge,'D5');
	setCellStyle($objPHPExcel,'工期（小时）','E5',"10",'center',$merge,'E5');
	setCellStyle($objPHPExcel, $Currency,'F5',"10",'center','F5:G5','F5:G5');
   
	//所有內容量6==>
	$Nstart=6;
	$totalAmout=0;
	for($i=0;$i<count($demand);$i++){
		$a=$Nstart;
		$totalAmout+=$demand[$i][valuation];
	    setCellStyle($objPHPExcel,$demand[$i][type],'B'.$a,"10",'center',$merge,'B'.$a);
		$objPHPExcel->getActiveSheet()->getRowDimension($Nstart)->setRowHeight(30);
	setCellStyle($objPHPExcel,$demand[$i][content],'C'.$a,"10",'center',$merge,'C'.$a);
	setCellStyle($objPHPExcel,$demand[$i][number],'D'.$a,"10",'center',$merge,'D'.$a);
	setCellStyle($objPHPExcel,$demand[$i][workingHours],'E'.$a,"10",'center',$merge,'E'.$a);
	$m='F'.$a.':G'.$a;
	setCellStyle($objPHPExcel,$demand[$i][valuation],'F'.$a,"10",'center', $m,	$m);
	$objPHPExcel->getActiveSheet()->getStyle('F'.$a)->getNumberFormat()->setFormatCode('$0,000');
	$Nstart+=1;
	}
	//需求
	//$Nstart=($start+count($demand)-1);
	$Range='A5:A'.($Nstart-1);
	setCellStyle($objPHPExcel,'需求清单','A5',"10",'center',$Range,$Range);
	//其他資訊
	//$Nstart+=1;
	$Range='A'.$Nstart.":C".$Nstart;
	setCellStyle($objPHPExcel,'总预算','A'.$Nstart,"10",'center',$Range,$Range);
	$Range='D'.$Nstart.":G".$Nstart;
	setCellStyle($objPHPExcel,$totalAmout,'D'.$Nstart,"10",'center',$Range,$Range);
	$objPHPExcel->getActiveSheet()->getStyle('D'.$Nstart)->getNumberFormat()->setFormatCode('$0,000');
    //簽字
	$Nstart+=1;
	$Range='A'.$Nstart.":C".$Nstart;
	setCellStyle($objPHPExcel,'相关部门负责人签字','A'.$Nstart,"10",'center',$Range,$Range);
	$Range='D'.$Nstart.":G".$Nstart;
	setCellStyle($objPHPExcel,"",'D'.$Nstart,"10",'center',$Range,$Range);
	$objPHPExcel->getActiveSheet()->getRowDimension($Nstart)->setRowHeight(40);
	//外包
	$Nstart+=1;
	$objPHPExcel->getActiveSheet()->getRowDimension($Nstart)->setRowHeight(2);
	$Nstart+=1;
	$Range='A'.$Nstart.":C".$Nstart;
	setCellStyle($objPHPExcel,'外包公司或个人','A'.$Nstart,"10",'center',$Range,$Range);
	setCellStyle($objPHPExcel,'价格','D'.$Nstart,"10",'center',"",'D'.$Nstart);
	setCellStyle($objPHPExcel,'起始时间','E'.$Nstart,"10",'center',"",'E'.$Nstart);
	$Range='F'.$Nstart.":G".$Nstart;
	setCellStyle($objPHPExcel,'其他条件','F'.$Nstart,"10",'center',$Range,$Range);
	$objPHPExcel->getActiveSheet()->getRowDimension($Nstart)->setRowHeight(40);
	//外包名稱
	$Nstart+=1;
    $Range='A'.$Nstart.":C".$Nstart;
	setCellStyle($objPHPExcel,$baseData[Outsourcing],'A'.$Nstart,"10",'center',$Range,$Range);
	setCellStyle($objPHPExcel,$totalAmout,'D'.$Nstart,"10",'center',"",'D'.$Nstart);
    $objPHPExcel->getActiveSheet()->getStyle('D'.$Nstart)->getNumberFormat()->setFormatCode('$0,000');
	setCellStyle($objPHPExcel,$baseData[startDay],'E'.$Nstart,"10",'center',"",'E'.$Nstart);
	$Range='F'.$Nstart.":G".$Nstart;
	setCellStyle($objPHPExcel,'','F'.$Nstart,"10",'center',$Range,$Range);
	$objPHPExcel->getActiveSheet()->getRowDimension($Nstart)->setRowHeight(40);
	//CEO
	$Nstart+=1;
	$Range='A'.$Nstart.":C".$Nstart;
	setCellStyle($objPHPExcel,"CEO签字",'A'.$Nstart,"10",'center',$Range,$Range);
	$Range='D'.$Nstart.":G".$Nstart;
	setCellStyle($objPHPExcel,' ','D'.$Nstart,"10",'center',$Range,$Range);
	$objPHPExcel->getActiveSheet()->getRowDimension($Nstart)->setRowHeight(40);
	saveExcel($objPHPExcel);
	
}

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
 
function saveExcel($objPHPExcel){
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="01simple.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
}

?>

<?php //其他資訊備註
       // VERTICAL_CENTER 垂直置中
       //VERTICAL_TOP 垂直置頂
       //HORIZONTAL_CENTER 水平置中
       //HORIZONTAL_RIGHT 水平靠右
       //HORIZONTAL_LEFT 水平靠左
?>