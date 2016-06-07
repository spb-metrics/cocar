<?php
/*
    Este arquivo ï¿½ parte do programa COCAR



    COCAR ï¿½ um software livre; vocï¿½ pode redistribui-lo e/ou

    modifica-lo dentro dos termos da Licenï¿½a Pï¿½blica Geral GNU como

    publicada pela Fundaï¿½ï¿½o do Software Livre (FSF); na versï¿½o 2 da

    Licenï¿½a.



    Este programa ï¿½ distribuido na esperanï¿½a que possa ser  util,

    mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUAï¿½ï¿½O a qualquer

    MERCADO ou APLICAï¿½ï¿½O EM PARTICULAR. Veja a

    Licenï¿½a Pï¿½blica Geral GNU para maiores detalhes (GPL2.txt).



    Vocï¿½ deve ter recebido uma cï¿½pia da Licenï¿½a Pï¿½blica Geral GNU

    junto com este programa, se nï¿½o, escreva para a Fundaï¿½ï¿½o do Software

    Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

	// Desativa o relatorio de todos os erros
	error_reporting(0);
	$Host 		= $_POST["Host"];
	$Community 	= $_POST["Community"];
	$obj		= $_GET["obj"];
	$Programa 	= $_SERVER['PHP_SELF'];

	$array = array( 
      "====== Equipamentos ======"		=> array("#",	"Host",				"Community"),
      "<------ Roteador ------>"=> array("#",	"Host",				"Community"),
      "Acre"				=> array("2",	"loopgerac",		"startrek"),
      "Alagoas"				=> array("3",	"loopgeral",		"community"),
      "Amapa"				=> array("4",	"loopgerap",		"community"),
      "Amazonas"			=> array("5",	"loopgeram",		"community"),
      "Bahia"				=> array("6",	"loopgerba",		"community"),
      "Espirito Santo"		=> array("10",	"loopgeres",		"community"),
      "Goias"				=> array("11",	"loopgergo",		"community"),
      "Maranhao"			=> array("12",	"loopgerma",		"community"),
      "Mato Grosso"			=> array("13",	"loopgermt",		"community"),
      "Mato Grosso do Sul"	=> array("14",	"loopgerms",		"community"),
      "Minas Gerais"		=> array("15",	"loopgermg",		"community"),
      "Para"				=> array("16",	"loopgerpa",		"community"),
      "Paraiba"				=> array("17",	"loopgerpb",		"community"),
      "Parana"				=> array("18",	"loopgerpr",		"community"),
      "Pernambuco"			=> array("19",	"loopgerpe",		"community"),
      "Piaui"				=> array("20",	"loopgerpi",		"community"),
      "Rio Grande do Norte"	=> array("21",	"loopgerrn",		"community"),
      "Rio Grande do Sul"	=> array("22",	"loopgerrs",		"community"),
     	);

?>
<html>
<head>
<title>Informa&ccedil;&otilde;es das Interfaces</title>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<style type="text/css">

body
	{
		scrollbar-face-color: #FFFFFF; 
		scrollbar-shadow-color: #c0c0c0; 
		scrollbar-highlight-color: #FFFFFF; 
		scrollbar-3dlight-color: #c0c0c0; 
		scrollbar-darkshadow-color: #FFFFFF;
		scrollbar-track-color: #ffffff; 
		scrollbar-arrow-color: #c0c0c0;
		font-family: Verdana, Arial; 
		font-size: 11pt; 
	}

.styletextbox 
	{
		BORDER-BOTTOM: 1px solid; 
		BORDER-LEFT: 1px solid; 
		BORDER-RIGHT: 1px solid; 
		BORDER-TOP: 1px solid; 
		FONT-FAMILY: Arial, Helvetica, sans-serif; 
		FONT-SIZE: 9pt; 
		WIDTH: 150px
	}

.botao
	{
		FONT-SIZE: 12px;
		COLOR: #000000; 
		FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif;
		FONT-WEIGHT: Bold;
		CURSOR: hand;
		WIDTH: 30px;
	}

.tabela 
	{
		border-width: 2px;
		border-style: solid;
		border-color: #FFA824;
	}

.par
	{
		background-color: #FFFFFF; 
		border-width: 1px;
		border-style: solid;
		border-color: #FFA824;
		FONT-FAMILY: Verdana, Arial; 
		FONT-SIZE: 9pt;
	}

.topo
	{
		background-color: #CDEDCF; 
		border-width: 1px;
		border-style: solid;
		border-color: #FFA824;
		FONT-FAMILY: Verdana, Arial; 
		FONT-SIZE: 9pt;
	}

.base
	{
		background-color: #FFFFCC; 
		border-width: 1px;
		border-style: solid;
		border-color: #FFA824;
		FONT-FAMILY: Verdana, Arial; 
		FONT-SIZE: 9pt;
	}

.impar
	{	
		background-color: #EEEEE6; 
		border-width: 1px;
		border-style: solid;
		border-color: #FFA824;
		FONT-FAMILY: Verdana, Arial; 
		FONT-SIZE: 9pt;
	}

.impar2
	{
		background-color: #FFFFCC; 
		border-width: 1px;
		border-style: solid;
		border-color: #FFA824;
		FONT-FAMILY: Verdana, Arial; 
		FONT-SIZE: 9pt;
	}

.thfont
	{
		background-color: #006C36; 
		border-width: 1px;
		border-style: solid;
		border-color: #006C36;
		FONT-FAMILY: Arial, Helvetica, sans-serif; 
		FONT-SIZE: 10pt; 
		COLOR: #FFFFFF; 
		FONT-WEIGHT: Bold;
	}

</style>

<script language="javascript">
function mudapagina(combo)
{
	var endereco = combo.value;
	if (endereco!="#" || endereco!="")
	{
		novapagina = window.location=endereco;
	}
}
</script>

<script language="JavaScript">
function validaForm()
{
		//validar Host
		d = document.equipamento;
		if (d.Host.value == ""){
			alert("O campo " + d.Host.name + " deve ser preenchido!\nDigite o Ip ou o Nome do Host.");
			d.Host.focus();
			return false;
		}
		//validar Host
		if (d.Community.value == ""){
			alert("O campo " + d.Community.name + " deve ser preenchido!");
			d.Community.focus();
			return false;
		}
		return true;
	}
</script>
</head>

<body>

<TABLE width="650" ALIGN="center" CELLSPACING="3" CELLPADDING="0">
  <TR>
	<td bgcolor="#0078B3" align="center" valign="middle" WIDTH="150" height="100%">
		<font color="#FFFFFF" size="3" face="Arial, Helvetica, sans-serif"><B>DIPRO<BR>Interfaces</B></font>
	</td>
	<td align="center" valign="top">
		<form action="<?php $$_SERVER['PHP_SELF']?>" method="POST" name="equipamento" id="rede_acesso" onSubmit="return validaForm()">
        	<table border="0" width="100%" cellspacing="0" height="65" cellpadding="2" ALIGN="center">
            <tr>
               	<td width="100%" height="55%" ALIGN="center" VALIGN="middle" bgcolor="#B3D9FF" >
				<select size="1" name="Host" onChange="mudapagina(this);">
   
<?php   

   	reset($array);
	 while ($NewArray = current($array)) 
	{ 
    		$option= key($array);
			
			echo "<option value='" .  $_SERVER['PHP_SELF'] . "?obj=" . $NewArray[0] . "'>" . $option . "\n";
      
		  	next($array); 
  	} 

?>
  				</select>
            </td>
        </tr>
        <tr>
        	<td width="100%" height="45%" bgcolor="#0099CC" ALIGN="center" VALIGN="middle">
  				<FONT FACE="Arial" COLOR="#FFFFFF" SIZE="2"><B>Host&nbsp;</B></FONT>
				  	<input type="text" name="Host" CLASS="styletextbox"  MAXLENGTH="18">
				    &nbsp;<FONT FACE="Arial" COLOR="#FFFFFF" SIZE="2"><B>Community&nbsp;</B></FONT>
					<input type="password" name="Community" MAXLENGTH="15" CLASS="styletextbox">
				&nbsp;&nbsp;<input  class="botao" name="cadastrar" type="submit" id="OK" value="OK">
            </td>
        </tr>
    		</table>
  </TR>
		</form>	
</TABLE>	


<?php

############ 	$Veloc			= str_replace($lixo, $limpo, snmpwalk($Host, $Community, ".1.3.6.1.2.1.2.2.1.5", 	 $snmptimeout));
////////////////////////////  PARA SABER O  TIPO DE MEMÓRIA ???????
#$aux = explode(":", $sysConfigName);
#$image = $aux[0];
////////////////////////////////////////////////////////////////

#######################################################
	snmp_set_quick_print(1);
	
	$timeout = 500000; // For initial check 1000000 = 1 second
	$lixo = array ("\"", "STRING: ", "INTEGER: ", "Counter32: ", "IpAddress: ");
	$limpo = array ("", "", "", "", "");
#######################################################

$y = 0; # ele eh global
	
if($obj!="" &&  $Host=="" && $Community=="")
{
	reset($array); 
	while ($Vet = current($array)) 
	{ 
    		$option = key($array);
			
			if ($Vet[0]== $obj)		
			{
				$Host= $Vet[1];
				$Community=$Vet[2];
			}
    	next($array); 
  	} 

}

if($Host!="")
{
	$sysName = str_replace($lixo, $limpo, snmpget($Host, $Community, ".1.3.6.1.2.1.1.5.0", $timeout));	
	if ($sysName != "")
		chamadas($Host, $Community);
	else
		problemas($Host);
		
}

function chamadas($Host, $Community)
{
	imprimeHost($Host);
		geral($Host, $Community);
	interfaces($Host, $Community);
}

function problemas($Host)
{
	global $Programa;

	$Host = str_replace("$Programa?Host=", "", $Host); 
	
	echo "<BR><BR>
		<DIV ALIGN='center'><H2>PROBLEMAS!!!&nbsp;&nbsp;&nbsp;<FONT COLOR='#0000FF'>$Host</FONT></DIV></H2>
	<HR WIDTH='600' COLOR='#333399'>
<PRE>
<FONT FACE='Verdana' SIZE='3'>

			>>>&nbsp;&nbsp;&nbsp;Verifique se o equipamento possui <B>'Hostname'</B>.

			>>>&nbsp;&nbsp;&nbsp;Verifique se equipamento está ligado. Usando comando <B>ping</B>.

			>>>&nbsp;&nbsp;&nbsp;Verifique se o <B>ip</B> e/ou <B>Community</B> esta(ão) correto(s).
</FONT>
</PRE>
	";
}
function imprimeHost($Host)
{
	$chassis = chassis($Host, $Community);
	echo "<FONT SIZE='+1' COLOR='#000000' FACE='Verdana'><B>&nbsp;&nbsp;&nbsp;$Host</FONT>&nbsp;&nbsp;(Modelo: $chassis)</B><HR WIDTH='100%' COLOR='#006C36'>";
}
function geral($Host, $Community)
{
	global $timeout, $lixo, $limpo, $sysName, $y;
	$y=0; ########### ele é global
	$oids = array(
		"sysUptime" => ".1.3.6.1.2.1.1.3.0",
		"whyReload" => ".1.3.6.1.4.1.9.2.1.2.0",
		"version" => ".1.3.6.1.2.1.1.1.0",
		"location" => ".1.3.6.1.2.1.1.6.0",
		"contact" => ".1.3.6.1.2.1.1.4.0",
		"avgBusy1" => ".1.3.6.1.4.1.9.2.1.57.0",
		"avgBusy5" => ".1.3.6.1.4.1.9.2.1.58.0",
		"sysConfigName" => ".1.3.6.1.4.1.9.2.1.73.0",
		"tsLines" => ".1.3.6.1.4.1.9.2.9.1.0",
		"cmSystemInstalledModem" => ".1.3.6.1.4.1.9.9.47.1.1.1.0",
		"cmSystemModemsInUse" => ".1.3.6.1.4.1.9.9.47.1.1.6.0",
		"cmSystemModemsDead" => ".1.3.6.1.4.1.9.9.47.1.1.10.0",
		"Memory" => ".1.3.6.1.4.1.9.3.6.6.0",
		"Services" => ".1.3.6.1.2.1.1.7.0"
	);
  
	$whyReload 	= str_replace($lixo, $limpo, snmpget($Host, $Community, $oids{whyReload},	$timeout));
	$location 	= str_replace($lixo, $limpo, snmpget($Host, $Community,	$oids{location},	$timeout));
	$contact 	= str_replace($lixo, $limpo, snmpget($Host, $Community,	$oids{contact},		$timeout));
	$Memory 	= str_replace($lixo, $limpo, snmpget($Host, $Community, $oids{Memory},		$timeout));
	$version 	= str_replace($lixo, $limpo, snmpget($Host, $Community, $oids{version},		$timeout));
	$avgBusy1 	= str_replace($lixo, $limpo, snmpget($Host, $Community, $oids{avgBusy1},	$timeout));
	$avgBusy5 	= str_replace($lixo, $limpo, snmpget($Host, $Community, $oids{avgBusy5},	$timeout));
	$sysCfgName	= str_replace($lixo, $limpo, snmpget($Host, $Community, $oids{sysConfigName},$timeout));
	$tsLines 	= str_replace($lixo, $limpo, snmpget($Host, $Community, $oids{tsLines},		$timeout));
	$Services	= str_replace($lixo, $limpo, snmpget($Host, $Community, $oids{Services},	$timeout));
	$SysModem 	= str_replace($lixo, $limpo, snmpget($Host, $Community, $oids{cmSystemInstalledModem},$timeout));
	
	$Services = syssrv($Services);

	$Memory /= (1024*1024); # TRANSFORMA PARA Mb

	$aux = explode(",", stristr($version, "Version "));
	$version = $aux[0];

	$sysUpTime 		= str_replace($lixo, $limpo, snmpget($Host, $Community,	 $oids{sysUptime},		$timeout));
	$sysUpTime = uptime($sysUpTime); # FORMATA SYSUPTIME

	echo "<BR>
		<TABLE border=0	 CLASS='tabela' CELLPADDING='2' ALIGN='center'>
		<TR><TH CLASS='thfont' COLSPAN='2'>Informa&ccedil;&otilde;es Gerais</TH></TR>
	";

	print_infogeral ("UpTime", "$sysUpTime  (motivo: $whyReload)");
	print_infogeral ("Nome do Equipamento", "$sysName");
	print_infogeral ("Services", "$Services");
	if ($location != "")
		print_infogeral ("Localiza&ccedil;&atilde;o", "$location");
	if ($contact != "")	
		print_infogeral ("Contato", "$contact");
	print_infogeral ("Mem&oacute;ria Mb", "$Memory");
	print_infogeral ("Vers&atilde;o", "$version");
	print_infogeral ("1/5 min CPU util", "$avgBusy1/$avgBusy5 %");
	print_infogeral ("Imagem Carregada", "$sysCfgName");
	print_infogeral ("Terminal lines", "$tsLines");

	if ($SysModem > 0)
	{
		$ModemsInUse	= str_replace($lixo, $limpo, snmpget($Host, $Community, $oids{cmSystemModemsInUse},$timeout));
		$ModemsDead		= str_replace($lixo, $limpo, snmpget($Host, $Community, $oids{cmSystemModemsDead},$timeout));

		print_infogeral ("Digital modems", "$SysModem");
		print_infogeral ("In use modems", "$ModemsInUse");
		print_infogeral ("Modems Dead", "$ModemsDead");
	}

	echo "</table></td><td valign=top>";
} ################ FIM DA FUNÇÃO GERAL

function interfaces($Host, $Community)
{
	global $timeout, $lixo, $limpo, $y;
	$y=0; ########### ele é global
	
	$oids = array( 
		"sysUptime" => ".1.3.6.1.2.1.1.3.0",
		"IfIndex" => ".1.3.6.1.2.1.2.2.1.1",
		"IfDescr" => ".1.3.6.1.2.1.2.2.1.2",
		"Description" => ".1.3.6.1.2.1.31.1.1.1.18",
		"Ip" => ".1.3.6.1.2.1.4.20.1.1",
		"IpPorder" => ".1.3.6.1.2.1.4.20.1.2",		
		"IfAdminStatus" => ".1.3.6.1.2.1.2.2.1.7",
		"IfOperStatus" => ".1.3.6.1.2.1.2.2.1.8",
		"LastChange" => ".1.3.6.1.2.1.2.2.1.9",
		"ifmMac" => ".1.3.6.1.2.1.2.2.1.6"
		);

	$sysUpTime 		= str_replace($lixo, $limpo, snmpget($Host, $Community,	 $oids{sysUptime},		$timeout));
	$IfIndex 		= str_replace($lixo, $limpo, snmpwalk($Host, $Community, $oids{IfIndex},		$timeout));
	$IfDescr		= str_replace($lixo, $limpo, snmpwalk($Host, $Community, $oids{IfDescr}, 	 	$timeout));
	$Description 	= str_replace($lixo, $limpo, snmpwalk($Host, $Community, $oids{Description},	$timeout));
	$IP				= str_replace($lixo, $limpo, snmpwalk($Host, $Community, $oids{Ip},	 			$timeout));
	$IpPorder		= str_replace($lixo, $limpo, snmpwalk($Host, $Community, $oids{IpPorder},		$timeout));
	$ifAdminStatus  = str_replace($lixo, $limpo, snmpwalk($Host, $Community, $oids{IfAdminStatus}, 	$timeout));
  	$ifOperStatus   = str_replace($lixo, $limpo, snmpwalk($Host, $Community, $oids{IfOperStatus},	$timeout));
	$LastChange		= str_replace($lixo, $limpo, snmpwalk($Host, $Community, $oids{LastChange},	 	$timeout));
	$ifmMac			= str_replace($lixo, $limpo, snmpwalk($Host, $Community, $oids{ifmMac},	 	$timeout));
	
	$segsUT = segs($sysUpTime); # TOTAL DO SYSUPTIME EM SEGUNDOS

if (count($IP)==count($IpPorder))
{
		for ($i=0; $i < count($IfIndex); $i++)
		{	
				for ($x=0; $x<count($IP); $x++)
				{
	    				$indice = $IfIndex[$i];
						    					
	    				if ($IfIndex[$i] == $IpPorder[$x])
								$IPs[$indice] = $IP[$x];
		   	 	}
		}
}

	echo 	
	"	<BR>
		<TABLE border=1 width='97%' CLASS='tabela' CELLPADDING='2'>
		<TR><TH COLSPAN='8' CLASS='thfont'>Informa&ccedil;&otilde;es das Interfaces</TH></TR>
		<TR><TH CLASS='topo' ROWSPAN='2'>N&ordm;<BR>Interface</TH><TH CLASS='topo' ROWSPAN='2'>Tipo de <BR>Interface</TH><TH CLASS='topo' ROWSPAN='2'>Descri&ccedil;&atilde;o da Interface</TH><TH CLASS='topo' COLSPAN='2'>Status</TH><TH CLASS='topo' ROWSPAN='2'>Endere&ccedil;o<br>Ip</TH><TH CLASS='topo' ROWSPAN='2'>Endere&ccedil;o<br>MAC</TH><TH CLASS='topo' ROWSPAN='2'>Last Change</TH></TR>
		<TR><TH CLASS='topo'>Adm</TH><TH CLASS='topo'>Opr</TH></TR>	";
	
	for ($i=0; $i < count($IfIndex); $i++)
	{	
		
		$AdminStatus 	= status($ifAdminStatus[$i]);
		$OperStatus 	= status($ifOperStatus[$i]);

		$segsLC = segs($LastChange[$i]);
		$lsch = vertime	($segsUT - $segsLC);

		if ($Description[$i] == "")
			$Description[$i] = "&nbsp;";
		
		
		$indice = $IfIndex[$i];
		
		if ($IPs[$indice]=="")
			$IPs[$indice]="&nbsp;";
		
		$classe = par();

		echo "<TR ALIGN='center'><TD ALIGN='center' CLASS='$classe'>$IfIndex[$i]</TD><TD CLASS='$classe'>$IfDescr[$i]</TD><TD CLASS='$classe'>$Description[$i]</TD><TD CLASS='$classe'>$AdminStatus</TD><TD CLASS='$classe'>$OperStatus</TD><TD CLASS='$classe'>$IPs[$indice]</TD>";
		if ($ifmMac[$i])
		{ 
		/*
			$m1=$m2=$m3=$m4=$m5=$m6=0;
      		list($m1,$m2,$m3,$m4,$m5,$m6) = explode(":",$ifmMac[$i]);
 		  	$xz	= printf("<td CLASS='$classe'>%02X %02X %02X %02X %02X %02X</td>",hexdec($m1),hexdec($m2),hexdec($m3),hexdec($m4),hexdec($m5),hexdec($m6));
		*/
			echo "<td CLASS='$classe'>$ifmMac[$i]</td>";
		}
		else 
			echo("<td CLASS='$classe'>&nbsp;</td>");	
		echo "<TD CLASS='$classe'>$lsch</TD></TR>";
		
	}
	echo "</TABLE>";

} ////////////////////////  FIM DA FUNÇÃO INTERFACES

