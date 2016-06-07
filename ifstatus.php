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
	var $uf;
	
		function SnmpLinux()
		{
		     	define(NL,chr(10));
	     	}


		function SetHost($uf, $H, $C)
		{
			  $this->uf		 = $uf;
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


			if($this->sysUpTime==NULL)
			{
			#	$this->Mail_RouterFora();
				return (0);
			}

			return (1);
		}

		function Mail_RouterFora()
		{
			$headers  = 'De: Mandacaru <system>';
			$para	    = "chris.monaco@previdencia.gov.br";

				$assunto  = "Roteador Fora do Ar.";
				$mensagem = "DIPRO" . NL . NL .
						"Mensagem Automatica - " . date('H:i d/m/Y'). NL .
						"De: <system>" . $this->Server() . NL .
						"=================================================================". NL . NL .
						"	Estado: ". $this->uf . NL .
               				"     Host: ". $this->host . NL .
						"		Roteador Fora do Ar."
						;
			mail($para, $assunto, $mensagem, $headers);
		}
		
		
		function Server()
		{
			$aux = shell_exec('ifconfig | head -2| tail -1');
                  $aux = substr(strstr($aux, ':'),2);
			$server = explode(" " , $aux);
			return $server[0];
		}
		
		function Mail_ConcentradoraFora($ifDescr, $IfAdminStatus, $IfOperStatus)
		{
			$headers  = 'De: Mandacaru <system>';
			$para	    = "chris.monaco@previdencia.gov.br";

			      $assunto  = "Concentradora Fora do Ar.";
				$mensagem = "DIPRO" . NL . NL .
						"Mensagem Automatica - " . date('H:i d/m/Y'). NL .
						"De: <system>" . $this->Server() . NL .
						"=================================================================". NL . NL .
						"	Estado: ". $this->uf . NL .
               				"     Host: ". $this->host . NL .
						"		Concentradora: ". $IfDescr . " Fora do Ar."
						;

			
			mail($para, $assunto, $mensagem, $headers);
		}



}
?>

<?php

##################################################
##	INCLUINDO ARQUIVOS DE CONFIGURACAO	##
##################################################0

	## CONFIGURACAO DE CONEXAO COM MYSQL
	include("config/conexao.php");
	$conexao = conexao();
	@mysql_select_db(BANCO);
#########################################

      $obj = new SnmpLinux;
	$oids = array
			(
				"IfIndex" => ".1.3.6.1.2.1.2.2.1.1",
				"IfDescr" => ".1.3.6.1.2.1.2.2.1.2",
				"IfAdminStatus" => ".1.3.6.1.2.1.2.2.1.7",
				"IfOperStatus" => ".1.3.6.1.2.1.2.2.1.8"
			);
					 

//alteracao para flexibilizacao
$query = "select DISTINCT uf  from rede_acesso";
$estados=mysql_query($query,$conexao);
while($line = mysql_fetch_array($estados))
{
        $uf=$line['uf'];
//fim alteracao de flexibilizacao


	$consulta1 = "SELECT DISTINCT host, community FROM " . TABELA . " WHERE uf='$uf' AND GeraAlarme='S'";
	$matriz1 = mysql_query($consulta1) or die ("Falha na Consulta 1\n");

      while ( list($Host, $Community) = mysql_fetch_array($matriz1,MYSQL_NUM))
	{
		$obj->SetHost($uf, $Host, $Community);

			$update02 = "UPDATE " . TABELA .
			   		" SET IfAdminStatus='UP',  IfOperStatus='UP' " .
			    		"		WHERE uf='$uf' AND host='$Host' AND history NOT LIKE 'Z%'";
                  mysql_query($update02);
				
            	if($obj->sysUpTime())
			{
				$consulta2 = "SELECT cod_int, num_int, serial, tipo ".
						"FROM " . TABELA .
						"	WHERE uf='$uf' AND host='$Host' AND IfStatus='UP' " .
						"		AND history LIKE 'Z_' AND GeraAlarme='S'"
						;

				$matriz2 = mysql_query($consulta2);

					while ( list($cod_int, $num_int, $serial, $tipo) = mysql_fetch_array($matriz2,MYSQL_NUM))
					{
					   	$MIBs =	$oids{'IfDescr'}       	. ".$num_int " .
      	                	      		$oids{'IfAdminStatus'}	. ".$num_int " .
            		      		      $oids{'IfOperStatus'}	. ".$num_int "
                           			;
						
						list($IfDescr, $IfAdminStatus, $IfOperStatus) = $obj->fc_snmpget($MIBs);

						$IfDescr = str_replace("-aal5 layer","", $IfDescr);
						$IfDescr = str_replace("atm subif",  "", $IfDescr);

                                    if($serial==$IfDescr)
						{
							$IfAdminStatus	= status($IfAdminStatus);
							$IfOperStatus	= status($IfOperStatus);

							$update = "UPDATE " . TABELA .
								    " SET IfAdminStatus='$IfAdminStatus',  IfOperStatus='$IfOperStatus' " .
								    "		WHERE cod_int='$cod_int'";
							mysql_query($update);
						}


					   #	if (strtoupper($tipo)=="P")
		    			   #		$obj->Mail_ConcentradoraFora($ifDescr, $IfAdminStatus, $IfOperStatus);

					}
			}
   			else
   			{
				$update = "UPDATE " . TABELA .
					    " SET IfAdminStatus='INAT',  IfOperStatus='INAT' " .
					    "		WHERE uf='$uf' AND host='$Host'";

				mysql_query($update);
			}

		}
}


		function UpdateIndex($x,$oids, $Host)
		{
			$IfIndex = $x->fc_snmpwalk($oids{'IfIndex'});
			$IfDescr = $x->fc_snmpwalk($oids{'IfDescr'});
			
				for ($i=0; $i<count($IfIndex); $i++)
				{
					$update = "
						UPDATE ". TABELA . "
						SET num_int='" . $IfIndex[$i] . "'
						WHERE host='$Host' AND serial='". $IfDescr[$i] . "'";

					mysql_query($update);
                        }

		}


            function status($status)
		{
                if(eregi("up",$status))
                        return "UP";
                elseif(eregi("down",$status))
                        return "DOWN";
                else
                      return "INAT";

		}


	mysql_close() or die ('nao desconectou');

?>

