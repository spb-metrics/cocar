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

error_reporting(0);
include ("jpgraph.php");
include ("jpgraph_line.php");
include ("jpgraph_bar.php");
include ("jpgraph_stock.php");
include ("../config/conexao.php");
$conexao = conexao();
mysql_select_db("rede_acesso", $conexao);


$hoje 		= date('Y-m-d');
$dia_fim 	= strtotime($hoje) - 86400;
$dia_ini 	= $dia_fim - 2592000;
$dia_ini 	= date('Y-m-d',$dia_ini);
$dia_fim 	= date('Y-m-d',$dia_fim);

$query= "select DISTINCT uf  from rede_acesso";
$estados=mysql_query($query,$conexao);
while($line = mysql_fetch_array($estados))
{	
//$uf = &$estados[$t];
$consulta = "SELECT cod_int,orgao, tecnologia FROM rede_acesso WHERE uf='" . $line['uf'] . "'";
$localidades = mysql_query($consulta, $conexao);

while ($parametros = mysql_fetch_row($localidades)) {
	$arquivo 	= $parametros[0];
	$tech       = $parametros[2];
	echo $arquivo."<BR>";
//VERIFICA SE SE HA REGISTRO NO BANCO  MYSQL PARA ESTA DATA
$query = "SELECT count(*) dia FROM performance_diaria WHERE (dia >= '$dia_ini' and dia <= '$dia_fim')and(cod_int = '$arquivo')";
//echo $query;
$resultado = mysql_query($query, $conexao);
$existe = mysql_result($resultado,0);
if ($existe > 1){	
	//apanha o cir in e cir out
	$query = "SELECT cir_in, cir_out FROM performance_diaria WHERE (dia >= '$dia_ini' and dia <= '$dia_fim')and(cod_int = '$arquivo')";
	$resultado = mysql_query($query, $conexao);

	$i = 0;
	while ($row = mysql_fetch_row($resultado)) {
		if (($tech != "ETH")and($row[1]<2000)){
    		$ydata3[$i] = $row[0];	//linhas com o cir
			$ydata4[$i] = $row[1];
		}else{
    		$ydata3[$i] = 0;	//linhas com o cir
			$ydata4[$i] = 0;
		}//***if ($tech == "FR")					
		$i = $i + 1;
	}

	//Desenha gráficos
	Grafico ("volume_in","volume_out","MBytes",1000000,"volume","Volume - uúltimos 30 dias"); 
//	Grafico ("hmm_criticidade_in","hmm_criticidade_out","Percentual - %",1, "hmm_criticidade", "Acima do CIR na HMM");
//	Grafico ("7_19_criticidade_in","7_19_criticidade_out","Percentual - %",1, "periodo_criticidade", "Acima do CIR entre 7:00 e 19:00");
	Grafico ("cir_in_rec","cir_out_rec","kbps",1,"cir_rec","Taxa = 95% amostras - 30 dias"); 
	
//	Grafico2 ("7_19_media_in","cir_in_rec","7_19_pico_in","kbps",1,"in_m95M","SAIDA APS (últimos 30 dias) - Média/Taxa95%/Máx"); 
//	Grafico2 ("7_19_media_out","cir_out_rec","7_19_pico_out","kbps",1,"out_m95M","ENTRADA APS (últimos 30 dias) - Média/Taxa95%/Máx"); 
	Grafico2 ("cir_out_rec","7_19_media_out","7_19_pico_out","kbps",1,"out_m95M","ENTRADA APS (últimos 30 dias) - Média/Taxa95%/Máx"); 
	Grafico2 ("cir_in_rec","7_19_media_in","7_19_pico_in","kbps",1,"in_m95M","SAIDA APS (últimos 30 dias) - Média/Taxa95%/Máx"); 
	
	HMM ("hmm_hora_in", "hmm_hora_out", "HMM");
	HMM ("hmm_hora_in_per", "hmm_hora_out_per", "HMMper");
	
}else echo " - sem registro de ocorrências"."<BR>";//if que verifica se há registros para o período em "performance_diaria"
	
}//fim primeiro while que descarrega os circuitos de um estado

}//***fim do FOR que apanha cada estado		
//****************FUNÇÃO HMM *****************************************
function HMM ($hmm_in, $hmm_out, $nome){
global $conexao, $dia_fim, $dia_ini, $arquivo, $conexao;
$query = "SELECT $hmm_in, $hmm_out, dia FROM performance_diaria WHERE (dia >= '$dia_ini' and dia <= '$dia_fim')and(cod_int = '$arquivo') ORDER BY dia";
$resultado = mysql_query($query, $conexao);
$i = 0;
while ($row = mysql_fetch_row($resultado)) {  
	$h = strtotime ($row[1]);
	$ydata[$i] = Date('H',$h);
	$h = strtotime ($row[0]);
	$ydata2[$i] = Date('H',$h);
    $time = strtotime ($row[2]);
	$a[$i] = Date('d/m',$time);
    $i = $i + 1;
	}
$graph = new Graph(480,220,"auto");    
$graph->SetScale("textlin");
$graph->img->SetMargin(40,5,5,60);

$lineplot = new LinePlot($ydata);
$lineplot2= new LinePlot($ydata2);  
// Adiciona a linha ao grafico
$graph->Add($lineplot);
$graph->Add($lineplot2);
$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->Set("hora");
$graph->xaxis->SetTickLabels($a); 
$graph->xaxis->SetLabelAngle(90);

$lineplot->SetColor("green:0.8");
$lineplot->SetWeight(1);
$lineplot->mark->SetType(MARK_SQUARE);

$lineplot2->SetColor("blue");
$lineplot2->SetWeight(1);
$lineplot2->mark->SetType(MARK_SQUARE);
$lineplot->mark->SetFillColor("green");

$graph->title->Set("Horario inicial da HMM      ");
// Set the legends for the plots
$lineplot->SetLegend("->APS");
$lineplot2->SetLegend("APS->");
// Adjust the legend position
$graph->legend->SetLayout(LEGEND_HOR);
$graph->legend->Pos(0.02,0.06,"right","center");
$graph->legend->SetFrameWeight(0);
$graph->legend->SetShadow(0);
//grava figura
$graph->SetFrame(false);
$nome_graf = "./diario/".$arquivo.$nome.".png";
$graph->Stroke($nome_graf);
}//*************** FIM DA FUNÇÃO HMM ************************