function MemoryFlash($Host, $Community)
{
	global $timeout, $lixo, $limpo, $y;
	$y=0; ########### ele é global

	$oids = array(
		"flashSize" => ".1.3.6.1.4.1.9.2.10.1.0",
		"flashFree" => ".1.3.6.1.4.1.9.2.10.2.0",
		"lflashFileName" => ".1.3.6.1.4.1.9.2.10.17.1.1",
		"lflashFileSize" => ".1.3.6.1.4.1.9.2.10.17.1.2",
		);

	$FlashNames = str_replace($lixo, $limpo, snmpwalk($Host, $Community, $oids{lflashFileName},	$timeout));
	$size 		= str_replace($lixo, $limpo, snmpwalk($Host, $Community, $oids{lflashFileSize}, $timeout));
	$flashSize	= str_replace($lixo, $limpo, snmpget($Host, $Community, $oids{flashSize},		$timeout));
	$flashFree	= str_replace($lixo, $limpo, snmpget($Host, $Community, $oids{flashFree}, 		$timeout));

	$flashSize /= (1024*1024);
	$freeflash = round(($flashFree/(1024*1024)), 2);

	echo "<BR>
		<TABLE border=0	 CLASS='tabela' CELLPADDING='2' ALIGN='center' WIDTH='350'>
		<TR><TH CLASS='thfont' COLSPAN='2'>&Iacute;ndices de Mem&oacute;ria Flash</TH></TR>
		<TR BGCOLOR='#CDEDCF'><TD Class='topo' ALIGN='center'><B>Filename</B></TD><TD Class='topo' ALIGN='center'><B>Size</B></TD></TR>";			

	for ($x = 0; $x<count($FlashNames); $x++)
		echo "<TR><TD CLASS='par'>$FlashNames[$x]</TD><TD CLASS='par'>$size[$x]</TD></TR>";
	

	echo 	"<TR><TD COLSPAN='2' CLASS='base' BGCOLOR='#FFFFCC' ALIGN='center'><B>Total flash:</B> $flashSize Mb<br>
			 <B>Livre:</B> $freeflash Mb</TD></TR></table>";

} // FIM DA FUNCAO MemoriaFlash

