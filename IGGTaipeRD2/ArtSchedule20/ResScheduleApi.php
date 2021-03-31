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
				   Pub_DragTasks($data,$i,$Rect[1] ,  $BgColor , $data[$i][5], $data[$i][6]);
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
				   if(strlen( $arr[$i])==5){
			       DrawIDPic(returnPicPath( $arr[$i] ),array($x,$y,$w,$w),$id);
			       $n=Pub_ReturnFinCodeByCode($arr[$i]);
				   $Prefix=substr($n, 0, 1); 
				   $BgColor="#555555";
				   if( $Prefix=="B")$BgColor="#aa5555";
				   JAPI_DrawJavaDragbox( $n,$x,$y,$w,$h,8,  $BgColor,"#ffffff",$id,8);
				   $x+=$w+1;
				   }
			   }
	  }
	  function DrawDragMat($data,$x,$y,$w,$h,$x2){
		       global $LocY;
			   $y=$LocY;
			   global $Resdatas;
	           for($i=0;$i<count( $data);$i++){	
		           $id="SetMat=".$data[$i][3];
				   $co=count(  filterArrayContainStr($Resdatas,14, $data[$i][3]) );
			       DrawIDPic(returnPicPath($data[$i][3],$type),array($x,$y,$w,$w),$id);
				   $n= Pub_ReturnFinCode($data[$i]);
			       JAPI_DrawJavaDragbox( $n,$x,$y,$w,$h,8, "#222222","#ffffff",$id,8);
				   $BgColor="#bbaa66" ;
				   if($co==0)$BgColor="#66aa66" ;
				   if($co>4)$BgColor="#ff6666" ;
				   DrawRect("x".$co,"8","#ffffff",array($x+30,$y+30,10,10),$BgColor);
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
<?php //共用
      //拖曳區
      function Pub_DragTasks($data,$i ,$y,  $BgColor ,$startDay,$days  ){
		       global $startDate;
			   global $CalendarRect;
			   if($days=="")$days=1;
			   $id= "gdcode=".$data[$i][0]."=".$data[$i][2]."=".$data[$i][4]; //分類/gd碼
			   $Eid= "Egdcode=".$data[$i][0]."=".$data[$i][2]."=".$data[$i][4]."=".$data[$i][5];
			   $x= $CalendarRect[0]+ (CAPI_returnLocX($startDay,$startDate )-1)*$CalendarRect[2];
				   $w=$days*$CalendarRect[2];
				   JAPI_DrawJavaDragbox( $days ,$x,$y,$w,$h,10, $BgColor, "#ffffff",$id);
				   $x2= $x+ $w;
				   $w=$CalendarRect[2];
				   $BgColorE=PAPI_changeColor( $BgColor,array(0.8,0.8,0.8));
				   JAPI_DrawJavaDragbox("_" ,$x2,$y,$w,$h,10,   $BgColorE, "#ffffff",  $Eid);
	  }
	     //拖曳區2
      function Pub_ReturnFinCode($data){
		       global $ResTypeSingleData;
		       $n=$data[3];
			 //  $p=mb_substr( $data[3],0,1 );
		       $p= $ResTypeSingleData[9];
			   if($data[16]!="")$n=returnReSort($data[16],$data[17], $p) ;
			   return $n;
	  }
	  function Pub_ReturnFinCodeByCode($code){
	           global $ResdatasT;
			   $data=filterArray($ResdatasT,3,$code); 
			   return  Pub_ReturnFinCode($data[0]) ;
	  }
	  
?>

<?php  //完成度判斷
     function DrawFinRects($str,$x,$y,$ResCount){
	          $st=  explode("=", $str) ;
			  if( $st[$ResCount-1]=="已完成"){
					$BgColor="#55aaaa";
					 DrawRect("fin","8","#ffffff",array($x+4,$y-2,40,10),$BgColor );
					 return;
				}
			  DrawRect("","8","#ffffff",array($x,$y-2,11*$ResCount,11),"#000000" );
			  for($i=0;$i<$ResCount;$i++){
				  $BgColor="#556666";
				  if($st[$i]!="")$BgColor="#995555";
				  if($st[$i]=="已完成")$BgColor="#55aaaa";
				  if($st[$i]=="進行中")$BgColor="#88cc88";
				  DrawRect("","8","#ffffff",array($x,$y,10,5),$BgColor );
				  $x+=11;
			 
			  }
	 }
 
?>
<?php //重新GD排序
     function returnReSort($s1,$s2, $Prefix="x"){
		      if($s1=="")return "";
			  if( $Prefix=="x")$Prefix=substr($_POST["ResType"], 0, 1); 
	          
	          return $Prefix.PAPI_ReturnzeroCode($s1,4).$s1."_".PAPI_ReturnzeroCode($s2,2).$s2;
	 }  
     function getReSortRes(){//取得重新排列
	          global  $Resdatas;
			  $sort1= getLastSN2($Resdatas,16);
 
			  global  $ReSortResDatas,$NoSortDatas;
			  $NoSortDatas=$Resdatas;
			  $ReSortResDatas=array();
			  for($i=1;$i<=$sort1;$i++){
			     $Res=filterArray( $Resdatas,16,$i);
				 $ResS= sortArrays($Res ,17);
			 
				 $NoSortDatas= RemoveArray($NoSortDatas,16,$i);
				 array_push( $ReSortResDatas, $ResS);
			  }
 
	 }
	 function SortRes(){
		      getReSortRes();
		      global  $ReSortResDatas,$NoSortDatas;
			 
			  global $Prefix;
			  $sort1= getLastSN2($ResdatasT,16)+2;
			  $x=20;
			  $y=100;
			  $w=60;
			  $h=60;
			  $x2=$x;
			  global  $highest;
			  for($i=0;$i<=count($ReSortResDatas);$i++){
                  DrawSingleSortArea($ReSortResDatas[$i],$i+1,$x2,$y,$w,$h, $Prefix);
				  $x2+=$w+2;
				  if($x2>1000){
				     $x2=$x;
				     $y+=$highest+$h+20;
				  }
		      }
			  $x2=$x;
			  $y+=$highest+$h+22;
			  $BgColor="#222222";
			  $fontColor="#ffffff";
		      for($i=0;$i<count($NoSortDatas);$i++){
				   $id= "gdcode=".$NoSortDatas[$i][3];//."=".$NoSortDatas[$i][2]."=".$i;//1.gdcode. 2.
				   $msg=$NoSortDatas[$i][3];
				   JAPI_DrawJavaDragbox($msg,$x2,$y,$w,$h,10,$BgColor,$fontColor,$id);
				   DrawPic( returnPicPath($NoSortDatas[$i][3] ),array($x2,$y+12,$w,$w) );// $noPic
				   	  DrawRect($NoSortDatas[$i][4],8,"#ffffff",array($x2+20,$y+50,40,10),"#000000" );
				   $x2+=$w+1;
				   if($x2>1000){
				     $x2=$x;
				     $y+=$h+1;
				   }
			  }
			  
	  }
	 function DrawSingleSortArea($data,$s,$x,$y,$w,$h,$Prefix){
		       global $highest;
			   $BgColor="#999999";
			   $fontColor="#ffffff";
			   $pn=$Prefix.PAPI_ReturnzeroCode($s,3).$s ;
			   $ch=$h*count($data);
			   if($ch>$highest)$highest=$ch;
			   DrawRect( $pn,10,$fontColor,array($x,$y-12,$w,20),"#222222" );
			   $LastSN= getLastSN2($data,17)+1;
			   for($i=1;$i<=$LastSN;$i++){
				   $id="setSort1=".$s."=".$i;
				   $msg=$pn."_".PAPI_ReturnzeroCode($i,2).$i ;
				   JAPI_DrawJavaDragArea("",$x,$y,$w,$h,$BgColor,$fontColor,$id);
				   $arr=filterArray($data,17,$i);
				   if(count($arr)==1){  //拖曳
				      $id= "gdcode=".$arr[0][3]."=remove"; 
					  DrawPic( returnPicPath($arr[0][3] ),array($x,$y+12,$w,$w) );
					  JAPI_DrawJavaDragbox( $msg,$x,$y,$w-4,10,8,"#332222",$fontColor,$id);
					  DrawRect( $arr[0][4],8,$fontColor,array($x+20,$y+50,40,10),"#000000" );
				   }
				   $y+=$h+1;
			   }
	  }
     
     function upReSort($curentData ,$upSort1,$upSort2,$remove  ){
	 	      global $WebSendVal,$URL,$ResdataBase;
	         // echo $curentData[0][3].">".$upSort1;
			  $WHEREtable=array( "gdcode", "EData");
		      $WHEREData=array($curentData[0][3],"data"  );
			  $Base=array("reGDSort","skinSn");
			  $up=array($upSort1,$upSort2);
			  if($remove=="remove") $up=array("","");
			  $stmt=MAPI_MakeUpdateStmt($ResdataBase,$Base,$up,$WHEREtable,$WHEREData);
			  //echo  $stmt;
			  SendCommand($stmt,$data_library);		
			  JAPI_ReLoad($WebSendVal,$URL);
	 }
?>

<?php //等待接續清單
     function ListContinue(){
              CollectionContRes();
	 }
	 function CollectionContRes(){
			  global $ResPregresList;
			  $ResReadyArr=array();
		      for($i=0;$i<count($ResPregresList);$i++){
				  $arr=CollectionSContRes($i,count($ResPregresList));
				  array_push( $ResReadyArr, $arr);
		      }
			  global $CstartY;
			  global $singleResHieght;
			  $singleResHieght=20;
			  $CstartY=100;
			  $tc=0;
		      global  $CalendarH;
			  for($i=0;$i< count($ResReadyArr)-1;$i++) $tc+=count($ResReadyArr[$i]);
			  $CalendarH= (($singleResHieght+2)*$tc)+20;
              ListCalendar();
			  for($i=0;$i< count($ResReadyArr)-1;$i++){
			      ListContRes($ResReadyArr[$i],$ResPregresList[$i+1],$i+1 );
			  }
			
		   
	 }
	 function ListContRes($data,$PregrestName,$s){
		      global $CstartY;
		      global $singleResHieght;
			  global $ColorCode;
			  $colorSet= $ColorCode[12];
			  $x=20;
			  $y=$CstartY;
			  $h=$singleResHieght;
			  DrawRect( "未安排接續".$PregrestName ,12,"#ffffff",array($x,$y,280, $h),"#222222");
			  $y+= $h+2;
			  $BgColor=$colorSet[$s];
	          for($i=0;$i< count($data);$i++){
				  $pic= returnPicPath($data[$i][3]);
				  $principal=explode("=",$data[$i][9]);
			      $outsourcing=explode("=",$data[$i][10]);
				  DrawRect( $data[$i][3].$data[$i][4]."[".$principal[$s]."][".$outsourcing[$s],10,"#ffffff",array($x,$y,220, $h),"#555555");
				  DrawPic( $pic,array($x,$y,20, $h));
	              //拖曳區
				  
				  $id= "gdcode=".$data[$i][3]."=".$data[$i][2]."=".($s);
				  JAPI_DrawJavaDragbox( $PregrestName ,$x+220,$y,60,$h,10, $BgColor, "#ffffff",$id);
	              $startDate=explode("=",$data[$i][7]);
				  $days =explode("=",$data[$i][8]);
				  if( $startDate[$s]!=""){
	                 Pub_DragcontTasks($data ,$i ,$y,  $BgColor ,$startDate[$s],$days[$s] ,$s );
				  }
				  $y+= $h+2;
			  } 
			  $CstartY=$y;
	 }
	 function Pub_DragcontTasks($data,$i ,$y,  $BgColor ,$startDay,$days,$ResSort ){
		       global $startDate;
			   global $CalendarRect;
			   if($days=="")$days=1;
			   $id= "gdcode=".$data[$i][3]."=".$data[$i][2]."=".($ResSort);
			   $Eid= "Egdcode=".$data[$i][3]."=".$data[$i][2]."=".$ResSort."=".$startDay;
			   $x= $CalendarRect[0]+ (CAPI_returnLocX($startDay,$startDate )-1)*$CalendarRect[2];
				   $w=$days*$CalendarRect[2];
				   JAPI_DrawJavaDragbox( $days ,$x,$y,$w,$h,10, $BgColor, "#ffffff",$id);
				   $x2= $x+ $w;
				   $w=$CalendarRect[2];
				   $BgColorE=PAPI_changeColor( $BgColor,array(0.8,0.8,0.8));
				   JAPI_DrawJavaDragbox("_" ,$x2,$y,$w,$h,10,   $BgColorE, "#ffffff",  $Eid);
	  }
	 function CollectionSContRes($s,$count){
		      global $Resdatas;
			  $arr=array();
			  for($i=1;$i<count($Resdatas);$i++){
			      $state=explode("=",$Resdatas[$i][11]);
				  if( $state[$s]=="已完成"   ){
					  if( $state[$s+1]=="" or $state[$s+1]=="規劃排程")   array_push( $arr,$Resdatas[$i] );
				  }
			  }
			  return $arr;
	 }
?>
