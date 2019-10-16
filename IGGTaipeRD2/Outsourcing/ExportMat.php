<?php
require_once dirname(dirname(dirname(__FILE__))) .'/phpexcel/Classes/PHPExcel.php';
require_once 'xlsApiv2.php';
 
DefineData();

function DefineData(){
         global $sn;
         global $Exporttype;
		 global $outsDetial,$OutsCost, $outsData;
		 //詳細表單
		 $outsDetialT=getMysqlDataArray("outsdetail");
		 $outsDetial= filterArray( $outsDetialT,1,$sn);
 
		 //外包單
		 $OutsCostT=getMysqlDataArray("fpoutsourcingcost");
		 $OutsCost= filterArray(  $OutsCostT,1,$sn);
		 //外包名單
		 $code=$OutsCost[0][15];
		 $outsDataT=getMysqlDataArray("outsourcing");
		 $outsData=filterArray(  $outsDataT,1,$code);
		 setMatData();
	 	 //分類
         if($Exporttype=="mat1")creatMat1();
		 if($Exporttype=="mat3")creatMat3();
	     if($Exporttype=="Quote") creatQuote() ;
}
 

function setMatData(){
	     global $sn;
		 global $outsDetial;
		 global $outsDetial,$OutsCost, $outsData; 
		 global $baseData,$demand;
         $baseData=array();
		 $principal="黃謙信";
		 $baseData=array(
		     "project"=>"FP",
		     "principal"=>$principal,
		     "startDay"=>$OutsCost[0][14],
			 "currency"=>$outsData[0][30],
			 "Outsourcing"=> $outsData[0][15],
			 "hourprice"=>$outsData[0][3],
			 "tex"=>"0.0%"
		 );
		 $demand=array();
		 for($i=0;$i<count($outsDetial);$i++){
			 // echo $outsDetial[$i][3];
		     $tmp=  array(
			   "type"=>$outsDetial[$i][3],
			    "content"=>$outsDetial[$i][4],
				"number"=>$outsDetial[$i][6],
				"workingHours"=>$outsDetial[$i][7],
				"valuation"=>$outsDetial[$i][8],
				"starDate"=>$outsDetial[$i][9],
				"EndDate"=>$outsDetial[$i][10],
				"detail"=>$outsDetial[$i][5],
              );
			  array_push($demand,$tmp);
		 }
 
}


