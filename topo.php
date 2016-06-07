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

session_start();
error_reporting(0);

$janela=0;


// -------------comentada por enquanto sem estatíticas de acesso --------

/* ---inicio coment1
if ($_SESSION['user']=="" && $_SESSION['servico']!=$_SERVER['PHP_SELF'])
{
	$_SESSION['user'] 	 = $_SERVER['REMOTE_ADDR'];
	$_SESSION['nome'] 	 = $_SERVER['REMOTE_HOST'];
	$_SESSION['servico'] = $_SERVER['PHP_SELF'];
	
	$user 	= $_SERVER['REMOTE_ADDR'];
	$nome 	= $_SERVER['REMOTE_HOST'];
	$data 	= strftime("%Y-%m-%d %H:%M:%S",date('U'));
		
	#####################################
		include("config/conexao.php");
		$conexao = conexao();
		@mysql_select_db(BANCO);
		$TabAcessos = "acessos";
	#####################################
	

	$consulta = "SELECT qtd_acessos FROM $TabAcessos WHERE usuario='$user' ";
	$matrix = mysql_query($consulta);
	
	list($qtd_acessos) = mysql_fetch_row($matrix);

		if ($qtd_acessos=="")
		{
				$inclusao = "INSERT INTO $TabAcessos(usuario, nome, qtd_acessos, ultimo_acesso)VALUES('$user', '$nome', '1', '$data')";
				mysql_query($inclusao);
		}
		else
		{	
				$qtd_acessos++;
				$update = "UPDATE $TabAcessos SET qtd_acessos='$qtd_acessos', nome='$nome', ultimo_acesso='$data' WHERE usuario='$user' ";
				mysql_query($update);
		}

	 $janela = 1;
	 
	estatisticas();
	
}	

function estatisticas()
{
	include("estatisticas/contar.php");
}



mysql_close();
---- fim coment1*/
?>

<HTML>
<HEAD>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<style>
A
	{
		FONT-FAMILY: Verdana, Arial; 
		FONT-WEIGHT: bold; 
		FONT-SIZE: 8pt; 
		COLOR: #08296b; 
		text-decoration: none
	}

A:hover
	{
		FONT-FAMILY: Verdana, Arial; 
		FONT-SIZE: 8pt; 
		FONT-WEIGHT: bold; 
		COLOR: #ff0000; 
		text-decoration: none
	}
.sys
	{
		FONT: 8pt Verdana, Arial;
		FONT-WEIGHT: bold; 
		COLOR: #404000;
	}

.tht
	{
		FONT-FAMILY: Verdana, Arial; 
		FONT-SIZE: 8pt;
		FONT-WEIGHT: bold; 
	}
</style>

<script language="JavaScript" type="text/JavaScript">
function janela()
	{
		open ("aviso.html", "janela", "status=no, width=434, height=435");
	}
function contatos()
	{
	/* Remover Item Contato */
		window.open("contatos.html", "janela", "status=no, width=360, height=170");
	}
	function AbrirNovaJanela(theURL,winName)
	{
		window.open(theURL,'winName',"width=700,height=390,resizable,scrollbars");
	}


