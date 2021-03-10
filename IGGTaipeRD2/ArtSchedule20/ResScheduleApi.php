<?php //統計
      function Resstatistics(){
	          global $ResTypeSingleData;
			  global $AssemblyType;
 
              DrawAssemblyType(  $AssemblyType);
			  //廠商整理
			  collectionOuts();
			  ListOuts();
	  }
      function DrawAssemblyType(  $AssemblyType){
		       global $WebSendVal;
			   global $URL;
			   $valArray =$WebSendVal; 
			   $Rect=array(300,80,40,20);
			   global   $SubmitName;
	  		   for($i=0;$i<count($AssemblyType);$i++){
				   $str=$AssemblyType[$i]."[".$i;
				   $BgColor="#222222";
				   $valArray[4][1]=$str;
				   if( $WebSendVal[4][1]==$str)$BgColor="#ff6666";
			       sendVal($URL,  $valArray ,$SubmitName,$str,$Rect,10,$BgColor);
                   $Rect[0] += ($Rect[2]+1);				   
  			   }
	  }
	  function collectionOuts(){
	           global $Resdatas;
			   global $Outs, $OutsWorks;
			   $sort=explode("[",$_POST["AssemblyType"]);
               $ResSort=$sort[1];
			   $Outs=array();
	           for($i=0;$i<count($Resdatas);$i++){
				   $str= ReturnStr($Resdatas[$i], 10,$ResSort );//外包
			       if(  $str!=""){
					  if(!in_array( $str,$Outs)){
					  array_push($Outs, $str );
					  }
			       }
			   }
			   $OutsWorks;
			  // print_r($Outs);
			   for($i=0;$i<count($Outs);$i++){
			       $arr= ReturnOutsArr($Outs[$i],$ResSort) ;
				   $OutsWorks[$Outs[$i]]=$arr;
			   }
	      
	  }
	  function ReturnOutsArr($outsName, $ResSort){ //外包名 流水線編號
	           global $Resdatas;
			   $arr=array();
			   for($i=0;$i<count($Resdatas);$i++){
				   $str= ReturnStr($Resdatas[$i],10,$ResSort);
			       if($outsName==$str){
					   $name=$Resdatas[$i][3].$Resdatas[$i][3];
					   $cost=ReturnStr($Resdatas[$i] ,15,$ResSort );
					   array_push(  $arr,array($Resdatas[$i][3],$Resdatas[$i][4],$cost));
			       }
			   }
			   return $arr;
	  }
	  function ReturnStr($data,$dataSort,$ResSort){
		       $arr=explode("=",$data[$dataSort]); 
			   $str=$arr[$ResSort];
			   return $str;
	  }
	  //列印
	  function ListOuts(){
		       global $Outs, $OutsWorks;
			   global $AssemblyRect;
			   $AssemblyRect =array(20,120,100,80);
			   for($i=0;$i<count($Outs);$i++){
				   DrawRect($Outs[$i],"12","#ffffff",$AssemblyRect,"#000000" );
				   ListOutsingle($Outs[$i],$OutsWorks[$Outs[$i]]);
				   $AssemblyRect[1]+=   $AssemblyRect[3]+2;
			   }
	  }
	  function ListOutsingle($Outsname,$Outsdata){
		    
		       global $AssemblyRect;
			   $Rect=$AssemblyRect;
			   $Rect[2]=$Rect[3];
			   $Rect[0]+=102;
			   $fontColor="#ffffff";
	           for($i=0;$i<count($Outsdata);$i++){
				   
				   $code=$Outsdata[$i][0];
				   DrawPic( returnPicPath($code ),$Rect );// $noPic
				   $re=array($Rect[0],$Rect[1]+$Rect[2]-15,$Rect[2],15);
				   DrawRect($Outsdata[$i][0].$Outsdata[$i][1],9,$fontColor,$re,"#333333");
				   if($Outsdata[$i][2]!=""){
			       $re2=array($Rect[0]+$Rect[2]-20,$Rect[1]+$Rect[2]-25,20,10);
 
				   DrawRect($Outsdata[$i][2] ,8,$fontColor,$re2,"#aa8855");
				   } 
				   $Rect[0]+=  $Rect[2]+1;
				  
			   }
	  }
