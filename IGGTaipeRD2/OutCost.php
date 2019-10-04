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
			 $OutCostst=filterArray($MainPlanDataT,3,"FP");
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
			 
			 getPregressLastState();
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
			 DrawLinkRect("總表",10,"#eeeeee",$x,$y,$w,$h,"#000000",$Link,$border);
			 $x+=$w+2;
			 
			 $Link= $BaseURL."?SortType=".$SortType."&ListType=prepress";
			 DrawLinkRect("請款進程",10,"#eeeeee",$x,$y,$w,$h,"#000000",$Link,$border);
			 
			 $x+=$w+2;
			 $Link= $BackURL."&ListType=AddOuts";
			 $w=30;
			 DrawLinkRect("+",10,"#ffffff",$x,$y,$w,$h,"#992222",$Link,$border);
			  
	}


?>
<?php //處理表格類別
      function filterListType(){
             global $ListType;
			   global $submit;
		     if($submit!="")return;
	         if($ListType==""){
				 DrawContacts();
                 DrawTitle();
				 return;
			 }
		     if($ListType=="prepress"){
			 ListPregress();
			 }
			 if($ListType=="prepressUpdate"){
			 PregressUpdate();
			 }
             if($ListType=="AddOuts"){
				 CreatNewOuts();
			 }
	}
      function filterSubmit(){
		 
			  global $submit;
	          global $ListNames,$ListSize,$OutCosts;
			       echo $submit;
			  if($submit=="")return;
			  if($submit=="搜尋") filterContacts();
			  if($submit=="新增外包表單")AddNewMysqlData();
	
	}
