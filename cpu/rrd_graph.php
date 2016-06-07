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

// Data no passado
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
// Sempre modificado
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
// HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
// HTTP/1.0
header("Pragma: no-cache");
?>

<?php
	error_reporting(0);
	$periodo = $_GET["periodo"];
	$cod_int = $_GET["log"];

	if($cod_int==NULL)
	{
		echo "<H1>Erro: Falta Informar a Interface.</H1>";
		exit;
	}

	//////////////////////////////////////////////////
	
	$Path_RRD = "/usr/bin/";
	$dir_fig = "/var/www/html/graficos/";
	$dir_rrd = "/var/www/rrd/cpu/";

?>

<HTML>

<HEAD>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<script language="javascript">
function mudapagina(combo)
{
	var endereco = combo.value;
	if (endereco != "#")
	{
		novapagina = window.location=endereco;
	}
}
</script>

<style>
body
	{
		scrollbar-face-color: white; 
		scrollbar-shadow-color: grey; 
		scrollbar-highlight-color: white; 
		scrollbar-3dlight-color: grey; 
		scrollbar-darkshadow-color: white;
		scrollbar-track-color: #ffffff; 
		scrollbar-arrow-color: #c0c0c0;
	}

.tht
	{
		font-size: 10pt; 
		font-family: Verdana, Arial; 
		font-weight: bold; 
		text-align: left; 
		text-valign: middle
	}

.tdt
	{
		font-size: 10pt; 
		font-family: Verdana, Arial; 
		font-weight: bold; 
		text-align: center; 
		text-valign: middle
	}
</style>
<TITLE>DIPRO :: Grafico do MRTG</TITLE>
</HEAD>

<BODY BACKGROUND="../imagens/fundoko.gif">

<?php

##################################################
##	INCLUINDO ARQUIVOS DE CONFIGURACAO	##
##################################################0

	## CONFIGURACAO DE CONEXAO COM MYSQL
	include("../config/conexao.php");
	$conexao = conexao();

	$banco='rede_acesso';
	$tabela='cpu';

	@mysql_select_db($banco) or die ('Nao conectou');
#########################################


	$consulta1 = "SELECT Descricao, IpHost, NomeHost, Origem, ModeloHost " .
			 "FROM $tabela " .
                   "WHERE CodigoHost='$cod_int'";
	$matriz1 = mysql_query($consulta1) or die ("Falha na Consulta 1\n");

	list($Descricao, $IpHost, $NomeHost, $Origem, $ModeloHost) = mysql_fetch_row($matriz1);

	$arq_rrd= $dir_rrd . $cod_int . '_cpu.rrd';
		
	echo "&nbsp;&nbsp;&nbsp;<A HREF='telnet://$IpHost' TITLE='TELNET'><IMG SRC='../imagens/telnet.gif' ALIGN='middle' BORDER='0'></A>
	&nbsp;&nbsp; <FONT FACE='Verdana' SIZE='+1'>$Origem: $NomeHost ($IpHost)</FONT>
	<DIV ALIGN='center'>
	<table  width='100%'  border='0' cellspacing='0' cellpadding='3'>
			<tr>
	    		<td CLASS='tdt' BGCOLOR='#FFE0C1' WIDTH='80%'><B>Percentual de Utilizacao da CPU: $Descricao - Modelo: $ModeloHost</B></td>
				<td CLASS='tdt' BGCOLOR='#FFCC99'>
		<select name='pages' size='1' onChange='mudapagina(this);' CLASS='mn_box'>
			<option value='#'>Periodo
						<option value='" . $_SERVER['PHP_SELF'] . "?log=$cod_int&periodo=6hours'>6 horas
						<option value='" . $_SERVER['PHP_SELF'] . "?log=$cod_int&periodo=8hours'>8 horas
						<option value='" . $_SERVER['PHP_SELF'] . "?log=$cod_int&periodo=1day'>1 dia
						<option value='" . $_SERVER['PHP_SELF'] . "?log=$cod_int&periodo=40hours'>40 horas
						<option value='" . $_SERVER['PHP_SELF'] . "?log=$cod_int&periodo=160hours'>1 semana
						<option value='" . $_SERVER['PHP_SELF'] . "?log=$cod_int&periodo=720hours'>1 mes
						<option value='" . $_SERVER['PHP_SELF'] . "?log=$cod_int&periodo=4months'>4 meses
						<option value='" . $_SERVER['PHP_SELF'] . "?log=$cod_int&periodo=1year'>1 ano
		</select>

		 </td>
	       </tr>
	<TR>
	</TABLE>";


	if(!file_exists($arq_rrd))
	{
		echo 	"<h3>Equipamento nao monitorado.</h3>
			 </DIV>";

		exit;
	}

	if ($periodo==NULL)
		$periodo = "6hours";

		switch ($periodo)
		{
			case '6hours':	$escala = "6 Horas";	$step=60;	$media="1 min";	break;
			case '8hours':	$escala = "8 Horas";	$step=60;	$media="1 min";	break;
			case '1day': 	$escala = "1 Dia";	$step=300;	$media="5 min";	break;
			case '40hours':	$escala = "40 Horas";	$step=300;	$media="5 min";	break;
			case '160hours':	$escala = "1 Semana";	$step=1200;	$media="20 min";	break;
			case '720hours':	$escala = "1 Mes";	$step=5400;	$media="90 min";	break;
			case '4months': 	$escala = "4 Meses";	$step=21600;$media="360 min";	break;
			case '1year':	$escala = "1 Ano";	$step=86400;$media="1440 min";break;
		}


		$figura =  $cod_int . "_$periodo.png";

	          $com =  $Path_RRD . "rrdtool graph  $dir_fig$figura " .
                	" --start -$periodo --end now --step $step " .
           		" --title='Utilizacao de CPU: $Descricao - $escala ($media)' " .
           		"--vertical-label 'Percentual' " .
            	"--width 485 --height 162 " .
                	"DEF:UserCpu=$arq_rrd:cpu:AVERAGE " .
            	"CDEF:cpu=UserCpu " .
              	"COMMENT:'        ' ".
                	"COMMENT:'        ' ".
                	"COMMENT:'  ' ".
            	"AREA:cpu#0000FF:'Percentual' " .
            	"COMMENT:'\\n' ".
                	"COMMENT:'        ' ".
                	"GPRINT:cpu:MAX:'Maximo\\:%17.1lf %%' ".
                	"COMMENT:'\\n' ".
                	"COMMENT:'        ' ".
                	"GPRINT:cpu:MIN:'Minimo\\:%17.1lf %%' ".
                	"COMMENT:'\\n' ".
                	"COMMENT:'        ' ".
                	"GPRINT:cpu:AVERAGE:'Media\\:%18.1lf %%' ".
                	"COMMENT:'\\n' ".
                	"COMMENT:'        ' ".
                	"GPRINT:cpu:LAST:'Ultima\\:%17.1lf %%' "
			;

	shell_exec($com);
	################################

	echo "<BR>
		<img src='http://" . $_SERVER['SERVER_NAME'] . "/graficos/$figura'>
	</DIV>
	";

?>
</BODY>
</HTML>
