<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>外包發費表</title>
</head>
 
<body bgcolor="#b5c4b1">
<?php  //主控台
    $id=$_COOKIE['IGG_id'];
    include('PubApi.php');
    include('mysqlApi.php');
    defineData();
	sortcontact();
	filterSubmit();
    DrawButtons();
    filterListType();
?>

<?php //初始資料
    function defineData(){
			 //返回資料
			 global $BaseURL,$BackURL,$SortType,$ListType;
			 $BaseURL="OutCost.php";
             $BackURL=$BaseURL."?SortType=".$SortType."&ListType=".$ListType;
			 //表單資料
		     global $ListNames,$ListSize,$OutCosts,$OutsLastSort;
			 global $data_library,$tableName,$pregressData;
	         $tableName="fpoutsourcingcost";
			 $data_library="iggtaiperd2"; 
			 $MainPlanDataT=getMysqlDataArray($tableName); 
			 $ListNames=filterArray($MainPlanDataT,0,"title");
			 $ListSize=filterArray($MainPlanDataT,0,"size");
			 $OutCostst=filterArray($MainPlanDataT,0,"cost");
			 
			 $OutsLastSort= getLastSN2($OutCostst,1);
			 //排序
			 $forward="true";
			 if($SortType=="Reverse") $forward="false";
		     $OutCosts= sortArrays($OutCostst ,1,$forward) ;
			 
             //請款進程資料
			 global $pregress,$PreList,$PreListSize;
			 $pregressData="fpoutpregress";
			 $pregressT=getMysqlDataArray("fpoutpregress"); 
			 $PreList=filterArray($pregressT,0,"title");
			 $PreListSize=filterArray($pregressT,0,"size");
			 $pregressT2=filterArray($pregressT,0,"pregress");
			 $pregress= sortArrays( $pregressT2 ,1,"true") ;
			 
			 //過濾已付款項目
			 if($ListType!="history")filterDoneOrder();
			 
			 
			 //外包資料
			 global $outsBaseData,$outsBaseSelects;
		     getOutsData();
			 getPregressLastState();
			 
			 //表格資料
			 global $DetailFormName, $FormRect,$FormList,$FormListsize,$FormTitle;
			 $DetailFormName="outsdetail";
			 $formBase=getMysqlDataArray($DetailFormName); 
			 $FormTitle=filterArray($formBase,0,"資料類別");
			 $FormListsizeT=filterArray($formBase,0,"size");
			 $FormListsize=$FormListsizeT[0];
			 $FormList=array(2,3,4,5,6,7,8,9,10 );
			 $FormRect=array(100,120,120,20);
	}
	function sortcontact(){  //整理聯絡人
         	 global $OutCosts;
	         global $contacts ;
			 $contacts=array();
			 for($i=0;$i<count($OutCosts);$i++){
			     $full=trim($OutCosts[$i][7]);
			     $n= explode("(",$OutCosts[$i][5]);
			      if($full!=trim($n[0])){
				     $full=$OutCosts[$i][7]."_".$n[0];
				  }
			     if (!in_array($full,  $contacts)){
					 array_push($contacts,$full);
				 }
			 }
			 //加編號
			 for($i=0;$i<count($contacts );$i++){
			     $contacts[$i]=$i."_".$contacts[$i];
			 }
	}
    function getPregressLastState(){
	         global $pregress,$OutCosts;
			 for($i=0;$i<count($pregress);$i++){
			     $pregress[$i][process]= returnPregress($pregress[$i]);
			 }
			 for($i=0;$i<count($OutCosts);$i++){
				 $sn=$OutCosts[$i][1];
				 //echo $sn;
			     $pdata= returnArraySingel( $pregress,1,$sn);  
		         $OutCosts[$i][process]=$pdata[process];
				// echo $OutCosts[$i][process];
			 }
	}
	function getOutsData(){
		     global $outs, $outsBaseData,$outsBaseSelects;
			 $outsT= getMysqlDataArray("outsourcing"); 
			 $outs=filterArray($outsT,0,"data");
			 $outsBaseData=array();
			 $outsBaseSelects=array();
			 for($i=0;$i<count($outs);$i++){
				 $name=$outs[$i][15];
		    	 if($name!=$outs[$i][2])$name=$name."(".$outs[$i][2].")";
				 if($name!=$outs[$i][16])$name=$name."(".$outs[$i][16].")";
			     $tmp=array($outs[$i][17],$outs[$i][1],$name); //0code 1序號 2名稱
				 $sel= $outs[$i][17]."-".$name;
				 array_push($outsBaseData,$tmp);
				 array_push($outsBaseSelects,$sel);
			 }
	}
	function filterDoneOrder(){
	         global  $pregress,$OutCosts;
			 $tmp=array();
			 for($i=0;$i<count($pregress);$i++){
			     if($pregress[$i][27]!="")array_push($tmp,$pregress[$i][1]);
			 }		
             $tmp2=array();			 
			  for($i=0;$i<count($OutCosts);$i++){
			       if (!in_array($OutCosts[$i][1], $tmp)) {
				    array_push( $tmp2,$OutCosts[$i]);
				   }
			  }
	        $OutCosts=$tmp2;
	}
