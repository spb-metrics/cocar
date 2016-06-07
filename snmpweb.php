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

	// Desativa o relatorio de todos os erros
	error_reporting(E_ALL);
?>

<?php

class SnmpLinux
{
	var $host;
	var $community;
	var $sysUpTime;
	var $sysName;
 	var $UpTimeTickts;
	var $y;
	var $Version;

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


		function Geral()
		{
			$oids = array
				(
					"sysUptime" => ".1.3.6.1.2.1.1.3.0",
					"whyReload" => ".1.3.6.1.4.1.9.2.1.2.0",
					"version" => ".1.3.6.1.2.1.1.1.0",
					"location" => ".1.3.6.1.2.1.1.6.0",
					"contact" => ".1.3.6.1.2.1.1.4.0",
					"avgBusy1" => ".1.3.6.1.4.1.9.2.1.57.0",
					"avgBusy5" => ".1.3.6.1.4.1.9.2.1.58.0",
					"sysConfigName" => ".1.3.6.1.4.1.9.2.1.73.0",
					"tsLines" => ".1.3.6.1.4.1.9.2.9.1.0",
					"cmSystemInstalledModem" => ".1.3.6.1.4.1.9.9.47.1.1.1.0",
					"cmSystemModemsInUse" => ".1.3.6.1.4.1.9.9.47.1.1.6.0",
					"cmSystemModemsDead" => ".1.3.6.1.4.1.9.9.47.1.1.10.0",
					"Memory" => ".1.3.6.1.4.1.9.3.6.6.0",
					"Services" => ".1.3.6.1.2.1.1.7.0"
				);

			   $MIBs = 	$oids{'sysUptime'}	. " " .
			   		$oids{'whyReload'}      . " " .
				    	$oids{'location'}   	. " " .
                  	      $oids{'contact'} 		. " " .
                        	$oids{'Memory'}  		. " " .
                    	    	$oids{'avgBusy1'}  	. " " .
                   	    	$oids{'avgBusy5'}  	. " " .
                   	    	$oids{'tsLines'}  	. " " .
                   	    	$oids{'Services'}  	. " " .
                   	    	$oids{'sysConfigName'} 	. " " .
                   	    	$oids{'cmSystemInstalledModem'}
					;

				list(	$sysUptime, $whyReload, $location, $contact,  $Memory, $avgBusy1, $avgBusy5,
  					$tsLines, $Services, $sysCfgName, $SysModem) = $this->fc_snmpget($MIBs);
	
				$sysName		= $this->sysName;
				$Services		= $this->syssrv($Services);
				$Memory 		/= (1024*1024); # TRANSFORMA PARA Mb
  				$this->Version   	= $this->sysVersion($Version);
   				$sysUpTime 		= $this->UpTime($sysUptime);
				echo $Services;	
    				$Version		= $this->VerificaNull($Version);
      			$sysCfgName		= $this->VerificaNull($sysCfgName);
      			$tsLines		= $this->VerificaNull($tsLines);

				$this->y=0;

				echo "
					<BR>
					<TABLE border=0	 CLASS='tabela' CELLPADDING='2' ALIGN='center'>
					<TR><TH CLASS='thfont' COLSPAN='2'>Informa&ccedil;&otilde;es Gerais</TH></TR>
					";

				$this->PrintInfoGeral("UpTime", "$sysUpTime  (motivo: $whyReload)");
				$this->PrintInfoGeral("Nome do Equipamento", "$sysName");
				$this->PrintInfoGeral("Services", "$Services");

				if ($location != NULL)
					$this->PrintInfoGeral("Localiza&ccedil;&atilde;o", "$location");

				if ($contact != NULL)
					$this->PrintInfoGeral("Contato", "$contact");

				$this->PrintInfoGeral("Mem&oacute;ria Mb", "$Memory");
				$this->PrintInfoGeral("Vers&atilde;o", $this->Version);
				$this->PrintInfoGeral("1/5 min CPU util", "$avgBusy1/$avgBusy5 %");
				$this->PrintInfoGeral("Imagem Carregada", "$sysCfgName");
				$this->PrintInfoGeral("Terminal lines", "$tsLines");

				if ($SysModem > 0)
				{

					   $MIBs = 	$oids{'cmSystemModemsInUse'}	. " " .
		   					$oids{'cmSystemModemsDead'}   ;

						list($ModemsInUse, $ModemsDead) = $this->fc_snmpget($MIBs);

						$this->PrintInfoGeral("Digital modems", "$SysModem");
						$this->PrintInfoGeral("In use modems", "$ModemsInUse");
						$this->PrintInfoGeral("Modems Dead", "$ModemsDead");
				}

			echo "</table>";

		}


