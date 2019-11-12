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
   ListContent();
   ShowButton();
   CheckUp();
   //檢查進度
   GetCode();
?>

<?php //title
     function CookieSet(){
		      global $BaseURL;
		      global  $CookieArray,$MysQlArray;
		 	  $CookieArray=array("type1","type2","type3");
			  $MysQlArray=array(0,12,13);
			  setcookies($CookieArray,$BaseURL);
	          SetGlobalcookieData( $CookieArray);
			//  CheckCookie($CookieArray);
			  
	 }
     function DefineBaseData(){
		      global $BaseURL;
			  global $stageNum;
			  $stageNum=13;
		      $BaseURL="FPresCheck.php";
	          $data_library= "iggtaiperd2";
		      $tableName="fpresdata"; 
			  //$stmp= getMysqlDataArray("fpschedule");	
			  global $ResData;
			  $ResData=getMysqlDataArray( $tableName);	
			  global $type1Title;
			  $type1Title=array( 
			           array("英雄","hero"),
					   array("怪物","mob"),
					   array("魔王","boss")
			   );
			  global $type2Title;
			  $type2Title=array( 
			           array("all","all"),
					   array("m2","m2"),
					   array("m3","m3"),
					   array("m4","m4"),
					   array("m5","m5"),
					   array("未定","Undefined"),
			   );
			   global $type3Title;
			   $type3Title=array(array("--","all"));
			   for($i=1;$i<=$stageNum;$i++){
				    $s="CH.".$i;
			        array_push($type3Title,array($s,$i));
			   } 
		 	  global $CookieArray;
			  global $ScheduleData, $ScheduleDataPlan ,$ScheduleData3D,$ScheduleDataAni,$ScheduleDataVfx;
			  $stmp= getMysqlDataArray("fpschedule");	
			  $ScheduleData=$stmp;
	          $ScheduleDataPlan  =  filterArray($stmp,5,"工項");
			  $ScheduleData3D =  filterArray($stmp,5,"建模");
			  $ScheduleDataAni =  filterArray($stmp,5,"動作");
			  $ScheduleDataVfx =  filterArray($stmp,5,"特效");
	 } 
     function ShowButton(){
		      global $type1Title,$type2Title,$type3Title;
			  $Rect=array(20,30,100,20);
			  DrawButton($Rect,$type1Title,"type1" ,array(" "," "));
			  $Rect=array(20,55,100,20);
		      DrawButton($Rect,$type2Title,"type2",array("type3","all") );
			  $Rect=array(20,80,50,20);
		      DrawButton($Rect,$type3Title,"type3",array("type2","all") );
			  global $BaseURL;
			  $ValArray=array(array("Up","ViewPic"));
			  $Rect=array(20,110,100,20);
			  sendVal($BaseURL,$ValArray,"submit","開啟編輯",$Rect, 12, "#ee6666", "#ffffff" );
			  if($_POST['Up']=="ViewPic") DrawRect("注意不要一次上傳太多檔案",12, "#ffffff",   $Rect[0], $Rect[1],200,20,"#ff1234" );
			  //取得code
			  $Rect=array(1224,10,100,20);
			  $ValArray=array(array("CheckCode","true"));
			  sendVal($BaseURL,$ValArray,"submit","CheckCode",$Rect,8, "#aaaaaa", "#ffffff" );
	 }
 
	 function DrawButton($Rect,$btArray,$arraytype,$AddArray,$BgColor="#000000",  $fontColor="#ffffff"){
		    global $BaseURL;
			global $ResDatafi;
		    for($i=0;$i<count( $btArray);$i++){
			      $BGC=$BgColor;
			      if($_COOKIE[$arraytype]==$btArray[$i][1]){
					  $BGC="#ee0000";
				  }
				  $valArray=array(array($arraytype, $btArray[$i][1]), $AddArray);
				  $SubmitName="submit";
				  $SubmitVal= $btArray[$i][0] ;
			 	  sendVal($BaseURL,$valArray,$SubmitName,$SubmitVal,$Rect,10, $BGC,$fontColor,"true");
				  if($arraytype=="type1" && $_COOKIE[$arraytype]==$btArray[$i][1])
					  DrawRect( "x".count($ResDatafi),10,"#ffffff", $Rect[0]+60, $Rect[1]+3,20,15,"#000000");
				  $Rect[0]+=$Rect[2]+5;
			  }
	 
	 }
?>

