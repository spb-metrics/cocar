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

//error_reporting(0);
	if ( isset( $_GET["Idd"] ) ) $Idd = $_GET["Idd"];
	if ( isset( $_GET["Imm"] ) ) $Imm = $_GET["Imm"];
	if ( isset( $_GET["Iaa"] ) ) $Iaa = $_GET["Iaa"];
	if ( isset( $_GET["Iperiodo"] ) )$Iperiodo= $_GET["Iperiodo"];
	
	if ( isset( $_GET["Fdd"]) ) $Fdd = $_GET["Fdd"];
	if ( isset( $_GET["Fdd"]) ) $Fmm = $_GET["Fmm"];
	if ( isset( $_GET["Fdd"]) ) $Faa = $_GET["Faa"];

	if ( isset( $_GET["$uf"] ) )	$uf = $_GET["uf"];
	if ( isset( $_GET["$circ"] ) )	$circ = $_GET["circ"];	
	if ( isset( $_GET["$mostra_cir"] ) ) $mostra_cir = $_GET["mostra_cir"];
	if ( isset( $_GET["$h_inicio"] ) ) $h_inicio = $_GET["h_inicio"];

	
	$semana = array ("dom", "seg","ter","qua","qui","sex","sab");
	include ("../config/conexao.php");
	$conexao = conexao();
	$tabela = "rede_acesso";
	mysql_select_db(BANCO,$conexao);
	
	$aplicacao = "REDE DE ACESSO - Tráfego nos Circuitos";
?>
<HTML>
<p><img border="0" src="logo/top_logo.gif" width="100" height="44"><img border="0" src="logo/top_dataprev.gif" width="456" height="44"><img border="0" src="logo/top_pontos.gif" width="198" height="44"></p>

<HEAD>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<TITLE>Log de Tráfego - <?php echo $aplicacao; ?></TITLE>
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
		font-family: Arial, Verdana; 
		font-weight: bold; 
		text-align: left; 
		text-valign: middle
	}

.tdt
	{
		font-size: 10pt; 
		font-family: Verdana, Arial; 
		font-weight: bold; 
		text-align: left; 
		text-valign: middle
	}
.tdi
	{
		font-size: 10pt; 
		font-family: Verdana, Arial; 
		font-weight: bold; 
		text-align: left; 
		text-valign: middle;
		BORDER-COLOR: #F58735; 
		BORDER-STYLE: solid; 
		BORDER-WIDTH: 1px; 
	}
.tdx
	{
		font-size: 9pt;
		font-family: Verdana, Arial; 
		font-weight: bold; 
		text-align: left; 
		text-valign: middle;


	}
	
</style>

<script language="javascript">
function mudapagina(combo)
{
	var endereco = combo.value;
	if (endereco!="#" || endereco!="")
	{
		novapagina = window.location=endereco;
	}
}
</script>

<script language="JavaScript">
	function validaForm(){
		d = document.buscalog;
		//validar user
		if (d.circ.value == ""){
			alert("O circuito deve ser selecionado!");
			d.circ.focus();
			return false;
		}
		if (d.Idd.value == ""){
			alert("O campo - Dia de Inicio - deve ser selecionado!");
			d.Idd.focus();
			return false;
		}
		if (d.Imm.value == ""){
			alert("O campo - Mes de Inicio - deve ser selecionado!");
			d.Imm.focus();
			return false;
		}
		if (d.Fdd.value == ""){
			alert("O campo - Dia de Fim - deve ser selecionado!");
			d.Fdd.focus();
			return false;
		}
		if (d.Fmm.value == ""){
			alert("O campo - Mes do Fim - deve ser selecionado!");
			d.Fmm.focus();
			return false;
		}
		return true;
	}
</script>

</HEAD>
<BODY BACKGROUND='logo/fundoko.gif'>

<DIV ALIGN="left">
<FONT FACE="Verdana" SIZE="" COLOR="#004000"><B><?php echo $aplicacao." - 1 amostra por minuto"; ?></B></FONT>
</DIV>

       <table border="0" width="100%" cellpadding="5" CLASS="tdi" BGCOLOR="#FFCC99">
		<TR><TD>

<FORM ACTION="<?php echo $_SERVER['PHP_SELF'];?>" METHOD="GET" ENCTYPE="text" name="buscalog" onSubmit="return validaForm()"> 

<select size="1" name="uf" style="font-family: Verdana; font-size: 11px; background-color: #FAF8EB;" onChange="mudapagina(this);">
	<option selected value=<?php if($uf=="")$aux="Org&atilde;o"; else $aux=$uf; echo "'$uf'>$aux&nbsp;&nbsp;</option>"; ?>
        			