    		function Interfaces()
		{
				$oids = array
					(
						"sysUptime" => ".1.3.6.1.2.1.1.3.0",
						"IfIndex" => ".1.3.6.1.2.1.2.2.1.1",
						"IfDescr" => ".1.3.6.1.2.1.2.2.1.2",
						"Description" => ".1.3.6.1.2.1.31.1.1.1.18",
						"Ip" => ".1.3.6.1.2.1.4.20.1.1",
						"IpPorder" => ".1.3.6.1.2.1.4.20.1.2",
						"IfAdminStatus" => ".1.3.6.1.2.1.2.2.1.7",
						"IfOperStatus" => ".1.3.6.1.2.1.2.2.1.8",
						"LastChange" => ".1.3.6.1.2.1.2.2.1.9",
						"ifMac" => ".1.3.6.1.2.1.2.2.1.6"
					 );


				echo
				"	<BR>
					<TABLE border=1 width='97%' CLASS='tabela' CELLPADDING='2'>
					<TR><TH COLSPAN='8' CLASS='thfont'>Informa&ccedil;&otilde;es das Interfaces</TH></TR>
					<TR><TH CLASS='topo' ROWSPAN='2'>N&ordm;<BR>Interface</TH><TH CLASS='topo' ROWSPAN='2'>Tipo de <BR>Interface</TH><TH CLASS='topo' ROWSPAN='2'>Descri&ccedil;&atilde;o da Interface</TH><TH CLASS='topo' COLSPAN='2'>Status</TH><TH CLASS='topo' ROWSPAN='2'>Endere&ccedil;o<br>Ip</TH><TH CLASS='topo' ROWSPAN='2'>Endere&ccedil;o<br>MAC</TH><TH CLASS='topo' ROWSPAN='2'>Last Change</TH></TR>
					<TR><TH CLASS='topo'>Adm</TH><TH CLASS='topo'>Opr</TH></TR>
				";

	                  $this->y=0;
   				echo $this->SegsUpTime;

				$ArrayIndex = $this->fc_snmpwalk($oids{'IfIndex'});
      	      	$IP 		= $this->fc_snmpwalk($oids{'Ip'});
				$IpPorder	= $this->fc_snmpwalk($oids{'IpPorder'});


				if (count($IP)==count($IpPorder))
				{
						for ($i=0; $i < count($ArrayIndex); $i++)
						{
								for ($x=0; $x<count($IP); $x++)
								{
	    								$indice = $ArrayIndex[$i];

	    								if ($ArrayIndex[$i] == $IpPorder[$x])
										$IPs[$indice] = $IP[$x];
		   	 					}
						}
				}

   				for ($i=0; $i< count($ArrayIndex); $i++)
   				{
      				$index = $ArrayIndex[$i];

				   	$MIBs =	$oids{'IfDescr'}       	. ".$index " .
            	      		      $oids{'IfAdminStatus'}	. ".$index " .
            	      		      $oids{'IfOperStatus'}	. ".$index " .
            	      		      $oids{'ifMac'}		. ".$index " .
            	      		      $oids{'LastChange'}	. ".$index " .
						    	$oids{'Description'}   	. ".$index "

						;

		            	  list(	$IfDescr, $IfAdminStatus, $IfOperStatus,
					  		$ifMac, $LastChange, $Description, ) = $this->fc_snmpget($MIBs);

					  $IfAdminStatus	= $this->status($IfAdminStatus);
   					  $IfOperStatus 	= $this->status($IfOperStatus);
	      		        $ifMac		= $this->ifMacAddress($ifMac);
                  		  $Description	= $this->VerificaNull($Description);
		                    $ifIP		= $this->VerificaNull($IPs[$index]);
					  $LastChange     = $this->LastChange($LastChange);

					$classe = $this->ParImpar();


					echo
					"
					<TR ALIGN='center'>
						<TD ALIGN='center' CLASS='$classe'>$index</TD>
						<TD CLASS='$classe'>$IfDescr</TD>
						<TD CLASS='$classe'>$Description</TD>
						<TD CLASS='$classe'>$IfAdminStatus</TD>
						<TD CLASS='$classe'>$IfOperStatus</TD>
						<TD CLASS='$classe'>$ifIP</TD>
						<TD CLASS='$classe'>$ifMac</TD>
						<TD CLASS='$classe'>$LastChange</TD>
					</TR>
					";

				}
				echo "</TABLE>";

		}


