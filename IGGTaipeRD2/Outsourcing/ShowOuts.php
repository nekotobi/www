<?php
    //require_once('PubApi.php');
	require_once 'xlsApiv2.php';
	defineData();
	ExportXls();
	//ListAll();
?>
<?php
  
  function defineData(){
	        global $ListArray;
		    $ListArray=array(
		    array("所属项目","project",3,10,"A"),
			array("部门","department",4,10,"B"),
			array("外包商(公司/个人)","outsourcing",5,40,"C"),
			array("国家","country",6,10,"D"),
			array("联系人以及联系方式","contact",7,30,"E"),
			array("制作内容","content",8,60,"F"),
			array("外包金额（台幣）","nt",9,20,"G"),
			array("外包金额（美元）","usdollar",10,20,"H"),
			array("外包金额（人民幣）","CNY",11,20,"I"),
			array("当前状态","state",12,20,"J"),
			array("跟进人员","principal",12,10,"K")
			);
			 
			global $OutCosts;
			$tableName="fpoutsourcingcost";
			$MainPlanDataT=getMysqlDataArray($tableName); 
			$OutCostst=filterArray($MainPlanDataT,0,"cost");
			$OutCosts=$OutCostst;// sortArrays($OutCostst ,1,"true") ;
			global 	$pregress,$pregressTitle;
			$pregressT=getMysqlDataArray("fpoutpregress");
		    $pregress=filterArray($pregressT,0,"pregress");
		    $pregressTitleT=filterArray($pregressT,0,"title");
		    $pregressTitle= $pregressTitleT[0];
			$pregressTitle[27]="已付款";
			 
  }
  function ListAll(){
            global $ListArray;
		    global $OutCosts;
			$y=20;
			DrawTitle( $ListArray ,$y);
            for($i=0;$i<count($OutCosts);$i++){
			    $y+=22;
			 	Drawdet($OutCosts[$i],$y);
			}
  }
  function getPregress($sn){
          global 	$pregress;
	 	  global   $pregressTitle;
		  $nowPres=filterArray($pregress,1,$sn);
		  $state="";
		  for($i=13;$i<=27;$i++){
		      if($nowPres[0][$i]!="")$state=$pregressTitle[$i];
		  }
		  return $state;
  }
  function DrawTitle($data,$y){
	  	    $fontSize=12;
			$fontColor="#ffffff";
		    $BgColor= "#000000";
	      	$x=20;
			$w=100;
			$h=20;
            for($i=0;$i<count($data);$i++){
				$msg=$data[$i][0];
			    DrawRect($msg,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor);
                $x+=102;				
			}
  }
  function Drawdet($data,$y){
	        global $ListArray;
	  	    $fontSize=12;
		    $fontColor="#000000";
		    $BgColor= "#dddddd";
	        $x=20;
			$w=100;
			$h=20;
			for($i=0;$i<count($ListArray);$i++){
				$s=$ListArray[$i][2];
				$msg=$data[$s];
				if($i==9)$msg=getPregress($data[1]);
			    DrawRect($msg,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor);
                $x+=102;				
			}
  }
?>
<?php //輸出xls
  function ExportXls(){
        require_once dirname(dirname(dirname(__FILE__))) .'/phpexcel/Classes/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)  ;
        global $ListArray;
		for($i=0;$i<count($ListArray);$i++){
		    $area=$ListArray[$i][4]."1";
			$objPHPExcel->getActiveSheet()->getColumnDimension($ListArray[$i][4])->setWidth($ListArray[$i][3]); 
		    setCellStyle($objPHPExcel,$ListArray[$i][0],$area,"14",'center',"",$area);
		}
		global $OutCosts;
         $ntTotal=0;
	     $USTotal=0;
		 $CNYTotal=0;
		
        for($i=0;$i<count($OutCosts);$i++){
		      Drawxlsdet($objPHPExcel,$OutCosts[$i],$i+2);
		           $ntTotal+=$OutCosts[$i][9];
			       $USTotal+=$OutCosts[$i][10];
			       $CNYTotal+=$OutCosts[$i][11];
			}
	    //總額
		/*
		$area="G".(count($OutCosts)+2);
	    setCellStyle($objPHPExcel, $ntTotal,$area,"12",'center',"",$area);
		 
		$area="H".(count($OutCosts)+2);
				 setCellStyle($objPHPExcel, $USTotal,$area,"12",'center',"",$area);
		$area="I".(count($OutCosts)+2);
				 setCellStyle($objPHPExcel,  $CNYTotal,$area,"12",'center',"",$area);
				 */
		saveExcel($objPHPExcel,"FP项目外包量汇总表.xls");
  }
 function Drawxlsdet( $objPHPExcel,$data,$y){
	    global $ListArray;
	 	  for($i=0;$i<count($ListArray);$i++){
			  $s=$ListArray[$i][2];
			  $msg=$data[$s];
			  $area=$ListArray[$i][4].$y;
			  if($i==9)$msg=getPregress($data[1]);
			  if($i==10 && $msg=="")$msg="黃謙信";
			  if($data[13]!="" && $i==2) $msg=$msg."(第".trim($data[13])."包)";//
			  setCellStyle($objPHPExcel,$msg,$area,"12",'center',"",$area);
			  $objPHPExcel->getActiveSheet()->getStyle($area)->getNumberFormat()->setFormatCode('#,##0.00;-#,##0.00');
		  }
 }

?>