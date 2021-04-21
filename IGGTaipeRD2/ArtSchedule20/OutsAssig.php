<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>發包需求暫存區</title>
</head>
<body bgcolor="#b5c4b1">
<script type="text/javascript">
function Drop2Area(event) {
		event.preventDefault();
		var DragID  = event.dataTransfer.getData("text");
		var targetID =  event.currentTarget.id;
	    var tx= document.getElementById( targetID).style.left;
	    var x=tx.split("px");
	    document.Show.DragID.value=  DragID;
	    document.Show.target.value=  targetID;
	     Show.submit();
	}

</script>
<?php  //主控台
      require_once('/Apis/PubApi20.php');
	  require_once('/Apis/mysqlApi20.php');
	  require_once('/Apis/ProjectApi.php');
	  require_once('/Apis/PubJavaApi.php');
	  defineData();
	  ListOuts();
      CheckSubmit();
	  setJavaForm();//java表單一定要最後
?>

<?php //定義資料
    function defineData(){
	        global $URL ;
			global $data_library ,$tableName;
			global $ResdataBase;
			global $WebSendVal;
			global $selectProject;
			$selectProject="zombie";
			$WebSendVal=array();
			$tableName="outstmp_zombie";
			$ResdataBase="outstmp_zombie";
			$data_library="iggtaiperd2";
			$URL="OutsAssig.php";
			global $AllOuts;
			global $filterOuts;
			$AllOuts= getMysqlDataArray( "outsourcing");
		    $filterOuts =  filterArray( $AllOuts,35,"true");
			global $AllTmp;
			global $NoOrders;
			$AllTmp=getMysqlDataArray( "outstmp_zombie");
		    $NoOrders=  filterArray( $AllTmp,3,"");
			global $AllOutsName;
			global $OutsNames;
			global $OutsTmpOrder;
			$AllOutsName =  filterArray( $AllTmp,9,"");
			$OutsNames= getSortArrayNames($AllOutsName,3);
			$OutsTmpOrder= SortNamesToArray($AllOutsName,3,$OutsNames);
	}
	
?>

<?php //List
    function ListOuts(){
		     DrawRect("外包需求未申請暫存區",14,"#ffffff",array(20,20,1000,20),"222222");
			 global $ListY;
			 $ListY=100;
		     ListOutsInput();
		     DrawNewOrder();
			 ListOutsNames();
	}
	function DrawNewOrder(){
		     global $NoOrders;
			 global $ListY;
			 for($i=0;$i<count($NoOrders);$i++){
				  $id="ECode=".$NoOrders[$i][1];
				  $n=returnTitleStr($NoOrders[$i]);
				  JAPI_DrawJavaDragbox($n,20,$ListY,300,18,12,"#aa7777" ,"#ffffff",$id);
				  $ListY+=20;
			 }
	}
	function ListOutsInput(){
		     global $URL ;
			 $ValArray=array();
			 $BgColor="#aaaaaa";
			 $fontColor="#ffffff";
	         array_push(  $ValArray,array("EditType","Add"));
			 $upFormVal=array("AddOuts","AddOuts",$URL);
			 DrawRect("",12,"#ff2222",array(20,55,310,40),"#662222");
			 //hide
			 $UpHidenVal=array();
			 array_push( $UpHidenVal,array("EData","data"));
		     array_push( $UpHidenVal,array("ECode",returnDataCode( ) ));
 
			 //Up
			 $inputVal=array();
			 $tarr=array("text", "content", "內容",8,20,55,200,20,$BgColor,$fontColor,"",30);
			 $cost=array("text", "costs", "金額",8,210,55,200,20,$BgColor,$fontColor,"",5);
		 	 $pri=array("text", "principal", "需求人",8,20,75,200,20,$BgColor,$fontColor,"1",4);
			 $ps=array("text", "ps", "附註"          ,8,120,75,205,20,$BgColor,$fontColor,"",10);
			 $file=array("file", "filename", "檔案",8,240,75,200,20,$BgColor,$fontColor,"1",10);
			 $sub=array("submit", "submit", "",8,280,55,50,20,"#aa3333","#111111","新增",10);
			 array_push($inputVal,$tarr);
			 array_push($inputVal,$cost);
			 array_push($inputVal,$pri);
			 array_push($inputVal,$ps);
			 array_push($inputVal, $file);
			 array_push($inputVal,$sub);
			 //$inputVal=0/type 1/name 2/showname 3/fontsize 4/5/6/7rect  8/bgcolor 9/fontColor 10/val 11/size
			 upSubmitform($upFormVal,$UpHidenVal, $inputVal);
		     ProAPI_DrawOutsAreas(array(350,60,30,24),true);

	}
    function ListOutsNames(){
	         global $OutsNames;
			 global  $ListY;

			 for($i=0;$i<count($OutsNames);$i++){
				  $ListY+=20;
                  DrawRect($OutsNames[$i],12,"#ffffff",array(20, $ListY,500,18),"222222");		
                  ListOutsOrdwes($OutsNames[$i]);				  
			}
	}
	function ListOutsOrdwes($Outsname){
			 global $OutsTmpOrder;
			 global $ListY;
			 global $selectProject;
		     for($i=0;$i<count($OutsTmpOrder[$Outsname]);$i++){
				 $ListY+=20;
				 $id="ECode=".$OutsTmpOrder[$Outsname][$i][1];
				 $n=returnTitleStr($OutsTmpOrder[$Outsname][$i]);
				 JAPI_DrawJavaDragbox($n,20,$ListY,400,18,11,"#227777" ,"#ffffff",$id);
				 if($OutsTmpOrder[$Outsname][$i][10]!=""){
				     $LinkPath="/".$selectProject."Res/OutsTmp/".$OutsTmpOrder[$Outsname][$i][1].".".$OutsTmpOrder[$Outsname][$i][10];
                     $pic="Pics\\folder.png";		    
					 DrawLinkPic($pic,array(400,$ListY,12,12),$LinkPath );
				 }
			 }
	}
	function returnTitleStr($data){
		$tarr=explode("-",$data[1]);
		$time=$tarr[1]."-".$tarr[2];
		$n=$time."[內容]".$data[4]."[cost]".$data[6]."[發起人]".$data[7];
		return $n;
	}