function MM_findObj(n, d) { //v4.01
  var p,i,x;
  	if(!d) d=document;
	  if((p=n.indexOf("?"))>0&&parent.frames.length)
	  {
		d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);
	  }

	    if(!(x=d[n])&&d.all)
	    	x=d.all[n];

	    for (i=0;!x&&i< d.forms.length;i++)
	    	x=d.forms[i][n];

	  	for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_showHideLayers() { //v6.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}
function XX(x)
{
	MM_showHideLayers('Layer1','','hide');
	MM_showHideLayers('Layer2','','hide');
	MM_showHideLayers('Layer3','','hide');
	MM_showHideLayers('Layer4','','hide');
	MM_showHideLayers('Layer5','','hide');
	MM_showHideLayers('Layer6','','hide');
	MM_showHideLayers('Layer7','','hide');

	x=2; // Para voltar a janela pop-up-> comentar esta linha
	if(x==1)
        janela();
}


</script>

<base target="principal">
</HEAD>


<BODY text=#000000  vLink=#08296B aLink=#08296B bgColor=#FFFFFF leftMargin=0 topMargin=0  ONLOAD="XX(<?php echo $janela;?>)">

<TABLE cellSpacing=0 cellPadding=0 width="100%" 
background=topo_arquivos/Fundo1.gif border=0>
  <TBODY>
  <TR>
    <TD width="45%" height=49>
      <TABLE height=52 cellSpacing=0 cellPadding=0 width="100%" background=topo_arquivos/fundo_barra.gif border=0>
        <TBODY>
        <TR bgColor=#669966>
          <TD vAlign=center width="21%" height=54><A 
            href="http://www.mpas.gov.br/" target=_blank><IMG 
            alt="Site do MPAS" src="topo_arquivos/logo.gif" 
            align=bottom border=0 ></A></TD>
          <TD vAlign=bottom width="79%" height=54><IMG 
            src="topo_arquivos/top_dataprev.gif" align=top 
            useMap=#Map border=0 width="479" height="44"></TD></TR></TBODY></TABLE></TD>
    <TD vAlign=bottom borderColor=#669966 width="55%" 
    background=topo_arquivos/fundo_barra.gif bgColor=#669966>
      <P align=right><IMG height=44 src="topo_arquivos/top_pontos.gif" 
      width=198 align=bottom>. </P></TD></TR>
  </TBODY></TABLE><MAP name=Map><AREA title="Site da DATAPREV" 
  shape=RECT target=_blank alt="Site da DATAPREV" coords=5,5,97,23 
  href="http://www.dataprev.gov.br/"><AREA title="Site do MPAS" shape=RECT 
  target=_blank alt="Site do MPAS" coords=5,22,421,42 
  href="http://www.mpas.gov.br/"></MAP>
<div align="right">
  <table border="0" width="100%" bgcolor="#CCCC99" cellspacing="0" cellpadding="0">
    <tr>
      <td width="300" CLASS="sys"><B><i>&nbsp;COCAR: Controlador Centralizado do Ambiente de Rede</i></td>
      <td align="right" height="22">
		  	<B><FONT face="Verdana" color='#000000' size="1">
  
			<script>
			var now = new Date();
			var mName = new Array("Janeiro", "Fevereiro", "Mar&ccedil;�o", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
			var dName = new Array("Domingo", "Segunda-feira", "Ter&ccedil;a-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "S&aacute;�bado");
			var dayNr = now.getDate();
			var Mes = now.getMonth() + 1;
			var yearNr = now.getYear();
			var H = now.getHours();
			var M= now.getMinutes();
			var S= now.getSeconds();

			if (dayNr < 10) { dayNr = "0" + dayNr }
			if (Mes < 10) { Mes = "0" + Mes }
			if (H < 10) { H = "0" + H }
			if (M < 10) { M = "0" + M }
			/*if (yearNr < 2000) { yearNr = "19" + yearNr }*/
			if (yearNr < 2000) { yearNr = 1900 + yearNr }

			document.write( dName[now.getDay()] + ", " + dayNr + "/" + Mes + "/"+ yearNr + " " + H + ":" + M )
			</script>
			&nbsp;&nbsp;

			</font><B>
	</td>
      <td align="right">
		  	<B><FONT face="Verdana" color=#ffffff size="2">&nbsp;|&nbsp;</font><B>
                <a HREF="index.html" target="_top" ONMOUSEOVER="MM_showHideLayers('Layer1','','show')" onMouseOut="MM_showHideLayers('Layer1','','hide')"><img border="0" src="topo_arquivos/home.gif" align="middle"></a>
        	<B><FONT face="Verdana" color=#ffffff size="2">&nbsp;|&nbsp;</font><B>
			  	<a href="tabela_desempenho.php" TARGET="principal" ONMOUSEOVER="MM_showHideLayers('Layer2','','show')" onMouseOut="MM_showHideLayers('Layer2','','hide')"><img border="0" src="topo_arquivos/total.gif" align="middle"></a>
        	<B><FONT face="Verdana" color=#ffffff size="2">&nbsp;|&nbsp;</font><B>
			  	<a href="configurar.php" TARGET="principal" ONMOUSEOVER="MM_showHideLayers('Layer6','','show')" onMouseOut="MM_showHideLayers('Layer6','','hide')"><img border="0" src="topo_arquivos/config.gif" align="middle"></a>
        	<B><FONT face="Verdana" color=#ffffff size="2">&nbsp;|&nbsp;</font><B>
			  	<a href="relatorios.php" TARGET="principal" ONMOUSEOVER="MM_showHideLayers('Layer7','','show')" onMouseOut="MM_showHideLayers('Layer7','','hide')"><img border="0" src="topo_arquivos/rel.jpeg" align="middle"></a>
        	<B><FONT face="Verdana" color=#ffffff size="2">&nbsp;|&nbsp;</font><B>
<!--			  	<a href="dipro.html" TARGET="principal" ONMOUSEOVER="MM_showHideLayers('Layer3','','show')" onMouseOut="MM_showHideLayers('Layer3','','hide')"><img border="0" src="topo_arquivos/contatos.gif" align="middle">
-->

			  	<a href="javascript:contatos();" OnClick="javascript:contatos();"  ONMOUSEOVER="MM_showHideLayers('Layer3','','show')" onMouseOut="MM_showHideLayers('Layer3','','hide')"><img border="0" src="topo_arquivos/contatos.gif" align="middle">

			<B><FONT face="Verdana" color=#ffffff size="2">&nbsp;|&nbsp;</font><B>
			  	<a href="manual/cocar.pdf"  ONMOUSEOVER="MM_showHideLayers('Layer4','','show')" onMouseOut="MM_showHideLayers('Layer4','','hide')"><img border="0" src="topo_arquivos/ajuda.gif" align="middle"></a>
		  	<B><FONT face="Verdana" color=#ffffff size="2">&nbsp;|&nbsp;</font><B>



<!--           COMENTADA DEVIDO A SER LINK PARA INTRANET
			  	<a href="http://www-dereo" target="_blank" ONMOUSEOVER="MM_showHideLayers('Layer5','','show')" onMouseOut="MM_showHideLayers('Layer5','','hide')">DERE.O</a>


		  	<B><FONT face="Verdana" color=#ffffff size="2">&nbsp;|&nbsp;</font><B>
	</td>
		<td CLASS="sys">DERE.O</td>
-->
    </tr>
  </table>
</div>

<div id="Layer1" style="position: absolute; top: 58; right: 320; width: 187; height: 19 visibility: hidden;"> 
	<TABLE WIDTH="100%" CELLSPACING="0" CELLPADDING="0" BORDER="0">
		<TR>
			<TD align="right" CLASS="tht" VALIGN="bottom">P&aacute;gina Inicial</TD>
		</TR>
	</TABLE>
 </div>
 
<div id="Layer2" style="position: absolute; top: 58; right: 320; width: 187; height: 19 visibility: hidden;"> 
	<TABLE WIDTH="100%" CELLSPACING="0" CELLPADDING="0" BORDER="0">
		<TR>
			<TD align="right" CLASS="tht" VALIGN="bottom">Totaliza&ccedil;&atilde;o de Desempenho</TD>
		</TR>
	</TABLE>
 </div>
 
 <div id="Layer3" style="position: absolute; top: 58; right: 320; width: 187; height: 19 visibility: hidden;"> 
	<TABLE WIDTH="100%" CELLSPACING="0" CELLPADDING="0" BORDER="0">
		<TR>
			<TD align="right" CLASS="tht" VALIGN="bottom">Contatos</TD>
		</TR>
	</TABLE>
 </div>
 
  <div id="Layer4" style="position: absolute; top: 58; right: 320; width: 187; height: 19 visibility: hidden;"> 
	<TABLE WIDTH="100%" CELLSPACING="0" CELLPADDING="0" BORDER="0">
		<TR>
			<TD align="right" CLASS="tht" VALIGN="bottom">Ajuda</TD>
		</TR>
	</TABLE>
 </div>
 
  <div id="Layer5" style="position: absolute; top: 58; right: 320; width: 187; height: 19 visibility: hidden;"> 
	<TABLE WIDTH="100%" CELLSPACING="0" CELLPADDING="0" BORDER="0">
		<TR>
			<TD align="right" CLASS="tht" VALIGN="bottom">P&aacute;gina do Departamento</TD>
		</TR>
	</TABLE>
 </div>
 
<div id="Layer6" style="position: absolute; top: 58; right: 320; width: 187; height: 19 visibility: hidden;"> 
	<TABLE WIDTH="100%" CELLSPACING="0" CELLPADDING="0" BORDER="0">
		<TR>
			<TD align="right" CLASS="tht" VALIGN="bottom">Configura&ccedil;&atilde;o</TD>
		</TR>
	</TABLE>
 </div>
<div id="Layer7" style="position: absolute; top: 58; right: 320; width: 187; height: 19 visibility: hidden;"> 
	<TABLE WIDTH="100%" CELLSPACING="0" CELLPADDING="0" BORDER="0">
		<TR>
			<TD align="right" CLASS="tht" VALIGN="bottom">Relat&oacute;rios</TD>
		</TR>
	</TABLE>
 </div>
</BODY>
</HTML>
