function CriticaCampos(form_cep)
{
  if (form_cep.value == "")
  {
    alert("Informe no mínimo os 5(cinco) primeiros dígitos do CEP. Ex. 70001-970");
    form_cep.focus();
    return (false);
  }

  if (form_cep.value.length <= 4)
  {
   	alert("Informe no mínimo os 5(cinco) primeiros dígitos do CEP. Ex. 70001-970");
   	form_cep.focus();
   	return (false);
  }  

  { 
   var Numeros = "0123456789";
   var Posic, Carac;
   var Temp = form_cep.value.length;    
   var Cont = 0;
   for (var i=0; i < Temp; i++)   
   {  
   Carac =  form_cep.value.charAt (i);
   Posic  = Numeros.indexOf (Carac);   
   if (Posic > -1)   
	  Cont++;      
   }   
   if (Cont == 9)
   {
    	alert("O CEP tem no máximo 8(oito) digitos numéricos. Ex. 70001-970");
    	form_cep.focus();
    	return (false);
   } 
 }
  { 
   var Numeros = "0123456789-";
   var Posic, Carac;
   var Temp = form_cep.value.length;    
   var Cont = 0;
   for (var i=0; i < Temp; i++)   
   {  
   Carac =  form_cep.value.charAt (i);
   Posic  = Numeros.indexOf (Carac);   
   if (Posic == -1)   
      {	  
    	alert("Informe um CEP válido. Ex. 70001-970");
    	form_cep.focus();
    	return (false);
      }
   }   
 }
return true;
}    

function MascaraCEP (formato, keypress, objeto)
	{
	campo = eval (objeto);
	if (formato=='CEP')
		{
		caracteres = '01234567890';
		separacoes = 1;
		separacao1 = '-';
		conjuntos = 2;
		conjunto1 = 5;
		conjunto2 = 3;
		if ((caracteres.search(String.fromCharCode (keypress))!=-1) && campo.value.length < 
		(conjunto1 + conjunto2 + 1))
			{
			if (campo.value.length == conjunto1) 
			   campo.value = campo.value + separacao1;
			}
		else 
			event.returnValue = false;
		}
	}
	
	function MascaraHora (formato, keypress, objeto)
	{
	campo = eval (objeto);
	if (formato=='Hora')
		{
		caracteres = '01234567890';
		separacoes = 1;
		separacao1 = ':';
		conjuntos = 2;
		conjunto1 = 2;
		conjunto2 = 2;
		if ((caracteres.search(String.fromCharCode (keypress))!=-1) && campo.value.length < 
		(conjunto1 + conjunto2 + 1))
			{
			if (campo.value.length == conjunto1) 
			   campo.value = campo.value + separacao1;
			}
		else 
			event.returnValue = false;
		}
	}	

function format_number(pnumber,decimals)
{
	if (isNaN(pnumber)) { return 0};
	if (pnumber=='') { return 0};
	
	var snum = new String(pnumber);
	var sec = snum.split('.');
	var whole = parseFloat(sec[0]);
	var result = '';
	
	if(sec.length > 1){
		var dec = new String(sec[1]);
		dec = String(parseFloat(sec[1])/Math.pow(10,(dec.length - decimals)));
		dec = String(whole + Math.round(parseFloat(dec))/Math.pow(10,decimals));
		var dot = dec.indexOf('.');
		if(dot == -1){
			dec += '.'; 
			dot = dec.indexOf('.');
		}
		while(dec.length <= dot + decimals) { dec += '0'; }
		result = dec;
	} else{
		var dot;
		var dec = new String(whole);
		dec += '.';
		dot = dec.indexOf('.');		
		while(dec.length <= dot + decimals) { dec += '0'; }
		result = dec;
	}	
	return result;
}

function eNumero(strString)
{
   var strValidChars = "0123456789,.-";
   var strChar;
   var blnResult = true;

   if (strString.length == 0) return false;

   //  test strString consists of valid characters listed above
   for (i = 0; i < strString.length && blnResult == true; i++)
      {
      strChar = strString.charAt(i);
      if (strValidChars.indexOf(strChar) == -1)
         {
         blnResult = false;
         }
      }
   return blnResult;
}

function MascaraNumero (formato, keypress, objeto)
	{
	campo = eval (objeto);
	if (formato=='numero')
		{
		caracteres = '01234567890';
		if (caracteres.search(String.fromCharCode (keypress))!=-1)
			event.returnValue = true;
		else 
			event.returnValue = false;
		}
	}

function MascaraHora (formato, keypress, objeto)
	{
	campo = eval (objeto);
	if (formato=='hora')
		{
		caracteres = '01234567890';
		separacoes = 2;
		separacao1 = ':';
		conjuntos = 3;
		conjunto1 = 2;
		conjunto2 = 2;
		conjunto3 = 2;
		if ((caracteres.search(String.fromCharCode (keypress))!=-1) && campo.value.length < 
		(conjunto1 + conjunto2 + conjunto3 + 2))
			{
			if (campo.value.length == conjunto1) 
			{
			   campo.value = campo.value + separacao1;
			}
			if (campo.value.length == conjunto1 + conjunto2 + 1) 
			{
			   campo.value = campo.value + separacao1;			   
			}
			}
		else 
			event.returnValue = false;
		}
	}

