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


error_reporting(0);
	$uf = $_GET["uf"];
?>

<HTML>
<HEAD>
<TITLE>Alertas - <?php echo $uf; ?></TITLE>
<meta http-equiv='refresh' content='60; URL=<?php echo $_SERVER['PHP_SELF'] . "?uf=" . $uf; ?>'>

<script language="JavaScript" type="text/JavaScript">
function janela()
	{
		open ("info/info.html", "janela", "status=no, width=407, height=220")
	}
//janela()

function MM_openBrWindow(theURL,winName,features) 
	{ 
		  window.open(theURL,winName,"width=700,height=460,resizable,scrollbars");
	}
</script>


<style type="text/css">
.topo
	{
		background-color: #006666; 
		border-width: 1px; 
		border-style: solid; 
		border-color: #FF9900; 
		padding-left: 1; 
		padding-right: 1; 
		padding-top: 2; 
		padding-bottom: 2; 
		FONT: 10pt Verdana, Arial; 
		FONT-WEIGHT: bold; 
		COLOR: #FFFFFF;
	}

.rly
	{
		background-color: #CEFFCE; 
		border-width: 1px; 
		border-style: solid; 
		border-color: #FF9900; 
		padding-left: 1; 
		padding-right: 1; 
		padding-top: 2; 
		padding-bottom: 2; 
		FONT: 7pt Verdana, Arial; 
		FONT-WEIGHT: bold; 
		COLOR: #00000;
	}

.alto1
	{	
		background-color: #80FFFF; 
		border-width: 1px; 
		border-style: solid; 
		border-color: #FF9900; 
		padding-left: 1; 
		padding-right: 1; 
		padding-top: 2; 
		padding-bottom: 2; 
		FONT: 7pt Verdana, Arial; 
		FONT-WEIGHT: bold; 
		COLOR: #000000;
	}

.alto2
	{
		background-color: #B3D9FF; 
		border-width: 1px; 
		border-style: solid; 
		border-color: #FF9900; 
		padding-left: 1; 
		padding-right: 1; 
		padding-top: 2; 
		padding-bottom: 2; 
		FONT: 7pt Verdana, Arial; 
		FONT-WEIGHT: bold; 
		COLOR: #000000;
	}

.zero1
	{
		background-color: #FFFFDD; 
		border-width: 1px; 
		border-style: solid; 
		border-color: #FF9900; 
		padding-left: 1; 
		padding-right: 1; 
		padding-top: 2; 
		padding-bottom: 2; 
		FONT: 7pt Verdana, Arial; 
		FONT-WEIGHT: bold; 
		COLOR: #0000BF;
	}

.zero2
	{
		background-color: #F0F000; 
		border-width: 1px; 
		border-style: solid; 
		border-color: #FF9900; 
		padding-left: 1; 
		padding-right: 1; 
		padding-top: 2; 
		padding-bottom: 2; 
		FONT: 7pt Verdana, Arial; 
		FONT-WEIGHT: bold; 
		COLOR: #0000BF;
	}

body
	{
		scrollbar-face-color: #FFFFFF; 
		scrollbar-shadow-color: #c0c0c0; 
		scrollbar-highlight-color: #FFFFFF; 
		scrollbar-3dlight-color: #c0c0c0; 
		scrollbar-darkshadow-color: #FFFFFF;
		scrollbar-track-color: #ffffff; 
		scrollbar-arrow-color: #c0c0c0;
		font-family: Verdana, Arial; 
		font-size: 12pt; 
	}

A:link
	{
		font-family: Verdana, Arial; 
		font-size: 8pt; 
		FONT-WEIGHT: bold; 
		COLOR: #000000; 
		TEXT-DECORATION: none; 
		CURSOR: hand;
	}

A:visited 
	{
		font-family: Verdana, Arial; 
		font-size: 8pt;
		FONT-WEIGHT: bold; 
		COLOR: #333333; 
		TEXT-DECORATION: none; 
		CURSOR: hand;
	}

A:active
	{
		font-family: Verdana, Arial; 
		font-size: 8pt; 
		FONT-WEIGHT: bold; 
		COLOR: #FF8000; 
		TEXT-DECORATION: none; 
		CURSOR: hand;
	}

A:hover
	{
		font-family: Verdana, Arial; 
		font-size: 8pt;
		FONT-WEIGHT: bold; 
		TEXT-DECORATION: none; 
		color:#FF0000; 
		CURSOR: hand;
	}
	
	
.result
	{
		border-width: 1px; 
		border-style: solid; 
		border-color: #F58735; 
		FONT-FAMILY: Verdana, Arial; 
		FONT-SIZE: 9pt; 
		FONT-WEIGHT: bold
	}

.thd 
	{
		background-color: #006666; 
		border-width: 1px; 
		border-style: solid; 
		border-color: #F58735; 
		FONT-FAMILY: Verdana, Arial; 
		FONT-SIZE: 9pt; 
		FONT-WEIGHT: bold; 
		COLOR: #FFFFFF; 
		ALIGN: center;
	}
	
</style>
</HEAD>
<BODY BACKGROUND="imagens/fundoko.gif">


