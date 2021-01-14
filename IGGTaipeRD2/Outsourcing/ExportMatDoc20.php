<?php 
global $Exporttype ;
 	   $Exporttype=$_POST['Exporttype'];
 global $sn;
	   $sn=$_POST['sn'];
  global  $DetailFormName, $OutsFormName;
	     $selectProject=$_POST["selectProject"];
		 $DetailFormName="outsdetail_".$selectProject;
		 $OutsFormName="outcost_".$selectProject;
global $filename;
header("Content-type: text/html; charset=charset=unicode");
$filename="材料2：申请资料.doc";
if($Exporttype=="mat4")$filename="材料4：需求描述模板.doc";
if($Exporttype=="Demand")$filename=" 需求明细.doc";
 header("Content-Type:application/ms-word");  
//$doc = new VsWord(); 
//$doc->saveAs($fileName);
//header("Content-Type:application/vnd.openxmlformats-officedocument.wordprocessingml.document"); 
header("Content-Disposition:attachment;filename=".$filename);
header("Pragma:no-cache");
header("Expires:0");
?> 

<?php
     // require_once  dirname(dirname(__FILE__)).'/PubApi.php';
 	  global $filename;
	  require_once 'xlsApiv2.php';
      setBGColor();
	  DefineData();
	 // readfile($filename);`
	  function DefineData(){
         global $sn;
         global $Exporttype;
		 global $outsDetial,$OutsCost, $outsData;
		 global $project, $principal;
	     global $DetailFormName, $OutsFormName;
		 $project="VT";
		 $principal="黃謙信";
		 //詳細表單
		 $outsDetialT=getMysqlDataArray($DetailFormName);
		 $outsDetial= filterArray( $outsDetialT,1,$sn);
		 $outsDetial= sortArrays($outsDetial ,"2" ,"true");
 
		 //外包單
		 $OutsCostT=getMysqlDataArray($OutsFormName);
		 $OutsCost= filterArray(  $OutsCostT,1,$sn);
		 //外包名單
		 $code=$OutsCost[0][15];
		 $outsDataT=getMysqlDataArray("outsourcing");
		 $outsData=filterArray(  $outsDataT,1,$code);
		 //檢查中國個人多美金欄位
 		 $CurrencyType=$baseData[currency];
	     global $Currencytype;
	     if($baseData["currency"]=="人民幣" && $baseData["studio"]="個人"){
		    $Currencytype="CNY2USD";
	     }
 
 
 
		 //分類
		 if($Exporttype=="Demand")createDemand();
         if($Exporttype=="mat2")setMat2Data();
		 if($Exporttype=="mat4")printMat4();
	
	  }
	  function setMat2Data(){
	     global $sn;
		 global $outsDetial;
		 global $outsDetial,$OutsCost, $outsData; 
		 global $baseData,$demand;
		 $tmp= explode("/",$OutsCost[0][14]);
         printTitle( $tmp[0],$tmp[1],$tmp[2]);
		 //內容
		  $infoArray=array();
		 for($i=0;$i<count($outsDetial);$i++){
		 $tmp=array( $outsDetial[$i][4], $outsDetial[$i][7]."小時", $outsDetial[$i][9]);
		 array_push($infoArray,$tmp);
		 }
         ptintTable($infoArray);
		 //金額
		 $cash=0;
		 for($i=0;$i<count($outsDetial);$i++){
		 $cash+=$outsDetial[$i][8];
		 }
		 	 echo "<p>　</p>";  
		 $Currency=$outsData[0][30];
		 printPay($cash,$Currency) ;
		 
         //付款資訊
		 $payinfo=array(
		        array(
	           "AccountName"=>"开户名",
			   "AccountBank"=>"开户行",
			   "AccountNumber"=>"银行账号",
			   "BankAdress"=>"银行地址",
			   "PartyB"=>"乙方",
			   "Contact"=>"联系人",
			   "Tel"=>"联系电话",
			   "Adress"=>"公司地址",
			   "ID"=>"公司统一社会信用代码",
			    "BankSwiftCode"=>"Bank Swift Code: "
			   ),
			      array(
	           "AccountName"=>$outsData[0][21],
			   "AccountBank"=>$outsData[0][22],
			   "AccountNumber"=>$outsData[0][23],
			   "BankAdress"=>$outsData[0][24],
			   "PartyB"=>$outsData[0][25],
			   "Contact"=>$outsData[0][26],
			   "Tel"=>$outsData[0][27],
			   "Adress"=>$outsData[0][28],
			   "ID"=>$outsData[0][29],
			    "BankSwiftCode"=>$outsData[0][31]
			   ),
	     );
		 printPayPaymentInformation($payinfo);

		 
	      
       }   
?>

