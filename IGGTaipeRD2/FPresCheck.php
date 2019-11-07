<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>FP資源索引v2</title>
</head>
<?php //主控台
    include('PubApi.php');
   DefineBaseData();
   CookieSet();
   ShowButton();
   ListContent();
   CheckUp();
?>

<?php //title
     function CookieSet(){
		      global $BaseURL;
		      global  $CookieArray,$MysQlArray;
		 	  $CookieArray=array("type1","type2");
			  $MysQlArray=array(0,12);
			  setcookies($CookieArray,$BaseURL);
	          SetGlobalcookieData( $CookieArray);
			 // CheckCookie($CookieArray);
			  
	 }
     function DefineBaseData(){
		      global $BaseURL;
		      $BaseURL="FPresCheck.php";
	          $data_library= "iggtaiperd2";
		      $tableName="fpresdata"; 
			  //$stmp= getMysqlDataArray("fpschedule");	
			  global $ResData;
			  $ResData=getMysqlDataArray( $tableName);	
		      //$ScheduleData  =  filterArray($stmp,0,"data");
		      //$ScheduleDataTitle=filterArray($ScheduleData,5,"工項");
			  global $type1Title;
			  $type1Title=array( 
			           array("英雄","hero"),
					   array("怪物","mob"),
					   array("魔王","boss")
			   );
			  global $type2Title;
			   $type2Title=array( 
			           array("m1","m1"),
					   array("m2","m2"),
					   array("m3","m3"),
					   array("m4","m4"),
					   array("all","all"),
			   );;
			  global $CookieArray;
		
			
	 } 
     function ShowButton(){
		      global $type1Title,$type2Title;
			  $Rect=array(20,20,100,20);
			  DrawButton($Rect,$type1Title,"type1" );
			  $Rect=array(20,50,100,20);
		      DrawButton($Rect,$type2Title,"type2" );
			  global $BaseURL;
			  $ValArray=array(array("Up","ViewPic"));
			  $Rect=array(20,80,100,20);
			  sendVal($BaseURL,$ValArray,"submit","開啟編輯",$Rect, 12, "#eeaaaa", "#ffffff" );
	 }
	 function DrawButton($Rect,$btArray,$arraytype,$BgColor="#000000",  $fontColor="#ffffff"){
		    global $BaseURL;
		    for($i=0;$i<count( $btArray);$i++){
			      $BGC=$BgColor;
			      if($_COOKIE[$arraytype]==$btArray[$i][1])$BGC="#ee0000";
				  $valArray=array(array($arraytype,  $btArray[$i][1]));
				  $SubmitName="submit";
				  $SubmitVal= $btArray[$i][0] ;
				  $Rect[0]+=110;
			 	  sendVal($BaseURL,$valArray,$SubmitName,$SubmitVal,$Rect,10, $BGC,$fontColor,"true");
			  }
	 
	 }
?>

