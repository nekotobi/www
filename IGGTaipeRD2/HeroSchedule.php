<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>英雄排程區</title>
</head>
<?php  //主控台
   $id=$_COOKIE['IGG_id'];
   include('PubApi.php');
   include('CalendarApi.php');  
   include('mysqlApi.php');
   //include('scheduleApi.php');
   include('HerojavaApi.php');

   defineData_schedule();
   submitCont();
   DrawTitle();
   DrawDragHeros();
   DrawCallendarRange();
   DrawEvents();
   CreatJavaForm();
   ListProgressHeros();
  // ListHero() ;
?>

<?php //主要資料
   function  defineData_schedule(){
	         global $data_library,$tableName;
		     $data_library="iggtaiperd2";
			 $tableName="vtevent";
	   		 global $URL;
             $URL="HeroSchedule.php";
	         global $CalendarX, $startY;
		     $CalendarX=20;
			 $startY=80;
			 global $DateRange;
			 $DateRange=array(2020,4,2020,12);
			 global $DateWid;
			 $DateWid=6;
             global $HeroRes;
		     $data_library="iggtaiperd2";
			 $ResData=getMysqlDataArray("fpresdata");
			 $HeroResT=filterArray($ResData,0,"hero");
			 $HeroResT2=filterArray( $HeroResT,9,"");
			 $HeroRes= sortGDCodeArrays($HeroResT2 ,2 ,"true");
			 global $EventDatas;
			 $EventDatasT=getMysqlDataArray("vtevent");
			 $EventDatas=filterArray($EventDatasT,0,"ver");
			  //去掉已排入英雄
		     global $QueuedHerosData;
			 filterQueued();
			 //排定進度英雄
	         global $OnProgressHeros;
			 $OnProgressHeros=collectUnFinHeros();
			 //行程表
			 global $fpschedule;
			 $fpschedule=getMysqlDataArray("fpschedule");
			 global $HeroSc;
			 $HeroSc=filterArray(  $fpschedule,10,"角色");
 		     global $StartCalendarDay;
		     $StartCalendarDay=array( $DateRange[0],$DateRange[1],1);
   }
?>
<?php //上傳區
 
   
?>
<?php //隱藏/取得進度
    function filterQueued( ){ //去掉已排入英雄
	         global $EventDatas;
		     global $QueuedHerosData;
			 $str= returnInEventHeros($EventDatas);
			 $QueuedHeros=explode("_",$str) ;
			 global $HeroRes;
			 $QueuedHerosData=$HeroRes;
			 for($i=0;$i<count($QueuedHerosData)-1;$i++){
			      if(in_array( $QueuedHerosData[$i][2],  $QueuedHeros))unset( $QueuedHerosData[$i]);
			 }
			 $QueuedHerosData=array_values(  $QueuedHerosData);
	}
    function collectUnFinHeros(){
		     global $EventDatas;
			 global $HeroRes;
	         $EventHerosSTR= returnInEventHeros($EventDatas);
			 $EventHeros=explode("_", $EventHerosSTR) ;
			 $OnProgressHeros=array();
	         for($i=0;$i<count( $EventHeros) ;$i++){
				 $hr=filterArray( $HeroRes,2,$EventHeros[$i]);
			     if(strpos( $hr[0][8],'已完成') ==false){
				 array_push($OnProgressHeros,$hr[0]);
			   }
			 }
			 return  $OnProgressHeros;
	}
	function returnInEventHeros($EventDatas){
	         $str="";
			 for($i=0;$i<count($EventDatas);$i++){
				 $str= $str.$EventDatas[$i][6]."_";
			 }
			 return $str;
	}
