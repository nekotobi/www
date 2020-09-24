<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>英雄排程區</title>
</head>
<?php  //主控台
   $id=$_COOKIE['IGG_id'];
   include('PubApi.php');
   include('CalendarApi.php');  
   include('mysqlApi.php');
   include('HerojavaApi.php');
   defineData();
   DrawTitle();
   DrawButtons();
   DrawAllDragMats();
   DrawCallendarRange();
   typeCont();
   submitCont();
   DrawCurrentDate();
?>

<?php //主要資料
   function  defineData(){
	   	     global $SelectType;
			 $SelectType=$_POST["type"];
			 if($SelectType=="")$SelectType="Event";
             defineData_Base();
			 defineData_schedule();
			 defineData_mats();

   }
   function  defineData_Base(){
	         global $data_library,$tableName;
		     $data_library="iggtaiperd2";
			 $tableName="vtevent";
	   		 global $URL;
             $URL="HeroSchedule.php";
	         global $CalendarX, $startY;
		     $CalendarX=20;
			 $startY=40;
			 //日期
			 global $DateRange;
		 	 $DateRange=  gethDateRange();// array(2020,4,2021,2);
		     global $StartCalendarDay;
		     $StartCalendarDay=array( $DateRange[0],$DateRange[1],1);
			 global $DateWid;
			 $DateWid=6;
			 //post陣列
			 global $typeArray;
			 global $WebPostRecData;
			 $typeArray=array(array());
			 //mats
		     global  $matX,$matY;
			 $matX=20;
			 $matY=80;
   }
   function  gethDateRange(){
	         $sy=date("Y");
             $sm=date("n");
			 $sm-=1;
			 if($sm<1){
				$sm=12;
			   $sy-=1;
			}
			 if($sm==1){
				$sy =$sy-1;
			    $sm=12;
			}
			 return array($sy,$sm,$sy+1,$sm);
   }
   function  defineData_schedule(){
             //行程表
			 global $fpschedule;
			 $fpschedule=getMysqlDataArray("fpschedule");
			 global $TargetSchedule;
			 $TargetSchedule=filterArray(  $fpschedule,10,"目標"); 
			 global $HeroSc;
			 $HeroSc=filterArray(  $fpschedule,10,"角色"); 
		     global $mobSc;
			 $mobSc=filterArray(  $fpschedule,10,"怪物");
		     global $bossSc;
			 $mobSc=filterArray(  $fpschedule,10,"召喚獸王");
			 global $eventSc;
			 $eventSc=filterArray(  $fpschedule,10,"活動");
			 global $AllSchedule;
			 $AllSchedule=$HeroSc;
			 $AllSchedule=addArray( $AllSchedule,$mobSc);
             $AllSchedule=addArray( $AllSchedule,$bossSc);
			 $AllSchedule=addArray( $AllSchedule,$eventSc);
   }
   function  defineData_mats(){
			 //活動表
	   		 global $EventDatas;
			 global $SelectType;
			 $EventDatasT=getMysqlDataArray("vtevent");
			 $EventDatas=filterArray($EventDatasT,0,"ver");
			 //所有素材字串
			 global $InEventMats;
			 $num=6;
			 if( $SelectType=="Awake")$num=7;
			 $InEventMats=returnInEventMats($EventDatas,$num);
			 
			 //高度
			 global  $maxHeight;
			 $maxHeight = GetMaxHeight($EventDatas);
			 $ResData=getMysqlDataArray("fpresdata");
			 //英雄素材=========================================
		   
             global $HeroRes_All, $HeroRes; 
			 $HeroRes_All=filterArray($ResData,0,"hero");
			 $HeroResT2=filterArray(  $HeroRes_All,13,"");
			 $HeroRes= sortGDCodeArrays($HeroResT2 ,2 ,"true");
			 //怪物素材=========================================
			 global $MobRes;
		 	 $MobResT=filterArray($ResData,0,"mob");
			 $MobResT2=filterArray( $MobResT,13,"");
			 $MobRes= sortGDCodeArrays($MobResT2 ,2,"true");
			 //Boss素材=========================================
			 global $BossRes;
		 	 $BossResT=filterArray($ResData,0,"boss");
			 $BossResT2=filterArray( $BossResT,13,"");
			 $BossRes= sortGDCodeArrays($BossResT2 ,2,"true");
			 //Event素材=========================================
			 global $EventRes;
		 	 $EventResT=filterArray($ResData,0,"event");
			 $EventResT2=filterArray( $EventResT,13,"");
			 $EventRes= sortGDCodeArrays( $EventResT2 ,2,"true");
 
			 //所有素材陣列
             $AllMatRes=$HeroRes;	 
		  	 $AllMatRes=addArray($AllMatRes,$MobRes);
		     $AllMatRes=addArray($AllMatRes,$BossRes);
		     $AllMatRes=addArray($AllMatRes,$EventRes);
		    
		     //去掉已排入英雄
		     global $QueuedHerosData;
			 $QueuedHerosData= filterQueuedMat($HeroRes);
		     //去掉已排入怪物
		     global $QueuedMobData;
			 $QueuedMobData= filterQueuedMat($MobRes);
			 //去掉已排入boss
			 global $QueuedBossData;
			 $QueuedBossData= filterQueuedMat($BossRes);
			 //去掉已排入event
			 global $QueuedEventData;
			 $QueuedEventData= filterQueuedMat($EventRes);
			  //覺醒英雄
			 global $QueuedAwakeData;
			 $QueuedAwakeData= filterQueuedMat($HeroRes_All);
			 
			 //排定進度英雄
	         global $OnProgressMats;
			 $OnProgressMats=collectUnFinMats( $AllMatRes);
 
   }