?>
<?php
function creatMat1(){
	global $baseData,$demand;
	$ProjectTitle="《".$baseData[project].'》';
	$Currency="估价（".$baseData[currency]."）";
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0)  ;
    $CurrencyType=$baseData[currency];
	//設定欄寬
	foreach(range('A','G') as $col){
		$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setWidth(12); 
	}  
    setCellStyle($objPHPExcel,'项目外包需求申请单','A1',"20",'center','A1:G1',"");
    //3
	setCellStyle($objPHPExcel,'项目','A3',"10",'center',$merge,'A3');
	setCellStyle($objPHPExcel,$ProjectTitle,'B3',"10",'center','B3:C3','B3:C3');
	setCellStyle($objPHPExcel,'申请人','D3',"10",'center','','D3');
	setCellStyle($objPHPExcel,$baseData[principal],'E3',"10",'center','','E3');
	setCellStyle($objPHPExcel,'时间','F3',"10",'center','','F3');
	setCellStyle($objPHPExcel,$baseData[startDay],'G3',"10",'center','','G3');
	//4
	$objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(40);
	setCellStyle($objPHPExcel,'事由','A4',"10",'center','','A4');
	setCellStyle($objPHPExcel,$ProjectTitle.'项目美术外包需求申请单','B4',"10",'center','B4:G4','B4:G4');

	//5
	setCellStyle($objPHPExcel,'制作类型','B5',"10",'center',$merge,'B5');
	setCellStyle($objPHPExcel,'内容','C5',"10",'center',$merge,'C5');
	setCellStyle($objPHPExcel,'数量','D5',"10",'center',$merge,'D5');
	setCellStyle($objPHPExcel,'工期（小时）','E5',"10",'center',$merge,'E5');
	setCellStyle($objPHPExcel, $Currency,'F5',"10",'center','F5:G5','F5:G5');
   
	//所有內容量6==>
	$Nstart=6;
	$totalAmout=0;
	for($i=0;$i<count($demand);$i++){
		$a=$Nstart;
		$totalAmout+=$demand[$i][valuation];
	    setCellStyle($objPHPExcel,$demand[$i][type],'B'.$a,"10",'center',$merge,'B'.$a);
		$objPHPExcel->getActiveSheet()->getRowDimension($Nstart)->setRowHeight(30);
    	setCellStyle($objPHPExcel,$demand[$i][content],'C'.$a,"10",'center',$merge,'C'.$a);
	    setCellStyle($objPHPExcel,$demand[$i][number],'D'.$a,"10",'center',$merge,'D'.$a);
	    setCellStyle($objPHPExcel,$demand[$i][workingHours],'E'.$a,"10",'center',$merge,'E'.$a);
	$m='F'.$a.':G'.$a;
	setCellStyle($objPHPExcel,$demand[$i][valuation],'F'.$a,"10",'center', $m,	$m);
    $area='F'.$a;
	makeCurrency($area, $CurrencyType,$objPHPExcel);
	$Nstart+=1;
	}
	//需求
	//$Nstart=($start+count($demand)-1);
	$Range='A5:A'.($Nstart-1);
	setCellStyle($objPHPExcel,'需求清单','A5',"10",'center',$Range,$Range);
	//其他資訊
	//$Nstart+=1;
	$Range='A'.$Nstart.":C".$Nstart;
	setCellStyle($objPHPExcel,'总预算','A'.$Nstart,"10",'center',$Range,$Range);
	$Range='D'.$Nstart.":G".$Nstart;
	setCellStyle($objPHPExcel,$totalAmout,'D'.$Nstart,"10",'center',$Range,$Range);
	 makeCurrency('D'.$Nstart,$Currency,$objPHPExcel) ; 
