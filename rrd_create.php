<?php
/*
    Este arquivo é parte do programa COCAR



    COCAR é um software livre; você pode redistribui-lo e/ou 

    modifica-lo dentro dos termos da Licença Pública Geral GNU como 

    publicada pela Fundação do Software Livre (FSF); na versão 2 da 

    Licença.



    Este programa é distribuido na esperança que possa ser  util, 

    mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUAÇÂO a qualquer

    MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a

    Licença Pública Geral GNU para maiores detalhes (GPL2.txt).



    Você deve ter recebido uma cópia da Licença Pública Geral GNU

    junto com este programa, se não, escreva para a Fundação do Software

    Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

	$arg = $_SERVER['argv'];
?>

<?php

##################################################
##	INCLUINDO ARQUIVOS DE CONFIGURACAO	##
##################################################

	## CONFIGURACAO DE CONEXAO COM MYSQL
	include("config/conexao.php");
	$conexao = conexao();

##################################################

	mysql_select_db(BANCO);

	/////////////////////// CONFIGURACOES /////////////////////////////////////////////////
	$query  = "SELECT dir_rrd_novos, Path_RRD FROM " . CONFIGURACOES. " WHERE codigo = '1'";
	$result = mysql_query($query);
	list ($dir_rrd_novos, $Path_RRD) = mysql_fetch_array($result,MYSQL_NUM);
	//////////////////////////////////////////////////////////////////////////////////////

if ($arg[1] != "")
	$consulta = "SELECT cod_int FROM " . TABELA . " WHERE uf='$arg[1]' AND IfStatus='UP'";
else  
	$consulta = "SELECT cod_int FROM " . TABELA . " WHERE IfStatus='UP'";

$matriz = mysql_query($consulta);

while ($codigo = mysql_fetch_array($matriz,MYSQL_NUM))
{		
	$arq_rrd = $dir_rrd_novos . $codigo[0] . ".rrd";
	
	if (!file_exists($arq_rrd))
	{
		$com = 
				$Path_RRD . "rrdtool create $arq_rrd " . 
				"--step 60 DS:ds0:COUNTER:120:0:125000000 " . 
				"DS:ds1:COUNTER:120:0:125000000 " . 
				"RRA:AVERAGE:0.5:1:4320 " . 
				"RRA:AVERAGE:0.5:5:2016	" . 
				"RRA:AVERAGE:0.5:20:2232 " . 
				"RRA:AVERAGE:0.5:90:2976 " . 
				"RRA:AVERAGE:0.5:360:1460 " . 
				"RRA:AVERAGE:0.5:1440:730 " . 
				"RRA:MAX:0.5:1:4320 " . 
				"RRA:MAX:0.5:5:2016 " . 
				"RRA:MAX:0.5:20:2232 " .
				"RRA:MAX:0.5:90:2976 " .
				"RRA:MAX:0.5:360:1460 " . 
				"RRA:MAX:0.5:1440:730";
		
		shell_exec($com);
	}
}
mysql_free_result;
mysql_close();
?>