		function Hardware()
		{
				$oids = array
						(
							"cards"	=> ".1.3.6.1.4.1.9.3.6.11.1.3",
							"ctype"	=> ".1.3.6.1.4.1.9.3.6.11.1.2",
							"slotnum"	=> ".1.3.6.1.4.1.9.3.6.11.1.7",
							"cardSlots" => ".1.3.6.1.4.1.9.3.6.12.0"
						);

				$CardTypes[1] = "desconhecido";
				$CardTypes[2] = "csc1";
				$CardTypes[3] = "csc2";
				$CardTypes[4] = "csc3";
				$CardTypes[5] = "csc4";
				$CardTypes[6] = "rp";
				$CardTypes[7] = "cpu-igs";
				$CardTypes[8] = "cpu-2500";
				$CardTypes[9] = "cpu-3000";
				$CardTypes[10] = "cpu-3100";
				$CardTypes[11] = "cpu-accessPro";
				$CardTypes[12] = "cpu-4000";
				$CardTypes[13] = "cpu-4000m";
				$CardTypes[14] = "cpu-4500";
				$CardTypes[15] = "rsp1";
				$CardTypes[16] = "rsp2";
				$CardTypes[17] = "cpu-4500m";
				$CardTypes[18] = "cpu-1003";
				$CardTypes[19] = "cpu-4700";
				$CardTypes[20] = "csc-m";
				$CardTypes[21] = "csc-mt";
				$CardTypes[22] = "csc-mc";
				$CardTypes[23] = "csc-mcplus";
				$CardTypes[24] = "csc-envm";
				$CardTypes[25] = "chassisInterface";
				$CardTypes[26] = "cpu-4700S";
				$CardTypes[27] = "cpu-7200-npe100";
				$CardTypes[28] = "rsp7000";
				$CardTypes[29] = "chassisInterface7000";
				$CardTypes[30] = "rsp4";
				$CardTypes[31] = "cpu-3600";
				$CardTypes[32] = "cpu-as5200";
				$CardTypes[33] = "c7200-io1fe";
				$CardTypes[34] = "cpu-4700m";
				$CardTypes[35] = "cpu-1600";
				$CardTypes[36] = "c7200-io";
				$CardTypes[37] = "cpu-1503";
				$CardTypes[38] = "cpu-1502";
				$CardTypes[39] = "cpu-as5300";
				$CardTypes[40] = "csc-16";
				$CardTypes[41] = "csc-p";
				$CardTypes[50] = "csc-a";
				$CardTypes[51] = "csc-e1";
				$CardTypes[52] = "csc-e2";
				$CardTypes[53] = "csc-y";
				$CardTypes[54] = "csc-s";
				$CardTypes[55] = "csc-t";
				$CardTypes[80] = "csc-r";
				$CardTypes[81] = "csc-r16";
				$CardTypes[82] = "csc-r16m";
				$CardTypes[83] = "csc-1r";
				$CardTypes[84] = "csc-2r";
				$CardTypes[56] = "sci4s";
				$CardTypes[57] = "sci2s2t";
				$CardTypes[58] = "sci4t";
				$CardTypes[59] = "mci1t";
				$CardTypes[60] = "mci2t";
				$CardTypes[61] = "mci1s";
				$CardTypes[62] = "mci1s1t";
				$CardTypes[63] = "mci2s";
				$CardTypes[64] = "mci1e";
				$CardTypes[65] = "mci1e1t";
				$CardTypes[66] = "mci1e2t";
				$CardTypes[67] = "mci1e1s";
				$CardTypes[68] = "mci1e1s1t";
				$CardTypes[69] = "mci1e2s";
				$CardTypes[70] = "mci2e";
				$CardTypes[71] = "mci2e1t";
				$CardTypes[72] = "mci2e2t";
				$CardTypes[73] = "mci2e1s";
				$CardTypes[74] = "mci2e1s1t";
				$CardTypes[75] = "mci2e2s";
				$CardTypes[100] = "csc-cctl1";
				$CardTypes[101] = "csc-cctl2";
				$CardTypes[110] = "csc-mec2";
				$CardTypes[111] = "csc-mec4";
				$CardTypes[112] = "csc-mec6";
				$CardTypes[113] = "csc-fci";
				$CardTypes[114] = "csc-fcit";
				$CardTypes[115] = "csc-hsci";
				$CardTypes[116] = "csc-ctr";
				$CardTypes[121] = "cpu-7200-npe150";
				$CardTypes[122] = "cpu-7200-npe200";
				$CardTypes[123] = "cpu-wsx5302";
				$CardTypes[124] = "gsr-rp";
				$CardTypes[126] = "cpu-3810";
				$CardTypes[150] = "sp";
				$CardTypes[151] = "eip";
				$CardTypes[152] = "fip";
				$CardTypes[153] = "hip";
				$CardTypes[154] = "sip";
				$CardTypes[155] = "trip";
				$CardTypes[156] = "fsip";
				$CardTypes[157] = "aip";
				$CardTypes[158] = "mip";
				$CardTypes[159] = "ssp";
				$CardTypes[160] = "cip";
				$CardTypes[161] = "srs-fip";
				$CardTypes[162] = "srs-trip";
				$CardTypes[163] = "feip";
				$CardTypes[164] = "vip";
				$CardTypes[165] = "vip2";
				$CardTypes[166] = "ssip";
				$CardTypes[167] = "smip";
				$CardTypes[168] = "posip";
				$CardTypes[169] = "feip-tx";
				$CardTypes[170] = "feip-fx";
				$CardTypes[178] = "cbrt1";
				$CardTypes[179] = "cbr120e1";
				$CardTypes[180] = "cbr75e";
				$CardTypes[181] = "vip2-50";
				$CardTypes[182] = "feip2";
				$CardTypes[183] = "acip";
				$CardTypes[200] = "npm-4000-fddi-sas";
				$CardTypes[201] = "npm-4000-fddi-das";
				$CardTypes[202] = "npm-4000-1e";
				$CardTypes[203] = "npm-4000-1r";
				$CardTypes[204] = "npm-4000-2s";
				$CardTypes[205] = "npm-4000-2e1";
				$CardTypes[206] = "npm-4000-2e";
				$CardTypes[207] = "npm-4000-2r1";
				$CardTypes[208] = "npm-4000-2r";
				$CardTypes[209] = "npm-4000-4t";
				$CardTypes[210] = "npm-4000-4b";
				$CardTypes[211] = "npm-4000-8b";
				$CardTypes[212] = "npm-4000-ct1";
				$CardTypes[213] = "npm-4000-ce1";
				$CardTypes[214] = "npm-4000-1a";
				$CardTypes[215] = "npm-4000-6e";
				$CardTypes[217] = "npm-4000-1fe";
				$CardTypes[218] = "npm-4000-1hssi";
				$CardTypes[230] = "pa-1fe";
				$CardTypes[231] = "pa-8e";
				$CardTypes[232] = "pa-4e";
				$CardTypes[233] = "pa-5e";
				$CardTypes[234] = "pa-4t";
				$CardTypes[235] = "pa-4r";
				$CardTypes[236] = "pa-fddi";
				$CardTypes[237] = "sa-encryption";
				$CardTypes[238] = "pa-ah1t";
				$CardTypes[239] = "pa-ah2t";
				$CardTypes[241] = "pa-a8t-v35";
				$CardTypes[242] = "pa-1fe-tx-isl";
				$CardTypes[243] = "pa-1fe-fx-isl";
				$CardTypes[244] = "pa-1fe-tx-nisl";
				$CardTypes[245] = "sa-compression";
				$CardTypes[246] = "pa-atm-lite-1";
				$CardTypes[247] = "pa-ct3";
				$CardTypes[248] = "pa-oc3sm-mux-cbrt1";
				$CardTypes[249] = "pa-oc3sm-mux-cbr120e1";
				$CardTypes[254] = "pa-ds3-mux-cbrt1";
				$CardTypes[255] = "pa-e3-mux-cbr120e1";
				$CardTypes[257] = "pa-8b-st";
				$CardTypes[258] = "pa-4b-u";
				$CardTypes[259] = "pa-fddi-fd";
				$CardTypes[260] = "pm-cpm-1e2w";
				$CardTypes[261] = "pm-cpm-2e2w";
				$CardTypes[262] = "pm-cpm-1e1r2w";
				$CardTypes[263] = "pm-ct1-csu";
				$CardTypes[264] = "pm-2ct1-csu";
				$CardTypes[265] = "pm-ct1-dsx1";
				$CardTypes[266] = "pm-2ct1-dsx1";
				$CardTypes[267] = "pm-ce1-balanced";
				$CardTypes[268] = "pm-2ce1-balanced";
				$CardTypes[269] = "pm-ce1-unbalanced";
				$CardTypes[270] = "pm-2ce1-unbalanced";
				$CardTypes[271] = "pm-4b-u";
				$CardTypes[272] = "pm-4b-st";
				$CardTypes[273] = "pm-8b-u";
				$CardTypes[274] = "pm-8b-st";
				$CardTypes[275] = "pm-4as";
				$CardTypes[276] = "pm-8as";
				$CardTypes[277] = "pm-4e";
				$CardTypes[278] = "pm-1e";
				$CardTypes[280] = "pm-m4t";
				$CardTypes[281] = "pm-16a";
				$CardTypes[282] = "pm-32a";
				$CardTypes[283] = "pm-c3600-1fe-tx";
				$CardTypes[284] = "pm-c3600-compression";
				$CardTypes[285] = "pm-dmodem";
				$CardTypes[288] = "pm-c3600-1fe-fx";
				$CardTypes[288] = "pm-c3600-1fe-fx";
				$CardTypes[290] = "as5200-carrier";
				$CardTypes[291] = "as5200-2ct1";
				$CardTypes[292] = "as5200-2ce1";
				$CardTypes[310] = "pm-as5xxx-12m";
				$CardTypes[330] = "wm-c2500-5in1";
				$CardTypes[331] = "wm-c2500-t1-csudsu";
				$CardTypes[332] = "wm-c2500-sw56-2wire-csudsu";
				$CardTypes[333] = "wm-c2500-sw56-4wire-csudsu";
				$CardTypes[334] = "wm-c2500-bri";
				$CardTypes[335] = "wm-c2500-bri-nt1";
				$CardTypes[360] = "wic-serial-1t";
				$CardTypes[364] = "wic-s-t-3420";
				$CardTypes[365] = "wic-s-t-2186";
				$CardTypes[366] = "wic-u-3420";
				$CardTypes[367] = "wic-u-2091";
				$CardTypes[368] = "wic-u-2091-2081";
				$CardTypes[400] = "pa-jt2";
				$CardTypes[401] = "pa-posdw";
				$CardTypes[402] = "pa-4me1-bal";
				$CardTypes[414] = "pa-a8t-x21";
				$CardTypes[415] = "pa-a8t-rs232";
				$CardTypes[416] = "pa-4me1-unbal";
				$CardTypes[417] = "pa-4r-fdx";
				$CardTypes[424] = ",pa-1fe-fx-nisl";
				$CardTypes[435] = ",mc3810-dcm";
				$CardTypes[436] = ",mc3810-mfm-e1balanced-bri";
				$CardTypes[437] = ",mc3810-mfm-e1unbalanced-bri";
				$CardTypes[438] = ",mc3810-mfm-e1-unbalanced";
				$CardTypes[439] = ",mc3810-mfm-dsx1-bri";
				$CardTypes[440] = ",mc3810-mfm-dsx1-csu";
				$CardTypes[441] = ",mc3810-vcm";
				$CardTypes[442] = ",mc3810-avm";
				$CardTypes[443] = ",mc3810-avm-fxs";
				$CardTypes[444] = ",mc3810-avm-fxo";
				$CardTypes[445] = ",mc3810-avm-em";
				$CardTypes[445] = ",mc3810-avm-em";
				$CardTypes[480] = ",as5300-4ct1";
				$CardTypes[481] = ",as5300-4ce1";
				$CardTypes[482] = ",as5300-carrier";
				$CardTypes[500] = ",vic-em";
				$CardTypes[501] = "vic-fxo";
				$CardTypes[502] = "vic-fxs";
				$CardTypes[503] = "vpm-2v";
				$CardTypes[504] = "vpm-4v";
				$CardTypes[530] = ",pos-qoc3-mm";
				$CardTypes[531] = ",pos-qoc3-sm";
				$CardTypes[532] = ",pos-oc12-mm";
				$CardTypes[533] = ",pos-oc12-sm";
				$CardTypes[534] = ",atm-oc12-mm";
				$CardTypes[535] = ",atm-oc12-sm";
				$CardTypes[536] = ",pos-oc48-mm-l";
				$CardTypes[537] = ",pos-oc48-sm-l";
				$CardTypes[538] = ",gsr-sfc";
				$CardTypes[539] = ",gsr-csc";
				$CardTypes[540] = ",gsr-csc4";
				$CardTypes[541] = ",gsr-csc8";
				$CardTypes[542] = ",gsr-sfc8";
				$CardTypes[545] = ",gsr-oc12chds3-mm";
				$CardTypes[546] = ",gsr-oc12chds3-sm";
				$CardTypes[546] = ",gsr-oc12chds3-sm";
				$CardTypes[546] = ",gsr-oc12chds3-sm";
				$CardTypes[605] = ",pm-atm25";



     				$cards 	= $this->fc_snmpwalk($oids{'cards'});
      		      $ctype 	= $this->fc_snmpwalk($oids{'ctype'});
				$slotnum	= $this->fc_snmpwalk($oids{'slotnum'});
				$cardSlots	= $this->fc_snmpget($oids{'cardSlots'}) -1 ;

				$this->y=0;

				if (count($cards) > 1)
				{
      					echo
							"<BR>
							<TABLE border=0	 CLASS='tabela' CELLPADDING='3' ALIGN='center'>
							<TR><TH CLASS='thfont' COLSPAN='3'>Informa&ccedil;&otilde;es de Hardware</TH></TR>
							<TR BGCOLOR='#CDEDCF'><TD Class='topo' ALIGN='center'><B>Descri&ccedil;&atilde;o</B></TD><TD Class='topo' ALIGN='center'><B>Tipo</B></TD><TD Class='topo' ALIGN='center'><B>Slot</B></TD></TR>
							";

						for ($x = 0; $x<count($cards); $x++)
						{

								if ($CardTypes[$ctype[$x]]== NULL)
									$CdTypes = $CardTypes[1];
								else
									$CdTypes = $CardTypes[$ctype[$x]];


								$this->PrintInfoHardware($cards[$x], $CdTypes, $slotnum[$x]);

						}
						echo "
								<TR>
									<TD COLSPAN='2' Class='base' BGCOLOR='#FFFFCC' ALIGN='center'>
										<B>N&uacute;mero mais elevado de Slot</B>
									</TD><td Class='base' BGCOLOR='#FFFFCC' ALIGN='center'>
										<B>$cardSlots</B>
									</TD>
								</TR>
							</TABLE>
							";
				}

		}


