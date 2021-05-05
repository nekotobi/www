 <?PHP
 	  require_once('ResScheduleApi.php');
 	  require_once('/Apis/PubJavaApi.php');
 	  require_once('/Apis/mysqlApi20.php');
	  require_once('/Apis/CalendarApi20.php');
   	  require_once('/Apis/ProjectApi.php');
      require_once('/Apis/PubApi20.php');

	  function CheckCookies(){
		       global $URL;
			   $URL="ResSchedule.php";
			   global $CookieArray;
			   $CookieArray=array("selectProject","startDate_Res","DateRange_Res" );
			   $WebSendArray=array("ResType","ListType","SortType","SelectWorkUnit");
	           //PubApi_setcookies($CookieArray, $URL);
		  	   JAPI_setcookiesAndReload($CookieArray, $WebSendArray, $URL);
			   PubApi_GetArrayCookie($CookieArray); 
               for($i=0;$i<count($CookieArray);$i++) {
				   global $$CookieArray[0][0];
				   $$CookieArray[0][1]=$$CookieArray[0][1];
	           }		  
	  }
	  CheckCookies();
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>美術素材表</title>
</head>
<body bgcolor="#b5c4b1">
<script type="text/javascript">
function Drop2Area(event) {
		event.preventDefault();
		var DragID  = event.dataTransfer.getData("text");
		var targetID =  event.currentTarget.id;
	    var tx= document.getElementById( targetID).style.left;
	    var x=tx.split("px");
	    document.Show.DragID.value=  DragID;
	    document.Show.target.value=  targetID;
	    Show.submit();
	}

</script>
<?php //主控台
	  defineData();
	  SwitchListType();
	  checkSubmit();
      DrawButtoms();
	  DrawDateRangeButtom();
      setJavaForm();//java表單一定要最後
