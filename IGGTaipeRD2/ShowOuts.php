<?php
    include('PubApi.php');
	defineData();
	ListAll();
?>
<?php
  
  function defineData(){
	        global $ListArray;
		    $ListArray=array(
		    array("所属项目","project",3),
			array("部门","department",4),
			array("外包商(公司/个人)","outsourcing",5),
			array("国家","country",6),
			array("联系人以及联系方式","contact",7),
			array("制作内容","content",8),
			array("外包金额（台幣）","nt",9),
			array("外包金额（美元）","usdollar",10),
			array("外包金额（人民幣）","CNY",11),
			array("当前状态","state",12),
			array("跟进人员","principal",12)
			);
			global $OutCosts;
			$tableName="fpoutsourcingcost";
			$MainPlanDataT=getMysqlDataArray($tableName); 
			$OutCostst=filterArray($MainPlanDataT,0,"cost");
			$OutCosts= sortArrays($OutCostst ,1,"true") ;
			global 	$pregress;
			$pregressT=getMysqlDataArray("fpoutpregress");
		    $pregress=filterArray($pregressT,0,"pregress");
		    $pregressTitleT=filterArray($pregressT,0,"title");
		    $pregressTitle= $pregressTitleT[0];
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
		  $nowPres=filterArray($pregress,1,$sn);
		  for($i=13;$i<=27;$i++){
		  
		  }
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
			    DrawRect($msg,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor);
                $x+=102;				
			}
  }
?>