<?php //List
     function getData(){
		      global  $CookieArray,$MysQlArray;
	          global $ResData;
			  $t1=$_COOKIE[$CookieArray[0]];
			  $t2=$_COOKIE[$CookieArray[1]];
			  $t3=$_COOKIE[$CookieArray[2]];
			  $data=filterArray( $ResData,0,$t1);
			  if($t2!="all") $data=filterArray( $data,$MysQlArray[1],$t2);
			  if($t3!="all") $data=filterArray( $data,$MysQlArray[2],"CH.".$t3); 
			  $data= SortList( $data,3);
			  return $data;
	 }
     
     function ListContent(){
	          global  $CookieArray,$MysQlArray;
			  global $ResData, $ResDatafi;
              for($i=0;$i<count($CookieArray);$i++)  if($_COOKIE[$CookieArray[$i]]=="")return;
			  $data = getData();
			  $ResDatafi= $data; 

			  
			  $size= filterArray( $ResData,0,"size");
			  $title= filterArray( $ResData,0,"name");
			  $ListArray=array(2,3,4);
			  $Rect=array(20,150,90,20);
			  echo   "<form id='EditRes'  name='Show' action='".$BackURL."' method='post'  enctype='multipart/form-data'>";
			   //關卡
				   DrawStageList($Rect);
 		      for($i=0;$i<count($data);$i++){
				  //內容
			     DrawRect("",11,$fontColor,$Rect[0],$Rect[1],200,100,"#000000");
			     DrawSingle($data[$i],$Rect,$ListArray,$size[0]);
				 //上傳圖檔
			     $n="pic_".$i;
				 $c="c_".$i;
				 if($_POST['Up']=="ViewPic"){
				      DrawRect("",11,$fontColor,$Rect[0]+200,$Rect[1],200,100,"#000000");
				 echo "<input type=hidden name=".$c." value=".$data[$i][2].">";
				 $input="<input type=file name=".$n."	id=file  size=10   >";
				 DrawInputRect("代表圖檔"." ","12","#ffffff", $Rect[0]+202, $Rect[1]  ,1220,20, $colorCodes[4][2],"top", $input);
				 //max檔
				 $n="Max_".$i;
				 $input="<input type=file name=".$n."	id=file  size=10   >";
				 DrawInputRect("3D檔"." ","12","#ffffff", $Rect[0] +202, $Rect[1]+22  ,1220,20, $colorCodes[4][2],"top", $input);
				 
				 //ani檔
				 $n="Ani_".$i;
				 $input="<input type=file name=".$n."	id=file  size=10   >";
				 DrawInputRect("動畫檔"." ","12","#ffffff", $Rect[0] +202, $Rect[1]+43  ,1220,20, $colorCodes[4][2],"top", $input);
			    //VFX檔
				 $n="VFX_".$i;
				 $input="<input type=file name=".$n."	id=file  size=10   >";
				 DrawInputRect("特效檔"." ","12","#ffffff", $Rect[0]+ 202, $Rect[1]+65  ,1220,20, $colorCodes[4][2],"top", $input);
				 }
				 $Rect[1]+=104;
			  }
			  if($_POST['Up']!="") $submit ="<input type=submit name=submit value=上傳>";
	          DrawInputRect("","12","#ffffff", 440 ,  120,100,20, $colorCodes[4][2],"上傳",$submit );
			  echo "</form>";
	
	 }
     function DrawSingle ($Base,$Rect,$ListArray,$size){
		      global $type1;
			  $BaseRect=$Rect;
              $fontColor="#000000";
			  $BgColor="#ffffff";
		  	  $Rect[0]+=2;
			  $Rect[1]+=2;
              DrawRect_Layer($Base[2],12,$fontColor,$Rect,$BgColor,$Layer);
			  $Rect[1]+=22;
			  DrawRect_Layer($Base[3],12,$fontColor,$Rect,$BgColor,$Layer);
			  $Rect[1]+=22;
			  //圖檔
			  $state="設定";
			  $pic="ResourceData/".$type1."/viewPic/".$Base[2].".png";
			  if( file_exists($pic)){
			      DrawLinkPic($pic,$Rect[1]-44,$Rect[0]+94,96,96,$Link);
				    $state="建模";
			  }	
			  //max
			  $max="ResourceData/".$type1."/model/".$Base[2] ;
			  $file=  checkfileExists( $max,"zip");
			  $pic="Pics/3D.png";
		
			  $Code=$Base[2];
			  if ($file!=""){
			       DrawLinkPic($pic,$Rect[1] ,$Rect[0],20,20,$file);
				   $state="動作";
			  } 
			  //Ani
			  $Ani="ResourceData/".$type1."/Ani/".$Base[2] ;
			  $file=  checkfileExists( $Ani,"zip");
			  $pic="Pics/Ani.png";
			  if ($file!=""){
				  $Rect[0]+=22;
			       DrawLinkPic($pic,$Rect[1] ,$Rect[0] ,20,20,$file);
				   $state="特效";
			  } 
			  //VFX
			  $file="ResourceData/".$type1."/VFX/".$Base[2].".unitypackage" ;
			  $pic="Pics/VFX.png";
			  if ( file_exists(  $file)){
				   $Rect[0]+=22;
			       DrawLinkPic($pic,$Rect[1] ,$Rect[0] ,20,20,$file);
				   $state="fin";
			 } 
			 if( $state!="fin")
			 CheckState($Code, $BaseRect,$state);
		
		
 
	 }
	 function CheckState($Code,$Rect,$state){
	          global $ScheduleData,$ScheduleDataPlan;
			  $SCCode=returnCode($ScheduleDataPlan,$Code);
			 // echo $Code.".".$SCCode.">".$state;
			 $Rect[1]+=44;
			  $pr=filterArray($ScheduleData,5,$state);
			  $p= filterArray($pr,3,$SCCode);
			   /*
			  $p=array();
			  switch($state){
			      case "設定":
				       $p= filterArray($ScheduleData3D,3,$SCCode);
					  break;
				  case "建模":
				       $p= filterArray($ScheduleDataAni,3,$SCCode);
					   break;
				  case "動作":
				       $p= filterArray($ScheduleDataVfx,3,$SCCode);
					   break;
			  }
			  */
			  $msg="未排定";
			  if(count($p)>=1)$msg= $state.">".$p[0][9]."製作中".$p[0][2]."[".$p[0][6]."]";
			  $Rect[1]+=22;
			  $Rect[3]=32;
		      DrawRect_Layer($msg,10,"#ffffff",$Rect,"#444444",$Layer);
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

<?php //stage
      function DrawStageList($Rect){
	        global  $CookieArray;
			$ch=$_COOKIE[$CookieArray[2]];
			if($ch=="all")return;
			$Rect[0]+=500;
		    DrawRect("",11,$fontColor,$Rect[0],$Rect[1],560,400,"#000000");

	        //場景圖
			$add="0";
			  $Dir="ResourceData/stage";
			if($ch>=10)$add="";
			$picN="level_ch".$add.$ch."_01".".png";
			$pic= $Dir."/".$picN;
			//echo $pic;
			DrawRect("關卡資料",11,$fontColor,$Rect[0]+2,$Rect[1]+2,120,20,"#ffffff");
			if ( file_exists( $pic)){
			       DrawLinkPic($pic,$Rect[1]+24 ,$Rect[0]+2,512,256,$pic);
			}
			//U3d
			$n="Stage_U3D".$ch;
			$u3d=$Dir."/".$n.".unitypackage";
			$pic="Pics/SCU.png";
			//echo 	$u3d;
			if ( file_exists( $u3d)){
			       DrawLinkPic($pic,$Rect[1]+2  ,$Rect[0]+132,20,20,$u3d);
			}
			 //上傳
		    if($_POST['Up']=="ViewPic"){
			   $Rect[1]+=280;
			   //場景圖
			   $input="<input type=file name=stagePic	id=file  size=10   >";
			   echo "<input type=hidden name=stagepicCode value=".$picN.">";
			   DrawInputRect("關卡圖"." ","12","#ffffff", $Rect[0] , $Rect[1]  ,1220,20, $colorCodes[4][2],"top", $input);
			   $Rect[1]+=22; 
			   //場景packge
			 //  $n="Stage_U3D".$i;
			   $input="<input type=file name=Stage_U3D	id=file  size=10   >";
			   DrawInputRect("場景unitypackage"." ","12","#ffffff", $Rect[0] , $Rect[1]  ,1220,20, $colorCodes[4][2],"top", $input);
			}
	  }

?>

<?php //CheckPre進度
	 function GetCode(){
	           if ($_POST['CheckCode']!="true")return;
			   global $ResData;
			   $stmp= getMysqlDataArray("fpschedule");	
	           $ScheduleData  =  filterArray($stmp,5,"工項");
			   for($i=0;$i<count($ResData);$i++){
				   if($ResData[$i][2]!=""){
				      $c=  returnCode($ScheduleData,$ResData[$i][2]);
				   echo "</br>".$c;
				   }
			    
			   }
			   //echo "Check";
	 }
	 function returnCode($ScheduleData,$Code){
	          for($i=0;$i<count($ScheduleData);$i++){
			      if(strpos($ScheduleData[$i][3],$Code) !== false)
					  return $ScheduleData[$i][1];				  
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
			  UpStageFile();
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
     function UpStageFile(){
		      global  $CookieArray;
			  $ch=$_COOKIE[$CookieArray[2]];
			  $Dir="ResourceData/stage";
			  MakeDir( $Dir);
			  //pic
			  $picN=$_POST['stagepicCode'];
			  $Uppath=$Dir."/".$picN;
	          UpsimpleFile("stagePic",$Uppath);
			  //package
			  $n="Stage_U3D".$ch;
			  $Uppath=$Dir."/".$n.".unitypackage";
			  UpsimpleFile("Stage_U3D",$Uppath);
	 }
	 
     function UpsimpleFile($name,$Uppath){
		   if($_FILES[$name]["name"]!=""){
				   move_uploaded_file($_FILES[$name]["tmp_name"], $Uppath);
			  }
	 }

?>
