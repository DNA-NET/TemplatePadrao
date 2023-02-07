function processStateChange(){
  statusDiv = document.getElementById("stats");
  if (req.readyState == 0){ statusDiv.innerHTML = "UNINITIALIZED"; }
  if (req.readyState == 1){ statusDiv.innerHTML = "LOADING"; }
  if (req.readyState == 2){ statusDiv.innerHTML = "LOADED"; }
  if (req.readyState == 3){ statusDiv.innerHTML = "INTERACTIVE"; }
  if (req.readyState == 4){
    statusDiv.innerHTML = "COMPLETE"; 
    statusDiv.innerHTML = req.responseText; 
    }
}


try
{
// Firefox, Opera 8.0+, Safari...
req = new XMLHttpRequest();
}
catch(ex)
{
// Internet Explorer...
	try
	{
	req = new ActiveXObject('Msxml2.XMLHTTP');
	}
	catch(ex)
	{
	req = new ActiveXObject('Microsoft.XMLHTTP');
	}
}


if (req) {
    req.onreadystatechange = processStateChange;
    req.open("GET", "http://sv52.dnanet.com.br/Atualiza/api.aspx?campo=11&usuario=" + usuario + "&template=" + template + "&secao_id=" + secao, false);
    req.send();
}