
<?php  //產生基礎文件
	 function makeDoc(){
	      $filename ="test.doc";
	      ob_end_clean();
          header("Content-type: text/html; charset=utf-8");
          header("Content-Type: application/ms-word");
          header("Content-Disposition: attachment;filename=".$filename);
		  $contents = "number \t 網頁現在都是採用 \t 職務 \t \n";
		  $contents2  = iconv("UTF-8","BIG5", $contents);
          echo $contents2;
	 }
	 function makeExecl(){
	     $filename ="test4.xls";
	     ob_end_clean();
         header("Content-type: text/html; charset=utf-8");
         header("Content-Type: application/vnd.ms-excel");
         header("Content-Disposition: attachment;filename=".$filename);
		 $contents = "number \t 網頁現在都是採用 \t 職務 \t \n";
		 $contents2  = iconv("UTF-8","BIG5", $contents);
         echo $contents2;
	 }
?>