?>
<?php //選項按鈕
    function DrawButtons(){
	         global $BaseURL,$BackURL,$SortType,$ListType;
			 $x=20;
			 $y=40;
			 $w=20;
			 $h=18;
			 $Link= $BaseURL."?SortType=Forward"."&ListType=".$ListType;
			 DrawLinkRect("▲",10,"#cccccc",$x,$y,$w,$h,"#000000",$Link,$border);
			 $x+=$w+2;
 
		     $Link= $BaseURL."?SortType=Reverse"."&ListType=".$ListType;
			 DrawLinkRect("▼",10,"#cccccc",$x,$y,$w,$h,"#000000",$Link,$border);
			 $x+=$w+2;
			 $w=60;
			 $Link= $BaseURL."?SortType=".$SortType."&ListType=";
			 DrawLinkRect("未播款表單",10,"#eeeeee",$x,$y,$w,$h,"#000000",$Link,$border);
			 $x+=$w+2;
			 $Link= $BaseURL."?SortType=".$SortType."&ListType=prepress";
			 DrawLinkRect("請款進程",10,"#eeeeee",$x,$y,$w,$h,"#000000",$Link,$border);
			 $x+=$w+2;
			 $Link= $BaseURL."?SortType=".$SortType."&ListType=history";
			 DrawLinkRect("歷史總表",10,"#eeeeee",$x,$y,$w,$h,"#000000",$Link,$border);
			 $x+=$w+2;
			 $Link= $BackURL."&ListType=AddOuts";
			 $w=30;
			 DrawLinkRect("+",10,"#ffffff",$x,$y,$w,$h,"#992222",$Link,$border);
			  
	}


?>
<?php //處理表格類別
      function filterListType(){
             global $ListType;
		     global $submit,$Hilight;
			 if($Hilight!=""){
				 MakeHiLight();
			 }
			 if($submit=="上傳表單")return;
			 if($submit=="確定上傳表單")return;
		  //   if($submit!="搜尋" or $submit!="")return;
	         if($ListType==""){
				 DrawContacts();
                 DrawTitle();
				 return;
			 }
			 if($ListType=="history"){
				 DrawContacts();
                 DrawTitle();
				 return;
			 }
		     if($ListType=="prepress"){
				 DrawContacts();
			 ListPregress();
			 }
			 if($ListType=="prepressUpdate"){
			 PregressUpdate();
			 }
			  
             if($ListType=="AddOuts"){
				 CreatNewOuts();
			 }
			 if($ListType=="EditRemark"){
			    EditRemark();
			 }
			 if($ListType=="EditOutsForm"){
			    EditOutsForm();
			 }
			 if($ListType=="inputOutsForm"){
			    EditOutsForm();
			 }
	}
      function filterSubmit(){
			  global $submit;
	          global $ListNames,$ListSize,$OutCosts;
			  if($submit=="")return;
			  if($submit=="搜尋") filterContacts();
			  if($submit=="新增外包表單")AddNewMysqlData();
	          if($submit=="更新註解")RemarkUpdate();
			  if($submit=="上傳圖檔") UpPic();
			  if($submit=="上傳表單") UpformCheck();
		      if($submit=="確定上傳表單") Upform();
	}
