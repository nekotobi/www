
         var dt=new Date();
		 var currentY=dt.getFullYear();
		 var currentM=dt.getMonth()+1;
		 var currentD=parseInt(dt.getDate());
		 var BaseDate=[ currentY, currentM, currentD];
		 var First_day=returnDay( new Date(currentY,currentM,0).getDay()) ;
		 var end_day=new Date(currentY,currentM,0).getDate();
		 var clickStart="";
		 var clickStartD=0;
		 var clickEnd="";
		 var Workdays=1;
		 var click=0;
		 DefuseDate();
	     function upfrom(){
			 	  document.getElementById("workingDays").value= Workdays ;
		          document.getElementById("year").value=  currentY;
				  document.getElementById("month").value=  currentM;
				  document.getElementById("day").value=  clickStartD;
	
		 }
	     function DefuseDate(){
		         var s = document.getElementById("TargetDate");
			         s.innerHTML = currentY+"-"+currentM; 
					 	 UpCalendar();
						 upfrom();
		 }
	     function MonthLeft(add){
		     var s = document.getElementById("TargetDate");
			 currentM+=add;
			 if(currentM>12){
				 currentM=1;
			     currentY+=1;
			 }
			 if(currentM<=0){
				 currentM=12;
			     currentY-=1;
			 }
			 end_day=new Date(currentY,currentM,0).getDate();
		     First_day=returnDay( new Date(currentY,currentM,0).getDay() ) ;
		     UpCalendar();
			 var info=currentY+"-"+currentM ;
			 s.innerHTML =info;
		}
	     function returnDay(t){
			t-=2;
			if(t<0)t+=7;
			return t;
		}
	     function UpCalendar(){ 
			      var w=0;
				  var s=0;
				  var BgColor='#eeeeee';
				  for(var j=0;j<6;j++){
		              for(var i=0;i<7;i++){
					      BgColor='#eeeeee';
						  if(i==0 || i==6) BgColor='#ffaaaa';
						  if(w==(currentD-1) && currentM== BaseDate[1]) BgColor='#aaffaa';
						  if(i==First_day && s==0) s=1;
						  if(s>=1) w+=1;
						  var id=i+"-"+j;
						  var o= document.getElementById(id);
						  o.innerHTML =w;
					      if(s==0 || s==2 ) BgColor='#888888';
						   o.style.backgroundColor=BgColor;
						  if(w>=end_day){
							  w=0;
							  s=2;
						  }
					 }
				  }
		}
		 function UpCalendarColor(sd ,ed){
			     BgColor='#aaffaa';
				 		sd=parseInt(sd);
						ed=parseInt(ed);
						var log="";
		          for(var j=0;j<6;j++){
		              for(var i=0;i<7;i++){
						  var id=i+"-"+j;
						   var o= document.getElementById(id);
						   var c= parseInt( o.innerHTML);  //parseInt(o.value);
						   log+=id+":"+c;
						   if(c>=sd && c<=ed) o.style.backgroundColor=BgColor;
					     }
		           }
				   // document.getElementById("debug").value= log;
		 }
		 function ClickCalendar(e){
			     e = e || window.event;
                 var elementId = (e.target || e.srcElement).id;
                 var BgColor='#44ddaa';
	        	 var o= document.getElementById(elementId) ;
			  	 var day=o.innerHTML;
                 
				 if( click==2){
				 	  clickStart="";
		              clickEnd="";
				      UpCalendar();
					  click=0;
					  return;
				 }
				 if(click==0){
					 clickStartD=day;
				     clickStart= currentY+"-"+currentM+"-"+day;
					  click=1;
					   upfrom();
                       o.style.backgroundColor=BgColor;					   
					   return;
				 }
				if( click==1) {  
					clickEnd=currentY+"-"+currentM+"-"+day;
					click=2;
				 Workdays=getDays();
					 BgColor='#99dd99';
					 upfrom();	
					  UpCalendarColor(clickStartD ,day);
					
				 }
              
		         o.style.backgroundColor=BgColor;
               			 
		}
		 function getDays(){
		        var startDayArray =clickStart.split("-");
		        var nowDayArray  =clickEnd.split("-");
			    var y= parseInt(startDayArray[0]);
			    var m= parseInt(startDayArray[1]);
			    var d=parseInt( startDayArray[2]);
			    var ny= parseInt(nowDayArray[0]);
			    var nm= parseInt(nowDayArray[1]);
			    var nd= parseInt(nowDayArray[2]);
			    var td=1; 
	       // document.getElementById("debug").value=  nd+">"+d ;
			    if( ny== y){//同一年
			       if( nowDayArray[1]> startDayArray[1]){//跨月
				       td+=new Date(y,m,0).getDate(); //getMonthDay($m,$y)-$d;
					   m+=1;
				       while(m<nm){
			                 td+=Date(y,m,0).getDate(); 
				             m+=1;
				       }
				       td+= nd;
					   return  td;
				    }
		          if( nm== startDayArray[1]){//同月
				       while(nd>d){
						     var dayw=returnDay( new Date(y,m,d).getDay()) ;
					
						     if(dayw!=0 && dayw!=6 ){
							    td+=1;
							   }
                                d+=1;
					    } 
 
					   return  td;
				  }
			  }
              return  td;
		}
		 function UpCalendarColor(sd ,ed){
			     BgColor='#aaffaa';
				 		sd=parseInt(sd);
						ed=parseInt(ed);
						var log="";
		          for(var j=0;j<6;j++){
		              for(var i=0;i<7;i++){
						  var id=i+"-"+j;
						   var o= document.getElementById(id);
						   var c= parseInt( o.innerHTML);  //parseInt(o.value);
						   log+=id+":"+c;
						   if(c>=sd && c<=ed) o.style.backgroundColor=BgColor;
					     }
		           }
				    document.getElementById("debug").value= log;
		}
 