<?php
     function setBGColor(){
	     echo "<title></title>";
         echo "<body bgcolor=#ffffff>";
	 }
     function printTitle($year,$m,$d){
         echo "<div style=font-size:22px ; align=center >";
         echo "《VT》项目美术外包申请";
         echo "</div>";
         echo "</p>";
         echo "外包内容以及完成时间";
         echo "</p>";
         echo "内容 ：VT项目美术外包";
         echo "</p>";
         echo "合同时间：自 ".$year." 年".$m."月".$d."日开始。";
         echo "</p>";
	 }		 
	 function ptintTable($infoArray){
		 $ListTitle=array("制作描述","制作类型","工时","开始时间");
	     $ListSize=array("100","300","100","100");
		 $color=array("#ffffff","#ffffff","#ffcccc");
		 $LineStr="美术外包";

		 echo "<table  border=1 cellpadding=1 cellspacing=0  bordercolor=#000000 >";
		 echo "　<tr>";
		 for($i=0;$i<count($ListTitle);$i++){
		    echo "<td bgcolor=#000000 width=".$ListSize[$i].">".$ListTitle[$i]."</td>";
		 }
         echo "　</tr>";
		 echo "<tr>";
		 //內容
		 echo "<td rowspan=".count( $infoArray)." bgcolor=#ffcccc>".$LineStr."</td>";
		 for($i=0;$i<count($infoArray);$i++){
		    echo "<td bgcolor=".$color[0]." width=".$ListSize[1].">".$infoArray[$i][0]."</td>";
			  echo "<td bgcolor=".$color[1]." width=".$ListSize[2].">".$infoArray[$i][1]."</td>";
			    echo "<td bgcolor=".$color[2]." width=".$ListSize[3].">".$infoArray[$i][2]."</td>";
				   echo "</tr>";
				   if($i!==count($infoArray)-1)echo"<tr>";
		 }
		 echo "</tr>";
         echo "</table>";
		 echo "<p>　</p>";  
			 
	 }
     function printPay($cash,$Currency) {
		    echo "</p>";
            echo "外包金额以及付款步骤: 一次性付清";
            echo "</p>";
            echo "总金额：".$cash.$Currency;
            echo "<p>　</p>";  
	 }
	 function printPayPaymentInformation($payinfo){
	        $PayPayC1=array("开户名：","开户行","银行账号","银行地址","Bank Swift Code: ");
	        $paypay1=array("AccountName","AccountBank","AccountNumber","BankAdress","BankSwiftCode");
            $PayPayC2=array("乙方","联系人","联系电话: ","公司地址","公司统一社会信用代码");
 	        $paypay2=array("PartyB","Contact","Tel","Adress","ID");		
            echo "乙方账户(填写英文)";			
	        for ($i=0;$i<count($paypay1);$i++){
			     echo "</p>";
			     echo  $payinfo[0][$paypay1[$i]]."：".$payinfo[1][$paypay1[$i]];
	        }
		 	 echo "<p>　</p>";  
		    echo "乙方联系方式（填写中文）";
            for ($i=0;$i<count($paypay2);$i++){
			     echo "</p>";
			     echo  $payinfo[0][$paypay2[$i]]."：".$payinfo[1][$paypay2[$i]];
	        }
				 echo "<p>　</p>";  
            echo   "附件：营业执照扫描件 /身份证扫描件";
	 }

?>

