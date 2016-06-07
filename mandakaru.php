
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

//	error_reporting(0);

#####################################
	include("config/conexao.php");
	$conexao = conexao();
	mysql_select_db(BANCO);
#####################################
	$TabelaPerform= "performance_diaria";
	
//alteração para flexibilibilzacao
$query= "select DISTINCT uf  from rede_acesso";
$estados=mysql_query($query,$conexao);
while($line = mysql_fetch_array($estados)) {
$uf = $line['uf'];
//fim alteracao de flexibilixzacao

	$consulta = "SELECT cod_int, cir_in, cir_out, porta FROM " . TABELA . " WHERE uf='$uf' AND IfStatus='UP'";
	$resultado = mysql_query($consulta);
	
	while ($row = mysql_fetch_array($resultado,MYSQL_NUM)) 
	{
		$cod_int = &$row[0];
		$cir_in  = &$row[1];	
		$cir_out = &$row[2];
		$porta	 = &$row[3];

			## criticidade no Periodo - numero de amostas que ultrapassam o CIR
			if ($cir_in == 0)
			{
				$cir_in  = $porta*0.85;	
				$cir_out = $porta*0.85;
			}
			$arquivo = strtr($cod_int, ".", "_");


			$dia = strftime('%Y-%m-%d', (date('U')-86400));
			$data_hora_inicio = $dia." 07:00:00";
			$data_hora_fim = $dia." 19:00:00";
	
			###	CALCULA VOLUME IN E OUT
				$consulta = "SELECT SUM(volume_in), SUM(volume_out) FROM $arquivo WHERE (data_hora >= '$dia 00:00:00')and(data_hora <= '$dia  23:59:00')";
				$resp = mysql_fetch_row((mysql_query($consulta)));
					$volumeIN 	= $resp[0]*60;
					$volumeOUT 	= $resp[1]*60;


			### CALCULA HMM IN: HORA, MEDIA, PICO, CRITICIDADE
				list ($horaHMM_in, $minHMM_in, $mediaHMM_in, $picoHMM_in, $criticidadeHMM_in) = HMM("volume_in", 0, 23, $cir_in);
				$hora_HMM_in = $horaHMM_in.":".$minHMM_in.":00";
	
			### CALCULA HMM OUT: HORA, MEDIA, PICO, CRITICIDADE
				list ($horaHMM_out, $minHMM_out, $mediaHMM_out, $picoHMM_out, $criticidadeHMM_out) = HMM ("volume_out", 0, 23, $cir_out);
				$hora_HMM_out = $horaHMM_out.":".$minHMM_out.":00";

			## CALCULA HMM IN NO PERIODO : HORA, MEDIA, PICO, CRITICIDADE
				list ($horaHMM_in, $minHMM_in, $mediaHMM_in_per, $picoHMM_in_per, $criticidadeHMM_in_per) = HMM ("volume_in", 7, 18, $cir_in);
				$hora_HMM_in_per = $horaHMM_in.":".$minHMM_in.":00";

			## CALCULA HMM OUT NO PERIODO: HORA, MEDIA, PICO, CRITICIDADE
				list ($horaHMM_out, $minHMM_out, $mediaHMM_out_per, $picoHMM_out_per, $criticidadeHMM_out_per) = HMM ("volume_out", 7, 18, $cir_out);
				$hora_HMM_out_per = $horaHMM_out.":".$minHMM_out.":00";
	
			## CALCULA PERIODO IN: HORA, MEDIA, PICO, CRITICIDADE
				list ($pico7_19_in, $media7_19_in, $criticidade7_19_in) = Periodo ("volume_in", $cir_in);
	
			## CALCULA PERIODO OUT: HORA, MEDIA, PICO, CRITICIDADE
			list ($pico7_19_out, $media7_19_out, $criticidade7_19_out) = Periodo ("volume_out", $cir_out);
				$CirRecomendadoIN = CirRecomendado($arquivo, "volume_in", $dia);
				$CirRecomendadoOUT = CirRecomendado($arquivo, "volume_out", $dia);

			### GRAVA DADOS NA TABELA
			$inclusao = "INSERT INTO $TabelaPerform(
						cod_int, dia, cir_in, cir_out, cir_in_rec, cir_out_rec, volume_in, volume_out, hmm_hora_in, 
						hmm_hora_out, hmm_pico_in, hmm_pico_out, hmm_media_in, hmm_media_out, hmm_criticidade_in,
						hmm_criticidade_out, hmm_hora_in_per, hmm_hora_out_per, hmm_pico_in_per, hmm_pico_out_per, 
						hmm_media_in_per, hmm_media_out_per, hmm_criticidade_in_per, hmm_criticidade_out_per, 
						7_19_pico_in, 7_19_pico_out, 7_19_media_in, 7_19_media_out, 7_19_criticidade_in, 
						7_19_criticidade_out) 
					VALUES(
						'$cod_int', '$dia','$cir_in','$cir_out','$CirRecomendadoIN','$CirRecomendadoOUT','$volumeIN','$volumeOUT',
						'$hora_HMM_in','$hora_HMM_out','$picoHMM_in','$picoHMM_out','$mediaHMM_in','$mediaHMM_out',
						'$criticidadeHMM_in','$criticidadeHMM_out','$hora_HMM_in_per','$hora_HMM_out_per','$picoHMM_in_per',
						'$picoHMM_out_per','$mediaHMM_in_per','$mediaHMM_out_per','$criticidadeHMM_in_per',
						'$criticidadeHMM_out_per','$pico7_19_in','$pico7_19_out','$media7_19_in','$media7_19_out',
						'$criticidade7_19_in','$criticidade7_19_out')";
		
			if (!mysql_query($inclusao))
	  		{
					criar_tabela($TabelaPerform);
					mysql_query($inclusao);
			}

	}## FIM DO WHILE QUE VARRE OS CIRCUITOS

}
mysql_free_result;
mysql_close($conexao);