<?php

	$query = "SELECT DISTINCT uf FROM $tabela ORDER BY uf";
	$resultado = mysql_query($query);

	while( list($estados) = mysql_fetch_array($resultado, MYSQL_NUM))
	{
		echo 	"<option value='". $_SERVER['PHP_SELF']. "?uf=$estados'>&nbsp;$estados</option>\n";
	}
		echo "</select>";

	if($uf!="")
	{
		if($circ!=NULL)
		{	
			$query = "SELECT nome FROM $tabela WHERE cod_int='$circ'";
			$result = mysql_query($query);
			list($name) = mysql_fetch_row($result);
			$aux=$name; 
		}else $aux="Circuito";	
				
			$query = "SELECT cod_int, nome FROM $tabela WHERE uf='$uf' ORDER BY nome";
			$resultado2 = mysql_query($query);

			echo "&nbsp;
				<select size='1' name='circ' style=\"font-family: Verdana; font-size: 11px; background-color: #FAF8EB;\">\n
					<option selected value='$circ'>$aux&nbsp;</option>";
						
			while( list($cod_int, $nome) = mysql_fetch_array($resultado2, MYSQL_NUM))
			{
					$nome = str_replace(" (ATM)", "", $nome);
					echo 	"<option value='$cod_int'>&nbsp;$nome</option>\n";
			}
		echo "</select>";

///////////////////////////////////////////////////////////////////////////////////////////
?>

&nbsp;

       			<select size="1" name="Idd" style="font-family: Verdana; font-size: 11px; background-color: #FAF8EB;">
					<option selected value=<?php if($Idd=="")$aux="Dia"; else $aux=$Idd; echo "'$Idd'>$aux&nbsp;&nbsp;</option>"; ?>
					<option value="01">01</option>
        			<option value="02">02</option>
        			<option value="03">03</option>
        			<option value="04">04</option>
        			<option value="05">05</option>
        			<option value="06">06</option>
        			<option value="07">07</option>
        			<option value="08">08</option>
        			<option value="09">09</option>
        			<option value="10">10</option>
        			<option value="11">11</option>
        			<option value="12">12</option>
        			<option value="13">13</option>
        			<option value="14">14</option>
        			<option value="15">15</option>
        			<option value="16">16</option>
        			<option value="17">17</option>
        			<option value="18">18</option>
        			<option value="19">19</option>
        			<option value="20">20</option>
        			<option value="21">21</option>
        			<option value="22">22</option>
        			<option value="23">23</option>
        			<option value="24">24</option>
        			<option value="25">25</option>
        			<option value="26">26</option>
        			<option value="27">27</option>
        			<option value="28">28</option>
        			<option value="29">29</option>
        			<option value="30">30</option>
        			<option value="31">31</option>

        </select>
        <select size="1" name="Imm" style="font-family: Verdana; font-size: 11px; background-color: #FAF8EB;">
					<option selected value=<?php if($Imm=="")$aux="Mes"; else $aux=$Imm; echo "'$Imm'>$aux&nbsp;&nbsp;</option>"; ?>
        			<option value="01">01</option>
        			<option value="02">02</option>
        			<option value="03">03</option>
        			<option value="04">04</option>
        			<option value="05">05</option>
        			<option value="06">06</option>
        			<option value="07">07</option>
        			<option value="08">08</option>
        			<option value="09">09</option>
        			<option value="10">10</option>
        			<option value="11">11</option>
        			<option value="12">12</option>
        </select>
 		<select size="1" name="Iaa" style="font-family: Verdana; font-size: 11px; background-color: #FAF8EB;">
        			<option value="2007">2007</option>
					<option value="2006" selected>2006</option>
					<option value="2005">2005</option>
        			<option value="2004">2004</option>
        </select>

&nbsp;
<b><font face="Verdana" size="2">Início:</font></b>
       			<select size="1" name="h_inicio" style="font-family: Verdana; font-size: 11px; background-color: #FAF8EB;">
        			<option selected value=<?php if($Iperiodo==24) $h_inicio="00"; if($h_inicio=="")$aux3="07:00"; else $aux3=$h_inicio.":00"; echo "'$h_inicio'>$aux3&nbsp;&nbsp;</option>"; ?>
        			<option value="0">00:00</option>	
					<option value="01">02:00</option>
        			<option value="04">04:00</option>
        			<option value="06">06:00</option>
        			<option value="07">07:00</option>
        			<option value="08">08:00</option>
        			<option value="09">09:00</option>
        			<option value="10">10:00</option>
        			<option value="11">11:00</option>
        			<option value="12">12:00</option>
        			<option value="13">13:00</option>
        			<option value="14">14:00</option>
        			<option value="15">15:00</option>
        			<option value="16">16:00</option>
        			<option value="17">17:00</option>
        			<option value="18">18:00</option>
        			<option value="20">20:00</option>        			
        </select>
<b><font face="Verdana" size="3">+</font></b>        
        <select size="1" name="Iperiodo" style="font-family: Verdana; font-size: 11px; background-color: #FAF8EB;"">
					<option selected value=<?php if($Iperiodo=="")$aux3="Periodo"; else $aux3=$Iperiodo.":00"; echo "'$Iperiodo'>$aux3&nbsp;&nbsp;</option>"; ?>
        			<option value="02">02:00</option>
        			<option value="04">04:00</option>
        			<option value="06">06:00</option>
        			<option value="08">08:00</option>
        			<option value="10">10:00</option>
        			<option value="12">12:00</option>
        			<option value="24">dia</option>        			
        </select>

     

<?php

///////////////////////////////////////////////////////////////////////////////////////////
if ($Iperiodo == "") $Iperiodo =10;
if ($h_inicio == "") $h_inicio =7;
$dia 		= $Iaa."-".$Imm."-".$Idd." ";
$time 		= strtotime ($dia);
$dia_tela 	= date('d/m/Y',$time);
$hoje		= date('Y-m-d ');

$h_fim 		= $h_inicio + $Iperiodo;
if ($h_fim >= 24){
	$h_fim 	= "23:59:00";
}else $h_fim = $h_fim.":00:00";

if ($Iperiodo == 24){
	$h_fim = "23:59:00";
	$h_inicio = "00";
}
$i = 0;
$data_hora_inicio 	= $dia.$h_inicio.":00:00";			
$data_hora_fim 		= $dia.$h_fim;
		echo"
		 <INPUT TYPE=SUBMIT STYLE=\"font-family: Verdana; font-size: 11px; font-weight:bold; cursor: hand; width: 30;\" value='OK' name='Ok'>	
		";
?>

<input type="checkbox" name="mostra_cir" value="ON" checked>CIR
  </TD></TR></form></table>
  
<?php	

//**************
if ($circ=="") exit;

//****************************TRECHO QUE GERA O GRAFICO****************************
include ("jpgraph.php");
include ("jpgraph_line.php");

//VERIFICA SE SE HA REGISTRO NO BANCO  MYSQL PARA ESTA DATA
$query 		= "SELECT data_hora FROM $circ WHERE data_hora LIKE '$dia%' LIMIT 1";
$resultado 	= mysql_query($query, $conexao);
$existe 	= mysql_result($resultado,0);
if ($dia==$hoje){
    echo"<TR>
	    <table border='0' width='300' cellpadding='5' CLASS='tdi' BGCOLOR='#CCFFCC' ALIGN='center' HEIGHT='70'>
 		<BR><BR><BR>
		<TR><TD ALIGN='center' VALIGN='middle' ><FONT SIZE='+1'>Sem gráficos gerados para o dia de hoje.</FONT>
		</TD></TR>
        </table>
	";
	exit;	
}

if (($existe=="")){

    echo"<TR>
	    <table border='0' width='300' cellpadding='5' CLASS='tdi' BGCOLOR='#CCFFCC' ALIGN='center' HEIGHT='70'>
 		<BR><BR><BR>
		<TR><TD ALIGN='center' VALIGN='middle' ><FONT SIZE='+1'>Não há registro para o dia $dia_tela</FONT>
		</TD></TR>
        </table>";
	
	exit;	
}


$query1 = "SELECT nome,tecnologia,tipo,circuito,serial FROM rede_acesso WHERE cod_int = '$circ'";
$resultado1 = mysql_query($query1, $conexao);
$row1 		= mysql_fetch_row($resultado1);
$localidade = &$row1[0];
$tecno 		= strtoupper($row1[1]);
$tipo 		= strtoupper($row1[2]);
$circuito 	= strtoupper($row1[3]);
$serial		= strtoupper($row1[4]);
//apanha o cir in e cir out do dia consultado
$query = "SELECT cir_in,cir_out,hmm_hora_in,hmm_hora_out,7_19_pico_in,7_19_pico_out,
				cir_in_rec,cir_out_rec,volume_in,volume_out,hmm_hora_in_per,
				hmm_hora_out_per,7_19_media_in,7_19_media_out FROM performance_diaria 
			WHERE (dia = '$dia' and cod_int = '$circ')";
$resultado = mysql_query($query, $conexao);
while ($row = mysql_fetch_row($resultado)) {
    $cir_out 	= $row[0];
	$cir_in  	= $row[1];
	$time 		= strtotime ($row[2]);
	$hmm_out 	= Date('H:i',$time);
	$time 		= strtotime ($row[3]);
	$hmm_in 	= Date('H:i',$time);
	$max_out 	= $row[4];
	$max_in 	= $row[5];
	$cir_in_rec = $row[7];
	$cir_out_rec= $row[6];	
	$vol_in 	= number_format($row[9]/1000000, 1, ',', '.');
	$vol_out 	= number_format($row[8]/1000000, 1, ',', '.');
	$time 		= strtotime ($row[10]);
	$hmm_out_per= Date('H:i',$time);
	$time 		= strtotime ($row[11]);
	$hmm_in_per = Date('H:i',$time);
	$media_out 	= $row[12];
	$media_in 	= $row[13];
	}
$query=	"SELECT
		ROUND(MAX(volume_in)*0.008,1),
		ROUND(MAX(volume_out)*0.008,1),
		ROUND(AVG(volume_in)*0.008,1),
		ROUND(AVG(volume_out)*0.008,1)
		FROM $circ WHERE (data_hora >= '$data_hora_inicio') and (data_hora <= '$data_hora_fim') order by data_hora";
$resultado = mysql_query($query);
list($MaxVin, $MaxVout,$AVGin, $AVGout) = mysql_fetch_row($resultado);

$query = "SELECT volume_in, volume_out, data_hora FROM $circ 
			WHERE (data_hora >= '$data_hora_inicio') and (data_hora <= '$data_hora_fim') order by data_hora";

$resultado = mysql_query($query, $conexao);
while ($row = mysql_fetch_row($resultado)) {
	$ydata[$i] 	= 8*$row[0]/1000;
    $ydata2[$i] = 8*$row[1]/1000;
	$ydata3[$i] = $cir_in;
    $ydata4[$i] = $cir_out;
    $time 		= strtotime ($row[2]);
	$a[$i] 		= Date('H:i',$time);
	$i = $i + 1;
	}

mysql_free_result($resultado);
$dia_graf 	=	Date('d/m/Y',$time);
$pos_hmm_in = array_search ($hmm_in, $a);
$pos_hmm_out= array_search ($hmm_out, $a);
// Cria o gráfico
$graph = new Graph(750,210,"auto");	
//$graph = new Graph(750,280,"auto");	
$graph->SetScale("textlin");

$lineplot =new LinePlot($ydata);
$lineplot2=new LinePlot($ydata2);  
$lineplot3=new LinePlot($ydata3);  
$lineplot4=new LinePlot($ydata4);        
$graph->ygrid->Show(true,true);    //Todas as linhas de grade do eixo y
 

             
// Adiciona a linha ao grafico
$graph->Add($lineplot2);
$graph->Add($lineplot);

if (($tipo=="C")and( $tecno!="ETH")and($mostra_cir)){
	$graph->Add($lineplot3);
	$graph->Add($lineplot4);
}
if ($tipo!="C" ){
	$cir_out = "N.A.";
	$cir_in = "N.A.";	
}
$graph->img->SetMargin(40,20,20,50);
$dia_semana = Date('w',$time);
$graph->title->Set("Circuito $circuito ($serial) - $dia_graf ($semana[$dia_semana])");
//$graph->xaxis->title->Set("período");
$graph->yaxis->title->Set("kbps");
$graph->SetMarginColor("#F6F6F6");

$graph->title->SetFont(FF_FONT2,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT2,FS_BOLD);
$graph->legend->SetFont(FF_FONT1,FS_BOLD);

$lineplot->SetColor("green:0.8");
$lineplot->SetWeight(1);

$lineplot2->SetColor("blue");
$lineplot2->SetWeight(1);
//******Desenha a HMM out e in

if ($pos_hmm_out ) $lineplot->AddArea($pos_hmm_out,$pos_hmm_out+60,LP_AREA_FILLED,"green:0.8");
if ($pos_hmm_in) $lineplot2->AddArea($pos_hmm_in,$pos_hmm_in+60,LP_AREA_FILLED,"blue");

//*******************
$graph->yaxis->SetColor("red");
$graph->yaxis->SetWeight(2);

$graph->xaxis->SetTickLabels($a); 
$graph->xaxis->SetTextTickInterval(120*$Iperiodo/24,0);
$graph->xaxis->SetFont(FF_FONT1); 

// Determina legendas 
$lineplot->SetLegend("Max = $MaxVin   Média = $AVGin (kbps)");
$lineplot2->SetLegend("Max = $MaxVout   Média = $AVGout (kbps)        ");
$graph->legend->SetFrameWeight(0);
$graph->legend->SetShadow(0);
$graph->legend->Pos(0.18,0.99,"right","bottom");  // Adjusta posicao da legenda 
$graph->legend->SetFillColor("#F6F6F6");
$graph->legend->SetColumns(2);
// Cria linhas do Cir
$lineplot3->SetColor("blue");
$lineplot3->SetWeight(1);
$lineplot4->SetColor("green:0.4");
$lineplot4->SetWeight(1);


// Display the graph

$nome_graf = "diario/$circ" ."_" .  date('U') . ".png";
$graph->Stroke($nome_graf);


	}