?>
<?php //定義資料
    function defineData(){
	         global $URL,$selectProject;
			 if($selectProject=="")$selectProject="zombie";
			 global $CookieArray;
		     global  $SubmitName;
		     $SubmitName="submit";
			 //進度座標
		     global $CalendarRect;
		     global $startDate,$DateRange;
			 global $startDate_Res ,$DateRange_Res;
			 global $ColorCode;
		     $startDate=$startDate_Res;//"2021-1-1";
			 if($startDate=="")  $startDate=  "2021-1-1";
			 $DateRange=$DateRange_Res; 
			 if($DateRange=="")$DateRange=6;
		     $CalendarRect=array(315,80,10,0);
			 $ColorCode= GetColorCode();
			 //資源位置
			 global  $webPath,$ResPath,$noPic;
			 $webPath="..\\..\\..\\".$selectProject."Res";
			 $ResPath=dirname(dirname(dirname(__FILE__))) ."\\".$selectProject."Res";
			 $noPic=$webPath."\\NoPic.png";
			 global  $WebSendVal  ;
			 //$Restmp=$_POST["ResType"];
			 $selUnitmp=$_POST["SelectWorkUnit"];
			 if ($_POST["SelectWorkUnit"]=="--") $selUnitmp="";
			  if ($_POST["ListType"]=="清單") $selUnitmp="";
			 $WebSendVal=array(array("ResType",$_POST["ResType"]),
			                   array("ResSn",$_POST["ResSn"] )  ,
							   array("ListType",$_POST["ListType"] )  ,
							   array("SortType",$_POST["SortType"] )  ,
							   array("AssemblyType",$_POST["AssemblyType"] )  ,
							   array("SelectWorkUnit",$selUnitmp )  ,
							   );
			 //網頁變數
			 global $ResTypes,$ResTypeSingleData;
			 global $typeDatabase,$Resdatas, $ResLastGDSN,$ResdatasT;
             global  $ResdataBase,$typeDatabase;
			 global $ResTypeAll, $ResTypeAllStep;
			 $typeDatabase="restype_".$selectProject;
			 $ResdataBase="resdata_".$selectProject;
			 //類別
		     $ResTypeT= getMysqlDataArray( $typeDatabase);
		     $ResTypeAll=  filterArray( $ResTypeT,0,"data");
			 $ResTypeSingleDataT=filterArray( $ResTypeAll,2,$_POST["ResType"] );
			 $ResTypeSingleData=$ResTypeSingleDataT[0];
	         $ResTypes = returnArraybySort( $ResTypeAll,2);
			 $ResTypeAllStep= SetResTypeArr();
			 global $AssemblyType;
			 $AssemblyType=explode("_", $ResTypeSingleData[3]);
			 //細項
			 $type= $WebSendVal[0][1];
			 if( $type=="")return;
			 global $ResdataAll;
		     $currentType= filterArray(  $ResTypeAll,2, $type);
		     $ResdataAll= getMysqlDataArray($ResdataBase);
		     $Resdatas =  filterArray(  $ResdataAll,2, $type);
			 $ResLastGDSN=PAPI_getGDCODELastSN( $Resdatas,3);//最後的gd編碼
			 //排序
			 global $SortType;
		     $SortType=array("▲","▼");
			 AddResSort();
			 AddMRes();//月
 	         //資源進程
			 global $ResPregresList;
			 $ResPregresList= explode("_",  $currentType[0][3]) ;
			 //java傳遞欄位
		     global  $inputsTextNames ;
             $inputsTextNames=array("cost","DragID","target");
		     //分類資料
			 global $ListType;
			 global $className,$class;
			 $className= explode("=", $ResTypeSingleData[7]) ;
			 $ListType=array("清單","排程表","統計","熱區","GD排序","接續");
			 if($_POST["ResType"]=="SceneBattel") array_push( $ListType,"怪物分布");
	         for($i=0;$i<count($className);$i++){
				 array_push( $ListType,$className[$i]."[".$i);
	         }
			 SortResData();
		     global $singleResHieght;
			 $singleResHieght=  count($ResPregresList)*20;
			 if($singleResHieght<80)$singleResHieght=60;
			 global $CalendarH;
			 $CalendarH= $singleResHieght*count($Resdatas)+ 50;
			 global $Prefix;
			 $Prefix= $ResTypeSingleData[9];
			 if($Prefix=="")$Prefix=substr($_POST["ResType"], 0, 1); 
 
	}//重新排序
	function AddResSort(){
	     	 global $SortType; 
			 global $ResLastGDSN;
			 $s=0;
			 for($i=1;$i< $ResLastGDSN;$i+=5){
				  $s+=1;
				  array_push( $SortType,$s);
			 }
	}
 
	 //整理列印Res
	function SortResData(){
		     global $Resdatas;
	         if($_POST["SortType"]=="" or $_POST["SortType"]=="▲"){
		        $Resdatas=	PAPI_sortCodeWithGDCode( $Resdatas ,3);
				return;
			 }
			 if($_POST["SortType"]=="▼"  ){
			    $Resdatas= PAPI_sortCodeWithGDCode( $Resdatas ,3,"false");
			    return;
			 }
			 //月計畫
			  if( strpos($_POST["SortType"],"m") !== false  ){
				 $m=explode("m",$_POST["SortType"] );
				 $Resdatas=SortMPlanResData($m[0]);
				  return;
			  }
			 //分頁
			 $so=$_POST["SortType"];
			 $arr=array();
			 for ($i=0;$i<count($Resdatas);$i++){
			      $s=PAPI_GDCODE2Sort($Resdatas[$i][3]);
				  if($s>($so-1)*5 and $s<=($so)*5){
				   array_push($arr,$Resdatas[$i]);
				  }
			 }
			 $Resdatas= $arr;
	}
	function SortMPlanResData($m){ //過濾月資料
	          global $Resdatas;
			  global $ResTypeSingleData;
			  $c=0;
			  $strArr=explode("=",$ResTypeSingleData[7]);
			  //取得月計畫欄位
			  for($i=0;$i<count($strArr);$i++){
			      if($strArr[$i]=="月計畫")$c=$i;
			  }
			  $arr=array();
			  for($i=0;$i<count($Resdatas);$i++){
			      $strAr =explode("=",$Resdatas[$i][14]);
				  if($strAr[$c]==$m){
					  array_push( $arr,$Resdatas[$i]);
				  }
			  
			  }
		      return $arr;
	}
	
?>
<?php //判斷submit
     function checkSubmit(){
		       global $data_library, $ResdataBase ;
			   global $WebSendVal,$URL;
	           if($_POST["submit"]=="變更"){
				   //echo $_POST["ResType"];
				   upform();
					return;
			   }
			   if($_POST["submit"]=="+"){ //新增工單
				  MAPI_AutoCreateNewMsQLData($data_library, $ResdataBase );
				  JAPI_ReLoad($WebSendVal,$URL);
			   } 
			   if($_POST["DragID"]!=""){ //拖曳物件
				    upScedule();
			   }
	  }