###### FUNCAO QUE CALCULA HMM
function HMM ($campo, $h_ini, $h_fim, $cir)
{
	global $arquivo, $dia;
	$bytesHMM = 0;
		for ($h = $h_ini; $h <= $h_fim ; $h++)
		{
				for ($m = 0; $m < 60; $m++)
				{
					$hora = $dia." ".$h.":".$m.":00";			//inicio do calculo da HMM
					$hHMM = $h + 1;								//periodo de 60 minutos apóo instante inicial
					$hora60min = $dia." ".$hHMM.":".$m.":00"; 	//fim do periodo de 60 min acima do minutos da hora atual
					//Soma todos os bytes de 60 min a partir do minuto atual	
					$queryHMM = "SELECT SUM($campo) FROM $arquivo WHERE (data_hora >= '$hora')and(data_hora <= '$hora60min')";
					$bytes = mysql_result(mysql_query($queryHMM),0);
						if ($bytes > $bytesHMM)
						{
								$bytesHMM = $bytes;
								$vetor_HMM[2] = (8/60000)*$bytesHMM;	//media na HMM em kilobits/s
								$vetor_HMM[0] = $h;						//hora HMM
								$vetor_HMM[1] = $m;						//minuto HMM
						}	
				}
		}
	## PICO NA  HMM
	$hora = $dia." ".$vetor_HMM[0].":".$vetor_HMM[1].":00";	//inicio do cáulo da HMM
	$hHMM = $vetor_HMM[0] + 1;							//perío de 60 minutos apóo instante inicial;
	$hora60min = $dia." ".$hHMM.":".$vetor_HMM[1].":00";

	$query = "SELECT MAX($campo) FROM $arquivo WHERE (data_hora >= '$hora')and(data_hora < '$hora60min')";
	$bytes = mysql_result(mysql_query($query),0);
	$vetor_HMM[3] = (8/1000)*$bytes;	

	//Criticidade na HMM
	$query = "SELECT $campo FROM $arquivo WHERE (data_hora >= '$hora')and(data_hora < '$hora60min')and($campo > '$cir'*(1000/8))";
	$vetor_HMM[4] = (100/60)* mysql_num_rows((mysql_query($query)));
	return $vetor_HMM;
}//////////////////////////////////////// FIM DA FUNCAO HMM ////////////////////////////////////////

