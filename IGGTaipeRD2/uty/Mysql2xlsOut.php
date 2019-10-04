<?php
       require_once dirname(dirname(dirname(__FILE__))) .'/phpexcel/Classes/PHPExcel.php';
	   require_once 'xlsApi.php';
       OutExcel();
 function OutExcel(){
	        global $data_library;  
			global $selectable;
 
			$tables=  returnTables($data_library ,$selectable);
			$tableDatas=getMysqlDataArray($selectable); 
		    $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0)  ;
			
			//97-122 chr($a);
			$f=count($tableDatas)+97;
		    for($i=97;$i<$f;$i++){
	          	$objPHPExcel->getActiveSheet()->getColumnDimension(chr($i))->setWidth(20); 
			}
			$area="A1";
			setCellStyle($objPHPExcel,$selectable,$area,"10",'center',"",$area); 
		
			$area="B1";
	     	setCellStyle($objPHPExcel,$data_library,$area,"10",'center',"",$area); 
			
			
	 	    for($i=0;$i<count($tables);$i++){
			    $area=getAreaA($i)."2";
	    
			     setCellStyle($objPHPExcel,$tables[$i],$area,"10",'center',"",$area); 
		    }
			 
		    $sizes= getColumnsize();
			for($i=0;$i<count( $sizes);$i++){
	     	    $area=getAreaA($i)."3";
			  setCellStyle($objPHPExcel,$sizes[$i],$area,"10",'center',"",$area); 
		    }
			
			
			$l=4;
		    for($i=0;$i<count($tableDatas);$i++){
	     	   	 
			    for($j=0;$j<count($tableDatas[$i]);$j++){
				    $area=getAreaA($j).($l+$i) ;
					$str="'".$tableDatas[$i][$j]."'";
			        setCellStyle($objPHPExcel,$tableDatas[$i][$j],$area,"10",'center',"",$area); 
				}
		    }
		  
		  saveExcel($objPHPExcel);
		
 }
 function getAreaA($i){
	   $s=chr($i+97);
	   if(($i+97)>=123) $s="a".chr($i+71) ;
	   return $s;
 }
 
?>
