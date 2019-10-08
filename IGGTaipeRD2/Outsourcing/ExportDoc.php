<?php 
header("Content-type: text/html; charset=charset=unicode"); //頁面編碼
header("background-color:#ffdddd");
header("Content-Type:application/doc");  
header("Content-Disposition:attachment;filename=".mb_convert_encoding("word_filename","gbk","utf8").".doc");   //設定word檔名
header("Pragma:no-cache");
header("Expires:0");
?> 


 

<?php
     require_once  dirname(dirname(__FILE__)).'/PubApi.php';
      setBGColor();
	  printTitle();
      ptintTable();
	  $cash="245500";
	  $Currency="人民幣";
	  printPay($cash,$Currency);
	   printPayPaymentInformation();
	   
?>

<?php
     function setBGColor(){
	     echo "<title></title>";
         echo "<body bgcolor=#ffffff>";
	 }
     function printTitle(){
	     $year="2019";
         $m="9";
         $d="27";
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
	 function ptintTable(){
		 $ListTitle=array("制作描述","制作类型","工时","开始时间");
	     $ListSize=array("100","300","100","100");
		 $color=array("#ffffff","#ffffff","#ffcccc");
		 $LineStr="美术外包";
		 $infoArray=array(array( "蘿賓特效製作","72","2019/9/27"),array( "特蕾沙特效製作","72","2019/9/27"));
		// echo "<table  style=border:3px #FFAC55 double;padding:5px; rules=all cellpadding=5;>";
		  echo "<table  border=1 cellpadding=1 cellspacing=1  bordercolor=#000000 >";// border-collapse: collapse  border=1 align=center>";
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
	 function printPayPaymentInformation(){
	  $PayPayC1=array("开户名：","开户行","银行账号","银行地址");
	  $paypay1=array("AccountName"."AccountBank","AccountNumber","BankAdress");
      $PayPayC2=array("乙方","联系人","联系电话: ","公司地址","公司统一社会信用代码");
 	  $paypay2=array("PartyB"."Contact","Tel","Adress","ID");				
 
 

	 }
	 
	 
	 
	 function printPayPaymentInformation_bak(){

		$bankAccount="Shanghai Fierygame Network Technology Co. LTD";
		$AccountBank="China Construction Bank Shanghai Changqiao Branch";
		$account="3105 0173 4600 0000 0258	";
		$BankAdress="1018-1028 Baise Road, Shanghai";
		$outsName="上海大推网络科技有限公司";
		$Contact="马赫然";
		$Tel="13166949166";
		$Adress="上海市普陀区怒江北路399号 新曹杨科技大厦";
		$IDNumber="91310114MA1GUD336J";
	    echo "乙方账户(填写英文)";
		echo "</p>";
        echo "开户名：".$bankAccount;   
        echo "</p>";			
        echo "开户名：". $AccountBank;
        echo "</p>";
	    echo "银行账号：".$account;
        echo "</p>";
 		echo "银行地址：".$BankAdress;
		echo "</p>";
        echo "乙方联系方式（填写中文）";
		echo "</p>";
        echo "乙方：".$outsName;
		echo "</p>";
        echo "联系人：".$Contact;
		echo "</p>";
        echo "联系电话:". $Tel;
		echo "</p>";
        echo "公司地址：".$Adress; 
		echo "</p>";
        echo "公司统一社会信用代码/身份证号码".$IDNumber;
        echo "</p>";
        echo   "附件：营业执照扫描件 /身份证扫描件";
	 }


?>

 