?>
<?php //buttoms;
	 function DrawDateRangeButtom(){
		       if($_POST["ListType"]!=="排程表")return;
		 
	            //控制日期
			   global $URL,$startDate,$DateRange;
			   global $WebSendVal;
			   $LocX=305;
			   $LocY=68;
			   CAPI_setDateRangeButtom($URL,$LocX,$LocY,$startDate,$DateRange,$WebSendVal,"Res");
	  }
     function DrawButtoms(){
		      global $URL;
              global $CookieArray;
			  global $ProjectTypes,$selectProject,$startY,$URL;
			  $startY=20;
		      //資源分類
			  global $ResTypes; 
			  $Rect=array("20",$startY,"40","15");
		      DrawSingleButtom(  $ResTypes,0,$Rect);
			  //顯示
			  global $ListType;
			  DrawSingleButtom($ListType,2,$Rect);
			  //排序
			   $Rect =array(20,$startY,15,15);
			   global $SortType;
			  DrawSingleButtom($SortType,3,$Rect,6);
			  //新增工單
			  if($_POST["ListType"]=="清單") {
				   $x=count($SortType)-2;
				   AddResButtom( );
			  }
	 }
	 function AddResButtom(   ){
	          global $WebSendVal;
			  global $URL;
			  global $ResLastGDSN;
			  global $startY;
		      $valArray=$WebSendVal; 
			  $SubmitName="submit";
			  $BgColor="#aa5555";
			  $Rect =array(20,  $startY,40,12);
			  DrawRect( "X".$ResLastGDSN,"10","#ffffff",$Rect,"#222222" );
			  array_push($valArray,array("AddRes" , $ResLastGDSN));
		      array_push($valArray,array("Type", $_POST["ResType"]));
			  array_push($valArray,array("ECode",  PAPI_returnECode( )));
		      array_push($valArray,array("EData",  "data"));
			  $GdCode=ProAPI_ReturnGDCode( $_POST["ResType"],$ResLastGDSN+1);
			  array_push($valArray,array("gdcode", $GdCode));
              $Rect[0]+= $Rect[2]+2;
			  $Rect[2]= 20;
			  sendVal($URL,  $valArray ,$SubmitName,  "+",$Rect,10,$BgColor); 
	 }
	 function DrawSingleButtom($data,$Wsort ,$Rect,$fontSize=10){
		      global $URL;
		      global $WebSendVal  ;
			  global $startY;
			  $SubmitName="submit";
			  $valArray=$WebSendVal;
			  $valArray[1][1]="";
			  $BaseRect=$Rect;
			  $Rect[1]=$startY;
	          for($i=0;$i<count($data);$i++){
				   $BgColor="#222222";
				   $name= $data[$i] ;
				   if( $WebSendVal[$Wsort][1]== $name)$BgColor="#ff2222";
				   $valArray[$Wsort][1]=  $name;
			       sendVal($URL,  $valArray ,$SubmitName,$name,$Rect,$fontSize,$BgColor); 
				   $Rect[0]+=$Rect[2]+2;
				  
				   if( $Rect[0]>280    ){
				  // $count=0;
				   $Rect[0]=$BaseRect[0];
				   $Rect[1]+=$Rect[3] +1;
				   }
				 
			  }
			  $startY=$Rect[1] +$Rect[3] +1;
	 }