function hardware($Host, $Community)
{
	global $timeout, $lixo, $limpo, $y;
	$y=0; ########### ele é global

	$CardTypes[1] = "desconhecido";
	$CardTypes[2] = "csc1";
	$CardTypes[3] = "csc2";
	$CardTypes[4] = "csc3";
	$CardTypes[5] = "csc4";
	$CardTypes[6] = "rp";
	$CardTypes[7] = "cpu-igs";
	$CardTypes[8] = "cpu-2500";
	$CardTypes[9] = "cpu-3000";
	$CardTypes[10] = "cpu-3100";
	$CardTypes[11] = "cpu-accessPro";
	$CardTypes[12] = "cpu-4000";
	$CardTypes[13] = "cpu-4000m";
	$CardTypes[14] = "cpu-4500";
	$CardTypes[15] = "rsp1";
	$CardTypes[16] = "rsp2";
	$CardTypes[17] = "cpu-4500m";
	$CardTypes[18] = "cpu-1003";
	$CardTypes[19] = "cpu-4700";
	$CardTypes[20] = "csc-m";
	$CardTypes[21] = "csc-mt";
	$CardTypes[22] = "csc-mc";
	$CardTypes[23] = "csc-mcplus";
	$CardTypes[24] = "csc-envm";
	$CardTypes[25] = "chassisInterface";
	$CardTypes[26] = "cpu-4700S";
	$CardTypes[27] = "cpu-7200-npe100";
	$CardTypes[28] = "rsp7000";
	$CardTypes[29] = "chassisInterface7000";
	$CardTypes[30] = "rsp4";
	$CardTypes[31] = "cpu-3600";
	$CardTypes[32] = "cpu-as5200";
	$CardTypes[33] = "c7200-io1fe";
	$CardTypes[34] = "cpu-4700m";
	$CardTypes[35] = "cpu-1600";
	$CardTypes[36] = "c7200-io";
	$CardTypes[37] = "cpu-1503";
	$CardTypes[38] = "cpu-1502";
	$CardTypes[39] = "cpu-as5300";
	$CardTypes[40] = "csc-16";
	$CardTypes[41] = "csc-p";
	$CardTypes[50] = "csc-a";
	$CardTypes[51] = "csc-e1";
	$CardTypes[52] = "csc-e2";
	$CardTypes[53] = "csc-y";
	$CardTypes[54] = "csc-s";
	$CardTypes[55] = "csc-t";
	$CardTypes[80] = "csc-r";
	$CardTypes[81] = "csc-r16";
	$CardTypes[82] = "csc-r16m";
	$CardTypes[83] = "csc-1r";
	$CardTypes[84] = "csc-2r";
	$CardTypes[56] = "sci4s";
	$CardTypes[57] = "sci2s2t";
	$CardTypes[58] = "sci4t";
	$CardTypes[59] = "mci1t";
	$CardTypes[60] = "mci2t";
	$CardTypes[61] = "mci1s";
	$CardTypes[62] = "mci1s1t";
	$CardTypes[63] = "mci2s";
	$CardTypes[64] = "mci1e";
	$CardTypes[65] = "mci1e1t";
	$CardTypes[66] = "mci1e2t";
	$CardTypes[67] = "mci1e1s";
	$CardTypes[68] = "mci1e1s1t";
	$CardTypes[69] = "mci1e2s";
	$CardTypes[70] = "mci2e";
	$CardTypes[71] = "mci2e1t";
	$CardTypes[72] = "mci2e2t";
	$CardTypes[73] = "mci2e1s";
	$CardTypes[74] = "mci2e1s1t";
	$CardTypes[75] = "mci2e2s";
	$CardTypes[100] = "csc-cctl1";
	$CardTypes[101] = "csc-cctl2";
	$CardTypes[110] = "csc-mec2";
	$CardTypes[111] = "csc-mec4";
	$CardTypes[112] = "csc-mec6";
	$CardTypes[113] = "csc-fci";
	$CardTypes[114] = "csc-fcit";
	$CardTypes[115] = "csc-hsci";
	$CardTypes[116] = "csc-ctr";
	$CardTypes[121] = "cpu-7200-npe150";
	$CardTypes[122] = "cpu-7200-npe200";
	$CardTypes[123] = "cpu-wsx5302";
	$CardTypes[124] = "gsr-rp";
	$CardTypes[126] = "cpu-3810";
	$CardTypes[150] = "sp";
	$CardTypes[151] = "eip";
	$CardTypes[152] = "fip";
	$CardTypes[153] = "hip";
	$CardTypes[154] = "sip";
	$CardTypes[155] = "trip";
	$CardTypes[156] = "fsip";
	$CardTypes[157] = "aip";
	$CardTypes[158] = "mip";
	$CardTypes[159] = "ssp";
	$CardTypes[160] = "cip";
	$CardTypes[161] = "srs-fip";
	$CardTypes[162] = "srs-trip";
	$CardTypes[163] = "feip";
	$CardTypes[164] = "vip";
	$CardTypes[165] = "vip2";
	$CardTypes[166] = "ssip";
	$CardTypes[167] = "smip";
	$CardTypes[168] = "posip";
	$CardTypes[169] = "feip-tx";
	$CardTypes[170] = "feip-fx";
	$CardTypes[178] = "cbrt1";
	$CardTypes[179] = "cbr120e1";
	$CardTypes[180] = "cbr75e";
	$CardTypes[181] = "vip2-50";
	$CardTypes[182] = "feip2";
	$CardTypes[183] = "acip";
	$CardTypes[200] = "npm-4000-fddi-sas";
	$CardTypes[201] = "npm-4000-fddi-das";
	$CardTypes[202] = "npm-4000-1e";
	$CardTypes[203] = "npm-4000-1r";
	$CardTypes[204] = "npm-4000-2s";
	$CardTypes[205] = "npm-4000-2e1";
	$CardTypes[206] = "npm-4000-2e";
	$CardTypes[207] = "npm-4000-2r1";
	$CardTypes[208] = "npm-4000-2r";
	$CardTypes[209] = "npm-4000-4t";
	$CardTypes[210] = "npm-4000-4b";
	$CardTypes[211] = "npm-4000-8b";
	$CardTypes[212] = "npm-4000-ct1";
	$CardTypes[213] = "npm-4000-ce1";
	$CardTypes[214] = "npm-4000-1a";
	$CardTypes[215] = "npm-4000-6e";
	$CardTypes[217] = "npm-4000-1fe";
	$CardTypes[218] = "npm-4000-1hssi";
	$CardTypes[230] = "pa-1fe";
	$CardTypes[231] = "pa-8e";
	$CardTypes[232] = "pa-4e";
	$CardTypes[233] = "pa-5e";
	$CardTypes[234] = "pa-4t";
	$CardTypes[235] = "pa-4r";
	$CardTypes[236] = "pa-fddi";
	$CardTypes[237] = "sa-encryption";
	$CardTypes[238] = "pa-ah1t";
	$CardTypes[239] = "pa-ah2t";
	$CardTypes[241] = "pa-a8t-v35";
	$CardTypes[242] = "pa-1fe-tx-isl";
	$CardTypes[243] = "pa-1fe-fx-isl";
	$CardTypes[244] = "pa-1fe-tx-nisl";
	$CardTypes[245] = "sa-compression";
	$CardTypes[246] = "pa-atm-lite-1";
	$CardTypes[247] = "pa-ct3";
	$CardTypes[248] = "pa-oc3sm-mux-cbrt1";
	$CardTypes[249] = "pa-oc3sm-mux-cbr120e1";
	$CardTypes[254] = "pa-ds3-mux-cbrt1";
	$CardTypes[255] = "pa-e3-mux-cbr120e1";
	$CardTypes[257] = "pa-8b-st";
	$CardTypes[258] = "pa-4b-u";
	$CardTypes[259] = "pa-fddi-fd";
	$CardTypes[260] = "pm-cpm-1e2w";
	$CardTypes[261] = "pm-cpm-2e2w";
	$CardTypes[262] = "pm-cpm-1e1r2w";
	$CardTypes[263] = "pm-ct1-csu";
	$CardTypes[264] = "pm-2ct1-csu";
	$CardTypes[265] = "pm-ct1-dsx1";
	$CardTypes[266] = "pm-2ct1-dsx1";
	$CardTypes[267] = "pm-ce1-balanced";
	$CardTypes[268] = "pm-2ce1-balanced";
	$CardTypes[269] = "pm-ce1-unbalanced";
	$CardTypes[270] = "pm-2ce1-unbalanced";
	$CardTypes[271] = "pm-4b-u";
	$CardTypes[272] = "pm-4b-st";
	$CardTypes[273] = "pm-8b-u";
	$CardTypes[274] = "pm-8b-st";
	$CardTypes[275] = "pm-4as";
	$CardTypes[276] = "pm-8as";
	$CardTypes[277] = "pm-4e";
	$CardTypes[278] = "pm-1e";
	$CardTypes[280] = "pm-m4t";
	$CardTypes[281] = "pm-16a";
	$CardTypes[282] = "pm-32a";
	$CardTypes[283] = "pm-c3600-1fe-tx";
	$CardTypes[284] = "pm-c3600-compression";
	$CardTypes[285] = "pm-dmodem";
	$CardTypes[288] = "pm-c3600-1fe-fx";
	$CardTypes[288] = "pm-c3600-1fe-fx";
	$CardTypes[290] = "as5200-carrier";
	$CardTypes[291] = "as5200-2ct1";
	$CardTypes[292] = "as5200-2ce1";
	$CardTypes[310] = "pm-as5xxx-12m";
	$CardTypes[330] = "wm-c2500-5in1";
	$CardTypes[331] = "wm-c2500-t1-csudsu";
	$CardTypes[332] = "wm-c2500-sw56-2wire-csudsu";
	$CardTypes[333] = "wm-c2500-sw56-4wire-csudsu";
	$CardTypes[334] = "wm-c2500-bri";
	$CardTypes[335] = "wm-c2500-bri-nt1";
	$CardTypes[360] = "wic-serial-1t";
	$CardTypes[364] = "wic-s-t-3420";
	$CardTypes[365] = "wic-s-t-2186";
	$CardTypes[366] = "wic-u-3420";
	$CardTypes[367] = "wic-u-2091";
	$CardTypes[368] = "wic-u-2091-2081";
	$CardTypes[400] = "pa-jt2";
	$CardTypes[401] = "pa-posdw";
	$CardTypes[402] = "pa-4me1-bal";
	$CardTypes[414] = "pa-a8t-x21";
	$CardTypes[415] = "pa-a8t-rs232";
	$CardTypes[416] = "pa-4me1-unbal";
	$CardTypes[417] = "pa-4r-fdx";
	$CardTypes[424] = ",pa-1fe-fx-nisl";
	$CardTypes[435] = ",mc3810-dcm";
	$CardTypes[436] = ",mc3810-mfm-e1balanced-bri";
	$CardTypes[437] = ",mc3810-mfm-e1unbalanced-bri";
	$CardTypes[438] = ",mc3810-mfm-e1-unbalanced";
	$CardTypes[439] = ",mc3810-mfm-dsx1-bri";
	$CardTypes[440] = ",mc3810-mfm-dsx1-csu";
	$CardTypes[441] = ",mc3810-vcm";
	$CardTypes[442] = ",mc3810-avm";
	$CardTypes[443] = ",mc3810-avm-fxs";
	$CardTypes[444] = ",mc3810-avm-fxo";
	$CardTypes[445] = ",mc3810-avm-em";
	$CardTypes[445] = ",mc3810-avm-em";
	$CardTypes[480] = ",as5300-4ct1";
	$CardTypes[481] = ",as5300-4ce1";
	$CardTypes[482] = ",as5300-carrier";
	$CardTypes[500] = ",vic-em";
	$CardTypes[501] = "vic-fxo";
	$CardTypes[502] = "vic-fxs";
	$CardTypes[503] = "vpm-2v";
	$CardTypes[504] = "vpm-4v";
	$CardTypes[530] = ",pos-qoc3-mm";
	$CardTypes[531] = ",pos-qoc3-sm";
	$CardTypes[532] = ",pos-oc12-mm";
	$CardTypes[533] = ",pos-oc12-sm";
	$CardTypes[534] = ",atm-oc12-mm";
	$CardTypes[535] = ",atm-oc12-sm";
	$CardTypes[536] = ",pos-oc48-mm-l";
	$CardTypes[537] = ",pos-oc48-sm-l";
	$CardTypes[538] = ",gsr-sfc";
	$CardTypes[539] = ",gsr-csc";
	$CardTypes[540] = ",gsr-csc4";
	$CardTypes[541] = ",gsr-csc8";
	$CardTypes[542] = ",gsr-sfc8";
	$CardTypes[545] = ",gsr-oc12chds3-mm";
	$CardTypes[546] = ",gsr-oc12chds3-sm";
	$CardTypes[546] = ",gsr-oc12chds3-sm";
	$CardTypes[546] = ",gsr-oc12chds3-sm";
	$CardTypes[605] = ",pm-atm25";

	$oids = array(
		"cards"		=> ".1.3.6.1.4.1.9.3.6.11.1.3",
		"ctype"		=> ".1.3.6.1.4.1.9.3.6.11.1.2",
		"slotnum"	=> ".1.3.6.1.4.1.9.3.6.11.1.7",
		"cardSlots" => ".1.3.6.1.4.1.9.3.6.12.0"
		);
			
	$cards	= str_replace($lixo, $limpo, snmpwalk($Host, $Community, $oids{"cards"},	$timeout));
	$ctype	= snmpwalk($Host, $Community, $oids{"ctype"},	$timeout);
	$slotnum	= snmpwalk($Host,  $Community, $oids{"slotnum"},	$timeout);
	$cardSlots	= ( snmpget($Host, $Community, $oids{"cardSlots"},	$timeout) ) -1;

	if (count($cards) > 0)
	{
	
		echo "<BR>
		<TABLE border=0	 CLASS='tabela' CELLPADDING='3' ALIGN='center'>
		<TR><TH CLASS='thfont' COLSPAN='3'>Informa&ccedil;&otilde;es de Hardware</TH></TR>
		<TR BGCOLOR='#CDEDCF'><TD Class='topo' ALIGN='center'><B>Descri&ccedil;&atilde;o</B></TD><TD Class='topo' ALIGN='center'><B>Tipo</B></TD><TD Class='topo' ALIGN='center'><B>Slot</B></TD></TR>
		";	
		
		for ($x = 0; $x<count($cards); $x++)
		{
			if ($CardTypes[$ctype[$x]]== "")
				$CdTypes = $CardTypes[1];
			else
				$CdTypes = $CardTypes[$ctype[$x]];	

			print_infohw($cards[$x], $CdTypes, $slotnum[$x]);
		}
	}
	echo "<tr><td COLSPAN='2' Class='base' BGCOLOR='#FFFFCC' ALIGN='center'><b>N&uacute;mero mais elevado de Slot</b></td><td Class='base' BGCOLOR='#FFFFCC' ALIGN='center'><b>$cardSlots</b></td></tr>
		  </table>";
}