<?php
error_reporting(0);
#####################################
	include("config/conexao.php");
	$conexao = conexao();
	mysql_select_db(BANCO);
#####################################


	/////////////////////// CONFIGURACOES /////////////////////////////////////////////////
	$query  = "SELECT IpServerMonitora FROM ". CONFIGURACOES . " WHERE codigo = '1'";
	$result = mysql_query($query);
	list ($IpServerMonitora) = mysql_fetch_array($result,MYSQL_NUM);

	define(LinkGraph, ("rrd_graph.php?log=") );
//	define(LinkGraph, ("http://" . $IpServerMonitora . "/cocar/teste/graph.php?log=") );
	//////////////////////////////////////////////////////////////////////////////////////	
	
	
	if($uf=="")
	{
		$consulta = "SELECT count(cod_int) FROM " . TABELA . " WHERE IfStatus='UP' AND gerencia!='Entidades Externas'";
		$analise = "Backbone";
	}
	else
	{	
		$consulta = "SELECT count(cod_int) FROM " . TABELA . " WHERE uf='$uf' AND IfStatus='UP' AND gerencia!='Entidades Externas'";
		$analise = nome_uf($uf);
	}
		
	$consulta = mysql_fetch_row(mysql_query($consulta));
	$Total = $consulta[0];


		
echo "<TABLE WIDTH='100%' CELLSPACING='3' BORDER='0'>
	<TR><TD ALIGN='left' VALIGN='middle'><H3>Alertas: <FONT COLOR='#FF0000'>$analise</FONT></H3></TD><TD ALIGN='right' VALIGN='middle'><A HREF='javascript:janela();'>Legenda&nbsp;&nbsp;<IMG SRC='imagens/info.gif' BORDER='0' TITLE='Clique para Informa��es.' ALIGN='middle'></A></TD></TR>";

	$qtd_A = ver_trafego("A", "Unidades que Apresentam Tr�fego Alto");
	$qtd_Z = ver_trafego("Z", "Unidades que N�o Apresentam Tr�fego");
	$qtd_RLY = ver_rly("Unidades com Problemas de Confiabilidade");
	$qtd_total = ($qtd_A + $qtd_Z) + $qtd_RLY;

echo "<BR>
	<P align='center'>
	<B>Total de Unidades com Alerta de Tr�fego Irregular<FONT COLOR='#FF3300'>&nbsp;&nbsp;$qtd_total <FONT COLOR='#000000'>de</FONT> $Total</FONT></B>
";


echo "
<table width='490' cellspacing='2' cellpadding='2' ALIGN='center'>
	<TR ALIGN='center' VALIGN='middle'>
		<TD CLASS='thd 	' >Tr�fego Alto</TD>
		<TD CLASS='thd' >Tr�fego Baixo</TD>
		<TD CLASS='thd' >Confiabilidade</TD>
	</TR>
	<TR ALIGN='center' VALIGN='middle'>
		<TD CLASS='result' >$qtd_A</TD>
		<TD CLASS='result' >$qtd_Z</TD>
		<TD CLASS='result'>$qtd_RLY</TD>
	</TR>
</table>
";


function ver_trafego($tipo, $INFO)
{
	global $uf;

	if ($tipo=="A")
	{
		$classe1 = "alto1";
		$classe2 = "alto2";
	}
	else
	{
		$classe1 = "zero1";
		$classe2 = "zero2";
	}

	if ($uf=="")
		$consulta = "SELECT cod_int, nome, orgao,  uf, history, IfAdminStatus, IfOperStatus FROM " . TABELA . " WHERE history LIKE '$tipo%' AND IfStatus='UP' AND gerencia!='Entidades Externas' ORDER BY uf";
	else
		$consulta = "SELECT cod_int, nome, orgao, uf, history, IfAdminStatus, IfOperStatus FROM " . TABELA . " WHERE history LIKE '$tipo%' AND uf='$uf' AND IfStatus='UP' AND gerencia!='Entidades Externas' ORDER BY nome";
		
	$matrix = mysql_query($consulta);
	
	$tipo .= "1";
	$x=1;
	$xl=1;
	$qtd_err=0;
	////////////////////////////////////////////////////
	echo "<table border=0 width='100%' CELLSPACING='4'>";
	////////////////////////////////////////////////////
			echo "<tr><TD height='26' align='center' valign='middle' CLASS='topo' COLSPAN='5'>$INFO</TD></tr>"; 
	while (list ($cod_int, $nome, $orgao,  $estado, $history, $IfAdminStatus, $IfOperStatus) = mysql_fetch_array($matrix,MYSQL_NUM))
	{
			$nome		= eregi_replace("APS ", "", $nome);

			$link 	= LinkGraph . $cod_int;

			if ($x == 1)
			{
				echo "<TR>";
				$xl++;
			}
		
			if($history == $tipo)
				$classe = $classe1; 
			else
				$classe = $classe2; 
	
			if($uf=="")
				$nome .= "/$estado";
		

				echo 
					"<TD width='25%' height='26' align='center' valign='middle' CLASS='$classe'>\n
					<A HREF='javascript:MM_openBrWindow(\"$link\",\"\")'>$nome</A>";		

		      $IfAdminStatus = strtoupper($IfAdminStatus);
		      $IfOperStatus  = strtoupper($IfOperStatus);

			if (eregi("Z", $tipo))
			{
                  	if($IfAdminStatus!="INAT")
					echo "<BR>Adm: $IfAdminStatus - Oper: $IfOperStatus";
				else
				      echo "<BR>Inacess&iacute;vel";
			}

				echo "</TD>";			


		$x++;
		$qtd_err++;
	
		if ($x == 5)
		{
			$x = 1;
			echo "</TR>";
		}
	} // FIM DO WHILE QUE PESQUISA NO BANCO

	////////////////////////////////////////////////////
	echo "</table><BR>";
	////////////////////////////////////////////////////

	return $qtd_err;
}

