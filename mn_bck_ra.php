<?php
/*
    Este arquivo � parte do programa COCAR



    COCAR � um software livre; voc� pode redistribui-lo e/ou 

    modifica-lo dentro dos termos da Licen�a P�blica Geral GNU como 

    publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da 

    Licen�a.



    Este programa � distribuido na esperan�a que possa ser  util, 

    mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUA��O a qualquer

    MERCADO ou APLICA��O EM PARTICULAR. Veja a

    Licen�a P�blica Geral GNU para maiores detalhes (GPL2.txt).



    Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU

    junto com este programa, se n�o, escreva para a Funda��o do Software

    Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/
	$uf		= $_GET["uf"];
	$ger	= $_GET["ger"];
	$serial	= $_GET["serial"];
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
body{scrollbar-face-color: white; scrollbar-shadow-color: grey; scrollbar-highlight-color: white; scrollbar-3dlight-color: grey; scrollbar-darkshadow-color: white;scrollbar-track-color: #ffffff; scrollbar-arrow-color: #c0c0c0;}
A:link {font-family: Arial, Verdana; font-size: 8pt; FONT-WEIGHT: bold; COLOR: #000000; TEXT-DECORATION: none; CURSOR: hand;}
A:visited {font-family: Arial, Verdana; font-size: 8pt; FONT-WEIGHT: bold; COLOR: #1A0300; TEXT-DECORATION: none; CURSOR: hand;}
A:active {font-family: Arial, Verdana; font-size: 8pt; FONT-WEIGHT: bold; COLOR: #0000FF; TEXT-DECORATION: none; CURSOR: hand;}
A:selected {font-family: Arial, Verdana; font-size: 8pt; FONT-WEIGHT: bold; COLOR: #0000FF; TEXT-DECORATION: none; CURSOR: hand;}
A:hover {font-family: Arial, Verdana; font-size: 8pt; FONT-WEIGHT: bold; TEXT-DECORATION: none; color:#F58735; CURSOR: hand;}
.caixa{BORDER-COLOR: #F58735; BORDER-STYLE: solid; BORDER-WIDTH: 1px; FONT-SIZE: 10px; FONT-FAMILY: Verdana, Helvetica, sans-serif; width:102px;}
.laterais {border-left: #F58735 1px solid; border-right: #F58735 1px solid; border-bottom: #F58735 1px solid; FONT-FAMILY: Arial, Verdana, Helvetica, sans-serif; FONT-SIZE: 8pt; FONT-WEIGHT: bold}
.final {border-bottom: #F58735 1px solid;}
.tht {FONT-FAMILY: Arial, Verdana, Helvetica, sans-serif; FONT-SIZE: 9pt; FONT-WEIGHT: bold; TEXT-VALGIN: middle;}
</style>

<!-- #######################   VALIDA FORM DE PESQUISA  ############################## -->
<SCRIPT LANGUAGE="JavaScript">
function validaForm(){
	d = document.cadastro;
	parte = d.objeto.value.length;
	if (parte < 3)
	{
		alert ("O nome para procura,\ndeve possuir no m�nimo 3 letras!");
        d.objeto.focus();
        return false;
	}
    return true;
}
</SCRIPT>
</HEAD>
	  
<body background="imagens/fundoko.gif" LEFTMARGIN="8" RIGHTMARGIN="0" TOPMARGIN="6" BOTTOMMARGIN="0">

<?php
error_reporting(0);

#####################################
	include("config/conexao.php");
	$conexao = conexao();
#####################################
	$tabela = "bckbone";
	$banco = "rede_acesso";
	mysql_select_db($banco);

	/////////////////////// CONFIGURACOES /////////////////////////////////////////////////
	$query  = "SELECT IpServerMonitora FROM ". CONFIGURACOES . " WHERE codigo = '1'";
	$result = mysql_query($query);
	list ($aux) = mysql_fetch_array($result,MYSQL_NUM);
	
	define(IpServerMonitora, $aux);
	define(LinkGraph, ("rrd_graph.php?log=") );
#	define(LinkGraph, ("http://" . IpServerMonitora . "/php/teste/graph.php?log=") );

	//////////////////////////////////////////////////////////////////////////////////////	

#####################################
	$home = "index.html";
	$ra = "mn_aps.php";
#####################################
	
if($uf!="" && $ger=="")
{
	gerencias($uf);
	voltar_principal($home, $ra);
}
elseif($uf!="" && $ger!="")
{
	if ($serial!="")
	{
		gerencias($uf);
		concentradora($serial, $uf);
		voltar_conc($uf);
	}
	else
	{
		gerencias($uf);
		entidades($ger, $uf);
		voltar_ger($ger, $uf);
	}
}

###########################################################
#####		FUNCOES PARA GERACAO DOS MENUS
###########################################################
function fcth($texto)
{
	echo "
	<table width='150'  border='0' cellspacing='0' cellpadding='0' style='margin-top: 5px'>
	  <tr>
	    <td width='1' align='right'><img border='0' src='imagens/boxverde_e.gif' width='5' height='25'></td>
	    <td background='imagens/boxverde_m.gif'  align='center' valign='bottom' CLASS='tht'><b>$texto</b></td>
	    <td width='5'><img src='imagens/boxverde_d.gif' width='5' height='25'></td>
	  </tr>
	</table>
	";
} # FIM DA FUNCAO CABECALHO

function voltar_principal($home, $ra)
{
	global $uf;
echo "
<table width='140' border='0' cellspacing='0' cellpadding='0'>
	<TR><TD>
		<BR><a href='$ra?uf=$uf' TARGET='_self'><img SRC='imagens/bt_voltar.gif' BORDER='0'></a>
	</TD></TR>	
</table>";
} # FIM DA FUNCAO VOLTAR

function voltar_ger($ger, $uf)
{	
echo "
<table width='140' border='0' cellspacing='0' cellpadding='0'>
	<TR><TD>
		<BR><a href='" . $_SERVER['PHP_SELF'] . "?uf=$uf' TARGET='_self'><img SRC='imagens/bt_voltar.gif' BORDER='0'></a>
	</TD></TR>	
</table>";
} # FIM DA FUNCAO VOLTAR

function voltar_conc($uf)
{	
echo "
<table width='140' border='0' cellspacing='0' cellpadding='0'>
	<TR><TD>
		<BR><a href='" . $_SERVER['PHP_SELF'] . "?ger=Concentradoras&uf=$uf' TARGET='_self'><img SRC='imagens/bt_voltar.gif' BORDER='0'></a>
	</TD></TR>	
</table>";
} # FIM DA FUNCAO VOLTAR


function gerencias($uf)
{
	global $conexao, $banco, $tabela;
	
	$consulta = "SELECT DISTINCT gerencia FROM $tabela WHERE uf='$uf' AND gerencia NOT LIKE 'LAN%' ORDER BY gerencia";
	$resultado = mysql_query($consulta);

	$nome_uf = nome_uf($uf);
	fcth("$nome_uf");
	echo "<table width='150'  border='0' cellspacing='0' cellpadding='4' CLASS='laterais' bgcolor='#F3FAE9'>
		<TR><TD>
      	Backbone<BR>
		=======================<BR>
		";	

	while( list($ger) = mysql_fetch_array($resultado, MYSQL_NUM))
	{   
		echo "<A HREF='" . $_SERVER['PHP_SELF'] . "?ger=$ger&uf=$uf'>$ger</A><BR>";	
	}

	echo "</TD></TR></table>";
	
} ### FIM DA FUN�AO GERENCIAS


function entidades($ger, $uf)
{
	global $conexao, $banco, $tabela;

	$consulta = "SELECT cod_int, nome, orgao, serial, tipo FROM $tabela WHERE gerencia='$ger' AND uf='$uf' AND IfStatus='UP' ORDER BY nome";
	$resultado = mysql_query($consulta);

	$ger = str_replace("GEx ", "", $ger);
	fcth("$ger/$uf");

	echo "
		<table width='150'  border='0' cellspacing='0' cellpadding='4' CLASS='laterais' bgcolor='#F3FAE9'>
		<TR><TD>
		";
		 	
	while( list($cod_int, $nome, $orgao, $serial, $tipo) = mysql_fetch_array($resultado, MYSQL_NUM))	
	{
		$serial_2 = serial($serial);
		$link 	= LinkGraph . $cod_int;

		if ( (strtoupper($tipo)) == "C")
			echo "<A HREF='$link' TITLE='$orgao - $serial_2' TARGET='principal'>$nome</A><BR>";
		else
			echo "<A HREF='$link' TITLE='$orgao - $serial_2' ONCLICK=\"document.location.href='" . $_SERVER['PHP_SELF'] . "?ger=$ger&uf=$uf&serial=$serial'\" TARGET='principal'>$nome</A><BR>";
   	}		

	echo "</TD></TR></table>";
	
} ### FIM DA FUN�AO ENTIDADES

function procura($objeto)
{
	global $conexao, $banco, $tabela, $home;
	$objeto = strtoupper($objeto);
	if($objeto=="APS" || $objeto=="GEX" || $objeto=="APS " || $objeto=="GEX ")
	{
		echo "<BR><BR>
			<table width='150'  border='0' cellspacing='2' cellpadding='4'>
			<TR><TD ALIGN='center'><IMG SRC='imagens/alerta1.jpg'></TD></TR>
			<TR><TD ALIGN='center'><FONT SIZE='+1' COLOR='#000000'>N&atilde;o digite o tipo, e sim o nome da <FONT COLOR='#FF0000'>Unidade</font> Desejada.</FONT></TD></TR>
			</table>
		";		
		voltar_principal($home);
		exit;
	}
	
	$consulta = "SELECT cod_int, nome, orgao, gerencia, uf, serial FROM $tabela WHERE (nome LIKE '%$objeto%' OR orgao LIKE '%$objeto%' ) AND IfStatus='UP' ORDER BY uf";
	$resultado = mysql_query($consulta);

	while( list($cod_int, $name, $orgao, $GEx, $uf, $serial ) = mysql_fetch_array($resultado, MYSQL_NUM))	
	{
		$GEx = str_replace("GEx ", "", $GEx);
	//	$nome = str_replace("APS ", "", $name);
		$link 	= LinkGraph . $cod_int;
	
		$nome = $name;
		fcth("$GEx/$uf");	
		echo "<table width='150'  border='0' cellspacing='0' cellpadding='4' CLASS='laterais' bgcolor='#F3FAE9'>
				<TR><TD>
					<A HREF='$link'  TITLE='$orgao - $serial' TARGET='principal'>$name</A><BR>
				</TD></TR>
			</table>";
   	}		
	if ($nome == "")
	{
		echo "<BR><BR>
			<table width='150'  border='0' cellspacing='2' cellpadding='4'>
			<TR><TD ALIGN='center'><IMG SRC='imagens/alerta1.jpg'></TD></TR>
			<TR><TD ALIGN='center'><FONT SIZE='+1' COLOR='#333333'>Unidade N&atilde;o Encontrada!!!</FONT></TD></TR>
			</table>
		";
	}	
} // FIM DA FUN��O PROCURA

function concentradora($serial_consulta, $uf)
{
	global $conexao, $banco, $tabela;

	$consulta = "SELECT cod_int, nome, orgao, serial FROM $tabela WHERE (serial LIKE '$serial_consulta%') AND uf='$uf' AND IfStatus='UP' ORDER BY nome";
	$resultado = mysql_query($consulta);

	fcth("$serial_consulta - $uf");

	echo "
		<table width='150'  border='0' cellspacing='0' cellpadding='4' CLASS='laterais' bgcolor='#F3FAE9'>
		<TR><TD>
		";
			 
	while( list($cod_int, $nome, $orgao, $serial) = mysql_fetch_array($resultado, MYSQL_NUM))	
	{
		$link 	= LinkGraph . $cod_int;
		echo "<A HREF='$link' TITLE='$orgao -  $serial' TARGET='principal'>$nome</A><BR>";
   	}		

	echo "</TD></TR></table>";
	
} ### FIM DA FUN�AO CONCENTRADORA

function serial($serial)
{
	return	str_replace("_", "/", $serial);
} # FIM DA FUNCAO SERIAL

function nome_uf($uf)
{
	$uf = strtoupper($uf);
	switch($uf)
	{
	case 'AC': $nome_uf = "Acre"; 				break;
	case 'AL': $nome_uf = "Alagoas"; 			break;
	case 'AM': $nome_uf = "Amazonas"; 			break;
	case 'AP': $nome_uf = "Amapa"; 				break;
	case 'BA': $nome_uf = "Bahia"; 				break;
	case 'CE': $nome_uf = "Ceara"; 				break;
	case 'DF': $nome_uf = "Distrito Federal"; 	break;
	case 'ES': $nome_uf = "Esp�rito Santo"; 	break;
	case 'GO': $nome_uf = "Goi�s"; 				break;
	case 'MA': $nome_uf = "Maranh�o"; 			break;
	case 'MG': $nome_uf = "Minas Gerais"; 		break;
	case 'MS': $nome_uf = "Mato Grosso Sul"; 	break;
	case 'MT': $nome_uf = "Mato Grosso";		break;
	case 'PA': $nome_uf = "Par�"; 				break;
	case 'PB': $nome_uf = "Para�ba"; 			break;
	case 'PE': $nome_uf = "Pernambuco"; 		break;
	case 'PI': $nome_uf = "Piau�";				break;
	case 'PR': $nome_uf = "Parana";				break;
	case 'RJ': $nome_uf = "Rio de Janeiro";		break;
	case 'RN': $nome_uf = "Rio Grande Norte";	break;
	case 'RO': $nome_uf = "Rond�nia"; 			break;
	case 'RR': $nome_uf = "Ror�ima"; 			break;
	case 'RS': $nome_uf = "Rio Grande do Sul"; 	break;
	case 'SC': $nome_uf = "Santa Catarina"; 	break;
	case 'SE': $nome_uf = "Sergipe"; 			break;
	case 'SP': $nome_uf = "S�o Paulo"; 			break;
	case 'TO': $nome_uf = "Tocantins"; 			break;
	}
	return $nome_uf;
}

mysql_free_result;
mysql_close() or die ('nao desconectou');
?>
</BODY>
</HTML>