if ($nome_graf != "")
{
	echo "
	<TABLE WIDTH='100%'>
	<TR>
		<TH><IMG SRC='$nome_graf'></TH>
	</TR>
	</TABLE>";	
}else{
     echo"<TR>
	   <table border='0' width='300' cellpadding='5' CLASS='tdi' BGCOLOR='#CCFFCC' ALIGN='center' HEIGHT='70'>
 		<BR><BR><BR>
		<TR><TD ALIGN='center' VALIGN='middle' ><FONT SIZE='+1'>Escolha o Org&atilde;o</FONT>
		</TD></TR>
        </table>
	";
}
if ($circ!=""){
echo ("
	<TABLE WIDTH='100%'  BORDER='1'  CELLSPACING='0'>
		<TR BGCOLOR='#FFFFCC'>
		<TD ALIGN='center' 'width'=89 style='border: solid; border-color: #FFFFFF' bgcolor='#FFFFFF'>
          <p align='right'>&nbsp;</TD>
		<TD ALIGN='center' 'width'=264 colspan=3><b>no Dia (00:00 às 24:00)</b></TD>
		<TD ALIGN='right' 'width'=351 colspan=4>
          <p align='center'><b>no Período (07:00 às 19:00)</b></TD>
	</TR>
	<TR BGCOLOR='#FFFFCC'>
		<TD ALIGN='center'>SENTIDO</TD>
		<TD ALIGN='center'>CIR</TD>
		<TD ALIGN='center'>Volume</TD>
		<TD ALIGN='center'>HMM</TD>
		<TD ALIGN='center' title='taxa de tráfego correspondente a 95% das amostras'>Tx_95%</TD>
		<TD ALIGN='center'>HMM</TD>
		<TD ALIGN='center'>Pico</TD>
		<TD ALIGN='center'>Média</TD>		
	</TR>
	<TR>	
		<TD><font color='#0000FF'><b>Saida da Datraprev</b></TD>
		<TD ALIGN='right'><font color='#0000FF'>$cir_in kbps</font></TD>
		<TD ALIGN='right'><font color='#0000FF'>$vol_in MB</TD>
		<TD ALIGN='right'><font color='#0000FF'>$hmm_in</TD>
		<TD ALIGN='right'><font color='#0000FF'>$cir_in_rec kbps</TD>
		<TD ALIGN='right'><font color='#0000FF'>$hmm_in_per</TD>
		<TD ALIGN='right'><font color='#0000FF'>$max_in kbps</TD>
		<TD ALIGN='right'><font color='#0000FF'>$media_in kbps</TD>
	</TR>
	<TR font color='#0099FF'>	
		<TD><font color='#009900'><b>Entrada na Datraprev</b></TD>
		<TD ALIGN='right'><font color='#009900'>$cir_out kbps</TD>
		<TD ALIGN='right'><font color='#009900'>$vol_out MB</TD>
		<TD ALIGN='right'><font color='#009900'>$hmm_out</TD>
		<TD ALIGN='right'><font color='#009900'>$cir_out_rec kbps</TD>
		<TD ALIGN='right'><font color='#009900'>$hmm_out_per</TD>
		<TD ALIGN='right'><font color='#009900'>$max_out kbps</TD>
				<TD ALIGN='right'><font color='#009900'>$media_out kbps</TD>
	</TR>
	</TABLE>			
		");	


}
?>

</BODY>
</HTML>