?>
<?php //行程表熱區
      function ListHotZone(){
		       global $singleResHieght;
			   $singleResHieght=20;
               CollectionHotRes();	
			   global $dateArr;
               CAPI_getTimeRange($dateArr);	 
	           global $CalendarH;	
			   global $singleResHieght;
			   $hs=0;
			   global $Resdatas;
               for($i=0;$i<count($Resdatas);$i++){
				   foreach($Resdatas[$i] as $v)$hs+=1;
               }	
 
			   $CalendarH=$singleResHieght* $hs+20;
               ListCalendar();
			   DrawHotZone();
 	  }
 
	  function DrawHotZone(){
		       global $Resdatas;
			   global $HotRect;
			   $HotRect=array(20,110,20,20);
	           for($i=0;$i<count($Resdatas);$i++){
				 
				  DrawHotSingleZone($Resdatas[$i]);
			   }
	  }

	  function DrawHotSingleZone($data){
		       global $HotRect;
			   global $ColorCode;
			   global $CalendarRect;
			   global $startDate;
			   $colorSet= $ColorCode[12];
			   $HotRect[2]=20*count($data);
			   $HotRect[3]=20*count($data);
			   $id=$data[0][0];
		       DrawIDPic(returnPicPath($data[0][0]),$HotRect,$id);
			   $msg=$data[0][0].$data[0][1];
			   $Rect=  $HotRect;
			   $Rect[0]+=$HotRect[3];
			   $Rect[2]=150-$HotRect[3];
			   DrawRect(  $msg,"10","#ffffff",$Rect,"#333333" );
			   $Rect[0]+=  $Rect[2];
		       for($i=0;$i<count($data);$i++){
			       $msg=  "[".$data[$i][3]."]".$data[$i][8].$data[$i][9];
				   $Rect[1]+=$i*20;
				   $Rect[2]=132;
				   $Rect[3]=19;
				   $BgColor=$colorSet[$data[$i][4]];
				   DrawRect(  $msg,"10","#ffffff",$Rect,    $BgColor );
				   //拖曳區
				   $id= "gdcode=".$data[$i][0]."=".$data[$i][2]."=".$data[$i][4]; //分類/gd碼
				   $Eid= "Egdcode=".$data[$i][0]."=".$data[$i][2]."=".$data[$i][4]."=".$data[$i][5];
				   $x= $CalendarRect[0]+ (CAPI_returnLocX($data[$i][5],$startDate )-1)*$CalendarRect[2];
				   $y=  $Rect[1];
				   $w=$data[$i][6] *$CalendarRect[2];
				   JAPI_DrawJavaDragbox( $data[$i][6] ,$x,$y,$w,$h,10, $BgColor, "#ffffff",$id);
				   
				   $x2= $x+ $w;
				   $w=$CalendarRect[2];
				   $BgColorE=PAPI_changeColor( $BgColor,array(0.8,0.8,0.8));
				   JAPI_DrawJavaDragbox("_" ,$x2,$y,$w,$h,10,   $BgColorE, "#ffffff",  $Eid);
			   }
			   
			   
			   $HotRect[1]+=20*count($data)+1;
	  }
	  function CollectionHotRes(){
	           global $Resdatas;
			   global $dateArr;
			   $dateArr=array();
			   $arr=array();
			   for($i=0;$i<count($Resdatas);$i++){
				   $ar=CollectionHotRess($Resdatas[$i]);
				   if($ar!=null)array_push($arr,$ar);
			   }   
			  $Resdatas= $arr;
	  }
	  function CollectionHotRess($data){
		       global $AssemblyType;
			   global $dateArr;
		       $dateStr=explode("=",$data[7]);
			   if(count ($dateStr)<2)return null;
			   $stateArr=explode("=",$data[11]);
			   $arr=array();
			   $startDayArr=explode("=",$data[7]);
			   $WorkingDayArr=explode("=",$data[8]);
			   $principalArr=explode("=",$data[9]);
			   $outArr=explode("=",$data[10]);
			   for($i=0;$i<count($dateStr);$i++){
				   if( $dateStr[$i]!=""){
				       if($stateArr[$i]!="已完成"){
					   //0編號  1名稱 2英雄類別 3類別 4類別編號  5日期 6時間 7狀態 8內 9外 
					    array_push( $dateArr,array($startDayArr[$i],$WorkingDayArr[$i]));
				        $ar=array($data[3],$data[4],$data[2],$AssemblyType[$i],$i,
    						      $startDayArr[$i],$WorkingDayArr[$i],$principalArr[$i],$outArr[$i]);
					    array_push( $arr,$ar);
				      }
				   }
			   }
			    if(count ( $arr)==0)return null;
				return $arr;
	  }