?>
<?php //List
     function SwitchListType(){
			  global $typeDatabase,$Resdatas;
		      global $ResPregresList;
			  global $singleResHieght;
              $h= $singleResHieght;// 20*count($ResPregresList);
			 // $w=$singleResHieght;
			  $w=80;
			  if($h<60)$h=60;
			  $Rect=array("20","110",$w,$h);
			  if(strpos($_POST["ListType"],"[") != false){ 
			     DrawType();
				 return;
			  }
		      if($_POST["ListType"]=="熱區"){
				 ListHotZone();
			     return;
			  }
		      if($_POST["ListType"]=="怪物分布" & $_POST["ResType"]=="SceneBattel"){
				 StageMobSet();
			  }
			  //統計
			  if($_POST["ListType"]=="統計"){
				  Resstatistics();
			      return;
			  }	
			  if($_POST["ListType"]=="GD排序"){
			      SortRes();
				  return;
			  }
	          if($_POST["ListType"]== "接續"){
		         ListContinue();
				 return;
	          }
			  if($_POST["ListType"]=="排程表")    ListCalendar();
			  if($_POST["SelectWorkUnit"]!=""){
				     ListSelectUintWork();
					 return;
				  }
			  for($i=0;$i<count($Resdatas);$i++){
	              ListSingle($Resdatas[$i],$Rect);
			      $Rect[1]+=$Rect[3]+2;
			  }
	 }
	 function DrawType(){ //列印[]分類
	          global   $ResTypeSingleData;
			  global   $ClasstypeSort;//分類編號
              $sort=6;
			  $ClasstypeSortArr=explode("[", $_POST["ListType"]);
			  $ClasstypeSort=$ClasstypeSortArr[1];
			  $classArr=explode("=", $ResTypeSingleData[$sort]);
			  $class=explode("_", $classArr[  $ClasstypeSort]);
			  
			  array_push( $class,"未分類");
			  //拖曳底部
			  global $ColorCode;
			  $colorSet= $ColorCode[12];
			  if(strpos($_POST["ListType"],"珠色") !== false) $colorSet=$ColorCode[13];
		      DrawTypeDragBase(  $class,20,120,1000,80,$colorSet);
           
			  
			
	 }

     function DrawTypeDragObj($typeName,$x,$y,$Typesort,$types,$sortArr){
		      global  $Resdatas;
			   global $ResPregresList; 
			   $ResCount=count( $ResPregresList);
	         // $sortArr= returnSortTypes( $Resdatas,$typeName,$Typesort,$types);
			  $BgColor="#333333";
			  $fontColor="#ffffff";
		      $w=50;
			  $h=20;
			  $ax=$x+30;
			  $y+=4;
			  $Acount=0;
			  for($i=0;$i<count($sortArr);$i++){
			      $id= "gdcode=".$sortArr[$i][3]."=".$sortArr[$i][2]."=".$i;//1.gdcode. 2
				  $BgColor="#000000";
				  DrawRect("","12","#ffffff",array($ax-1,$y-1,$w+2,$w+2+$h),$BgColor);
				   DrawIDPic(returnPicPath($sortArr[$i][3]),array($ax,$y+$h,$w,$w),$id);
				   //顯示工作狀態
				   DrawFinRects($sortArr[$i][11],$ax,$y+$h+42,  $ResCount);
				  //$fin= isMatFin($sortArr[$i]); 
		     	 // DrawFinRects($fin,$ax,$y+$h+40,  $ResCount);
				//  if(  $BgColor=="#335555" )   DrawRect("fin",7,"#ffffff",array($ax,$y+$h,12,12),$BgColor);
				  //判斷素材完成
				 
				  JAPI_DrawJavaDragbox(  $sortArr[$i][3] ,$ax,$y,$w,$h,8, $BgColor,$fontColor,$id);
				  if($sortArr[$i][2])
				  DrawRect($sortArr[$i][4] ,"8","#ffffff",array($ax,$y+$h-10,$w,10),"#222222" );
				  
				  $ax+=$w+1;
				  $Acount+=1;
				  if($Acount>=16){
				     $y+=$w+$h+4;
					 $ax=$x+30;
					 $Acount=0;
				  }
			  }
	 }
	 //列印底部
	 function DrawTypeDragBase($types,$x,$y,$w,$h,$colorSet){
			  global $ClasstypeSort;//分類編號
			  global $ResTypeSingleData;
			  global $Resdatas;
			  $fontColor="#ffffff";
			  $By=$y;
			  $Typesort=$ClasstypeSort;
			  
              $AllResTotal=count ($Resdatas);
			  if($ResTypeSingleData[8]!="") $AllResTotal=$ResTypeSingleData[8];
			  $sortArrs=array();
			  //計算區量
		      for($i=0;$i<count($types);$i++){
			     $sortArr= returnSortTypes( $Resdatas,$types[$i],$Typesort,$types);
				  array_push( $sortArrs, $sortArr);
			  }
			  //拖曳區
			  $total=0;
	          for($i=0;$i<count($types);$i++){
				  $id="tableName=classification=".$types[$i]."=".$ClasstypeSort;
				  $ArrCount=count($sortArrs[$i]);
				  $total+= $ArrCount;
				  $BgColor= $colorSet[$i];
				  if($types[$i]=="未分類") $BgColor="#888888";
				  
				  $Ah=ceil($ArrCount/16)*$h;
				  if($ArrCount==0) $Ah= $h;
				  JAPI_DrawJavaDragArea($types[$i],$x,$y,$w,$Ah,$BgColor,$fontColor,$id,"12" );
				  //季計畫完成度
				  if(strpos($_POST["ListType"],"計畫") !== false){
					$msg=$total."/".$AllResTotal."[".(int)(($total/$AllResTotal)*100)."%]";
				    DrawRect($msg,8,"#ffffff",array($x+1,$y+16,47,14),"#000000");
				
				  }
				  $y+=$Ah+2;
			  }
			  for($i=0;$i<count($types);$i++){
				  //拖曳物件
			      DrawTypeDragObj($types[$i],$x+20,$By,$Typesort,$types, $sortArrs[$i]);
				  $lineCont=ceil(count( $sortArrs[$i])/18);
				  if ($lineCont<1)$lineCont=1;
				  $Ah=$lineCont*$h;
				  ListProgressRate($types[$i],$sortArrs[$i],900,$By);   //列印完成度
				  $By+=$Ah+2;
				 
			  }
		   //   
	 }
	 //取得該type的resdata
	 function returnSortTypes( $Resdatas,$typeName,$Typesort,$types){
		      $arr=array();
	          for($i=0;$i<count( $Resdatas);$i++){
				  $t=explode("=",$Resdatas[$i][14]);//14為type欄位
				  if($typeName!="未分類"){
				    if(  $t[$Typesort]==$typeName)array_push($arr,$Resdatas[$i]);
				  }
				  if($typeName=="未分類"){
				     if (!in_array($t[$Typesort], $types))array_push($arr,$Resdatas[$i]); 
				  }
			  }
			  return $arr;
	 }
	 function ListSingle($data,$Rect){
		 		  global $URL;
	              global $WebSendVal  ;
			      global $webPath,$ResPath;
				  $ERect=$Rect;
	 			  //編號
				  $BgColor="#222222";
				  $GDcode= $data[3];
			      $SubmitName="submit";
				  $ValArray=$WebSendVal;
				  $name=$GDcode;
				  if ($data[16]!="")$name=$data[16]."_".$data[17]."(".$GDcode.")";
				  if($_POST["ListType"]=="清單"){
				     array_push(  $ValArray,array("EditRes",$GDcode));
			         sendVal($URL,  $ValArray ,$SubmitName,$name,$Rect,10,$BgColor); 
				  }else{
					  $sn=$data[3];
					  $Resort=returnReSort($data[16],$data[17]);
					  if($Resort!="")$sn=$Resort;
				      DrawRect($sn,10,"#ffffff",$Rect,"#222222" );
					  if($Resort!="")  DrawRect($data[3],8,"#aaaaaa",array($Rect[0]+45,$Rect[1]+12,30,10),"#444444" );
				  }
				  //名稱
				  $nRect=array($Rect[0]+1,$Rect[1]+$Rect[3]-20,$Rect[2]-2,18);
			
				  DrawRect($data[4],10,"#000000",$nRect,"#eeeeee" );
			      //縮圖
				  $Rect[0]+=$Rect[2]+2;
				  $Rect[2]=$Rect[3];
				  
				  if($data[18]=="") {
					  $LinkPath=returnPicPath($GDcode ,"true" );
				  }
				  if($data[18]!="") {
					  $LinkPath=returnPicPath($GDcode ,"Link" );
				  }
				  DrawLinkPic(returnPicPath($GDcode ),$Rect,$LinkPath );
				  //jila
				  $jila=$data[12];
				  $Link="http://bzbfzjira.iggcn.com/browse/ZW-".$jila;
				  if( $jila!="") DrawLinkRect_newtab($jila,8,"#ffffff",$Rect[0],$Rect[1],20,10,"#aa5555",$Link,$border);
				  if( $_POST["EditRes"]== $GDcode and $_POST["EditRes"]!="") UpSingle($data,$ERect);
				  if($_POST["ListType"]!="排程表")return;
			
				  //可拖曳工作分類
                  SchedlueList($data ,$Rect);
				  //如果是編輯
				
	 } 
	 function returnPicPath($GdCode ,$Basepic="false"){
			   global $noPic;
	           global $webPath,$ResPath;
			   global $selectProject;
			   $type= returnGDType($GdCode);
			   $resdir="\\".$type."\\spic\\".$GdCode.".png";
			   $Bigpic= "/".$selectProject."Res/".$type."/".$GdCode.".png";
	         
			   $pic=$webPath.$resdir;
			   $path=$ResPath.$resdir;
			   if($Basepic=="true") {
				  if (is_readable($path))  return $Bigpic;
			   }
			   if($Basepic=="Link") {
				     $Linkpic= "/".$selectProject."Res/".$type."/LinkPic/".$GdCode.".png";
				   return $Linkpic;
			   }
			   if (is_readable($path) != false)   return $Bigpic ;
			   return $noPic;
			   
	 }
	 function returnGDType($GdCode){
	          $s=  substr($GdCode,0, 1); 
			  if($s=="H")return "Hero";
			    if($s=="M")return "Mob";
				 if($s=="B")return "Boss";
				  if($s=="S")return "SceneBattel";
				    if($s=="A")return "Army";
					   if($s=="T")return "Town";
					     global $WebSendVal ;
					return   $WebSendVal[0][1];
	 }
	 function SchedlueList($data ,$Rect){ //拖曳區
	           global $CalendarRect;
   		       global $ResPregresList;
			   global $startDate,$DateRange;
		       global $ColorCode;
			   global $singleResHieght;
		       $fontColor="#ffffff";
			   $Rect[0]+=$Rect[2];
			   $Rect[3]=$Rect[3]/count($ResPregresList)-1;
			   $Rect[2]=40;
			   $startDay=explode("=",$data[7]);
	           $workingDays=explode("=",$data[8]);
			   $principal=explode("=",$data[9]);
			   $outsourcing=explode("=",$data[10]);
			   $state=explode("=",$data[11]);
			   $jila=explode("=",$data[12]);
		
			   //7-s w-8 p-9 out-10 state=11
			    //附註
 			   if(trim($data[13])!="")DrawRect($data[13],10,"#000000",array(22,$Rect[1]+2,100,20),"#ffee88");
			   for($i=0;$i<count($ResPregresList);$i++){
				   $wd= $workingDays[$i];
	               if($wd=="")$wd=1;
				   $BgColor=ColorCode[11][$i];
				   $id= "gdcode=".$data[3]."=".$data[2]."=".$i;
				   $Eid= "Egdcode=".$data[3]."=".$data[2]."=".$i."=".$startDay[$i];
				   $x=$Rect[0];
                   $y=$Rect[1]+$i*($Rect[3]+1);
				   $w=100 +(100-$singleResHieght);
				   $h=$Rect[3];
				   //分類標題
	               $costArr=explode("=",$data[15]);
				   $msg=$ResPregresList[$i];
				  
				   if(  $principal[$i]!="")$msg=$msg."[".$principal[$i]."]";
				   if(  $outsourcing[$i]!="")$msg=$msg."[".$outsourcing[$i]."]";
				   if(  $state[$i]!="")$msg=$msg."[".$state[$i]."]";
				   $BgColor=$ColorCode[12][$i];
				   if($startDay[$i]==""  or $state[$i]=="未定義"   ) $BgColor="#222222";
				   if($state[$i]=="已完成")  $BgColor="#999999";
				   if($state[$i]=="規劃排程")  $BgColor=PAPI_changeGlayColor(  $BgColor,2);
				   if($state[$i]=="進行中") $BgColor=PAPI_changeColor( $BgColor,array(1.3,1.3,1.3));
                   JAPI_DrawJavaDragbox(   $msg ,$x,$y,$w,$h,10, $BgColor, "#ffffff",$id);
				   //價格
				   //已排定
				   if( $startDay[$i]!="" and $state[$i]!="未定義" ){
					   //判斷時間範圍
					  if(CAPI_boolInDataRange($startDay[$i],$wd, $startDate,$DateRange)  ){
					    $workWid=$CalendarRect[2]*$wd;
					    $fontColor="#eeeeee";
						$x2= $CalendarRect[0]+ (CAPI_returnLocX($startDay[$i],$startDate )-1)*$CalendarRect[2];
						//補助線
						if($state[$i]!="已完成")  DrawRect("",1,$fontColor,array($x+$w,$y+5,$x2-$x-$w,2),$BgColor);
						//主拖曳
						$xe=$x2;
						$endx=0;
						if($x2<($x+$w)){
							$x2=($x+$w);
							$endx=$x2-$xe;
						}
					    JAPI_DrawJavaDragbox( "[".$wd."]",$x2,$y+1,$workWid-$endx,$h-4,10, $BgColor,$fontColor,$id);
						//拖曳天數
					    $BgColorE=PAPI_changeColor( $BgColor,array(0.8,0.8,0.8));
						if($state[$i]!="已完成") JAPI_DrawJavaDragbox( "",$xe+$workWid,$y+1,$CalendarRect[2] ,$h-4,10, $BgColorE,$fontColor, $Eid);
					  }
				   }
				   if( $costArr[$i]!="") DrawRect( $costArr[$i],7,"#ffffff",array( $x+$w-30,$y,30,12),"#aa7744");
			   }
			 
	 }
	 function UpSingle($data,$Rect){
	          //$upFormVal ==>0/id 1/name 2/URL 
			  //$UpHidenVal=array 0/name,1/val
			  //$inputVal=0/type 1/name 2/showname 3/fontsize 4/5/6/7rect  8/bgcolor 9/fontColor 10/val 11/size
			  global $URL;
			  global $WebSendVal;
			  $upFormVal=array("EditResForm","EditResForm",$URL);
			  $UpHidenVal=array();
			//  array_push($UpHidenVal,array("ResType",$_POST["ResType"]));
			  		  array_push($UpHidenVal,array("EResType",$_POST["ResType"]));
					  array_push($UpHidenVal,array("SortType",$_POST["SortType"]));
			 // echo ">".$_POST["SortType"].$WebSendVal[0][1];
			  array_push($UpHidenVal,array("gdcode",$data[3]));
			  $BGRect=$Rect;
			  $BGRect[2]=400;
              $inputVal=array(); 
			  //基底
		      DrawRect($msg,$fontSize,$fontColor,$BGRect,"#442222" );
			
			  $name=array("text","name" ,$data[3]."修改名字","10", $Rect[0],$Rect[1],$Rect[2],$Rect[3], "#aaaaaa", "#ffffff", $data[4],14);
			  $remark=array("text","remark" ,"附註","8", $Rect[0]+100,$Rect[1],$Rect[2],$Rect[3], "#aaaaaa", "#ffffff", $data[13],24);
			  $reGDSort =array("text","reGDSort" ,"重新編號","10", $Rect[0]+80,$Rect[1]+21,20,20, "#aaaaaa", "#ffffff", $data[16],24);
			  //類別
			  //jila
			  $Jila =array("text","jila" ,"jila","8", $Rect[0]+220,$Rect[1] ,60,20, "#aaaaaa", "#ffffff", $data[12],14);
			  //pic
			  $file=array("file","pic" ,"pic","6",  $Rect[0] ,$Rect[1]+30,$Rect[2],$Rect[3], "#fffff", "ffffff", "1",10);
			  //參考圖
			  $file2=array("file","LinkPic" ,"LinkPic","6",  $Rect[0]+100 ,$Rect[1]+30,$Rect[2],$Rect[3], "#fffff", "ffffff", "1",10);
			  $submit=array("submit","submit" ,"s","10",  $Rect[0]+320  ,$Rect[1]+10 ,$Rect[2],$Rect[3], "#ffffff", "#fffff", "變更",20);
			  array_push($inputVal,$name);
			  array_push($inputVal,$remark);
			  array_push($inputVal,$file);
			  array_push($inputVal,$file2);
			  array_push($inputVal,$Jila);
			  array_push($inputVal,$submit);
			  upSubmitform($upFormVal,$UpHidenVal, $inputVal);
	 }
 