		function MemoryFlash()
		{

			if (!ereg("[1-11]\.", $this->Version))
       		{
  				$oids = array
				(
				  "flashSupported"   => ".1.3.6.1.4.1.9.9.10.1.1.1.0",
				  "flashSize" 	   => ".1.3.6.1.4.1.9.9.10.1.1.2.1.2",
				  "flashFileName"	   => ".1.3.6.1.4.1.9.9.10.1.1.4.2.1.1.5",
				  "flashDeviceDescr" => ".1.3.6.1.4.1.9.9.10.1.1.2.1.8",
				  "flashFreeSpace"   => ".1.3.6.1.4.1.9.9.10.1.1.4.1.1.5"
				);
			}

				echo
					"<BR>
					<TABLE border=0	 CLASS='tabela' CELLPADDING='2' ALIGN='center' WIDTH='500'>
					<TR>
						<TH CLASS='thfont' COLSPAN='4'>&Iacute;ndices de Mem&oacute;ria Flash</TH>
					</TR>
					<TR BGCOLOR='#CDEDCF'>
						<TD Class='topo' ALIGN='center'><B>Filename</B></TD>
						<TD Class='topo' ALIGN='center'><B>Dispositivo</B></TD>
						<TD Class='topo' ALIGN='center'><B>Size (MB)</B></TD>
						<TD Class='topo' ALIGN='center'><B>Free (MB)</B></TD>
					</TR>";

                        	$flashSupported = $this->fc_snmpget($oids{'flashSupported'});
                              $flashSize = $this->fc_snmpwalk($oids{'flashSize'});


					for ($x = 0; $x< count($flashSize); $x++)
					{
                                 	$flashSize[$x] = round( ($flashSize[$x]/(1024*1024)), 1);

							if($flashSize[$x])
							{

									$MIBs =  $oids{'flashFileName'}    . '.' . ($x + 1) . ".1.1 " .
         									   $oids{'flashDeviceDescr'} . '.' . ($x + 1) . " " .
										   $oids{'flashFreeSpace'}   . '.' . ($x + 1) . ".1 "
					  						   ;
									list($flashFileName, $flashDeviceDescr, $flashFreeSpace) = $this->fc_snmpget($MIBs);

									$flashFreeSpace	= round(($flashFreeSpace/(1024*1024)), 1);

									$this->PrintInfoFlash($flashFileName, $flashDeviceDescr, $flashSize[$x], $flashFreeSpace);
							 }



					}



				echo 	"
					 </table>
					 ";
/*
				echo 	"
						<TR>
							<TD COLSPAN='2' CLASS='base' BGCOLOR='#FFFFCC' ALIGN='center'>
								<B>Total flash:</B> $flashSize Mb<br>
				 				<B>Livre:</B> $freeflash Mb
							 </TD>
						 </TR>
					 </table>
					 ";
*/
		}