?>
<?php //列印請款進程
      function ListPregress(){
		  	   global $ListNames,$ListSize,$OutCosts,$SortType;
		       global $pregress,$PreList,$PreListSize;
			   global $BaseURL,$BackURL;
	           $costList=array(1,5,8);
			   $pregressList=array(3,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28);
			   $ListSn=array();
			   $x=20;
			   $y=60;
			   $h=20;
               Drawfiled($ListNames[0],$ListSize[0],$x,$y,$h,$costList,"#ffffff","#000000","");
	   		   for($i=0;$i<count($OutCosts);$i++){
				   $y+=22;
				   $BgColor="#ffaaaa";
				   $colcolor="#ffffff";
				   if($OutCosts[$i][16]==""){
					   $BgColor="#aaaaaa";
					   $colcolor="#eeeeee";
				   }
				   
				   Drawfiled($OutCosts[$i],$ListSize[0],$x,$y,$h, $costList,"#000000",$colcolor,""); 
				   array_push( $ListSn,$OutCosts[$i][1]);
				   //高亮
				   $Rect=array( 15,$y,5,20);
				   $Link=$BackURL."&Hilight=".$OutCosts[$i][2]."&hi=".$OutCosts[$i][16];
				   DrawLinkRect_Layer("▶",10,$BgColor,$Rect,$BgColor,$Link,$border,0);
				   //第幾包
				   if($OutCosts[$i][13]!="") 
				   DrawRect("第".$OutCosts[$i][13]."包",9,$fontColor,$Rect[0]+60,$Rect[1],30,18,"#eeffcc");
			   }
			   for($i=0;$i<count($costList);$i++)$x+=$ListSize[0][$costList[$i]];
			   $y=60;  
			   $x+=6;
			   Drawfiled($PreList[0],$PreListSize[0],$x,$y,$h,$pregressList,"#ffffff","#000000","");
			   for($i=0;$i<count($ListSn);$i++){
				   $data= returnArraySingel( $pregress,1,$ListSn[$i]);  
				   $y+=22;
				   $nextx=Drawfiled($data,$PreListSize[0],$x,$y,$h, $pregressList,"#222222", "",$ListSn[$i]); 
				   //註解
				   $remstr=$data[28];
				   $bgc="#ffccaa";
				   $Rect=array( $nextx,$y,30,$h);
				   $Link=$BaseURL."?SortType=".$SortType."&ListType=EditRemark&sn=".$ListSn[$i]."&Rx=".$Rect[0]."&Ry=".$Rect[1]."&info=".$remstr;
				   DrawLinkRect_Layer("+註解",10,$fontColor,$Rect,$bgc,$Link,$border,0);
				   //if($data)
			   }
			 
	  }
	  function returnArraySingel($baseArray,$ArrayNum,$matchString){
		       for($i=0;$i<count($baseArray);$i++){
			       if($baseArray[$i][$ArrayNum]==$matchString)return $baseArray[$i];
			   }
	  }
	  function Drawfiled($BaseData,$ListSize,$x,$y,$h, $showField,$fontColor,$bgColor,$sort){
		       global $BaseURL,$BackURL;
			   global $ListType,$SortType;
		       if($bgColor=="")$lastsn=$BaseData[process] ; // $lastsn=returnPregress($BaseData);
			   $bgc=$bgColor;
	           for($i=0;$i<count($showField);$i++){
				   $n=$showField[$i];
			       $w=$ListSize[$n];
				   $msg=$BaseData[$n];
				   if($bgColor!=""){
				     DrawRect($msg,10,$fontColor,$x,$y,$w,$h, $bgc);
				   }
				   
				   if($bgColor==""){
				        $bgc="#cccccc";
				        if($n<=$lastsn)  $bgc="#ccffaa";
						$Link=$BaseURL."?ListType=prepressUpdate&SortType=".$SortType."&sn=".$sort."&Column=".$n."&info=".$msg;
						$Rect=array($x,$y,$w,$h);
						DrawLinkRect_Layer($msg,10,$fontColor,$Rect,$bgc,$Link,$border,0);
						   //  DrawRect($msg,10,$fontColor,$x,$y,$w,$h, $bgc);
				    } 
				   
				   $x+=$w+2;
			   }
			   return $x;
	  }
	  function returnPregress($data){
		   $n=0;
		   for($i=14;$i<28;$i++){
			   if($data[$i]!="")$n=$i;
		   }
		   return $n;
	  }
?>
<?php //過濾
	function filterContacts(){
		     global $outsBaseData,$outsBaseSelects;
			 global $selectName;
			 global $OutCosts;
			 $code=getOutCode($selectName);
			 $OutCosts=filterArraycontain($OutCosts,15,  $code);
	}
	function getOutCode($selectName){
	      global $outsBaseData,$outsBaseSelects;
		  $tmp= explode("-",$selectName); //0code 1序號 2名稱
		  $sn=$tmp[0];
		  for($i=0;$i<count($outsBaseData);$i++){
		      if($outsBaseData[$i][0]==$sn)return $outsBaseData[$i][1];
		  }
	}