?>
<?php //ListCalendar  
      function ListCalendar(){
		       global $ResPregresList;
		       global $Resdatas;
			   global $CalendarRect;
			   global $startDate,$DateRange;
			   global $singleResHieght;
			   global $CalendarH;
			   $h=$CalendarH; // $singleResHieght*count($Resdatas)+ 50;
			 //  if($h<600)  $h=600;
	           CAPI_DrawBaseCalendar($startDate,$DateRange,$CalendarRect[0],$CalendarRect[1],$CalendarRect[2],$h);
               $Rect=array(320,0,40,10);
			   //工作人員區
			   ProAPI_DrawWorkersAreas($Rect,true);
	  }
?>
<?php //判斷submit;
      function setJavaForm(){
		       global $URL;
			   global $ResdataBase,$typeDatabase;
			   global $inputsTextNames ;
			   global $WebSendVal;
			   $x=600;
			   $y=60;
	           JAPI_CreatJavaForm( $URL, $ResdataBase,$inputsTextNames,$WebSendVal,$x,  $y );
			   //費用
			   $id="tableName=cost";
			   $BgColor= "#aa9977";
			   JAPI_DrawJavaDragArea("_",590,58,10,20,$BgColor,$fontColor,$id,"12" );
			   //清除
			   $id="cmd=delete";
 
			   JAPI_DrawJavaDragArea("D",920,58,10,10,"#aa7777" ,"#ffffff",$id,7 );
			    //清除
			   $id="cmd=delete=all";
 
			   JAPI_DrawJavaDragArea("D",940,58,10,10,"#ff7777" ,"#ffffff",$id,7 );
	  }

	  function upform(){
		       global $ResdataBase,$typeDatabase;
			   global $selectProject;
			   global $webPath,$ResPath;
			   global $URL;
			   global $WebSendVal;
			   global $data_library,  $ResdataBase ;
			  //  echo  $_POST["ResType"].">=".$_POST["EResType"];;
			   //上傳圖檔
			   $upPath="..\\..\\".$selectProject."Res\\".$_POST["ResType"];
			   if (!is_dir($upPath) ) mkdir($upPath, 0700);
               $sPicPath=$upPath."\\spic";
			   if (!is_dir($sPicPath) ) mkdir($sPicPath, 0700);

			   if($_FILES["pic"]["name"]!=""){
				  $filePath= $upPath."\\".$_POST["gdcode"].".png";
	              $filePaths= $sPicPath."\\".$_POST["gdcode"].".png";
				//  echo $filePath;
				  move_uploaded_file($_FILES["pic"]["tmp_name"], $filePath);
				  $cmd="convert     $filePath    -flatten  -resize 256  $filePaths";
			      exec($cmd);
			   }
			   //LinkPic
			   $specialVal=array();
			   $LinkPath="..\\..\\".$selectProject."Res\\".$_POST["ResType"]."\\LinkPic";
			   echo  $LinkPath;
			   if (!is_dir($LinkPath) ) mkdir($LinkPath, 0700);
			   if($_FILES["LinkPic"]["name"]!=""){
				   $LinkPath2= "//".$selectProject."Res/".$_POST["ResType"]."/LinkPic/".$_POST["gdcode"].".png";
				   $LinkPath= $LinkPath."\\".$_POST["gdcode"].".png";;
			       $specialVal=array(array("LinkPic",$LinkPath2));
				   move_uploaded_file($_FILES["LinkPic"]["tmp_name"], $LinkPath);
			
			   }//
			   //修改資料
			     $WHEREtable=array("EData","gdcode");
				 $WHEREData=array("data",$_POST["gdcode"]);
			     MAPI_AutoEditMsQLData($data_library, $ResdataBase,$WHEREtable,$WHEREData , $specialVal);
			     $arr=array(array("ResType",$_POST["ResType"] ),array("ListType",$_POST["ListType"] ),array("SortType",$_POST["SortType"] ));
                 JAPI_ReLoad(  $arr,$URL);
			 
	  }