//	$objPHPExcel->getActiveSheet()->getStyle('D'.$Nstart)->getNumberFormat()->setFormatCode('$#,##0;-$#,##0');
    //簽字
	$Nstart+=1;
	$Range='A'.$Nstart.":C".$Nstart;
	setCellStyle($objPHPExcel,'相关部门负责人签字','A'.$Nstart,"10",'center',$Range,$Range);
	$Range='D'.$Nstart.":G".$Nstart;
	setCellStyle($objPHPExcel,"",'D'.$Nstart,"10",'center',$Range,$Range);
	$objPHPExcel->getActiveSheet()->getRowDimension($Nstart)->setRowHeight(40);
	//外包
	$Nstart+=1;
	$objPHPExcel->getActiveSheet()->getRowDimension($Nstart)->setRowHeight(2);
	$Nstart+=1;
	$Range='A'.$Nstart.":C".$Nstart;
	setCellStyle($objPHPExcel,'外包公司或个人','A'.$Nstart,"10",'center',$Range,$Range);
	setCellStyle($objPHPExcel,'价格','D'.$Nstart,"10",'center',"",'D'.$Nstart);
	setCellStyle($objPHPExcel,'起始时间','E'.$Nstart,"10",'center',"",'E'.$Nstart);
	$Range='F'.$Nstart.":G".$Nstart;
	setCellStyle($objPHPExcel,'其他条件','F'.$Nstart,"10",'center',$Range,$Range);
	$objPHPExcel->getActiveSheet()->getRowDimension($Nstart)->setRowHeight(40);
	//外包名稱
	$Nstart+=1;
    $Range='A'.$Nstart.":C".$Nstart;
	setCellStyle($objPHPExcel,$baseData[Outsourcing],'A'.$Nstart,"10",'center',$Range,$Range);
	setCellStyle($objPHPExcel,$totalAmout,'D'.$Nstart,"10",'center',"",'D'.$Nstart);
	 makeCurrency('D'.$Nstart,$Currency,$objPHPExcel);
    //$objPHPExcel->getActiveSheet()->getStyle('D'.$Nstart)->getNumberFormat()->setFormatCode('$#,##0;-$#,##0');
	
	setCellStyle($objPHPExcel,$baseData[startDay],'E'.$Nstart,"10",'center',"",'E'.$Nstart);
	$Range='F'.$Nstart.":G".$Nstart;
	setCellStyle($objPHPExcel,'','F'.$Nstart,"10",'center',$Range,$Range);
	$objPHPExcel->getActiveSheet()->getRowDimension($Nstart)->setRowHeight(40);
	//CEO
	$Nstart+=1;
	$Range='A'.$Nstart.":C".$Nstart;
	setCellStyle($objPHPExcel,"CEO签字",'A'.$Nstart,"10",'center',$Range,$Range);
	$Range='D'.$Nstart.":G".$Nstart;
	setCellStyle($objPHPExcel,' ','D'.$Nstart,"10",'center',$Range,$Range);
	$objPHPExcel->getActiveSheet()->getRowDimension($Nstart)->setRowHeight(40);
	saveExcel($objPHPExcel,"材料1：项目外包需求申请单.xls");
}
function makeCurrency($area,$Currency,$objPHPExcel){
       $objPHPExcel->getActiveSheet()->getStyle($area)->getNumberFormat()->setFormatCode('¥#,##0;-$#,##0');
	   if($baseData=="人民幣")
	   $objPHPExcel->getActiveSheet()->getStyle($area)->getNumberFormat()->setFormatCode('$#,##0;-$#,##0');

}
function creatMat3(){
      global $baseData,$demand;
	  $objPHPExcel = new PHPExcel();
      $objPHPExcel->setActiveSheetIndex(0)  ;
	  //設定欄寬
	  $ColWidth=array(
	  array('A',11),
	   array('B',41),
	    array('C',8),
		 array('D',8),
		  array('E',8),
		   array('F',8),
		    array('G',15),
			  array('H',15),
	  );
	  for($i=0;$i<count($ColWidth);$i++){
	     	$objPHPExcel->getActiveSheet()->getColumnDimension($ColWidth[$i][0])->setWidth($ColWidth[$i][1]); 
	  }
	  $title=array(
	  array('A',"制作类型","type"),
	   array('B',"内容","content"),
	    array('C',"数量","number"),
		 array('D',"工时（小时）","workingHours"),
		  array('E',"工时单价","hourprice"),
		   array('F',"合计","Total"),
		    array('G',"开始时间","starDate"),
			  array('H',"完成时间","EndDate"),
	  );

	  //第一行
	  for($i=0;$i<count( $title);$i++){
		  $area=($title[$i][0]."1");
	     	  setCellStyle($objPHPExcel,$title[$i][1],$area,"10",'center',"",$area);
			  $objPHPExcel->getActiveSheet()->getStyle($area)->getFont()->getColor()->setARGB('FFFFFFFF'); 
			  $objPHPExcel->getActiveSheet()
			  ->getStyle($area)
			  ->getFill()
			  ->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' =>'000000'))); 

	  }
	  //詳細資料

	  $Nstart=2;
	  $total=0;
	  for($i=0;$i<count( $demand);$i++){
		  $total+=$demand[$i]["workingHours"];
		    for($j=0;$j<count( $title);$j++){
				 $area=($title[$j][0].$Nstart);
				 $info=$demand[$i][$title[$j][2]];
				 if($title[$j][1]=="工时单价")$info=$baseData["hourprice"];
				 if($title[$j][1]=="合计")$info=$baseData["hourprice"]*$demand[$i]["workingHours"];
				 if($title[$j][1]=="内容")$info= $demand[$i]["content"]. $demand[$i]["detail"];
				 if ($info!=""){
				     setCellStyle($objPHPExcel, $info,$area,"10",'center',"",$area);
				 }
			}
	      $Nstart+=1;
	  }
	 
	  
	  //工時總計
	  $area=("D".$Nstart);
	  setCellStyle($objPHPExcel, $total,$area,"10",'center',"",$area);
	  //合計
	  $Nstart+=2;
	  $area=("E".$Nstart);
	  setCellStyle($objPHPExcel, "总计",$area,"10",'center',"",$area);
	  //總金額
	  $area=("F".$Nstart);
	  $cost=$total*$baseData["hourprice"];
	  setCellStyle($objPHPExcel,  $cost,$area,"10",'center',"",$area);
	  	   //外框
	  $area="A1:"."H".(count( $demand)+2);
	  $borderStyle="PHPExcel_Style_Border::BORDER_MEDIUM";
      DrawLineOut($objPHPExcel,$area,$borderStyle);
	  //外框2
	  $area="E".$Nstart.":"."F".$Nstart;
	  $borderStyle="PHPExcel_Style_Border::BORDER_MEDIUM";
      DrawLineOut($objPHPExcel,$area,$borderStyle);
	//  setCellStyle($objPHPExcel,'项目外包需求申请单','A1',"20",'center','A1:G1',"");
	  saveExcel($objPHPExcel,"材料3：合同报价单.xls"); 
}