?>
<?php //列印總表資料
     function DrawContacts(){
	          global $outsBaseData,$outsBaseSelects;//    global $contacts ;
			  global $BaseURL,$BackURL;
			  global $selectName;
			  $x=20;
			  $y=20;
			  $w=500;
			  $h=20;
			  $BgColor="#ffcccc";
			  echo   "<form id='ChangeOut'  name='Show' action='".$BackURL."' method='post'>";
		      $input=	MakeSelectionV2($outsBaseSelects,$selectName,"selectName",10);
			  DrawInputRect("顯示外包",10,"#222222",$x,$y,$w,$h,$BgColor,$WorldAlign,$input);
			  $x+=$w+2;
			  $w=100;
			  $submitP="<input type=submit name=submit value=搜尋 style= font-size:10px; >";
	          DrawInputRect("",8 ,"#ffffff",$x,$y,$w,$h, $colorCodes[4][2],"top",$submitP);
			  echo "</form>";
	 }
     function DrawTitle(){
		      global $ListNames,$ListSize,$OutCosts;
	          $x=20;
			  $y=60;
			  $h=20;
			  for($i=1;$i<count($ListNames[0]) ;$i++){
				  $w= $ListSize[0][$i];
				  if($w!=""){
			         DrawRect($ListNames[0][$i],10,"#FFFFFF",$x,$y,$w,$h,"#000000");
					 $x+=$w+2;
				  }
			  }
			  for($i=0;$i<count($OutCosts);$i++){
				 DrawLines($OutCosts[$i],($i+4)*22 );
			  }
	 }
	 function DrawLines($Data,$y ){
		      global  $ListSize,$PreList;
			  global  $BaseURL,$SortType;
			  $x=20;
			  $h=20;
			  $BgColor="#DDDDDD";
			  $fontColor="#000000";
		      for($i=1;$i<(count($Data)-3);$i++){
				  $w= $ListSize[0][$i];
			      $msg=  $Data[$i];
				  if($i==1){
				       $Link=$BaseURL."?SortType=".$SortType."&ListType=EditOutsForm&sn=".$msg;
				       DrawLinkRect( $msg,10,$fontColor,$x,$y,$w,$h,$BgColor,$Link,$border);
					   $x+=$w+2;
				  }else{
				    if($w!=""){
			           DrawRect($msg,10,"#000000",$x,$y,$w,$h,"#DDDDDD");
					   $x+=$w+2;
				     }
				  }
			  }
			  $n= $Data[process];
			  $msg=$PreList[0][$n];
			  $BGcolor="#FFDDDD";
              if($msg=="付款日")  {
				  $BGcolor="#DDFFDD";
			      $msg="完成付款";
			  }
			
			  DrawRect($msg,10,"#000000",$x,$y,$w,$h, $BGcolor);
	 }