?>
<?php //submitCont
     function typeCont(){
	           global $SelectType;
			   DrawEvents();
               CreatJavaForm();
		 
	 }
     function submitCont(){
		      global $data_library,$tableName;
			  global $URL;
		      //echo ">". $_POST["submitUp"];
              if($_POST["DragID"]!=""){
			  	 if( $_POST["DragID"]!="DragIDs" &&   $_POST["target"]!= "target") 
	                  UPEdit();
			  }
		 	  if ($_POST["submitUp"]!="" ){
			      $code=$_POST["code"];
				  $postArray=array(array("code", $code),array("submitUp","xxxx"));
			      DrawMysQLEdit($data_library,$tableName,$code,$URL,$PostArray,"修改資料",9);
		      }
			  if ($_POST["submit"]=="修改表單"){
				   $code=$_POST["code"];
				   upMysQLEdit($data_library,$tableName,$code,$URL,$PostArray ,"code" ,"ver");
				   ReLoad();
			  }
			  if($_POST["submitNew"]!=""){
                  AddEvent();
			  }
	 }
	 function DrawButtons(){
		      global $URL;
			  global $finalTasks;
			  global $CalendarX, $startY;
			  global $EventDatas;
			  $LocX= $CalendarX+300;
              $LocY=  $startY+8;
			  $w=80;
			  $type=$_POST["type"];
			  if($type=="")$type="Event";
			  $typeArray=array(array("Event","活動排程"),
			                   array("Awake","覺醒排程"));
							   //array("mob","scene");
			  for($i=0;$i<count($typeArray);$i++){
			  	  $BgColor="#aaaaaa";
				  $fontColor="#000000";
			      if($type==$typeArray[$i][0])$BgColor="#ffaaaa";
				   $sendarr= array(array("type",$typeArray[$i][0]) ) ;
		           sendVal($URL,$sendarr,"change",$typeArray[$i][1],array($LocX,$LocY,$w,18),10,$BgColor, $fontColor);
				   $LocX+=5+$w;
			  }
		      $startY+=35;
		
	 }
     function ReLoad(){
	    	   global $PostArray,$URL;
			   JavaPostArray($PostArray,$URL); 
	  }
