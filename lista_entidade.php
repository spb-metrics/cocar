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
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">

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
<?php
//inclusao de informacoes de conexao e definicao da funcao conexao()
include("config/conexao.php");

//script de confirmacao de delecao
echo "
<script>\n
function confirmDelete(ent) {\n
  if (confirm(\"deletar ent?\")) {\n
    document.location = teste.php;\n
  }\n
}\n
</script>\n";


function interf()
{
	$con=conexao();
	mysql_select_db("rede_acesso",$con);
	$res=mysql_query("select * from entidades",$con);
	if (mysql_num_rows($res) <> 0)
	{
		echo "<table border=1>\n";
		echo "<tr><td align=center>Identificador</td><td align=center>Descri&ccedil;&atilde;o</td><td align=center>Editar</td><td align=center>Excluir</td></tr>";
		while ($linha = mysql_fetch_array($res)) 
		{
			//começa linha do registro
			echo "<tr>\n";
	
			//primeira coluna
			echo "<td align=center>" . $linha['identificador'] . "</td>\n";
			//segunda coluna
			echo "<td align=left>" . $linha['nome_entidade'] . "</td>\n";
			//terceira coluna
			echo "<td align=center><form name=e_" . $linha['identificador'] . " method=POST action=edita_entidade.php target='cadastro'> <input type='hidden' name='id' value='" . $linha['identificador'] . "'><input type=submit name='edit' value='Editar'> </form></td>\n";
			//Quarta coluna
			echo "<td align=center><form name=x_" . $linha['identificador'] . " method=POST action=lista_entidade.php> <input type='hidden' name='id' value='" . $linha['identificador'] . "'><input type=submit name='Excluir' value='X' onclick=\"return confirm('deseja deletar " . $linha['identificador'] . "?')\"> </form></td>\n";
	
			//fim do registro
			echo "</tr>\n";
		}
		echo "</table>\n";
	}
	mysql_close($con);
}

function excluir($id)
{
	$con=conexao();
	mysql_select_db("rede_acesso",$con) or die("problemas ao acessar rede_acesso"); 
	$query="delete from entidades where identificador=\"" . $id ."\"";
	mysql_query($query,$con) or die("problemas na execucao da delecao");
	mysql_close($con);
	echo "exclus&atilde;o de $id realizada com sucesso!";
	interf();
}

if ( $_POST['Excluir'] == 'X' )
{
	excluir($_POST['id']);
}
else
{
	interf();
}

?>

</BODY>
</HTML>
