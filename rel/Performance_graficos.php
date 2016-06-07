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

#error_reporting(0);
	$uf		= $_GET["uf"];	
	$circ	= $_GET["circ"];	
	$Tipo	= $_GET["Tipo"];
include ("../config/conexao.php");
$conexao = conexao();
mysql_select_db("rede_acesso", $conexao);
$tabela = "rede_acesso";
	
$aplicacao = "Rede de Acesso - Performance Mensal dos Circuitos";
?>
<HTML>



<p><img border="0" src="logo/top_logo.gif" width="100" height="44"><img border="0" src="logo/top_dataprev.gif" width="456" height="44"><img border="0" src="logo/top_pontos.gif" width="198" height="44"></p>

<HEAD>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<TITLE>"Performance Mensal dos Circuitos"</TITLE>
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
	function AbrirNovaJanela(theURL,winName)
	{
		window.open(theURL,'winName',"width=800,height=450,resizable,scrollbars,status");
	}
	function AbrirNovaJanela2(theURL,winName)
	{
		window.open(theURL,'winName',"width=500,height=390,resizable,scrollbars");
	}
	function AbrirNovaJanela3(theURL,winName)
	{
		window.open(theURL,'winName',"width=600,height=330,resizable,scrollbars");
	}
	function validaForm(){
		d = document.buscalog;
		//validar user
		if (d.circ.value == ""){
			alert("O circuito deve ser selecionado!");
			d.circ.focus();
			return false;
		}
		if (d.Tipo.value == ""){
			alert("O Parâmetro deve ser selecionado!");
			d.Tipo.focus();
			return false;
		}
		return true;
	}
</script>

</HEAD>
<BODY>

<DIV ALIGN="left">
<FONT FACE="Verdana" SIZE="" COLOR="#004000"><B><?php echo $aplicacao; ?></B></FONT>
</DIV>

       <table border="0" width="100%" cellpadding="5" CLASS="tdi" BGCOLOR="#FFCC99">
		<TR><TD>
<?php
$URL = "portas_alto.php?localidade=$localidade&circ=$circ&sentido=Saida";
$Nome_do_Link = "<IMG SRC='logo/high.jpg' BORDER='0'>";
echo "<a title=' Clique para Concentradoras com alto Tráfego! ' href=\"javascript:AbrirNovaJanela3('$URL','Ajuda');\">$Nome_do_Link</a>";
?>
		</TD>
		<TD>
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

			echo "&nbsp;&nbsp;&nbsp;
				<select size='1' name='circ' style=\"font-family: Verdana; font-size: 11px; background-color: #FAF8EB;\">\n
					<option selected value='$circ'>$aux&nbsp;</option>";
						
			while( list($cod_int, $nome) = mysql_fetch_array($resultado2, MYSQL_NUM))
			{
					$nome = str_replace(" (ATM)", "", $nome);
					echo 	"<option value='$cod_int'>&nbsp;$nome</option>\n";
			}
		echo "</select>";
?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 		<select size="1" name="Tipo" style="font-family: Verdana; font-size: 11px; background-color: #FAF8EB;">
        			<option selected value=<?php if ($Tipo == ""){ $Tipo = "Taxa = 95% amostras";$aux = "Taxa = 95% amostras";} else $aux=$Tipo; echo "'$Tipo'>$aux";?> </option>
        			<option value="Taxa = 95% amostras">Taxa = 95% amostras</option>
					<option value="Volume">Volume</option>
        			<option value="HMM no Dia">HMM no Dia</option>
        			<option value="HMM no Período">HMM no Período</option>
        			<option value="Med_95%_Max - Entrada">Med_95%_Max - Entrada</option>
        			<option value="Med_95%_Max - Saida">Med_95%_Max - Saida</option>
        </select>
<?php	
	
$query1 = "SELECT nome,tecnologia,tipo,serial FROM rede_acesso WHERE cod_int = '$circ'";
$resultado1 = mysql_query($query1, $conexao);
$row1 		= mysql_fetch_row($resultado1);
$localidade = &$row1[0];
$serial		= $row1[3];
		echo"&nbsp;&nbsp;&nbsp;
		 <INPUT TYPE=SUBMIT STYLE=\"font-family: Verdana; font-size: 11px; font-weight:bold; cursor: hand; width: 40;\" value='OK' name='Ok'>	
		";
if ($circ=="") exit;

}

$ontem = mktime (0, 0, 0, date("m"), date("d")-1,  date("Y"));
$Idd = date('d',$ontem);
$Imm = date('m',$ontem);
$Iaa = date('Y',$ontem);

