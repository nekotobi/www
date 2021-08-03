 <!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>帳號登入2.0</title>
</head>
<body bgcolor="#999999">
</body>
<?php
     require_once('/Apis/PubApi20.php');
	  require_once('/Apis/mysqlApi20.php');
	 defineBaseData();
     CheckState();
?>
<?php
    function defineBaseData(){
	         global $URL;
			 $URL="Login20.php";
			 global $data_library;
		     $data_library="iggtaiperd2";
			 global $member;
             $member=getMysqlDataArray("members");
			 global   $colorCodes,	$types;
			 $colorCodes= GetColorCode();
			 $types=getMysqlDataArray("jobtype");
	}
    function CheckState(){
	     	//echo ">".$_COOKIE['IGG_id'];
		     if($_POST['Logout']=="true"){
		        setcookie("IGG_id","",time()+36000,"/");
		        setcookie("IGG_Rank","",time()+36000,"/");
		        RefreshURL($URL);
			 }
			 if($_COOKIE['IGG_id']!=""){
			    DrawLogOut();
				echo "xx";
				return;
			 }
			 if($_POST["submit"]=="") DrawLogin();
	         if($_POST["submit"]=="送出")   CheckLoginData();
 
	}
	function DrawLogOut(){
	          global   $colorCodes ;
			  DrawRect("RD2美術網頁帳號登出","22","#ffffff",array(100,100,320,220), $colorCodes[2][0]);
			  DrawRect("目前登入:".$_COOKIE['IGG_id'],"16","#ffffff",array(100,200,320,220), $colorCodes[2][0]);
			  $submit="<input type=submit name=submit value=登出 >";
			   DrawInputRect("","12","#ffffff",array(335,200,100,20), $colorCodes[4][2],"top",$submit);
			  echo "<form id=form name=form method=post action=Login20.php?Login >"; 
			  
	}
	function DrawLogin(){
		     global   $colorCodes,	$types;
		     DrawRect("RD2美術網頁帳號登入","22","#ffffff",array(100,100,320,220), $colorCodes[2][0]);
		     echo "<form id=form name=form method=post action=Login20.php?Login >"; 
		  	 $inputID="<input type=text name=ID size=20    >";
			 DrawInputRect("User:　","12","#ffffff",array(150,140,200,20), $colorCodes[4][2],"top", $inputID);
			 $inputpass="<input type=password  name=pass size=20    >";
			 DrawInputRect("pass:　","12","#ffffff",array(150,170,200,20), $colorCodes[4][2],"top",$inputpass);	
		     $submit="<input type=submit name=submit value=送出 >";
		     DrawInputRect("","12","#ffffff",array(335,170,100,20), $colorCodes[4][2],"top",$submit);
             echo "</form>";
		}			
?>
<?php //AddMemberData
 
    function  CheckLoginData(){
			  global $member;
			  $ID=$_POST['ID'];
			  $pass=$_POST['pass']; 
			  $str="無此帳號";
			  for($i=0;$i<count($member);$i++){ 
			      if($member[$i][0]==$ID ){
					   $str="密碼輸入錯誤";
				      if($member[$i][2]==$pass ){
					     setcookie("IGG_id",$ID,time()+3655000,"/");
					     setcookie("IGG_Rank",$member[$i][6],time()+3655000,"/");
						 RefreshURL('index.php') ;
					  }
				  }
			  }
		     echo  $str; 
			}

?>

<?php //function
		function checkRepet($arrayData,$name,$dataNum){
		       $b=false;
			   for($i=0;$i<count($arrayData);$i++){
				  if($arrayData[$i][$dataNum]==$name)$b=true;
		       }
			   return $b;
		}
?>