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

//error_reporting (0);

include ("../config/conexao.php");
$conexao = conexao();
mysql_select_db("rede_acesso", $conexao);


$hoje 		= date('Y-m-d');
$dia_fim 	= strtotime($hoje) - 86400;
$dia_ini 	= $dia_fim - 2592000;
$dia_ini 	= date('Y-m-d',$dia_ini);
$dia_fim 	= date('Y-m-d',$dia_fim);

$arquivo 	=$_GET['circ'];
$sentido 	=$_GET['sentido'];
$localidade =$_GET['localidade'];

if ($sentido=="Saida"){
	$p1 = "7_19_media_in";
	$p2 = "cir_in_rec";
	$p3 = "7_19_pico_in";
}else{
	$p1 = "7_19_media_out";
	$p2 = "cir_out_rec";
	$p3 = "7_19_pico_out";
}

echo "<H3><FONT COLOR='#333399'><B>Comparativo Media / 95% / M�ximo</B></H3></FONT>";
echo "<H3><B>Localidade.....:</B>".$localidade." (".$sentido." )"."</H3>";
echo "<P align='center'>";
echo "<TABLE BORDER='1' width='70%'>";
echo "<TR BGCOLOR='#99CCFF'><TH>Dia</TH><TH>M�dia(kbps)</TH><TH>Taxa 95%(kbps)</TH><TH>M�ximo (kbps)</TH></TR>";

$query = "SELECT  $p1, $p2, $p3, dia FROM performance_diaria WHERE (dia >= '$dia_ini' and dia <= '$dia_fim')and(cod_int = '$arquivo')";
$resultado = mysql_query($query, $conexao);
$i = 0;
while ($row = mysql_fetch_row($resultado)) {
    $data1y[$i] = $row[0];
    $data2y[$i] = $row[1];
    $data3y[$i] = $row[2];
    $time 		= strtotime ($row[3]);
	$a[$i] 		= Date('d/m',$time);
	echo "<P align='right'>";
			echo ("
			 <TR>
			 	<TD align='center'>$a[$i]</TD>
				<TD align='right'>$data1y[$i]</TD>
				<TD align='right'>$data2y[$i]</TD>
				<TD align='right'>$data3y[$i]</TD>
			 </TR>
			");		
    $i 			= $i + 1;
	}

?>
<A HREF="javascript:window.close(0);">Fechar</A>