?>
<?php //關卡排怪區
      function StageMobSet(){
           global $Resdatas;
		   global $ResdatasT;
		   $Mobs=filterArray( $ResdatasT,2,"Mob" );
		   $Boss=filterArray($ResdatasT,2,"Boss" );
		   $x=170;
		   $y=110;
		   $w=280;
		   $h=60;
		   //拖曳區
		   DrawRect( "怪物分布" ,"10","#ffffff",array($x,$y-20,$w,$h),"#222222" );
		   for($i=0;$i<count($Resdatas);$i++){
		       $id="gdcode=".$Resdatas[$i][3];
			   JAPI_DrawJavaDragArea("mob" ,$x,$y,$w,$h, "#999999", "#cc9999",$id); 
			  // $id="gdcode=".$Resdatas[$i][3]."=Remove";
			  // JAPI_DrawJavaDragArea("mob" ,$x+$w,$y,10,$h, "#553333", "#cc9999",$id); 
			  if($Resdatas[$i][14]!="")
			   DrawDragMatInst($Resdatas[$i][14],$x+2,$y+10,40,10);
			   $y+=$h+2;
		   }	
           //mob
		   $x2=$x+$w+12;
		   $x=$x2;
		   $c=0;
		   $w=40;
		   $h=10;
		   global $LocY;
		   $LocY  =110;
           DrawDragMat( $Mobs,$x,$y,$w,$h,$x2);
		   $LocY+=$w+2;
           DrawDragMat($Boss,$x,$y,$w,$h,$x2);   
      }
	  function DrawDragMatInst($mats,$x,$y,$w,$h){
	           $arr=explode("_",$mats);
		       for($i=0;$i<count( $arr);$i++){	
				   $id="SetMat=".$arr[$i];
			       DrawIDPic(returnPicPath( $arr[$i] ),array($x,$y,$w,$w),$id);
				   JAPI_DrawJavaDragbox(  $arr[$i] ,$x,$y,$w,$h,8, "#222222","#ffffff",$id,8);
				   $x+=$w+1;
			   }
	  }
	  function DrawDragMat($data,$x,$y,$w,$h,$x2){
		       global $LocY;
			   $y=$LocY;
	           for($i=0;$i<count( $data);$i++){	
		           $id="SetMat=".$data[$i][3];
			       DrawIDPic(returnPicPath($data[$i][3],$type),array($x,$y,$w,$w),$id);
			       JAPI_DrawJavaDragbox( $data[$i][3] ,$x,$y,$w,$h,8, "#222222","#ffffff",$id,8);
			       $c+=1;
			       $x+=$w+1;
			       if ($c>10){
			           $c=0;
				       $x =$x2;
				       $y+=40;
			       }
				 $LocY=$y;  
		   }
	  }
       function upDragMat( $curentData ,$MatCode,$Remove){//上傳怪物分布
		        global $WebSendVal,$URL,$ResdataBase;
			    $WHEREtable=array( "gdcode", "EData");
		        $WHEREData=array($curentData[0][3],"data"  );
				$Base=array("classification");
			    $up=array(returnSetMat($curentData[0][14],$MatCode));
			    $stmt=MAPI_MakeUpdateStmt($ResdataBase,$Base,$up,$WHEREtable,$WHEREData);
				 SendCommand($stmt,$data_library);		
			    JAPI_ReLoad($WebSendVal,$URL);
	   }
	   function returnSetMat($BaseStr,$addStr){
				 $arr=explode("_",$BaseStr);
				 $str="";
				 $Repetbool=false;
				 for($i=0;$i<count($arr);$i++){
					  if($arr[$i]==$addStr   ) $Repetbool=true;
					 if($arr[$i]!=$addStr && $arr[$i]!="" ){
						 $str=$str.$arr[$i]."_";
					 }
				 }
				if(! $Repetbool) $str=$str.$addStr ;    
				 return $str;
	   }
	   
?>

<?php //季計畫
      function DrawSeasonPlan(){
               global $ResdatasT;
			  
	  }
?>

