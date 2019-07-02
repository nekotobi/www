<?php
    include('PubApi.php');
    include('CalendarApi.php');  
    include('mysqlApi.php');
    include('scheduleApi.php');
	UpPicEdit();
    UpPic();
?>

<?php  //上傳縮圖
    function UpPic(){
	     global $submit;
	         if($submit=="")return; 
             echo $submit;			 
			 echo "xx";
			 echo $_FILES["file"]["name"];
			 echo $_FILES["file"]["tmp_name"];
			 $path="ResourceData/test.png";
			 $spicpath="ResourceData/tests.png";
			 move_uploaded_file($_FILES["file"]["tmp_name"],$path);
			 $cmd="convert    $path    -flatten -resize 64  $spicpath";
			 exec($cmd);
	}
    function UpPicEdit(){
		     global $submit;
			 if($submit!="")return;
		     $BackURL="test.php";
		     $x=200;
		     $y=100;
			 $w=300;
			 $h=300;
	         DrawPopBG($x,$y,$w,$h,"測試上傳資料" ,"12",$BackURL);
	         echo   "<form id='EditRes'  name='Show' action='".$BackURL."' method='post'  enctype='multipart/form-data'>";
			 $input="<input type=file name=file	id=file  size=60   >";
			 $y+=30;
		     DrawInputRect("圖檔","12","#ffffff", $x  ,$y,420,20, $colorCodes[4][2],"top", $input);
			 
			 
	         $submit ="<input type=submit name=submit value=上傳>";
			 DrawInputRect("","12","#ffffff",($x+150),$y+100 ,120,18, $colorCodes[4][2],"top",$submit );
			 
			 
	         echo "</form>";
	}



?>



<?php  //暫時

     function    testWarring(){//測試公式
		          global $VacationDays; //年 月 日
	              $startDay=array(2019,6,19);
				  $nowDayArray=array(2019,7,1);
				  $passDays= getPassDays($startDay,$nowDayArray);
				  echo   $passDays;
	
	 }
?>