<?php //List
     function getData(){
		      global  $CookieArray,$MysQlArray;
	          global $ResData;
			  $t1=$_COOKIE[$CookieArray[0]];
			  $t2=$_COOKIE[$CookieArray[1]];
			  $data=filterArray( $ResData,0,$t1);
			  if($t2!="all") $data=filterArray( $data,$MysQlArray[1],$t2);
			  $data= SortList( $data,3);
			  return $data;
	 
	 }
    
     function ListContent(){
	          global  $CookieArray,$MysQlArray;
			  global $ResData;
              for($i=0;$i<count($CookieArray);$i++)  if($_COOKIE[$CookieArray[$i]]=="")return;
			  $data = getData();
			  $size= filterArray( $ResData,0,"size");
			  $title= filterArray( $ResData,0,"name");
			  $ListArray=array(2,3,4);
			  $Rect=array(20,120,90,20);
			  echo   "<form id='EditRes'  name='Show' action='".$BackURL."' method='post'  enctype='multipart/form-data'>";
 		      for($i=0;$i<count($data);$i++){
				  //內容
			     DrawRect("",11,$fontColor,$Rect[0],$Rect[1],200,100,"#000000");
			     DrawSingle($data[$i],$Rect,$ListArray,$size[0]);
				 //上傳圖檔
			     $n="pic_".$i;
				 $c="c_".$i;
				 if($_POST['Up']=="ViewPic"){
				      DrawRect("",11,$fontColor,$Rect[0]+100,$Rect[1],200,100,"#000000");
				 echo "<input type=hidden name=".$c." value=".$data[$i][2].">";
				 $input="<input type=file name=".$n."	id=file  size=10   >";
				 DrawInputRect("代表圖檔"." ","12","#ffffff", $Rect[0]+93, $Rect[1]  ,1220,20, $colorCodes[4][2],"top", $input);
				 //max檔
				 $n="Max_".$i;
				 $input="<input type=file name=".$n."	id=file  size=10   >";
				 DrawInputRect("3D檔"." ","12","#ffffff", $Rect[0]+93, $Rect[1]+22  ,1220,20, $colorCodes[4][2],"top", $input);
				 
				 //ani檔
				 $n="Ani_".$i;
				 $input="<input type=file name=".$n."	id=file  size=10   >";
				 DrawInputRect("動畫檔"." ","12","#ffffff", $Rect[0]+93, $Rect[1]+43  ,1220,20, $colorCodes[4][2],"top", $input);
			    //VFX檔
				 $n="VFX_".$i;
				 $input="<input type=file name=".$n."	id=file  size=10   >";
				 DrawInputRect("特效檔"." ","12","#ffffff", $Rect[0]+93, $Rect[1]+65  ,1220,20, $colorCodes[4][2],"top", $input);
				 }
				 
				 $Rect[1]+=104;
			
			  }
			 if($_POST['Up']!="") $submit ="<input type=submit name=submit value=上傳>";
	          DrawInputRect("","12","#ffffff", 340 ,  120,100,20, $colorCodes[4][2],"上傳",$submit );
			  echo "</form>";
	
	 }
     function DrawSingle ($Base,$Rect,$ListArray,$size){
		      global $type1;
              $fontColor="#000000";
			  $BgColor="#ffffff";
		  	  $Rect[0]+=2;
			   $Rect[1]+=2;
              DrawRect_Layer($Base[2],12,$fontColor,$Rect,$BgColor,$Layer);
			  $Rect[1]+=22;
			  DrawRect_Layer($Base[3],12,$fontColor,$Rect,$BgColor,$Layer);
			  $Rect[1]+=22;
			  //max
			  $max="ResourceData/".$type1."/model/".$Base[2] ;
			  $file=  checkfileExists( $max,"zip");
			  $pic="Pics/3D.png";
			  if ($file!=""){
			       DrawLinkPic($pic,$Rect[1] ,$Rect[0],20,20,$file);
			  }
			  //Ani
			  $Ani="ResourceData/".$type1."/Ani/".$Base[2] ;
			  $file=  checkfileExists( $Ani,"zip");
			  $pic="Pics/Ani.png";
			  if ($file!=""){
			       DrawLinkPic($pic,$Rect[1] ,$Rect[0]+22,20,20,$file);
			  }
			  //VFX
			  $VFX="ResourceData/".$type1."/VFX/".$Base[2] ;
			  $file=  checkfileExists(  $VFX,"zip");
			  $pic="Pics/VFX.png";
			  if ($file!=""){
			       DrawLinkPic($pic,$Rect[1] ,$Rect[0]+44,20,20,$file);
			  }
			  //圖檔
			  $Rect[0]+=98;
			  $Rect[1]-=44;
			  $pic="ResourceData/".$type1."/viewPic/".$Base[2].".png";
			  if( file_exists($pic)){
			      DrawLinkPic($pic,$Rect[1],$Rect[0],96,96,$Link);
			  }	
			  
	 }
	 function DrawSingle_old($Base,$Rect,$ListArray,$size){
		    for($i=0;$i<count($ListArray);$i++){
				 $s=$ListArray[$i];
				 $Rect[2]=$size[$s];
				 $fontColor="#000000";
				 $BgColor="#ffffff";
	             DrawRect_Layer($Base[$s],12,$fontColor,$Rect,$BgColor,$Layer);
				 $Rect[0]+=($Rect[2]+2);
		       } 
			 
	 }
     function checkfileExists($Path,$type){
	          if($type="Zip"){
			      if( file_exists($Path.".rar"))return $Path.".rar";
				  if( file_exists($Path.".zip"))return $Path.".zip";
				  return "";
			  }
	 }
?>

<?php //up
     function CheckUp(){
	          if($_POST['submit']=="")return;
	          if($_POST['submit']=="上傳")upfile();
	 }
     function upfile(){
			  global $type1;
			  global $BaseURL;
			  $data = getData();
			  $dir="ResourceData/".$type1;
		      $picdir=$dir."/viewPic/Base";
			  $viewDir=$dir."/viewPic";
              MakeDir($picdir);			
			  MakeDir($viewDir);		
	          for($i=0;$i<count($data);$i++){
				  $n="pic_".$i;
				  $c="c_".$i;
				  $code=  $_POST[$c];
				  //圖檔
				  if($_FILES[$n]["name"]!=""){
					 $ext = explode(".",$_FILES[$n]["name"]);
                     $filePath=$picdir."/".$code.".".$ext[1];
					// echo $filePath;
				     move_uploaded_file($_FILES[$n]["tmp_name"], $filePath);
					 $finalPath= $viewDir."/".$code.".png";
					//  echo  $finalPath;
				     $cmd="convert     $filePath    -flatten  -resize 256  $finalPath ";
					 exec($cmd);
				  }
			      //3d檔案
				   UPTypeFile($dir,"model","Max_",$i,$code);
				   UPTypeFile($dir,"Ani","Ani_",$i,$code);
			       UPTypeFile($dir,"VFX","VFX_",$i,$code);
			  }
			    echo " <script language='JavaScript'>window.location.replace('".$BaseURL."')</script>";
	 }
	 function UPTypeFile($dir,$type,$name,$i,$code){
		          $na=$name.$i ;
				  $fdir=$dir."/".$type;
				   MakeDir($fdir);	
			      if($_FILES[$na]["name"]!=""){
				     $ext = explode(".",$_FILES[$na]["name"]);
                     $filePath= $fdir."/".$code.".".$ext[1];
					 echo $filePath;
					 move_uploaded_file($_FILES[$na]["tmp_name"], $filePath);
				  }
	 }



?>
