<?php  //選擇專案
      	  function ProAPI_DrawProjectButtoms($ProjectTypes,$selectProject,$startY,$URL){
		     //  global $ProjectTypes,$selectProject;
			  // global $startY;
			  // global $URL;
			   $BgColor="#111111";
			   $SubmitName="submit";
			   for($i=0;$i<count( $ProjectTypes);$i++){
				   $ArrayVal=array(array("selectProject",$ProjectTypes[$i]));
				   $BgColor="#111111";
				   if($selectProject==$ProjectTypes[$i]){ $BgColor="#aa1111";}
				   $Rect=array(400+$i*60,10,58,12);
				   $SubmitVal=$ProjectTypes[$i];
			       sendVal($URL,$ArrayVal,$SubmitName,$SubmitVal,$Rect,10, $BgColor , "#ffffff","true");
			   } 
			      

	  }
?>