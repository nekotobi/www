<?php //統計
      function Resstatistics(){
	          global $ResTypeSingleData;
			  $AssemblyType=explode("_", $ResTypeSingleData[3]);
              DrawAssemblyType(  $AssemblyType);
			  //廠商整理
			  collectionOuts();
			  ListOuts();
	  }
      function DrawAssemblyType(  $AssemblyType){
		       global $WebSendVal;
			   global $URL;
			   $valArray =$WebSendVal; 
			   $Rect=array(300,80,40,20);
			   global   $SubmitName;
	  		   for($i=0;$i<count($AssemblyType);$i++){
				   $str=$AssemblyType[$i]."[".$i;
				   $BgColor="#222222";
				   $valArray[4][1]=$str;
				   if( $WebSendVal[4][1]==$str)$BgColor="#ff6666";
			       sendVal($URL,  $valArray ,$SubmitName,$str,$Rect,10,$BgColor);
                   $Rect[0] += ($Rect[2]+1);				   
  			   }
	  }
	  function collectionOuts(){
	           global $Resdatas;
			   global $Outs, $OutsWorks;
			   $sort=explode("[",$_POST["AssemblyType"]);
               $ResSort=$sort[1];
			   $Outs=array();
	           for($i=0;$i<count($Resdatas);$i++){
				   $str= ReturnStr($Resdatas[$i], 10,$ResSort );//外包
			       if(  $str!=""){
					  if(!in_array( $str,$Outs)){
					  array_push($Outs, $str );
					  }
			       }
			   }
			   $OutsWorks;
			  // print_r($Outs);
			   for($i=0;$i<count($Outs);$i++){
			       $arr= ReturnOutsArr($Outs[$i],$ResSort) ;
				   $OutsWorks[$Outs[$i]]=$arr;
			   }
	      
	  }
	  function ReturnOutsArr($outsName, $ResSort){ //外包名 流水線編號
	           global $Resdatas;
			   $arr=array();
			   for($i=0;$i<count($Resdatas);$i++){
				   $str= ReturnStr($Resdatas[$i],10,$ResSort);
			       if($outsName==$str){
					   $name=$Resdatas[$i][3].$Resdatas[$i][3];
					   $cost=ReturnStr($Resdatas[$i] ,15,$ResSort );
					   array_push(  $arr,array($Resdatas[$i][3],$Resdatas[$i][4],$cost));
			       }
			   }
			   return $arr;
	  }
	  function ReturnStr($data,$dataSort,$ResSort){
		       $arr=explode("=",$data[$dataSort]); 
			   $str=$arr[$ResSort];
			   return $str;
	  }
	  //列印
	  function ListOuts(){
		       global $Outs, $OutsWorks;
			   global $AssemblyRect;
			   $AssemblyRect =array(20,120,100,80);
			   for($i=0;$i<count($Outs);$i++){
				   DrawRect($Outs[$i],"12","#ffffff",$AssemblyRect,"#000000" );
				   ListOutsingle($Outs[$i],$OutsWorks[$Outs[$i]]);
				   $AssemblyRect[1]+=   $AssemblyRect[3]+2;
			   }
	  }
	  function ListOutsingle($Outsname,$Outsdata){
		    
		       global $AssemblyRect;
			   $Rect=$AssemblyRect;
			   $Rect[2]=$Rect[3];
			   $Rect[0]+=102;
			   $fontColor="#ffffff";
	           for($i=0;$i<count($Outsdata);$i++){
				   
				   $code=$Outsdata[$i][0];
				   DrawPic( returnPicPath($code ),$Rect );// $noPic
				   $re=array($Rect[0],$Rect[1]+$Rect[2]-15,$Rect[2],15);
				   DrawRect($Outsdata[$i][0].$Outsdata[$i][1],9,$fontColor,$re,"#333333");
				   if($Outsdata[$i][2]!=""){
			       $re2=array($Rect[0]+$Rect[2]-20,$Rect[1]+$Rect[2]-25,20,10);
 
				   DrawRect($Outsdata[$i][2] ,8,$fontColor,$re2,"#aa8855");
				   }
				   $Rect[0]+=  $Rect[2]+1;
				  
			   }
	  }
?>