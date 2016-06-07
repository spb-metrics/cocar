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

//	$arg = $_SERVER['argv'];
//	$uf  = strtoupper($arg[1]);
?>

<?php
/*

 Coleta o Reliability e armazena
 em tabelas diferentes. ---> uma para cada interface

*/


//	if($uf=="")
//	{
//			echo "FALTOU INFORMAR O ESTADO!!!\n\n";
//			exit;
//	}


##################################################
##      INCLUINDO ARQUIVOS DE CONFIGURACAO      ##
##################################################

        ## CONFIGURACAO DE CONEXAO COM MYSQL
        include("config/conexao.php");
        $conexao = conexao();
		mysql_select_db(BANCO) or die ("ERRO: ".mysql_error()."\n");
##################################################

//alteracao para flexibilizacao
$query = "select DISTINCT uf  from rede_acesso";
$estados=mysql_query($query,$conexao);
while($line = mysql_fetch_array($estados))
{
        $uf=$line['uf'];
//fim alteracao de flexibilizacao

///////////////////////////////////////////////////////////////////////////////////////////////
	$query  = "SELECT dir_rrd_rly, Path_RRD FROM " . CONFIGURACOES . " WHERE codigo = '1'";
	$result = mysql_query($query);
	list ($dir_rrd_rly, $Path_RRD) = mysql_fetch_array($result,MYSQL_NUM);
///////////////////////////////////////////////////////////////////////////////////////////////
	
	## ATULIAZAR TODOS COMO OK
 	$update= "UPDATE " . TABELA . " SET rly='0'  WHERE uf='$uf' AND IfOperStatus!='UP' ";
	mysql_query($update);

 	$update= "UPDATE " . TABELA . " SET rly='-1' WHERE uf='$uf' AND (serial_dest='' OR num_int_dest='0') ";
	mysql_query($update);


	$sql = "
		SELECT
			cod_int,
			host_dest,
			num_int_dest,
			community_dest

		FROM " . TABELA . "
		  WHERE
		  	uf='$uf'
		  	AND IfStatus='UP'
			AND IfOperStatus = 'UP'
			AND num_int_dest!='0'
			AND serial_dest!=''
			AND host_dest!=''
			AND community_dest !=''

		 ORDER BY num_int";

	$consulta = mysql_query($sql) or die ("ERRO: ".mysql_error()."\n");

		while ( list ($cod_int, $host_dest, $num_int_dest, $community_dest) = mysql_fetch_array($consulta,MYSQL_NUM))
		{
				$com = "snmpget -Ov -t 1 -r 1 -c $community_dest -v 1 $host_dest .1.3.6.1.4.1.9.2.2.1.1.22.$num_int_dest 2> /dev/null";
				$rly = snmp(shell_exec($com));

				if(!$rly)
				$rly=0;

                        $tabela_rly = $cod_int . "_rly";

					$data= ((int)(date('U')/600))*600;
					$inclusao = "INSERT INTO $tabela_rly (data_hora, rly) VALUES('$data','$rly')";

					if( !mysql_query($inclusao) )
    					{
							cria_tabela($tabela_rly);
							mysql_query($inclusao);
					}
			
					// ATUALIZAR RD_ACESSO
					$update = "UPDATE " . TABELA . " SET rly='$rly' WHERE cod_int='$cod_int'";
					mysql_query($update);


############################################ INSERT RRD
					$arq_rrd = $dir_rrd_rly . $cod_int . "_rly.rrd";
	
					if (!file_exists($arq_rrd))
						cria_rrd($arq_rrd);

					### UPDATE RRD
					update_rrd($arq_rrd, $data, $rly);
##########################################################


		}
}


function snmp($resp)
{
	$resp = strstr($resp, ':');
	$resp = str_replace(":", "", $resp);
	return (trim($resp));
}	

function update_rrd($arq_rrd, $data, $rly)
{
	global $Path_RRD;

	$com = $Path_RRD . "rrdtool update $arq_rrd $data:$rly";
	shell_exec($com);
}

function cria_rrd($arq_rrd)
{
	global $Path_RRD;
	$com	= $Path_RRD . "rrdtool create " . $arq_rrd . " --step 600 " . 
			  "DS:rly:GAUGE:1200:0:256 " . 
			  "RRA:AVERAGE:0.5:1:480 " . 
			  "RRA:AVERAGE:0.5:2:510 " . 
			  "RRA:AVERAGE:0.5:9:500 " . 
			  "RRA:AVERAGE:0.5:36:500 " . 
			  "RRA:AVERAGE:0.5:144:370 " . 
			  "RRA:MIN:0.5:1:480 " . 
			  "RRA:MIN:0.5:2:510 " . 
			  "RRA:MIN:0.5:9:500 " . 
			  "RRA:MIN:0.5:36:500 " . 
			  "RRA:MIN:0.5:144:370";

	shell_exec($com);

}

function cria_tabela($tabela_rly)
{
	$cria_tabela=
		"
		CREATE TABLE `$tabela_rly` (
		  `data_hora` bigint(20) unsigned NOT NULL default '0',
		  `rly` smallint(6) default '',
		  PRIMARY KEY  (`data_hora`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1
		";
	mysql_query($cria_tabela);
}

mysql_close();

?>