?>
<?php //上傳
     function RemarkUpdate(){
	       global $sn,$Column,$info;
		   global $data_library, $pregressData;
		   global $BaseURL,$Remark;
		   $WHEREtable=array( "data_type", "sn" );
		   $WHEREData=array( "pregress",$sn);
		   $Base=array("Remark");
		   $up=array($Remark);
		   $Link=$BaseURL."?SortType=".$SortType."&ListType=prepress";
		   $stmt= MakeUpdateStmt(  $data_library,$pregressData,$Base,$up,$WHEREtable,$WHEREData);
			      echo $stmt;
			  SendCommand($stmt,$data_library);
		   echo " <script language='JavaScript'>window.location.replace('".$Link."')</script>";
	 }
     function PregressUpdate(){
	         global $sn,$Column,$info;
			 global $data_library, $pregressData;
			 global $BaseURL;
			 $BackURL=$BaseURL."?ListType=prepress";
			 if($info==""){
		        $Ndate=date("Y/m/d") ;
			 }else{
			    $Ndate="";
			 }
			 $tables= returnTables($data_library , $pregressData);
			 echo $tables[$Column];
	         $WHEREtable=array( "data_type", "sn" );
		     $WHEREData=array( "pregress",$sn);
			 $Base=array( $tables[$Column]);
			 $up=array($Ndate);
			 $stmt= MakeUpdateStmt(  $data_library,$pregressData,$Base,$up,$WHEREtable,$WHEREData);
			      echo $stmt;
				  SendCommand($stmt,$data_library);
				     global $BaseURL,$BackURL,$SortType,$ListType;
					   $Link=$BaseURL."?SortType=".$SortType."&ListType=prepress";
			  echo " <script language='JavaScript'>window.location.replace('".$Link."')</script>";
	 }
     function AddNewMysqlData(){
	          global  $data_library,$tableName,$OutCosts;
			  global  $BaseURL;
			  global  $selectOut;
			  global  $outsBaseData,$outsBaseSelects,$outs;
			  		  $p=$tableName;
				      $tables=returnTables($data_library,$p);
					  $t= count( $tables);
					  //外包基礎資料
					  $code= getOutCode($selectOut);
					  $OutData=filterArray($outs,1, $code); 
					  $OutData[0][2];  
					 //國家
					  $WHEREtable=array();
				      $WHEREData=array();
		              for($i=0;$i<$t;$i++){
	       	               global $$tables[$i];
						   if($tables[$i]=="outsourcing")$$tables[$i]=$OutData[0][15];
						   if($tables[$i]=="contact")$$tables[$i]=$OutData[0][16];
						   if($tables[$i]=="country")$$tables[$i]=$OutData[0][4];
						   if($tables[$i]=="outcode")$$tables[$i]=$OutData[0][1];
						   if($tables[$i]=="state")$$tables[$i]=date("Y/m/d");
				           array_push($WHEREtable, $tables[$i] );
					       array_push($WHEREData,$$tables[$i]);
		              }
					  $stmt=   MakeNewStmtv2($tableName,$WHEREtable,$WHEREData);
					  echo $stmt;
				      SendCommand($stmt,$data_library);
					  //新增進度表
					  $pregressData="fpoutpregress";
					  $tables=returnTables($data_library,  $pregressData);
					  $WHEREtable=array();
				      $WHEREData=array();
					  for($i=0;$i<count( $tables);$i++){
						   $inside="";
						   if($tables[$i]=="data_type")$inside="pregress";
						   if($tables[$i]=="sn")$inside=$sn;
						   if($tables[$i]=="code")$inside=$sn;
						   array_push($WHEREtable, $tables[$i] );
					       array_push($WHEREData,$inside);
					  }
					  $stmt=   MakeNewStmtv2($pregressData,$WHEREtable,$WHEREData);
					  echo $stmt;
				      SendCommand($stmt,$data_library);
					   global $BaseURL,$BackURL,$SortType,$ListType;
					   $BackURL=$BaseURL."?SortType=".$SortType."&ListType=".$ListType;
			           echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";
	 }
     function UpPic(){
	          global $sn,$picNum;
			  echo $sn.";".$picNum;
			  for($i=0;$i<$picNum;$i++){
				  $fn="pic".$i;
				  $dir="Outsourcing/SortPic/".$sn;
				   if (!is_dir($dir) ) mkdir($dir, 0700);
				  if($_FILES[$fn]["name"]!=null){
				     $temp = explode(".", $_FILES[$fn]["name"]);
				     $path=$dir."/pic".$i.".".$temp[1];
					 $Npath=$dir."/spic".$i.".jpg";
					//echo $path;
					 move_uploaded_file($_FILES[$fn]["tmp_name"], $path);  
				     $cmd="convert       $path   -flatten -resize 256  $Npath ";
					 exec($cmd);
				  }
			  }
	 }
	 function UpForm(){
		      global  $sn,$datas;
			  global  $data_library,$tableName,$OutCosts,$DetailFormName;
			  global  $BaseURL;
		      require_once 'uty/xls2mysqlApi.php';
		      $baseT=getMysqlDataArray("outsdetail"); 
			  $baseT2=filterArray($baseT,0,"outs");
			  $base=filterArray($baseT2,1,$sn);
			  $tableName=$DetailFormName;
			  $tables=returnTables($data_library,$tableName);
			 
	          //清除
			  for($i=0;$i<count($base);$i++){
				 $WHEREtable=array("OutsSn","sn");
				 $WHEREData=array($sn,$base[$i][2]);
				 $stmt= MakeDeleteStmt($tableName,$WHEREtable,$WHEREData);
				  echo $stmt;
				   SendCommand($stmt,$data_library);
			  }
			 
 		      $WHEREtable= returnData($tables);
			  $data=getTxtArray();
			  $datas=filterArray( $data,0,"outs");
			  for($i=0;$i<count($datas);$i++){
				  $WHEREData=returnData($datas[$i]); 
				  $stmt=  MakeNewStmtv2($tableName,$WHEREtable,$WHEREData);
				 SendCommand($stmt,$data_library);
			      echo $stmt;
			  }
			  $Link=$BackURL."?ListType=EditOutsForm&sn=".$sn;
		      echo " <script language='JavaScript'>window.location.replace('".$Link."')</script>";
	 }
	 function returnData($data){
	          $t=array();
			  for($i=0;$i<count( $data);$i++){
			      array_push($t,$data[$i]);
			  }
			  return $t;
	 }
	 function MakeHiLight(){
		  global  $data_library,$tableName,$OutCosts,$DetailFormName;
	     	  global $Hilight,$hi;
	          $WHEREtable=array( "data_type", "sn" );
		      $WHEREData=array( "cost",$Hilight);
			  $h="1";
			  if($hi!="")$h="";
			  $Base=array("hiLight");
			  $up=array($h);
			  $stmt= MakeUpdateStmt(  $data_library, $tableName,$Base,$up,$WHEREtable,$WHEREData);
			      echo $stmt;
				  SendCommand($stmt,$data_library);
				  global $BaseURL,$BackURL,$SortType,$ListType;
				  $Link=$BaseURL."?SortType=".$SortType."&ListType=".$ListType;
			      echo " <script language='JavaScript'>window.location.replace('".$Link."')</script>";
	 }
