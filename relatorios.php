<HEAD>
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


<STYLE type=text/css>
.mh
	{
		PADDING-RIGHT: 5px; 
		DISPLAY: block; 
		PADDING-LEFT: 4px; 
		FONT-WEIGHT: bold; 
		FONT-SIZE: 12px; 
		PADDING-BOTTOM: 2px; ; 
		WIDTH: expression("100%"); 
		COLOR: #556677; 
		PADDING-TOP: 1px; 
		FONT-FAMILY: Verdana, Geneva, Arial, Helvetica, sans-serif; 
		BACKGROUND-COLOR: #f8f8f8;
}
.mn
	{
		BORDER-RIGHT: #556677 1px solid; 
		PADDING-RIGHT: 1px; 
		BORDER-TOP: #556677 1px solid; 
		PADDING-LEFT: 1px; 
		Z-INDEX: 100; 
		BACKGROUND: #eeeff0; 
		PADDING-BOTTOM: 1px; 
		BORDER-LEFT: #556677 1px solid; 
		PADDING-TOP: 1px; 
		BORDER-BOTTOM: #556677 1px solid; 
		POSITION: absolute;
}
.mn A
	{
		BORDER-RIGHT: #eeeff0 1px solid; 
		PADDING-RIGHT: 5px; 
		BORDER-TOP: #eeeff0 1px solid; 
		DISPLAY: block; 
		PADDING-LEFT: 4px; 
		FONT-WEIGHT: normal; 
		FONT-SIZE: 12px; 
		PADDING-BOTTOM: 2px; 
		BORDER-LEFT: #eeeff0 1px solid; 
		WIDTH: expression("100%"); 
		COLOR: #2f4f4f; 
		PADDING-TOP: 1px; 
		BORDER-BOTTOM: #eeeff0 1px solid; 
		FONT-FAMILY: Verdana, Geneva, Arial, Helvetica, sans-serif; 
		TEXT-DECORATION: none;
}
.mn A:hover 
	{
		BORDER-RIGHT: #223344 1px inset; 
		BORDER-TOP: #223344 1px inset; 
		BACKGROUND: #778899; 
		BORDER-LEFT: #223344 1px inset; 
		COLOR: #e0f0f0; 
		BORDER-BOTTOM: #223344 1px inset; 
		TEXT-DECORATION: none;
}
.sp
	{
		BORDER-TOP: #ffffff 0px solid; 
		MARGIN: 2px; 
		BORDER-BOTTOM: #334455 1px solid;
	}
.sys
	{
		FONT: 8pt Verdana, Arial;
		FONT-WEIGHT: bold; 
		COLOR: #404000;
	}
</STYLE>
<script language="JavaScript" type="text/JavaScript">
function MM_openBrWindow(theURL,winName,features) 
	{ 
		  window.open(theURL,winName,"width=390,height=560");
	}
</script>
</HEAD>

 <BODY background="imagens/fundoko.gif" LEFTMARGIN="0" RIGHTMARGIN="0" TOPMARGIN="0" BOTTOMMARGIN="0">

<BR>
<!-- 
<DIV ALIGN="center">

<FONT FACE="Verdana" SIZE="+2" COLOR="#2f4f4f"><B>
== Servi&ccedil;os ==

</B></FONT>
</DIV>
-->

<SCRIPT language=JavaScript1.2>

q1="<a href='";
q1b="' target='";
q2="' title='";
q3="'>";
q4="</a>";
q5="<div class=sp></div>";
q6="</div>";
q7="<div class='mh'>";
q8="' class='mn' style='position: absolute;width:";

d=document;dm=d.getElementById?1:0;ie=d.all?1:0;i4=(d.all && !dm)?1:0;n4=d.layers?1:0
mn=new Array();ln=new Array();sn=new Array();sw=new Array();el= new Array();mel= new Array()


tp=30 // distancia do topo do menu em relacao a pagina 
lf=10 //distancia da esquerda do menu em relacao a pagina
sp=5 //espaço entre os menus
hr=1 //espaco horizontal (1 - horizontal | 0 - vertical)
oh=50 
ov=50

