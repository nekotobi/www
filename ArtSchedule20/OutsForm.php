<?PHP
 
	  require_once('/Apis/ProjectApi.php');
      global $URL;
      global $CookieArray;
	  $URL="OutsForm.php";
 
	  $CookieArray=array("selectProject");
      function   ProAPI_CheckCookies($URL, $CookieArray){
                 require_once('/Apis/PubApi20.php');
	             PubApi_setcookies($CookieArray, $URL);
		  	     PubApi_GetArrayCookie($CookieArray);
	            }
	  ProAPI_CheckCookies( $URL, $CookieArray);

	  if($CookieArray[1]==""){
		  $CookieArray[1]="Zombie";
		  $selectProject=$CookieArray[1];
	  }

 
?>

<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>外包需求表</title>
</head>
 
<body bgcolor="#b5c4b1">
<?php //主控台
      require_once('/Apis/PubApi20.php');
	  require_once('/Apis/mysqlApi20.php');
	  require_once('/Apis/PubJavaApi.php');
	  require_once('/Apis/CalendarApi20.php');
	  $id=$_COOKIE['IGG_id'];
	  $Rank=$_COOKIE["IGG_Rank"];
	  if($Rank!=1){
		 echo "未登入或無權限觀看此頁";
		 return;
	  }
	  defineData();
      DrawButtoms();
	  SwitchType();
      showSpends();
