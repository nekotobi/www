
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
	   function saveUpdateTime($type,$upd){//更新排程表最後更新日期
	           global $data_library,$SC_tableName_now;
		    	 DefineVTTableName();
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
<?php  //日歷
       function DrawBaseCalendar($StartY,$StartM,$MRange,$LocX,$LocY,$wid,$h){
		        $BgColor="#222222";
			    $fontColor="#ffffff";
			    $fontSize=10;
				$y=$StartY;
				$m=$StartM;
	            for($i=0;$i<$MRange;$i++){
                   $days = cal_days_in_month(CAL_GREGORIAN, $m,$y); // 30
				   DrawRect($m,$fontSize,$fontColor,$LocX,$LocY,$wid*$days-2,18,$BgColor);
				   $ym=$y."_".$m;
				   DrawVTDays($LocX,$LocY,$wid,$days, $h,$ym);
			       $m+=1;
				   if($m>12){$m=1;$y+=1;}
				   $LocX+=$wid* $days;
				}
	   }
       function DrawVTDays($LocX,$LocY,$wid,$days, $h,$ym){
		     	  $BgColor="#aaaaaa";
			      $fontColor="#ffffff";
			      $fontSize=10;
				  $date=date("Y_n_d");
			      for($i=1;$i<=$days;$i++){
					 $id="startDay=".$ym."_".$i;
					 
					 $BgColor="#aaaaaa";
					 if($date==$ym."_".$i)$BgColor="#aa7777";
			         VTDrawJavaDragArea("",$LocX+$i*$wid ,$LocY+20,$wid-1,$h,$BgColor,$fontColor,$id,$fontSize );
				  }
		}
?>
<?php //資源索引
      function getResSorType($Res_Array,$Restype ){//動作特效xx
	            $ty=$Restype."_type";
				$n=0;
				if($Restype =="awake"){
					$ty="hero_type";
					$n=14;
				}
				$types=filterArray($Res_Array,0,$ty);
				$a=array();
				for($i=5;$i<11;$i++){
				    array_push($a,$types[0][$i+$n]);
				}
				return $a;
	 }
	  function returnTaskMainCode($ScheduleData,$Code){
	          for($i=0;$i<count($ScheduleData);$i++){
			      if(strpos($ScheduleData[$i][3],$Code) !== false &&  $ScheduleData[$i][6]==""  ){
					  return $ScheduleData[$i][1];				  
			  }
			  }
	 }
      function returnMainTask($ScheduleData,$Code){
	          for($i=0;$i<count($ScheduleData);$i++){
			      if(strpos($ScheduleData[$i][3],$Code) !== false &&  $ScheduleData[$i][6]==""  ){
					  return $ScheduleData[$i] ;				  
			  }
			  }
	 }
      function returnPic($dir,$code){
	    $pic="ResourceData/".$dir."/viewPic/".$code.".png";
		return $pic;
	  }
      //取得行事曆內容
      function getSCRange($Tasks, $startDate,$Range,$MaxNum){// $startDate= "y-m-d"  range= array(-1,1);前一個月 後一個月
	            $a=array();
	           for($i=0;$i<count($Tasks);$i++){
			       $checkDay=strtr( $Tasks[$i][2],"_","-");
				   $n= (strtotime( $checkDay)-strtotime($startDate))/86400;
				   if($n>$Range[0]*30 && $n<$Range[1]*30){
					   $Tasks[$i]["sort"]=$n;
					  array_push($a, $Tasks[$i]);
					  if(count($a)>$MaxNum)return $a;
				   }
			   }
			   $a= SortArrayByKey($a ,"sort");
			   return $a;
	  }
	  function findTaskTitle($Code){
		       global $TaskTitle;
			   if(count($TaskTitle)==0){
			      $T=getVTSCData("now");
				  $TaskTitle=filterArray($T,5,"工項");   
			   }
			   for($i=0;$i<count($TaskTitle);$i++){
			       if($TaskTitle[$i][1]==$Code)return $TaskTitle[$i][3];
			   }
			   
	  }
      function getSCTypes($type){ //返回排程類別
	         $types=  getMysqlDataArray("scheduletype");
			 $s= filterArray(  $types,0 ,$type);
			 $a=array();
			 for($i=0;$i<count($s);$i++){
			     array_push($a,$s[$i][2]);
			 }
			 return $a;
	 }
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
<?php //java
        function VTDrawJavaDragPic($pic,$x,$y,$w,$h,$id){
		     	 echo "<div id=".$id  ;
				 echo " draggable='true' ondragstart='Drag(event)' ";
				 echo "' style='position:absolute; 
				       top:".$x."px;Left:".$y."px; width:".$w."px;height:".$h."px;
				      '><img src=".$pic." width=".$w." height=".$h."></div>";
	   }
	    function VTDrawJavaDragbox($msg,$x,$y,$w,$h,$fontSize,$BgColor,$fontColor,$id){
	          echo "<div  id=".$id." ";
			  	//    echo " ondragover='alert(xxx)' ";
 
			  echo " draggable='true' ondragstart='Drag(event)' ";// ondragend='leave(event)' ";
              echo " style=' " ; //align=left
			  echo "position:absolute; top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; 
			        font-size:".$fontSize."px; color:".$fontColor."; background-color:".$BgColor."; '>".$msg;
	          echo "</div>";
	    }
	    function VTDrawJavaDragArea($msg,$x,$y,$w,$h,$BgColor,$fontColor,$id,$fontSize=10){
	          echo "<div  id=".$id." ";
			 // echo " ondragenter='enter(event)' ";
			  echo " ondrop='Drop2Area(event)'  ondragover='AllowDrop(event)' ";//  ondragleave = 'leave(event)' ";
              echo  " style='   " ;
			  echo "position:absolute;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor."; ";
	          echo  " font-size:".$fontSize."px ; color:".$fontColor."; ";
              echo	 "'  >";
			  echo $msg;
	          echo "</div>";
	    }

?>
<?php //javaform
     function VTCreatJavaForm( $URL,$tableName){
		      global $Restype;
			  $st="角色";
			  if($Restype=="hero")  $st="角色";;
 
		      $x=20;
			  $y=10;
			  global  $typeArray;
			 // global $tableName;
			  $code=returnDataCode( );
			  $lastUpdate=date("Y_j_n_H_i_s");
		      $upFormVal=array("Show","Show",$URL);
			  $UpHidenVal=array(array("tablename",$tableName),
			                    array("data_type","data"),
								array( "Send","sendjava" ),
								array( "Restype2",$Restype ),
									array( "Restype",$Restype ),
	                            );
		      $UpHidenVal=	addArray( $UpHidenVal,$typeArray);	
		      $inputVal=array(array("text","DragID","DragID",10,420,$y,300,20,$BgColor,$fontColor,"" ,15),
			                   array("text","target","target",10,570,$y,200,20,$BgColor,$fontColor,"" ,20),
						       array("text","workingDays","workingDays",10,720,$y,200,20,$BgColor,$fontColor,"" ,6),
							   array("text","state","state",10,920,$y,200,20,$BgColor,$fontColor,"" ,6),
							   array("text","principal","principal",10,1020,$y,200,20,$BgColor,$fontColor,"" ,6),
							   array("text","outsourcing","outsourcing",10,1120,$y,200,20,$BgColor,$fontColor,"" ,6),
							   array("text","type","type",10,1220,$y,200,20,$BgColor,$fontColor,"" ,6),
							   array("text","selecttype","selecttype",10,1420,$y,200,20,$BgColor,$fontColor, $st ,6),
							   array("text","startDay","startDay",10,1320,$y,200,20,$BgColor,$fontColor,"" ,6),
							   array("text","code","code",10,1520,$y,200,20,$BgColor,$fontColor,$code,6),
							   array("text","plan","plan",10,1520,$y+20,200,20,$BgColor,$fontColor,"" ,6),
                               array("text","lastUpdate","lastUpdate",10,1320,$y+20,200,20,$BgColor,$fontColor,$lastUpdate ,6),
	                          );			 
		      upSubmitform($upFormVal,$UpHidenVal, $inputVal);
	 }

?>
