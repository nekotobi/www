<?php
   function getTxtArray($txt){ //將xls轉為陣列
		   $data=array();
		   $row=explode("\n",$txt) ;//表單名[0]格英文[1] 中文[2] 字數[3]
	       for ($i=0;$i<count($row);$i++){ 
				$line=explode("\t",$row[$i])  ;
			    for ($j=0;$j<count($line);$j++){ 
		             $data[$i][$j]=trim($line[$j]) ;
			         } 
			}
			return $data;
  }
?>