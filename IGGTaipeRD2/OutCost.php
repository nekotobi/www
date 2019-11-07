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
    filterUpType();

	sortcontact();
	filterSubmit();
    DrawButtons();
	SearchContacts();
    filterListType();
	DrawFormcostEdit();
?>

<?php //初始資料
    function defineData(){
			 //返回資料
			 global $BaseURL,$BackURL,$SortType,$ListType;
			 $BaseURL="OutCost.php";
             $BackURL=$BaseURL."?SortType=".$SortType."&ListType=".$ListType;
			 //cookie
			 $CookieArray=array('SortType','ListType','sn',"SelectOut","Column","info","Rx","Ry","EditType");
             setcookies($CookieArray, $BaseURL);
			 SetGlobalcookieData($CookieArray);
			  //CheckCookie($CookieArray);
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
			 $FormRect=array(100,140,120,20);
			 
			 global  $editList;//編輯欄位
			   $editList=array(8,9,10,11,13);
			   
			  //過濾
			  global $SelectOut;
			  if($SelectOut!="")
			   $OutCosts=filterArraycontain($OutCosts,15,  $SelectOut);
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
			     $pdata= returnArraySingel( $pregress,1,$sn);  
		         $OutCosts[$i][process]=$pdata[process];
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
	function change(){
	         global  $NT2Us,$Us2NT;
			 $ChangeWeb="https://zt.coinmill.com/";
			 $NT="TWD";
			 $CNY="CNY";
			 $USD="USD";
              /*
			 $NT2US="https://zt.coinmill.com/TWD_USD.html#USD=";
			 $US2NT="https://zt.coinmill.com/TWD_USD.html#USD=";
			 
		     global  $CNY2Us,$Us2CNY;
			 $CNY2US="https://zt.coinmill.com/CNY_USD.html#USD=";
			 $US2CNY="https://zt.coinmill.com/CNY_USD.html#USD=";
			 
			 global  $NT2CNY,$CNY2NT;
			 $NT2CNY="https://zt.coinmill.com/CNY_TWD.html#CNY=";
			 $CNY2NT="https://zt.coinmill.com/CNY_TWD.html#CNY=111";
			 */
			 
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
			 DrawLinkRect2sendVal("▲",10,"#cccccc",$x,$y,$w,$h,"#000000",$Link,$border);
			 $x+=$w+2;
 
		     $Link= $BaseURL."?SortType=Reverse"."&ListType=".$ListType;
			 DrawLinkRect2sendVal("▼",10,"#cccccc",$x,$y,$w,$h,"#000000",$Link,$border);
			 $x+=$w+2;
			 $w=60;
			 $Link= $BaseURL."?SortType=".$SortType."&ListType=Processing";
			 DrawLinkRect2sendVal("處理中表單",10,"#eeeeee",$x,$y,$w,$h,"#000000",$Link,$border);
			 $x+=$w+2;
			 $Link= $BaseURL."?SortType=".$SortType."&ListType=prepress";
			 DrawLinkRect2sendVal("請款進程",10,"#eeeeee",$x,$y,$w,$h,"#000000",$Link,$border);
			 $x+=$w+2;
			 $Link= $BaseURL."?SortType=".$SortType."&ListType=history";
			 DrawLinkRect2sendVal("歷史總表",10,"#eeeeee",$x,$y,$w,$h,"#000000",$Link,$border);
			 $x+=$w+2;
			 $Link= $BackURL."&ListType=AddOuts";
			 $w=30;
			 DrawLinkRect2sendVal("+",10,"#ffffff",$x,$y,$w,$h,"#992222",$Link,$border);
			  
	}
 
?>
<?php //處理表格類別
      function filterUpType(){
		       global $UpType;
		        $UpType=$_POST['UpType'];
				echo $UpType;
	           if( $_POST['UpType']=="prepressUpdate"){
			   PregressUpdate();
			   }
			   if( $_POST['UpType']=="Outfin"){
			      UpOutFin();
			    }
			  // if( $_POST['UpType']=="EditOutsForm") EditOutsForm();
	         //  if( $_POST['UpType']=="inputOutsForm")EditOutsForm();
	  }
      function filterListType(){
            global  $ListType ;
	         $submit= $_POST['submit'];
			 global  $EditType;
		     global  $Hilight;
			 global  $costList,$pregressList;
			 if($Hilight!=""){
				 MakeHiLight();
			 }
			 if($submit=="上傳表單")return;
			 if($submit=="確定上傳表單")return;
		  //   if($submit!="搜尋" or $submit!="")return;
		      
	         if($ListType=="Processing"){
				 $costList=array(1,5,7,8,9,10,11,12,13);
			    //$pregressList=array(3,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28);
				 //DrawContacts();
				 
               //  DrawTitle();
			     ListPregress();
				 return;
			 }
			 if($ListType=="history"){
				// DrawContacts();
                 DrawTitle();
				 return;
			 }
		     if($ListType=="prepress"){
				$costList=array(1,5,8);
			    $pregressList=array(3,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28);
			    ListPregress();
			 }
		 
			  
             if($ListType=="AddOuts"){
				 CreatNewOuts();
			 }
			 if($EditType=="EditRemark"){
			    EditRemark();
				// ListPregress();
			 }
			  if($ListType=="EditOutsForm") EditOutsForm();
	        // if($ListType=="inputOutsForm")  EditOutsForm();
	 
	}
      function filterSubmit(){
		      $submit= $_POST['submit'];
	          global $ListNames,$ListSize,$OutCosts;
		  
			  global $BaseURL;
			//  if($submit=="")return;
              if($submit=="+註解") EditRemark();
			  if($submit=="搜尋") filterContacts();
			  if($submit=="新增外包表單")AddNewMysqlData();
	          if($submit=="更新註解")RemarkUpdate();
			  if($submit=="上傳圖檔") UpPic();
			  if($submit=="上傳表單") UpformCheck();
		      if($submit=="確定上傳表單") Upform();
			 // if($submit=="修改表單") UpEditForm();
			  if($submit=="上傳匯率") UpExchangeRate();
			  if($submit=="取消"){
				$CookieArray=array(array("EditType",""));
				  setcookiesForce($CookieArray,$BackURL);
			  }
	}
?>
<?php //列印請款進程
      function ListPregress(){
		  	   global $ListNames,$ListSize,$OutCosts,$SortType;
		       global $pregress,$PreList,$PreListSize;
			   global $BaseURL,$BackURL;
	           global  $costList,$pregressList;
			 
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
                   DrawHiLight( array( 15,$y,5,20),$BgColor,$OutCosts[$i][2],$OutCosts[$i][16],$OutCosts[$i][13]);
				   DrawEditButton(array( 0,$y+5,10,10),$OutCosts[$i][2],$y);
			   }
			   
			   for($i=0;$i<count($costList);$i++){
			 
				   $x+=$ListSize[0][$costList[$i]];
			   }
			   $y=60;  
			   $x+=6;
			   Drawfiled($PreList[0],$PreListSize[0],$x,$y,$h,$pregressList,"#ffffff","#000000","");
			   if(count($pregressList)==0)return;
			   for($i=0;$i<count($ListSn);$i++){
				   $data= returnArraySingel( $pregress,1,$ListSn[$i]);  
				   $y+=22;
				   $nextx=Drawfiled($data,$PreListSize[0],$x,$y,$h, $pregressList,"#222222", "",$ListSn[$i]); 
				   //註解
				   $remstr=$data[28];
				   $bgc="#ffccaa";
				   $Rect=array( $nextx,$y,30,$h);
				   $Link=$BaseURL."?SortType=".$SortType."&EditType=EditRemark&sn=".$ListSn[$i]."&Rx=".$Rect[0]."&Ry=".$Rect[1]."&info=".$remstr;
				   DrawLinkRect_Layer2sendVal("+註解",10,$fontColor,$Rect,$bgc,$Link,$border,0);
				   
				   //外包申請驗收
				   $OutFin=$data[29];
				
				   $bgc="#aaaaaa";
				   $dir="Outsourcing/AcceptanceData/".$sn;
				   $Rect=array( $nextx+32,$y,70,$h);
				   $msg="(".$ListSn[$i].")申請驗收";
				   if($OutFin!=""){
					 if(strpos($OutFin,'Fin') !== false){
					  $bgc="#eeffee";
					  $msg="(".$ListSn[$i].")已申請";
					 }else{
					  // $pic="pics/folder.png";
					    $bgc="#ffaaaa";
					    $Rect=array( $nextx+102,$y,420,$h);
					    $rootLink="\\\\10.4.1.249\AppServ\www\IGGTaipeRD2\Outsourcing\AcceptanceData\\".$ListSn[$i];
				       //DrawLinkRect_Layer($rootLink,10,$fontColor,$Rect,"#ffeeee", "",$border,0);
					    DrawRect( $rootLink,10,$fontColor,$nextx+102,$y,420,$h,"#ffeeee");
					 }
				   }
				   $Rect=array( $nextx+32,$y,70,$h);
				   $ValArray=array(array("UpType","Outfin"),array("sn",$ListSn[$i]),array("info",$OutFin));
				   sendVal($BaseURL,$ValArray,"submit",$msg,$Rect,10, $bgc,$fontColor);
				   //$Link=$BaseURL."?SortType=".$SortType."&ListType=Outfin&sn=".$ListSn[$i]."&info=".$OutFin;
				  // DrawLinkRect_Layer($msg,10,$fontColor,$Rect,$bgc,$Link,$border,0);
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
		       $fc="#000000";
	           for($i=0;$i<count($showField);$i++){
				   $n=$showField[$i];
			       $w=$ListSize[$n];
				   $msg= $BaseData[$n];
				   if($bgColor!=""){
					  if($i==0){
						 $Link=$BaseURL."?SortType=".$SortType."&ListType=EditOutsForm&sn=".$msg;
						 
				         DrawLinkRect2sendVal( $msg,10,$fontColor,$x,$y,$w,$h,$bgc,$Link,$border);
					  }else{
				     DrawRect($msg,10,$fontColor,$x,$y,$w,$h, $bgc);
					  }
				   }
				   if($bgColor==""){
				        $bgc="#cccccc";
				        if($n<=$lastsn)  $bgc="#ccffaa";
						if($msg=="")$msg="_";
						
						//$Link=$BaseURL."?UpType=prepressUpdate&SortType=".$SortType."&sn=".$sort."&Column=".$n."&info=".$msg;
						$Rect=array($x,$y,$w,$h);
						//DrawLinkRect_Layer2sendVal($msg,10,$fontColor,$Rect,$bgc,$Link,$border,0);
						$ValArray=array(array("UpType","prepressUpdate"),array("sn",$sort),array("Column",$n),array("info",$msg));
						sendVal($BaseURL,$ValArray,"submit",$msg,$Rect,8,$bgc,$fc );
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
	  function DrawHiLight( $Rect,$BgColor,$Hilight,$hi,$num){
		  	   global $BaseURL,$BackURL;
	  			   //高亮
				  // $Rect=array( 15,$y,5,20);
				   $Link=$BackURL."&Hilight=".$Hilight."&hi=".$hi;
				   DrawLinkRect_Layer("▶",10,$BgColor,$Rect,$BgColor,$Link,$border,0);
				   //第幾包
				   if($num!="") //  if($OutCosts[$i][13]!="") 
				   DrawRect("第".$num."包",9,$fontColor,$Rect[0]+50,$Rect[1],35,18,"#eeffcc");
	  }
	  function DrawEditButton($Rect,$sn,$hi){
		       global $ListType;
		       if($ListType=="prepress")return;
		       global $BaseURL,$BackURL;
	           $Link=$BackURL."&Edit=".$sn."&colHi=".$hi;
			   DrawLinkRect_Layer("E",10,"#ffffff",$Rect,"#998888",$Link,$border,0);
	  }
?>
<?php //過濾
	function filterContacts(){
		     global $outsBaseData,$outsBaseSelects;
			  $selectName= $_POST['selectName'];
			  $searchName=$_POST['searchName'];
			// global $selectName,$searchName;
			 global $BaseURL;
			 global $OutCosts;
		 
			 if($searchName!=""){
				  getSearchNameCode($searchName);
				  return;
			 } 
			 $code=getOutCode($selectName);		
			
			 $send=array(array("SelectOut",$code));
			// echo $code;
			 setcookiesForce($send,$BaseURL);
			  $OutCosts=filterArraycontain($OutCosts,15,  $code);
	}
	function getSearchNameCode($searchName){
	     	 global $outsBaseData,$outsBaseSelects;
			 global $OutCosts;
			 $a=array();
	         for($i=0;$i<count($outsBaseSelects);$i++){
			     if(strpos($outsBaseSelects[$i],$searchName) !== false){ 
				    $code= getOutCode($outsBaseSelects[$i]);
					//echo  $code;
				    $Out=filterArraycontain($OutCosts,15,  $code);
				    foreach ($Out as $o) array_push($a, $o);
				 }
			 }
			 $OutCosts=$a;
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
	 function SearchContacts(){
	          global $outsBaseData,$outsBaseSelects;//    global $contacts ;
			  global $BaseURL,$BackURL;
			  global $selectName;
			  $x=20;
			  $y=20;
			  $w=500;
			  $h=20;
			  $BgColor="#ffcccc";
			   echo "<form action=".$BaseURL." method=post >";
			 // echo   "<form id='ChangeOut'  name='Show' action='".$BaseURL."' method='post'>";
		      $input=	MakeSelectionV2($outsBaseSelects,$selectName,"selectName",10);
			  DrawInputRect("顯示外包",10,"#222222",$x,$y,$w,$h,$BgColor,$WorldAlign,$input);
			  
			  $x+=$w+2;
			 
			  $input ="<input type=text name=searchName   style= font-size:10px; >";
			  DrawInputRect("",10,"#222222",$x,$y,$w,$h,$BgColor,$WorldAlign,$input);
			  $x+=102;
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
				       $Link=$BaseURL."?SortType=".$SortType."&UpType=EditOutsForm&sn=".$msg;
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
			  
			  DrawHiLight( $Rect,$BgColor,$Hilight,$hi,"");
			  
		
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
	 
		     $sn=$_POST['sn'];
		  	 $Column=$_POST['Column'];
	 
			 $info=$_POST['info'];
	        // global $sn,$Column,$info;
			 global $data_library, $pregressData;
			 global $BaseURL;
			 $BackURL=$BaseURL."?ListType=prepress";
			 if($info=="_"){
		        $Ndate=date("Y/m/d") ;
			 }else{
			    $Ndate="";
			 }
			 $tables= returnTables($data_library , $pregressData);
			// print_r($tables);
           //  echo count($tables).".".$tables[$Column]."[".$Column."</br>";
	         $WHEREtable=array( "data_type", "sn" );
		     $WHEREData=array( "pregress",$sn);
			 $Base=array( $tables[$Column]);
			 $up=array($Ndate);
			 $stmt= MakeUpdateStmt(  $data_library,$pregressData,$Base,$up,$WHEREtable,$WHEREData);
		   //  echo $stmt;
			 SendCommand($stmt,$data_library);
			 global $BaseURL,$BackURL,$SortType,$ListType;
 
		     echo " <script language='JavaScript'>window.location.replace('".$BaseURL."')</script>";
	 }
	 function UpOutFin(){
		       $sn=  $_POST['sn'];
		       $info=$_POST['info'];
	         // global $sn,$info;
              global $data_library, $pregressData;
			  $WHEREtable=array( "data_type", "sn" );
		      $WHEREData=array( "pregress",$sn);
		      $Ndate=date("Y/m/d") ;
			  $info=trim($info);
			  if($info==""){
			   $Ndate=date("Y/m/d") ;  
			  }
			   if($info!=""){
			     $Ndate="Fin_".date("Y/m/d") ;  
			  }
			   if(strpos($info,'Fin') !== false){
				   $Ndate="";
			   }

		      $dir="Outsourcing/AcceptanceData/".$sn;
		 
			  if (!is_dir($dir) ){
				  mkdir($dir, 0700);
			  }
		      $Base=array(  "OutFin");
			  $up=array($Ndate);
			  $stmt= MakeUpdateStmt(  $data_library,$pregressData,$Base,$up,$WHEREtable,$WHEREData);
			//  echo $stmt;
			  SendCommand($stmt,$data_library);
			  global $BaseURL,$BackURL,$SortType,$ListType;
					 $Link=$BaseURL."?SortType=".$SortType."&ListType=prepress";
			    echo " <script language='JavaScript'>window.location.replace('".$Link."')</script>";
	 }
	 
     function AddNewMysqlData(){
	          global  $data_library,$tableName,$OutCosts;
			  global  $BaseURL;
		      $selectOut=$_POST['selectOut'];
			  $sn=$_POST['sn'];
			
			  global  $outsBaseData,$outsBaseSelects,$outs;
			  		  $p=$tableName;
				      $tables=returnTables($data_library,$p);
					  $t= count( $tables);
					  //外包基礎資料
					  $code= getOutCode($selectOut);
					  $OutData=filterArray($outs,1, $code); 
					 // $OutData[0][2];  
					 //國家
					  $WHEREtable=array();
				      $WHEREData=array();
		              for($i=0;$i<$t;$i++){
	       	            //   global $$tables[$i];
						   $tmp=$_POST[$tables[$i]]; 
						   if($tables[$i]=="outsourcing") $tmp=$OutData[0][15];
						   if($tables[$i]=="contact") $tmp=$OutData[0][16];
						   if($tables[$i]=="country") $tmp=$OutData[0][4];
						   if($tables[$i]=="outcode") $tmp=$OutData[0][1];
						   if($tables[$i]=="state") $tmp=date("Y/m/d");
				           array_push($WHEREtable, $tables[$i] );
					       array_push($WHEREData, $tmp);
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
						   $tab=trim($tables[$i]);
						  // echo $tab;
						   if($tab=='data_type')$inside="pregress";
						   if($tab=='sn'){
							 //    echo ">".$sn.">";
							   $inside=$sn;
						   }
						   if($tab=='code')$inside=$sn;
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
		 
		 global $BackURL;
	          global $sn,$picNum;
			  echo $sn.";".$picNum;
			  for($i=0;$i<=$picNum;$i++){
				  $fn="pic".$i;
				  echo $fn;
				  $dir="Outsourcing/SortPic/".$sn;
				   if (!is_dir($dir) ) mkdir($dir, 0700);
				  if($_FILES[$fn]["name"]!=null){
				     $temp = explode(".", $_FILES[$fn]["name"]);
				     $path=$dir."/pic".$i.".".$temp[1];
					 $Npath=$dir."/spic".$i.".jpg";
					 echo $path;
					 move_uploaded_file($_FILES[$fn]["tmp_name"], $path);  
				     $cmd="convert       $path   -flatten -resize 256  $Npath ";
					 exec($cmd);
				  }
			  }
			    echo " <script language='JavaScript'>window.location.replace('".$BackURL."&sn=".$sn."')</script>";
	 }
	 function UpForm(){
		      global  $sn,$datas;
			  global  $data_library,$tableName,$OutCosts,$DetailFormName;
			  global  $BaseURL;
			  //echo "UpForm";
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
			  global $txt;
			  $txt=$_POST['txt'];
 		      $WHEREtable= returnData($tables);
			  $data=getTxtArray();
			  $datas=filterArray( $data,0,"outs");
			  echo $datas;
			  for($i=0;$i<count($datas);$i++){
				  $WHEREData=returnDatafix($datas[$i],$sn,($i+1)); 
				  $stmt=  MakeNewStmtv2($tableName,$WHEREtable,$WHEREData);
				  SendCommand($stmt,$data_library);
			      echo $stmt;
			  }
			  $Link=$BackURL."?UpType=EditOutsForm&sn=".$sn;
		     echo " <script language='JavaScript'>window.location.replace('".$Link."')</script>";
	 }
	 function returnDatafix($data,$sn,$sort){
	          $t=array();
			  for($i=0;$i<count( $data);$i++){
				  $up=$data[$i];
				  if($i==1)$up=$sn;
				  if($i==2)$up=$sort;
				  if($i==0)$up="outs";
			      array_push($t,$up);
			  }
			  return $t;
	 }
	 function returnData($data){
	          $t=array();
			  for($i=0;$i<count( $data);$i++){
				  $up=$data[$i];
			      array_push($t,$up);
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
	 function UpEditForm(){
		      
	 	      global $editsn ,$Column,$info;
		      global $data_library ;
		      global $BaseURL,$BackURL;
			  global $editList;
		      $WHEREtable=array( "data_type", "sn" );
		      $WHEREData=array( "cost",$editsn );
 
			  $Base=array();
		      $up=array();
			  $tables=returnTables($data_library,"fpoutsourcingcost");
			  for($i=0;$i<count($editList);$i++){
				   $n=$editList[$i];
				   $tabn=$tables[$n];
				   global  $$tabn;
			       array_push(  $Base, $tabn);
				   array_push(  $up, $$tabn);
			  }
 
		      $Link=$BaseURL."?SortType=".$SortType."&ListType=prepress";
		      $stmt= MakeUpdateStmt(  $data_library,"fpoutsourcingcost",$Base,$up,$WHEREtable,$WHEREData);
			   echo $stmt;
			   SendCommand($stmt,$data_library);
		       echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";
	 }
	 function  UpExchangeRate(){
	           global  $outsn,$datas;
			   global $data_library ;
			   global $exchangeRate;
			   global $BaseURL,$BackURL;
			   global $backURLval;
	           $WHEREtable=array( "data_type", "OutsSn"   );
		       $WHEREData=array( "outs",$outsn  );
			   $Base=array("exchangeRate");
		       $up=array($exchangeRate);
			   $stmt= MakeUpdateStmt(  $data_library,"outsdetail",$Base,$up,$WHEREtable,$WHEREData);
			   SendCommand($stmt,$data_library);
			   //上傳截圖
			   if($_FILES['exchangeRatepic']["name"]!=null  ){
				   $temp = explode(".", $_FILES['exchangeRatepic']["name"]);
				   $path="Outsourcing/exchangeRate/".$outsn.".".$temp[1];
			       move_uploaded_file($_FILES['exchangeRatepic']["tmp_name"], $path);  
				   $Npath="Outsourcing/exchangeRate/".$outsn.".png";
				  $cmd="convert   $path       $Npath ";
				  exec($cmd);
			   }
			   
			   echo " <script language='JavaScript'>window.location.replace('".$backURLval."&sn=".$outsn."')</script>";
	 }
	 
?>
<?php //上傳前表單
      function  DrawFormcostEdit(){ //編輯內容
          global $Edit,$colHi,$ListSize;
		  if($Edit=="")return;
		  global $OutCosts;
		  global  $data_library;
		  global $BackURL;
		  global $editList;		
		  $tables=returnTables($data_library,"fpoutsourcingcost");
		  $showField=array(1,5,7,8,9,10,11,12,13);
		  $costEditT=filterArray($OutCosts,1,$Edit);
		  $costEdit= $costEditT[0];
		  echo  "<form method=post enctype=multipart/form-data action=".$BackURL.">";
		  echo "<input type=hidden name=editsn value=".$Edit."  >";
		  $x=30;
		  $y=$colHi;
		  DrawRect("",9,"",$x,$y+18,1100,2,"#ff7777");
		  for($i=0;$i<count($showField);$i++){
				   $n=$showField[$i];
			       $w=$ListSize[0][$n];
 
				   if (in_array($n,$editList)) {
					   $s=$w/6;
				     $input="<input type=text name=".$tables[$n]."  value='". $costEdit[$n]." 'size=".$s."  style= font-size:10px; >";
		      	    DrawInputRect_size("",10,"#ffffff",$x  ,$y, $w,20,$BgColor,$WorldAlign,$input);
				   }
				   $x+=$w+2;
		  }
		    $submitP="<input type=submit name=submit value=修改表單 style= font-size:10px; >";
            DrawInputRect("",8 ,"#ffffff",$x ,$y ,100,20, $colorCodes[4][2],"top",$submitP);
		  echo "</form>";
	  }
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
		      // ListPregress();
			   
	           global  $BaseURL,$BackURL;
			   global  $OutsLastSort,$contacts;
			   global $Rx,$Ry,$sn,$info;
			   $ex= $Rx;
			   $ey=$Ry;
			   $w=200;
			   $h=40;
			  // $sn=$OutsLastSort+1;
			   $title="編輯註解[".$sn."]";
			//   $Link=$BaseURL//."?SortType=".$SortType."&ListType=prepress";
	           //DrawPopBG($ex,$ey-22,$w,$h,$title ,"12",$BaseURL);
			   echo   "<form id='ChangeOut'  name='Show' action='".$BaseURL."' method='post'>";
			   echo "<input type=hidden name=sn value='".$sn."'   >";
			   DrawRect("註解",10,"#ffffff",$ex,$ey-2 ,333,22,"#000000");
			   $submitP="<input type=submit name=submit value=更新註解 style= font-size:10px; >";
			   DrawInputRect("",8 ,"#ffffff",$ex+200 ,$ey  ,100,20, $colorCodes[4][2],"top",$submitP);
			   $submitP="<input type=submit name=submit value=取消 style= font-size:10px; >";
			   DrawInputRect("",8 ,"#ffffff",$ex+270 ,$ey  ,60,20, $colorCodes[4][2],"top",$submitP);
			   
			   $input="<input type=text name=Remark value='".$info."'size=30  style= font-size:10px; >";
			   DrawInputRect_size("註解",10,"#ffffff",$ex,$ey,200,20,$BgColor,$WorldAlign,$input);
			   echo "</form>";
	  }
	  function  EditOutsForm(){
		       global  $BaseURL,$BackURL;
			   global  $sn;
			   global  $OutCosts;
			  // global  $ListType;
			   global $UpType;
			   $currentDataT= filterArray( $OutCosts,1,$sn);
			   $currentData= filterArray( $currentDataT,2,$sn);
			   $ex=100;
			   $ey=100;
			   $w=1200;
			   $h=800;
	           $Link=$BaseURL;//."?SortType=".$SortType;
			   $ValArray=array(array("ListType","Processing"));
			   $c="(第".$currentData[0][13]."包)";
			   $title ="編輯".$currentData[0][1]."-".$currentData[0][5].$c."[".$currentData[0][8]."]製作內容";
	         //  DrawPopBG($ex,$ey,$w,$h,$title ,"12",$Link);
			   DrawPopBGsendVal($ex,$ey,$w,$h,$title ,"12",$Link,$ValArray);
			 
			   if($UpType=="")  ExportForms($sn);
		       if($UpType=="inputOutsForm") InputForms($sn);
               DrawPrecautions($currentData[0][15],$sn);//判斷中國人
			   
		
	  }
      function  DrawPrecautions($code,$sn){
	            global $outs,$BackURL;  
                global $FormRect;
			    global $Outstotal;
			    global $exchangeRate;
			    $currentOutT=filterArray( $outs,1,$code);
				$currentOut=$currentOutT[0];
				$country=trim($currentOut[4]);
				$studio=trim($currentOut[6]);
				$rect=$FormRect;	   //估價費率:
                 if ($country=="中國" && $studio=="個人"  ){  //人民幣>美金
				    $msg=$country."!注意中國個人申請使用美金" ;
					DrawRect($msg,10,"#ffffff", $rect[0], $rect[1]-18,200,18,"#ff7777");
				   // $Link=$BaseURL."?sn=".$sn."&picNum=".count($outsDetial);
					echo  "<form method=post enctype=multipart/form-data action=".$BackURL.">";
					echo "<input type=hidden name=outsn value=".$sn.">";
			        echo "<input type=hidden name=backURLval  value=".$BackURL.">";
			        $input="<input type=text name=exchangeRate  size=12 style= font-size:10px; value=".$exchangeRate." >";
                    DrawInputRect("(人民幣>美金總額)",8 ,"#ffffff",$rect[0]+310,$rect[1]-18,200,$rect[3], $colorCodes[4][2],"top",$input);
				    $input="<input type=file name=exchangeRatepic  size=12 style= font-size:10px;  >";
                    DrawInputRect("(轉匯截圖)",8 ,"#ffffff",$rect[0]+450,$rect[1]-18,300,$rect[3], $colorCodes[4][2],"top",$input);
			        $submitP="<input type=submit name=submit value=上傳匯率 style= font-size:10px; >";
                    DrawInputRect("",8 ,"#ffffff",$rect[0]+580,$rect[1]-18,100,$rect[3], $colorCodes[4][2],"top",$submitP);
					echo "</from>";
				  }
				  $pic="Outsourcing/exchangeRate/".$sn.".png";
			    	 if(file_exists($pic))  
						   DrawLinkPic($pic,$rect[1]-60,$rect[0]+877,300,120,$Link);
	  }
	  function  ExportForms($sn){
		       global $BaseURL,$BackURL;
			   
	           $outsDetialT=getMysqlDataArray("outsdetail"); 
			   $outsDetial= filterArray( $outsDetialT,1,$sn);
			   $ListTitle=filterArray( $outsDetialT,0,"資料類別");
			   $List=array(4,5,6,7,8,9);
			   $rect=array(100,160,120,20);
			   $fontColor="#ffffff";$BGcolor="#000000";
			   Drawsingel($ListTitle[0],$List,$rect,$fontColor,$BGcolor);
			   $rect[1]+=22;
			   $fontColor="#222222";$BGcolor="#cccccc";
			   //列印
			 // $Link=$BaseURL."?sn=".$sn."&picNum=".count($outsDetial);
			   echo  "<form method=post enctype=multipart/form-data action=".$Link.">";
			  //細節
			   echo "<input type=hidden name=picNum value=".count($outsDetial)." >";
	           DrawDetialList($outsDetial ,$fontColor,$BGcolor);
			   $rect[1]+=count($outsDetial)*22;
			   //送出
			   $submitP="<input type=submit name=submit value=上傳圖檔  style= font-size:10px; >";
               DrawInputRect("",8 ,"#ffffff",$rect[0]+750,$rect[1],200,$rect[3], $colorCodes[4][2],"top",$submitP);
			
			   //連接修改表單
			//   $Link=$BaseURL."?ListType=inputOutsForm&sn=".$sn;
			   // $Link=$BaseURL."?UpType=inputOutsForm&sn=".$sn;
			  // DrawLinkRect_Layer2sendVal("修改表單2",10,$fontColor,$rect,"#ffaacc",$Link,$border,$Layer);
			   $ValArray=array(array("UpType","inputOutsForm"));
			   sendVal($BaseURL,$ValArray,"submit","修改表單",$rect,10, "#ffaacc" ,$fontColor   );
			   //匯率
			   $Link2="https://www.baidu.com/s?ie=utf-8&f=8&rsv_bp=1&rsv_idx=1&tn=baidu&wd=%E6%B1%87%E7%8E%87&oq=%25E6%25B1%2587%25E7%258E%2587%25E4%25BA%25BA%25E6%25B0%2591%25E5%25B8%2581%25E5%258F%25B0%25E5%25B8%2581&rsv_pq=fb7d36c4000e11ee&rsv_t=cc6dkVdXoMRFbjGQ46xf0UoT5jFYhDXhUsiL7NjPvkFHLT%2BDehA9LNu%2BEj8&rqlang=cn&rsv_enter=1&rsv_dl=tb&inputT=373&rsv_sug3=19&rsv_sug1=11&rsv_sug7=100&rsv_sug2=0&rsv_sug4=512&rsv_sug=2";
			   DrawLinkRect("匯率運算連結",10,"#000000",$rect[0]+200,$rect[1],100,20,"#ccffaa",$Link2,$border);
			  
	
			   echo "</form>";
			      global $Outstotal;
			   DrawRect("總額:".$Outstotal,10,"#000000",$rect[0]+210, 120,100,18,"#eeeeee");
			   
			   //輸出
			   $Link="../../IGGTaipeRD2/Outsourcing/ExportMat.php?Exporttype=mat1&sn=".$sn;
			   $msg="產生[材料1：项目外包需求申请单.xls]";
			   $fontColor="#ffffff";$BGcolor="#99aa99";
			   $rect[1]+=42;
		       $rect[2]=300;
			   DrawLinkRect_Layer2sendVal($msg,12,$fontColor,$rect,$BGcolor,$Link,$border,$Layer);
			   //mat2
			   $Link="../../IGGTaipeRD2/Outsourcing/ExportMatDoc.php?Exporttype=mat2&sn=".$sn;
			   $msg="產生[材料2：申请资料.docx]";
			   $rect[1]+=32;
			   DrawLinkRect_Layer2sendVal($msg,12,$fontColor,$rect,$BGcolor,$Link,$border,$Layer);
			   //mat3
			   $Link="../../IGGTaipeRD2/Outsourcing/ExportMat.php?Exporttype=mat3&sn=".$sn;
			   $msg="產生[材料3：合同报价单.xls]";
			   $rect[1]+=32;
			   DrawLinkRect_Layer2sendVal($msg,12,$fontColor,$rect,$BGcolor,$Link,$border,$Layer);
			   //mat4
			   $Link="../../IGGTaipeRD2/Outsourcing/ExportMatDoc.php?Exporttype=mat4&sn=".$sn;
			   $msg="產生[材料4：需求描述模板.doc]";
			   $rect[1]+=32;
			   DrawLinkRect_Layer2sendVal($msg,12,$fontColor,$rect,$BGcolor,$Link,$border,$Layer);
			   //報價
			   $Link="../../IGGTaipeRD2/Outsourcing/ExportMat.php?Exporttype=Quote&sn=".$sn;
			   $msg="產生[報價.xlsx]";
			   $rect[1]+=32;
			   DrawLinkRect_Layer2sendVal($msg,12,$fontColor,$rect,$BGcolor,$Link,$border,$Layer);
			   //報價
			   $Link="../../IGGTaipeRD2/Outsourcing/ExportMatDoc.php?Exporttype=Demand&sn=".$sn;
			   $msg="產生[需求明细.doc]";
			   $rect[1]+=32;
			   DrawLinkRect_Layer2sendVal($msg,12,$fontColor,$rect,$BGcolor,$Link,$border,$Layer);
			   //產生預覽圖
			   $Link="../../IGGTaipeRD2/Outsourcing/ExportMat.php?Exporttype=pic&sn=".$sn;
			   $msg="產生[縮圖表.xls]";
			   $rect[1]+=32;
			   DrawLinkRect_Layer2sendVal($msg,12,$fontColor,$rect,$BGcolor,$Link,$border,$Layer);
			   
	  }
	  function  InputForms($sn){
		        echo "InputForms";
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
			   global $Outstotal;
			   global $exchangeRate;
			   $Outstotal=0;
			   $rect=$FormRect;
			   Drawsingel($FormTitle[0],$FormList, $rect,"#ffffff","#000000");
			   $rect[1]+=22;
			   Drawsingel($outsDetial[$i],$FormList, $rect,$fontColor,$BGcolor);
	  		   for($i=0;$i<count($outsDetial);$i++){
			        $Outstotal+=$outsDetial[$i][8];
					if($outsDetial[$i][11]!="")$exchangeRate=$outsDetial[$i][11];
			        Drawsingel($outsDetial[$i],$FormList, $rect,$fontColor,$BGcolor);
				    $rect[1]+=22;
			   }
	  }
	  function  Drawsingel($data,$List,$rect,$fontColor,$BGcolor){
		    global $DetailFormName, $FormRect,$FormList,$FormListsize;
			global $sn;
		    for($i=0;$i<count($List);$i++){
				  $w=$FormListsize[$List[$i]];
			      DrawRect($data[$List[$i]],10,$fontColor,$rect[0],$rect[1],$w,$rect[3],$BGcolor);
				  $rect[0]+=$w+2;
			   }
			  // if($data[0]=="表單")return;
			   $pic="Outsourcing/SortPic/".$sn."/spic".$data[2].".jpg" ;
			   // echo $pic;
	           DrawPosPic($pic,$rect[1],$rect[0],20,20,"absolute" );
			   $rect[0]+=22;
               $input= "<input type=file name=pic".$data[2]." style= font-size:10px;>";
			   DrawInputRect_size("效果图例",10,"#ffffff",$rect[0] ,$rect[1],300,$rect[3],$BGcolor,$WorldAlign,$input);
	  }
	  function  UpformCheck(){
		      echo " UpformCheck";
	     	  global $sn;
			  global $DetailFormName, $FormRect,$FormList,$FormListsize;
			  global $BaseURL;
			  global $txt;
			   $txt=$_POST['txt'];
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