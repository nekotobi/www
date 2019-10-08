<?php 
global $Exporttype ;
header("Content-type: text/html; charset=charset=unicode");
$filename="材料2：申请资料.doc";
header("Content-Disposition: attachment; filename=" . $filename); 
header("Content-Type:application/doc");  
header("Content-Disposition:attachment;filename=".$filename);
header("Pragma:no-cache");
header("Expires:0");
?> 


 

<?php
      require_once  dirname(dirname(__FILE__)).'/PubApi.php';
      setBGColor();
	  DefineData();
	 
	  function DefineData(){
         global $sn;
         global $Exporttype;
		 global $outsDetial,$OutsCost, $outsData;
		 //詳細表單
		 $outsDetialT=getMysqlDataArray("outsdetail");
		 $outsDetial= filterArray( $outsDetialT,1,$sn);
 
		 //外包單
		 $OutsCostT=getMysqlDataArray("fpoutsourcingcost");
		 $OutsCost= filterArray(  $OutsCostT,1,$sn);
		 //外包名單
		 $code=$OutsCost[0][15];
		 $outsDataT=getMysqlDataArray("outsourcing");
		 $outsData=filterArray(  $outsDataT,1,$code);
		 
		 //分類
         if($Exporttype=="mat2")setMat2Data();
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
			   "ID"=>"公司统一社会信用代码"
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
			   "ID"=>$outsData[0][29]
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
         echo "《FP》项目美术外包申请";
         echo "</div>";
         echo "</p>";
         echo "外包内容以及完成时间";
         echo "</p>";
         echo "内容 ：FP项目美术外包";
         echo "</p>";
         echo "合同时间：自 ".$year." 年".$m."月".$d."日开始。";
         echo "</p>";
	 }		 
	 function ptintTable($infoArray){
		 $ListTitle=array("制作描述","制作类型","工时","开始时间");
	     $ListSize=array("100","300","100","100");
		 $color=array("#ffffff","#ffffff","#ffcccc");
		 $LineStr="美术外包";

		 echo "<table  border=1 cellpadding=1 cellspacing=1  bordercolor=#000000 >";
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
     function  printPay($cash,$Currency) {
		    echo "</p>";
            echo "外包金额以及付款步骤: 一次性付清";
            echo "</p>";
            echo "总金额：".$cash.$Currency;
            echo "<p>　</p>";  
	 }
	 function printPayPaymentInformation($payinfo){
	        $PayPayC1=array("开户名：","开户行","银行账号","银行地址");
	        $paypay1=array("AccountName","AccountBank","AccountNumber","BankAdress");
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

 