?>
<?php //列印請款進程
      function ListPregress(){
		  	   global $ListNames,$ListSize,$OutCosts;
		       global $pregress,$PreList,$PreListSize;
	           $costList=array(1,5,8);
			   $pregressList=array(3,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28);
			   $ListSn=array();
			   $x=20;
			   $y=60;
			   $h=20;
               Drawfiled($ListNames[0],$ListSize[0],$x,$y,$h,$costList,"#ffffff","#000000","");
	   		   for($i=0;$i<count($OutCosts);$i++){
				   $y+=22;
				   Drawfiled($OutCosts[$i],$ListSize[0],$x,$y,$h, $costList,"#000000","#eeeeee",""); 
				   array_push( $ListSn,$OutCosts[$i][1]);
			   }
 
			   for($i=0;$i<count($costList);$i++)$x+=$ListSize[0][$costList[$i]];
			   $y=60;  
			   $x+=6;
			   Drawfiled($PreList[0],$PreListSize[0],$x,$y,$h,$pregressList,"#ffffff","#000000","");
			   for($i=0;$i<count($ListSn);$i++){
				   $data= returnArraySingel( $pregress,1,$ListSn[$i]);  
				   $y+=22;
				   Drawfiled($data,$PreListSize[0],$x,$y,$h, $pregressList,"#222222", "",$ListSn[$i]); 
			   }
	  }
	  function returnArraySingel($baseArray,$ArrayNum,$matchString){
		       for($i=0;$i<count($baseArray);$i++){
			       if($baseArray[$i][$ArrayNum]==$matchString)return $baseArray[$i];
			   }
	  }
	  function Drawfiled($BaseData,$ListSize,$x,$y,$h, $showField,$fontColor,$bgColor,$sort){
		       global $BaseURL,$BackURL;
			   global $ListType;
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
						$Link=$BaseURL."?ListType=prepressUpdate&sn=".$sort."&Column=".$n."&info=".$msg;
						$Rect=array($x,$y,$w,$h);
						DrawLinkRect_Layer($msg,10,$fontColor,$Rect,$bgc,$Link,$border,0);
						   //  DrawRect($msg,10,$fontColor,$x,$y,$w,$h, $bgc);
				    } 
				   
				   $x+=$w+2;
			   }
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
				// echo "X";
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
?>
<?php //列印總表資料
     function DrawContacts(){
	          global $contacts ;
			  global $BaseURL;
			  global $selectName;
			  $x=20;
			  $y=20;
			  $w=300;
			  $h=20;
			  $BgColor="#ffcccc";
			  echo   "<form id='ChangeOut'  name='Show' action='".$BaseURL."' method='post'>";
		      $input=	MakeSelectionV2($contacts,$selectName,"selectName",10);
			  DrawInputRect("顯示外包",10,"#222222",$x,$y,$w,$h,$BgColor,$WorldAlign,$input);
			  $x+=$w+2;
			  $w=100;
			//  $Outinput="<input type=text name=Outinput value='".$Outinput."'  size=10 >";
			 // DrawInputRect("",10,"#222222",$x,$y,$w,$h,$BgColor,$WorldAlign,$Outinput);
			//  $x+=$w+2;
			  $submitP="<input type=submit name=submit value=搜尋 style= font-size:10px; >";
			 
	          DrawInputRect("",8 ,"#ffffff",$x,$y,$w,$h, $colorCodes[4][2],"top",$submitP);
			  echo "</form>";
	 }
     function DrawTitle(){
		      global $ListNames,$ListSize,$OutCosts;
	          $x=20;
			  $y=60;
			  $h=20;
			  for($i=1;$i<count($ListNames[0]);$i++){
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
			  $x=20;
			  $h=20;
		      for($i=1;$i<(count($Data)-2);$i++){
				  $w= $ListSize[0][$i];
			      $msg=  $Data[$i];
				  if($w!=""){
			         DrawRect($msg,10,"#000000",$x,$y,$w,$h,"#DDDDDD");
					 $x+=$w+2;
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
     function DrawLinesField($Data,$y,$showField){
		      global  $ListSize;
			  $x=20;
			  $h=20;
		      for($i=1;$i<count($Data);$i++){
				  $w= $ListSize[0][$i];
				  if($w!=""){
			         DrawRect($Data[$i],10,"#000000",$x,$y,$w,$h,"#DDDDDD");
					 $x+=$w+2;
				  }
			  }
	 }
 
?>

<?php //上傳
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
			 echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";
	 }
     function AddNewMysqlData(){
	          global  $data_library,$tableName,$OutCosts;
			  global  $BaseURL;
			  global  $selectOut;
			  		  $p=$tableName;
				      $tables=returnTables($data_library,$p);
					  //外包基礎資料
					  echo $selectOut;
	                  $t= count( $tables);
				      $c= explode("_",$selectOut);
					  $outs=$c[1];
					  $con=$c[2];
				     
					  if(count($c)==2) $con=$c[1];
					  	  //國家
					  $cou= SearchArray($OutCosts,7,$con,6);
					  
					  $WHEREtable=array();
				      $WHEREData=array();
					  
					  
		              for($i=0;$i<$t;$i++){
	       	               global $$tables[$i];
						   if($tables[$i]=="outsourcing")$$tables[$i]=$outs;
						   if($tables[$i]=="contact")$$tables[$i]= $con;
						   if($tables[$i]=="country")$$tables[$i]=$cou;
				           array_push($WHEREtable, $tables[$i] );
					       array_push($WHEREData,$$tables[$i]);
		              }
					  $stmt=   MakeNewStmtv2($tableName,$WHEREtable,$WHEREData);
					 //echo $stmt;
				      SendCommand($stmt,$data_library);
			           echo " <script language='JavaScript'>window.location.replace('".$BaseURL."')</script>";
				 
	 }
    
	 
?>
<?php //上傳前表單
function CreatNewOuts(){
		      global  $BaseURL,$BackURL;
			  global  $OutsLastSort,$contacts;
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
			  $w=300;
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
              DrawInputRect("",8 ,"#ffffff",$x+($w+200),$y-30,$w,$h, $colorCodes[4][2],"top",$submitP);
			  //外包
			  $input=MakeSelectionV2($contacts,$selectOut,"selectOut",10);
			  DrawInputRect("選擇外包_",10,"#ffffff",$x,$y,$w,$h,"",$WorldAlign,$input);
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
?>