<?php //mat4
	 function createDemand(){
	      global $outsDetial,$OutsCost, $outsData;
		  global $sn;
		  global $project ,$principal;
		  echo "<table  border=1 cellpadding=1 cellspacing=0 bordercolor=#000000 >";
		  echo "<tr>";
		  echo "<td width=5%>项目</td>";
		  echo "<td width=12%>$project</td>";
		  echo "<td width=38%>申请人</td>";
		  echo "<td width=12%>$principal</td>";
		  echo "<td width=8%>时间</td>";
		  echo "<td width=20%>".$OutsCost[0][14]."</td>";
		  echo "</tr>";
		  echo "<tr>";
		  echo "<td>事由</td>";
	      echo "<td colspan=5>项目制作</td>";
		  echo "</tr>";
		  echo "<tr>";
		  $row=4+count($outsDetial);
		  echo "<td rowspan=$row>需求清单</td>";
		  echo "<td>内容</td>";
		  echo "<td>需求明细</td>";
		  echo "<td colspan=3>效果图例</td>";
		  echo "</tr>";
		  echo "<td>";
		  echo collectWork($outsDetial);
		  echo "</td>";
		  echo "<td>";
		
		  for($i=0;$i<count( $outsDetial);$i++){
		
		      $msg=($i+1).".".$outsDetial[$i][4].$outsDetial[$i][5];
		      echo "<p>".$msg."</p>";
		  }
		  echo "</td>";
		  echo "<td colspan=3>";
		  for($i=0;$i<count( $outsDetial);$i++){
		      $msg=($i+1).".".$outsDetial[$i][4] ;
		      echo "<p>".$msg."</p>";
		      $pic="pic0.png";
			//  $pic="Outsourcing/SortPic/".$sn."/spic".$i.".jpg" ;
			  echo $pic;
			  $pic64=create_data_uri("pic0.png", "png");
			  
			  echo "<img  src=".$pic64.">";
		  }
	      echo "</td>";
	      echo "</tr>";
		  $outs=$outsData[0][15];
		  
		  echo "<tr><td>　</td><td> </td><td colspan=3> </td></tr>";
		    $total=0;
		  for($i=0;$i<count( $outsDetial);$i++){
			   $total+=$outsDetial[$i][8];
			   echo "<tr>";
		       echo "<td>$outs</td>";
			   echo "<td>".$outsDetial[$i][4].$outsDetial[$i][5]."</td>";
			   echo "<td colspan=3>".$outsDetial[$i][8].$outsData[0][30]."</td>";
		       echo "</tr>";
		  }
		  echo "<tr><td>總預算</td><td> </td><td colspan=3>".$total.$outsData[0][30]."</td></tr>";
	      echo "</table>";		
          //印明細
      	
	 }
	 function printMat4(){
	      global $outsDetial,$OutsCost, $outsData;
		  global $sn;
		  echo "<table  border=1 cellpadding=1 cellspacing=0 bordercolor=#000000 >";
		  echo "<tr>";
		  echo "<td rowspan=2 width=10%>需求清单</td>";
		  echo "<td width=15%>内容</td>";
		  echo "<td width=35%>需求明細</td>";
		  echo "<td width=35%>效果图例</td>";
		  echo "</tr>";
		  echo "<tr>";
		  echo "<td>";
		  echo collectWork($outsDetial);
		  echo "</td>";
		  echo "<td>";
		  for($i=0;$i<count( $outsDetial);$i++){
		      $msg=($i+1).".".$outsDetial[$i][4].$outsDetial[$i][5];
		      echo "<p>".$msg."</p>";
		  }
		  echo "</td>";
		  echo "<td>";
		  for($i=0;$i<count( $outsDetial);$i++){
		      $msg=($i+1).".".$outsDetial[$i][4] ;
		      echo "<p>".$msg."</p>";
		      $pic="pic0.png";
			//  $pic="Outsourcing/SortPic/".$sn."/spic".$i.".jpg" ;
			  echo $pic;
			  $pic64=create_data_uri("pic0.png", "png");
			  echo "<img  src=".$pic64.">";
		  }
	      echo "</td>";
	      echo "</tr>";
	      echo "</table>";
	 }
 
?>
<?php //需求明细.doc
       function PrintDemandv2(){
	   	  global $outsDetial,$OutsCost, $outsData;
		  global $sn;
		  global $project ,$principal;
		 // echo "<table  border=1 cellpadding=1 cellspacing=1  bordercolor=#000000 >";
		   echo "<table   border=1  cellpadding=1 cellspacing=0  bordercolor=#000000 >";
		  echo "<tr>";
		  echo "<td width=10% height=100>内容</td>";
		  echo "<td width=20%>".$project."</td>";
		  echo "<td width=30%>'申请人'</td>";
		  echo "<td width=10%>".$principal."</td>";
		  echo "<td width=5%>'时间'</td>";
	      echo "<td width=25%>".$OutsCost[$sn][14]."</td>";
	   }
?>

<?php //圖片轉code
       function create_data_uri($source_file, $mime_type) {
                $encoded_string = base64_encode(file_get_contents($source_file));
                 return('data:image/' . $mime_type . ';base64,' . $encoded_string);
              }
			  /*
	   function sortArrays($BaseArray ,$ArrayNum ,$forwardBool){
  		  $newArray=array();
		  $lastSn=  getLastSN2($BaseArray,$ArrayNum);
      		 
		 if($forwardBool=="true"){//正向
		  	  for($i=0;$i<= $lastSn;$i++){
                 $tmpArray= GetArraySn($BaseArray, $ArrayNum ,$i);
				 if(count($tmpArray)>0)$newArray=  array_merge( $newArray,$tmpArray); 
			  } 
		  }
		  if($forwardBool=="false"){//逆向
		  	  for( $i=$lastSn;$i>0;$i--){
                 $tmpArray= GetArraySn($BaseArray, $ArrayNum ,$i );
				 if(count($tmpArray)>0)$newArray=  array_merge( $newArray,$tmpArray); 
			  }
		  }
	      return  $newArray;
	  }  
	   function getLastSN2($SQLData,$SnNum){
	      $lastSN=0;
		  for($i=0;$i<count($SQLData);$i++){
		 
		  if($SQLData[$i][$SnNum]>$lastSN)$lastSN=$SQLData[$i][$SnNum];
		  }
		  return $lastSN;
	   }
	  function GetArraySn($BaseArray, $ArrayNum ,$sn ){
			  $newArray=array();
		      for($i=0;$i<count($BaseArray);$i++){
			     if($BaseArray[$i][ $ArrayNum]==$sn) {
					  array_push (  $newArray,$BaseArray[$i]);
				 }  
			  }
			  return $newArray;
	   }
	   */
?>
 
 

