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

##################################################
##	INCLUINDO ARQUIVOS DE CONFIGURACAO	##
##################################################

	## CONFIGURACAO DE CONEXAO COM MYSQL
	include("config/conexao.php");
	$conexao = conexao();

##################################################

	mysql_select_db(BANCO);

	$update = ("UPDATE " . TABELA . " SET history='N'");
	mysql_query($update);
mysql_free_result;
mysql_close();
?>