?>
<?php //上傳前表單
      function  CreatNewOuts(){
		      global  $BaseURL,$BackURL;
			  global $outsBaseData;
			  global  $OutsLastSort,$contacts;
			   global $outsBaseData,$outsBaseSelects;
		      $ex=100;
			  $ey=100;
			  $w=600;
			  $h=400;
			  $sn=$OutsLastSort+1;
			  $title="新增外包表單 編號[".$sn."]";
	          DrawPopBG($ex,$ey,$w,$h,$title ,"12",$BaseURL);
		      echo   "<form id='ChangeOut'  name='Show' action='".$BaseURL."' method='post'>";
			  $x=$ex;
			  $y=$ey+30;
			  $w=500;
			  $h=20;
			  //隱藏
			  $project="FP";
			  $department="台北二部";
			  echo "<input type=hidden name=data_type value='cost'   >";
			  echo "<input type=hidden name=sn value='".$sn."'   >";
			  echo "<input type=hidden name=code value='".$sn."'  >";
			  echo "<input type=hidden name=project value='". $project."'   >";
			  echo "<input type=hidden name=department value='". $department."'   >";
		 
			  //送出
			  $submitP="<input type=submit name=submit value=新增外包表單 style= font-size:10px; >";
              DrawInputRect("",8 ,"#ffffff",$x+($w),$y-30,$w,$h, $colorCodes[4][2],"top",$submitP);
			  //外包
			  $input=MakeSelectionV2($outsBaseSelects,$selectOut,"selectOut",10);
			  DrawInputRect("選擇外包_",10,"#ffffff",$x,$y,$w,$h,"",$WorldAlign,$input);
			  //第幾包
			  $input="<input type=text name=count value='".$OutsCount." 'size=20  style= font-size:10px; >";
			  DrawInputRect_size("第幾包_",10,"#ffffff",$x+$w-20 ,$y,150,20,$BgColor,$WorldAlign,$input);
  
			  //內容
			  $y+=30;
			  $w=600;
			  $input="<input type=text name=content value='".$content."'size=80  style= font-size:10px; >";
			  DrawInputRect_size("製作內容_",10,"#ffffff",$x,$y,$w,$h,$BgColor,$WorldAlign,$input);
			  //金額
			  $y+=30;
			  $w=120;
			  $input="<input type=text name=nt value='".$nt."'size=10  style= font-size:10px; >";
			  DrawInputRect_size("台幣_",10,"#ffffff",$x,$y,$w,$h,$BgColor,$WorldAlign,$input);
			  $input="<input type=text name=usdollar value='".$usdollar."'size=10  style= font-size:10px; >";
			  DrawInputRect_size("美金_",10,"#ffffff",$x+$w+20,$y,$w,$h,$BgColor,$WorldAlign,$input);
			  $input="<input type=text name=CNY value='".$CNY."'size=10  style= font-size:10px; >";
			  DrawInputRect_size("人民幣_",10,"#ffffff", $x+$w*2+20,$y,$w,$h,$BgColor,$WorldAlign,$input);
			  echo "</form>";
	 }
	  function  EditRemark(){
		       ListPregress();
	           global  $BaseURL,$BackURL;
			   global  $OutsLastSort,$contacts;
			   global $Rx,$Ry,$sn,$info;
			   $ex= $Rx;
			   $ey=$Ry;
			   $w=200;
			   $h=40;
			  // $sn=$OutsLastSort+1;
			   $title="編輯註解[".$sn."]";
			   $Link=$BaseURL."?SortType=".$SortType."&ListType=prepress";
	           DrawPopBG($ex,$ey-22,$w,$h,$title ,"12",$Link);
			   echo   "<form id='ChangeOut'  name='Show' action='".$BaseURL."' method='post'>";
			    echo "<input type=hidden name=sn value='".$sn."'   >";
			   $submitP="<input type=submit name=submit value=更新註解 style= font-size:10px; >";
			   DrawInputRect("",8 ,"#ffffff",$ex+150 ,$ey+32 ,100,20, $colorCodes[4][2],"top",$submitP);
			   $input="<input type=text name=Remark value='".$info."'size=30  style= font-size:10px; >";
			   DrawInputRect_size("註解",10,"#ffffff",$ex,$ey,200,20,$BgColor,$WorldAlign,$input);
			   echo "</form>";
	  }
	  function  EditOutsForm(){
		       global  $BaseURL,$BackURL;
			   global  $sn;
			   global  $OutCosts;
			   global  $ListType;
			   $currentDataT= filterArray( $OutCosts,1,$sn);
			   $currentData= filterArray( $currentDataT,2,$sn);
			   $ex=100;
			   $ey=100;
			   $w=1200;
			   $h=800;
	           $Link=$BaseURL."?SortType=".$SortType;
			   $c="(第".$currentData[0][14]."包)";
			   $title ="編輯".$currentData[0][1]."-".$currentData[0][5].$c."[".$currentData[0][8]."]製作內容";
	           DrawPopBG($ex,$ey,$w,$h,$title ,"12",$Link);
			    
			    if($ListType=="EditOutsForm")  ExportForms($sn);
		        if($ListType=="inputOutsForm") InputForms($sn);
               
		
	  }
	  function  ExportForms($sn){
		       global $BaseURL;
	           $outsDetialT=getMysqlDataArray("outsdetail"); 
			   $outsDetial= filterArray( $outsDetialT,1,$sn);
			   $ListTitle=filterArray( $outsDetialT,0,"資料類別");
			   $List=array(4,5,6,7,8,9);
			   $rect=array(100,120,120,20);
			   $fontColor="#ffffff";$BGcolor="#000000";
			   Drawsingel($ListTitle[0],$List,$rect,$fontColor,$BGcolor);
			   $rect[1]+=22;
			   $fontColor="#222222";$BGcolor="#cccccc";
			   //列印
			   $Link=$BaseURL."?sn=".$sn."&picNum=".count($outsDetial);
			   echo  "<form method=post enctype=multipart/form-data action=".$Link.">";
			  //細節
			   
	           DrawDetialList($outsDetial ,$fontColor,$BGcolor);
			   $rect[1]+=count($outsDetial)*22;
			   //送出
			   $submitP="<input type=submit name=submit value=上傳圖檔  style= font-size:10px; >";
               DrawInputRect("",8 ,"#ffffff",$rect[0]+750,$rect[1],200,$rect[3], $colorCodes[4][2],"top",$submitP);
			   echo "</form>";
			   //連接修改表單
			   $Link=$BaseURL."?ListType=inputOutsForm&sn=".$sn;
			   DrawLinkRect_Layer("修改表單",10,$fontColor,$rect,"#ffaacc",$Link,$border,$Layer);
			   
			   //匯率
			   $Link2="https://www.baidu.com/s?ie=utf-8&f=8&rsv_bp=1&rsv_idx=1&tn=baidu&wd=%E6%B1%87%E7%8E%87&oq=%25E6%25B1%2587%25E7%258E%2587%25E4%25BA%25BA%25E6%25B0%2591%25E5%25B8%2581%25E5%258F%25B0%25E5%25B8%2581&rsv_pq=fb7d36c4000e11ee&rsv_t=cc6dkVdXoMRFbjGQ46xf0UoT5jFYhDXhUsiL7NjPvkFHLT%2BDehA9LNu%2BEj8&rqlang=cn&rsv_enter=1&rsv_dl=tb&inputT=373&rsv_sug3=19&rsv_sug1=11&rsv_sug7=100&rsv_sug2=0&rsv_sug4=512&rsv_sug=2";
			   DrawLinkRect("匯率運算",10,"#000000",$rect[0]+300,$rect[1],100,20,"#ccffaa",$Link2,$border);
			   //輸出
			   $Link="../../IGGTaipeRD2/Outsourcing/ExportMat.php?Exporttype=mat1&sn=".$sn;
			   $msg="產生 [材料1：项目外包需求申请单.xls]";
			   $fontColor="#ffffff";$BGcolor="#99aa99";
			   $rect[1]+=42;
		       $rect[2]=300;
			   DrawLinkRect_LayerNew($msg,12,$fontColor,$rect,$BGcolor,$Link,$border,$Layer);
			   //mat2
			   $Link="../../IGGTaipeRD2/Outsourcing/ExportMatDoc.php?Exporttype=mat2&sn=".$sn;
			   $msg="產生 [材料2：申请资料.docx]";
			   $rect[1]+=32;
			   DrawLinkRect_LayerNew($msg,12,$fontColor,$rect,$BGcolor,$Link,$border,$Layer);
			   //mat3
			   $Link="../../IGGTaipeRD2/Outsourcing/ExportMat.php?Exporttype=mat3&sn=".$sn;
			   $msg="產生 [材料3：合同报价单.xls]";
			   $rect[1]+=32;
			   DrawLinkRect_LayerNew($msg,12,$fontColor,$rect,$BGcolor,$Link,$border,$Layer);
			   //mat4
			   $Link="../../IGGTaipeRD2/Outsourcing/ExportMatDoc.php?Exporttype=mat4&sn=".$sn;
			   $msg="產生 [材料4：需求描述模板.doc]";
			   $rect[1]+=32;
			   DrawLinkRect_LayerNew($msg,12,$fontColor,$rect,$BGcolor,$Link,$border,$Layer);
			   //報價
			   $Link="../../IGGTaipeRD2/Outsourcing/ExportMat.php?Exporttype=Quote&sn=".$sn;
			   $msg="產生 [報價.xlsx]";
			   $rect[1]+=32;
			   DrawLinkRect_LayerNew($msg,12,$fontColor,$rect,$BGcolor,$Link,$border,$Layer);
			   //報價
			   $Link="../../IGGTaipeRD2/Outsourcing/ExportMatDoc.php?Exporttype=Demand&sn=".$sn;
			   $msg="產生 [需求明细.doc]";
			   $rect[1]+=32;
			   DrawLinkRect_LayerNew($msg,12,$fontColor,$rect,$BGcolor,$Link,$border,$Layer);
	  }
	  function  InputForms($sn){
		  	    global $BaseURL;
				$rect=array(100,120,120,20);
				$Link="https://docs.google.com/spreadsheets/d/1kU1Nq95YIrua0EDWv9wIHaijPLAJX6SNb81685HQKnA/edit#gid=1048566831";
				 DrawLinkRect("xls表單範例",10,"#000000",$rect[0],$rect[1],100,20,"#ccffaa",$Link,$border);
		    	$Link=	$BackURL."?sn=".$sn;
	            echo  "<form method=post enctype=multipart/form-data action=".$Link.">";
			    $input="<textarea name=txt cols=90 rows=12></textarea>";
			    DrawInputRect_size("貼上execl剪貼",12,"#ffffff",$rect[0],$rect[1]+20,500,100,$BGcolor,$WorldAlign,$input);

				
				
				
				$submitP="<input type=submit name=submit value=上傳表單  style= font-size:12px; >";
                DrawInputRect("",8 ,"#ffffff",$rect[0]+670,$rect[1]+300,200,$rect[3], $colorCodes[4][2],"top",$submitP);
				echo "</form>";
		   
	  }
	  function  DrawDetialList($outsDetial,$fontColor,$BGcolor){
		       global $DetailFormName, $FormRect,$FormList,$FormListsize,$FormTitle;
			   $rect=$FormRect;
			   Drawsingel($FormTitle[0],$FormList, $rect,"#ffffff","#000000");
			   $rect[1]+=22;
			   Drawsingel($outsDetial[$i],$FormList, $rect,$fontColor,$BGcolor);
	  		   for($i=0;$i<count($outsDetial);$i++){
			        Drawsingel($outsDetial[$i],$FormList, $rect,$fontColor,$BGcolor);
				    $rect[1]+=22;
			   }
	  }
	  function  Drawsingel($data,$List,$rect,$fontColor,$BGcolor){
		    global $DetailFormName, $FormRect,$FormList,$FormListsize;
		    for($i=0;$i<count($List);$i++){
				  $w=$FormListsize[$List[$i]];
			      DrawRect($data[$List[$i]],10,$fontColor,$rect[0],$rect[1],$w,$rect[3],$BGcolor);
				  $rect[0]+=$w+2;
			   }
			   $pic="Outsourcing/SortPic/".$sn."/spic".$i.".jpg" ;
	           DrawPosPic($pic,$rect[1],$rect[0],20,20,"absolute" );
			   $rect[0]+=22;
               $input= "<input type=file name=pic".$i." style= font-size:10px;>";
			   DrawInputRect_size("效果图例",10,"#ffffff",$rect[0] ,$rect[1],300,$rect[3],$BGcolor,$WorldAlign,$input);
	  }
	  function  UpformCheck(){
	     	  global $sn;
			  global $DetailFormName, $FormRect,$FormList,$FormListsize;
			  global $BaseURL;
			  global $txt;
		      EditOutsForm();
	          require_once 'uty/xls2mysqlApi.php';
			  $data=getTxtArray();
			  $data_library=$data[0][1];
			  $tableName=$data[0][0];
			  if($tableName!="outsdetail"){
			  echo "上傳格式有錯!";
			  return;
			  }
              $tables=returnTables($data_library ,$tableName);
			  $collect=filterArray( $data,0,"outs");
			  $fontColor="#222222";
			  $BGcolor="#ffffff";
			  echo  "<form method=post enctype=multipart/form-data action=".$BaseURL.">";
			  DrawDetialList($collect,$fontColor,$BGcolor);
			  echo "<input type=hidden name=sn value='".$sn."'   >";
		      echo "<input type=hidden name=txt value='".$txt."'   >";
			  //echo "<input type=hidden name=datas class=mail-contacts  value='".$collect."'   >";
			  $submitP="<input type=submit name=submit value=確定上傳表單  style= font-size:12px; >"; 
			  $rect=$FormRect;
              DrawInputRect("",8 ,"#ffffff",$rect[0]+470,($rect[1]+(count($collect)+1)*22),200,$rect[3], "#ffcccc","top",$submitP);
			  echo "</form>";
	 }	