?>
<?php //上傳
      function  Addform(){
	            global $data_library,$ResdataBase;
			    global $ResLastGDSN;
				
	  }
      function  returnState($Basestr,$state,$ResSort,$count){ //回傳切割_1_xx_1
	            $arr=explode("=",$Basestr);
				$str="";
				for($i=0;$i<=$count;$i++){
				    $s=$arr[$i];
					if($ResSort==$i)$s=$state;
					$str=$str.$s."=";
				}
				return $str;
	  }
	  function  clearSc($curentData, $WHEREtable,$WHEREData,$sort,$all){
		        global $WebSendVal,$URL;
		        global $ResdataBase;
		        //7 startDay 8 workingDays 9principal 10 outsourcing 11 state
				global $ResPregresList;
		       // echo "clear".$curentData[0][3].">".$sort;
		        $Base=array("startDay","workingDays","principal","outsourcing","state");
				$up=array();
				for($i=0;$i<count( $Base);$i++){
				    $Rsort=$i+7;
					$str=returnState($curentData[0][$Rsort],"",$sort,count($ResPregresList));
					if($all=="all") $str="";
 
					array_push( $up,$str);
				}
				$stmt=MAPI_MakeUpdateStmt($ResdataBase,$Base,$up,$WHEREtable,$WHEREData);
			    echo $stmt;
				  SendCommand($stmt,$data_library);		
			    JAPI_ReLoad($WebSendVal,$URL);
	  }
      function  upScedule(){
			    global $data_library,$ResdataBase;
			    global $ResPregresList;
				global $Resdatas;
				global $ResdataAll,$ResTypeAllStep;
				global $WebSendVal,$URL;
				global $ListType;
			    $DragID=explode("=",$_POST["DragID"]);
				$data2=explode("=",$_POST["target"]);
				$gdcode=$DragID[1];
				$Type=$DragID[2];
				$ResSort=$DragID[3];
				$tableNames=returnTables($data_library ,$ResdataBase);
		        $WHEREtable=array( "gdcode", "Type");
		        $WHEREData=array( $gdcode,$Type  );
				//目前的資源資料
				$curentData=filterArray($ResdataAll,3,$gdcode);
				 //判斷特殊狀況
				if($DragID[2]=="PrioritySort"){
					 upPriority($curentData, $WHEREtable,$WHEREData,$DragID[1],$data2[2]);
					 return;
				}
				if($data2[0]=="cmd"){
				   if($data2[1]="delete") clearSc(	$curentData, $WHEREtable,$WHEREData,$DragID[3],$data2[2]);
				   return;
				}
			 
				if($DragID[0]=="SetMat"){ //怪物分布圖
			       $gdcode=$data2[1];  
				   $curentData=filterArray($Resdatas,3,$gdcode);
				   upDragMat( $curentData,$DragID[1],$data2[2] );
				   return;
				}
				if($data2[0]=="setSort1"){ //重新排序
				   $curentData=filterArray($Resdatas,3,$gdcode);
				   $sort1=$data2[1];
				   upReSort( $curentData,$sort1,$data2[2],$DragID[2]);
			       return;
				}
				//設定項目時間
				if($DragID[0]=="gdcode"){
					if($data2[0]=="startDay"){
					   $Base=array("startDay");
					    $c=count($ResTypeAllStep[$curentData[0][2]]);
					   //$str=returnState($curentData[0][7],$data2[1],$ResSort,count($ResPregresList));
					   $str=returnState($curentData[0][7],$data2[1],$ResSort,$c);
					   $up=array($str);
					}
			     	if($data2[0]=="tableName"){
							 
					   //如果是類別
					   if($data2[1]=="classification")  $ResSort=explode("[",$_POST["ListType"])[1];
					   $tableName=$data2[1];
					   $Base=array($tableName);
					   $val=$data2[2];
                       if($val=="--")$val="";
					   if($data2[1]=="cost") $val=$_POST["cost"];
					   $sort= MAPI_returnTableSort($tableNames, $tableName);
					   $count=count($ResPregresList);
					   if(  $tableName="classification")  $count=count($ListType)-2;//如果是類別
					   $str=returnState($curentData[0][$sort],$val,$ResSort,$count );
					   $up=array($str);
					}
		 
				}
			 
				if($DragID[0]=="Egdcode"){
				    $Base=array("workingDays");
			        $arr=explode("=",$curentData[0][7]);
				    $e=$DragID[4];
					$s= $data2[1];
					$days=CAPI_GetPassDays($s,$e);
					$ResSort=$DragID[3];
				    $str=returnState($curentData[0][8],$days,$ResSort,count($ResPregresList));
				    $up=array($str);
		 
				}
			//	echo ">".$curentData[0][$sort]."[";
			    $stmt=MAPI_MakeUpdateStmt($ResdataBase,$Base,$up,$WHEREtable,$WHEREData);
			 //  echo $stmt;
			   SendCommand($stmt,$data_library);		
			   JAPI_ReLoad($WebSendVal,$URL);
	  }
	
?>