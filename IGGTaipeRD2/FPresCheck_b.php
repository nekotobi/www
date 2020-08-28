<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 
   <title>FP資源索引v2</title>
</head>

<?php //主控台
     require_once('PubApi.php');
	 require_once('mysqlApi.php');
	 require_once('scheduleApi.php');
     require_once 'ResGDfindApi.php';
	 DefineUnityResPath();
     CookieSet();
     DefineBaseData();
     filterSubmit();
     ListContent();
     ShowButton();
    //filterSubmit();
     //檢查進度
     GetCode();
     DrawPercentage();
	// ExportMilestone();
?>


<?php //title
     function CookieSet(){
		      global $BaseURL;
		      global  $CookieArray,$MysQlArray;
		 	  $CookieArray=array("type1","type2","type3","type4" ,"Up");
			  $MysQlArray=array(0,12,13);
			  setcookies($CookieArray,$BaseURL);
	          SetGlobalcookieData( $CookieArray);
		     // CheckCookie($CookieArray);	  
	 }
	 function DefineUnityResPath(){
		      global $unityUIPath;
			  $unityUIPath="VTUnity/Main/Assets/AssetBundles/PrimaryBundle/UI/";
			  global $iconPath;
			  $iconPath=array("Skill");
			  global $BuffIconDir;
			  $BuffIconDir="ResourceData/hero/buff";
	 }
     function DefineBaseData(){
		      global $BaseURL;
			  global $stageNum;
			  global $data_library;
			  global $tableName;
			  $stageNum=13;
		      $BaseURL="FPresCheck.php";
	          $data_library= "iggtaiperd2";
		      $tableName="fpresdata"; 
			  global $ResData;
			  $ResData=getMysqlDataArray( $tableName);	
			  global $type1Title;
			  $type1Title=array( 
			           array("英雄","hero" ),
					   array("小怪","mob"),
					   array("boss&召喚獸" ,"boss"),
					   array("場景" ,"stage"),
					   array("活動" ,"event")
			   );
			  global $type2Title;
	       	  CreateTitle2( );
			  global $type3Title; 
			  $type3Title=array(
			              array("-","-"),
			              array("3D","6"),
						  array("動作","7"),
						  array("VFX","8"),	  
	          );
		      if($_POST["type2"]=="event"){
			  	  $type3Title=array(
			              array("-","-"),
						      array("設定","5"),
							        array("3D","6"),
									      array("VFX","7"),
						  );
			  }
			   
		 	  global $CookieArray;
			  global $ScheduleData, $ScheduleDataPlan  ;
			  $stmp= getMysqlDataArray("fpschedule");	
			 //
			  $Sc2  =getMysqlDataArray( "fpschedule_old");
	          $scmix=addArray($stmp,$Sc2);
		 	  $stmp=$scmix;
		     //
			  echo count($stmp);
			  $ScheduleData=$stmp;
	          $ScheduleDataPlan  =filterArray($stmp,5,"工項");
			  
			  
              global $OutsData, $memberData;
			  $OutsDatat1=getMysqlDataArray("outsourcing");	
			  $OutsDatat=filterArray($OutsDatat1,0,"data");	
			  $OutsData=returnArraybySort(  $OutsDatat,2);
			  $memberDataT=getMysqlDataArray("members");
			  $memberData=returnArraybySort(  $memberDataT,1);
			  global $Worktype;
			  $Worktype=array("設定","建模","動作","特效");
			  global  $workType;
			  $mt=getMysqlDataArray( "scheduletype"); 
	          $mt2  =filterArray($mt,0,"data3");
			  $workType=returnArraybySort(  $mt2 ,2);
			  global $ResDatafi;
			  $ResDatafi=  getData(); 
			  CreateTitle4( );
			  
	 }
	 function AddT2Group(){
	           global $type2Title;
			   global $ResGroup;
			   global $type1;
			   $ResGroup=ReturncolectionGroup();
			   if(count($ResGroup)==0)return;
			   if($type1=="mob"){
			       for($i=0;$i<count($ResGroup)+1;$i++)
				       array_push($type2Title,array("G".$i,"G".$i));
				  }
               if($type1=="hero"){
                   for($i=0;$i<count($ResGroup)+1;$i++){
					     if($ResGroup[$i]!="")
				             array_push($type2Title,array( $ResGroup[$i],"G_".$ResGroup[$i]));
				   }
				   for($i=1;$i<=3;$i++){
					   array_push($type2Title,array("星".$i,"S_".$i));
				   }
				     array_push($type2Title,array("紅","C_紅"));
					     array_push($type2Title,array("藍","C_藍"));
						     array_push($type2Title,array("紫","C_紫"));
							     array_push($type2Title,array("綠","C_綠"));
								    array_push($type2Title,array("過去","T_過去"));
									 array_push($type2Title,array("現在","T_現在"));
									  array_push($type2Title,array("未來","T_未來"));
			   }
	 }
	 function ReturncolectionGroup(){
		      global $type1;
			  global $ResData;
			  $tRs=filterArray( $ResData,0,$type1);
			  $ResGroup=array();
			  switch ($type1){
			         case "mob":
					 for($i=0;$i<count($tRs);$i++){
			              if($tRs[$i][14]!=""){
				             $s=explode("_",$tRs[$i][14]);
				             $n=0;
					          if(count($s)>1 ) $n=$s[1]-1;
				              $ResGroup[$s[0]][$n]=$tRs[$i];
				            }
			           }
			         return $ResGroup;
				     case "hero":
				     for($i=0;$i<count($tRs);$i++){
						 	$s= explode('(',$tRs[$i][14]);
							// echo $s[0];
						       if (!in_array($s[0], $ResGroup) ) {
							      array_push($ResGroup,$s[0]);
						      }
					  }
					 return $ResGroup;
			  }
			  return null;
 
       		  
			 
	 }
	 function getXlsPath(){
		      global  $ResDatafi;
	          global  $type1Title,$type1;
			  $type=ReturnCH($type1Title,$type1);
			 /// echo $type1.">". $type;
			  $GDCodeArray=array();
			  for($i=0;$i<count($ResDatafi);$i++){
				   array_push( $GDCodeArray,$ResDatafi[$i][2]);
			  }
			  $t=returnXlsArray($type,$GDCodeArray);
			  return $t;
	 }
	 function ReturnCH($typeArray,$typec){
	      for($i=0;$i<count($typeArray);$i++){
		     if($typeArray[$i][1]==$typec) return $typeArray[$i][0];
		  }
	 }
     function ShowButton(){
		      global $type1Title,$type2Title,$type3Title,$type4Title;
			  global $type1,$type2,$type3,$type4,$Up;
			  $Rect=array(20,30,100,20);
			  DrawButton($Rect,$type1Title ,"type1" ,array(" "," "),$type1);
			  $Rect=array(20,55,40,20);
		      DrawButton($Rect,$type2Title,"type2",array("",""),$type2 );
			  $Rect=array(20,80,50,20);
			  DrawButton($Rect,$type3Title,"type3",array("",""),$type3 );
			  global $BaseURL;
			  $ValArray=array(array("Up","ViewPic"));
			  $Rect=array(20,130,98,20);
			  if($Up!="ViewPic" )sendVal($BaseURL,$ValArray,"submit","上傳圖檔",$Rect, 12, "#ee6666", "#ffffff","true");
			  $ValArray=array(array("Up","Edit"));
			   $Rect[0]+=98;
			  if($Up!="Edit" )sendVal($BaseURL,$ValArray,"submit","編輯進度",$Rect, 12, "#ee6666", "#ffffff","true");
			  if($Up=="ViewPic"){
				  DrawRect("注意不要一次上傳太多檔案",12, "#ffffff",   $Rect[0]+100, $Rect[1],200,20,"#ff1234" );
				 $ValArray=array(array("Up","_"));
				  sendVal($BaseURL,$ValArray,"submit","關閉編輯",$Rect, 12, "#ee6666", "#ffffff","true");
			  }
			  //取得code
			  $Rect=array(1224,10,100,20);
			  $ValArray=array(array("CheckCode","true"));
			  sendVal($BaseURL,$ValArray,"submit","CheckCode",$Rect,8, "#aaaaaa", "#ffffff" );
			  //輸出mile資料
			  $Rect=array(1224,30,100,20);
			  $ValArray=array(array("List","onlyTitle"));
			  sendVal($BaseURL,$ValArray,"submit","mileData",$Rect,8, "#aaaaaa", "#ffffff" );
	 }
	 function DrawButton($Rect,$btArray,$arraytype,$AddArray,$typev, $BgColor="#000000",  $fontColor="#ffffff"){
		    global $BaseURL;
			global $ResDatafi;
		    for($i=0;$i<count( $btArray);$i++){
			      $BGC=$BgColor;
			      if( $typev ==$btArray[$i][1]){
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
	 function CreateTitle2(){
              global $type1;
			  global $type2Title;
			  if($type1=="")return  ;
			  global $ResData;
			  $type2Title=array(  array("all","all"));//, array("m2","m2"), array("m3","m3"), array("m4","m4"));
			  $tmp=filterArray($ResData,0,$type1);
			  $a=array();
			  for($i=0;$i<count($tmp);$i++){
			      if(!in_array( $tmp[$i][12],$a))  array_push($a,$tmp[$i][12]);
			  }
			  $sorts=sortStringArray(  $a);
			  for($i=0;$i<count($sorts);$i++){
			     array_push($type2Title,array($sorts[$i],$sorts[$i]));
			  }
			  AddT2Group(); //加入group
			   
	 }
     function CreateTitle4() {
		      global $ResDatafi;
			  global $type3;
			  global $type4Title;
			  $arr=array();
			  for($i=0;$i<count($ResDatafi);$i++){
				   $str  = explode(">",$ResDatafi[$i][$type3]);
				   if($str[0]!="")
				      if(!in_array( $str[0],$arr))  array_push($arr,$str[0]);
				 
			  }
			  $type4Title=array();
			  for($i=0;$i<count($arr);$i++) {
			      array_push($type4Title,array($arr[$i],$i));
			  }
				 
	 }
 
?>

<?php //List
     function DrawPercentage(){
		      global  $Percentage;
			  global  $ResDatafi;
			  global $type1;
			  $all=count($ResDatafi);
			  if($all==0)return;
			  if ( $type1=="hero" && $type2=="all")$all=30;
			  $i=round($Percentage[0]/$all*100);
			  $d=round($Percentage[1]/$all*100);
			  $a=round($Percentage[2]/$all*100);
			  $v=round($Percentage[3]/$all*100);
			  $msg= "[總量]".$all."[設定]".$i."%"."[建模]".$d."%"."[動畫]".$a."%"."[特效]".$v."%";
			  DrawRect($msg,11,"#ffffff",600,30,300,20,"#000000");
	 }
     function getData(){
		      global  $CookieArray,$MysQlArray;
	          global  $ResData;
			  global  $type1,$type2,$type3;
              global  $ResGroup;
			  $data= array();
			  echo $type1;
		      $ResData=filterArray($ResData,0,$type1);
			   if($type2=="all"){
				    $data= SortList(  $ResData,3);
				   return $data;
			   }
		      if( $type1=="mob"){
					   if(strpos($type2,'G') !== false){ //Group
				       $s= str_replace( "G" , "" ,$type2 );
			           for($i=0;$i<count($ResGroup[$s]);$i++){
				          array_push($data,$ResGroup[$s][$i]);
				         }
						   return $data;
					   }
				    if(strpos($type2,'G')  == false) $data =filterArray($ResData,12,$type2);
				 
				   }
			       if( $type1=="hero"){
                     // $s= str_replace( "G" , "" ,$type2 );
					  $s=explode("_",$type2);
					  if(count($s)==1) $data =filterArray($ResData,12,$type2);
					  if($s[0]=="G") $data =filterArray($ResData,14,$s[1]);
		              if($s[0]=="S")   $data =filterArray($ResData,9,$s[1]);
		        	  if($s[0]=="C")   $data =filterArray($ResData,11,$s[1]);
					  if($s[0]=="T")   $data =filterArray($ResData,10,$s[1]);
				      return $data;
				   }
 
			  if(  $type1=="stage"){
				   $data=filterArray( $ResData,13,$type2);
				   return $data;
			  }
			  $data=filterArray( $ResData,0,$type1);
		      if($type2!="all") $data=filterArray( $data,$MysQlArray[1],$type2);
			  
			  if($type3!="-"){
			  
			  }
				  
			  $data= SortList( $data,3);
			  
			
			  return $data;
	 }
	 function getMileData(){
			  global  $type1Title;
			  global $ResData;
			  global $type1,$type2,$type3;
			  $d=filterArray( $ResData,12,$type2);
			  $a=array();
			  for($i=0;$i<count($type1Title)-1;$i++){
				  $b=filterArray( $d,0,$type1Title[$i][1]);
				  $b= SortList( $b,3);
				  array_push($a,  $b);
				 }
			  return $a;
	 }
	 function ListMilestoneAll(){
	          $data = getMileData();
			  $x=20;
			  global  $yloc;
			  $yloc=140;
			  for($i=0;$i<count($data);$i++){
			         ListMilestonetype($data[$i],$x);
					  
			  }
	 }
	 function ListMilestonetype($data,$x){
		 	  global  $yloc;
			  $fontColor="#000000";
			  $bgcolor="#ffffff";
			  		  $yloc+=22;
			  $w=400;
			  $hi=20;
			  $fontSize=1;
			  $tableArray=array(
				              array("名稱",100,"#000000","#ffffff"),
							  array("3D",100,"#000000","#ffffff"),
	          );
			  DrawTable($tableArray,$x,$yloc,$fontColor,$fontSize,$bgcolor);
			  $yloc+=22;
		      for($i=0;$i<count($data);$i++){
				  $tableArray=array(
				              array($data[$i][2].$data[$i][3],100),
				              array( $data[$i][6],100),
				  );
				  DrawTable($tableArray,$x,$yloc,$fontColor,$fontSize,$bgcolor);
				  $yloc+=22;
			 }
	 }
	 function ListPrincipal(){
		      global $type4Title,$type4,$type3;
			  global $ResDatafi;
			  $arr=array();
			  for($j=0;$j<count($type4Title);$j++){
				   array_push( $arr,array($type4Title[$j][0]));
				}
			  array_push( $arr,array("未排進" ));
			  for($i=0;$i<count($ResDatafi);$i++){
				//  echo $ResDatafi[$i][$type3];
			      $str  = explode(">",$ResDatafi[$i][$type3]);  
				  $n=  returnPriNum($str[0],$type4Title);
				  $color="#ffcccc";
				  if($str[1]=="已完成") $color="#ffffff";
				  array_push( $arr[$n],$ResDatafi[$i][2].$ResDatafi[$i][3]."_".$color);
			  }
			  $x=20;
			  $y=220;
			  for($i=0;$i<count($arr) ;$i++){
				   $Bgc="#000000";
				   $fontColor="#ffffff";
				    for($j=0;$j<count($arr[$i]) ;$j++){
					     $str  = explode("_",$arr[$i][$j]);  
					     $Bgc="#000000";
					     if($str[1]!="") $Bgc=$str[1];
			             //DrawRect($arr[$i][$j],11,$fontColor,$x+$i*120,$y+$j*22,100,20,$Bgc);
						  DrawRect($str[0],11,$fontColor,$x+$i*120,$y+$j*22,100,20,$Bgc);
				
						  $fontColor="#000000";
					}
			 }
	 }
	 function returnPriNum($s,$type4Title){
		      
	          for($j=0;$j<count($type4Title);$j++){
				   if($type4Title[$j][0]==$s)return $j;
				}
				return count($type4Title) ;
	 }
     function ListContent(){
	          global  $CookieArray,$MysQlArray;
			  global $ResData, $ResDatafi;
			  global $Up;
			  global $type1,$type3;
			  if($type3!="-"){
			  ListPrincipal();
			  return;
			  }
			  if($type1=="all"){
				  ListMilestoneAll();
				  return;
			  }
              for($i=0;$i<2;$i++)  if($_COOKIE[$CookieArray[$i]]=="")return;
               $xlsPath=getXlsPath();
			  global  $Percentage;
			  $Percentage=array(0,0,0,0);
			  $size= filterArray( $ResData,0,"size");
			  $title= filterArray( $ResData,0,"name");
			  $ListArray=array(2,3,4);
			  $Rect=array(20,150,90,20);
			  echo   "<form id='EditRes'  name='Show' action='".$BackURL."' method='post'  enctype='multipart/form-data'>";
			  //關卡
			  DrawStageList($Rect,$i);
			  $data= $ResDatafi;
 		      for($i=0;$i<count($data);$i++){
				  //內容
			      DrawRect("",11,$fontColor,$Rect[0],$Rect[1],400,100,"#000000");
			     DrawSingle($data[$i],$Rect,$ListArray,$size[0],$xlsPath[$i]);
			      if($Up=="ViewPic")  UpPic($Rect,$i,$data);  //上傳圖檔
				  if($Up=="Edit")   setWork($data[$i],$Rect[1]+32); //工作資訊
			  	  $Rect[1]+=104;
			  }
			  if($Up=="ViewPic") $submit ="<input type=submit name=submit value=上傳>";
	          DrawInputRect("","12","#ffffff", 440 ,  120,100,20, $colorCodes[4][2],"上傳",$submit );
			  echo "</form>";
	 }
	 function UpPic($Rect,$i,$data){
		         global $type1;
				// echo ">>>".$type3;
		 	      if($type1=="stage"  )return;
				  DrawRect("",11,$fontColor,$Rect[0]+200,$Rect[1],900,100,"#000000");
	             $n="pic_".$i;
				 $c="c_".$i;
				 echo "<input type=hidden name=".$c." value=".$data[$i][2].">";
				 $input="<input type=file name=".$n."	id=file  size=10   >";
				 DrawInputRect("設定"." ","10","#ffffff", $Rect[0]+202, $Rect[1]  ,520,20, $colorCodes[4][2],"top", $input);
				 //max檔
				 $n="Max_".$i;
				 $input="<input type=file name=".$n."	id=file  size=10   >";
				 DrawInputRect("3D檔"." ","10","#ffffff", $Rect[0] +202, $Rect[1]+22  ,1220,20, $colorCodes[4][2],"top", $input);
				 //ani檔
				 $n="Ani_".$i;
				 $input="<input type=file name=".$n."	id=file  size=10   >";
				 DrawInputRect("動畫檔"." ","10","#ffffff", $Rect[0] +202, $Rect[1]+43  ,1220,20, $colorCodes[4][2],"top", $input);
			    //VFX檔
				 $n="VFX_".$i;
				 $input="<input type=file name=".$n."	id=file  size=10   >";
				 DrawInputRect("特效檔"." ","10","#ffffff", $Rect[0]+ 202, $Rect[1]+65  ,1220,20, $colorCodes[4][2],"top", $input);
			     /*
				 //Buff
				 $n="Buff_C_".$i;
				 $input="<input type=file name=".$n."	id=file  size=10   >";
				 DrawInputRect("技能1"." ","10","#ffffff", $Rect[0]+ 402, $Rect[1]   ,1220,20, $colorCodes[4][2],"top", $input);
				 $n="Buff_P_".$i;
				 $input="<input type=file name=".$n."	id=file  size=10   >";
				 DrawInputRect("技能2"." ","10","#ffffff", $Rect[0]+ 402, $Rect[1]+22   ,1220,20, $colorCodes[4][2],"top", $input);
				 */
	 }
	 function BuildnewOrder( $sendArrays,$data,$x,$y){
		         $code=returnDataCode( );
	             array_push( $sendArrays, array("code",$code));
				 array_push( $sendArrays, array("plan",$data[2].$data[3]));
				 array_push( $sendArrays, array("type","工項"));
				 $dateup=date(Y)."_".(int)date(m)."_".(int)date(d);
			     array_push( $sendArrays, array("startDay",$dateup));
			
			     echo  "<form   name='Show2' action='".$BaseURL."' method='post'  enctype='multipart/form-data'>";
				
				 sendInputHiddenVal($sendArrays);
				 $input  ="<input type=text  name=line value=1 style=font-size:10px  size=2 > ";
	             DrawInputRect( "行數","10","#ffffff", $x,$y ,50,20, "#dddddd","top",  $input);
				 echo "<input type=hidden name=gdcode value=".$data[2].">";
				 $submit="<input type=submit style=font-size:10px  name=submit value=新增工項>";
	             DrawInputRect( "","10","#ffffff", $x+50,$y ,100,20, "#dddddd","top",  $submit);
				 echo  "</form>";
	 } 
	 function setWork($data,$y){
		      global  $type1,$type2;
		      global $BaseURL;
			  $x=610;
			  global $OutsData,$memberData;
			  global $Worktype;
			  global $ScheduleData;
			  $st="角色";
			  
		      if($type1=="mob") $st="怪物";
			  if($type1=="boss") $st="召喚獸王";
			  if($type1=="event") $st="活動";
			  $stmp= $ScheduleData;
			  $ScheduleData  =  filterArray($stmp,5,"工項");
			  $code=  returnCode($ScheduleData, $data[2]);
			  //$code=$data[1];
			  $sendArrays=array(
			           array("data_type","data"),
				       array("milestone",$type2),
					   array("BaseURL",$BaseURL),
					   array("selecttype",$st),
					   array("sendtableName","fpschedule"),
			 	       array("lastUpdate",date(Y_m_d_H_i,time()+(8*3600))) 
				 );
			  if($code==""){
				 BuildnewOrder( $sendArrays,$data,$x,$y);
			     return;    
			  }
			  $size=10;
	          $ScheduleData  =  filterArray($stmp,3,$code);
			  echo ">".count($stmp);
			  $y-=20;
			  for($i=0;$i<count($Worktype);$i++){
				  	  echo  "<form   name='Show2' action='".$BaseURL."' method='post'  enctype='multipart/form-data'>";
				      $s= filterArray($ScheduleData,5,$Worktype[$i]);
				      $sc=$s[0];
					  $Ecode=$sc[1];
					  if(count($s)==0)$Ecode=returnDataCode();
					  array_push($sendArrays,array("code",$Ecode));
					  array_push($sendArrays,array("type",$Worktype[$i]));
					  array_push($sendArrays,array("plan", $code));
			 
					  sendInputHiddenVal($sendArrays);
					//  echo $Worktype[$i]. count ($s);
				      if(  $sc[9]!="")   DrawRect("",11,$fontColor,$x,$y,510,20,"#aa7777");
					  if(  $sc[7]=="已完成")   DrawRect("",11,$fontColor,$x,$y,510,20,"#77aa77");
					  $out=trim($sc[9]); 
					  //"startDay","principal","outsourcing","workingDays"
                      $selectTable= MakeSelectionV2($OutsData, $out,"outsourcing", $size);
			          DrawInputRect( "","10","#ffffff", $x,$y ,220,20, "#dddddd","top",  $selectTable);
					  $pri=$sc[8];
					  $selectTable= MakeSelectionV2($memberData, $pri,"principal", $size);
			          DrawInputRect( "","10","#ffffff", $x+170,$y ,100,20, "#dddddd","top",  $selectTable);
					  $start=trim($sc[2]);
					  $input="<input style=font-size:10px;background-color:#aaccaa; type=text name=startDay size=12  value=".$start."    ></input>";
					  DrawInputRect( "","10","#ffffff", $x+230,$y ,100,20, "#dddddd","top", $input);
					  $day=$sc[6];
					  $input="<input style=font-size:10px;background-color:#aaccaa; type=text name=workingDays size=2 value=".$day."   ></input>";
				      DrawInputRect( "","10","#ffffff", $x+330,$y ,30,20, "#dddddd","top", $input);
				      sendInputHiddenVal($sendArrays);
					   global  $workType;
					   $w=$sc[7];
					   $selectTable= MakeSelectionV2($workType, $w,"state", $size);
			          DrawInputRect( "","10","#ffffff", $x+360,$y ,100,20, "#dddddd","top",  $selectTable);
					  
					  $subname="修改";
					  $BgColor="#ccffaa" ;
					  if(count ($s)==0){
						  $subname="新增";
					      $BgColor="#ffaaaa" ;
					  }
			          $submit ="<input  style=font-size:10px;background-color:".$BgColor."; type=submit name=submit value=".$subname.">";
	                  DrawInputRect( "","10","#ffffff", $x+470,$y ,100,20, "#dddddd","top",  $submit);
					  $y+=22;
					  echo  "</form>";
			  }
			
		
	 }
     function DrawSingle ($Base,$Rect,$ListArray,$size,$xls){
		      global $type1;
			  global  $Percentage;
			  $BaseRect=$Rect;
              $fontColor="#000000";
			  $BgColor="#ffffff";
		  	  $Rect[0]+=2;
			  $Rect[1]+=2;
			  $msg=$Base[2];
		      if($type1!="hero")$msg= $Base[2]."[".$Base[13]."]";
              DrawRect_Layer( $msg ,11,$fontColor,$Rect,$BgColor,$Layer);
			  $Rect[1]+=22;
			  $msg=$Base[3];
			  DrawRect_Layer( $msg,11,$fontColor,$Rect,$BgColor,$Layer);
			  $Rect[1]+=22;
			  //圖檔
			  $state="設定";
			  $dir="hero";
			  if(strpos($Base[2],'m') !== false)$dir="mob";
			  if(strpos($Base[2],'b') !== false)$dir="boss";
			  if(strpos($Base[2],'e') !== false)$dir="event";
			  $pic="ResourceData/".$dir."/viewPic/".$Base[2].".png";
			  $p=0;
			  $newFileName=$Base[2]."_".$Base[3]."_".$Base[4];
			  $newFileNamebig5= iconv( "UTF-8" , "big5" , $newFileName);
			  if( file_exists($pic)){
			      DrawLinkPic($pic,$Rect[1]-44,$Rect[0]+94,96,96,$pic);
				  $state="建模";
				  $p=1;
			  }	
			  if( strpos($Base[5],"完成") !== false    )$p=1;
			  $Percentage[0]+=$p;;
		      $max="ResourceData/".$dir."/model/" ;
			  $file=  checkfileExists_New( $max,"zip",$newFileNamebig5,$newFileName);
			  $pic="Pics/3D.png";
			  $Code=$Base[2];
			  $p=0;
			  if ($file!=""){
			       DrawLinkPic($pic,$Rect[1] ,$Rect[0],20,20,$file);
				   $state="動作";
				   $$p=1;
			  } 
			  if( strpos($Base[6],"完成") !== false    )$p=1;
			  $Percentage[1]+=$p;
			  //Ani
			  $p=0;
			  //$Ani="ResourceData/".$dir."/Ani/".$Base[2] ;
			  $Ani="ResourceData/".$dir."/Ani/";
			  $file=  checkfileExists_New( $Ani,"zip",$newFileNamebig5,$newFileName);
			  $pic="Pics/Ani.png";
			  if ($file!=""){
				  $Rect[0]+=22;
			       DrawLinkPic($pic,$Rect[1] ,$Rect[0] ,20,20,$file);
				   $state="特效";
				  $p=1;
			  } 
			  if( strpos($Base[7],"完成") !== false    )$p=1;
			  $Percentage[2]+=$p;
			  //VFX
			  $p=0;
			 // $file="ResourceData/".$dir."/VFX/".$Base[2].".unitypackage" ;
			  $file="ResourceData/".$dir."/VFX/".$newFileNamebig5.".unitypackage" ;
			  $pic="Pics/VFX.png";
			  if ( file_exists(  $file)){
				   $Rect[0]+=22;
				   $file2="ResourceData/".$dir."/VFX/".$newFileName.".unitypackage" ;
			       DrawLinkPic($pic,$Rect[1] ,$Rect[0] ,20,20,$file2);
				   $state="fin";
				   $p=1;
			  } 
		      if( strpos($Base[8],"完成") !== false    )$p=1;
			  $Percentage[3]+=$p;
			  //Xls
			  $pic="Pics/excel.png";
			   DrawLinkPic($pic,$Rect[1] ,$Rect[0]+22 ,20,20,$xls);  
			  //icons
			   DrawIcons($Base,$BaseRect);

			 if( $state!="fin")
			 CheckState($Code, $BaseRect,$state);
	 }
	 function DrawIcons($Base,$Rect){
		      global $unityUIPath;
			  global $type1;
			  $Rect[1]+=48;
			  $Rect[0]+=12;
              switch ($type1){
				  case "hero":
				     $skillPath=$unityUIPath."Skill/";
				     $file=$unityUIPath."Skill/".$Base[2]."_C.png" ;
                     DrawfileLinkPic( $file, $file,array($Rect[0]+182,$Rect[1]-45,48,48));
			    	 $file=$unityUIPath."Skill/".$Base[2]."_P.png" ;
                     DrawfileLinkPic( $file, $file,array($Rect[0]+182,$Rect[1]+5,48,48));
				     //頭圖
				     $file=$unityUIPath."Heropic/heropic_".$Base[2].".png" ;
			         DrawfileLinkPic( $file, $file,array($Rect[0]+230,$Rect[1]-45,48,48));
				     //碎片
				     $na=substr($Base[2], -3);
				     $file=$unityUIPath."Heropic/hero_piece_11".$na.".png" ;
			         DrawfileLinkPic( $file, $file,array($Rect[0]+230,$Rect[1]+5,48,48));
				     //技能圖
			         $file=$unityUIPath."Battle/Skill_lcon/ui_skill_button_sp_".$Base[2].".png" ;
			         DrawfileLinkPic( $file, $file,array($Rect[0]+280,$Rect[1]-48,50,35));
					 //施放技能
					  $file=$unityUIPath."Battle/banner/ui_ultimate_skill_".$Base[2].".png" ;
			          DrawfileLinkPic( $file, $file,array($Rect[0]+280,$Rect[1]-2,88,50)); 
				    break;
					case "mob":
					 $file=$unityUIPath."Monster/monster_".$Base[2].".png" ;
					 DrawfileLinkPic( $file, $file,array($Rect[0]+182,$Rect[1]-45,48,48));
					break;
					case "boss":
					 $file=$unityUIPath."Monster/monster_".$Base[2].".png" ;
					 DrawfileLinkPic( $file, $file,array($Rect[0]+182,$Rect[1]-45,48,48));
					break;
					 case "stage":
					 $file=$unityUIPath."Monster/monster_".$Base[2].".png" ;
					 DrawfileLinkPic( $file, $file,array($Rect[0]+182,$Rect[1]-45,48,48));
					break;
			  }
			 
	 
	 }
	 function DrawfileLinkPic($Link,$pic,$Rect){
		   if ( file_exists( $Link)){
			       DrawLinkPic($pic,$Rect[1] ,$Rect[0] ,$Rect[2] ,$Rect[3],$Link);
			  } 
	 }
	 function CheckState($Code,$Rect,$state){
	          global $ScheduleData,$ScheduleDataPlan;
			  $SCCode=returnCode($ScheduleDataPlan,$Code);
			  $Rect[1]+=44;
			  $pr=filterArray($ScheduleData,5,$state);
			  $p= filterArray($pr,3,$SCCode);
			  $msg="未排定";
			  $BGC="#ee4422;";
			  if(count($p)>=1){
				  $msg= $state.">".$p[0][9].$p[0][7].$p[0][2]."[".$p[0][6]."]";
				  $BGC="#22aa55";
			  }
			  $Rect[1]+=22;
			  $Rect[3]=32;
		      DrawRect_Layer($msg,10,"#ffffff",$Rect,$BGC,$Layer);
	 }
     function checkfileExists($Path,$type){
	          if($type="Zip"){
			      if( file_exists($Path.".rar"))return $Path.".rar";
				  if( file_exists($Path.".zip"))return $Path.".zip";
				  return "";
			  }  
	 }
     function checkfileExists_New($RootPath,$type,$big5,$base){
	          if($type="Zip"){
				  $Path=$RootPath.$big5;
				  $BPath=$RootPath.$base;
				//  echo $Path;
			      if( file_exists($Path.".rar"))return $BPath.".rar";
				  if( file_exists($Path.".zip"))return $BPath.".zip";
				  return "";
			  }  
	 }
	 
?>

<?php //stage
      function DrawStageList($Rect){
	        global  $CookieArray;
			global  $type1,$type2;
			global  $ResData;
			$ch= (int) preg_replace('/[^\d]/','',$type2);
		//	$t=filterArray($ResData,0,$type1);
			//echo count($t);
			$info=filterArray($ResData,12,$type2);
			//echo count($info);
			if($type1!="stage")return;
			$Rect[0]+=400;
		    DrawRect("",11,$fontColor,$Rect[0],$Rect[1],560,400,"#000000");
            global $Up;
	        //場景圖
			$add="0";
		    $Dir="ResourceData/stage";
			if($ch>=10)$add="";
			$picN="level_ch".$add.$ch."_01".".png";
			$pic= $Dir."/".$picN;
			//echo $pic;
			$stagen=$type2.$info[0][3] ;
			DrawRect($stagen,11,$fontColor,$Rect[0]+2,$Rect[1]+2,220,20,"#ffffff");
			if ( file_exists( $pic)){
			       DrawLinkPic($pic,$Rect[1]+24 ,$Rect[0]+2,512,256,$pic);
			}
			//U3d
			$n="Stage_U3D".$ch;
			$u3d=$Dir."/".$n.".unitypackage";
			$pic="Pics/SCU.png";
			//echo 	$u3d;
			if ( file_exists( $u3d)){
			       DrawLinkPic($pic,$Rect[1]+2  ,$Rect[0]+232,20,20,$u3d);
			}
			 //上傳
		    if($Up=="ViewPic"){
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
     function getStates(){
	           global $ResData;
			   global $type1;
			   global $data_library;
			   global $ScheduleData;
	           $stmp= $ScheduleData;	
	           $ScheduleData  =  filterArray($stmp,5,"工項");
			   $type=array("設定","建模","動作","特效");
			   $sc=array( );
			   for($i=0;$i<count($type);$i++){
				   $t=filterArray($stmp,5,$type[$i]);
				   array_push($sc,$t);
			   }
	 }
 
	 function GetCode(){
	           if ($_POST['CheckCode']!="true")return;
			   global $ResData;
			   global $type1;
			   global $data_library;
			    global $ScheduleData;
			 //  setcookie("codeDate" , date(Y_m_d_H), time()+360);
			   $tableName="fpresdata";
			   $stmp= $ScheduleData;	
	           $ScheduleData  =  filterArray($stmp,5,"工項");
			   $type=array("設定","建模","動作","特效");
			   $sc=array( );
			   for($i=0;$i<count($type);$i++){
				   $t=filterArray($stmp,5,$type[$i]);
				   array_push($sc,$t);
			   }
			   $WHEREtable=array( "data_type", "gdcode" );
			   $Base=array("code","stateCode_1","stateCode_2","stateCode_3","stateCode_4");
			   $Rs=  filterArray( $ResData,0,$type1);
			   for($i=0;$i<count($Rs);$i++){
				   $GDcode=$Rs[$i][2];
				   if( $GDcode!=""){
					   $code=  returnCode($ScheduleData, $GDcode);
					   $up=returnStringArray($type, $sc,$code);
		               $WHEREData=array( $type1, $GDcode );
					   $stmt=   MakeUpdateStmtv2($tableName,$Base,$up,$WHEREtable,$WHEREData);
					   SendCommand($stmt,$data_library);
				   }
			   }
			   
	 }
	 function returnStringArray($type, $sc,$code){
		       $ar=array($code);
	           for($i=0;$i<count($type);$i++){
				  $tmp= filterArray($sc[$i],3,$code);
			   //   array_push( $ar,$tmp[0][9].">".$tmp[0][8].">".$tmp[0][2].">".$tmp[0][6].">".$tmp[0][7]);
			       array_push( $ar,$tmp[0][9].">".$tmp[0][7]);//.">".$tmp[0][2].">".$tmp[0][6].">".$tmp[0][7]);
			   }
			   return $ar;
	 }
	 
	 function returnCode($ScheduleData,$Code){
	          for($i=0;$i<count($ScheduleData);$i++){
			      if(strpos($ScheduleData[$i][3],$Code) !== false)
					  return $ScheduleData[$i][1];				  
			  }
	 }

?>

<?php //up
	 //填表單進度
	 function AddTypeSchedule(){

			  AddDataV2( );
	 }
     function EditTypeSchedule(){
		       global $data_library;
			   $tableName="fpschedule";
		      $WHEREtable=array( "data_type", "code" );
		      $WHEREData=array( "data",$_POST["code"] );
			  $Base=array("startDay","principal","outsourcing","workingDays","state");
			  $up=array($_POST["startDay"],$_POST["principal"],$_POST["outsourcing"],$_POST["workingDays"],$_POST["state"]);
			  $stmt= MakeUpdateStmt(  $data_library,$tableName,$Base,$up,$WHEREtable,$WHEREData);
			//  echo $stmt;
			   SendCommand($stmt,$data_library);		
			 //   echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";
	 }
     function filterSubmit(){
	          if($_POST['submit']=="")return;
	          if($_POST['submit']=="上傳")upfile();
			  if($_POST['submit']=="新增工項")AddDatav2( );
			  if($_POST['submit']=="新增") AddTypeSchedule( );
			  if($_POST['submit']=="修改")EditTypeSchedule( );
	 }
     function upfile(){
			  global $type1;
			  global $BaseURL;
			  global $BuffIconDir;
			  $data = getData();
			  $dir="ResourceData/".$type1;
		      $picdir=$dir."/viewPic/Base";
			  $viewDir=$dir."/viewPic";
		      MakeDir($BuffIconDir);	
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
				     move_uploaded_file($_FILES[$n]["tmp_name"], $filePath);
					 $finalPath= $viewDir."/".$code.".png";
				     $cmd="convert     $filePath    -flatten  -resize 256  $finalPath ";
					 exec($cmd);
				  }
			      //3d檔案
				   UPTypeFile($dir,"model","Max_",$i,$code);
				   UPTypeFile($dir,"Ani","Ani_",$i,$code);
			       UPTypeFile($dir,"VFX","VFX_",$i,$code);
				   if($type1=="hero"){
					   $nameArray=array( array("Buff_C_".$i,$code."_C"), array("Buff_P_".$i,$code."_P"));
				       UPskillIcon ( $BuffIconDir,$i ,$nameArray);
				   }
				     
			  }
			  UpStageFile();
			 // echo " <script language='JavaScript'>window.location.replace('".$BaseURL."')</script>";
	 }
	 function UPskillIcon($Dir,$i,$nameArray){
		      for($i=0;$i<count($nameArray);$i++){
				   if($_FILES[$nameArray[$i][0]]["name"]!=""){
				      $ext = explode(".",$_FILES[$nameArray[$i][0]]["name"]);
                      $filePath=$Dir."/".$nameArray[$i][1] .".".$ext[1];
					  move_uploaded_file($_FILES[$nameArray[$i][0]]["tmp_name"], $filePath);
					  $finalPath =$Dir."/".$nameArray[$i][1].".png";
					  $cmd="convert     $filePath    -flatten   $finalPath ";
					  exec($cmd);
				   }					   
			  }
	 }
	 function UPTypeFile($dir,$type,$name,$i,$code){
		          $na=$name.$i ;
				  $fdir=$dir."/".$type;
				  MakeDir($fdir);	
			      if($_FILES[$na]["name"]!=""){
				     $ext = explode(".",$_FILES[$na]["name"]);
					 $newname=	   GetFullName($code);
					$newname= iconv( "UTF-8" , "big5" , $newname );
					 // $newname=$code;
					// $fn=mb_convert_encoding($newname,"big5","utf-8");
                     $filePath= $fdir."/".$newname.".".$ext[1];
					 // echo $filePath;
					  move_uploaded_file($_FILES[$na]["tmp_name"], $filePath);
				  }
	 }
	 function GetFullName($code){
		    global $ResData;
			$t=filterArray($ResData,2,$code);
			return $code."_".$t[0][3]."_".$t[0][4];
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
     function AddDataV2( ){
	            //echo "xxx";
		        global  $data_library;//,$tableName;
			    global  $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2,$EditHide;
			       $tableName=$_POST['sendtableName'];
				   echo  $_POST['sendtableName'];
				   $tables=returnTables($data_library,$tableName);
	               $t= count( $tables);
				   $WHEREtable=array();
				   $WHEREData=array();
		           for($i=0;$i<$t;$i++){
				        array_push($WHEREtable, $tables[$i] );
					    array_push($WHEREData,$_POST[$tables[$i]]);
		              }
					$stmt=   MakeNewStmtv2($tableName,$WHEREtable,$WHEREData);
					echo $stmt;
				    SendCommand($stmt,$data_library);
			        echo " <script language='JavaScript'>window.location.replace('".$_POST[$BaseURL]."')</script>";
		      	  
 	 }
?>

<?php //xls
     function ExportMilestone(){
		 if($_POST['List']!="miledata")return;
			 echo "Xx";
		      require_once 'ExportXlsApi.php';
		      $data = getMileData();
			  Exporxls($data,"test");
	 }

?>

<?php //old
/*
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
	 */
?>
<body bgcolor="#b5c4b1">