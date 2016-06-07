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

##################################################
##	INCLUINDO ARQUIVOS DE CONFIGURACAO	##
##################################################

	## CONFIGURACAO DE CONEXAO COM MYSQL
	include("config/conexao.php");
	$conexao = conexao();
#########################################

	mysql_select_db(BANCO);

	$query  = "SELECT dir_rrd_trafego, Path_RRD, dir_logs FROM " . CONFIGURACOES . " WHERE codigo = '1'";
	$result = mysql_query($query);
	list ($dir_rrd_trafego, $Path_RRD, $dir_logs) = mysql_fetch_array($result,MYSQL_NUM);
	

$query = "select DISTINCT uf  from rede_acesso";
$estados=mysql_query($query,$conexao);
while($line = mysql_fetch_array($estados))
{	
	$uf = $uf=$line['uf'];

	$consulta = "SELECT cod_int, tipo, porta, cir_in, cir_out, history, serial FROM " . TABELA . " WHERE uf='$uf' AND IfStatus='UP' AND gerencia != 'Entidades Externas'";
	$matrix = mysql_query($consulta);

while ( list($cod_int, $tipo, $porta, $cir_in, $cir_out, $history, $serial) = mysql_fetch_array($matrix,MYSQL_NUM))
{
	$media_in = 0;
	$media_out = 0;
	$qtd = 0;

	$rrd = $cod_int . ".rrd";
	if( file_exists("$dir_rrd_trafego$rrd"))
	{
		$fim = (date('U')) - 60;
		$inicio = $fim - 720;
		$com = $Path_RRD . "rrdtool fetch $dir_rrd_trafego$rrd AVERAGE --start $inicio --end $fim | sed -e \"s/ds[01]//g\" | sed \"s/nan/0/g\" | tr \":\" \" \" | tr -s \" \" | sed -e \"s/ \$//\" | grep -v \"^\$\" |tail -10";
		$linhas = explode("\n", shell_exec($com));
		for ($i=0; $i < count($linhas) - 1; $i++)
		{
			$campos = explode(" ", $linhas[$i]);
				$data 		= strftime("%Y-%m-%d %H:%M:%S",$campos[0]);
				$vol_in 	= calcula($campos[1]);
			    $vol_out 	= calcula($campos[2]);	
	
				if ($data!="1970-01-01 00:00:00" && $data!="1969-12-31 21:00:00")
				{	
					$media_in 	+= $vol_in;
					$media_out 	+= $vol_out;	
					$qtd++;
				}
		}## FIM DO FOr

		
		$history 	= strtoupper($history);	
		$hist_novo 	= "N";

		if ($qtd !=0)	
		{
			$media_in 	= round( ($media_in*0.008)/$qtd, 1);
			$media_out 	= round( ($media_out*0.008)/$qtd, 1);
			
			if (strtoupper($tipo) == "C" && (!eregi("ETH",$serial)) )
			{	# TRATA COMO CIRCUITO	
				$cir_in		= round( ($cir_in*1.2), 1);
				$cir_out 	= round(( $cir_out*1.2), 1);
			
				if ($media_in > $cir_in || $media_out > $cir_out)
				{	
						if ( $history!="A1" && $history!="A2")
							$hist_novo = "A1";
						elseif  ( $history=="A1" || $history=="A2")
							$hist_novo = "A2";
				}
				elseif  ($media_out == 0.0 || $media_in == 0.0 ) 
				{
						if ( $history!="Z1" && $history!="Z2" )
							$hist_novo = "Z1";
						elseif  ( $history=="Z1" || $history=="Z2")
							$hist_novo = "Z2";
				}
				
			} # Fim do Tratamento para Circuito
			else
			{ # INICIO DO TRATAMENTO COMO CONCENTRADORA
				$porta	= round( ($porta*0.85), 1);

				if ($media_in > $porta || $media_out > $porta)
				{	
						if ( $history!="A1" && $history!="A2")
							$hist_novo = "A1";
						elseif  ( $history=="A1" || $history=="A2")
							$hist_novo = "A2";
				}
				elseif  ($media_out == 0.0 || $media_in == 0.0 ) 
				{
						if ( $history!="Z1" && $history!="Z2" )
							$hist_novo = "Z1";
						elseif  ( $history=="Z1" || $history=="Z2")
							$hist_novo = "Z2";
				}		
			}// FIM DO TRATAMENTO DE PORTA
		} // FIM DO IF DE qtd!=0
		else
		{
			$arq = strftime("%d-%m-%Y",$fim);
			$fp = fopen("$dir_logs$arq.log", 'a');
			$info = "$rrd\n";
			fwrite($fp, $info);
		}
		
		$update = "UPDATE " . TABELA . " SET history='$hist_novo' WHERE cod_int='$cod_int'";
		mysql_query($update);

	
	} // FIM DO IF QUE TESTA SE ARQ EXISTE			
}/////////// FIM DO ENQUANTO

sleep (7);
}///////////////////////////////////////////////////////////////   FIM DO FOR


function calcula($valor)
{
		$valor = strtr($valor, ",", ".");	
		settype ($valor, 'double');
		$valor = round($valor,1);
	return $valor;
}

mysql_close() or die ('nao desconectou');
?>
