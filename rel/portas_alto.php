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

include_once ("../config/conexao.php");
$conexao = conexao();
mysql_select_db("rede_acesso", $conexao);
$mes_atual 	= mktime (0, 0, 0, date("m")-1, date("d"),  date("Y"));
$data		= Date ('m/Y',$mes_atual);
$mes_atual 	= Date ('Y-m-01',$mes_atual);

echo "<H3><FONT COLOR='#333399'><B>CONCENTRADORAS COM ALTO TR�FEGO - REDE DE ACESSO</B></H3></FONT>";
echo "<H3><FONT COLOR='#333399'><B>M�s de Refer�ncia: $data</B></H3></FONT>";
	echo "<P align='center'>";
	echo ("<TABLE WIDTH='70%' BORDER='1'>
	<TR BGCOLOR='#CCFFFF'><TD ALIGN='center'>UF</TD><TD ALIGN='center'>CONCENTRADORA</TD><TD ALIGN='center'>Serial</TD><TD ALIGN='center'>Num Circuito</TD><TD ALIGN='center'>Porta(kbps)</TD><TD ALIGN='center'>Taxa 95%(kbps)</TD></TR>");

$query= "select DISTINCT uf  from rede_acesso";
$estados=mysql_query($query,$conexao);
while($line = mysql_fetch_array($estados))
{	
//$uf = &$estados[$t];
$consulta = "SELECT cod_int,porta,nome,circuito,serial FROM rede_acesso WHERE uf='" . $line['uf'] ."' and tipo='P'";
$localidades = mysql_query($consulta, $conexao);

while ($parametros = mysql_fetch_row($localidades)) {
	$circuito 	= $parametros[0];
	$porta		= $parametros[1];
	$nome		= $parametros[2];
	$num_circ	= $parametros[3];
	$serial		= $parametros[4];

$query = "SELECT cir_in_rec,cir_out_rec FROM performance_mensal 
				WHERE (mes_ano = '$mes_atual' and cod_int = '$circuito')";

$resultado = mysql_query($query, $conexao);

while ($row = mysql_fetch_row($resultado)) {
    $porta_in   = $row[0];
    $porta_out  = $row[1];
	if ($porta_out > 0.85*$porta)
	{
			echo ("
			 <TR>
				<TD ALIGN='center'>" . $line['uf'] . "</TD>
				<TD ALIGN='right'>$nome</TD>
				<TD ALIGN='right'>$serial</TD>
				<TD ALIGN='right'>$num_circ</TD>
				<TD ALIGN='right'>$porta</TD>
				<TD ALIGN='right'>$porta_out</TD>
			 </TR>
			");	
	}
	}
}//fim primeiro while

}//fim do FOR

	echo ("</TABLE>");

?>