?>
<?php //列印
   function DrawTitle(){
            DrawRect("英雄進度及活動對照表",24,"#ffffff",20,40,1400,30,"#222222");
   }
   function DrawEvents(){
            global $EventDatas;
			for($i=0;$i<count($EventDatas);$i++){
                DrawSingelEvent($EventDatas[$i],$i);
			}
   } 
   function DrawDragHeros(){
	        global  $QueuedHerosData;
		   	global $CalendarX, $startY;
			$x=20;
			$y=$startY;
			$w=40;
			$h=40;
			$Layer=1;
		    $BgColor="#222222";
            for($i=0;$i<count( $QueuedHerosData);$i++){
				$id= $QueuedHerosData[$i][2];
			    $pic=getPicLink($QueuedHerosData[$i][2] );
			    DrawJavaDragPic($pic,$y,$x,$w,$h,$id);
				DrawRect($id,10,"#ffffff",$x,$y+28,$w,14,$BgColor);
				DrawHeroProgress( $QueuedHerosData[$i],$x,$y,$w,$h);
			    $x+=$w;
				if($x>1400){
					$x=20;
				    $y+=$h+12;
				}
			}
            $startY+=$y+20  ;
   }
   function DrawHeroProgress($HeroData,$x,$y,$w,$h){
		     $BgColor="#222222";
			 $y+=$w;
			 //$w=1;
			 DrawRect("",10,"#ffffff",$x,$y,$w,10,$BgColor);
			 for($i=0;$i<4;$i++){
				 	    $BgColor="#117711";
			      if($HeroData[$i+5]!="" && $HeroData[$i+5]!=">"){
				 	 if(strpos($HeroData[$i+5],'已完成') !== false) $BgColor="#99ff99";
				      DrawRect("",10,"#ffffff",($x+1+$i*10),$y+1,9,8,$BgColor);
					// }
				 }
			 }
	 
	}
   function DrawSingelEvent($EventData,$i  ){
		    global $DateWid;
	   	    global $CalendarX, $startY;
		    $BgColor="#222222";
			$fontColor="#ffffff";
			$LocY=$startY;
   		    $LocX= (returnLocX($EventData[4])*$DateWid)+$CalendarX;
			$passDay= getPassDays( explode("_",$EventData[4]) ,explode("_",$EventData[5]));
			$w= $passDay*$DateWid;
			//標題
		    DrawRect($EventData[3],10,$fontColor,$LocX,$LocY,$w,20,$BgColor);
			//日期
			$LocY+=20;
			$currentYear= date("y")."_";
			$start= str_replace($currentYear,'',$EventData[4]);
			$end=str_replace($currentYear,'',$EventData[5]);
		    DrawRect( $start."-".$end,10,$fontColor,$LocX,$LocY,$w,20,"#555555");
			$LocY+=20;
		    //拖曳區
           	DrawMatDatas($EventData,$i,$LocX,$LocY,$w,$h );
		    
   }
   function DrawMatDatas($EventData,$i,$LocX,$LocY,$w,$h ){
            global $HeroRes;
			global $LargeY;//記錄高度
			$heroH=64;
			$heros=explode("_",$EventData[6]) ;
			$id="E_".$EventData[2];
			$h=count($heros)*$heroH+10;
	        if($h>$LargeY)$LargeY=$h;
		    DrawJavaDragArea("",$LocX,$LocY,$w,$h,"#999999",$fontColor,$id);
			//英雄區
			$x=$LocX+8;
			$y=$LocY+4;
			for($i=0;$i<count($heros);$i++){
				if($heros[$i]!=""){
				  $HeroData=filterArray( $HeroRes,2,$heros[$i]);
				DrawHero($x,$y,$heroH,$heroH, $HeroData[0]);
				$y+=$heroH+8;
				}
			}
			/*
			//移除
			$BgColor="#aa6666";
			$id="D_".$i;
			$y+=56;
		    DrawJavaDragArea("",$LocX,$y,$w,20,$BgColor,$fontColor,$id);;
			 */
   }
   function returnLocX($date){
	   	    global $CalendarX, $startY;
		    global $DateRange;
			global $StartCalendarDay;
	        $day = explode("_",$date);
		    $passDay= getPassDays( $StartCalendarDay,$day);
		    
			return $passDay;
		         
   }
   function DrawCallendarRange(){
		      global $finalTasks;
			  global $CalendarX, $startY;
			  global $DateWid;
			  global $DateRange;
			  global $Vacationdays;
			  $BgColor="#555555";
			  $fontColor="#ffffff";
		      $y=$DateRange[0];
			  $m=$DateRange[1];
			  $Ey=$DateRange[2];
			  $Em=$DateRange[3];
			  $LocX=  $CalendarX;
			  $LocY=  $startY;
			  $sdate=$y."_".$m;
			  $fdate=$DateRange[2]."_".$DateRange[3];
			  while ($sdate!=$fdate){
				    $days=getMonthDay($m,$y);
			        DrawRect($m,10,$fontColor,$LocX,$LocY-20,$days* $DateWid-1,20,$BgColor);
					$LocX+=$days* $DateWid;
					$m+=1;
				    $sdate=$y."_".$m;
			  }
			  $days=getMonthDay($m,$y);
					
			  /*
			  $sdate="";
			  $fdate=$DateRange[2]."_".$DateRange[3];
			
		
			  $t=0;
			  while ($sdate!=$fdate){
				     $days=getMonthDay($m,$y);
					 DrawRect($m,10,$fontColor,$LocX,$LocY-20,$days* $DateWid-1,20,$BgColor);
					 $arr=  ReturnVacationDays($y,$m,$Vacationdays);
					 DrawDays($days,$LocX,$LocY ,$DateWid,count($finalTasks), $arr,$y."_".$m);
					 $sdate=$y."_".$m;
					 if($sdate==$fdate)break;
					 $m+=1;
					 if($m>12){
						 $y+=1;
					 $m=1;
					 }
			        $t++;
					if($t>12)break;
					$LocX+=$days* $DateWid;
			  }
			  */
	 }
   function ListHero() {
	        global $HeroRes;
			$x=20;
			$y=100;
			$w=64;
			$h=64;
			$Layer=1;
            for($i=0;$i<count($HeroRes);$i++){
			    DrawHero($x,$y,$w,$h,$HeroRes[$i]);
			    $y+=70;
			}
   }
   function DrawHero($x,$y,$w,$h,$HeroData){
            $pic=getPicLink($HeroData[2]);
			$BGColor=returnColor($HeroData[11]);
			DrawRect("",$fontSize,$fontColor,$x-2,$y-2,$w+4,$h+4,$BGColor);
			DrawJavaDragPic($pic,$y+2,$x+2,$w-4,$h-4,$HeroData[2]);
		//	DrawPic_Layer($pic,$x+2,$y+2,$w-4,$h-4,$Layer);
			$msg=$HeroData[2].$HeroData[3];
			DrawRect($msg,10,"#ffffff",$x-2,$y+52,$w+4,14,$BGColor);
			$pic="Pics/construction.png";
		    if(strpos($HeroData[8],'已完成') ==false){
				DrawPic_Layer($pic,$x+$w-30,$y,32,32,$Layer);
			}
		 
   }
   function returnColor($ChineseColor){
	   if($ChineseColor=="紅")return "#cc1111";
	   if($ChineseColor=="藍")return "#11aacc";
	   if($ChineseColor=="綠")return "#11cc11";
	   if($ChineseColor=="紫")return "#aa11cc";
	    return "#000000";
   }
   function ListProgressHeros(){
            global $OnProgressHeros;
			global $startY;
			global $DateWid;
			global $LargeY;
			$y= $startY+$LargeY;
		 	$w=40;
			for($i=0;$i<count($OnProgressHeros);$i++){
			   if($OnProgressHeros[$i]!=""){
				  $y+=$w  ;
			      DrawEventHeroProgress($OnProgressHeros[$i],$y);
	     		}
			}
   }

   function DrawEventHeroProgress($HeroData,$y){
	        global $DateWid;
	   	    $startx=20;
            $w=40;
			$h=40;
            $pic= getPicLink($HeroData[2]);
			global $HeroSc;
			$Hs=filterArray($HeroSc,3,$HeroData[1]);
		    $fontColor="#ffffff";
		    $BGColor="#555555";
			$LastX= $startx;
			$upX;
			$y+=10;
			$LastState=0;
			//表單中的
			for($i=0;$i<count($Hs);$i++){
				$d=returnLocX($Hs[$i][2]);
			    if($d>0){
				    $x= $d*$DateWid+ $startx;
					if($i>0){
					  $w=$x-$upX;
					  DrawRect("",10,$fontColor,$upX,$y,$w,20,"#999999");
					}
					$w=$Hs[$i][6]*$DateWid;
				    $BGColor= returnTypeColor($Hs[$i][5]);
					DrawRect( $Hs[$i][5],10,$fontColor,$x,$y,$w,20, $BGColor);
					$x+=$w;
					$upX=$x;
					if($x>$LastX)$LastX=$x;
				}
				if($Hs[$i][5]=="設定")$t=1;
				if($Hs[$i][5]=="建模")$t=2;
				if($Hs[$i][5]=="動作")$t=3;
			    if($Hs[$i][5]=="特效")$t=4;
				if($t>$LastState)$LastState=$t;
			}
			//未排定
			$states=array("設定","建模","動作","特效","InGame");
		    if($LastState==0){
		           $d=returnLocX(date("Y_n_j"));
		           $LastX= $d*$DateWid+ $startx;
     		}
	         
			$x= $LastX;
			for($i=$LastState;$i<count($states);$i++){
				$BGColor2=returnTypeColor2( $states[$i]);
				$fontColor="#bbbbbb";
				DrawRect( $states[$i],10,$fontColor,$x,$y,$DateWid*10,20, $BGColor2);
				$x+=$DateWid*10;
			}
 
			$y-=10;
		    DrawRect( "" ,10,"#ffffff",$x ,$y,120,38,"#000000");
		    DrawJavaDragPic($pic,$y+2,$x+2,34,34,$HeroData[2]);
		  	DrawRect( $HeroData[2].$HeroData[3] ,10,"#ffffff",$x+40,$y,80,20,"#000000");
			//計算預計完成日
			 global $StartCalendarDay;
			 
			$Passday =($x- $startx)/$DateWid;
			$finDay= getPassDaysDay($StartCalendarDay,	$Passday);
		    $f= "Fin[".$finDay[0]."_".$finDay[1]."_".$finDay[2]."]";
		 	DrawRect($f,10,"#ffffff",$x+40,$y+20,78,16,$BGColor);
   }
   
 
   function getPicLink($GDcode){
            $pic="ResourceData/hero/viewPic/".$GDcode.".png";
		    if(!file_exists($pic))$pic="Pics/nopic.png";
            return $pic;
   }
    function returnTypeColor($type){
       if($type=="設定")return "#662222";
       if($type=="建模")return "#666622";
       if($type=="動作")return "#226666";
       if($type=="特效")return "#222266";
   }
   function returnTypeColor2($type){
       if($type=="設定")return "#998888";
       if($type=="建模")return "#999988";
       if($type=="動作")return "#889999";
       if($type=="特效")return "#888899";
	   if($type=="InGame")return "#777799";
   }