?>
<?php //隱藏/取得進度
    function filterQueuedMat($matData){ //去掉已排入素材
			 global $InEventMats; 

			 $Arr=array();
			 for($i=0;$i<count($matData) ;$i++){   
			      if(!in_array( $matData[$i][2],  $InEventMats)){
					  array_push( $Arr,$matData[$i]);
				  }
			 }
			 return $Arr;
	}

    function collectUnFinMats($matData){
             global $InEventMats;
			 $OnProgressMats=array();
	         for($i=0;$i<count($InEventMats) ;$i++){
				 $hr=filterArray( $matData,2,$InEventMats[$i]);
			     if(strpos( $hr[0][8],'已完成') ==false){
				 array_push( $OnProgressMats,$hr[0]);
			   }
			 }
			 return   $OnProgressMats;
	}
 
	function returnInEventMats($EventDatas,$num){
	         $str="";
			 for($i=0;$i<count($EventDatas);$i++){
				 $str= $str.$EventDatas[$i][$num]."_";
			 }
			 return explode("_",$str) ;
	}
	function GetMaxHeight($EventDatas){
		     $maxHeight=0;
		     for($i=0;$i<count($EventDatas);$i++){
				  $EventHeros=explode("_", $EventDatas[$i][6]) ;
				  if($maxHeight<count($EventHeros))  $maxHeight=count($EventHeros);
		     }
			 return $maxHeight;
	}
