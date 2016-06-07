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

//variáveis
$ano = "2007";

include ("../config/conexao.php");
$conexao = conexao();
mysql_select_db("rede_acesso", $conexao);

//**************************************************************************//
//                          INICIO DO PROGRAMA                              //
//**************************************************************************//
$query= "select DISTINCT uf  from rede_acesso";
$estados=mysql_query($query,$conexao);
while($line = mysql_fetch_array($estados))
{	
	$uf = $line['uf'];
	$consulta = "SELECT cod_int,orgao, tecnologia FROM rede_acesso WHERE uf='" . $line['uf'] . "'";
	$resultado = mysql_query($consulta, $conexao);
	while ($row = mysql_fetch_array($resultado,MYSQL_NUM)) {
		$arquivo = $row[0];
		for ($mes = 1; $mes <= 8; $mes++) {
			//Período a ser gerado na tabela
			$uso_20_50_in = 0;
			$uso_50_85_in = 0;
			$uso_m_85_in  = 0;
			$uso_20_50_out= 0;
			$uso_50_85_out= 0;
			$uso_m_85_out = 0;
			$ocorrencias  = 0;
			$volume_mensal_out = 0;
			$volume_mensal_in  = 0;	
			unset($c_out_rec);
			unset($c_in_rec);
			if($mes<10) $mes = "0".$mes;		
			$mes_ano = $ano."-".$mes."-01";
			//apanha dados na tabela performance diaria
			$consulta = "SELECT cir_in,cir_out,cir_in_rec,cir_out_rec,volume_in,volume_out,
						7_19_criticidade_in,7_19_criticidade_out
						FROM performance_diaria WHERE cod_int='$arquivo'and dia LIKE '$ano-$mes-%' order by dia";
			$resultado2 = mysql_query($consulta, $conexao);	
			while (list ($cir_in, $cir_out, $cir_in_rec,  $cir_out_rec, $volume_in, $volume_out,
						$criticidade_in,$criticidade_out) = mysql_fetch_row($resultado2)) 
			{
				//volume mensal
				$volume_mensal_out = $volume_mensal_out + $volume_out;
				$volume_mensal_in  = $volume_mensal_in  + $volume_in;	
				//cir mensal recomendado - separa valores
				$c_out_rec[$cir_out_rec] = $c_out_rec[$cir_out_rec] + 1;
				$c_out = $cir_out;
				$c_in_rec[$cir_in_rec] = $c_in_rec[$cir_in_rec] + 1;
				$c_in = $cir_in;
				//criticidade mensal - separa valores
				$ocorrencias = $ocorrencias + 1;
				if (($criticidade_out >= 20)and($criticidade_out < 50)){
					$uso_20_50_out = $uso_20_50_out + 1;
				}elseif (($criticidade_out >= 50)and($criticidade_out < 85)){
					$uso_50_85_out = $uso_50_85_out + 1;
				}elseif ($criticidade_out >= 85){
					$uso_m_85_out = $uso_m_85_out + 1;
				}
				if (($criticidade_in >= 20)and($criticidade_in < 50)){
					$uso_20_50_in = $uso_20_50_in + 1;
				}elseif (($criticidade_in >= 50)and($criticidade_in < 85)){
					$uso_50_85_in = $uso_50_85_in + 1;
				}elseif ($criticidade_in >= 85){
					$uso_m_85_in = $uso_m_85_in + 1;
				}
		
				//criticidade mensal - em %
				$uso_20_50_in = 100*$uso_20_50_in/$ocorrencias;
				$uso_50_85_in = 100*$uso_50_85_in/$ocorrencias;
				$uso_m_85_in  = 100*$uso_m_85_in/$ocorrencias;
				$uso_20_50_out= 100*$uso_20_50_out/$ocorrencias;
				$uso_50_85_out= 100*$uso_50_85_out/$ocorrencias;
				$uso_m_85_out = 100*$uso_m_85_out/$ocorrencias;
			}//fim do while (list  
			//cir mensal recomendado - apanha valor
			krsort ($c_in_rec);
			$cir_in_rec_m = key ($c_in_rec);
			if ($c_in_rec[$cir_in_rec_m] < 2) {
				next ($c_in_rec);	
				$cir_in_rec_m = key ($c_in_rec);
			}		
			krsort ($c_out_rec);
			$cir_out_rec_m = key ($c_out_rec);
			if ($c_out_rec[$cir_out_rec_m] < 2) {
				next ($c_out_rec);	
				$cir_out_rec_m = key ($c_out_rec);
			}
			//volume mensal em Gigabytes
			$volume_mensal_out = $volume_mensal_out/1000000;
			$volume_mensal_in  = $volume_mensal_in/1000000;
			//***Grava dados na Tabela
			$inclusao = "INSERT INTO performance_mensal (mes_ano,uf,cod_int,uso_20_50_in,uso_50_85_in,
				uso_m_85_in,uso_20_50_out,uso_50_85_out,uso_m_85_out,volume_in,volume_out,
				cir_in,cir_out,cir_in_rec,cir_out_rec) 
				VALUES('$mes_ano','$uf','$arquivo','$uso_20_50_in','$uso_50_85_in','$uso_m_85_in','$uso_20_50_out',
				'$uso_50_85_out','$uso_m_85_out','$volume_mensal_in','$volume_mensal_out','$c_in','$c_out',
				'$cir_in_rec_m','$cir_out_rec_m')";
			echo $inclusao;
			if (!mysql_query($inclusao, $conexao)) {
			     echo("Não Foi possível Atualizar a Tabela $arquivo !!!");
			}		

		}//fim do FOR para executar $day dias	 
	}//fim do while ($row = ) - que varre os circuitos
}//Fecha o for ($t=0; - estados
mysql_close($conexao);
?>