## FUNCAO QUE CALCULA VARIAVEIS DO PERIODO
function Periodo($campo, $cir)
{
	global $arquivo, $data_hora_inicio, $data_hora_fim;
	
	##CALCULA PICO NO PERIODO
	$query = "SELECT MAX($campo) FROM $arquivo WHERE (data_hora >= '$data_hora_inicio')and(data_hora < '$data_hora_fim')";
	$bytes = mysql_result((mysql_query($query)),0);
	$vetor_Periodo[0] = (8/1000)*$bytes;		//pico no periodo em kilobits/s

	## CALCULA MEDIA NO PERIODO
	$query = "SELECT SUM($campo) FROM $arquivo WHERE (data_hora >= '$data_hora_inicio')and(data_hora < '$data_hora_fim')";
	$bytes = mysql_result((mysql_query($query)),0);
	$vetor_Periodo[1] = (8/720000)*$bytes;		//media no periodo em kilobits/s

	## CRITICIDADE NO PERIODO - NUMERO DE AMOSTAS QUE ULTRAPASSAM O CIR
	$query = "SELECT $campo FROM $arquivo WHERE (data_hora >= '$data_hora_inicio')and(data_hora < '$data_hora_fim')and($campo > $cir*(1000/8))";
	$vetor_Periodo[2] =(100/720)* mysql_num_rows((mysql_query($query)));
	return $vetor_Periodo;
}///////////////////////////////////// FIM DA FUNCAO PERIODO /////////////////////////////////////

### FUNCAO CALCULA CIR RECOMENDADO
function CirRecomendado($arquivo, $campo, $dia)
{
	global $data_hora_inicio, $data_hora_fim;

	## VETOR COM OS VALORES DE CIR RECOMENDADO
	$cir_recomendado = array (16, 32, 48, 64, 80, 96, 112, 128, 160, 192, 224, 256, 288, 320, 352, 384, 448, 512, 640, 768, 896, 1024, 1152, 1280, 1408, 1536, 1664, 1792, 1920);

	$query = "SELECT $campo FROM $arquivo WHERE (data_hora >= '$data_hora_inicio')and(data_hora < '$data_hora_fim')";
	$resultado = mysql_query($query);
	
	while ( list($row) = mysql_fetch_row($resultado))
	{
		$row *= (8/1000);
		$amostras++;
	    $cir = round($row, 0);
		
		## GERA VETOR DE FAIXAS DE CIR, QUE ARMAZENA O NUMERO DE VEZES QUE OCORREU CADA FAIXA
		$inic 	= 0;
		$fim 	= count($cir_recomendado) -1;
		$meio	= ($inic + $fim)/2;
		settype ($meio, int);
		while ($inic!=$fim && $cir!=$cir_recomendado[$meio])
		{
			// $inic!=$fim -> determina quando o vetor foi todo percorrido
			// $cir!=$cir_recomendado[$meio] -> caso o valor do procura seja exatamente igual ao valor na posiç meio, nao precisa ocorrer interacao
			if ($cir > $cir_recomendado[$meio])  // Compara o valor $cir ao valor na posicao meio, caso ele seja maior a posicao anterior ao meio pode ser descartada
					$inic = $meio + 1;
			else								// caso o valor $cir seja Menor, entao a posicao Depois do meio pode ser descartada
					$fim = $meio;
			$meio	= ($inic + $fim)/2;// Como Inicio e fim receberam novas posicoes, entao meio deve ser remanejado
			settype ($meio, int);
		}
		// depois de percorrer o vetor por completo, ele saira na faixa de busca
		// e sera incrementado o vetor-contador de faixas, na posicao da faixa que se encontra no vetor $cir_recomendado
		$c_rec[$meio] += 1;
	}  

	$j = -1;
	while ($x < (0.95*$amostras))
	{//95% das amostras do periodo
		$j++;
		$x += $c_rec[$j];
	}

return $cir_recomendado[$j];
}///////////////////////////////////// FIM DA FUNCAO CIR RECOMENDADO /////////////////////////////////////