function MascaraDada (formato, keypress, objeto)
	{
	campo = eval (objeto);
	if (formato=='data')
		{
		caracteres = '01234567890';
		separacoes = 2;
		separacao1 = '/';
		conjuntos = 3;
		conjunto1 = 2;
		conjunto2 = 2;
		conjunto3 = 4;
		if ((caracteres.search(String.fromCharCode (keypress))!=-1) && campo.value.length < 
		(conjunto1 + conjunto2 + conjunto3 + 2))
			{
			if (campo.value.length == conjunto1) 
			{
			   campo.value = campo.value + separacao1;
			}
			if (campo.value.length == conjunto1 + conjunto2 + 1) 
			{
			   campo.value = campo.value + separacao1;			   
			}
			}
		else 
			event.returnValue = false;
		}
	}
	
function convert_date(field1)
{
var fLength = field1.value.length; // Length of supplied field in characters.
var divider_values = new Array ('-','.','/',' ',':','_',','); // Array to hold permitted date seperators.  Add in '\' value
var array_elements = 7; // Number of elements in the array - divider_values.
var day1 = new String(null); // day value holder
var month1 = new String(null); // month value holder
var year1 = new String(null); // year value holder
var divider1 = null; // divider holder
var outdate1 = null; // formatted date to send back to calling field holder
var counter1 = 0; // counter for divider looping 
var divider_holder = new Array ('0','0','0'); // array to hold positions of dividers in dates
var s = String(field1.value); // supplied date value variable
 
file://If field is empty do nothing
if ( fLength == 0 ) {
   return true;
}
 
// Deal with today or now
if ( field1.value.toUpperCase() == 'NOW' || field1.value.toUpperCase() == 'TODAY' ) {
   
 var newDate1 = new Date();
 
    if (navigator.appName == "Netscape") {
      var myYear1 = newDate1.getYear() + 1900;
    }
    else {
     var myYear1 =newDate1.getYear();
    }
  
 var myMonth1 = newDate1.getMonth()+1;  
 var myDay1 = newDate1.getDate();
 field1.value = myDay1 + "/" + myMonth1 + "/" + myYear1;
 fLength = field1.value.length;//re-evaluate string length.
 s = String(field1.value)//re-evaluate the string value.
}
 
file://Check the date is the required length
if ( fLength != 0 && (fLength < 6 || fLength > 11) ) {
 invalid_date(field1);
 return false;   
 }
 
// Find position and type of divider in the date
for ( var i=0; i<3; i++ ) {
 for ( var x=0; x<array_elements; x++ ) {
  if ( s.indexOf(divider_values[x], counter1) != -1 ) {
   divider1 = divider_values[x];
   divider_holder[i] = s.indexOf(divider_values[x], counter1);
     file://alert(i + " divider1 = " + divider_holder[i]);
   counter1 = divider_holder[i] + 1;
   file://alert(i + " counter1 = " + counter1);
   break;
  }
  }
 }
 
// if element 2 is not 0 then more than 2 dividers have been found so date is invalid.
if ( divider_holder[2] != 0 ) {
   invalid_date(field1);
 return false;   
}
 
// See if no dividers are present in the date string.
if ( divider_holder[0] == 0 && divider_holder[1] == 0 ) { 
   
  file://continue processing
  if ( fLength == 6 ) {//ddmmyy
     day1 = field1.value.substring(0,2);
       month1 = field1.value.substring(2,4);
     year1 = field1.value.substring(4,6);
     if ( (year1 = validate_year(year1)) == false ) {
      invalid_date(field1);
    return false; 
    }
   }
   
  else if ( fLength == 7 ) {//ddmmmy
     day1 = field1.value.substring(0,2);
     month1 = field1.value.substring(2,5);
     year1 = field1.value.substring(5,7);
     if ( (month1 = convert_month(month1)) == false ) {
      invalid_date(field1);
    return false; 
    }
     if ( (year1 = validate_year(year1)) == false ) {
      invalid_date(field1);
    return false; 
    }
   }
  else if ( fLength == 8 ) {//ddmmyyyy
     day1 = field1.value.substring(0,2);
     month1 = field1.value.substring(2,4);
     year1 = field1.value.substring(4,8);
   }
  else if ( fLength == 9 ) {//ddmmmyyyy
     day1 = field1.value.substring(0,2);
     month1 = field1.value.substring(2,5);
     year1 = field1.value.substring(5,9);
     if ( (month1 = convert_month(month1)) == false ) {
      invalid_date(field1);
    return false; 
    }
   }
  
  if ( (outdate1 = validate_date(day1,month1,year1)) == false ) {
  /*   alert("O valor " + field1.value + " deve ser uma data correta.\n\r" +  
   "Entre uma data correta no formato dd/mm/aaaa");
   field1.focus();
   field1.select();*/
   return false;
   }
 
  field1.value = outdate1;
  return true;// All OK
  }
  
// 2 dividers are present so continue to process 
if ( divider_holder[0] != 0 && divider_holder[1] != 0 ) {  
   day1 = field1.value.substring(0, divider_holder[0]);
   month1 = field1.value.substring(divider_holder[0] + 1, divider_holder[1]);
   file://alert(month1);
   year1 = field1.value.substring(divider_holder[1] + 1, field1.value.length);
 }
 
if ( isNaN(day1) && isNaN(year1) ) { // Check day and year are numeric
 invalid_date(field1);
 return false;  
   }
 
if ( day1.length == 1 ) { file://Make d day dd
   day1 = '0' + day1;  
}
 
if ( month1.length == 1 ) {//Make m month mm
 month1 = '0' + month1;   
}
 
if ( year1.length == 2 ) {//Make yy year yyyy
   if ( (year1 = validate_year(year1)) == false ) {
    invalid_date(field1);
  return false;  
  }
}
 
if ( month1.length == 3 || month1.length == 4 ) {//Make mmm month mm
   if ( (month1 = convert_month(month1)) == false) {
    alert("month1" + month1);
    invalid_date(field1);
    return false;  
   }
}
 
// Date components are OK
if ( (day1.length == 2 || month1.length == 2 || year1.length == 4) == false) {
   invalid_date(field1);
   return false;
}
 
file://Validate the date
if ( (outdate1 = validate_date(day1, month1, year1)) == false ) {
   /*alert("O valor " + field1.value + " deve ser uma data correta.\n\r" +  
 "Entre uma data correta no formato dd/mm/aaaa");
 field1.focus();
 field1.select();*/
 return false;
}
 
// Redisplay the date in dd/mm/yyyy format
field1.value = outdate1;
return true;//All is well
 
}
/******************************************************************
   convert_month()
   
   Function to convert mmm month to mm month 
   
   Called by convert_date()    
   
*******************************************************************/
function convert_month(monthIn) {
 
var month_values = new Array ("JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC");
 
monthIn = monthIn.toUpperCase(); 
 
if ( monthIn.length == 3 ) {
 for ( var i=0; i<12; i++ ) 
  {
    if ( monthIn == month_values[i] ) 
     {
   monthIn = i + 1;
   if ( i != 10 && i != 11 && i != 12 ) 
    {
      monthIn = '0' + monthIn;
    }
   return monthIn;
   }
  }
 }
 
else if ( monthIn.length == 4 && monthIn == 'SEPT') {
   monthIn = '09';
   return monthIn;
 }
 
else {
 return false;
 } 
}
/******************************************************************
   invalid_date()
   
   If an entered date is deemed to be invalid, invali
   d_date() is called to display a warning message to
   the user.  Also returns focus to the date  in que
   stion and selects the date for edit.
        
   Called by convert_date()
   
*******************************************************************/
function invalid_date(inField) 
{
/*alert("O valor " + inField.value + " deve ser uma data correta.\n\r" + 
        "Entre uma data correta no formato dd/mm/aaaa");
inField.focus();
inField.select();*/
return true   
}
/******************************************************************
   validate_date()
   
   Validates date output from convert_date().  Checks
   day is valid for month, leap years, month !> 12,.
   
*******************************************************************/
function validate_date(day2, month2, year2)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
{                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
var DayArray = new Array(31,28,31,30,31,30,31,31,30,31,30,31);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          
var MonthArray = new Array("01","02","03","04","05","06","07","08","09","10","11","12");                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
var inpDate = day2 + month2 + year2;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
var filter=/^[0-9]{2}[0-9]{2}[0-9]{4}$/;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          
 
file://Check ddmmyyyy date supplied
if (! filter.test(inpDate))                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
  {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          
  return false;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
  }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
/* Check Valid Month */                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               
filter=/01|02|03|04|05|06|07|08|09|10|11|12/ ;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
if (! filter.test(month2))                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
  {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               
  return false;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
  }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
/* Check For Leap Year */                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
var N = Number(year2);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
if ( ( N%4==0 && N%100 !=0 ) || ( N%400==0 ) )                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
   {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
   DayArray[1]=29;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
   }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
/* Check for valid days for month */                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
for(var ctr=0; ctr<=11; ctr++)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
   {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
   if (MonthArray[ctr]==month2)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
    {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
      if (day2<= DayArray[ctr] && day2 >0 )                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
        {
        inpDate = day2 + '/' + month2 + '/' + year2;       
        return inpDate;
        }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 
      else                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
        {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          
        return false;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
        }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
    }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
   }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
}
/******************************************************************
   validate_year()
   
   converts yy years to yyyy
   Uses a hinge date of 10
        < 10 = 20yy 
        => 10 = 19yy.
         
   Called by convert_date() before validate_date().
      
*******************************************************************/
function validate_year(inYear) 
{
if ( inYear < 10 ) 
 {
   inYear = "20" + inYear;
   return inYear;
 }
else if ( inYear >= 10 )
 {
   inYear = "19" + inYear;
   return inYear;
 }
else 
 {
 return false;
 }   
}