mn[0]='<B>Estat&iacute;sticas</B>';ln[0]='#';sw[0]=130;sn[0]="" // menu 
+q7+"Tr&aacute;fego 1 minuto"+q6 
+q1+"rel/trafego_1minuto.php"+q1b+"_blank"+q2+"Tr&aacute;fego 1 Minuto - Rede de Acesso"+q3+"&raquo;&nbsp;Rede de Acesso"+q4
+q1+"rel/trafego_1minuto_varios.php"+q1b+"_blank"+q2+"Tr&aacute;fego 1 Minuto - Rede de Acesso"+q3+"&raquo;&nbsp;Rede de Acesso - v&aacute;rios dias"+q4
+q5 //separador
+q7+"Perform. Mensal"+q6 
+q1+"rel/Performance_graficos.php"+q1b+"_blank"+q2+"Performance Mensal - Rede de Acesso"+q3+"&raquo;&nbsp;Rede de Acesso"+q4


// NAO E NECESSARIO ALTERAR O CODIGO ABAIXO //
ma=mn.length;
mw=0;
for(i=0;i<ma;i++)
{
	if(sw[i]>mw)
		mw=sw[i]
};

d.write("<div id='ctrl' style='position:absolute;width:100%;height:100%;z-indez:90' onmouseover='hA()'></div>");

ctr=gE('ctrl')

if(hr==1)
{
	sp+=(dm&&!ie)?4:0;
	for(i=0;i<ma;i++)
	{	
		d.write("<div id='main"+i+q8+sw[i]+";top:"+tp+";left:"+lf+q3+q1+ln[i]+"' onmouseover='hA();sE(el["+i+"]);sE(ctr)"+q3+mn[i]+q4+q6);mel[i]=gE("main"+i);
//		d.write("<div id='main"+i+q8+sw[i]+";top:"+tp+";left:"+lf+q3+q1+ln[i]+"' onmouseclick='hA();sE(el["+i+"]);sE(ctr)"+q3+mn[i]+q4+q6);mel[i]=gE("main"+i);
		d.write("<div id='sub"+i+q8+sw[i]+";top:"+(tp+gH(mel[i])-1)+";left:"+lf+q3+sn[i]+q6);el[i]=gE("sub"+i);lf+=(gW(mel[i])+sp)
	}
}
else
{
	for(i=0;i<ma;i++)
	{
		d.write("<div id='main"+i+q8+mw+";top:"+tp+";left:"+lf+q3+q1+ln[i]+"' onmouseover='hA();sE(el["+i+"]);sE(ctr)"+q3+mn[i]+q4+q6);mel[i]=gE("main"+i);
		//d.write("<div id='main"+i+q8+mw+";top:"+tp+";left:"+lf+q3+q1+ln[i]+"' onmouseclick='hA();sE(el["+i+"]);sE(ctr)"+q3+mn[i]+q4+q6);mel[i]=gE("main"+i);
		d.write("<div id='sub"+i+q8+sw[i]+";top:"+(tp+ov)+";left:"+(lf+mw-oh)+q3+sn[i]+q6);tp+=(gH(mel[i])+sp);el[i]=gE("sub"+i)
	}
}

function hA()
{
	for(i=0;i<ma;i++) hE(el[i]);hE(ctr)
};

function zA()
{
	for(i=0;i<ma;i++)
	{
		sZ(el[i],111);
		sZ(mel[i],100)
	}
};

function gE(e)
{
	if(dm)
	{
		r=d.getElementById(e);
		return d.getElementById(e).style
	}
	if(i4)return d.all[e].style;
	if(n4)return d.layers[e]
}

function hE(e)
{
	e.visibility="hidden"
};

function sE(e)
{
e.visibility="visible"
};
function sZ(e,z)
{
	e.zIndex=z
};

function gH(e)
{
	h=parseInt(e.height||e.pixelHeight||r.offsetHeight);
	return h
};

function gW(e)
{
	w=parseInt(e.width||e.pixelWidth||r.offsetWidth);
	return w
}

hA();
zA();
d.onclick=hA

</SCRIPT>

