<html>
<!--
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
-->

<head>
<title>Rede de Acesso</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<style type="text/css">
.caixa
	{
		BORDER-RIGHT: #F58735 1px solid;
		BORDER-TOP: #F58735 1px solid;
		BORDER-LEFT: #F58735 1px solid;
		BORDER-BOTTOM: #F58735 1px solid;
		FONT-SIZE: 10px;
		FONT-FAMILY: Verdana, Arial;
		width:100px;
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
		COLOR: #1A0300;
		TEXT-DECORATION: none;
		CURSOR: hand;
	}

A:active
	{
		font-family: Verdana, Arial;
		font-size: 8pt;
		FONT-WEIGHT: bold;
		COLOR: #0000FF;
		TEXT-DECORATION: none;
		CURSOR: hand;
	}

A:hover
	{
		font-family: Verdana, Arial;
		font-size: 8pt;
		FONT-WEIGHT: bold;
		TEXT-DECORATION: none;
		color:#F58735;
		CURSOR: hand;
	}

.thd
	{
		background-color: #007700;
		border-width: 1px;
		border-style: solid;
		border-color: #F58735;
		FONT-FAMILY: Verdana, Arial;
		FONT-SIZE: 9pt;
		FONT-WEIGHT: bold;
		COLOR: #FFFFFF;
	}

.impar
	{
		background-color: #FFFFFF;
		border-width: 1px;
		border-style: solid;
		border-color: #F58735;
		FONT-FAMILY: Verdana, Arial;
		FONT-SIZE: 8pt;
		FONT-WEIGHT: bold
	}

.par
	{
		background-color: #EEEEE6;
		border-width: 1px;
		border-style: solid;
		border-color: #F58735;
		FONT-FAMILY: Verdana, Arial;
		FONT-SIZE: 8pt;
		FONT-WEIGHT: bold
	}

.tab
	{
		border: #F58735 2px solid;
	}
</style>

<script language="javascript">
function muda_pagina(file1,file2)
{
	parent.frames[1].location.href=file1;
	parent.frames[2].location.href=file2;
}

</script>

</HEAD>


<body background="imagens/fundoko.gif" LEFTMARGIN="8" RIGHTMARGIN="0" TOPMARGIN="6" BOTTOMMARGIN="0">

<p align='center'><FONT FACE="Verdana" SIZE="+2" COLOR="#007700"><B>Totaliza&ccedil;&atilde;o de Desempenho</B></FONT></p>

<table width='550' cellspacing='2' cellpadding='1' CLASS='tab' bgcolor='#F3FAE9' ALIGN="center">
	<TR ALIGN='center' VALIGN='middle'>
		<TD CLASS='thd' width='130' HEIGHT="25">Estado</TD>
		<TD CLASS='thd' width='110'>Tr�fego Alto</TD>
		<TD CLASS='thd' width='110'>Tr�fego Baixo</TD>
		<TD CLASS='thd' width='110'>Confiabilidade</TD>
		<TD CLASS='thd' width='90'>Total</TD>
	</TR>

<?php
#error_reporting(0);
//conexao com o banco
#####################################
	include("config/conexao.php");
	$conexao = conexao();
	mysql_select_db(BANCO);
#####################################

//variaveis de totalizacao de alarmes
	$TotalInterfaces= 0;
	$TotalAlarmes	= 0;
	$TotalAlto	= 0;
	$TotalNulo	= 0;
	$TotalRly	= 0;

## Total de Interfaces
$sql=
	"
	SELECT uf, COUNT(*) AS Interfaces
	FROM " . TABELA . "
	WHERE IfStatus='UP' 
	group by uf"
	;

	$resultado = mysql_query($sql);

	while( list($uf, $Interfaces) = mysql_fetch_array($resultado, MYSQL_NUM))
	{
		$estados[$uf]['Interfaces'] = $Interfaces;
		$estados[$uf]['A'] = 0;
		$estados[$uf]['Z'] = 0;
		$estados[$uf]['Rly'] = 0;

		$TotalInterfaces+= $Interfaces;
	}

## Total do Reliability
$sql=
	"
	SELECT uf, COUNT(*) AS Total
	FROM " . TABELA . "
	WHERE  IfStatus='UP' AND (rly BETWEEN 0 AND 254)
	group by uf"
	;

	$resultado = mysql_query($sql);
	while( list($uf, $Rly) = mysql_fetch_array($resultado, MYSQL_NUM))
	{
		$estados[$uf]['Rly'] = $Rly;
	}

## Total de Altos e Nulos

$sql=
	"
	SELECT uf, replace(replace( group_concat(UPPER(history)), '1', ''), '2','') as Meus
	FROM " . TABELA . "
	WHERE history!='n' AND IfStatus='UP'
	group by uf"
	;

	$resultado = mysql_query($sql);

	while( list($uf, $Array) = mysql_fetch_array($resultado, MYSQL_NUM))
	{
		$aux= explode(',',$Array);
		$alarme= array_count_values($aux);

		if($alarme['A'])
                		$estados[$uf]['A'] = $alarme['A'];
		if($alarme['Z'])
				$estados[$uf]['Z'] = $alarme['Z'];
	}

$query = "select distinct uf as estado from ". TABELA;
$result = mysql_query($query, $conexao);


//Impressão do dados da tabela
$x=0; //flag para impresã oalternada de cores de linhas
$test = mysql_num_rows($result);
while ($est = mysql_fetch_assoc($result))
	foreach ($est as $orgao => $uf)
	{
		$query2 = "select nome_entidade from entidades where identificador='" . $uf . "'";
		$result2 = mysql_query($query2,$conexao);
		$nome_ent = mysql_fetch_assoc($result2);
		$nome_uf = $nome_ent['nome_entidade'];

     		$Total= $estados[$uf]['Interfaces'];
		$Alto= $estados[$uf]['A'];
		$Nulo= $estados[$uf]['Z'];
		$Rly= $estados[$uf]['Rly'];


		$AlarmesUF= ($Alto + $Nulo) + $Rly;
            	$TotalAlarmes += $AlarmesUF;

		$TotalAlto	+= $Alto;
		$TotalNulo	+= $Nulo;
		$TotalRly	+= $Rly;

		$resp= $x % 2;
		$x++;
		if( $resp==0 )
			$classe = "par";
		else
			$classe = "impar";
	
		echo "
			<TR ALIGN='center' VALIGN='middle'>
				<TD CLASS='$classe' ALIGN='left'>
				<a href=\"javascript:muda_pagina('mn_aps.php?uf=$uf','verifica_alarme.php?uf=$uf');\">$nome_uf</a>
				</TD>
				<TD CLASS='$classe' >$Alto</TD>
				<TD CLASS='$classe' >$Nulo</TD>
				<TD CLASS='$classe'>$Rly</TD>
				<TD CLASS='$classe' ALIGN='right'>$AlarmesUF/$Total</TD>
			</TR>";
	} //fim_do foreach

//imprime rodapé
	echo "
		<TR ALIGN='center' VALIGN='middle'>
			<TD CLASS='thd'>TOTAL</TD>
			<TD CLASS='thd' >$TotalAlto</TD>
			<TD CLASS='thd'>$TotalNulo</TD>
			<TD CLASS='thd' >$TotalRly</TD>
			<TD CLASS='thd' ALIGN='right'>$TotalAlarmes/$TotalInterfaces</TD>
		</TR>";



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

mysql_close() or die ('nao desconectou');


?>

</table><BR>
</BODY>
</HTML>
