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

class SnmpLinux
{
	var $host;
	var $community;
	var $sysUpTime;

		function SnmpLinux()
		{
			// CONSTRUTOR
		}


		function SetHost($H, $C)
		{
                    $this->host  	 = $H;
			  $this->community = $C;
		}

		function fc_snmpwalk($Oid, $t = 1, $r = 1)
		{
			/*
         			Nao permite SNMP BULK.
         			Retorna Resposta, apenas, para a primeira OID solicitada
			*/

         			$com = "snmpwalk -Ov -t " . $t . " -r " . $r . " -c " . $this->community . " -v 1 " . $this->host . " " . $Oid;
				$ArrayInfo = explode("\n", shell_exec($com));
         			$ArrayInfo = $this->format_snmp($ArrayInfo);

         		return $ArrayInfo;
		}


		function fc_snmpget($Oids, $t=1, $r=1)
		{
			# Receber OIDs separadas por espaco

         			$com = "snmpget -Ov -t " . $t . " -r " . $r . " -c " . $this->community . " -v 1 " . $this->host . " " . $Oids;
	        		$ArrayInfo = explode("\n", shell_exec($com));
      	   		$ArrayInfo = $this->format_snmp($ArrayInfo);

         		return $ArrayInfo;
		}


		function format_snmp($array)
		{
      			for($i=0; $i< count($array); $i++)
         			{
              				if($array[$i]!=NULL)
              				{
                                     		if(eregi(':',$array[$i]))
         							{
								   	$NewArray[$i] = substr(strstr($array[$i], ':'),1);
                                                 	$NewArray[$i] = str_replace("\"", '', trim($NewArray[$i]));
	 							}
	 							else
                                                 	$NewArray[$i] = str_replace("\"", '', trim($array[$i]));
						}
         			}

			if(count($NewArray)>1)
      	  		return ($NewArray);
                  else
      	  		return ($NewArray[0]);


		}

		function sysUpTime()
		{
			$this->sysUpTime = $this->fc_snmpget(".1.3.6.1.2.1.1.3.0");
			return $this->sysUpTime;
		}


} # fim da classe

?>

<?php

	error_reporting(0);

#####################################
	include("config/conexao.php");
	$conexao = conexao();
	mysql_select_db(BANCO);
#####################################


/*	$arg = $_SERVER['argv'];
      $uf = strtoupper($arg[1]);

	if ($uf == NULL)
	{
	        echo "ERRO:   FALTOU INFORMAR O ESTADO!!!\n";
      	  exit;
	}
*/
//alteracao para flexibilizacao
$query = "select DISTINCT uf  from rede_acesso";
$estados=mysql_query($query,$conexao);
while($line = mysql_fetch_array($estados))
{
        $uf=$line['uf'];
//fim alteracao de flexibilizacao

	define(NL,chr(10));

 	$consulta1 = "SELECT DISTINCT host, community FROM " . TABELA . " WHERE uf='$uf'";
	$matriz1 = mysql_query($consulta1) or die ("Falha na Consulta 1\n");

      $obj = new SnmpLinux;
      while ( list($Host, $Community) = mysql_fetch_array($matriz1,MYSQL_NUM))
	{
		$obj->SetHost($Host, $Community);
		$update=NULL;

		if($obj->sysUpTime()!=NULL)
		{
			$IfIndex	= $obj->fc_snmpwalk('.1.3.6.1.2.1.2.2.1.1');
			$IfDescr	= $obj->fc_snmpwalk('.1.3.6.1.2.1.2.2.1.2');

			$DIM= count($IfIndex);

			for ($i=0; $i<=$DIM; $i++)
			{
				$IfDescr[$i] = str_replace("\"", "", $IfDescr[$i]);

				if( eregi("ATM",$IfDescr[$i]))
				{
					if(eregi("atm subif",$IfDescr[$i]))
					{
						$IfDescr[$i] = str_replace('-atm subif', "", $IfDescr[$i]);

						$update .=
							"\n UPDATE " . TABELA .
							"\n SET num_int='" . $IfIndex[$i] . "' " .
							"\n	WHERE uf='$uf' AND host='$Host' AND serial='". $IfDescr[$i] . "';"
							;
					}
						$update .=
							"\n UPDATE " . TABELA .
							"\n SET num_int='" . $IfIndex[$i] . "' " .
							"\n	WHERE uf='$uf' AND host='$Host' AND serial='". $IfDescr[$i] . "';"
							;
				}
				else
				{
						$update .=
							"\n UPDATE " . TABELA .
							"\n SET num_int='" . $IfIndex[$i] . "' " .
							"\n	WHERE uf='$uf' AND host='$Host' AND serial='". $IfDescr[$i] . "';"
							;
				}

			  $update.= "\n";
			}# FIM DO FOR

		  mysql_query($update);
		}#FIM do IF sysUpTime
	}#FIM DO WHILE
}

?>