function  creatQuote(){
	  global $baseData,$demand;
	   global $outsDetial,$OutsCost, $outsData;
	  $objPHPExcel = new PHPExcel();
      $objPHPExcel->setActiveSheetIndex(0)  ;
	  //設定欄寬
	  $ColWidth=array(
	  array('A',10),
	   array('B',51),
	    array('C',8),
		 array('D',12),
		  array('E',12),
		   array('F',8),
		    array('G',15)
	  );
	  for($i=0;$i<count($ColWidth);$i++){
	     	$objPHPExcel->getActiveSheet()->getColumnDimension($ColWidth[$i][0])->setWidth($ColWidth[$i][1]); 
	  }
	  $title=array(
	  array('A',"制作类型","type"),
	   array('B',"内容","content"),
	    array('C',"数量","number"),
		 array('D',"总工时（小時）","workingHours"),
		  array('E',"小時单价（".$baseData["currency"]."）","hourprice"),
		   array('F',"税点","Tex"),
		    array('G',"總額","Total")
	  );
	 //第一行
	  for($i=0;$i<count( $title);$i++){
		      $area=($title[$i][0]."1");
	     	  setCellStyle($objPHPExcel,$title[$i][1],$area,"10",'center',"",$area);
			  Fill_Solid($objPHPExcel,$area,"#ffffffff","#000000");
			  $objPHPExcel->getActiveSheet()->getStyle($area)->getAlignment()->setWrapText(true);
	  }
	  $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(35); 
	  //動作類型
	  $area="A2";
	  $merg="A2:A".(count($demand)+2);
	  $msg=collectWork($outsDetial);
	  setCellStyle($objPHPExcel, $msg,$area,"10",'center',$merg,$merg);
      $objPHPExcel->getActiveSheet()->getStyle($area)->getAlignment()->setWrapText(true);
	  //詳細資料
	  $Nstart=2;
	  $total=0;
	  $totalNum=0;
	  $totalHours=0;
	  for($i=0;$i<count( $demand);$i++){
		  $t  = $baseData["hourprice"]*$demand[$i]["workingHours"];
		  $total+=$t;
		  $totalNum+=$demand[$i]["number"];
		  $totalHours+=$demand[$i]["workingHours"];
		    for($j=1;$j<count( $title);$j++){
				 $area=($title[$j][0].$Nstart);
				 $info=$demand[$i][$title[$j][2]];
				 if($title[$j][1]=="税点")$info="0%";
				 if($title[$j][1]=="内容")$info= $demand[$i]["content"]. $demand[$i]["detail"];
				 if($title[$j][0]=="E")$info= $baseData["hourprice"];
				 if($title[$j][1]=="總額")$info=   $t  ;
				 if ($info!=""){
				     setCellStyle($objPHPExcel, $info,$area,"10",'center',"",$area);
					 $objPHPExcel->getActiveSheet()->getStyle($area)->getAlignment()->setWrapText(true);
				 }
		        
			}
	      $Nstart+=1;
	  }
	 
	  //總計
	  $area="C".$Nstart; 
      setCellStyle($objPHPExcel, $totalNum,$area,"10",'center',"",$area);
	  $area="D".$Nstart;
	  setCellStyle($objPHPExcel, $totalHours,$area,"10",'center',"",$area);
	  $area="F".$Nstart;
	  setCellStyle($objPHPExcel, "总计",$area,"12",'center',"",$area);
	  $area="G".$Nstart;
	  setCellStyle($objPHPExcel, $total,$area,"10",'center',"",$area);
	  $objPHPExcel->getActiveSheet()->getStyle($area)->getNumberFormat()->setFormatCode('$#,##0;-$#,##0'); 
	   //外框
	  $LineArea="A1:G".(count($demand)+2);
	  DrawLineOut($objPHPExcel,$LineArea,"PHPExcel_Style_Border::BORDER_THIN");
	  //開始
	  $STENDay=getStartEndTime();
	  
      $Nstart+=2;
	  $area="A".$Nstart;
	  $objPHPExcel->getActiveSheet()->getRowDimension($Nstart)->setRowHeight(20);
      setCellStyle($objPHPExcel,"开始时间",$area,"11",'center',"",$area);
	  Fill_Solid($objPHPExcel,$area,"#ffffffff","#000000");
	  $area="B".$Nstart;
	  setCellStyle($objPHPExcel,$STENDay[0],$area,"11",'center',"",$area);
	  //完成
	  $Nstart+=1;
	  $area="A".$Nstart;
	  $objPHPExcel->getActiveSheet()->getRowDimension($Nstart)->setRowHeight(20);
      setCellStyle($objPHPExcel,"完成时间",$area,"11",'center',"",$area);
	  Fill_Solid($objPHPExcel,$area,"#ffffffff","#000000");
	  $area="B".$Nstart;
	  setCellStyle($objPHPExcel,$STENDay[1],$area,"11",'center',"",$area);
 
	  saveExcel($objPHPExcel,"報價.xls"); 
	  
}
function getStartEndTime(){
         global $outsDetial;
		 $year=array();
		 $startM=array();
		 $EndM=array();
		 $startD=array();
		 $EndD=array();
		 for($i=0;$i<count($outsDetial);$i++){
		     $tmp= explode("/",$outsDetial[$i][9]);
			 if(count($tmp)>2){
			 if(!in_array($tmp[0],$year))array_push($year,$tmp[0]);
			 if(!in_array($tmp[1],$startM))array_push($startM,$tmp[1]);
			 if(!in_array($tmp[2], $startD))array_push( $startD,$tmp[2]);
			 }
			 $tmp= explode("/",$outsDetial[$i][10]);
			 if(count($tmp)>2){
			 if(!in_array($tmp[0],$year))array_push($year,$tmp[0]);
			 if(!in_array($tmp[1],$EndM))array_push($EndM,$tmp[1]);
			 if(!in_array($tmp[2],$EndD))array_push( $EndD,$tmp[2]);
			 }
		 }
		 sort($year);
		 sort($startM);
	     sort($EndM);
		 $sd= getStartDay($outsDetial,$year[0],$startM[0],"");
		 $ed= getStartDay($outsDetial,$year[(count($year)-1)],$EndM[(count($EndM)-1)],"false");
	     $startYM=$year[0]."年".$startM[0]."月".$sd."日";
		 $EndYM=$year[(count($year)-1)]."年".$EndM[(count($EndM)-1)]."月".$ed."日";
		 return array($startYM,$EndYM);
}
function getStartDay($data,$y,$m,$forward){
	     $d=array();
		 $s=9;
		 if($forward=="false")$s=10;
		
	     for($i=0;$i<count($data);$i++){
			// echo $data[$i][$s];
		      $tmp= explode("/",$data[$i][$s]);
			  if($y==$tmp[0] and $m==$tmp[1]){
				   array_push($d,$tmp[2]);
			  // if(!in_array($tmp[2],$d))array_push($d,$tmp[2]);
			  }
		 }
		 sort($d);
		 $fd=$d[0];
		 if($forward=="false") $fd=$d[(count($d)-1)];
		 return $fd;
}
 
?>

 