 <?PHP
 
 	  require_once('/Apis/PubJavaApi.php');
 	  require_once('/Apis/mysqlApi20.php');
	  require_once('/Apis/CalendarApi20.php');
   	  require_once('/Apis/ProjectApi.php');
      require_once('/Apis/PubApi20.php');

	  function CheckCookies(){
		       global $URL;
			   $URL="ResSchedule.php";
			   global $CookieArray;
			   $CookieArray=array("selectProject","startDate_Res","DateRange_Res");
			   $WebSendArray=array("ResType","ListType","SortType");
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
			 //進度座標
		     global $CalendarRect;
		     global $startDate;
			 global $startDate_Res ,$DateRange_Res;
			 global $ColorCode;
		     $startDate=$startDate_Res;//"2021-1-1";
			 if($startDate=="")  $startDate=  "2021-1-1";
		     $CalendarRect=array(315,80,10,0);
			 $ColorCode= GetColorCode();
			 //資源位置
			 global  $webPath,$ResPath,$noPic;
			 $webPath="..\\..\\..\\".$selectProject."Res";
			 $ResPath=dirname(dirname(dirname(__FILE__))) ."\\".$selectProject."Res";
			 $noPic=$webPath."\\NoPic.png";
			 global  $WebSendVal  ;
			 $WebSendVal=array(array("ResType",$_POST["ResType"] ),
			                   array("ResSn",$_POST["ResSn"] )  ,
							   array("ListType",$_POST["ListType"] )  ,
							   array("SortType",$_POST["SortType"] )  ,
							   );
       
			 //網頁變數
			 global $ResTypes,$ResTypeSingleData;
			 global $typeDatabase,$Resdatas, $ResLastGDSN;
             global  $ResdataBase,$typeDatabase;
			 $typeDatabase="restype_".$selectProject;
			 $ResdataBase="resdata_".$selectProject;
			 //類別
		     $ResTypeT= getMysqlDataArray( $typeDatabase);
		     $ResTypeT2=  filterArray( $ResTypeT,0,"data");
			 $ResTypeSingleDataT=filterArray( $ResTypeT2,2,$_POST["ResType"] );
			 $ResTypeSingleData=$ResTypeSingleDataT[0];
	         $ResTypes = returnArraybySort( $ResTypeT2,2);
			 //細項
			 $type= $WebSendVal[0][1];
			 if( $type=="")return;
		     $currentType= filterArray(  $ResTypeT2,2, $type);
		     $ResdatasT= getMysqlDataArray($ResdataBase);
		     $Resdatas =  filterArray(  $ResdatasT,2, $type);
			 $ResLastGDSN=PAPI_getGDCODELastSN( $Resdatas,3);//最後的gd編碼
			 //排序
			 global $SortType;
		     $SortType=array("▲","▼");
			 AddResSort();
 	         //資源進程
			 global $ResPregresList;
			 $ResPregresList= explode("_",  $currentType[0][3]) ;
           
			 //java傳遞欄位
		     global  $inputsTextNames ;
             $inputsTextNames=array("DragID","target","Etype","ECode","DataName","Val","remark");
		  
		
		     //分類資料
			 global $ListType;
			 global $className,$class;
			 $className= explode("=", $ResTypeSingleData[7]) ;
			 $ListType=array("清單","排程表");
	         for($i=0;$i<count($className);$i++){
				 array_push( $ListType,$className[$i]."[".$i);
	         }
			 SortResData();
			  SwitchListType();
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
?>
<?php //判斷submit
      function checkSubmit(){
		       global $data_library, $ResdataBase ;
			   global $WebSendVal,$URL;
			 //  if($_POST["ListType"]!="清單"   and $_POST["ListType"]!=""){   
			   
			  // }

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
			  $Rect=array("20","40","50","20");
		      DrawSingle(  $ResTypes,0,$Rect);
		      $Rect[1]+=21;
			  //顯示
			  global $ListType;
			  DrawSingle($ListType,2,$Rect);
			  //排序
			   $Rect =array(20,82,20,12);
			   global $SortType;
			   DrawSingle($SortType,3,$Rect,8);
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
		      $valArray=$WebSendVal; 
			  $SubmitName="submit";
			  $BgColor="#aa5555";
			 
			  $Rect =array(20,95,40,12);
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
	 function DrawSingle($data,$Wsort ,$Rect,$fontSize=10){
		      global $URL;
		      global $WebSendVal  ;
			  $SubmitName="submit";
			  $valArray=$WebSendVal;
			  $valArray[1][1]="";
	          for($i=0;$i<count($data);$i++){
				   $BgColor="#222222";
				   $name= $data[$i] ;
				   if( $WebSendVal[$Wsort][1]== $name)$BgColor="#ff2222";
				   $valArray[$Wsort][1]=  $name;
			       sendVal($URL,  $valArray ,$SubmitName,$name,$Rect,$fontSize,$BgColor); 
				   $Rect[0]+=$Rect[2]+2;
			  }
	 }
?>
<?php //List
     function SwitchListType(){
			  global $typeDatabase,$Resdatas;
		      global $ResPregresList;
			  //global $ListStartRect;
              $h=20*count($ResPregresList);
			  $Rect=array("20","110","80","80");
			  if(strpos($_POST["ListType"],"[") != false){ 
			     DrawType();
				 return;
			  }
			  if($_POST["ListType"]=="排程表")   ListCalendar();
			  for($i=0;$i<count($Resdatas);$i++){
				  
	              ListSingle($Resdatas[$i],$Rect);
			      $Rect[1]+=$Rect[3]+2;
			  }
	 }
	 function DrawType(){
	          global   $ResTypeSingleData;
			  global   $ClasstypeSort;//分類編號
              $sort=6;
			  $ClasstypeSortArr=explode("[", $_POST["ListType"]);
			  $ClasstypeSort=$ClasstypeSortArr[1];
			  $classArr=explode("=", $ResTypeSingleData[$sort]);
			  $class=explode("_", $classArr[  $ClasstypeSort]);
			  array_push( $class,"未分類");
			  //拖曳底部
		      DrawTypeDragBase(  $class,20,120,1000,80);
	 }
     function DrawTypeDragObj($typeName,$x,$y,$Typesort){
		      global  $Resdatas;
			 
	          $sortArr= returnSortTypes( $Resdatas,$typeName,$Typesort);
			   if($typeName=="未分類"){
				   $sortArr1=returnSortTypes( $Resdatas,"未分類",$Typesort);
				   $sortArr2=returnSortTypes( $Resdatas,"",$Typesort);
				  $sortArr=addArray( $sortArr1, $sortArr2);
			   }
			  $BgColor="#333333";
			  $fontColor="#ffffff";
		      $w=50;
			  $h=20;
			  $x+=30;
			  $y+=4;
			  for($i=0;$i<count($sortArr);$i++){
			      $id= "gdcode=".$sortArr[$i][3]."=".$sortArr[$i][2]."=".$i;//1.gdcode. 2.
				  DrawRect("","12","#ffffff",array($x-1,$y-1,$w+2,$w+2+$h),"#000000" );
				  DrawIDPic(returnPicPath($sortArr[$i][3]),array($x,$y+$h,$w,$w),$id);
				  JAPI_DrawJavaDragbox(  $sortArr[$i][3] ,$x,$y,$w,$h,8, $BgColor,$fontColor,$id);
				  DrawRect($sortArr[$i][4] ,"8","#ffffff",array($x,$y+$h-10,$w,10),"#222222" );
				  $x+=$w+1;
			  }
	 }
	 //列印底部
	 function DrawTypeDragBase($types,$x,$y,$w,$h){
		      global $ColorCode;
			  global $ClasstypeSort;//分類編號
			  $fontColor="#ffffff";
			  $By=$y;
			  $Typesort=$ClasstypeSort;
	          for($i=0;$i<count($types);$i++){
				  $id="tableName=classification=".$types[$i]."=".$ClasstypeSort;
				  $BgColor=$ColorCode[12][$i];
				    if($types[$i]=="未分類") $BgColor="#888888";
				  JAPI_DrawJavaDragArea($types[$i],$x,$y,$w,$h,$BgColor,$fontColor,$id,"12" );
				  $y+=$h+2;
			  }
			   for($i=0;$i<count($types);$i++){
				    //拖曳物件
			      DrawTypeDragObj($types[$i],$x,$By,$Typesort);
				  $By+=$h+2;
			   }
		       //DrawTypeDragObj("",$x,$By,$Typesort);
	 }
	 //取得該type的resdata
	 function returnSortTypes( $Resdatas,$typeName,$Typesort){
		      $arr=array();
	          for($i=0;$i<count( $Resdatas);$i++){
			      $t=explode("=",$Resdatas[$i][14]);//14為type欄位
				  if(  $t[$Typesort]==$typeName)array_push($arr,$Resdatas[$i]);
			  }
			  return $arr;
	 }
	 function ListSingle($data,$Rect){
		 		  global $URL;
	              global $WebSendVal  ;
			      global  $webPath,$ResPath;
				  $ERect=$Rect;
	 			  //編號
				  $BgColor="#222222";
				  $name= $data[3];
			      $SubmitName="submit";
				  $ValArray=$WebSendVal;
				 
				 // $type=$WebSendVal[0][1];
				  if($_POST["ListType"]=="清單"){
			      //  if( $WebSendVal[1][1]== $name)$BgColor="#ff2222";
				      array_push(  $ValArray,array("EditRes",$name));
		              // $ValArray[1][1]=  $name;
			           sendVal($URL,  $ValArray ,$SubmitName,$name,$Rect,10,$BgColor); 
				  }else{
				        DrawRect($data[3],10,"#ffffff",$Rect,"#222222" );
				  }
				  //名稱
				  $nRect=array($Rect[0]+1,$Rect[1]+$Rect[3]-20,$Rect[2]-2,18);
				  DrawRect($data[4],10,"#000000",$nRect,"#eeeeee" );
			      //縮圖
				  $Rect[0]+=$Rect[2]+2;
				  $Rect[2]=$Rect[3];
				  DrawPic( returnPicPath($name ),$Rect );// $noPic
				  if( $_POST["EditRes"]== $name and $_POST["EditRes"]!="") UpSingle($data,$ERect);
				  if($_POST["ListType"]!="排程表")return;
				  //可拖曳工作分類
                  SchedlueList($data ,$Rect);
				  //如果是編輯
				
	 } 
	 function returnPicPath($GdCode ){
		       global $WebSendVal ;
			   global $noPic;
	           global  $webPath,$ResPath;
			   $type=$WebSendVal[0][1];
			   $resdir="\\".$type."\\spic\\".$GdCode.".png";
			   $pic=$webPath.$resdir;
			   $path=$ResPath.$resdir;
			   if (is_readable($path) != false)   return $pic ;
			   return $noPic;
			   
	 }
	 function SchedlueList($data ,$Rect){ //拖曳區
	           global $CalendarRect;
   		       global $ResPregresList;
			   global $startDate;
		       global $ColorCode;
		       $fontColor="#ffffff";
			   $Rect[0]+=$Rect[2];
			   $Rect[3]=$Rect[3]/4-1;
			   $Rect[2]=40;
			   $startDay=explode("=",$data[7]);
	           $workingDays=explode("=",$data[8]);
			   $principal=explode("=",$data[9]);
			   $outsourcing=explode("=",$data[10]);
			   $state=explode("=",$data[11]);
			   $jila=explode("=",$data[12]);
			   //7-s w-8 p-9 out-10 state=11
			   for($i=0;$i<count($ResPregresList);$i++){
				   $BgColor=ColorCode[11][$i];
				   $id= "gdcode=".$data[3]."=".$data[2]."=".$i;
				   $Eid= "Egdcode=".$data[3]."=".$data[2]."=".$i."=".$startDay[$i];
				   $msg=$ResPregresList[$i];
				   
				   $x=$Rect[0];
                   $y=$Rect[1]+$i*($Rect[3]+1);
				   $w=120;
				   $h=$Rect[3];
				   //未排定
				   $BgColor=$ColorCode[12][$i];
				//   $BgColor2= PAPI_changeColor( $BgColor,array(1.2,1.2,1.2));
				   if( $startDay[$i]=="")
				       JAPI_DrawJavaDragbox(   $msg ,$x,$y,$w,$h,10,"#222222", "#aaaaaa",$id);
				   //已排定
				   if( $startDay[$i]!=""  ){
					   if(  $principal[$i]!="")$msg=$msg."[".$principal[$i]."]";
					   if(  $outsourcing[$i]!="")$msg=$msg."[".$outsourcing[$i]."]";
					   if($state[$i]=="已完成"){
					       JAPI_DrawJavaDragbox( $msg ,$x,$y,$w,$h,10,"#888888", "#cccccc",$id);
					    }
					   if($state[$i]!="已完成"){
					    $BgColor2= PAPI_changeColor( $BgColor,array(1.2,1.2,1.2));
					    $wd= $workingDays[$i];
						if($wd=="")$wd=1;
					    $workWid=$CalendarRect[2]*$wd;
						$fontColor="#eeeeee";
					    $x2= $CalendarRect[0]+ (CAPI_returnLocX($startDay[$i],$startDate )-1)*$CalendarRect[2];
					    DrawRect("",1,$fontColor,array($x,$y+5,$x2-$x,2),$BgColor);
					    DrawRect($msg,10,$fontColor,array($x,$y,$w,$h),$BgColor );
						//主
                        $BgColorm=  ProAPI_ReturnStateColor(  $BgColor2,$state[$i]);
				        JAPI_DrawJavaDragbox(  $state[$i]."[".$wd."]",$x2,$y+1,$workWid,$h-2,10, $BgColorm,$fontColor,$id);
						//時間控制
						 $BgColorE= "#777777";// PAPI_changeColor( $BgColorm,array(0.8,0.8,0.8));
						JAPI_DrawJavaDragbox( "",$x2+$workWid,$y+1,$CalendarRect[2] ,$h-2,10, $BgColorE,$fontColor, $Eid);
						 }
				   }
			   }
			 
	 }
	 function UpSingle($data,$Rect){
		 echo "up";
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
			  $BGRect[2]=$Rect[2]*3;
              $inputVal=array(); 
			  //基底
		      DrawRect($msg,$fontSize,$fontColor,$BGRect,"#442222" );
			  $name=array("text","name" ,$data[3]."修改名字","10", $Rect[0],$Rect[1],$Rect[2],$Rect[3], "#aaaaaa", "#ffffff", $data[4],14);
			  //類別
			  $file=array("file","pic" ,"pic","10",  $Rect[0] ,$Rect[1]+40,$Rect[2],$Rect[3], "#fffff", "ffffff", "1",10);
			  $submit=array("submit","submit" ,"s","10",  $Rect[0]+120  ,$Rect[1]+10 ,$Rect[2],$Rect[3], "#ffffff", "#fffff", "變更",20);
			  array_push($inputVal,$name);
			  array_push($inputVal,$file);
			  array_push($inputVal,$submit);
			  upSubmitform($upFormVal,$UpHidenVal, $inputVal);
	 }
 
?>
<?php //ListCalendar
      function ListCalendar(){
		       global $ResPregresList;
		       global $Resdatas;
			   global $CalendarRect;
			   global $startDate;
 
			   $DateRange=6;
			   $h= count($Resdatas)* count($Resdatas)*10;
	           CAPI_DrawBaseCalendar($startDate,$DateRange,$CalendarRect[0],$CalendarRect[1],$CalendarRect[2],$h);
               $Rect=array(300,70,40,10);
			   ProAPI_DrawWorkersAreas($Rect);
	  }
?>
<?php //判斷submit;
      function setJavaForm(){
		       global $URL;
			   global $ResdataBase,$typeDatabase;
			   global $inputsTextNames ;
			   global $WebSendVal;
	           JAPI_CreatJavaForm( $URL, $ResdataBase,$inputsTextNames,$WebSendVal );
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
				  echo $filePath;
				  move_uploaded_file($_FILES["pic"]["tmp_name"], $filePath);
				  $cmd="convert     $filePath    -flatten  -resize 256  $filePaths";
			      exec($cmd);
			   }
			   //修改資料
			     $WHEREtable=array("EData","gdcode");
				 $WHEREData=array("data",$_POST["gdcode"]);
			     MAPI_AutoEditMsQLData($data_library, $ResdataBase,$WHEREtable,$WHEREData );
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
				for($i=0;$i<$count;$i++){
				    $s=$arr[$i];
					if($ResSort==$i)$s=$state;
					$str=$str.$s."=";
				}
				return $str;
	  }
      function  upScedule(){
			    global $data_library,$ResdataBase;
			    global $ResPregresList;
				global $Resdatas;
				global $WebSendVal,$URL;
				global $ListType;
				$datas=explode("=",$_POST["DragID"]);
				$data2=explode("=",$_POST["target"]);
				$gdcode=$datas[1];
				$Type=$datas[2];
				$ResSort=$datas[3];
		
				$tableNames=returnTables($data_library ,$ResdataBase);
		        $WHEREtable=array( "gdcode", "Type");
		        $WHEREData=array( $gdcode,$Type  );
				//目前的資源資料
				$curentData=filterArray($Resdatas,3,$gdcode);
				//設定項目時間
				if($datas[0]=="gdcode"){
					if($data2[0]=="startDay"){
					   $Base=array("startDay");
					   $str=returnState($curentData[0][7],$data2[1],$ResSort,count($ResPregresList));
					   $up=array($str);
					}
			     	if($data2[0]=="tableName"){
					   //如果是類別
					   if($data2[1]=="classification")  $ResSort=explode("[",$_POST["ListType"])[1];
			     	 
				      // echo $ResSort;
					   $tableName=$data2[1];
					   $Base=array($tableName);
					   $val=$data2[2];
                        if($val=="--")$val="";
					   $sort= MAPI_returnTableSort($tableNames, $tableName);
					   $count=count($ResPregresList);
					   if(  $tableName="classification")  $count=count($ListType)-2;//如果是類別
					   $str=returnState($curentData[0][$sort],$val,$ResSort,$count );
					   $up=array($str);
					}
		 
				}
				if($datas[0]=="Egdcode"){
				    $Base=array("workingDays");
			        $arr=explode("=",$curentData[0][7]);
					//echo $_POST["DragID"];
					//echo $ResSort.";";
				    $e=$datas[4];
					//$s= $arr[$ResSort] ;   // returnState($curentData[0][7],$data2[1],$ResSort,count($ResPregresList));
					$s= $data2[1];
					$days=CAPI_GetPassDays($s,$e);
					//echo $s.">".$e.">".$days;
					$ResSort=$datas[3];
				    $str=returnState($curentData[0][8],$days,$ResSort,count($ResPregresList));
				    $up=array($str);
		 
				}
			   $stmt=MAPI_MakeUpdateStmt($ResdataBase,$Base,$up,$WHEREtable,$WHEREData);
			   // echo $stmt;
			   SendCommand($stmt,$data_library);		
			   JAPI_ReLoad($WebSendVal,$URL);
	  }
	
?>