function ver_rly($INFO)
{
	global $uf;

	$classe = "rly";

/*
	echo $uf;
	echo TABELA;
*/

	if ($uf=="")
		$consulta = "SELECT cod_int, nome, orgao,  uf, rly FROM " . TABELA . " WHERE rly BETWEEN 0 AND 254 AND IfStatus='UP' AND gerencia!='Entidades Externas' AND gerencia !='Firewall' ORDER BY uf";
	else
		$consulta = "SELECT cod_int, nome, orgao, uf, rly FROM " . TABELA . " WHERE rly BETWEEN 0 AND 254 AND uf='$uf' AND IfStatus='UP' AND gerencia!='Entidades Externas' AND gerencia !='Firewall' ORDER BY nome";


	$matrix = mysql_query($consulta);
	
	$x=1; 	$xl=1; 	$qtd_err=0;
	////////////////////////////////////////////////////
	echo "<table border=0 width='100%' CELLSPACING='4'>";
	////////////////////////////////////////////////////
	echo "<tr><TD height='26' align='center' valign='middle' CLASS='topo' COLSPAN='5'>$INFO</TD></tr>"; 
	
	while (list ($cod_int, $nome, $orgao,  $estado, $rly) = mysql_fetch_array($matrix,MYSQL_NUM))
	{
			$nome		= eregi_replace("APS ", "", $nome);
		
			$link 	= LinkGraph . $cod_int . "&Rly=ON";

		if ($x == 1)
		{
			echo "<TR>";
			$xl++;
		}
		

		if($uf=="")
			$nome .= "/$estado";
		
	
		echo "<TD width='25%' height='26' align='center' valign='middle' CLASS='$classe'>\n
			<A HREF='javascript:MM_openBrWindow(\"$link\",\"\")'>$nome ($rly)</A></TD>";

		$x++;
		$qtd_err++;
	
		if ($x == 5)
		{
			$x = 1;
			echo "</TR>";
		}
	} // FIM DO WHILE QUE PESQUISA NO BANCO

	////////////////////////////////////////////////////
	echo "</table><BR>";
	////////////////////////////////////////////////////

	return $qtd_err;
}

function nome_uf($uf)
{
	$uf = strtoupper($uf);
	switch($uf)
	{
	case 'AC': $nome_uf = "Acre"; 				break;
	case 'AL': $nome_uf = "Alagoas"; 			break;
	case 'AM': $nome_uf = "Amazonas"; 			break;
	case 'AP': $nome_uf = "Amapa"; 				break;
	case 'BA': $nome_uf = "Bahia"; 				break;
	case 'CE': $nome_uf = "Ceara"; 				break;
	case 'DF': $nome_uf = "Distrito Federal"; 	break;
	case 'ES': $nome_uf = "Esp�rito Santo"; 	break;
	case 'GO': $nome_uf = "Goi�s"; 				break;
	case 'MA': $nome_uf = "Maranh�o"; 			break;
	case 'MG': $nome_uf = "Minas Gerais"; 		break;
	case 'MS': $nome_uf = "Mato Grosso Sul"; 	break;
	case 'MT': $nome_uf = "Mato Grosso";		break;
	case 'PA': $nome_uf = "Par�"; 				break;
	case 'PB': $nome_uf = "Para�ba"; 			break;
	case 'PE': $nome_uf = "Pernambuco"; 		break;
	case 'PI': $nome_uf = "Piau�";				break;
	case 'PR': $nome_uf = "Parana";				break;
	case 'RJ': $nome_uf = "Rio de Janeiro";		break;
	case 'RN': $nome_uf = "Rio Grande Norte";	break;
	case 'RO': $nome_uf = "Rond�nia"; 			break;
	case 'RR': $nome_uf = "Ror�ima"; 			break;
	case 'RS': $nome_uf = "Rio Grande do Sul"; 	break;
	case 'SC': $nome_uf = "Santa Catarina"; 	break;
	case 'SE': $nome_uf = "Sergipe"; 			break;
	case 'SP': $nome_uf = "S�o Paulo"; 			break;
	case 'TO': $nome_uf = "Tocantins"; 			break;
	}
	return $nome_uf;
}

mysql_close();

?>

</BODY>
</HTML>
