 
 <!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>帳號登入</title>
</head>
<body bgcolor="#999999">
</body>
<?php
	    include('PubApi.php');
		// $id=$_COOKIE['IGG_id'];
	    $data_library="iggtaiperd2";
	    $table="members";
		if($Logout=="true"){
		    setcookie("IGG_id","",time()+36000,"/");
		  
		    echo " <script language='JavaScript'>window.location.replace('index.php')</script>";
		}
		if($submit==""){
	    	DrawLogin();
		}
	    if($submit=="送出"){
		   CheckLoginData();
			
		}
		if($submit=="註冊"){
			AddMemberData();
	    
		}	
		function AddMemberData(){
			global $ID ,$pass;
			echo "Login>".$ID;
	        include('mysqlApi.php');
			$member=getMysqlDataArray("members");
			echo ">".count($member);
			$b=checkRepet($member,$ID,0);
			if(checkRepet($member,$ID,0)==1) {
				echo $ID."用戶重複"; 
				return;
			}			
			NewMemberData();
			
		}
	    function NewMemberData(){
			 global $data_library, $table;
	         global  $ID  , $Name  , $Pass  , $Type  , $Job  , $Color  , $Rank  ;
			  $Rank=5;
			 if($Type=="Art") $Rank=3;
			 if($Pass=="")$Pass="bzbee";
			 echo $Type;
			 $WHEREtable=array( "`ID`" , "`Name`" , "`Pass`" , "`Type`" , "`Job`" , "`Color`" , "`Rank`" );
		     $WHEREData=array( $ID  , $Name  , $Pass  , $Type  , $Job  , $Color  , $Rank );
	         $stmt= MakeNewStmt( $data_library,$table,$WHEREtable,$WHEREData);
			 echo $stmt;
	         SendCommand($stmt,$data_library);
			 
		     echo " <script language='JavaScript'>window.location.replace('Calendar.php')</script>";
	        }
		function checkRepet($arrayData,$name,$dataNum){
		      $b=0;
			  for($i=0;$i<count($arrayData);$i++){
				  if($arrayData[$i][$dataNum]==$name)$b=1;
		       }
			   return $b;
		}
		function CheckLoginData(){
	        	 global $ID ,$pass;
		         $member=getMysqlDataArray("members");
				 $isID="";
		        	for($i=0;$i<count($member);$i++){
		     	     if($member[$i][0]==$ID ){
						  $isID="true";
						   if($member[$i][2]==$pass){
							    echo "Login".$ID;
								setcookie("IGG_id",$ID,time()+3655000,"/");
								setcookie("IGG_Rank",$member[$i][6],time()+3655000,"/");
							    echo " <script language='JavaScript'>window.location.replace('index.php')</script>";
						   }
						    if($member[$i][2]!=$pass){
							    echo "密碼輸入錯誤".$ID; 
						   } 
					     }
				 }
			     if($isID==""){
				     echo "無此ID:".$ID; 
				 }
			}

	    function DrawLogin(){
		    $colorCodes= GetColorCode();
			$types=getMysqlDataArray("jobtype");
		    DrawRect("RD2美術網頁帳號登入","22","#ffffff",100,100,320,220, $colorCodes[2][0]);
		    echo "<form id=form name=form method=post action=Login.php?Login >"; 
			$inputID="<input type=text name=ID size=20    >";
			DrawInputRect("User:　","12","#ffffff",150,140,200,20, $colorCodes[4][2],"top", $inputID);
			$inputpass="<input type=password  name=pass size=20    >";
			DrawInputRect("pass:　","12","#ffffff",150,170,200,20, $colorCodes[4][2],"top",$inputpass);
			DrawRect("註冊新帳號","12", $colorCodes[2][1],110,200,300,110, $colorCodes[0][1]);
		
			$inputName="<input type=text name=Name size=20    >";
			DrawInputRect("中文名:　","12","#ffffff",140,210,200,20, $colorCodes[4][2],"top",$inputName);
			$inputJob="<input type=text name=Job size=18    >";
			DrawInputRect("工作屬性:　","12","#ffffff",140,240,200,20, $colorCodes[4][2],"top",$inputJob);
			
			$TypeSelect=MakeSelection($types,0,"Art","Type");
		    DrawInputRect("類別:　","12","#ffffff",140,270,200,20, $colorCodes[4][2],"top",$TypeSelect);
			
			
		    $submit="<input type=submit name=submit value=送出 >";
		    DrawInputRect("","12","#ffffff",335,170,100,20, $colorCodes[4][2],"top",$submit);
 	        $Registered="<input type=submit name=submit value=註冊 >";
		    DrawInputRect("","12","#ffffff",335,270,200,20, $colorCodes[4][2],"top", $Registered);
            echo "</form>";
		}			
			
        function DrawAlignRect($msg,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor,$WorldAlign){
	          echo "<div  style=' color:".$fontColor."; " ;
			  echo "text-align:".$WorldAlign." ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo "position:absolute;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor."; '>";
			  echo $msg;
	          echo "</div>";
	   }
	   function DrawWorldRect(){
	         echo "<div  style=' color:".$fontColor."; " ;
	   }


?>

