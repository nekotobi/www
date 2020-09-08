
<?php  //vt
       function DefineVTTableName(){
                global $data_library;
	            global $SC_tableName;
	            global $Res_tableName;
                $data_library = "iggtaiperd2";
	            $SC_tableName = "fpschedule";
	            $Res_tableName ="fpresdata";
				//次要
				global $SC_tableName_now,$SC_tableName_old,$SC_tableName_merge;
				$SC_tableName_now=$SC_tableName."_now";
				$SC_tableName_old=$SC_tableName."_old";
				$SC_tableName_merge=$SC_tableName."_merge";
	   }
       function getVTSCData($type){
		        global $data_library;
		        global $SC_tableName_now,$SC_tableName_old,$SC_tableName_merge;
				DefineVTTableName();
			    $sc_now=getMysqlDataArray($SC_tableName_now);
	            if($type=="now")return 	 $sc_now;
			    if($type=="mix"){
                     if( CheckandMerge($sc_now)) {
		               $joinTables=array($SC_tableName_now,$SC_tableName_old);
                        mergeTableData($data_library,$SC_tableName_merge,$joinTables);
			        }
				   return getMysqlDataArray($SC_tableName_merge);
			 }
	  }
	   function saveUpdateTime($type,$upd){ //更新排程表最後更新日期
	           global $data_library,$SC_tableName_now;
		    	 DefineVTTableName();
			   //echo "xxx";
			   $WHEREtable=array( "data_type", "code" );
		       $WHEREData=array( "Update","Update" );
			   $Base=array("plan");
			   $up=array(date("Y_j_n_H_i_s"));
	           if($type=="merge") {
	         	  $Base=array("line");
				  $up=$upd;
           	   }
	        
			   $stmt= MakeUpdateStmt(  $data_library,$SC_tableName_now,$Base,$up,$WHEREtable,$WHEREData);
			  // 	echo  $stmt;
		       SendCommand($stmt,$data_library);		
	  }
	   function CheckandMerge($SCData){
	           $upd=filterArray($SCData,0,"Update");
			   if($upd[0][3]==$upd[0][4])return false;
			   
			   saveUpdateTime("merge",array($upd[0][3]));  
			   return true;
	  }
	   function gettaskNames( ){ //整理工項到now
	           global $SC_tableName_now,$SC_tableName_old,$SC_tableName_merge;
			   DefineVTTableName();
			   $SC_old= getMysqlDataArray($SC_tableName_old);
			   $SC_now= getMysqlDataArray($SC_tableName_now);
			   $SC_old_Task=filterArray( $SC_old,5,"工項");
		       $SC_now_Task=filterArray( $SC_now,5,"工項");
			   $colect_now=array();
			   for($i=0;$i<count( $SC_now);$i++){
			         if(!in_array( $SC_now[$i][3],$colect_now)){
					    array_push($colect_now, $SC_now[$i][3]);
					 }
			   }
			   $LostArray=array();
			   for($i=0;$i<count($SC_old_Task);$i++){
				    if( in_array($SC_old_Task[$i][3], $colect_now)){
					    array_push($LostArray, $SC_old_Task[$i] );
					}
			   }
			  // print_r(  $LostArray) ;
	  } 
?>

<?php //資源索引
     function getResDetail( $RequireHeros){
		   
	    
	 
	 }
?>

<?php //版本排程
     function getLaterEventData(){//取得接下來的活動排程
	             global $data_library;
				 $Vte= getMysqlDataArray("vtevent");
				 $ev=array();
				 $today=  date('Y-m-d') ;
				 for($i=0;$i<count($Vte);$i++){
					  $checkDay=strtr( $Vte[$i][4],"_","-");
					  if(strtotime( $checkDay)>strtotime($today))    { 
					     $v=$Vte[$i];
						 $v[10]=(strtotime( $checkDay)-strtotime($today))/86400;
			             array_push($ev,$v);
		         	 }
				 }
                 return $ev;
	   }
	   function GetEventRes($EVData,$type){//取得英雄排程
	            $heros=array();
				for($i=0;$i<count($EVData);$i++){
				    $st=$EVData[$i][6];
					$s=explode("_",$st);
					for($j=0;$j<count($s);$j++){
						if(strpos($s[$j],$type) !== false) {
					       $a=array($s[$j],$EVData[$i][10]);
						   array_push($heros,$a);
						}
					}
				}
				return    $heros;
	   }
	   
?>