function chassis($Host, $Community)
{	
	global $timeout, $y;
	$y=0; ########### ele é global

	$ChassisTypes[1] = "Desconhecido";
	$ChassisTypes[2] = "multibus";
	$ChassisTypes[3] = "agsplus";
	$ChassisTypes[4] = "igs";
	$ChassisTypes[5] = "c2000";
	$ChassisTypes[6] = "c3000";
	$ChassisTypes[7] = "c4000";
	$ChassisTypes[8] = "c7000";
	$ChassisTypes[9] = "cs500";
	$ChassisTypes[10] = "c7010";
	$ChassisTypes[11] = "c2500";
	$ChassisTypes[12] = "c4500";
	$ChassisTypes[13] = "c2102";
	$ChassisTypes[14] = "c2202";
	$ChassisTypes[15] = "c2501";
	$ChassisTypes[16] = "c2502";
	$ChassisTypes[17] = "c2503";
	$ChassisTypes[18] = "c2504";
	$ChassisTypes[19] = "c2505";
	$ChassisTypes[20] = "c2506";
	$ChassisTypes[21] = "c2507";
	$ChassisTypes[22] = "c2508";
	$ChassisTypes[23] = "c2509";
	$ChassisTypes[24] = "c2510";
	$ChassisTypes[25] = "c2511";
	$ChassisTypes[26] = "c2512";
	$ChassisTypes[27] = "c2513";
	$ChassisTypes[28] = "c2514";
	$ChassisTypes[29] = "c2515";
	$ChassisTypes[30] = "c3101";
	$ChassisTypes[31] = "c3102";
	$ChassisTypes[32] = "c3103";
	$ChassisTypes[33] = "c3104";
	$ChassisTypes[34] = "c3202";
	$ChassisTypes[35] = "c3204";
	$ChassisTypes[36] = "accessProRC";
	$ChassisTypes[37] = "accessProEC";
	$ChassisTypes[38] = "c1000";
	$ChassisTypes[39] = "c1003";
	$ChassisTypes[40] = "c1004";
	$ChassisTypes[41] = "c2516";
	$ChassisTypes[42] = "c7507";
	$ChassisTypes[43] = "c7513";
	$ChassisTypes[44] = "c7506";
	$ChassisTypes[45] = "c7505";
	$ChassisTypes[46] = "c1005";
	$ChassisTypes[47] = "c4700";
	$ChassisTypes[48] = "c2517";
	$ChassisTypes[49] = "c2518";
	$ChassisTypes[50] = "c2519";
	$ChassisTypes[51] = "c2520";
	$ChassisTypes[52] = "c2521";
	$ChassisTypes[53] = "c2522";
	$ChassisTypes[54] = "c2523";
	$ChassisTypes[55] = "c2524";
	$ChassisTypes[56] = "c2525";
	$ChassisTypes[57] = "c4700S";
	$ChassisTypes[58] = "c7206";
	$ChassisTypes[59] = "c3640";
	$ChassisTypes[60] = "as5200";
	$ChassisTypes[61] = "c1601";
	$ChassisTypes[62] = "c1602";
	$ChassisTypes[63] = "c1603";
	$ChassisTypes[64] = "c1604";
	$ChassisTypes[65] = "c7204";
	$ChassisTypes[66] = "c3620";
	$ChassisTypes[68] = "wsx3011";
	$ChassisTypes[72] = "c1503";
	$ChassisTypes[73] = "as5300";
	$ChassisTypes[74] = "as2509RJ";
	$ChassisTypes[75] = "as2511RJ";
	$ChassisTypes[77] = "c2501FRADFX";
	$ChassisTypes[78] = "c2501LANFRADFX";
	$ChassisTypes[79] = "c2502LANFRADFX";
	$ChassisTypes[80] = "wsx5302";
	$ChassisTypes[82] = "c12012";
	$ChassisTypes[84] = "c12004";	
	
	$chassisType = ".1.3.6.1.4.1.9.3.6.1.0";
	$chassis	= snmpget($Host, $Community, $chassisType, $timeout);

	if ( $ChassisTypes[$chassis]=="")
		$chassis = $ChassisTypes[1];
	else
		$chassis = $ChassisTypes[$chassis];

	return $chassis;
}
function par()
{
	global $y;

	if( (bcmod($y,2))==0 )
		$classe = "par";
	else		
		$classe = "impar";
	$y++;
	return $classe;
}
	