echo "
<TD VALIGN='middle'  ALIGN='center' WIDTH='50'>";
if($uf)
echo "<a href='trafego_1minuto.php?uf=$uf&circ=$circ&Idd=$Idd&Imm=$Imm&Iaa=$Iaa' title=' Clique para verificar Tráfego! ' target='_blank'>
<IMG SRC='logo/traf.jpg' ALIGN='right' BORDER='0' width='24' height='24'></a>";

?>




</TABLE>
</form>
<TABLE border="0" WIDTH="100%" cellspacing="0" CLASS="texto">

<?php if ($Tipo == "Taxa = 95% amostras") { ?>

	<TR ALIGN="right" VALIGN="middle" BGCOLOR="#FFCC99">
		<TD WIDTH="2%"></TD>
		<TD WIDTH="90%" >
			<b><FONT size="4">Taxa = 95% amostras - <?php echo $localidade." (".$serial.")";?></FONT></b></TD>
		<TD WIDTH="0%" ALIGN="right">
<?php
$URL = "help5.htm?localidade=$localidade&circ=$circ&sentido=Saida";
$Nome_do_Link = "<IMG SRC='logo/help1.gif' BORDER='0'>";
echo "<a title=' Clique para Ajuda! ' href=\"javascript:AbrirNovaJanela3('$URL','Ajuda');\">$Nome_do_Link</a>";
?>		</TD>
	</TR>
	<TR>
		<TD COLSPAN="0">&nbsp;</TD>
    </TR>
	<TR ALIGN="left">
		<TD WIDTH="50%" COLSPAN="2"><IMG BORDER="0" SRC="./diario/<?php echo $circ;?>cir_rec.png"></TD>
		<TD WIDTH="50%" COLSPAN="2"><IMG BORDER="0" SRC="./mensal/<?php echo $circ;?>cir_rec_m.png"></TD>
    </TR>
    
 <?php }elseif ($Tipo == "Volume") {?> 
 
	<TR ALIGN="right" VALIGN="middle" BGCOLOR="#FFCC99">
		<TD WIDTH="2%"></TD>
		<TD WIDTH="90%" >
			<b><FONT size="4">VOLUME TRAFEGADO - <?php echo $localidade." (".$serial.")";?></FONT></b></TD>
		<TD WIDTH="10%" ALIGN="right">
<?php
$URL = "help4.htm?localidade=$localidade&circ=$circ&sentido=Saida";
$Nome_do_Link = "<IMG SRC='logo/help1.gif' BORDER='0'>";
echo "<a title=' Clique para Ajuda! ' href=\"javascript:AbrirNovaJanela3('$URL','Ajuda');\">$Nome_do_Link</a>";
?>		</TD>
	</TR>
	<TR>
		<TD COLSPAN="4">&nbsp;</TD>
    </TR>
	<TR ALIGN="left">
		<TD WIDTH="50%" COLSPAN="2"><IMG BORDER="0" SRC="./diario/<?php echo $circ;?>volume.png"></TD>
		<TD WIDTH="50%" COLSPAN="2"><IMG BORDER="0" SRC="./mensal/<?php echo $circ;?>volume_m.png"></TD>
	</TR>
 <?php }elseif ($Tipo == "HMM no Dia") {?> 	

	<TR ALIGN="center" VALIGN="middle" BGCOLOR="#FFCC99">
		<TD WIDTH="2%"></TD>
		<TD WIDTH="90%" >
			<b><FONT size="4">HMM do Dia - <?php echo $localidade." (".$serial.")";?></FONT></b></TD>
		<TD WIDTH="6%" ALIGN="right">
<?php
$URL = "help3.htm?localidade=$localidade&circ=$circ&sentido=Saida";
$Nome_do_Link = "<IMG SRC='logo/help1.gif' BORDER='0'>";
echo "<a title='Clique para obter informações sobre: HMM no dia!' href=\"javascript:AbrirNovaJanela3('$URL','Ajuda');\">$Nome_do_Link</a>";
?>		
</TD>
	</TR>
	<TR>
		<TD COLSPAN="4">&nbsp;</TD>
    </TR>
	<TR ALIGN="left">
		<TD WIDTH="50%" COLSPAN="2"><IMG BORDER="0" SRC="./diario/<?php echo $circ;?>HMM.png"></TD>
	</TR>
	
	 <?php }elseif ($Tipo == "HMM no Período") {?> 	

	<TR ALIGN="center" VALIGN="middle" BGCOLOR="#FFCC99">
		<TD WIDTH="2%"></TD>
		<TD WIDTH="90%" >
			<b><FONT size="4">HMM de 07:00 às 19:00 - <?php echo $localidade." (".$serial.")";?></FONT></b></TD>
		<TD WIDTH="6%" ALIGN="right">
<?php
$URL = "help2.htm?localidade=$localidade&circ=$circ&sentido=Saida";
$Nome_do_Link = "<IMG SRC='logo/help1.gif' BORDER='0'>";
echo "<a title='Clique para obter informações sobre: HMM no Período!' href=\"javascript:AbrirNovaJanela3('$URL','Ajuda');\">$Nome_do_Link</a>";
?>		
		</TD>
	</TR>
	<TR>
		<TD COLSPAN="4">&nbsp;</TD>
    </TR>
	<TR ALIGN="left">
		<TD WIDTH="50%" COLSPAN="2"><IMG BORDER="0" SRC="./diario/<?php echo $circ;?>HMMper.png"></TD>
	</TR>
	
		 <?php }elseif ($Tipo == "Med_95%_Max - Entrada") {?> 	

	<TR ALIGN="center" VALIGN="middle" BGCOLOR="#FFCC99">
		<TD WIDTH="2%"></TD>
		<TD WIDTH="90%" >
			<b><FONT size="4">Comparativo Media / 95% / Máximo - ENTRADA em <?php echo $localidade." (".$serial.")";?></FONT></b></TD>
		<TD WIDTH="6%" ALIGN="right">
<?php
$URL = "tabela.php?localidade=$localidade&circ=$circ&sentido=Entrada";
$Nome_do_Link = "<IMG SRC='logo/tabela.jpg' BORDER='0'>";
echo "<a title=' Clique para Tabela referente ao gráfico! ' href=\"javascript:AbrirNovaJanela2('$URL','$NomedaJanela');\">$Nome_do_Link</a>";
?>	
		</TD>
		<TD WIDTH="50" ALIGN="right">		
<?php
$URL = "help1.htm?localidade=$localidade&circ=$circ&sentido=Saida";
$Nome_do_Link = "<IMG SRC='logo/help1.gif' BORDER='0'>";
echo "<a title=' Clique para Ajuda! ' href=\"javascript:AbrirNovaJanela3('$URL','Ajuda');\">$Nome_do_Link</a>";
?>		
		</TD>		
	</TR>
	<TR>
		<TD COLSPAN="4">&nbsp;</TD>
    </TR>
	<TR ALIGN="left">
		<TD WIDTH="50%" COLSPAN="2"><IMG BORDER="0" SRC="./diario/<?php echo $circ;?>out_m95M.png"></TD>
	</TR>
			 <?php }elseif ($Tipo == "Med_95%_Max - Saida") {?> 	

	<TR ALIGN="center" VALIGN="middle" BGCOLOR="#FFCC99">
		<TD WIDTH="2%"></TD>
		<TD WIDTH="90%" >
			<b><FONT size="4">Comparativo Media / 95% / Máximo - SAÍDA de <?php echo $localidade." (".$serial.")";?></FONT></b></TD>
		<TD WIDTH="50" ALIGN="left">
			
<?php
$URL = "tabela.php?localidade=$localidade&circ=$circ&sentido=Saida";
$Nome_do_Link = "<IMG SRC='logo/tabela.jpg' BORDER='0'>";
echo "<a title=' Clique para Tabela referente ao gráfico! ' href=\"javascript:AbrirNovaJanela2('$URL','$NomedaJanela');\">$Nome_do_Link</a>";
?>		
		</TD>
		<TD WIDTH="50" ALIGN="right">
			
<?php
$URL = "help1.htm?localidade=$localidade&circ=$circ&sentido=Saida";
$Nome_do_Link = "<IMG SRC='logo/help1.gif' BORDER='0'>";
echo "<a title=' Clique para Ajuda! ' href=\"javascript:AbrirNovaJanela3('$URL','Ajuda');\">$Nome_do_Link</a>";
?>		
		</TD>
	</TR>
	<TR>
		<TD COLSPAN="4">&nbsp;</TD>
    </TR>
	<TR ALIGN="left">
		<TD WIDTH="50%" COLSPAN="2"><IMG BORDER="0" SRC="./diario/<?php echo $circ;?>in_m95M.png"></TD>
	</TR>

 <?php } ?>  
 
 
  
</TABLE>
 <?php if ($circ=="") 
 	echo "<TR>
	   <table border='0' width='500' cellpadding='5' CLASS='tdi' BGCOLOR='#CCFFCC' ALIGN='center' HEIGHT='70'>
 		<BR><BR><BR>
		<TR><TD ALIGN='center' VALIGN='middle' ><FONT SIZE='+1'>Selecione 'Concentradoras com Alto Tráfego' ou 'Org&atilde;o' para indicadores de um Org&atilde;o</FONT>
		</TD></TR>
        </table>
	";  
 ?>  

</BODY>
</HTML>

