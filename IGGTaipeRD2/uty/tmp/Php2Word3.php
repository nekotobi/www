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





echo "<table border=1 align=center>";
echo "　<tr>";
echo "<td  bgcolor=pink>這裡可以放表格內容</td>";
echo "</tr>";
echo "</table>";
echo "<br style='page-break-before:always'>" ;
echo "xx";

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
 
?>

 

