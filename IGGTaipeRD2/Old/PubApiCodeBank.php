<?php
	   function DrawOutLinkArea($StartX,$startY ,$BaseURL){
                $outTmp1=getMysqlDataArray("outsourcing");
				 $outTmp=filterArray($outTmp1,"0","data");
				 $x= $StartX;
	            for($i=0;$i<count( $outTmp);$i++){
					$Link=$BaseURL."?List=Out&user=".$outTmp[$i][1];
					$color="#444444";
				    $pic="Outsourcing/pic/".$outTmp[$i][13];
					$outName=substr($outTmp[$i][2],0,7);
					$color=getTypeColor($outTmp[$i][7]);
				    DrawMemberLinkRect(	$outName,"9","#ffffff", $x-2, $startY ,"70","40",$color,$outTmp[$i][7],$outTmp[$i][2], $Link,$pic);
				    $x+=  74;
			    }
	   }
?>