?>
<?php //快速表單
     function submitCont(){
		
			//  if( $_POST["DragID"]=="" &&   $_POST["target"]!= "target"){
				if($_POST["DragID"]=="")return;
			    if( $_POST["DragID"]!="DragIDs" &&   $_POST["target"]!= "target"){
			 
				    UPEdit();
			  }
			  
	 }		
	 function UPEdit(){
	          global $EventDatas;
			  global $URL;
			  $CE=explode("_",$_POST["target"]);
			  $CurrentE=filterArray($EventDatas,2,$CE[1]);
              $Hstr= ReturnHeros( $CurrentE[0][6],$_POST["DragID"]);
		      global $data_library,$tableName;
              $WHEREtable=array( "data_type", "EventSerialNum" );
		      $WHEREData=array( "ver",$CE[1] );
			  $Base=array("EventCharacter");
			  $up=array( $Hstr);
			  $stmt= MakeUpdateStmt(  $data_library,$tableName,$Base,$up,$WHEREtable,$WHEREData);
			  // echo $stmt;
			  SendCommand($stmt,$data_library);		
			  JavaPost($PostArray,$URL);
			//  echo " <script language='JavaScript'>".$URL."</script>"; 
	 }
	 
	 function ReturnHeros($BaseStr,$AddHero){
	          $strs= explode("_",$BaseStr);
			  $Rs="";
			  $bool=false;
			  for($i=0;$i<count($strs);$i++){
				  if($AddHero==$strs[$i])$bool=true;
			      if($AddHero!=$strs[$i]){
					 if( $strs[$i]!="")
				        if($i!=0) $Rs=$Rs."_";
				     $Rs=$Rs.$strs[$i];
				  }
			  }
			  if(!$bool){
				   if(count($strs)!=0) $Rs=$Rs."_"; 
				   $Rs=$Rs.$AddHero ;
			  }
			  return $Rs;
	 }
	 
     function CreatJavaForm(){
		      $x=1920;
			  $y=10;
		      global $URL;
		      $upFormVal=array("Show","Show",$URL);
			  $UpHidenVal=array(array("tablename","vtevent"),
			                    array("data_type","ver"),
								array( "Send","sendjava" ),
	                            );
		      $UpHidenVal=	addArray( $UpHidenVal,$typeArray);	
		      $inputVal=array(array("text","DragID","DragID",10,$x,$y,200,20,$BgColor,$fontColor,"DragIDs" ,10),
			                   array("text","target","target",10,$x+100,$y,200,20,$BgColor,$fontColor,"target" ,10),
						      

	                          );			 
		      upSubmitform($upFormVal,$UpHidenVal, $inputVal);
	 }
?>
<body bgcolor="#b5c4b1">