?>
<?php //列印
   function DrawTitle(){
	        global $startY;
			DrawRect("",16,"#ffffff",20,$startY,1400,30,"#222222");
			DrawRect("  英雄進度及活動對照表",18,"#ffffff",20,$startY,200,30,"#222222");
           
   }
   function DrawEvents(){
            global $EventDatas;
		    global $CalendarX, $startY;
			global $maxHeight;
			DrawRect( $start."-".$end,10,$fontColor,$CalendarX,$startY,1640,($maxHeight+1)*64+20,"#c5d4c1");
			for($i=0;$i<count($EventDatas);$i++){
                DrawSingelEvent($EventDatas[$i],$i);
			}
    } 
   function DrawSingelEvent($EventData,$i  ){
		    global $DateWid;
	   	    global $CalendarX, $startY;
			global $typeArray,$URL;
		    $BgColor="#333333";
			$fontColor="#ffffff";
			$LocY=$startY;
	        if(returnLocX($EventData[5])==0)return;
   		    $LocX= (returnLocX($EventData[4])*$DateWid)+$CalendarX;
			$passDay= getPassDays( explode("_",$EventData[4]) ,explode("_",$EventData[5]));
			$w= $passDay*$DateWid;
			//標題
			$sendarr=addArray($typeArray,array(array("code",$EventData[9]) ));
			sendVal($URL,$sendarr,"submitUp",$EventData[3],array($LocX,$LocY,$w,20),10,$BgColor, $fontColor);
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
            global $SelectType;
			global $LargeY;//記錄高度
			$heroH=60;
			$mats=explode("_",$EventData[6]) ;
		    if( $SelectType=="Awake"){
		      $mats=explode("_",$EventData[7]) ;
			}
		    $id="E_".$EventData[2];
            $h=(count($mats))*$heroH +40;
	        if($h>$LargeY)$LargeY=$h;
		    DrawJavaDragArea("",$LocX,$LocY,$w,$h,"#999999",$fontColor,$id);
			//英雄區
			$x=$LocX+8;
			$y=$LocY+4; 
			for($i=0;$i<count($mats);$i++){
				if($mats[$i]!=""){
			 
				        $datas= returnRes($mats[$i]);
						if($datas!=null){
						   $matData=filterArray( $datas,2, $mats[$i]);
				           Drawmat($x,$y,$heroH,$heroH, $matData[0]);
						}
				      $y+=$heroH+8;
 
 
				   }
				}
    }   
   function returnRes($str){
	   		global $HeroRes;
			global $MobRes;
		    global $BossRes;
			global $EventRes;
			global  $HeroRes_All;
			global $SelectType;
	               $t=substr($str,0,1);
				      if($t=="h" && $SelectType=="Awake")return  $HeroRes_All;
			      	 if($t=="h")return  $HeroRes;
				     if($t=="m")return  $MobRes;
				     if($t=="b")return  $BossRes;
				     if($t=="e")return  $EventRes;
				
				   return null;
   }
   function Drawmat($x,$y,$w,$h,$matData){
            $pic=getPicLink($matData[2]);
			$BGColor=returnColor($matData[11]);
			DrawRect("",$fontSize,$fontColor,$x-2,$y-2,$w+4,$h+4,$BGColor);
			DrawJavaDragPic($pic,$y+2,$x+2,$w-4,$h-4,$matData[2]);
		//	DrawPic_Layer($pic,$x+2,$y+2,$w-4,$h-4,$Layer);
			$msg=$matData[2].$matData[3];
			DrawRect($msg,10,"#ffffff",$x-2,$y+52,$w+4,14,$BGColor);
			$pic="Pics/construction.png";
		    if(strpos($matData[8],'已完成') ==false){
				$matType=returnMatType($matData[2]);
				DrawMatProgress($matData,$x,$y-19,$w,$h ,$MatType);
		     	DrawPic_Layer($pic,$x+$w-20,$y+35,16,16,$Layer);
			}
		 
   }
   function DrawAllDragMats(){
	        global $SelectType;
			if($SelectType=="" || $SelectType=="Event"){
	           global $QueuedHerosData;
	           DrawDragMat($QueuedHerosData,"Hero");
			   global $QueuedMobData;
	           DrawDragMat($QueuedMobData,"Mob");
		       global $QueuedBossData;
	           DrawDragMat($QueuedBossData,"boss");
			   global $QueuedEventData;
	           DrawDragMat($QueuedEventData,"event");
			}
		 	if($SelectType=="Awake" ){
			    global $QueuedAwakeData;
	           DrawDragMat($QueuedAwakeData,"AwHero");
			}

			global  $matX,$matY;
		    global  $CalendarX, $startY;
	        $startY=$matY+55;
    }
   function DrawDragMat($matDatas,$MatType){    
		   	global  $CalendarX, $startY;
			global  $matX,$matY;
			global  $DateY;
			$w=40;
			$h=55;
		    $BgColor="#222222";
            if($MatType=="Mob"){
			    $matX=20;
			    $matY+=$h+20;
			}
		    DrawRect($MatType ,10,"#ffffff",$matX, $matY,$w,50,$BgColor);
		    DrawRect("(x".count($matDatas).")" ,10,"#ffffff",$matX+3, $matY+33,$w-7,10,"#882222");
			$matX+=$w;

            for($i=0;$i<count( $matDatas);$i++){
                DragSingleMat($matDatas[$i],$matX, $matY,$w,$h,$MatType);
			    $matX+=$w;
				if( $matX>1400){
					$matX=20;
				    $matY+=$h+2;
				}
			}
			$matX+=20;
            $DateY=$matY+$h+25;
   }
   function DragSingleMat($data,$x,$y,$w,$h,$MatType){
	        $BgColor="#222222";
   			$id= $data [2];
			$pic=getPicLink($data [2]);
			DrawJavaDragPic($pic,$y,$x,$w,$h,$id);
		    DrawRect($data[3],9,"#ffffff",$x,$y+28,$w,14,$BgColor);
		    DrawMatProgress( $data ,$x,$y,$w,$h,$MatType);
			DrawRect($data[2],7,"#cccccc",$x,$y+45 ,$w,8,$BgColor);
   }
   function DrawMatProgress($HeroData,$x,$y,$w,$h,$MatType){
		     $BgColor="#222222";
			 $y+=$w;
			 $add=0;
			 if($_POST["type"]=="Awake") {
				 $add=14;
 
			 }
			 //$w=1;
			 DrawRect("",10,"#ffffff",$x,$y,$w,10,$BgColor);
		  //  if($MatType=="event"){
				 $s=0;
				for($i=0;$i<4;$i++){
					if($i!=2){
					  $BgColor="#117711";
			          if($HeroData[$i+5+$add]!="" && $HeroData[$i+5+$add]!=">"){
				 	     if(strpos($HeroData[$i+5+$add],'已完成') !== false) $BgColor="#99ff99";
				          DrawRect("",10,"#ffffff",($x+1+$s*14),$y+1,11,8,$BgColor);
					    }
						$s+=1;
				     }
			    }
				return;
			// }
			 for($i=0;$i<4;$i++){
				  $BgColor="#117711";
			      if($HeroData[$i+5]!="" && $HeroData[$i+5]!=">"){
				 	 if(strpos($HeroData[$i+5],'已完成') !== false) $BgColor="#99ff99";
				      DrawRect("",10,"#ffffff",($x+1+$i*10),$y+1,9,8,$BgColor);
				 }
			 }
	 
	}
   function DrawCallendarRange(){
		      global $finalTasks;
			  global $CalendarX, $startY;
			  global $DateWid;
			  global $DateRange;
			  global $Vacationdays;
			  global $EventDatas;
			  global $URL;
			  $BgColor="#222222";
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
				    $w=$days* $DateWid-1;
			        DrawRect($m,10,$fontColor,$LocX,$LocY ,$w,20,$BgColor);
					$sendarr= array(array("eventSn",count($EventDatas)+1 ) ,array("newM",$m));
			        sendVal($URL,$sendarr,"submitNew","+",array($LocX+$w-20,$LocY+1,18,18),10,"#662222","#993333");
					$LocX+=$days* $DateWid;
					$m+=1;
					if($m>12){
						$m=1;
						$y+=1;
					}
				    $sdate=$y."_".$m;
			  }
			  $days=getMonthDay($m,$y);		
	 	      $startY+=20;
	 }
   function ListProgressHeros(){
            global $OnProgressMats;
			global $startY;
			global $DateWid;
			global $LargeY;
			if($LargeY=="")$LargeY=-30;
			$y= $startY+$LargeY;
		 	$w=40;
			for($i=0;$i<count($OnProgressMats);$i++){
			   if($OnProgressMats[$i]!=""){
				  $y+=$w  ;
			      DrawEventMatProgress($OnProgressMats[$i],$y);
	     		}
			}
   }
   function DrawEventMatProgress($matData,$y){
	        global $DateWid;
	   	    $startx=20;
            $w=40;
			$h=40;
            $pic= getPicLink($matData[2]);
		    $matType=returnMatType($matData[2]);
			global  $AllSchedule;//$HeroSc;
			$Hs=filterArray( $AllSchedule,3,$matData[1]);
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
			$states=array(array("設定",10),array("建模",10),array("動作",10),array("特效",6),array("InGame",5));
			if($matType=="event"){
			   $states=array(array("設定",10),array("建模",30)  ,array("特效",6) );
			   if($t==2)$t==3;
			}
		    if($LastState==0){
		           $d=returnLocX(date("Y_n_j"));
		           $LastX= $d*$DateWid+ $startx;
     		}
			$x= $LastX;
			for($i=$LastState;$i<count($states);$i++){
				$BGColor2=returnTypeColor2( $states[$i][0]);
				$fontColor="#bbbbbb";
				DrawRect( $states[$i][0],10,$fontColor,$x,$y,$DateWid*$states[$i][1],20, $BGColor2);
				$x+=$DateWid*$states[$i][1];
			}
 
			$y-=10;
		    DrawRect( "" ,10,"#ffffff",$x ,$y,120,38,"#000000");
		    DrawJavaDragPic($pic,$y+2,$x+2,34,34,$matData[2]);
		  	DrawRect( $matData[2].$matData[3] ,10,"#ffffff",$x+40,$y,80,20,"#000000");
			//計算預計完成日
			 global $StartCalendarDay;
			 
			$Passday =($x- $startx)/$DateWid;
			$finDay= getPassDaysDay($StartCalendarDay,	$Passday);
		    $f= "Fin[".$finDay[0]."_".$finDay[1]."_".$finDay[2]."]";
		 	DrawRect($f,10,"#ffffff",$x+40,$y+20,78,16,$BGColor);
   }
   function DrawCurrentDate(){
	        global  $DateY;
			global  $DateWid;
			 global $CalendarX;
            $date=date("Y_n_j");
			$x= returnLocX($date);
			//echo $date.">".$x;
			DrawRect(" " ,10,"#ffffff",$CalendarX+$x*$DateWid,  $DateY,1,600,"#6699bb");
			DrawRect($date,8,"#ffffff",$CalendarX+$x*$DateWid-45,  $DateY-12,45,12,"#6699bb");
   }
   function DrawTarget(){

   }
