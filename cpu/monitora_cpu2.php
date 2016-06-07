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


class SnmpLinux
{
	var $host;
	var $community;
	var $sysUpTime;

		function SnmpLinux()
		{
		     	define(NL,chr(10));
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

           		if($this->sysUpTime==NULL)
			{
				$this->Mail_RouterFora();
				return (0);
			}

			return (1);
		}

}
?>

<?php

##################################################
##	INCLUINDO ARQUIVOS DE CONFIGURACAO	##
##################################################0

	## CONFIGURACAO DE CONEXAO COM MYSQL
	include("../config/conexao.php");
	$conexao = conexao();

	$banco='rede_acesso';
	$tabela='cpu';

	@mysql_select_db($banco) or die ('Nao conectou');
#########################################

	$dir_rrd='/var/www/rrd/cpu/';
	
      $obj = new SnmpLinux;


	$consulta1 = "SELECT CodigoHost, IpHost, community, TipoEquipamento " .
			 "FROM $tabela ".
			 "WHERE CodigoHost!='' AND IpHost!='' "
			;
	$matriz1 = mysql_query($consulta1) or die ("Falha na Consulta 1\n");

      while ( list($CodigoHost, $Host, $Community, $Equipamento) = mysql_fetch_array($matriz1,MYSQL_NUM))
	{
		$obj->SetHost($Host, $Community);

		if ($Equipamento <> "Firewall" and $Equipamento <> "Conteudo")
		{
			$PercCpu = $obj->fc_snmpget('.1.3.6.1.4.1.9.2.1.57.0');
		}
		else
		{	
                  	if ($Equipamento <> "Router" and $Equipamento <> "Firewall")
		                     {
			                   $PercCpu = $obj->fc_snmpget('.1.3.6.1.4.1.1872.2.5.1.2.2.3.0');
		                     }
		     else
		        {	
                        $cpu=0;
                        for ($i=0;$i<5;$i++)
                        {
                                $cpu = $cpu + $obj->fc_snmpget('.1.3.6.1.4.1.94.1.21.1.7.1.0');
                                sleep(1);
                        }
                        $PercCpu = ($cpu / 5);
		        }
		}	

		if($PercCpu)
		{
			$arq_rrd = $dir_rrd . $CodigoHost . '_cpu.rrd';
		      	update_rrd_cpu($arq_rrd, $PercCpu);
		}


	}



	function update_rrd_cpu($arq_rrd, $cpu)
	{
		if (!file_exists($arq_rrd))
			create_rrd_cpu($arq_rrd);

		    	$data = date('U');
      	  	$insert = "rrdtool update $arq_rrd $data:$cpu";

			shell_exec($insert);
	}

	function create_rrd_cpu($arq_rrd)
	{
			$create = 	"rrdtool create $arq_rrd --step 60 " .
					"DS:cpu:GAUGE:120:0:100 " .
					"RRA:AVERAGE:0.5:1:4320 " .
					"RRA:AVERAGE:0.5:5:2016	" .
					"RRA:AVERAGE:0.5:20:2232 " .
					"RRA:AVERAGE:0.5:90:2976 " .
					"RRA:AVERAGE:0.5:360:1460 " .
					"RRA:AVERAGE:0.5:1440:730 " .
					"RRA:MAX:0.5:1:4320 " .
					"RRA:MAX:0.5:5:2016 " .
					"RRA:MAX:0.5:20:2232 " .
					"RRA:MAX:0.5:90:2976 " .
					"RRA:MAX:0.5:360:1460 " .
					"RRA:MAX:0.5:1440:730"
						;
			shell_exec($create);
	}
?>

