<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>輸出Mysql資料</title>
</head>
 
<body bgcolor="#b5c4b1">
<?php   
    $id=$_COOKIE['IGG_id'];
	require_once dirname(dirname(__FILE__)).'\PubApi.php';
  	require_once dirname(dirname(__FILE__)).'\mysqlApi.php';
	defineData();
    ListForm();
    filterSubmit();

  
?>

<?php //

    function defineData(){
            global $data_library;  
			global $tables;
		    $data_library="iggtaiperd2"; 
			$tables= getlibraryTables($data_library);
			global $BaseURL;
			$BaseURL="Mysql2xls.php";
	}

	function ListForm(){
		    global $BaseURL;
		 	global $tables;
			global $selectable;
	        echo   "<form id='ChangeOut'  name='Show' action='".$BaseURL."' method='post'>";
			$y=20;
		    $input=	MakeSelectionV2($tables,$selectable,"selectable",10);
		    DrawInputRect("表單列表",10,"#222222",$x,$y,$w,$h,$BgColor,$WorldAlign,$input);
	
	 
			$x=200;
            $submitP="<input type=submit name=submit value=搜尋 style= font-size:10px; >";
	        DrawInputRect("",8 ,"#ffffff",$x,$y,$w,$h, $colorCodes[4][2],"top",$submitP);
				
			$x+= 60;
            $submitP="<input type=submit name=submit value=輸出 style= font-size:10px; >";
	        DrawInputRect("",8 ,"#ffffff",$x,$y,$w,$h, $colorCodes[4][2],"top",$submitP);
		    echo "</form>";
	}
 
?>
<?php  //處理表格類別
    function filterSubmit(){
			  global $submit;
			  if($submit=="")return;
			  if($submit=="搜尋") ListTableDatas();
			  if($submit=="輸出") {
				 global $data_library, $selectable;
			     $go="Mysql2xlsOut.php?data_library=".$data_library."&selectable=".$selectable."";
				  echo " <script language='JavaScript'>window.location.replace('".$go."')</script>";
			  }
	}

?>

<?php //列印
    function ListTableDatas(){
	         global $data_library, $selectable;
			 $tableDatas=getMysqlDataArray($selectable); 
			// echo $selectable."]".count( $tableDatas);
			 $msg="選擇表單[".$selectable."]";
			 $x=20;
			 $y=60;
			 $w=200;
			 $h=20;
	         DrawRect($selectable,10,"#ffffff",$x,$y,$w,$h,"#000000");
			 $x+=$w+2;
			 DrawRect($data_library,10,"#ffffff",$x,$y,$w,$h,"#000000");
		     $field=  returnTables($data_library ,$selectable);
			 $y+=32;
			 $w=100;
			 $x=20;
			 for( $i=0;$i<count($field);$i++){
			       DrawRect($field[$i],10,"#ffffff",$x,$y,$w,$h,"#000000");
				   $x+= $w+2;
			 }
			 $x=20;
			 $y+=22;
			 $sizes= getColumnsize();
			  for( $i=0;$i<count($sizes);$i++){
			       DrawRect($sizes[$i],10,"#ffffff",$x,$y,$w,$h,"#000000");
				   $x+= $w+2;
			 }
			 
			 $x=20;
			 $y+=22;
			 for( $i=0;$i<count($tableDatas);$i++){
				  $x=20;
			      $y+=22;
				  DrawLine($tableDatas[$i],$x,$y,$w,$h);
			 }

			 
	}
    function DrawLine($tableData,$x,$y,$w,$h){
	        for( $i=0;$i<count($tableData);$i++){
				DrawRect($tableData[$i],10,"#000000",$x,$y,$w,$h,"#eeeeee");
				$x+= $w+2;
			}
	}
    function getColumnsize(){
		   global $data_library, $selectable;
		   $db = mysql_connect("localhost","root","1406");
		   $db_selected = mysql_select_db( $data_library,$db);
		   $fields= returnTables($data_library ,$selectable);
		   $ret=array();
		   for($i=0;$i<count($fields);$i++){
			   $sql = "SELECT CHARACTER_MAXIMUM_LENGTH,column_name FROM information_schema.columns  
                     WHERE table_name = '$selectable' AND column_name LIKE '$fields[$i]'"; 
               $query = mysql_query($sql,$db) or die(mysql_error());
			   while($result = mysql_fetch_array($query)){
				     array_push($ret,$result[CHARACTER_MAXIMUM_LENGTH] );
				   //  echo  $result[CHARACTER_MAXIMUM_LENGTH] ;
			   }
		   }
		   return $ret;
	}

?>


 