//**********************  FUNCAO QUE DESENHA GRAFICO  II*************************
function Grafico2 ($parametro1, $parametro2, $parametro3, $y_eixo, $k, $nome, $tipo){
global $conexao, $ydata3, $ydata4, $dia_fim, $dia_ini, $perf_tbl, $arquivo, $parametros, $conexao;
$query = "SELECT $parametro1, $parametro2, $parametro3, dia FROM performance_diaria WHERE (dia >= '$dia_ini' and dia <= '$dia_fim')and(cod_int = '$arquivo') ORDER BY dia";
$resultado = mysql_query($query, $conexao);
$i = 0;
while ($row = mysql_fetch_row($resultado)) {
    $datay[$i] 	= $ydata3[1]/20 + $row[0]/$k;
    $i 		   	= $i + 1;
    $datay[$i] 	= $row[0]/$k;
    $i 		   	= $i + 1;
    $datay[$i] 	= $row[1]/$k;
    $i 		   	= $i + 1;
    $datay[$i] 	= $row[2]/$k;
    $time 		= strtotime ($row[3]);
	$a[$i/4] 	= Date('d/m',$time);
    $i 			= $i + 1;
	}
// Create the graph. 
$graph = new Graph(550,220,"auto");    
$graph->SetScale("textlin");
$graph->img->SetMargin(40,10,5,60);
// Create the bar plots
$b1plot = new StockPlot($datay);

$b1plot->SetWidth(9);

$graph->title->Set("$tipo");
$graph->yaxis->title->Set($y_eixo);

$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->SetTickLabels($a);
$graph->xaxis->SetLabelAngle(90);
//acrescenta linhas de cir
	$lineplot3=new LinePlot($ydata3);  
	$lineplot4=new LinePlot($ydata4);  
	$lineplot3->SetColor("red");
	$lineplot3->SetWeight(1);
	$lineplot4->SetColor("red");
	$lineplot4->SetWeight(1);	
if ($tipo == "ENTRADA APS (últimos 30 dias) - Média/Taxa95%/Máx") {
	$graph->Add($lineplot4);
}else{
	$graph->Add($lineplot3);
};
// Set the legends for the plots
//$b1plot->SetLegend("Taxa_95%");
$b1plot->SetWeight(2);
$b1plot->SetColor('blue','blue','orange','red');
//$graph->legend->SetFrameWeight(0);
//$graph->legend->SetShadow(0);
// Adjust the legend position
//$graph->legend->SetLayout(LEGEND_HOR);
//$graph->legend->Pos(0.02,0.07,"right","center");

// ...and add it to the graPH
$graph->Add($b1plot);
//Display the graph
$graph->SetFrame(false);
$nome_graf = "./diario/".$arquivo.$nome.".png";
$graph->Stroke($nome_graf);
}//*************fim funcao grafico**************

