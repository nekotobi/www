<script type="text/javascript">
    var upId="xx";
	var BaseColor="";
	var BGColor="";
	var startX=0;
	var StartWid=0;
	function AllowDrop(event) {
		    event.preventDefault();
			var OverID= event.currentTarget.id;
	        if(upId=="xx")upId =OverID;
			if(BGColor=="")BGColor=  document.getElementById(OverID).style.backgroundColor ;
		    if(upId!=OverID){
	            document.getElementById(upId).style.backgroundColor=BGColor;
			    upId=OverID;
				BGColor=  document.getElementById(OverID).style.backgroundColor ;
	          } 
		    document.getElementById( OverID).style.backgroundColor="#ffaaaa";
		    showDragDay();
	}
    function showDragDay(){
		     var targetID =  event.currentTarget.id;
			 var DragID  = event.dataTransfer.getData("text");
			 var tmp2= targetID.split("=");
			 var OverID= event.currentTarget.id;
			 var tx= document.getElementById( targetID).style.left;
		     var uptx= document.getElementById(DragID ).style.top;
             var y=uptx.split("px");
			 var ya= (parseInt(y[0])-12)+"px";
		     document.getElementById('DateInfo').style.left=tx;
			 document.getElementById('DateInfo').style.top=ya;
			   var tmp= targetID.split("=");
			 document.getElementById("DateInfo").innerHTML=tmp[1];
			 document.Show.startDay.value=tx;
			// document.Show.startDay.style.left=x;
	}
	function Drop2Area(event) {
		event.preventDefault();
		var DragID  = event.dataTransfer.getData("text");
		var targetID =  event.currentTarget.id;
	    var tx= document.getElementById( targetID).style.left;
	    var x=tx.split("px");
	    var tmp= DragID.split("=");
	    var tmp2= targetID.split("="); 
		   var SID;
		if(tmp.length>0){
		   document.Show.type.value= tmp[5];
		   document.Show.DragID.value=tmp[1];
		   var SID= new String( "S="+tmp[1]+"="+tmp[2]+"="+tmp[3]+"="+tmp[4]+"="+tmp[5]);
		  }
	    if( tmp[0]=="S"){
	
			 document.Show.target.value=targetID;
		     document.Show.startDay.value=tmp2[1];
			 var x3=(parseInt(x[0])+  parseInt(tmp[2])*parseInt(tmp[3]))+"px";
			 document.getElementById(DragID).style.left=tx;

		 }
	    if( tmp[0]=="E"){
			  document.getElementById( DragID).style.left=tx;
			//  var SID= new String( "S="+tmp[1]+"="+tmp[2]+"="+tmp[3]+"="+tmp[4]+"="+tmp[5]);
			  var sidx= document.getElementById(SID).style.left ;
		      var sidwx=sidx.split("px");
		      var x3=(parseInt(x[0])-parseInt(sidwx[0]));
		      document.getElementById(SID).style.width = x3+"px";
	          document.Show.workingDays.value=x3/tmp[3];
			  document.Show.startDay.value="";
		 }
		if( tmp[0]=="N"){
			  document.getElementById( DragID).style.left=tx;
			 // var SID= new String( "S="+tmp[1]+"="+tmp[2]+"="+tmp[3]+"="+tmp[4]);
			  document.Show.target.value=targetID;
              document.Show.DragID.value=DragID;
			  document.Show.startDay.value=tmp2[1];
			  document.Show.workingDays.value=5;
			  document.Show.plan.value=tmp[2];
			  document.Show.state.value="預排程";
			  document.Show.principal.value="黃謙信";
			  document.Show.outsourcing.value="";
			  document.Show.type.value= tmp[1];
		}
	    switch(tmp2[0]){
			    case  "state":
					 document.Show.selecttype.value="";
				     document.Show.startDay.value="";
			         document.Show.state.value=tmp2[1];
					 document.Show.code.value=tmp2[1];
			    break;
				case  "tableName":
					 document.Show.selecttype.value="";
				     document.Show.startDay.value="";
			         document.Show.tableName.value=tmp2[1];
					 document.Show.tableVal.value=tmp2[2];
					// document.Show.code.value=tmp2[1];
			    break;
			 }
 
    Show.submit();
	  
	}
 
	function Drag(event) {
	    event.dataTransfer.setData("text", event.currentTarget.id);
	    var DragID  = event.dataTransfer.getData("text");
	    if(BaseColor=="") BaseColor= document.getElementById(DragID).style.backgroundColor;
		if(startX==0)startX= document.getElementById(DragID).style.left;
	    y=document.getElementById(event.currentTarget.id).style.top;
	   
	}
   
	function Drop(event) {
		event.preventDefault();
		var DragID  = event.dataTransfer.getData("text");
		var tagetID = 	event.currentTarget.id
		var x= document.getElementById( tagetID).style.left;
		document.getElementById( DragID).style.left=x;
	}
	 
     document.captureEvents(Event.MOUSEMOVE)
     document.onmousemove = getMouseXY;
     function getMouseXY(e) {
         document.Show.MouseX.value = e.pageX
         document.Show.MouseY.value = e.pageY
         return true
     }
