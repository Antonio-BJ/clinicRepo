
<?php

require_once "Modelo.php";
/**
*  clase para editar los estatus de folios
*/
class RFC extends Modelo
{	
	function __construct()
	{
		 parent::__construct();
	}


function CalculaRFC($nombre,$paterno,$materno,$fecha){
	$fechaArray = explode('-', $fecha);
	$fecha=$fechaArray[0].$fechaArray[1].$fechaArray[2];
	$rfc= $this->CalcularRFC($nombre,$paterno,$materno,$fecha);		
	return $rfc;
}

//recibimos los datos.
function QuitarArticulos($palabra) 
{ 
$palabra=str_replace("DEL ","",$palabra); 
$palabra=str_replace("LAS ","",$palabra); 
$palabra=str_replace("DE ","",$palabra); 
$palabra=str_replace("LA ","",$palabra); 
$palabra=str_replace("Y ","",$palabra); 
$palabra=str_replace("A ","",$palabra); 
return $palabra; 
} 
function EsVocal($letra) 
{ 
if ($letra == 'A' || $letra == 'E' || $letra == 'I' || $letra == 'O' || $letra == 'U' || 
$letra == 'a' || $letra == 'e' || $letra == 'i' || $letra == 'o' || $letra == 'u') 
return 1; 
else 
return 0; 
} 
function CalcularRFC($nombre,$apellidoPaterno,$apellidoMaterno,$fecha) 
{ 
/*Cambiamos todo a mayúsculas. 
Quitamos los espacios al principio y final del nombre y apellidos*/ 
$nombre =strtoupper(trim($nombre)); 
$apellidoPaterno =strtoupper(trim($apellidoPaterno)); 
$apellidoMaterno =strtoupper(trim($apellidoMaterno)); 

//RFC que se regresará 
$rfc=""; 

//Quitamos los artículos de los apellidos 
$apellidoPaterno = $this->QuitarArticulos($apellidoPaterno); 
$apellidoMaterno = $this->QuitarArticulos($apellidoMaterno); 
//Agregamos el primer caracter del apellido paterno 
$rfc = substr($apellidoPaterno,0, 1); 
//Buscamos y agregamos al rfc la primera vocal del primer apellido 
$len_apellidoPaterno=strlen($apellidoPaterno); 
for($x=1;$x<$len_apellidoPaterno;$x++) 
{ 
$c=substr($apellidoPaterno,$x,1); 
if ($this->EsVocal($c)) 
{ 
$rfc .= $c; 
break; 
} 
} 

//Agregamos el primer caracter del apellido materno 
$rfc .= substr($apellidoMaterno,0, 1); 


//Agregamos el primer caracter del primer nombre 
$rfc .= substr($nombre,0, 1); 

//agregamos la fecha ddmmyyyy
$rfc .= substr($fecha,6, 2).substr($fecha,2, 2).substr($fecha,0, 2); 
//Le agregamos la homoclave al rfc 
//$this->CalcularHomoclave($apellidoPaterno." ".$apellidoMaterno." ".$nombre, $fecha,$rfc); 
return $rfc; 
} 
function CalcularHomoclave($nombreCompleto,$fecha, &$rfc) 
{ 
//Guardara el nombre en su correspondiente numérico 
//agregamos un cero al inicio de la representación númerica del nombre 
$nombreEnNumero="0"; 
//La suma de la secuencia de números de nombreEnNumero 
$valorSuma = 0; 

#region Tablas para calcular la homoclave 
//Estas tablas realmente no se porque son como son 
//solo las copie de lo que encontré en internet 

$tablaRFC1['&']='10'; 
$tablaRFC1['Ñ']='10'; 
$tablaRFC1['A']='11'; 
$tablaRFC1['B']='12'; 
$tablaRFC1['C']='13'; 
$tablaRFC1['D']='14'; 
$tablaRFC1['E']='15'; 
$tablaRFC1['F']='16'; 
$tablaRFC1['G']='17'; 
$tablaRFC1['H']='18'; 
$tablaRFC1['I']='19'; 
$tablaRFC1['J']='21'; 
$tablaRFC1['K']='22'; 
$tablaRFC1['L']='23'; 
$tablaRFC1['M']='24'; 
$tablaRFC1['N']='25'; 
$tablaRFC1['O']='26'; 
$tablaRFC1['P']='27'; 
$tablaRFC1['Q']='28'; 
$tablaRFC1['R']='29'; 
$tablaRFC1['S']='32'; 
$tablaRFC1['T']='33'; 
$tablaRFC1['U']='34'; 
$tablaRFC1['V']='35'; 
$tablaRFC1['W']='36'; 
$tablaRFC1['X']='37'; 
$tablaRFC1['Y']='38'; 
$tablaRFC1['Z']='39'; 
$tablaRFC1['0']='00'; 
$tablaRFC1['1']='01'; 
$tablaRFC1['2']='02'; 
$tablaRFC1['3']='03'; 
$tablaRFC1['4']='04'; 
$tablaRFC1['5']='05'; 
$tablaRFC1['6']='06'; 
$tablaRFC1['7']='07'; 
$tablaRFC1['8']='08'; 
$tablaRFC1['9']='09'; 

$tablaRFC2[0]="1"; 
$tablaRFC2[1]="2"; 
$tablaRFC2[2]="3"; 
$tablaRFC2[3]="4"; 
$tablaRFC2[4]="5"; 
$tablaRFC2[5]="6"; 
$tablaRFC2[6]="7"; 
$tablaRFC2[7]="8"; 
$tablaRFC2[8]="9"; 
$tablaRFC2[9]="A"; 
$tablaRFC2[10]="B"; 
$tablaRFC2[11]="C"; 
$tablaRFC2[12]="D"; 
$tablaRFC2[13]="E"; 
$tablaRFC2[14]="F"; 
$tablaRFC2[15]="G"; 
$tablaRFC2[16]="H"; 
$tablaRFC2[17]="I"; 
$tablaRFC2[18]="J"; 
$tablaRFC2[19]="K"; 
$tablaRFC2[20]="L"; 
$tablaRFC2[21]="M"; 
$tablaRFC2[22]="N"; 
$tablaRFC2[23]="P"; 
$tablaRFC2[24]="Q"; 
$tablaRFC2[25]="R"; 
$tablaRFC2[26]="S"; 
$tablaRFC2[27]="T"; 
$tablaRFC2[28]="U"; 
$tablaRFC2[29]="V"; 
$tablaRFC2[30]="W"; 
$tablaRFC2[31]="X"; 
$tablaRFC2[32]="Y"; 
$tablaRFC2[33]="Z"; 

$tablaRFC3['A']=10; 
$tablaRFC3['B']=11; 
$tablaRFC3['C']=12; 
$tablaRFC3['D']=13; 
$tablaRFC3['E']=14; 
$tablaRFC3['F']=15; 
$tablaRFC3['G']=16; 
$tablaRFC3['H']=17; 
$tablaRFC3['I']=18; 
$tablaRFC3['J']=19; 
$tablaRFC3['K']=20; 
$tablaRFC3['L']=21; 
$tablaRFC3['M']=22; 
$tablaRFC3['N']=23; 
$tablaRFC3['O']=25; 
$tablaRFC3['P']=26; 
$tablaRFC3['Q']=27; 
$tablaRFC3['R']=28; 
$tablaRFC3['S']=29; 
$tablaRFC3['T']=30; 
$tablaRFC3['U']=31; 
$tablaRFC3['V']=32; 
$tablaRFC3['W']=33; 
$tablaRFC3['X']=34; 
$tablaRFC3['Y']=35; 
$tablaRFC3['Z']=36; 
$tablaRFC3['0']=0; 
$tablaRFC3['1']=1; 
$tablaRFC3['2']=2; 
$tablaRFC3['3']=3; 
$tablaRFC3['4']=4; 
$tablaRFC3['5']=5; 
$tablaRFC3['6']=6; 
$tablaRFC3['7']=7; 
$tablaRFC3['8']=8; 
$tablaRFC3['9']=9; 
$tablaRFC3['']=24; 
$tablaRFC3[' ']=37; 

//Recorremos el nombre y vamos convirtiendo las letras en 
//su valor numérico 
$len_nombreCompleto=strlen($nombreCompleto); 
for($x=0;$x<$len_nombreCompleto;$x++) 
{ 
$c=substr($nombreCompleto,$x,1); 
if (isset($tablaRFC1[$c])) 
$nombreEnNumero.=$tablaRFC1[$c]; 
else 
$nombreEnNumero.="00"; 
} 
//Calculamos la suma de la secuencia de números 
//calculados anteriormente 
//la formula es: 
//( (el caracter actual multiplicado por diez) 
//mas el valor del caracter siguiente ) 
//(y lo anterior multiplicado por el valor del caracter siguiente) 

$n=strlen($nombreEnNumero)-1; 
for ($i = 0; $i < $n; $i++) 
{ 
$prod1 = substr($nombreEnNumero, $i, 2); 
$prod2 = substr($nombreEnNumero, $i + 1, 1); 
$valorSuma += $prod1 * $prod2; 
} 
//Lo siguiente no se porque se calcula así, es parte del algoritmo. 
//Los magic numbers que aparecen por ahí deben tener algún origen matemático 
//relacionado con el algoritmo al igual que el proceso mismo de calcular el 
//digito verificador. 
//Por esto no puedo añadir comentarios a lo que sigue, lo hice por acto de fe. 
$div = 0; 
$mod = 0; 
$div = $valorSuma % 1000; 
$mod = floor($div / 34);//cociente 
$div = $div - $mod * 34;//residuo 

$hc = $tablaRFC2[$mod]; 
$hc.= $tablaRFC2[$div]; 

$rfc .= $hc; 

//Aqui empieza el calculo del digito verificador basado en lo que tenemos del RFC 
//En esta parte tampoco conozco el origen matemático del algoritmo como para dar 
//una explicación del proceso, así que ¡tengamos fe hermanos!. 
$sumaParcial = 0; 
$n=strlen($rfc); 
for ($i = 0; $i < $n; $i++) 
{ 
$c=substr($rfc,$i,1); 
if (isset($tablaRFC3[$c])) 
{ 
$sumaParcial += ($tablaRFC3[$c] * (14 - ($i + 1))); 
} 
} 

$moduloVerificador = $sumaParcial % 11; 
if ($moduloVerificador == 0) 
$rfc .= "0"; 
else 
{ 
$sumaParcial = 11 - $moduloVerificador; 
if ($sumaParcial == 10) 
$rfc .= "A"; 
else 
$rfc .= $sumaParcial; 
} 
}
}
?>