            function status($status)
		{
				if (eregi("UP", $status))
					return ('UP');
				else
					return ('DOWN');
		}


		function HostName()
		{
			$Name = $this->fc_snmpget(".1.3.6.1.2.1.1.5.0");

			if ($Name==NULL)
				$this->Problemas();

			$this->sysName = $Name;
		}

		function Problemas()
		{
  			echo "<BR><BR>
			<DIV ALIGN='center'><H2>PROBLEMAS!!!&nbsp;&nbsp;&nbsp;<FONT COLOR='#0000FF'>" .
			$this->host .
			"</FONT></DIV></H2>
			<HR WIDTH='600' COLOR='#333399'>
			<PRE>
			<FONT FACE='Verdana' SIZE='3'>

			&gt;&gt;&gt;&nbsp;&nbsp;&nbsp;Verifique se o equipamento possui <B>'Hostname'</B>.

			&gt;&gt;&gt;&nbsp;&nbsp;&nbsp;Verifique se equipamento est&aacute; ligado. Usando comando <B>ping</B>.

			&gt;&gt;&gt;&nbsp;&nbsp;&nbsp;Verifique se o <B>IP</B> e/ou <B>Community</B> esta(&atilde;o) correto(s).
			</FONT>
			</PRE>
			";
			exit;
		}


