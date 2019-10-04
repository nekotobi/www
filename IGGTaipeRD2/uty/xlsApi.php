<?php  
	   function getMysqlDataArray($name){
	            $all_num= getAll_num( $name );
				$fieldnum=mysql_num_fields( $all_num);
				$fName=array();
				for ($x=0 ;$x<$fieldnum;$x++)	array_push($fName, mysql_field_name($all_num,$x));
				$returnData=array();
				 $t=mysql_num_rows($all_num); 
				for($i=0;$i<$t;$i++){
				    $data=array();
					for ($x=0 ;$x<count($fName);$x++){
						 $d=mysql_result(  $all_num,$i,$fName[$x]);
						 array_push($data,$d);
					}
					 array_push($returnData,$data);
				}
				return $returnData;
	  }
	   function getAll_num($SElectTable){
		  $data_library="IGGTaipeRD2";
	      $db = mysql_connect("localhost","root","1406");
	      mysql_select_db( $data_library,$db);
          mysql_query("SET NAMES 'utf8'");
	      return  mysql_query("SELECT * FROM ".$SElectTable,$db);	  
	  }
	       function getlibraryTables($data_library){ //取得資料表格內所有table
	        $db = mysql_connect("localhost","root","1406");
	        $db_selected = mysql_select_db( $data_library,$db);
			$sql = "SHOW TABLES FROM $data_library";
			$result = mysql_query($sql);
			$tables=array();
			while ($row = mysql_fetch_row($result)) {
				   Array_Push($tables, $row[0] );
                 //  echo "</br>Table: {$row[0]}\n";
            }
			return $tables;
	}
 	 function returnTables($data_library ,$tableName){ //取得資料表所有欄位名稱
		    $db = mysql_connect("localhost","root","1406");
	        $db_selected = mysql_select_db( $data_library,$db);
			$sql = "SELECT * from ".$tableName;
			$result = mysql_query($sql, $db );
		    $tables=array();
		    while($property = mysql_fetch_field($result)){
			      array_push($tables, $property->name);
            }
			return $tables;
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
    function getColumnsize(){
		   global $data_library, $selectable;
		   $db = mysql_connect("localhost","root","1406");
		   $db_selected = mysql_select_db( $data_library,$db);
		   $fields= returnTables($data_library ,$selectable);
		   $ret=array();
		   for($i=0;$i<count($fields);$i++){
			   $sql = "SELECT CHARACTER_MAXIMUM_LENGTH,column_name FROM information_schema.columns  
                     WHERE table_name = '$selectable' AND column_name LIKE '$fields[$i]'"; 
               $query = mysql_query($sql,$db) or die(mysql_error());
			   while($result = mysql_fetch_array($query)){
				     array_push($ret,$result[CHARACTER_MAXIMUM_LENGTH] );
				   //  echo  $result[CHARACTER_MAXIMUM_LENGTH] ;
			   }
		   }
		   return $ret;
	}
?>