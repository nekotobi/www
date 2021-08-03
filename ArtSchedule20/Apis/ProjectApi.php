<?php  //選擇專案
        function ProAPI_DrawProjectButtoms( $selectProject,$startY,$URL){
			   $ProjectTypes=array("zombie");
			   $BgColor="#111111";
			   $SubmitName="submit";
			   for($i=0;$i<count( $ProjectTypes);$i++){
				   $ArrayVal=array(array("selectProject",$ProjectTypes[$i]));
				   $BgColor="#111111";
				   if($selectProject==$ProjectTypes[$i]){ $BgColor="#aa1111";}
				   $Rect=array(400+$i*60,10,58,12);
				   $SubmitVal=$ProjectTypes[$i];
			       sendVal($URL,$ArrayVal,$SubmitName,$SubmitVal,$Rect,10, $BgColor , "#ffffff","true");
			   } 
	    }
		
        function ProAPI_DrawOutsAreas($Rect,$LinkButtom=false){ 
		         global $WorkY;
			     $WorkY=$Rect[1];
			     $wid=$Rect[2];   
			     $OutsT=getMysqlDataArray("outsourcing"); 
			     $OutsT2=filterArray($OutsT,35,"true");
			     $Outs=returnArraybySort( $OutsT2,2);
			     array_Push( $Outs,"--");
		         ProAPI_DrawDragUpArea($Outs,$Rect[0],$WorkY,$wid,"outsourcing",$LinkButtom);
		}
        function ProAPI_DrawWorkersAreas($Rect,$LinkButtom=false){ //
	           $startX=$Rect[0];
			 //  $startY=$Rect[1];
			   global $WorkY;
			  $WorkY=$Rect[1];
			   $wid=$Rect[2];
			    // 內部
			   $membersT=getMysqlDataArray("members"); 
			   $membersT2=filterArray($membersT,3,"Art");
			   $members=returnArraybySort( $membersT2,1);
			   array_Push($members,"--");
			   ProAPI_DrawDragUpArea($members,$startX,$WorkY,$wid,"principal",$LinkButtom);
			   $WorkY+=15;
			   //外部
			   $OutsT=getMysqlDataArray("outsourcing"); 
			   $OutsT2=filterArray($OutsT,35,"true");
			   $Outs=returnArraybySort( $OutsT2,2);
			   
			   array_Push( $Outs,"--");
		       ProAPI_DrawDragUpArea($Outs,$startX,$WorkY,$wid,"outsourcing",$LinkButtom);
			   $WorkY+=15;
			   //  array("進行中","已排程","驗證中","已完成");
           	   $Typestmp=getMysqlDataArray("scheduletype"); 
		       $arrT=filterArray( $Typestmp,0,"data3");
			   $arr=returnArraybySort($arrT,2);
			   ProAPI_DrawDragUpArea($arr,$startX,$WorkY,$wid,"state");
	 }
	    function ProAPI_DrawDragUpArea($arr,$x,$y,$wid,$uptableName,$LinkButtom=false){ //
	          $BgColor="#224444";
			  $fontColor="#ffffff";
			   global $WebSendVal  ;
			   global $URL;
			   global $WorkY;
			   $c=0;
			   $Bx=$x;
	          for($i=0;$i<count($arr);$i++){
				  $id="tableName=".$uptableName."=".$arr[$i];
		          $str= substr($arr[$i], 0, 9);
				  if($LinkButtom){
					 $valArray=$WebSendVal;
					 $SubmitName="submit";
					 array_push( $valArray ,array("SelectWorkUnit",$arr[$i]));
					 sendVal($URL,  $valArray ,$SubmitName,  "_",array($x-3,$WorkY,5,14),4,  "#664444"); 
				  }
				  JAPI_DrawJavaDragArea( $str,$x,$WorkY,$wid-1,14,$BgColor,$fontColor,$id,8);
				  $c+=1;
				  $x+=$wid;
				  if($c>17)  {
					  $WorkY+=15; 
					  $c=0;
					  $x=$Bx;
				  }
			
			   }
	    }
	    function ProAPI_ReturnStateColor($color ,$str){
		         if($str=="" or $str=="未定義")return "#888888";
			     if(  $str=="進行中")return PAPI_changeColor( $color ,array(1.2,1.2,1.2));
			     if(  $str=="預估排程")return PAPI_changeColor( $color ,array(0.6,0.6,0.6));
				 return $color;
		}
		function ProAPI_ReturnGDCode($ResType,$sort){
			     $ZeroCount=4-strlen($sort);
				 $str= substr($ResType, 0,  1);
				 for($i=0;$i<  $ZeroCount;$i++){
				 $str=$str."0";
				 }
				 $str=$str.$sort;
			     return $str;
		}
?>