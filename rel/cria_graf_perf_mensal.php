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

//**********Para a rede de acesso***************************************
//error_reporting (0);
include ("jpgraph.php");
include ("jpgraph_line.php");
include ("jpgraph_bar.php");
include ("../config/conexao.php");
$conexao = conexao();
mysql_select_db("rede_acesso", $conexao);

//$estados = array("AC", "AL", "AM", "AP", "BA", "CE", "DF", "ES", "GO", "MA", "MG", "MS", "MT", "PA","PB", "PE", "PI", "PR", "RJ", "RN", "RO", "RR", "RS", "SC", "SE", "SP", "TO");
//$estados = array("AC");
$query= "select DISTINCT uf  from rede_acesso";
$estados=mysql_query($query,$conexao);
while($line = mysql_fetch_array($estados))
{	
//$uf = &$estados[$t];
$consulta = "SELECT cod_int,orgao FROM rede_acesso WHERE uf='" . $line['uf'] . "'";
$localidades = mysql_query($consulta, $conexao);

while ($parametros = mysql_fetch_row($localidades)) {
	$circuito 	= strtr($parametros[0], ".", "_");
	$mes_atual	= date('Y-m-01');

	$mes_ini 	= mktime (0, 0, 0, date("m")-7, date("d"),  date("Y"));
	$mes_ini 	= Date ('Y-m-01',$mes_ini);
	echo $circuito."<BR>";
//Desenha gráficos
	Grafico ("volume_in","volume_out","GBytes",1000,"volume","Volume Mensal"); 
	Grafico ("cir_in_rec","cir_out_rec","kbps",1,"cir_rec","Taxa = 95% amostras Mensal"); 
//	Grafico2("uso_20_50_in","uso_50_85_in","uso_m_85_in","->APS");
//	Grafico2("uso_20_50_out","uso_50_85_out","uso_m_85_out","APS->");

}//fim primeiro while

}//fim do FOR
//**********************  FUNCAO QUE DESENHA GRAFICO  *************************
function Grafico ($parametro1, $parametro2, $y_eixo, $k, $nome, $tipo){
global $conexao, $ydata3, $ydata4, $mes_atual, $mes_ini, $perf_tbl, $circuito, $conexao;
$query = "SELECT $parametro1, $parametro2, mes_ano,cir_in, cir_out FROM performance_mensal 
				WHERE (mes_ano < '$mes_atual' and mes_ano > '$mes_ini' and cod_int = '$circuito') order by mes_ano";

echo "SELECT $parametro1, $parametro2, mes_ano,cir_in, cir_out FROM performance_mensal 
				WHERE (mes_ano < '$mes_atual' and mes_ano > '$mes_ini' and cod_int = '$circuito') order by mes_ano";
$resultado = mysql_query($query, $conexao);
$i = 0;
while ($row = mysql_fetch_row($resultado)) {
    $data1y[$i] = $row[0]/$k;
    $data2y[$i] = $row[1]/$k;
    $time 		= strtotime ($row[2]);
	$a[$i] 		= Date('m/Y',$time);
	$ydata3[$i] = $row[3];
	$ydata4[$i] = $row[4];
    $i = $i + 1;
	}
// Create the graph. These two calls are always required
$graph = new Graph(270,220,"auto");    
$graph->SetScale("textlin");
$graph->img->SetMargin(40,40,5,60);
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
//acrescenta linhas de cir
	
// Set the legends for the plots
$b1plot->SetLegend("APS>");
$b2plot->SetLegend(">APS");
$graph->legend->SetFrameWeight(0);
$graph->legend->SetShadow(0);
// Adjust the legend position
$graph->legend->SetLayout(LEGEND_VER);
$graph->legend->Pos(0.01,0.1,"right","center");
// Create the grouped bar plot
$gbplot = new GroupBarPlot(array($b1plot,$b2plot));
// ...and add it to the graPH
$graph->Add($gbplot);
if ($tipo == "CIR Mensal recomendado"){
	//apanha o cir in e cir out
	$lineplot3=new LinePlot($ydata3);  
	$lineplot4=new LinePlot($ydata4);  
	$graph->Add($lineplot4);
	$graph->Add($lineplot3);
	$lineplot3->SetColor("green:0.8");
	$lineplot3->SetWeight(1);
	$lineplot4->SetColor("blue");
	$lineplot4->SetWeight(1);	
}

// Display the graph
$graph->SetFrame(false);
$nome_graf = "./mensal/".$circuito.$nome."_m.png";
$graph->Stroke($nome_graf);
//$graph->Stroke();
}//*************fim funcao grafico**************

//**********************  FUNCAO QUE DESENHA GRAFICO 2*************************
function Grafico2 ($parametro1, $parametro2,$parametro3,$sentido){
global $conexao, $dia_fim, $dia_ini, $perf_tbl, $circuito, $arquivo, $conexao;
$query = "SELECT mes_ano,$parametro1, $parametro2,$parametro3 FROM performance_mensal WHERE (cod_int = '$circuito') order by mes_ano";
$resultado = mysql_query($query, $conexao);
$i = 0;
while ($row = mysql_fetch_row($resultado)) {
    $time 		= strtotime ($row[0]);
	$a[$i] 		= Date('m/Y',$time);
	$data1y[$i] = $row[1];
    $data2y[$i] = $row[2];
	$data3y[$i] = $row[3];
    $i = $i + 1;
	}
// Create the graph. These two calls are always required
$graph = new Graph(300,220,"auto");    
$graph->SetScale("textlin");
$graph->img->SetMargin(40,30,20,60);
// Create the bar plots
$b1plot = new BarPlot($data1y);
$b1plot->SetFillColor("yellow");
$b2plot = new BarPlot($data2y);
$b2plot->SetFillColor("orange:0.9");
$b3plot = new BarPlot($data3y);
$b3plot->SetFillColor("red");

$graph->title->Set("Criticidade mensal CIR  $sentido");
$graph->yaxis->title->Set("percentual - %");

$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->SetTickLabels($a); 
$graph->xaxis->SetLabelAngle(90);

$b1plot->SetLegend("20%-50%");
$b2plot->SetLegend("50%-85%");
$b3plot->SetLegend(">85%");
// Adjust the legend position
$graph->legend->SetLayout(LEGEND_VER);
$graph->legend->SetFillColor("white");
$graph->legend->SetShadow(false);
$graph->legend->Pos(0.01,0.22,'right','center');
// Display the graph
$graph->SetFrame(false);
// Create the grouped bar plot
$gbplot = new GroupBarPlot(array($b1plot,$b2plot,$b3plot));
// ...and add it to the graPH
$in_out = substr($parametro3,-3);
$graph->Add($gbplot);
$nome_graf = "./mensal/".$circuito."criticid_".$in_out."_m.png";
$graph->Stroke($nome_graf);
//$graph->Stroke();
}//*************fim funcao grafico**************

?>