?>
<?php //submit
     function CheckSubmit(){
	          global $data_library ,$tableName;
			  global $URL;
			  global $selectProject;
			  global $WebSendVal;
	          if($_POST["submit"]!=""){
			     $upPath="..\\..\\".$selectProject."Res\\".OutsTmp ;
			     $filename= $_POST["ECode"];
				 $exe= UpPic("filename",  $upPath, $filename);
				 $extraArr=array();
				 echo $exe;
				 if($exe!="") $extraArr["filename"]=$exe;
				 MAPI_AutoCreateNewMsQLData($data_library,$tableName, $extraArr );
			     JAPI_ReLoad(  $WebSendVal,$URL);
			  }
			  if($_POST["DragID"]!=""){
				  CheckDrag();
			  }
	 }
     function CheckDrag(){
		      global $data_library,$ResdataBase;
	          $Drag=explode("=",$_POST["DragID"]);
			  $targ=explode("=",$_POST["target"]);
		      $WHEREtable=array( "EData", "ECode");
		      $WHEREData=array( "data",$Drag[1] );
			  $Base=array($targ[1]);
			  $up=array($targ[2]);
			  if($targ[1]=="process"){
				array_push(  $Base, "OutsNum");
			    array_push( $up, $_POST["OutsNum"]);
			  }
              $stmt=MAPI_MakeUpdateStmt($ResdataBase,$Base,$up,$WHEREtable,$WHEREData);
			  // echo $stmt;
			  SendCommand($stmt,$data_library);		
			  JAPI_ReLoad($WebSendVal,$URL);
	 }
	 function setJavaForm(){
		       global $URL;
			   global $ResdataBase,$typeDatabase;
			   global $inputsTextNames ;
			   global $WebSendVal;
			   $x=1000;
			   $y=20;
			   $inputsTextNames=array( "DragID","target","OutsNum");
			   $RectArray=array();
			   $RectArray["DragID"]=array(  $x,$y ,10,10);
			   $RectArray["target"]=array(  $x,$y+20,10,10 );
			   $RectArray["OutsNum"]=array( 360,100 ,5,8);
	           JAPI_CreatJavaForm( $URL, $ResdataBase,$inputsTextNames,$WebSendVal,$x,  $y ,$RectArray);
			   $id="tableName=process=".date("y-m-j");
			   JAPI_DrawJavaDragArea( "已處理",440,98,40,20,"#225555","#ffffff",$id,11);
	  }
?>