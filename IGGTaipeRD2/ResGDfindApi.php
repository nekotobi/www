
<?php
   require_once 'PubApi.php';
  // $GDCodeArray=array("h0001","h0004");
  // returnXlsArray( "英雄",$GDCodeArray);
  function returnXlsArray($type ,$GDCodeArray){
           $dir ="../FPGDData/03_角色怪物文件/角色動作特效需求/".$type; 
           $asDir= ReturnPhpDir($dir);	
	       $file=scandir( $asDir);	
           $BaseFiles= returnfilePathArray( $asDir, $file);
		   $ra= filterGcodeArray($GDCodeArray,  $BaseFiles);
		   return $ra;
		 
  }
  function filterGcodeArray($GDCodeArray,  $BaseFiles){
	       $ra=array();
		   for($i=0;$i<count($GDCodeArray);$i++){
	          $c=  returnCodePath($GDCodeArray[$i], $BaseFiles);
			   array_push($ra,$c);
	       }
		   return $ra;
  }
  function returnCodePath($GDCode,$BaseFiles){
	       for($i=0;$i<count($BaseFiles);$i++){
	          if($GDCode==$BaseFiles[$i][0]){
			     return $BaseFiles[$i][1];
			  }
	       }
  }
  function returnfilePathArray($dir, $file){
	  $filePathArray=array();
	  for ($i=0;$i<count($file);$i++){
	       $Link= $dir."/".$file[$i];
		   $f=iconv("BIG5", "UTF-8", $file[$i]);
           $tmp=explode("_",$f);
		   $l=iconv("BIG5", "UTF-8", $Link);
		   $a=array($tmp[0],$l);
	       array_push($filePathArray,$a);
     }
	 return $filePathArray;
 }


?>
<?php //tmp

 
 function Listxls($dir, $file){
	  for ($i=0;$i<count($file);$i++){
	  $Link= $dir."/".$file[$i];
 	  $fn=iconv("BIG5", "UTF-8",$file[$i]);
	  $l=iconv("BIG5", "UTF-8", $Link);
	  //echo "<br>".$i.$file[$i];
	  DrawLinkRect_Layer($fn,12,"#ffffff",array(10,22*$i,100,20),"#000000",$l,$border,$Layer);
     }
 }
  function tmp2($type="英雄"){
     $dir ="../FPGDData/03_角色怪物文件/角色動作特效需求/".$type; 
	 $asDir= ReturnPhpDir($dir);
	 $file=scandir( $asDir);
     Listxls($asDir, $file);
 }
?>