//**********************  FUNCAO QUE DESENHA GRAFICO *************************
function Grafico ($parametro1, $parametro2, $y_eixo, $k, $nome, $tipo){
global $conexao, $ydata3, $ydata4, $dia_fim, $dia_ini, $perf_tbl, $arquivo, $parametros, $conexao;
$query = "SELECT $parametro1, $parametro2, dia FROM performance_diaria WHERE (dia >= '$dia_ini' and dia <= '$dia_fim')and(cod_int = '$arquivo') ORDER BY dia";
$resultado = mysql_query($query, $conexao);
$i = 0;
while ($row = mysql_fetch_row($resultado)) {
    $data1y[$i] = $row[0]/$k;
    $data2y[$i] = $row[1]/$k;
    $time 		= strtotime ($row[2]);
	$a[$i] 		= Date('d/m',$time);
    $i 			= $i + 1;
	}
// Create the graph. These two calls are always required
$graph = new Graph(480,220,"auto");    
$graph->SetScale("textlin");
$graph->img->SetMargin(40,10,5,60);
// Create the bar plots
$b1plot = new BarPlot($data1y);
$b1plot->SetFillColor("green:0.8");
$b1plot->SetWeight(0);
$b2plot = new BarPlot($data2y);
$b2plot->SetFillColor("blue");
$b2plot->SetWeight(0);

$graph->title->Set("$tipo");
$graph->yaxis->title->Set($y_eixo);

$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->SetTickLabels($a);
$graph->xaxis->SetLabelAngle(90);

if ($tipo == "Taxa = 95% amostras - 30 dias"){
//acrescenta linhas de cir
	$lineplot3=new LinePlot($ydata3);  
	$lineplot4=new LinePlot($ydata4);  
	$graph->Add($lineplot4);
	$graph->Add($lineplot3);
	$lineplot3->SetColor("green:0.8");
	$lineplot3->SetWeight(1);
	$lineplot4->SetColor("blue");
	$lineplot4->SetWeight(1);	
}	
// Set the legends for the plots
$b1plot->SetLegend("APS>");
$b2plot->SetLegend(">APS");
// Adjust the legend position
$graph->legend->SetLayout(LEGEND_HOR);
$graph->legend->Pos(0.02,0.07,"right","center");
$graph->legend->SetFrameWeight(0);
$graph->legend->SetShadow(0);
// Create the grouped bar plot
$gbplot = new GroupBarPlot(array($b1plot,$b2plot));
// ...and add it to the graPH
$graph->Add($gbplot);
//Display the graph
$graph->SetFrame(false);
$nome_graf = "./diario/".$arquivo.$nome.".png";
$graph->Stroke($nome_graf);
}//*************fim funcao grafico**************



?>