		function ImprimeHost()
		{
				$chassis = $this->Chassis();
				echo "<FONT SIZE='+1' COLOR='#000000' FACE='Verdana'><B>&nbsp;&nbsp;&nbsp;" .
					$this->host . "</FONT>&nbsp;&nbsp;(Modelo: $chassis)</B><HR WIDTH='100%' COLOR='#006C36'>";
		}

		function Chassis()
		{
			$ChassisTypes[0] = "Desconhecido";
			$ChassisTypes[2] = "multibus";
			$ChassisTypes[3] = "agsplus";
			$ChassisTypes[4] = "igs";
			$ChassisTypes[5] = "c2000";
			$ChassisTypes[6] = "c3000";
			$ChassisTypes[7] = "c4000";
			$ChassisTypes[8] = "c7000";
			$ChassisTypes[9] = "cs500";
			$ChassisTypes[10] = "c7010";
			$ChassisTypes[11] = "c2500";
			$ChassisTypes[12] = "c4500";
			$ChassisTypes[13] = "c2102";
			$ChassisTypes[14] = "c2202";
			$ChassisTypes[15] = "c2501";
			$ChassisTypes[16] = "c2502";
			$ChassisTypes[17] = "c2503";
			$ChassisTypes[18] = "c2504";
			$ChassisTypes[19] = "c2505";
			$ChassisTypes[20] = "c2506";
			$ChassisTypes[21] = "c2507";
			$ChassisTypes[22] = "c2508";
			$ChassisTypes[23] = "c2509";
			$ChassisTypes[24] = "c2510";
			$ChassisTypes[25] = "c2511";
			$ChassisTypes[26] = "c2512";
			$ChassisTypes[27] = "c2513";
			$ChassisTypes[28] = "c2514";
			$ChassisTypes[29] = "c2515";
			$ChassisTypes[30] = "c3101";
			$ChassisTypes[31] = "c3102";
			$ChassisTypes[32] = "c3103";
			$ChassisTypes[33] = "c3104";
			$ChassisTypes[34] = "c3202";
			$ChassisTypes[35] = "c3204";
			$ChassisTypes[36] = "accessProRC";
			$ChassisTypes[37] = "accessProEC";
			$ChassisTypes[38] = "c1000";
			$ChassisTypes[39] = "c1003";
			$ChassisTypes[40] = "c1004";
			$ChassisTypes[41] = "c2516";
			$ChassisTypes[42] = "c7507";
			$ChassisTypes[43] = "c7513";
			$ChassisTypes[44] = "c7506";
			$ChassisTypes[45] = "c7505";
			$ChassisTypes[46] = "c1005";
			$ChassisTypes[47] = "c4700";
			$ChassisTypes[48] = "c2517";
			$ChassisTypes[49] = "c2518";
			$ChassisTypes[50] = "c2519";
			$ChassisTypes[51] = "c2520";
			$ChassisTypes[52] = "c2521";
			$ChassisTypes[53] = "c2522";
			$ChassisTypes[54] = "c2523";
			$ChassisTypes[55] = "c2524";
			$ChassisTypes[56] = "c2525";
			$ChassisTypes[57] = "c4700S";
			$ChassisTypes[58] = "c7206";
			$ChassisTypes[59] = "c3640";
			$ChassisTypes[60] = "as5200";
			$ChassisTypes[61] = "c1601";
			$ChassisTypes[62] = "c1602";
			$ChassisTypes[63] = "c1603";
			$ChassisTypes[64] = "c1604";
			$ChassisTypes[65] = "c7204";
			$ChassisTypes[66] = "c3620";
			$ChassisTypes[68] = "wsx3011";
			$ChassisTypes[72] = "c1503";
			$ChassisTypes[73] = "as5300";
			$ChassisTypes[74] = "as2509RJ";
			$ChassisTypes[75] = "as2511RJ";
			$ChassisTypes[77] = "c2501FRADFX";
			$ChassisTypes[78] = "c2501LANFRADFX";
			$ChassisTypes[79] = "c2502LANFRADFX";
			$ChassisTypes[80] = "wsx5302";
			$ChassisTypes[82] = "c12012";
			$ChassisTypes[84] = "c12004";
			$ChassisTypes[87] = "c2600";
			$ChassisTypes[165] = "c7606";
			$ChassisTypes[278] = "c7606";

			$chassis = $this->fc_snmpwalk(".1.3.6.1.4.1.9.3.6.1.0");

			if ($ChassisTypes[$chassis]==NULL)
				return $ChassisTypes[0];
			else
				return $ChassisTypes[$chassis];


		}


	      function syssrv($sv)
		{
				if ($sv &  1) {$srv = " Repeater"; }
				if ($sv &  2) {$srv = "$srv Bridge"; }
    				if ($sv &  4) {$srv = "$srv Router"; }
    				if ($sv &  8) {$srv = "$srv Gateway"; }
    				if ($sv & 16) {$srv = "$srv Session"; }
    				if ($sv & 32) {$srv = "$srv Terminal"; }
    				if ($sv & 64) {$srv = "$srv Application"; }
    				if (!$sv)     {$srv = "SNMP services not supported"; }
    			return $srv;
		}


