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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"; HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">

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


<body background="imagens/fundoko.gif" LEFTMARGIN="8" RIGHTMARGIN="0" TOPMARGIN="6" BOTTOMMARGIN="0" onLoad="parent.lista.location.href='lista_entidade.php'">
<?php
//configuraco�es de conexao	
require ("config/conexao.php");

//funcao de cadastro 
function cadastra_ent($id, $desc) {
	$con=conexao();
	mysql_select_db("rede_acesso",$con) or die ("erro ao tentar usar o banco rede_acesso!");
	//echo  "select identificador from entidades where identificador=\"". $id . "\"";
	$res = mysql_query("select identificador from entidades where identificador=\"". $id . "\"",$con);
	while ($linha = mysql_fetch_array($res)) print ($linha['identificador']); 
	if ( mysql_num_rows($res) == 0 ) {
		//$res=mysql_query("insert into entidades ('identificador','nome_entidade') values (" . $id . "," . $desc),$con);
		//echo "executando: insert into entidades (identificador,nome_entidade) values ( \"$id\", \"$desc\")";
		$res=mysql_query("insert into entidades (identificador,nome_entidade) values ( \"$id\" , \"$desc\")",$con);
		if (mysql_affected_rows($res) <> 0) return 2;
		else return 0;
	}
	else return 1;
}


//-----------definicão interface
function set_interface($ident,$descr)
{
$interface ="<br/><br/><br/>\n
<center>\n
  <font color=\"#000000\" face=\"helvetica,arial\" size=\"-1\"><b>Cadastro de Entidades</b></font>\n
  <br/><br/><br/>\n
  <form name=\"form\" method=post action=\"cadastra_entidade.php\">\n
    <table border=0 cellspacing=2 cellpadding=2>\n
      <tr bgcolor=\"#ffffff\">\n
        <td colspan=2 align=\"center\">\n
        </td>\n
      </tr>\n
      <tr bgcolor=\"#ffffff\">\n
        <td align=\"left\"><font color=\"#000000\" face=\"helvetica,arial\" size=\"-1\">Identificador: </font></td>\n
	<td><input type=text name=identificador  value=\"" . $ident . "\" width=15></input></td>\n
      </tr>\n
      <tr bgcolor=\"#ffffff\">\n
        <td align=\"left\"><font color=\"#000000\" face=\"helvetica,arial\" size=\"-1\">Descri&ccedil;&atilde;o da entidade: </font></td>\n
        <td><input type=text name=desc_unidade value=\"" . $descr . "\" width=15></input></td>\n
      </tr>\n
      <tr bgcolor=\"#ffffff\">\n
	<td colspan=2 align=\"center\"><input type=submit name=\"cadastra\" value=\"Cadastrar\"></td>\n
      </tr>\n
    </table>\n
  </form>\n
</center>\n";

echo $interface;
}
//----------------codigo principal
//se não veio pelo submit
if (! isset($_POST['cadastra']) ) {
	$ident = "";
	$descr = "";
	set_interface($ident,$descr);
}
else {
	$ok=TRUE;
	//testa se campo foram enviados vazios
	if ( $_POST['identificador'] == '' ) {
        	$descr = $_POST['desc_unidade'];
		$msg .= "<center><br><font color='red'>Informe o identificador</font></center>\n";
		$ok=FALSE;
	}

	if ( $_POST['desc_unidade'] == '' ) {
	        $ident = $_POST['identificador'];
		$msg .= "<center><br><font color='red'>Informe a descri&ccedil;&atilde;o</font></center>\n";
		$ok=FALSE;
	}

	if  ( $ok ) 
	{
		//echo "cadastrar " . $_POST['identificador'] . " - " . $_POST['desc_unidade'];
		$result=cadastra_ent($_POST['identificador'], $_POST['desc_unidade']);
		switch ($result) 
		{
		  case 0:
			$msg = "<center><br><font color='blue'>" . $_POST['identificador'] . " Cadastrado com sucesso</font></center>\n";
		        $ident = "";
		        $descr = "";
			break;
		  case 1: 
		        $ident = $_POST['identificador'];
		        $descr = $_POST['desc_unidade'];
			$msg="<center><br><font color='red'>" . $_POST['identificador'] . " j&aacute; existe</font></center>\n";
			break;
		  case 2:
		        $ident = $_POST['identificador'];
		        $descr = $_POST['desc_unidade'];
			$msg="<center><br><font color='red'>Problemas na inser&ccedil;&atilde;o dos dados. <BR/> Poss&iacute;vel problema com o banco de dados. </font></center>\n";
                        break;
		  default:
		        $ident = $_POST['identificador'];
		        $descr = $_POST['desc_unidade'];
			 $msg="<center><br><font color='red'>Problema desconhecido. <BR/> Poss&iacute;vel problema com o banco de dados. </font></center>\n";
                         break;
		}
	}
	set_interface($ident,$descr);
	echo $msg;

}

?>
</BODY>
</HTML>
