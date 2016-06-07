<html>
<!--
/*
    Este arquivo � parte do programa COCAR



    COCAR � um software livre; voc� pode redistribui-lo e/ou

    modifica-lo dentro dos termos da Licen�a P�blica Geral GNU como

    publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da

    Licen�a.nt



    Este programa � distribuido na esperan�a que possa ser  util,66"

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
<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">

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


<body background="imagens/fundoko.gif" LEFTMARGIN="8" RIGHTMARGIN="0" TOPMARGIN="6" BOTTOMMARGIN="0" onLoad="parent.lista.location.href='lista_circuito.php'">
<?php
//configuraco�es de conexao	
require ("config/conexao.php");

//funcao de cadastro 
function cadastra_circ($circ) {
	$con=conexao();
	mysql_select_db("rede_acesso",$con) or die ("erro ao tentar usar o banco rede_acesso!");
	$res = mysql_query("select cod_int from rede_acesso where cod_int=\"". $circ['cod_int'] . "\"",$con);
	if ( mysql_num_rows($res) == 0 ) {
		$res=mysql_query("insert into rede_acesso (cod_int, nome, orgao, gerencia, uf, host, community, serial, tecnologia ,tipo, num_int, ip_orig, circuito, porta, cir_in, cir_out, serial_dest, num_int_dest, community_dest, host_dest) values ( \"" . $circ['cod_int'] . "\", \"" . $circ['nome'] . "\",\"" .  $circ['orgao'] . "\",\"" .  $circ['gerencia'] . "\",\"" .  $circ['orgao'] . "\",\"" .  $circ['host'] . "\",\"" .  $circ['community'] . "\",\"" .  $circ['serial'] . "\", \"" . $circ['tecnologia'] . "\" ,\"" . $circ['tipo'] . "\",\"" .  $circ['num_int'] . "\",\"" .  $circ['ip_orig'] . "\",\"" .  $circ['circuito'] . "\",\"" .  $circ['porta'] . "\",\"" .  $circ['cir_in'] . "\",\"" .  $circ['cir_out'] . "\",\"" .  $circ['serial_dest'] . "\",\"" .  $circ['num_int_dest'] . "\",\"" .  $circ['community_dest'] . "\",\"" .  $circ['host_dest'] . "\")" ,$con);
		if (mysql_affected_rows($res) <> 0) return 2;
		else return 0;
	}
	else return 1;
}

//funcao para gerar combos
function gera_combo($nome,$tabela,$chave,$descr,$marcar='')
{
	$con=conexao();
	mysql_select_db("rede_acesso",$con) or die ("erro ao tentar usar o banco rede_acesso!");
	$res=mysql_query("select $chave,$descr from $tabela",$con);
        if (mysql_num_rows($res) <> 0)
        {
		$combo = "<select name=" . $nome . ">\n";
		while( $linha = mysql_fetch_array($res))
		{
			if ($marcar == $linha[$chave]) $marca="SELECTED";
			else $marca="";
			$combo .= "<option " . $marca .  " value=" . $linha["$chave"] .">" . $linha["$descr"] . "</option>\n";
		}
		$combo .= "</select>\n";
	}
	mysql_close($con);
	return $combo;
}

function gera_radio($nome,$op,$marcar='')
{
	foreach ($op as $opt)
	{
		list($valor,$opcao) = explode(":", $opt);
		if ($valor == $marcar) $marca="CHECKED";
		else $marca="";
		$radio.="<input type=radio name=$nome  value='$valor' $marca>$opcao</input>\n";
	}
	return $radio;
}

//-----------definicão interface
function set_interface($circ)
{

$combo_entidade = gera_combo('orgao','entidades','identificador','nome_entidade');
$radio_tipo_circ = gera_radio("tipo",array("p:porta","c:circuito"),strtolower($circ['tipo']));

$interface ="<br/>\n
<center>\n
  <font color=\"#000000\" face=\"helvetica,arial\" size=\"-1\"><b>Cadastro de Circuitos</b></font>\n
  <br/>\n
  <form name=\"form\" method=post action=\"cadastra_circuito.php\">\n
    <table border=0 cellspacing=2 cellpadding=2>\n
      <tr bgcolor=\"#ffffff\">\n
        <td colspan=2 align=\"center\">\n
        </td>\n
      </tr>\n
      <tr bgcolor=\"#ffffff\">\n
        <td align=\"left\"><font color=\"#000000\" face=\"helvetica,arial\" size=\"-1\">C&oacute;digo da Interface: </font></td>\n
	<td><input type=text name=cod_int  value=\"" . $circ['cod_int'] . "\" width=10></input></td>\n
      </tr>\n
      <tr bgcolor=\"#ffffff\">\n
        <td align=\"left\"><font color=\"#000000\" face=\"helvetica,arial\" size=\"-1\">Descri&ccedil;&atilde;o do circuito: </font></td>\n
        <td><input type=text name=nome value=\"" . $circ['nome'] . "\" width=10></input></td>\n
      </tr>\n
      <tr bgcolor=\"#ffffff\">\n
        <td align=\"left\"><font color=\"#000000\" face=\"helvetica,arial\" size=\"-1\">Entidade: </font></td>\n
	<td>" . $combo_entidade . "</td>\n

      </tr>\n
      <tr bgcolor=\"#ffffff\">\n
        <td align=\"left\"><font color=\"#000000\" face=\"helvetica,arial\" size=\"-1\">Gerencia: </font></td>\n
	<td><input type=text name=gerencia  value=\"" . $circ['gerencia'] . "\" width=10></input></td>\n
      </tr>\n
      <tr bgcolor=\"#ffffff\">\n
        <td align=\"left\"><font color=\"#000000\" face=\"helvetica,arial\" size=\"-1\">IP rotedor backbone: </font></td>\n
	<td><input type=text name=host  value=\"" . $circ['host'] . "\" width=10></input></td>\n
      </tr>\n
      <tr bgcolor=\"#ffffff\">\n
        <td align=\"left\"><font color=\"#000000\" face=\"helvetica,arial\" size=\"-1\">Community snmp do roteador bacbone: </font></td>\n
	<td><input type=text name=community  value=\"" . $circ['community'] . "\" width=10></input></td>\n
      </tr>\n
      <tr bgcolor=\"#ffffff\">\n
        <td align=\"left\"><font color=\"#000000\" face=\"helvetica,arial\" size=\"-1\">Serial no roteador backbone: </font></td>\n
	<td><input type=text name=serial  value=\"" . $circ['serial'] . "\" width=10></input></td>\n
      </tr>\n
      <tr bgcolor=\"#ffffff\">\n
        <td align=\"left\"><font color=\"#000000\" face=\"helvetica,arial\" size=\"-1\">Tecnologia: </font></td>\n
	<td><input type=text name=tecnologia  value=\"" . $circ['tecnologia'] . "\" width=10></input></td>\n
      </tr>\n
      <tr bgcolor=\"#ffffff\">\n
        <td align=\"left\"><font color=\"#000000\" face=\"helvetica,arial\" size=\"-1\">Tipo da Interface: </font></td>\n
	<td>$radio_tipo_circ</td>\n
      </tr>\n
      <tr bgcolor=\"#ffffff\">\n
        <td align=\"left\"><font color=\"#000000\" face=\"helvetica,arial\" size=\"-1\">Ni&uacute;mero SNMP da interface: </font></td>\n
	<td><input type=text name=num_int  value=\"" . $circ['num_int'] . "\" width=10></input></td>\n
      </tr>\n
      <tr bgcolor=\"#ffffff\">\n
        <td align=\"left\"><font color=\"#000000\" face=\"helvetica,arial\" size=\"-1\">IP da serial da Interface: </font></td>\n
	<td><input type=text name=ip_orig  value=\"" . $circ['ip_orig'] . "\" width=10></input></td>\n
      </tr>\n
      <tr bgcolor=\"#ffffff\">\n
        <td align=\"left\"><font color=\"#000000\" face=\"helvetica,arial\" size=\"-1\">Cadastro do circuito: </font></td>\n
	<td><input type=text name=circuito  value=\"" . $circ['circuito'] . "\" width=10></input></td>\n
      </tr>\n
      <tr bgcolor=\"#ffffff\">\n
        <td align=\"left\"><font color=\"#000000\" face=\"helvetica,arial\" size=\"-1\">Velocidade: </font></td>\n
	<td><input type=text name=porta  value=\"" . $circ['porta'] . "\" width=10></input></td>\n
      </tr>\n
      <tr bgcolor=\"#ffffff\">\n
        <td align=\"left\"><font color=\"#000000\" face=\"helvetica,arial\" size=\"-1\">CIR In: </font></td>\n
	<td><input type=text name=cir_in  value=\"" . $circ['cir_in'] . "\" width=10></input></td>\n
      </tr>\n
      <tr bgcolor=\"#ffffff\">\n
        <td align=\"left\"><font color=\"#000000\" face=\"helvetica,arial\" size=\"-1\">CIR Out: </font></td>\n
	<td><input type=text name=cir_out  value=\"" . $circ['cir_out'] . "\" width=10></input></td>\n
      </tr>\n
      <tr bgcolor=\"#ffffff\">\n
        <td align=\"left\"><font color=\"#000000\" face=\"helvetica,arial\" size=\"-1\">Serial no roteador da ponta: </font></td>\n
	<td><input type=text name=serial_dest  value=\"" . $circ['serial_dest'] . "\" width=10></input></td>\n
      </tr>\n
      <tr bgcolor=\"#ffffff\">\n
        <td align=\"left\"><font color=\"#000000\" face=\"helvetica,arial\" size=\"-1\">Identifica&ccedil;&atilde;o SNMP da porta na ponta: </font></td>\n
	<td><input type=text name=num_int_dest  value=\"" . $circ['num_int_dest'] . "\" width=10></input></td>\n
      </tr>\n
      <tr bgcolor=\"#ffffff\">\n
        <td align=\"left\"><font color=\"#000000\" face=\"helvetica,arial\" size=\"-1\">Community SNMP do roteador da ponta: </font></td>\n
	<td><input type=text name=community_dest  value=\"" . $circ['community_dest'] . "\" width=10></input></td>\n
      </tr>\n
      <tr bgcolor=\"#ffffff\">\n
        <td align=\"left\"><font color=\"#000000\" face=\"helvetica,arial\" size=\"-1\"> IP da serial do roteador da ponta: </font></td>\n
	<td><input type=text name=host_dest  value=\"" . $circ['host_dest'] . "\" width=10></input></td>\n
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
       $circuito = array(
                        "cod_int" => '',
                        "nome" => '',
                        "orgao" => '',
                        "gerencia" => '',
                        "uf" => '',
                        "host" => '',
                        "community" => '',
                        "serial" => '',
                        "tecnologia" => '',
                        "tipo" => '',
                        "num_int" => '',
                        "ip_orig" => '',
                        "circuito" => '',
                        "porta" => '',
                        "cir_in" => '',
                        "cir_out" => '',
                        "serial_dest" => '',
                        "num_int_dest" => '',
                        "community_dest" => '',
                        "host_dest" => ''
        );

	set_interface($circuito);
}
else {
	$ok=TRUE;
        if ( $_POST['cod_int'] == '' ) {
                $circuito = $_POST;
                $msg .= "<center><br><font color='red'>C&oacute;digo da interface n&atilde;o pode ser vazio</font></center>\n";
                $ok=FALSE;
        }

        if  ( $ok )
        {
                $result=cadastra_circ($_POST);
                switch ($result)
                {
                  case 0:
                        $msg = "<center><br><font color='blue'>" . $_POST['cod_int'] . " cadastrado com sucesso</font></center>\n";
                        $circuito = array(
                        "cod_int" => '',
                        "nome" => '',
                        "orgao" => '',
                        "gerencia" => '',
                        "uf" => '',
                        "host" => '',
                        "community" => '',
                        "serial" => '',
                        "tecnologia" => '',
                        "tipo" => '',
                        "num_int" => '',
                        "ip_orig" => '',
                        "circuito" => '',
                        "porta" => '',
                        "cir_in" => '',
                        "cir_out" => '',
                        "serial_dest" => '',
                        "num_int_dest" => '',
                        "community_dest" => '',
                        "host_dest" => ''
                        );
                        break;
                  case 2:
                        $msg="<center><br><font color='red'>Problemas no cadastro dos dados. <BR/> Poss&iacute;vel problema com o banco de dados. </font></center>\n";
			$circuito=$_POST;
			break;
                  default:
                        $msg="<center><br><font color='red'>Problema desconhecido. <BR/> Poss&iacute;vel problema com o banco de dados. </font></center>\n";
			$circuito=$_POST;
                         break;
                }
        }
	set_interface($circuito);
	echo $msg;

}

?>
</BODY>
</HTML>