		function sysVersion()
		{
			$ArrayVersion = $this->fc_snmpget(".1.3.6.1.2.1.1.1.0");

			for($i=0; $i<count($ArrayVersion); $i++)
			{
				if(eregi("Version",$ArrayVersion[$i]))
				{
					$aux = explode(",", stristr($ArrayVersion[$i], "Version "));
					return $aux[0];
				}
			}
		}

		function UpTime($sysUpTime)
		{

	          	$sysUpTime = substr($sysUpTime, 1);
			$aux = explode(")",$sysUpTime);
			$sysUpTime = substr($aux[0], 0, -2);

                  $this->UpTimeTickts = $sysUpTime;

			$day		= bcdiv($sysUpTime, 86400);
			$sysUpTime	= bcmod($sysUpTime, 86400);
			$hour		= bcdiv($sysUpTime, 3600);
			$sysUpTime	= bcmod($sysUpTime, 3600);
			$minute	= bcdiv($sysUpTime, 60);
			$sec		= bcmod($sysUpTime, 60);

				if ($day    == 1) { $daystr    = "Dia";    } 	else { $daystr    = "Dias";    }
				if ($hour   == 1) { $hourstr   = "hora";   } 	else { $hourstr   = "horas";   }
				if ($minute == 1) { $minutestr = "minuto"; } 	else { $minutestr = "minutos"; }
  				if ($sec    == 1) { $secstr    = "segundos"; }	else { $secstr    = "segundos"; }

			$this->sysUpTime = "$day $daystr $hour $hourstr $minute $minutestr e $sec $secstr";
			return $this->sysUpTime;
		}


            function PrintInfoGeral($th, $info)
		{
			$classe = $this->ParImpar();
			echo "
				<TR><TD Class='$classe' ><B>$th</B></TD><TD Class='$classe'>$info</TD></TR>";
		}


		function PrintInfoHardware($cards, $CdTypes, $slotnum)
		{
			$classe = $this->ParImpar();
			echo "
				<TR>
					<TD CLASS='$classe'>$cards</TD>
					<TD CLASS='$classe'>$CdTypes</TD>
					<TD CLASS='$classe'  ALIGN='center'>$slotnum</TD>
				</TR>
				";
		}


		function PrintInfoFlash($flashFileName, $flashDeviceDescr, $flashSize, $flashFreeSpace)
		{
			$classe = $this->ParImpar();
                         echo "
					<TR>
						<TD CLASS='$classe'> $flashFileName</TD>
				            <TD CLASS='$classe'>$flashDeviceDescr</TD>
						<TD CLASS='$classe'>$flashSize</TD>
						<TD CLASS='$classe'>$flashFreeSpace</TD>
					</TR>
					";
		}
            function ParImpar()
		{
			if( (bcmod($this->y,2))==0 )
				$classe = "par";
			else
				$classe = "impar";
			$this->y++;

			return $classe;
		}

		function ifMacAddress($Mac)
		{
			$ifMac='&nbsp;';

			$partes = explode(":", strtoupper($Mac));
			for($i=0; $i<count($partes); $i++)
			{
	if ($partes[$i]=='0')
                              $partes[$i]="00";

				$ifMac .= " " . $partes[$i];
			}


			return $ifMac;

		}


            function VerificaNull($x)
		{
			if($x==NULL)
			      return ('&nbsp');
			else
			      return ($x);
		}

		function LastChange($LsCh)
		{

      	      $LsCh	= substr($LsCh, 1);
			$aux	= explode(")",$LsCh);
			$LsCh	= substr($aux[0], 0, -2);

			$x = $this->UpTimeTickts - $LsCh;

			$day		= bcdiv($x, 86400);
			$x		= bcmod($x, 86400);
			$hour		= bcdiv($x, 3600);
			$x		= bcmod($x, 3600);
			$minute	= bcdiv($x, 60);
			$sec		= bcmod($x, 60);

			return "<B>$day</B>d <B>$hour</B>h <B>$minute</B>m e <B>$sec</B>s";
		}


} # fim da classe

?>

<?php
	// Desativa o relatorio de todos os erros
	error_reporting(E_ALL);

	$Host 	= $_POST["Host"];
	$Community 	= $_POST["Community"];
	$obj		= $_GET["obj"];

	$array = array( 
      "====== Equipamentos ======"	=> array("#",	"Host",			"Community"),
      "<------ Roteador ------>"	=> array("#",	"Host",			"Community"),

      "Mato Grosso do Sul"		=> array("1",	"loopgerms",		"startrek"),



); 

?>
<html>
<head>
<title>Informa&ccedil;&otilde;es das Interfaces</title>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<style type="text/css">

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
		font-size: 11pt; 
	}

.styletextbox 
	{
		BORDER-BOTTOM: 1px solid; 
		BORDER-LEFT: 1px solid; 
		BORDER-RIGHT: 1px solid; 
		BORDER-TOP: 1px solid; 
		FONT-FAMILY: Arial, Helvetica, sans-serif; 
		FONT-SIZE: 9pt; 
		WIDTH: 150px
	}

.botao
	{
		FONT-SIZE: 12px;
		COLOR: #000000; 
		FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif;
		FONT-WEIGHT: Bold;
		CURSOR: hand;
		WIDTH: 30px;
	}