?>
 
<?php //old
/*
	function filterContacts(){
		     global $contacts ;
			 global $selectName;
			 global $OutCosts;
			 $tmp= explode("_",$selectName);
			 
			 $n=$tmp[0];
			 $tmp2=  $contacts[$n];
			 $tmp3= explode("_",$tmp2);
			 if(count($tmp3)==2){
				 $OutCosts=filterArraycontain($OutCosts,7, $tmp3[1]);
			 }
			 if(count($tmp3)>2){
				 $OutCosts1=filterArraycontain($OutCosts,7,$tmp3[1]);
			     $OutCosts2=filterArraycontain($OutCosts,5,$tmp3[2]);
				 $OutCosts = array_merge($OutCosts1,$OutCosts2) ;
				 $sn=array();
				 $ost=array();
				 for($i=0;$i<count( $OutCosts );$i++){
					 $s= $OutCosts[$i][1] ;
				     if(!in_array($s, $sn) ){
					  array_push($sn,$s);
					  array_push($ost,$OutCosts[$i]); 
					 } 
				 }
				 $OutCosts= $ost; 
			 }
			 
	}
	     function DrawLinesField($Data,$y,$showField){
		      global  $ListSize;
			  $x=20;
			  $h=20;
		      for($i=1;$i<count($Data);$i++){
				  $w= $ListSize[0][$i];
				  if($w!=""){
			         DrawRect( .$Data[$i],10,"#000000",$x,$y,$w,$h,"#DDDDDD");
					 $x+=$w+2;
				  }
			  }
	 }
	*/
?>