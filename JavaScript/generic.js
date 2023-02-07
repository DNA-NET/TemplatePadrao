var ultimo='';
function showHide(gName)
	{
		if((gName==ultimo)&&(ultimo!=''))
			{
				Hide(gName);
				ultimo='';				
			}
		else
			{
				var o = document.getElementById(gName);
				if(o.style.display == 'none')
					{
						o.style.display = 'block'; 
					}
				ultimo=gName;				
			}
		
	}
function Hide(gName)
	{
		var o = document.getElementById(gName);
		if(o.style.display == 'block'){
			o.style.display = 'none'; 
		}
	}

function Mostra(gName)
	{
		var o = document.getElementById(gName);
		if(o.style.display == 'none'){
			o.style.display = 'block'; 
		}
	}

	function horizontal() {

	   var navItems = document.getElementById("menu_dropdown").getElementsByTagName("li");
	    
	   for (var i=0; i< navItems.length; i++) {
	      if(navItems[i].className == "submenu")
	      {
		 if(navItems[i].getElementsByTagName('ul')[0] != null)
		 {
		    navItems[i].onmouseover=function() {this.getElementsByTagName('ul')[0].style.display="block";this.style.backgroundColor = "";}
			navItems[i].onTouchStart=function() {this.getElementsByTagName('ul')[0].style.display="block";this.style.backgroundColor = "";}
		    navItems[i].onmouseout=function() {this.getElementsByTagName('ul')[0].style.display="none";this.style.backgroundColor = "";}
			navItems[i].onTouchEnd=function() {this.getElementsByTagName('ul')[0].style.display="none";this.style.backgroundColor = "";}
		 }
	      }
	   }

	}

	function MM_openBrWindow(theURL,winName,features) { //v2.0
	  window.open(theURL,winName,features);
	}

	function MM_findObj(n, d) { //v4.01
	  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
	    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
	  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
	  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
	  if(!x && d.getElementById) x=d.getElementById(n); return x;
	}

	function MM_showHideLayers() { //v6.0
	  var i,p,v,obj,args=MM_showHideLayers.arguments;
	  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
	    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
	    obj.visibility=v; }
	}
	var extras = 0;
	var ultimo = '';
	var item_extra = '';
	function escondecamel(id){

		var i=1;
		for (i=1;i<=extras;i++)
		{
		item_extra += 'shide' + i;
		}

		var arrayObj = Array(item_extra);
				
		if(ultimo == id){
			var obj= document.getElementById(id);
			obj.style.display = 'none';
			ultimo = '';
		}else{
			for(i=0;i<arrayObj.length;i++){
				if(arrayObj[i] == id){
					var obj= document.getElementById(id);
					obj.style.display = '';
					ultimo = id;
				}else{
					var obj= document.getElementById(arrayObj[i]);
					obj.style.display ='none';
				}
			}
		}
	}

	function setDisplayBlock(idName)		{ document.getElementById(idName).style.display = 'block'; }
	function setDisplayNone(idName)			{ document.getElementById(idName).style.display = 'none'; }


	function ap4(Foto, tamx, tamy)
	{
	window.open(Foto,"","resizable=no,toolbar=no,status=no,left = 100,top = 100,menubar=no,scrollbars=no,width=" + tamx + ",height=" + tamy)
	}