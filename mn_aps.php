<?php
/*
    Este arquivo é parte do programa COCAR



    COCAR é um software livre; você pode redistribui-lo e/ou 

    modifica-lo dentro dos termos da Licença Pública Geral GNU como 

    publicada pela Fundação do Software Livre (FSF); na versão 2 da 

    Licença.



    Este programa é distribuido na esperança que possa ser  util, 

    mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUAÇÂO a qualquer

    MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a

    Licença Pública Geral GNU para maiores detalhes (GPL2.txt).



    Você deve ter recebido uma cópia da Licença Pública Geral GNU

    junto com este programa, se não, escreva para a Fundação do Software

    Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/
        if ( isset($_GET["uf"]) ) $uf = $_GET["uf"];
	else $uf = "";

	if ( isset($_GET["ger"]) ) $ger = $_GET["ger"];
	else $ger = "";

	if ( isset($_GET["serial"]) ) $serial = $_GET["serial"];
	else $serial = "";
	if ( isset($_GET["host"]) ) $host = $_GET["host"];
	else $host = "";

	if ( isset($_POST["objeto"]) ) $objeto = $_POST["objeto"];
	else $objeto = "";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<style type="text/css">
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
A:link
	{
		font-family: Arial, Verdana; 
		font-size: 8pt; 
		FONT-WEIGHT: bold; 
		COLOR: #000000; 
		TEXT-DECORATION: none; 
		CURSOR: hand;
	}
A:visited
	{
		font-family: Arial, Verdana; 
		font-size: 8pt; 
		FONT-WEIGHT: bold; 
		COLOR: #1A0300; 
		TEXT-DECORATION: none; 
		CURSOR: hand;
	}
A:active
	{
		font-family: Arial, Verdana; 
		font-size: 8pt; 
		FONT-WEIGHT: bold; 
		COLOR: #0000FF; 
		TEXT-DECORATION: none; 
		CURSOR: hand;
	}
A:hover
	{
		font-family: Arial, Verdana; 
		font-size: 8pt; 
		FONT-WEIGHT: bold; 
		TEXT-DECORATION: none; 
		color:#F58735; 
		CURSOR: hand;
	}
.caixa
	{
		BORDER-COLOR: #F58735; 
		BORDER-STYLE: solid; 
		BORDER-WIDTH: 1px; 
		FONT-SIZE: 10px; 
		FONT-FAMILY: Verdana, Arial; 
		width:102px;
	}
.laterais
	{
		border-left: #F58735 1px solid; 
		border-right: #F58735 1px solid; 
		border-bottom: #F58735 1px solid; 
		FONT-FAMILY: Arial, Verdana; 
		FONT-SIZE: 8pt; 
		FONT-WEIGHT: bold
	}
.final 
	{
		border-bottom: #F58735 1px solid;
	}
.tht
	{
		FONT-FAMILY: Arial, Verdana; 
		FONT-SIZE: 9pt; 
		FONT-WEIGHT: bold; 
		TEXT-VALGIN: middle;
	}
.OnLine
	{
		font-family: Verdana;
		font-size: 7pt;
		color: #000080;
		font-weight: bold;
	}
</style>

<!-- #######################   VALIDA FORM DE PESQUISA  ############################## -->
<SCRIPT LANGUAGE="JavaScript">
function validaForm(){
	d = document.cadastro;
	s =d.objeto.value;
	parte = d.objeto.value.length;
	if (parte < 3)
	{
		alert ("O nome para procura,\ndeve possuir no mínimo 4 letras!");
        d.objeto.focus();
        return false;
	}
    if(s == "Faça sua busca")
    {
		alert ("Digite o nome para procura!");
        d.objeto.focus();
        return false;    
	}
	return true;
}
</SCRIPT>

<script>
var flg1=true;
var flg2=true;
var txtorig1; 
var txtorig2; 
function inn(txt,i) 
{
	if (eval('flg'+i))
	{
	  	eval('txtorig'+i+'=txt.value;');
	  	eval('flg'+i+'=false;');
	}

	if (txt.value == eval('txtorig'+i)) txt.value = '';
}

function out(txt,i)
{
	if (txt.value == '') txt.value = eval('txtorig'+i);
}
</script>
</HEAD>
	  
<body background="imagens/fundoko.gif" LEFTMARGIN="8" RIGHTMARGIN="0" TOPMARGIN="6" BOTTOMMARGIN="0">

<?php
#error_reporting(0);
#####################################
	include("config/conexao.php");
	$conexao = conexao();
	mysql_select_db(BANCO);
#####################################
	
	/////////////////////// CONFIGURACOES /////////////////////////////////////////////////
	define("LinkGraph", "rrd_graph.php?log=" );

	//////////////////////////////////////////////////////////////////////////////////////	


#####################################
	$home = $_SERVER['SERVER_NAME'] . "/cocar_mod";
	$ObjCpu = NULL;
#####################################

fcth("Procura");	


if ($objeto!="")
	$obj= $objeto;
else
	$obj= "Faça sua busca";
	
echo "

<table width='150'  border='0' cellspacing='0' cellpadding='6' CLASS='laterais' bgcolor='#F3FAE9'>
	<TR><TD>
    <td><table width='100%'  border='0' cellspacing='0' cellpadding='0'>
	<form name='cadastro' method='post' action='" . $_SERVER['PHP_SELF'] . "' onSubmit='return validaForm()'>
          <tr>
   			<td ALIGN='center'><input name='objeto' type='text' class='caixa' id='busca' MAXLENGTH='22' VALUE=\"$obj\" onfocus=\"inn(this,2)\" onblur=\"out(this,2)\"></td>
            <td align='left'><input type='image' src='imagens/bt_ok.gif' onClick='' style='cursor:hand;'></td>
          </tr>
        </form>
    </table></td></tr>
</table>
";

if ($objeto!="")
{
	procura($objeto);
	voltar_principal($home);
}
elseif($uf=="" && $ger=="")
{
	inicial();
//	UserOnLine();
}
elseif($uf!="" && $ger=="")
{
	gerencias($uf);
	voltar_principal($home);
}
elseif($uf!="" && $ger!="")
{
	if ($serial!="")
	{
		concentradora($serial, $uf, $host);
		voltar_conc($uf);
	}
	else
	{
		entidades($ger, $uf);
		voltar_ger($ger, $uf);
	}
}

###########################################################
#####		FUNCOES PARA GERACAO DOS MENUS
###########################################################
function fcth($texto)
{
	echo "
	<table width='150'  border='0' cellspacing='0' cellpadding='0' style='margin-top: 5px'>
	  <tr>
	    <td width='1' align='right'><img border='0' src='imagens/boxverde_e.gif' width='5' height='25'></td>
	    <td background='imagens/boxverde_m.gif'  align='center' valign='bottom' CLASS='tht'><b>$texto</b></td>
	    <td width='5'><img src='imagens/boxverde_d.gif' width='5' height='25'></td>
	  </tr>
	</table>
	";
} # FIM DA FUNCAO CABECALHO

function voltar_principal($home)
{
echo "
<table width='140' border='0' cellspacing='0' cellpadding='0'>
	<TR><TD>
		<BR><a href='http://$home' TARGET='_top'><img SRC='imagens/bt_voltar.gif' BORDER='0'></a>
	</TD></TR>	
</table>";
} # FIM DA FUNCAO VOLTAR

function voltar_ger($ger, $uf)
{	
echo "
<table width='140' border='0' cellspacing='0' cellpadding='0'>
	<TR><TD>
		<BR><a href='" . $_SERVER['PHP_SELF'] . "?uf=$uf' TARGET='_self'><img SRC='imagens/bt_voltar.gif' BORDER='0'></a>
	</TD></TR>	
</table>";
} # FIM DA FUNCAO VOLTAR

function voltar_conc($uf)
{	
echo "
<table width='140' border='0' cellspacing='0' cellpadding='0'>
	<TR><TD>
		<BR><a href='" . $_SERVER['PHP_SELF'] . "?ger=Concentradoras&uf=$uf' TARGET='_self'><img SRC='imagens/bt_voltar.gif' BORDER='0'></a>
	</TD></TR>	
</table>";
} # FIM DA FUNCAO VOLTAR

function inicial()
{
	echo "
	    <table width='140' border='0' cellspacing='0' cellpadding='0'>
			<tr><td align='center' valign='middle'>
			<p align='center'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>
			<br>
	            <B>Digite o Nome da Unidade desejada.</B></font></p><BR>
			</td>
	      </tr>
	   </table>

	";
} ### FIM DA FUNÇAO AJUDANTE

function ajudante()
{
	echo "
	    <table width='140' border='0' cellspacing='0' cellpadding='0'>
			<tr><td align='center' valign='middle'>
			<p align='center'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>
			<br>
	            <B>Digite o Nome da Unidade desejada.</B></font></p><BR>
			</td>
	      </tr>
	      <tr> 
	        <td align='center' valign='top'><img src='imagens/ajudante.gif'  WIDTH='168' HEIGHT='156'></td>
	      </tr> 
	   </table>
	";
} ### FIM DA FUNÇAO AJUDANTE

function gerencias($uf)
{
	global $ObjCpu;

	$consulta = "SELECT DISTINCT gerencia FROM " . TABELA . " WHERE uf='$uf' AND IfStatus='UP' ORDER BY gerencia";
	$resultado = mysql_query($consulta);

	$nome_uf = nome_uf($uf);
	fcth("$nome_uf");
	echo "<table width='150'  border='0' cellspacing='0' cellpadding='4' CLASS='laterais' bgcolor='#F3FAE9'>
		<TR><TD>";	

	while( list($ger) = mysql_fetch_array($resultado, MYSQL_NUM))
	{   
		echo "<A HREF='" . $_SERVER['PHP_SELF'] . "?ger=$ger&uf=$uf'>$ger</A><BR>";	
	}


		echo "=======================<BR>
				<A HREF='verifica_alarme.php?uf=$uf' TARGET='principal'>Alarme de Tráfego</A><BR>
				<A HREF='cpu/rrd_graph.php?log=$ObjCpu' TARGET='principal'>CPU do Roteador</A><BR>
				<A HREF='mn_bck_ra.php?uf=$uf'>Monitoração Backbone</A>
			";

	echo "</TD></TR></table>";



} ### FIM DA FUNÇAO GERENCIAS


function entidades($ger, $uf)
{
	$consulta = "SELECT cod_int, nome, orgao, serial, tipo, host FROM " . TABELA . " WHERE gerencia='$ger' AND uf='$uf' AND IfStatus='UP' ORDER BY nome";
	$resultado = mysql_query($consulta);

	$ger = str_replace("GEx ", "", $ger);
	fcth("$ger/$uf");

	echo "
		<table width='150'  border='0' cellspacing='0' cellpadding='4' CLASS='laterais' bgcolor='#F3FAE9'>
		<TR><TD>
		";
		 	
	while( list($cod_int, $nome, $orgao, $serial, $tipo, $host) = mysql_fetch_array($resultado, MYSQL_NUM))
	{
		$serial_2 = serial($serial);
		
			$link 	= LinkGraph . $cod_int;
			
		if ( (strtoupper($tipo)) == "C")
			echo "<A HREF='$link' TITLE='$orgao - $serial_2' TARGET='principal'>$nome</A><BR>";
		else
			echo "<A HREF='$link' TITLE='$orgao - $serial_2' ONCLICK=\"document.location.href='" . $_SERVER['PHP_SELF'] . "?ger=$ger&uf=$uf&serial=$serial&host=$host'\" TARGET='principal'>$nome :  $serial</A><BR>";
   	}		

	echo "</TD></TR></table>";
	
} ### FIM DA FUNÇAO ENTIDADES

function procura($objeto)
{
	global $home;
	$objeto = strtoupper($objeto);
	if($objeto=="APS" || $objeto=="GEX" || $objeto=="APS " || $objeto=="GEX ")
	{
		echo "<BR><BR>
			<table width='150'  border='0' cellspacing='2' cellpadding='4'>
			<TR><TD ALIGN='center'><IMG SRC='imagens/alerta1.jpg'></TD></TR>
			<TR><TD ALIGN='center'><FONT SIZE='+1' COLOR='#000000'>N&atilde;o digite o tipo, e sim o nome da <FONT COLOR='#FF0000'>Unidade</font> Desejada.</FONT></TD></TR>
			</table>
		";		
		voltar_principal($home);
		exit;
	}
	elseif(eregi("\%", $objeto) || eregi("\*", $objeto) || eregi("\?", $objeto) || eregi("_", $objeto))
	{
		echo "<BR><BR>
			<table width='150'  border='0' cellspacing='2' cellpadding='4'>
			<TR><TD ALIGN='center'><IMG SRC='imagens/alerta1.jpg'></TD></TR>
			<TR><TD ALIGN='center'><FONT SIZE='+1' COLOR='#000000'>N&atilde;o é permitido o uso de <FONT COLOR='#FF0000'><BR>Meta-caracteres.</font></FONT></TD></TR>
			</table>
		";		
		voltar_principal($home);
		exit;
	}
	
//	$objeto = str_replace(" ", " d_ ", $objeto);
	
	$CamposIp = explode(".", $objeto);
	$QtdCamposIp = count($CamposIp);
	if($CamposIp[$QtdCamposIp-1]=="")
		$QtdCamposIp--;
	
	if($QtdCamposIp>=3)
		$BuscaIp= $objeto."%";
	else
		$BuscaIp= $objeto;
		
	$consulta = "SELECT cod_int, nome, orgao, gerencia, uf, serial FROM " . TABELA . " WHERE ( (nome LIKE '%$objeto%') OR (orgao LIKE '%$objeto%') OR (circuito LIKE '%$objeto%') OR (ip_orig LIKE '$BuscaIp') ) AND IfStatus='UP' ORDER BY uf";
	$resultado = mysql_query($consulta);

	while( list($cod_int, $name, $orgao, $GEx, $uf, $serial) = mysql_fetch_array($resultado, MYSQL_NUM))	
	{
		$GEx = str_replace("GEx ", "", $GEx);

			$link 	= LinkGraph . $cod_int;
		
		$nome = $name;
		fcth("$GEx/$uf");	
		echo "<table width='150'  border='0' cellspacing='0' cellpadding='4' CLASS='laterais' bgcolor='#F3FAE9'>
				<TR><TD>
					<A HREF='$link'  TITLE='$orgao - $serial' TARGET='principal'>$name</A><BR>
				</TD></TR>
			</table>";
   	}		
	if ($nome == "")
	{
		echo "<BR><BR>
			<table width='150'  border='0' cellspacing='2' cellpadding='4'>
			<TR><TD ALIGN='center'><IMG SRC='imagens/alerta1.jpg'></TD></TR>
			<TR><TD ALIGN='center'><FONT SIZE='+1' COLOR='#333333'>Unidade N&atilde;o Encontrada!!!</FONT></TD></TR>
			</table>
		";
	}	
} // FIM DA FUNÇÃO PROCURA

function concentradora($serial_consulta, $uf, $host)
{
	$consulta = "SELECT cod_int, nome, orgao, serial, tipo FROM " . TABELA . " WHERE (serial LIKE '$serial_consulta%') AND uf='$uf' AND IfStatus='UP' AND host='$host' ORDER BY tipo DESC, num_int ASC, nome ASC;";
	$resultado = mysql_query($consulta);

      fcth("$serial_consulta - $uf");

      echo "
		<table width='150'  border='0' cellspacing='0' cellpadding='4' CLASS='laterais' bgcolor='#F3FAE9'>
		<TR><TD>
		";
			 
	while( list($cod_int, $nome, $orgao, $serial, $tipo) = mysql_fetch_array($resultado, MYSQL_NUM))
	{
			$link = LinkGraph . $cod_int;

			$item = strstr($serial,'.');
					
  		if ( (strtoupper($tipo)) == "C")
			echo "<A HREF='$link' TITLE='$orgao -  $serial' TARGET='principal'><font color='#000080'>$item</font> $nome</A><BR>";
  		else
			echo "<A HREF='$link' TITLE='$orgao -  $serial' TARGET='principal'>$nome :  $serial</A><BR>";
   	}		

	echo "</TD></TR></table>";
	
} ### FIM DA FUNÇAO CONCENTRADORA

function serial($serial)
{
	return	str_replace("_", "/", $serial);
} # FIM DA FUNCAO SERIAL

function nome_uf($uf)
{
	global $ObjCpu;
	
	$uf = strtoupper($uf);
	switch($uf)
	{
	case 'AC': $nome_uf = "Acre"; 		 $ObjCpu="loopgerac";	break;
	case 'AL': $nome_uf = "Alagoas"; 		 $ObjCpu="loopgeral";	break;
	case 'AM': $nome_uf = "Amazonas"; 		 $ObjCpu="loopgeram";	break;
	case 'AP': $nome_uf = "Amapa"; 		 $ObjCpu="loopgerap";	break;
	case 'BA': $nome_uf = "Bahia"; 		 $ObjCpu="loopgerba";	break;
	case 'CE': $nome_uf = "Ceara"; 		 $ObjCpu="loopgerce";	break;
	case 'DF': $nome_uf = "Distrito Federal";  $ObjCpu="drdfas02";	break;
	case 'ES': $nome_uf = "Espírito Santo"; 	 $ObjCpu="loopgeres";	break;
	case 'GO': $nome_uf = "Goiás"; 		 $ObjCpu="loopgergo";	break;
	case 'MA': $nome_uf = "Maranhão"; 		 $ObjCpu="loopgerma";	break;
	case 'MG': $nome_uf = "Minas Gerais"; 	 $ObjCpu="loopgermg";	break;
	case 'MS': $nome_uf = "Mato Grosso Sul"; 	 $ObjCpu="loopgerms";	break;
	case 'MT': $nome_uf = "Mato Grosso";	 $ObjCpu="loopgermt";	break;
	case 'PA': $nome_uf = "Pará"; 		 $ObjCpu="loopgerpa";	break;
	case 'PB': $nome_uf = "Paraíba"; 		 $ObjCpu="loopgerpb";	break;
	case 'PE': $nome_uf = "Pernambuco"; 	 $ObjCpu="loopgerpe";	break;
	case 'PI': $nome_uf = "Piauí";		 $ObjCpu="loopgerpi";	break;
	case 'PR': $nome_uf = "Parana";		 $ObjCpu="loopgerpr";	break;
	case 'RJ': $nome_uf = "Rio de Janeiro";	 $ObjCpu="loopgercv";	break;
	case 'RN': $nome_uf = "Rio Grande Norte";	 $ObjCpu="loopgerrn";	break;
	case 'RO': $nome_uf = "Rondônia"; 		 $ObjCpu="loopgerro";	break;
	case 'RR': $nome_uf = "Rorâima"; 		 $ObjCpu="loopgerrr";	break;
	case 'RS': $nome_uf = "Rio Grande do Sul"; $ObjCpu="loopgerrs";	break;
	case 'SC': $nome_uf = "Santa Catarina"; 	 $ObjCpu="loopgersc";	break;
	case 'SE': $nome_uf = "Sergipe"; 		 $ObjCpu="loopgerse";	break;
	case 'SP': $nome_uf = "São Paulo"; 		 $ObjCpu="dsspmv01";	break;
	case 'TO': $nome_uf = "Tocantins"; 		 $ObjCpu="loopgerto";	break;
	}
	return $nome_uf;
}

function UserOnLine()
{
	$Tab_UserOnLine = 'useronline';
	$ip      = $_SERVER['REMOTE_ADDR'];
	$arquivo = $_SERVER['PHP_SELF'];


  	$Xtimestamp=time();
  	$timeout=time()-300; // valor em segundos
  	$result = mysql_db_query(BANCO,"INSERT INTO $Tab_UserOnLine VALUES ('$Xtimestamp','$ip','$arquivo')");
  	$result = mysql_db_query(BANCO,"DELETE FROM $Tab_UserOnLine WHERE Xtimestamp<$timeout");
  	$result = mysql_db_query(BANCO,"SELECT DISTINCT ip FROM $Tab_UserOnLine");
  	$usuarios = mysql_num_rows($result);

	if($usuarios>1)
		$texto= "$usuarios Usuários Conectados";
	else
		$texto= "$usuarios Usuário Conectado";
/*
 echo "
	<div style='position: absolute; bottom: 25; left: 5; width: 150; height: 12'>
		<table border='0' width='100%' cellspacing='0' cellpadding='0'>
			<tr>
				<td ALIGN='Center' Class='OnLine'>
					$texto
				</td>
  			</tr>
		</table>
	</div>
	";
*/
}
if ( isset($result) ) mysql_free_result($result);
mysql_close() or die ('nao desconectou');
?>
</BODY>
</HTML>