function print_infohw($cards, $CdTypes, $slotnum)
{
	$classe = par();
	echo "<TR><TD CLASS='$classe'>$cards</TD><TD CLASS='$classe'>$CdTypes</TD><TD CLASS='$classe'  ALIGN='center'>$slotnum</TD></TR>";
}

function print_infogeral ($th, $info)
{
	$classe = par();
	echo "<TR><TD Class='$classe' ><B>$th</B></TD><TD Class='$classe'>$info</TD></TR>";
}

function status($status)
{
	if ($status==1)
		$status = "Up";
	else
		$status = "Down";

	return $status;
}

function uptime($sysUpTime)
{
  sscanf($sysUpTime, "%d:%d:%d:%d.%d",$day,$hour,$minute,$sec,$ticks);
	if ($day    == 1) { $daystr    = "Dia";    } else { $daystr    = "Dias";    }
	if ($hour   == 1) { $hourstr   = "hora";   } else { $hourstr   = "horas";   }
	if ($minute == 1) { $minutestr = "minuto"; } else { $minutestr = "minutos"; }
  	if ($sec    == 1) { $secstr    = "segundos"; } else { $secstr    = "segundos"; }

$sysUpTime = "$day $daystr $hour $hourstr $minute $minutestr e $sec $secstr";

return $sysUpTime;
}