?>
<?php //function
   function returnLocX($date){
	   	    global $CalendarX, $startY;
		    global $DateRange;
			global $StartCalendarDay;
	        $day = explode("_",$date);
		    $passDay= getPassDays( $StartCalendarDay,$day);
		    
			return $passDay;
		         
   }
   function getPicLink($GDcode){
	        $MatType= returnMatType($GDcode);
            $pic="ResourceData/".$MatType."/viewPic/".$GDcode.".png";
		    if(!file_exists($pic))$pic="Pics/nopic.png";
            return $pic;
   }
   function returnMatType($GDcode){
            $t=substr($GDcode,0,1);
			$MatType="hero";
			if($t=="m")	$MatType="mob";
		    if($t=="b")	$MatType="boss";
		    if($t=="e")	$MatType="event";
			return $MatType;
   }
   function returnColor($ChineseColor){
	   if($ChineseColor=="紅")return "#cc1111";
	   if($ChineseColor=="藍")return "#11aacc";
	   if($ChineseColor=="綠")return "#11cc11";
	   if($ChineseColor=="紫")return "#aa11cc";
	    return "#000000";
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
	 function AddEvent(){
	          global $data_library,$tableName;
			  global $PostArray,$URL;
			  $code=returnDataCode( );
			  $sd=date("Y")."_".$_POST["newM"]."_1";
			   $ed=date("Y")."_".$_POST["newM"]."_28";
			  $sendVal=array(
			           "data_type"=>"ver",
			           "Ver"=>"1.".$_POST["eventSn"].".0",
			           "EventSerialNum"=>$_POST["eventSn"],
					   "EventName"=>"未定",
					   "EventStartDay"=>$sd,
					   "EventEndDay"=>$ed,
					   "code"=>$code);
			  FastAddMysQLData($data_library,$tableName,$code,$URL,$sendVal);
              $PostArray=array(array("submitUp","cc"),array("code",$code));
		      ReLoad();
	 }
	 function UPEdit(){
	          global $EventDatas;
			  global $URL;
			  $CE=explode("_",$_POST["target"]);
			  $CurrentE=filterArray($EventDatas,2,$CE[1]);
            //  $Hstr= ReturnHeros( $CurrentE[0][6],$_POST["DragID"]);
		      global $data_library,$tableName;
              $WHEREtable=array( "data_type", "EventSerialNum" );
		      $WHEREData=array( "ver",$CE[1] );
			  $Base=array("EventCharacter");
			  $up=array( ReturnHeros( $CurrentE[0][6],$_POST["DragID"]) );
			  if($_POST["type"]=="Awake") {
			     $Base=array("EventScene");
				  $up=array( ReturnHeros( $CurrentE[0][7],$_POST["DragID"]) );
			  }
			  $stmt= MakeUpdateStmt(  $data_library,$tableName,$Base,$up,$WHEREtable,$WHEREData);
			 // echo $stmt;
			  SendCommand($stmt,$data_library);		
			  $PostArray=array("type");
			  JavaPost($PostArray,$URL);
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
				   if(count($strs)!=0)
					     if( $strs[0]!="")
					           $Rs=$Rs."_"; 
				   $Rs=$Rs.$AddHero ;
			  }
			  return $Rs;
	 } 
     function CreatJavaForm(){
		      $x=1920;
			  $y=10;
		      global $URL;
			  global $SelectType;
		      $upFormVal=array("Show","Show",$URL);
			  $UpHidenVal=array(array("tablename","vtevent"),
			                    array("data_type","ver"),
								array( "Send","sendjava" ),
								array( "type",$SelectType),
	                            );
		      $UpHidenVal=	addArray( $UpHidenVal,$typeArray);	
		      $inputVal=array(array("text","DragID","DragID",10,$x,$y,200,20,$BgColor,$fontColor,"DragIDs" ,10),
			                   array("text","target","target",10,$x+100,$y,200,20,$BgColor,$fontColor,"target" ,10),
						       
	                          );			 
		      upSubmitform($upFormVal,$UpHidenVal, $inputVal);
	 }
?>
<?php //不用
/*
    function filterQueuedMatOld($matData){ //去掉已排入素材
			 global $InEventMats; 
			 for($i=0;$i<count($matData) ;$i++){   
			      if(in_array( $matData[$i][2],  $InEventMats)){
					  unset( $matData[$i]);
				  }
			 }
			 return  array_values($matData);
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
   */
?>
<body bgcolor="#b5c4b1">