?>
<?php //初始資料
    function showSpends(){
	         //總計
			 global $OutCosts;
			 global $OutsCostTotal;
			 $OutsCostTotal=0;
			 $budget=400000;
 
			 for($i=0;$i<count( $OutCosts);$i++){
			    $OutsCostTotal+=$OutCosts[$i][10];
			 }
			 $p=round(($OutsCostTotal/$budget)*100);
			 $Rect=array(900,20,200,20);
			 $msg="總計:".round($OutsCostTotal)."/".$budget."(".$p."%)";
			 DrawRect($msg,10,"#ffffff", $Rect,"#ffaa99");
	}
    function defineData(){
	         global $URL,$selectProject;
			 global $CookieArray;
			 //網頁變數
			 global  $WebSendVal,$WebSendValDetials ;
			 $WebSendVal=array(array("SortType",$_POST["SortType"] ),
			                   array("ListType",$_POST["ListType"] )
							   );
			 $WebSendValDetials=array("SortType"=>array(  "▲","▼"),
			                          "ListType"=>array( "處理中表單","請款進程","歷史總表","+"),
			                          );
			 //表單資料
		     global $ListNames,$ListSize,$OutCosts,$OutsLastSort;
			 global $data_library,$tableName,$pregressData;
	         $tableName="outcost_".$selectProject;
			 $data_library="iggtaiperd2"; 
			 $MainPlanDataT=getMysqlDataArray($tableName); 
			 $ListNames=filterArray($MainPlanDataT,0,"title");
			 $ListSize=filterArray($MainPlanDataT,0,"size");
			 $OutCostst=filterArray($MainPlanDataT,0,"cost");
			 $OutsLastSort= getLastSN2($OutCostst,1);
			 //進度
			 global $Pregress;
		     $Pregress= explode("_", $ListNames[0][17]); 
			 //排序
			 $forward="true";
			 if($_POST["SortType"]=="▼") $forward="false";
		     $OutCosts= sortArrays($OutCostst ,1,$forward) ;
			 sortcontact();//整理聯絡人 
			 //外包資料
			 global $outsBaseData,$outsBaseSelects;
		     getOutsData();
			 //表格資料
			 global $DetailFormName, $FormRect,$FormList,$FormListsize,$FormTitle;
			 $DetailFormName="outsdetail_".$selectProject;
			 $formBase=getMysqlDataArray($DetailFormName); 
			 $FormTitle=filterArray($formBase,0,"資料類別");
			 $FormListsizeT=filterArray($formBase,0,"size");
			 $FormListsize=$FormListsizeT[0];
			 $FormList=array(2,3,4,5,6,7,8,9,10 );
			 $FormRect=array(100,140,120,20);
			 //幣值兌換
			 global $CurrencyNTtoUs;
		     global $CurrencyCNYtoUs;
             $CurrencyNTtoUs=0.035;
			 $CurrencyCNYtoUs= 0.154;

 
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
    function getOutCode($selectName){ //取得外包碼
	          global $outsBaseData,$outsBaseSelects;
	     	  $tmp= explode("-",$selectName); //0code 1序號 2名稱
		      $sn=$tmp[0];
		      for($i=0;$i<count($outsBaseData);$i++){
		          if($outsBaseData[$i][0]==$sn)return $outsBaseData[$i][1];
		      }
	}
    function getOutsData(){ //取得外包資料
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
?>
<?php //判斷狀況
      function SwitchType(){
	           global $WebSendVal,$WebSendValDetials;
	           if($_POST["ListType"]=="處理中表單"){
					 DrawTitle();
				}
			   if($_POST["ListType"]=="請款進程"){
                    DrawTitle();
				}
			   if($_POST["ListType"]=="+"){//新增表單
			     CreatNewOuts();
			   }
			   if($_POST["ListType"]=="EditOutsForm"){ //編輯表單
			      EditOutsForm();
			   }
 
			   if($_POST["submit"]=="新增外包表單"){ 
			     AddNewMysqlData();
			   }
			   if($_POST["submit"]=="上傳表單") UpformCheck(); //上傳詳細內容
			   if($_POST["submit"]=="確定上傳表單"){ //上傳詳細資料
		        Upform();
			  }
			   if($_POST["pregressSN"]!=""){
			     UpPregress();
			   }
			   if($_POST["submit"]=="上傳匯率") UpExchangeRate();
	  }

?>
<?php //按鈕
     function DrawButtoms(){
		      global $URL;
              global $CookieArray;
			  global $selectProject,$startY,$URL;
			  $startY=20;
	          ProAPI_DrawProjectButtoms( $selectProject,$startY,$URL);
		      DrawValButton();
	 }
	 function DrawValButton(){
			  global $WebSendVal,$WebSendValDetials;
			  global $URL;
			  global $Rect;
			  $SubmitName="submit";
			  $Rect=array(20,40,40,18);
			  $w=6;
			  for($i=0;$i<count($WebSendVal);$i++){
				   $str=$WebSendVal[$i][0];
				   foreach($WebSendValDetials[$str]  as  $arr){
					     $BGColor="#111111";
				           $l= strlen( $arr )*$w;
						   $Rect[2] =$l;
						   if( $WebSendVal[$i][1]== $arr )$BGColor="#aa1111";
						   $SendVal= $WebSendVal;
						   $SendVal[$i][1]= $arr;
						   sendVal($URL,  $SendVal,$SubmitName,$arr ,$Rect,10,$BGColor   );
						   $Rect[0] = $Rect[0]+$l+2;
				   }
			  }
	 }
  
?>
<?php //列印總表資料
      function DrawTitle(){
		      global $ListNames,$ListSize,$OutCosts;
			  $Rect=array(20,60,40,20);
			  global   $Pregress ; 
		
			   //抬頭	
             if($_POST["ListType"]=="請款進程")	{		   
			    $arr=array( "序號","外包商(公司/個人)");
			    $Size=array( 44,200);
				 for($i=0;$i<count( $Pregress);$i++){
				     array_push($arr, $Pregress[$i]);
					 array_push( $Size,50);
				 } 
			     for($i=0;$i<count($arr) ;$i++){
				     $Rect[2]= $Size[$i];
					 DrawRect( $arr[$i],10,"#FFFFFF",$Rect,"#000000");
				     $Rect[0]= $Rect[0]+ $Rect[2]+2;
				 }
			  }
			 if($_POST["ListType"]=="處理中表單")	{		
 	            $arr=$ListNames[0];
			     for($i=1;$i<count($arr) ;$i++){
			      	 $Rect[2]= $ListSize[0][$i];
			  	    if( $Rect[2]!=""){
			           DrawRect( $arr[$i],10,"#FFFFFF",$Rect,"#000000");
					   $Rect[0]= $Rect[0]+ $Rect[2]+2;
				      }
			       }
			    }
			  for($i=0;$i<count($OutCosts);$i++){
				 DrawLines($OutCosts[$i],($i+4)*22, $Pregress );
				
			  }
			//  DrawTotal($OutCosts);   
	 }
      function Drawoutpregress($Pregressdata,$sn,$Rect, $Pregress){
		       $c= explode("_", $Pregressdata);  
			   global $WebSendVal;
			   global $URL;
			   global $selectProject;
			   $SendVal=$WebSendVal;
			   $Rect[2]=50;
			   array_push($SendVal,array("pregressSN",$sn));
			   $ListAccept=false;
	           for($i=0;$i<count($Pregress);$i++){
				   $BGColor="#999999";
				    array_push($SendVal,array("pregressSort",$i));
				    $msg="_";
			        if($c[$i]!="" and $c[$i]!="X" ) {
					   $BGColor="#99cc99";
					   	  $msg=$c[$i];
						  if($i>1) $ListAccept=true;
				   }
				   
			      sendVal($URL,  $SendVal,"submit", $msg,$Rect,7,$BGColor,"#ffffff"  );
				  $Rect[0]+=52;
			   }
			   if( !$ListAccept)return;
		       $Rect[2]=250;
			   $path="\\\\10.4.0.190\\AppServ\\www\\AcceptanceData\\".$selectProject."\\".$sn;
			   $webPath="..\\..\\AcceptanceData\\".$selectProject."\\".$sn;
			   if (!is_dir($webPath) ){
				   mkdir( $webPath, 0700);
		        }
				$BgColor="#aaaaaa";
			   DrawRect($path,9,$fontColor,$Rect,$BgColor);
			  // sendVal($URL,  $SendVal,"submit", $path,$Rect,7,$BGColor,"#ffffff"); 
	
	  }
	  function DrawLines($Data,$y, $Pregress ){
		      global  $ListSize,$PreList;
			  global  $URL,$selectProject;
			  global $WebSendVal;
		      $SubmitName="submit";
			  $BgColor="#DDDDDD";
			  $fontColor="#000000";
			  $w= $ListSize[0][1];
			  $Rect=array(20,$y,$w,20);
			  $SendVal=array(array( "ListType","EditOutsForm"),array("EditSn", $Data[1]));
		      sendVal($URL,  $SendVal,$SubmitName,$Data[1] ,$Rect,10,$BGColor,"#000000"  );
			  $Rect[0]= $Rect[0]+$w+2;
			  $t=count($Data)-3;
		      if($_POST["ListType"]=="請款進程")$t=6;
		         for($i=2;$i<$t;$i++){
				    $Rect[2]= $ListSize[0][$i];
			        $msg=  $Data[$i];
				    if($Rect[2]!=""){
			          DrawRect($msg,10,"#000000",$Rect,"#DDDDDD");
					  $Rect[0]= $Rect[0]+ $Rect[2]+2;
				     }
			  }
			  $n= $Data[process];
			  $msg=$PreList[0][$n];
			  $BGcolor="#FFDDDD";
              if($msg=="付款日")  {
				  $BGcolor="#DDFFDD";
			      $msg="完成付款";
			  } 
			  //列印進程
		      if($_POST["ListType"]=="請款進程")	{
				 Drawoutpregress($Data[17],$Data[1], $Rect, $Pregress);
			   }
			  //第幾包
			  if($Data[13]!=""){ 
			  $Rect=array(70,$y,35,18);
			      DrawRect("第".$Data[13]."包",9,$fontColor,  $Rect,"#eeffcc");
			  }		
      			  
	 }
?>
<?php //列印編輯表單
      function  CreatNewOuts(){
				        global $URL;
					    global  $OutsLastSort,$contacts;
					    $sn=$OutsLastSort+1;
						$title="新增外包表單 編號[".$sn."]";
					    DrawPopBG(100,100,600,400,$title ,"12",$URL);
				        global $selectProject;
						$department="台北二部";
						global $outsBaseSelects;
			  $x=100;
			  $y=130;
			  $w=500;
			  $h=20;
			  echo   "<form id='ChangeOut'  name='Show' action='". $URL."' method='post'>";
			  echo "<input type=hidden name=data_type value='cost'   >";
			  echo "<input type=hidden name=sn value='".$sn."'   >";
			  echo "<input type=hidden name=code value='".$sn."'  >";
			  echo "<input type=hidden name=project value='".$selectProject."'   >";
			  echo "<input type=hidden name=department value='". $department."'   >";
			  //送出
			  $submitP="<input type=submit name=submit value=新增外包表單 style= font-size:10px; >";
              //DrawInputRect("",8 ,"#ffffff",$x+($w),$y-30,$w,$h, $colorCodes[4][2],"top",$submitP);
			  DrawInputRect_size("",8 ,"#ffffff",$x+($w),$y-30,$w,$h,"#222222",$WorldAlign,  $submitP);
			  //外包
			  $input=PubApi_MakeSelection($outsBaseSelects,$selectOut,"selectOut",10);
			  //DrawInputRect("選擇外包_",10,"#ffffff",$x,$y,$w,$h,"",$WorldAlign,$input);
			  DrawInputRect_size("選擇外包_",10,"#ffffff",$x,$y,$w,$h,"#222222",$WorldAlign,$input);
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
 	  function EditOutsForm(){
		       global  $URL;
		  	   $sn=$_POST["EditSn"] ;
			   $UpType =$_POST["UpType"] ;
		 	   global  $OutCosts;
	           echo $sn;
			   $currentDataT= filterArray( $OutCosts,1,$sn);
			   $currentData= filterArray( $currentDataT,2,$sn);
			   $ex=100;
			   $ey=100;
			   $w=1000;
			   $h=800;
			   $c="(第".$currentData[0][13]."包)";
			   $title ="編輯".$currentData[0][1]."-".$currentData[0][5].$c."[".$currentData[0][8]."]製作內容";
			   $ValArray=array(array("ListType","Processing"));
			   DrawPopBG($ex,$ey,$w,$h ,$title ,"12",$URL);
               if($UpType=="")  ExportForms($sn);
			   if($UpType=="inputOutsForm") InputForms($sn);
			   DrawPrecautions($currentData[0][15],$sn);//判斷中國人
 
		
	  }
	  function  DrawPrecautions($code,$sn){//判斷中國人
	            global $outs,$URL;  
                global $FormRect;
			    global $Outstotal;
			    global $exchangeRate;
				//echo ">".$sn;
			    $currentOutT=filterArray( $outs,1,$code);
				$currentOut=$currentOutT[0];
				$country=trim($currentOut[4]);
				$studio=trim($currentOut[6]);
				$rect=$FormRect;	   //估價費率: 
                 if ($country=="中國" && $studio=="個人"  ){  //人民幣>美金
				    $msg=$country."!注意中國個人申請使用美金" ;
					DrawRect($msg,10,"#ffffff",array( $rect[0], $rect[1]-18,200,18),"#ff7777");
					echo "<form method=post enctype=multipart/form-data action=".$BackURL.">";
					echo "<input type=hidden name=outsn value=".$sn.">";
			        echo "<input type=hidden name=backURLval  value=".$BackURL.">";
			        $input="<input type=text name=exchangeRate  size=12 style= font-size:10px; value=".$exchangeRate." >";
                    DrawInputRect("(人民幣>美金總額)",8 ,"#ffffff",array($rect[0]+310,$rect[1]-18,200,$rect[3]), $colorCodes[4][2],"top",$input);
				    $input="<input type=file name=exchangeRatepic  size=12 style= font-size:10px;  >";
                    DrawInputRect("(轉匯截圖)",8 ,"#ffffff",array($rect[0]+450,$rect[1]-18,300,$rect[3]), $colorCodes[4][2],"top",$input);
			        $submitP="<input type=submit name=submit value=上傳匯率 style= font-size:10px; >";
                    DrawInputRect("",8 ,"#ffffff",array($rect[0]+580,$rect[1]-18,100,$rect[3]), $colorCodes[4][2],"top",$submitP);
					echo "</from>";
				  }
				  $pic="Outsourcing/exchangeRate/".$sn.".png";
			       if(file_exists($pic))  
					  DrawLinkPic($pic,array($rect[0]+777,$rect[1]-80,200,90),$Link);
	  }
	  //php 產生表單
	  function  ExportForms($sn){
		       global $URL;
			   global $DetailFormName;
			   global $selectProject;
			   $sn=$_POST["EditSn"];
	           $outsDetialT=getMysqlDataArray( $DetailFormName); 
			   $outsDetial= filterArray( $outsDetialT,1,$sn);
	  
			   $ListTitle=filterArray( $outsDetialT,0,"資料類別");
			   $List=array(4,5,6,7,8,9);
			   $rect=array(160,100,80,16);
			   $fontColor="#ffffff";$BGcolor="#000000";
			   //連接修改表單
			   $ValArray=array(array( "ListType","EditOutsForm"),array("UpType","inputOutsForm"),array("EditSn",$sn));
			   sendVal($URL,$ValArray,"submit","修改表單",$rect,10, "#ffaacc" ,$fontColor   );
			   //匯率
			   $Link2="https://www.baidu.com/s?ie=utf-8&f=8&rsv_bp=1&rsv_idx=1&tn=baidu&wd=%E6%B1%87%E7%8E%87&oq=%25E6%25B1%2587%25E7%258E%2587%25E4%25BA%25BA%25E6%25B0%2591%25E5%25B8%2581%25E5%258F%25B0%25E5%25B8%2581&rsv_pq=fb7d36c4000e11ee&rsv_t=cc6dkVdXoMRFbjGQ46xf0UoT5jFYhDXhUsiL7NjPvkFHLT%2BDehA9LNu%2BEj8&rqlang=cn&rsv_enter=1&rsv_dl=tb&inputT=373&rsv_sug3=19&rsv_sug1=11&rsv_sug7=100&rsv_sug2=0&rsv_sug4=512&rsv_sug=2";
			   DrawLinkRect("匯率運算連結",10,"#000000",$rect[0]+200,$rect[1],100,20,"#ccffaa",$Link2,$border);
			  
			   //detail內容
			   $rect[1]+=22;
			   $fontColor="#222222";$BGcolor="#cccccc";
               DrawDetialList($outsDetial,$fontColor,$BGcolor);
			   global $Outstotal;
			   DrawRect("總額:".$Outstotal,10,"#000000",array($rect[0]+210, 120,100,18),"#eeeeee");
			   //輸出
			   $Link="../../IGGTaipeRD2/Outsourcing/ExportMat20.php";//?Exporttype=mat1&sn=".$sn;
			   $msg="產生[材料1：项目外包需求申请单.xls]";
			   $fontColor="#ffffff";$BGcolor="#99aa99";
			   $rect[1]+=60+(count( $outsDetial)+1)*22;
			 //  echo ">>>>>>>>>>>>>>>>>>>>>>>>".count( $outsDetial).">>".$rect[1];
		       $rect[2]=300;
			   $ValArray=array(array("Exporttype",mat1),array("sn",$sn),array("selectProject",$selectProject));
			   sendVal( $Link,$ValArray,"submit",$msg,$rect, 12, $BGcolor ,$fontColor );

			   //mat2
			   $Link="../../IGGTaipeRD2/Outsourcing/ExportMatDoc20.php";//?Exporttype=mat2&sn=".$sn;
			   $msg="產生[材料2：申请资料.docx]";
			   $rect[1]+=32;
			   $ValArray=array(array("Exporttype",mat2),array("sn",$sn),array("selectProject",$selectProject));
			   sendVal( $Link,$ValArray,"submit",$msg,$rect, 12, $BGcolor ,$fontColor );
			   //mat3
			    $Link="../../IGGTaipeRD2/Outsourcing/ExportMat20.php";// $Link="../../IGGTaipeRD2/Outsourcing/ExportMat.php?Exporttype=mat3&sn=".$sn;
			   $msg="產生[材料3：合同报价单.xls]";
			   $rect[1]+=32;
			   $ValArray=array(array("Exporttype",mat3),array("sn",$sn),array("selectProject",$selectProject));
			   sendVal( $Link,$ValArray,"submit",$msg,$rect, 12, $BGcolor ,$fontColor );
               //mat4
		       $Link="../../IGGTaipeRD2/Outsourcing/ExportMatDoc20.php";//?Exporttype=mat2&sn=".$sn;
			   $msg="產生[材料4：需求描述模板.doc]";
			   $rect[1]+=32;
			   $ValArray=array(array("Exporttype",mat4),array("sn",$sn),array("selectProject",$selectProject));
			   sendVal( $Link,$ValArray,"submit",$msg,$rect, 12, $BGcolor ,$fontColor );
               //報價
			   $Link="../../IGGTaipeRD2/Outsourcing/ExportMat20.php";
			   $msg="產生[報價.xlsx]";
			   $rect[1]+=32;
			   $ValArray=array(array("Exporttype",Quote),array("sn",$sn),array("selectProject",$selectProject));
			   sendVal( $Link,$ValArray,"submit",$msg,$rect, 12, $BGcolor ,$fontColor );
		       //需求明细
			   $Link="../../IGGTaipeRD2/Outsourcing/ExportMatDoc20.php";//?Exporttype=Demand&sn=".$sn;
			   $msg="產生[需求明细.doc]";
			   $rect[1]+=32;
			   $ValArray=array(array("Exporttype",Demand),array("sn",$sn),array("selectProject",$selectProject));
			    sendVal( $Link,$ValArray,"submit",$msg,$rect, 12, $BGcolor ,$fontColor );
				
			   //產生預覽圖
			   $Link="../../IGGTaipeRD2/Outsourcing/ExportMat20.php";
			   $msg="產生[縮圖表.xls]";
			   $rect[1]+=32;
			   $ValArray=array(array("Exporttype",pic),array("sn",$sn),array("selectProject",$selectProject));
			   sendVal( $Link,$ValArray,"submit",$msg,$rect, 12, $BGcolor ,$fontColor );
		 

 
	 
			  
		 
	  }
	  function  Drawsingel($data,$List,$rect,$fontColor,$BGcolor){ //列印細節單項
		        global $DetailFormName, $FormRect,$FormList,$FormListsize;
		     	global $sn;
		        for($i=0;$i<count($List);$i++){
				    $w=$FormListsize[$List[$i]];
			        DrawRect($data[$List[$i]],9,$fontColor,array($rect[0],$rect[1],$w,$rect[3]),$BGcolor);
				    $rect[0]+=$w+2;
			    }		 
	  }
?>
<?php //上傳表單
     function UpPregress(){
	          $pregressSN= $_POST["pregressSN"];
			  $pregressSort= $_POST["pregressSort"];
			  global $data_library,$tableName;
			  global  $OutCosts;
			  $thisCost=filterArray( $OutCosts,1,$pregressSN );
			  $Base= $thisCost[0][17];
			  $str=makeRecodTxt($Base, $pregressSort);
		      $WHEREtable=array( "data_type", "sn");
		      $WHEREData=array( "cost", $pregressSN  );
			  $Base=array( "pregress");
			  $up=array($str);
			  $stmt=MAPI_MakeUpdateStmt($tableName,$Base,$up,$WHEREtable,$WHEREData);
		      
              SendCommand($stmt,$data_library);		
			  global $WebSendVal,$URL;
		 	  $ar=return_WebPostArray( $WebSendVal );
			    JAPI_ReLoad($ar,$URL);
 
	 }
     function return_WebPostArray($arr ){
	           $ar=array();
			   for($i=0;$i<count($arr);$i++){
			       array_Push($ar,array($arr[$i][0],$_POST[$arr[$i][0]]));
			   }
			   return $ar;
	  }
	 function makeRecodTxt($Base,$sort){
		      global $Pregress;
	          $str= explode("_", $Base);  
			  $returnStr="";
			  for($i=0;$i<count( $Pregress);$i++){
			     $s= $str[$i];
				 if($s=="")$s="X";
				 if($i==$sort){
				    if($s=="X"){
						$s=date("Y-n-j");
					}else{
						$s="X";
					}
				 }
                 $returnStr=  $returnStr.$s."_";				 
			  }
			  return $returnStr;
	 }
	 function InputForms($sn){  //輸入資料
		        echo "InputForms";
		  	    global $URL;
			     $sn=$_POST["EditSn"];
				$rect=array(100,120,120,20);
				$Link="https://docs.google.com/spreadsheets/d/1qoO2rSKDBOMOcYIDLn1uORfvzlWpkUDQYqDBBZEmbo8/edit#gid=784536737";
				DrawLinkRect("xls表單範例",10,"#000000",array($rect[0],$rect[1],100,20),"#ccffaa",$Link,$border,1);
				echo  "<form method=post enctype=multipart/form-data action=".$URL.">";
			    $input="<textarea name=txt cols=90 rows=12></textarea>";
			    echo "<input type=hidden name=EditSn value='".$sn."'   >";
			    DrawInputRect_size("貼上execl剪貼",12,"#ffffff",$rect[0],$rect[1]+20,500,100,$BGcolor,$WorldAlign,$input);
				$submitP="<input type=submit name=submit value=上傳表單  style= font-size:12px; >";
                DrawInputRect("",8 ,"#ffffff",array($rect[0]+670,$rect[1]+300,200,$rect[3]), $colorCodes[4][2],"top",$submitP);
				echo "</form>";
	  }
	 function UpForm(){ //上傳詳細表單
	          require_once   'Apis/xls2mysql20Api.php';
		      $sn=$_POST["EditSn"];
			  global  $data_library,$tableName,$OutCosts,$DetailFormName;
			  global  $URL;
			  echo "Upform";
              $tables=returnTables($data_library,$DetailFormName);
			  //刪除已登錄表單
			  $baseT=getMysqlDataArray($DetailFormName); 
			  $baseT2=filterArray($baseT,0,"outs");
			  $base=filterArray($baseT2,1,$sn); 
			  for($i=0;$i<count($base);$i++){
				 $WHEREtable=array("OutsSn","sn");
				 $WHEREData=array($sn,$base[$i][2]);
				 $stmt= MakeDeleteStmt($DetailFormName,$WHEREtable,$WHEREData);
				  SendCommand($stmt,$data_library);
			  }
			  //新增detial表單
			  $txt=$_POST['txt'];
			  $data=getTxtArray($txt);
			  $datas=filterArray( $data,0,"outs");
			  $WHEREtable=array();
			  for($i=0;$i<count($tables);$i++){
			      array_push($WHEREtable,$tables[$i]);
			  }
	 		  for($i=0;$i<count($datas);$i++){
				  $WHEREData=returnDatafix($datas[$i],$sn,($i+1),$WHEREtable); 
				  $stmt=  MakeNewStmt($DetailFormName,$WHEREtable,$WHEREData);
				  SendCommand($stmt,$data_library);
			  }
	 }
	 function returnDatafix($data,$sn,$sort,$WHEREtable){ //返回資料
	          $t=array();
			  for($i=0;$i<count($WHEREtable);$i++){
				  $up=$data[$i];
				  if($i==1)$up=$sn;
				  if($i==2)$up=$sort;
				  if($i==0)$up="outs";
				  echo $WHEREtable[$i].">".$up;
			      array_push($t,$up);
			  }
			  return $t;
	 }
     function AddNewMysqlData(){  //新增資料
	          global  $data_library,$tableName,$OutCosts;
	          global  $URL;
			  //外包基礎資料
			  global  $outsBaseData,$outsBaseSelects,$outs;
		              $selectOut=$_POST['selectOut'];
				      $code=  getOutCode($selectOut);
					  $OutData=filterArray($outs,1, $code); 
					  echo $_POST['selectOut'];
					  echo "[".$code."]";
			          $sn=$_POST['sn'];
				      $tables=returnTables($data_library, $tableName);
					  $WHEREtable=array();
				      $WHEREData=array();
					  $Usarr=returnUs();
		              for($i=0;$i< count( $tables);$i++){
						   $tmp=$_POST[$tables[$i]]; 
						   if($tables[$i]=="outsourcing") $tmp=$OutData[0][15];
						   if($tables[$i]=="contact") $tmp=$OutData[0][16];
						   if($tables[$i]=="country") $tmp=$OutData[0][4];
						   if($tables[$i]=="outcode") $tmp=$OutData[0][1];
						   if($tables[$i]=="state") $tmp=date("Y/m/d");
						   if($tables[$i]=="principal") $tmp="黃謙信";
						   if($tables[$i]=="usdollar") $tmp= $Usarr[0];
						   if($tables[$i]=="baseCurrency") $tmp= $Usarr[1];
				           array_push($WHEREtable, $tables[$i] );
					       array_push($WHEREData, $tmp);
		              }
					  $stmt=   MakeNewStmt($tableName,$WHEREtable,$WHEREData);
					 // echo $stmt;
				      SendCommand($stmt,$data_library);
                     
	 }
	 function AddNewPregressDataData(){ //未實裝
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
			      //  echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";
	   }
     function UpformCheck(){  //上傳詳細內容
	           require_once   'Apis/xls2mysql20Api.php';
		       echo " UpformCheck";
			   $sn=$_POST["EditSn"] ;
			   global $URL;
			   global $txt;
			   global $FormRect;
			   $txt=$_POST['txt'];
			   $data=getTxtArray( $txt);
			   $data_library=$data[0][1];
			   $tableName=$data[0][0];
	           $tables=returnTables($data_library ,$tableName);
		       $collect=filterArray( $data,0,"outs");
		 	   $fontColor="#222222";
			   $BGcolor="#eeeeee";
			   EditOutsForm();
			   echo  "<form method=post enctype=multipart/form-data action=".$URL.">";
			   DrawDetialList($collect,$fontColor,$BGcolor);
			   echo "<input type=hidden name=EditSn value='".$sn."'   >";
		       echo "<input type=hidden name=txt value='".$txt."'   >";
			   $submitP="<input type=submit name=submit value=確定上傳表單  style= font-size:12px; >"; 
			   $rect=$FormRect;
               DrawInputRect("",8 ,"#ffffff",array($rect[0]+470,($rect[1]+(count($collect)+1)*22),200,$rect[3]), "#ffcccc","top",$submitP);
			   echo "</form>";
 
	 }	
	 function DrawDetialList($outsDetial,$fontColor,$BGcolor){
		       global $DetailFormName, $FormRect,$FormList,$FormListsize,$FormTitle;
			   global $Outstotal;
			   global $exchangeRate;
			   $Outstotal=0;
			   $rect=$FormRect;
			   Drawsingel($FormTitle[0],$FormList, $rect,"#ffffff","#000000");
			   $rect[1]+=22;
	  		   for($i=0;$i<count($outsDetial);$i++){
			        $Outstotal+=$outsDetial[$i][8];
					if($outsDetial[$i][11]!="")$exchangeRate=$outsDetial[$i][11];
			        Drawsingel($outsDetial[$i],$FormList, $rect,$fontColor,$BGcolor);
				    $rect[1]+=22;
			   }
	  }
	   function  UpExchangeRate(){
		   echo "xxx";
	           global  $outsn,$datas;
			   global $data_library ;
			   global $exchangeRate;
			   global $BaseURL,$BackURL;
			   global $backURLval;
			   $outsn=$_POST["outsn"];
			 //  echo ">".$outsn;
	           $WHEREtable=array( "data_type", "OutsSn"   );
		       $WHEREData=array( "outs",$outsn  );
			   $Base=array("exchangeRate");
		       $up=array($exchangeRate);
			   $stmt= MAPI_MakeUpdateStmt(  $data_library,"outsdetail",$Base,$up,$WHEREtable,$WHEREData);
			   SendCommand($stmt,$data_library);
			   //上傳截圖
			   if($_FILES['exchangeRatepic']["name"]!=null  ){
				   $temp = explode(".", $_FILES['exchangeRatepic']["name"]);
				   $path="Outsourcing/exchangeRate/".$outsn.".".$temp[1];
				   echo $path;
			       move_uploaded_file($_FILES['exchangeRatepic']["tmp_name"], $path);  
				   $Npath="Outsourcing/exchangeRate/".$outsn.".png";
				   $cmd="convert   $path       $Npath ";
				   exec($cmd);
			   }
			  //  echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";
			 //  echo " <script language='JavaScript'>window.location.replace('".$backURLval."&sn=".$outsn."')</script>";
	 }
?>

<?php //判斷幣別
     function returnUs( ){
		 	  global $CurrencyNTtoUs;
		      global $CurrencyCNYtoUs;
              if($_POST["usdollar"]!="")return array( $_POST["usdollar"],"usdollar");
			  if($_POST["nt"]!=""){
				  $us= $_POST["nt"]*$CurrencyNTtoUs;
				  return array( $us,"nt");
			  }
			  if($_POST["CNY"]!=""){
				  $us= $_POST["CNY"]*$CurrencyCNYtoUs;
				  return array( $us,"CNY");
			  }
	 }
     function Setcurrency($sn){ //輸入合約序號 紀錄基礎幣別 計算其他幣別
	          global $data_library,$tableName ;
			  global $OutCosts;
			  $currentSort=filterArray( $OutCosts,1,$sn)[0] ;
			  if( $currentSort[10]!="")return;
			  global $CurrencyNTtoUs;
		      global $CurrencyCNYtoUs;
		      $WHEREtable=array("data_type","sn");
			  $WHEREData=array("cost",$sn);
			  $Base=array("usdollar","baseCurrency");
		      $up=array();
		  	  //基本幣別台幣
			  if( $currentSort[9]!=""){
				  $us=$currentSort[9]*$CurrencyNTtoUs;
			      $up=array($us,"nt");
			  }
			  //基本幣別人民幣
			   if( $currentSort[11]!=""){
			  	  $us=$currentSort[11]*$CurrencyCNYtoUs;
			      $up=array($us,"CNY");
			  } 
		   	 $stmt= MAPI_MakeUpdateStmt(  $tableName,$Base,$up,$WHEREtable,$WHEREData);
			 
			  echo $stmt;
			 SendCommand($stmt,$data_library);		
		
	 }
?>