function vertime($x)
{
	$day	= bcdiv($x, 86400);
	$x		= bcmod($x, 86400);
	$hour	= bcdiv($x, 3600);
	$x		= bcmod($x, 3600);
	$minute	= bcdiv($x, 60);
	$sec	= bcmod($x, 60);

	return "<B>$day</B>d <B>$hour</B>h <B>$minute</B>m e <B>$sec</B>s";
}

function segs($SysTime)
{
	$valor = explode(":", $SysTime);

	$dias 	= $valor[0]*86400;
	$horas 	= $valor[1]*3600;
	$min	= $valor[2]*60;
	settype($valor[3], int);	
	$segs	= $valor[3];

	$total = ( ( ($dias + $horas) + $min) + $segs);
	return $total;
}

function syssrv($sv)
{
	if ($sv &  1) {$srv = " Repeater"; }
    if ($sv &  2) {$srv = "$srv Bridge"; }
    if ($sv &  4) {$srv = "$srv Router"; }
    if ($sv &  8) {$srv = "$srv Gateway"; }
    if ($sv & 16) {$srv = "$srv Session"; }
    if ($sv & 32) {$srv = "$srv Terminal"; }
    if ($sv & 64) {$srv = "$srv Application"; }
    if (!$sv)     {$srv = "SNMP services not supported"; }
    return $srv;
}
?>
<BR>
</BODY>
</HTML>