</script>
<script type="text/javascript"> //傳遞變數
    function post_to_url(path, params, method) {
    method = method || "post";  
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);
    for(var key in params) {
        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", key);
        hiddenField.setAttribute("value", params[key]);
        form.appendChild(hiddenField);
    }
    document.body.appendChild(form);  
    form.submit();
}
</script>
<?php  //post
        function JavaPost($PostArray,$URL){
			$params="{";
			for($i=0;$i<count($PostArray);$i++){
			    $params=$params."'".$PostArray[$i][0]."':'".$PostArray[$i][1]."'";
				if(count($PostArray)>1) $params=$params.",";
			}
			$params=$params."}";
		    $javaCom=  "post_to_url('".$URL."', ".$params.");";
 
            echo " <script language='JavaScript'>".$javaCom."</script>"; 
        }
		function ReLoadArray(){
			    return array(array("Restype",$_POST["Restype2"]));		
		}
	    function CheckDrag(){
			   if($_POST["DragID"]=="")return;
			   if($_POST["plan"]!=""){
				   newTask();
				   return;
			   }
			   if($_POST["tableName"]!=""){
				   changetableVal();
				   return;
			   }
	           $Ecode=$_POST["DragID"];
			   $target=$_POST["target"]	;
	           $CheckArr= array("startDay","workingDays" ,"principal","outsourcing","type","state","selecttype");
			   $Base=array( );
			   $up=array();
			   for($i=0;$i<count($CheckArr);$i++){
		           if($_POST[$CheckArr[$i]]!=""){
					   array_push( $Base,$CheckArr[$i]);
					   array_push( $up,$_POST[$CheckArr[$i]] );
				   }
			   }
               ChangePlan($Ecode,$Base,$up,$_POST["type"]);
		      // ReLoad();
	    }
		function changetableVal(){
		        $Base=array($_POST["tableName"]);
			    $up=array($_POST["tableVal"]);
				ChangePlan($_POST["DragID"],$Base,$up,$_POST["type"]);
			    
		}
		function newTask(){
		     	 $Restype=$_POST["Restype2"];
			     global $SC_tableName_now,$SC_tableName_old,$SC_tableName_merge;
				 DefineVTTableName();
			     $tables=returnTables($data_library,$SC_tableName_now);
				 $WHEREtable=array();
				 $WHEREData=array();
		         for($i=0;$i<count( $tables);$i++){
				      array_push($WHEREtable, $tables[$i] );
					  $data=$_POST[$tables[$i]];
					  if($tables[$i]=="log") $data=$Restype;
					  array_push($WHEREData,$data);
				 }
				 $stmt=  MakeNewStmtv2($SC_tableName_now,$WHEREtable,$WHEREData);
				// echo "</br>".$stmt;
			      saveUpdateTime("",array(""));
		          SendCommand($stmt,$data_library);	
				  $PostArray=array(array("Restype",$Restype));
			      ReLoad();
		}
	    function ChangePlan($Ecode,$Base,$up,$type){
	           global $URL;
			   global $data_library,$tableName,$SC_tableName_now ;
		       DefineVTTableName();
			   $tableName=$SC_tableName_now;
			   $WHEREtable=array( "data_type", "code","type" );
		       $WHEREData=array( "data",$Ecode ,$type);
			   $stmt= MakeUpdateStmt(  $data_library,$tableName,$Base,$up,$WHEREtable,$WHEREData);
			 //   echo "</br>";
		      // echo $stmt;
		       saveUpdateTime("",array(""));
		      SendCommand($stmt,$data_library);
              ReLoad();			   
	    }
	 
	    function ReLoad(){
	    	   global  $URL;
			   $PostArray=ReLoadArray();
			   JavaPost($PostArray,$URL); 
	    }
?>