.tabela 
	{
		border-width: 2px;
		border-style: solid;
		border-color: #FFA824;
	}

.par
	{
		background-color: #FFFFFF; 
		border-width: 1px;
		border-style: solid;
		border-color: #FFA824;
		FONT-FAMILY: Verdana, Arial; 
		FONT-SIZE: 9pt;
	}

.topo
	{
		background-color: #CDEDCF; 
		border-width: 1px;
		border-style: solid;
		border-color: #FFA824;
		FONT-FAMILY: Verdana, Arial; 
		FONT-SIZE: 9pt;
	}

.base
	{
		background-color: #FFFFCC; 
		border-width: 1px;
		border-style: solid;
		border-color: #FFA824;
		FONT-FAMILY: Verdana, Arial; 
		FONT-SIZE: 9pt;
	}

.impar
	{	
		background-color: #EEEEE6; 
		border-width: 1px;
		border-style: solid;
		border-color: #FFA824;
		FONT-FAMILY: Verdana, Arial; 
		FONT-SIZE: 9pt;
	}

.impar2
	{
		background-color: #FFFFCC; 
		border-width: 1px;
		border-style: solid;
		border-color: #FFA824;
		FONT-FAMILY: Verdana, Arial; 
		FONT-SIZE: 9pt;
	}

.thfont
	{
		background-color: #006C36; 
		border-width: 1px;
		border-style: solid;
		border-color: #006C36;
		FONT-FAMILY: Arial, Helvetica, sans-serif; 
		FONT-SIZE: 10pt; 
		COLOR: #FFFFFF; 
		FONT-WEIGHT: Bold;
	}

</style>

<script language="javascript">
function mudapagina(combo)
{
	var endereco = combo.value;
	if (endereco!="#" || endereco!="")
	{
		novapagina = window.location=endereco;
	}
}
</script>

<script language="JavaScript">
function validaForm()
{
		//validar Host
		d = document.equipamento;
		if (d.Host.value == ""){
			alert("O campo " + d.Host.name + " deve ser preenchido!\nDigite o Ip ou o Nome do Host.");
			d.Host.focus();
			return false;
		}
		//validar Host
		if (d.Community.value == ""){
			alert("O campo " + d.Community.name + " deve ser preenchido!");
			d.Community.focus();
			return false;
		}
		return true;
	}
</script>
</head>

<BODY BACKGROUND="snmp.gif">

<TABLE width="650" ALIGN="center" CELLSPACING="3" CELLPADDING="0">
  <TR>
	<td bgcolor="#0078B3" align="center" valign="middle" WIDTH="150" height="100%">
		<font color="#FFFFFF" size="3" face="Arial, Helvetica, sans-serif"><B>DIPRO<BR>Interfaces</font>
            <BR>
			<font color="#FFFFFF" size="1" face="Verdana">Versao 3.0</B></font>
	</td>
	<td align="center" valign="top">
		<form action="<?php $_SERVER['PHP_SELF']?>" method="POST" name="equipamento" id="rede_acesso" onSubmit="return validaForm()">
        	<table border="0" width="100%" cellspacing="0" height="65" cellpadding="2" ALIGN="center">
            <tr>
               	<td width="100%" height="55%" ALIGN="center" VALIGN="middle" bgcolor="#B3D9FF" >
				<select size="1" name="Host" onChange="mudapagina(this);">
   
<?php   

   	reset($array);
	 while ($NewArray = current($array)) 
	{ 
    		$option= key($array);
			
			echo "<option value='" .  $_SERVER['PHP_SELF'] . "?obj=" . $NewArray[0] . "'>" . $option . "\n";
      
		  	next($array); 
  	} 

?>
  				</select>
            </td>
        </tr>
        <tr>
        	<td width="100%" height="45%" bgcolor="#0099CC" ALIGN="center" VALIGN="middle">
  				<FONT FACE="Arial" COLOR="#FFFFFF" SIZE="2"><B>Host&nbsp;</B></FONT>
				  	<input type="text" name="Host" CLASS="styletextbox"  MAXLENGTH="18">
				    &nbsp;<FONT FACE="Arial" COLOR="#FFFFFF" SIZE="2"><B>Community&nbsp;</B></FONT>
					<input type="password" name="Community" MAXLENGTH="15" CLASS="styletextbox">
				&nbsp;&nbsp;<input  class="botao" name="cadastrar" type="submit" id="OK" value="OK">
            </td>
        </tr>
    		</table>
  </TR>
		</form>	
</TABLE>	


<?php
if($obj!=NULL &&  $Host==NULL && $Community==NULL)
{
	reset($array); 
	while ($Vet = current($array)) 
	{ 
    		$option = key($array);
			
			if ($Vet[0]== $obj)		
			{
				$Host= $Vet[1];
				$Community=$Vet[2];
			}
    	next($array); 
  	} 

}

if($Host!=NULL)
{
      $obj = new SnmpLinux;

	$obj->SetHost($Host, $Community);

	$obj->HostName(); # se hostName falhar - deixa a execucao do programa
	
	chamadas($obj);

}

	function chamadas($obj)
	{
		$obj->ImprimeHost();
		echo "antes";

		$obj->Geral();

		echo "depois";

		echo "<TABLE BORDER='0' WIDTH='100%' CELLSPACING='3' CELLPADDING='1'><TR><TD VALIGN='top'>";
			$obj->Hardware();
		echo "</TD><TD VALIGN='top'>";
			$obj->MemoryFlash();
		echo "</TD></TR></TABLE>";

		$obj->Interfaces();

	}

?>
<BR>
</BODY>
</HTML>