function criar_tabela($TabelaPerform)
{	
	$CriarTabela = "CREATE TABLE `$TabelaPerform` (
	  `cod_int` varchar(20) NOT NULL default 'x',
	  `dia` date NOT NULL default '0000-00-00',
	  `cir_in` int(11) NOT NULL default '0',
	  `cir_out` int(11) NOT NULL default '0',
	  `cir_in_rec` int(11) NOT NULL default '0',
	  `cir_out_rec` int(11) NOT NULL default '0',
	  `volume_in` bigint(11) NOT NULL default '0',
	  `volume_out` bigint(11) NOT NULL default '0',
	  `delay_120_160` decimal(10,1) NOT NULL default '0.0',
	  `delay_M_160` decimal(10,1) NOT NULL default '0.0',
	  `perda_in_hora` time NOT NULL default '00:00:00',
	  `perda_in_pico` decimal(10,1) NOT NULL default '0.0',
	  `perda_out_hora` time NOT NULL default '00:00:00',
	  `perda_out_pico` decimal(8,0) NOT NULL default '0',
	  `perda_out_3_6` decimal(8,0) NOT NULL default '0',
	  `perda_in_3_6` decimal(8,0) NOT NULL default '0',
	  `perda_out_M_6` decimal(8,0) NOT NULL default '0',
	  `perda_in_M_6` decimal(8,0) NOT NULL default '0',
 	  `congest_in_10_30` decimal(10,1) NOT NULL default '0.0',
	  `congest_in_M_30` decimal(10,1) NOT NULL default '0.0',
	  `congest_out_10_30` decimal(10,1) NOT NULL default '0.0',
	  `congest_out_M_30` decimal(10,1) NOT NULL default '0.0',
	  `hmm_hora_in` time NOT NULL default '00:00:00',
	  `hmm_hora_out` time NOT NULL default '00:00:00',
	  `hmm_pico_in` int(11) NOT NULL default '0',
	  `hmm_pico_out` int(11) NOT NULL default '0',
	  `hmm_media_in` int(11) NOT NULL default '0',
	  `hmm_media_out` int(11) NOT NULL default '0',
	  `hmm_criticidade_in` decimal(11,1) NOT NULL default '0.0',
	  `hmm_criticidade_out` decimal(11,1) NOT NULL default '0.0',
	  `hmm_hora_in_per` time NOT NULL default '00:00:00',
	  `hmm_hora_out_per` time NOT NULL default '00:00:00',
	  `hmm_pico_in_per` int(11) NOT NULL default '0',
	  `hmm_pico_out_per` int(11) NOT NULL default '0',
	  `hmm_media_in_per` int(11) NOT NULL default '0',
	  `hmm_media_out_per` int(11) NOT NULL default '0',
	  `hmm_criticidade_in_per` decimal(11,1) NOT NULL default '0.0',
	  `hmm_criticidade_out_per` decimal(11,1) NOT NULL default '0.0',
	  `7_19_pico_in` int(11) NOT NULL default '0',
	  `7_19_pico_out` int(11) NOT NULL default '0',
	  `7_19_media_in` int(11) NOT NULL default '0',
	  `7_19_media_out` int(11) NOT NULL default '0',
	  `7_19_criticidade_in` decimal(11,1) NOT NULL default '0.0',
	  `7_19_criticidade_out` decimal(11,1) NOT NULL default '0.0',
	  	PRIMARY KEY  (`cod_int`,`dia`),
	  	UNIQUE KEY `uk` (`cod_int`,`dia`),
	  	KEY `cod_int` (`cod_int`)
		) TYPE=MyISAM
		";
	mysql_query($CriarTabela);
} ## FUNCAO PARA CRIAR TABELA PERFORMANCE

mysql_close();

?>
