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

##programa para incluir dados coletados no banco rrd para banco mysql
//	$arg = $_SERVER['argv'];
//	$uf  = strtoupper($arg[1]);
?>

<?php

##################################################
##	INCLUINDO ARQUIVOS DE CONFIGURACAO	##
##################################################
	
	## CONFIGURACAO DE CONEXAO COM MYSQL
	include("config/conexao.php");
	$conexao = conexao();
	mysql_select_db(BANCO,$conexao) or die("erro ao acessar" . BANCO);
##################################################

//if ($uf=="")
//{
//	echo "Faltou informar o Estado!!!\n\n";
//	exit;
//}

//alteracao para flexibilizacao
$query= "select DISTINCT uf  from rede_acesso";
$estados=mysql_query($query,$conexao);
while($line = mysql_fetch_array($estados))
{
	$uf = $line['uf'];
//fim alteracao de flexibilizacao
	
	/////////////////////// CONFIGURACOES /////////////////////////////////////////////////
	$query  = "SELECT dir_rrd_trafego, Path_RRD FROM " . CONFIGURACOES . " WHERE codigo = '1'";
	$result = mysql_query($query,$conexao);
	list ($dir_rrd_trafego, $Path_RRD) = mysql_fetch_array($result,MYSQL_NUM);
	//////////////////////////////////////////////////////////////////////////////////////
	
	
	$consulta = "SELECT cod_int FROM " . TABELA . " WHERE uf='$uf' AND IfStatus='UP'";
	$matrix = mysql_query($consulta,$conexao);

	while ($row = mysql_fetch_array($matrix,MYSQL_NUM))
	{
		$cod_int = &$row[0]; 
		$rrd 	 = $cod_int . ".rrd";
		
		$arq = $dir_rrd_trafego . $rrd;
		if( file_exists($arq))
		{
			$fim = (date('U')) - 120;
			$inicio = $fim - 86400;
			$com = $Path_RRD . "rrdtool fetch $arq AVERAGE --start $inicio --end $fim | sed -e \"s/ds[01]//g\" | sed \"s/nan/0/g\" | tr \":\" \" \" | tr -s \" \" | sed -e \"s/ \$//\" | grep -v \"^\$\"";
			$linhas = explode("\n", shell_exec($com));

			$cod_int = strtr($cod_int, ".", "_");	
	
			for ($i=0; $i < count($linhas); $i++)
			{
				$campos = explode(" ", $linhas[$i]);
				$data 		= strftime("%Y-%m-%d %H:%M:%S",$campos[0]);
				$vol_in 	= calcula($campos[1]);
				$vol_out = calcula($campos[2]);
		
				if ($data!="1970-01-01 00:00:00" && $data != "1969-12-31 21:00:00")
				{	
					$inclusao = "INSERT INTO $cod_int(data_hora, volume_in, volume_out) VALUES('$data','$vol_in','$vol_out')";
					echo $inclusao;
	    				if( !mysql_query($inclusao,$conexao) )
			    		{	
						$cria_tabela = "CREATE TABLE $cod_int (data_hora datetime PRIMARY KEY,	volume_in int, volume_out int, delay smallint, st_delay tinyint)";
						//echo "$cria_tabela\n";
						if ( !mysql_query($cria_tabela,$conexao))
						{	
							echo "erro na criacao da tabela $cod_int\n";
						}
						mysql_query($inclusao,$conexao);
					}## FIM DO IF INCLUSAO
				}## FIM DO IF DATA
			}## FIM DO FOR LINHAS
		}##fim do IF SE ARQUIVO EXISTE
	}## FIM DO WHILE MYSQL
}

function calcula($valor) 
{
		$valor = strtr($valor, ",", ".");	
		settype ($valor, "double");
		$valor = round($valor,1);
	return $valor;
}
mysql_free_result;
mysql_close();
?>
