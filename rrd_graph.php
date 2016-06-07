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


class RRDGraph
{
# Funcao de conexao com o BD
	var $conexao;

# Funcoes de Diretorios
	var $dir_rrd_trafego;
	var $dir_fig;
	var $dir_rrd_rly;
	var $Path_RRD;

# Relacionados a figura
	var $periodo;
	var $figura;
	var $arq_rrd;


# Funcao que inicia variaveis
	var $cod_int;
	var $nome;
	var $gerencia;
	var $uf;
	var $serial;
	var $host;
	var $ip_orig;
	var $tipo;
	var $circuito;
	var $porta;
	var $cir_in;
	var $cir_out;
	var $qtd_estacoes;
	var $serial_dest;
	var $checked;


##########
	var $step;
	var $escala;
	var $media;


		function RRDGraph()
		{
				$this->SetPeriodo();
				$this->conexao();
				$this->SetDiretorios();
		}

/////////////////////////////
/*
		function conexao()
		{
				$host_db	= "localhost";
				$user_db	= "admcocar";
				$password_db 	= "cocar";
				$this->conexao = mysql_pconnect($host_db, $user_db, $password_db);

				define(BANCO, "rede_acesso");
				define(TABELA, "rede_acesso");
				define(CONFIGURACOES, "configuracoes");

				mysql_select_db(BANCO);
		}
*/
/////////////////////////////


		function conexao()
		{
			include("config/conexao.php");

			$this->conexao = conexao();

			mysql_select_db(BANCO) or die ('nao conectou BD');
		}

		function Desconecta()
		{
            	mysql_close() or die ('nao desconectou');
		}

		function SetLog($log)
		{
			$this->cod_int	= $log;
			$this->InicVar();
           		$this->RemoveFigura();
		}


		function SetDiretorios()
		{
			$query  = "SELECT dir_rrd_trafego, dir_fig, dir_rrd_rly, Path_RRD FROM " . CONFIGURACOES . " WHERE codigo = '1'";
			$result = mysql_query($query);

			list ($this->dir_rrd_trafego, $this->dir_fig, $this->dir_rrd_rly, $this->Path_RRD) = mysql_fetch_array($result,MYSQL_NUM);
		}


		function SetPeriodo($periodo = "6hours")
		{
			$this->periodo= $periodo;
			$this->Escalas();
		}


		function SetChecked($funcao)
		{
			$this->checked[] = $funcao;
		}


		function Escalas($type=1)
		{
			if($type==1)
			{
				switch (strtolower($this->periodo))
				{
					case '6hours':		$this->escala = "6 Horas";	$this->step=60;		$this->media="1 min";		break;
					case '8hours':		$this->escala = "8 Horas";	$this->step=60;		$this->media="1 min";		break;
					case '1day': 		$this->escala = "1 Dia";	$this->step=300;	$this->media="5 min";		break;
					case '40hours':		$this->escala = "40 Horas";	$this->step=300;	$this->media="5 min";		break;
					case '160hours':	$this->escala = "1 Semana";	$this->step=1200;	$this->media="20 min";		break;
					case '720hours':	$this->escala = "1 Mes";	$this->step=5400;	$this->media="90 min";		break;
					case '4months': 	$this->escala = "4 Meses";	$this->step=21600;	$this->media="360 min";		break;
					case '1year':		$this->escala = "1 Ano";	$this->step=86400;	$this->media="1440 min";	break;
					default: 			$this->escala = $this->periodo; 				$this->media="desconhecido";
				}
			}
			elseif($type==2)
			{
				switch (strtolower($this->periodo))
				{
					case '6hours':		$this->escala = "6 Horas";	$this->step=600;	$this->media="10 min";		break;
					case '8hours':		$this->escala = "8 Horas";	$this->step=600;	$this->media="10 min";		break;
					case '1day': 		$this->escala = "1 Dia";	$this->step=600;	$this->media="10 min";		break;
					case '40hours':		$this->escala = "40 Horas";	$this->step=600;	$this->media="10 min";		break;
					case '160hours':	$this->escala = "1 Semana";	$this->step=1200;	$this->media="20 min";		break;
					case '720hours':	$this->escala = "1 Mes";	$this->step=5400;	$this->media="90 min";		break;
					case '4months': 	$this->escala = "4 Meses";	$this->step=21600;	$this->media="360 min";		break;
					case '1year':		$this->escala = "1 Ano";	$this->step=86400;	$this->media="1440 min";	break;
					default: 			$this->escala = $this->periodo; 				$this->media="desconhecido";
				}
			}

		}


		function InicVar()
		{
			if($this->cod_int=="")
			{
					echo "<H1>Erro: Falta Informar a Interface.</H1>";
					exit;
			}

			$consulta = "SELECT nome, gerencia, uf, serial, host, ip_orig, tipo, circuito, porta, cir_in, cir_out, qtd_estacoes, serial_dest FROM " . TABELA . " WHERE cod_int='" . $this->cod_int . "'";
			$matriz = mysql_query($consulta);

			if (mysql_num_rows($matriz)==0)
			{
					echo "<H1>Erro: Valor: <FONT COLOR='#FF0000'>" . $this->cod_int . "</FONT> inexistente.</H1>";
					exit;
			}


				list(
						$this->nome,	$this->gerencia,	$this->uf,			$this->serial,	$this->host,
						$this->ip_orig,	$this->tipo, 		$this->circuito,	$this->porta,	$this->cir_in,
						$this->cir_out, $this->qtd_estacoes,$this->serial_dest
					) = mysql_fetch_row($matriz);


			mysql_free_result($matriz);
			$this->nome = str_replace("'","",$this->nome);
			$this->tipo = strtoupper($this->tipo);
			$this->uf = strtoupper($this->uf);
			$this->checked[]="";

		}


		function RemoveFigura()
		{
			$com = "rm -fr " . $this->dir_fig . $this->cod_int . "*.png";
			shell_exec($com);
		}


		function ErroNoExist()
		{
				if (!file_exists( ($this->dir_rrd_trafego . $this->arq_rrd) ))
				{
					echo "<H1>Erro: A Unidade: <FONT COLOR='#FF0000'>" . $this->nome . "</FONT> nao monitorada.</H1>";
					exit;
				}
		}


		function HeaderHtml()
		{

			$ip = $this->PontaRemota();


			echo "<DIV ALIGN='center'>
						<TABLE WIDTH='100%' BORDER='0' border='0' cellspacing='0' cellpadding='0'>
							<TR HEIGHT='28'>
								<TD VALIGN='middle'  ALIGN='center' WIDTH='50'>";

							if($this->tipo=="C")
									echo "<A HREF='telnet://" . $ip . "' TITLE='TELNET'><IMG SRC='imagens/telnet.gif' ALIGN='middle' BORDER='0'></A>";
							else
									echo "<IMG SRC='imagens/telnet.gif' ALIGN='middle' BORDER='0'>";

						echo "
								</TD>
								<TD VALIGN='middle' CLASS='tht'><FONT COLOR='#0000FF'>" .
									$this->uf . "</FONT>:&nbsp;&nbsp;" . $this->nome . " - " . $this->gerencia;

						if($this->tipo=="C")
								echo  " (" . $this->ip_orig . ") N&ordm; " . $this->circuito;

						echo  "
								</TD>
							</TR>
						</TABLE>
						<table  width='100%'  border='0' cellspacing='0' cellpadding='2'>
							<tr HEIGHT='28'>
					    		<td CLASS='tdt' BGCOLOR='#FFE0C1'><B>"	. $this->serial	. "</B></td>
					    		<td CLASS='tdt' ><B>Porta: "	. $this->porta	. " KB</B></td>
					    		<td CLASS='tdt' ><B>Cir In: "	. $this->cir_in	. " KB</B></td>
								<td CLASS='tdt' ><B>Cir Out: "	. $this->cir_out . " KB</B></td>
							<TR>
					</TABLE>
					<TABLE width='100%'  border='0'>
							<TR>
								<TD>
									<form method='GET' action='" . $_SERVER['PHP_SELF'] . "' name='select_graph' >
										<table  width='100%'  border='0' cellspacing='0' cellpadding='3'>
											<tr>
												<td CLASS='tdt' BGCOLOR='#FFCC99'>";

												if(in_array("Todos",$this->checked)) $checked="checked"; else $checked="";
												echo "&nbsp;&nbsp;<input type='checkbox' name='Todos' value='ON' $checked >Todos";

												if(in_array("Traf",$this->checked)) $checked="checked"; else $checked="";
												echo "&nbsp;&nbsp;<input type='checkbox' name='Traf' value='ON' $checked>Tr&aacute;fego";

 												if($this->tipo=="C" && !eregi("ETH",$this->serial))
												{
													if(in_array("Conc",$this->checked)) $checked="checked"; else $checked="";
													echo "&nbsp;&nbsp;<input type='checkbox' name='Conc' value='ON' $checked>Concentradora";
												}

												#if(in_array("Perc",$this->checked)) $checked="checked"; else $checked="";
												# echo "&nbsp;&nbsp;<input type='checkbox' name='Perc' value='ON' $checked>Perc";
									/*

												if( $this->tipo=="C" && !eregi("Eth", $this->serial)	)
												{
														if(in_array("Estac",$this->checked)) $checked="checked"; else $checked="";
														echo "&nbsp;&nbsp;<input type='checkbox' name='Estac' value='ON'>Esta&ccedil;&otilde;es";
												}
									*/

												if(in_array("Rly",$this->checked)) $checked="checked"; else $checked="";
												echo "&nbsp;&nbsp;<input type='checkbox' name='Rly' value='ON' $checked>Confiabilidade";


												#if(in_array("Delay",$this->checked)) $checked="checked"; else $checked="";
												#echo "&nbsp;&nbsp;<input type='checkbox' name='Delay' value='ON' $checked>Delay";




						echo "
													&nbsp;
													<select name='periodo' size='1' CLASS='mn_box'>
														<option value='6hours'>Periodo
														<option value='6hours'>6 horas
														<option value='8hours'>8 horas
														<option value='1day'>1 dia
														<option value='40hours'>40 horas
														<option value='160hours'>1 semana
														<option value='720hours'>1 mes
														<option value='4months'>4 meses
														<option value='1year'>1 ano
													</select>
        											&nbsp;&nbsp;
													<input  class='botao' name='cadastrar' type='submit' id='OK' value='OK'>
		    										<INPUT TYPE='hidden' NAME='log' VALUE='" . $this->cod_int . "'>
												</td>
    										</tr>
										</TABLE>
									</form>
								</TD>
							</TR>
						</TABLE>
				</DIV>";

		}


		function PontaRemota()
		{
				if($this->ip_orig!="" && $this->ip_orig!="-")
				{
						$aux = explode(".",$this->ip_orig);
			 			return ($aux[0] . "." . $aux[1] . "." . $aux[2] . "." . ($aux[3]+1));
				}
				else
						return ($this->ip_orig);
		}

		function TodosGraph()
		{

			# Grafico de Trafego
			$this->GraphTrafego();


			#Grafico da Concentradora
			if($this->tipo=="C" && !eregi("ETH",$this->serial) )
				$this->GraphTrafegoConcentradora();

			#RELIABILITY
			$this->GraphRly();

			/*
				#Percentual de Utilizacao
				$this->GraphPercTrafego();

				#delay
				$this->GraphDelay();
			*/
		}


		function PrintGraph()
		{
			echo "
				<TABLE BORDER='0' WIDTH='100%' ALIGN='center' >
					<TR>
						<TD ALIGN='center' VALIGN='middle'>
							<img src='http://" . $_SERVER['SERVER_NAME'] . "/graficos/" . $this->figura . "'>
						</TD>
					</TR>
					</TABLE><BR>
		";
		}


		function GraphTrafego()
		{
			$this->arq_rrd	= $this->cod_int . ".rrd";

			$this->ErroNoExist();

			($this->cir_in >= $this->cir_out)?
					$cir=$this->cir_in:$cir=$this->cir_out;

					$cir_bits	= ($cir*1000);

				$this->figura = $this->cod_int . "_" . $this->periodo . "_" . date('U') . ".png";
				$this->Escalas(1);

					           $com =  $this->Path_RRD . "rrdtool graph  " . $this->dir_fig . $this->figura .
                                                " --start -". $this->periodo . " --end now --step ". $this->step .
                                                " --title='" . $this->nome . " - " . $this->escala . " (" . $this->media . ")' ".
                                                "--vertical-label 'Trafego em Bits/s' " .
                                                "--width 480 --height 162 " .
                                                "DEF:in=" . $this->dir_rrd_trafego . $this->arq_rrd . ":ds0:AVERAGE " .
                                                "DEF:out=" . $this->dir_rrd_trafego .  $this->arq_rrd . ":ds1:AVERAGE " .
                                                "CDEF:bitIn=in,8,* " .
                                                "CDEF:bitOut=out,8,* " .
								"COMMENT:'        ' ";

							if($this->tipo=="C" && !eregi("ETH",$this->serial))
							{
								$com .=
									  "HRULE:$cir_bits#FF0000:'CIR = $cir  ' " .
						  			  "COMMENT:'\\n' " .
						  			  "COMMENT:'        ' ";
							}

							$com .=
								"COMMENT:'        ' ".
                                                "AREA:bitIn#00CC00:'Entrada DATAPREV  ' " .
                                                "LINE1:bitOut#0000FF:'Saida DATAPREV' " .
  								"COMMENT:'\\n' ".
								"COMMENT:'        ' ".
								"GPRINT:bitIn:MAX:'Maximo\\:%14.1lf %sbit/s' ".
  								"GPRINT:bitOut:MAX:'%11.1lf %sbit/s' ".
								"COMMENT:'\\n' ".
								"COMMENT:'        ' ".
								"GPRINT:bitIn:AVERAGE:'Media\\:%15.1lf %sbit/s' ".
								"GPRINT:bitOut:AVERAGE:'%11.1lf %sbit/s' ".
								"COMMENT:'\\n' ".
								"COMMENT:'        ' ".
								"GPRINT:bitIn:LAST:'Ultima\\:%14.1lf %sbit/s' " .
								"GPRINT:bitOut:LAST:'%11.1lf %sbit/s' ";

						shell_exec($com);
						$this->PrintGraph();

		}


		function GraphTrafegoConcentradora()
		{
			list($se,$lixo) = explode(".",$this->serial);

			$query =
				" SELECT cod_int, nome " .
				" FROM " . TABELA .
				" WHERE uf='" . $this->uf . "' AND serial= '$se'";

			$result = mysql_query($query);
                  list ($cod_int, $nome) = mysql_fetch_row($result);


			$arq_rrd	= $cod_int . ".rrd";

				$this->figura = $cod_int . "_" . $this->periodo . "_" . date('U') . ".png";
				$this->Escalas(1);

					           $com =  $this->Path_RRD . "rrdtool graph  " . $this->dir_fig . $this->figura .
                                                " --start -". $this->periodo . " --end now --step ". $this->step .
                                                " --title='Concentradora: $se - " . $nome . " - " . $this->escala . " (" . $this->media . ")' ".
                                                "--vertical-label 'Trafego em Bits/s' " .
                                                "--width 480 --height 162 " .
                                                "DEF:in=" . $this->dir_rrd_trafego  . $arq_rrd . ":ds0:AVERAGE " .
                                                "DEF:out=" . $this->dir_rrd_trafego . $arq_rrd . ":ds1:AVERAGE " .
                                                "CDEF:bitIn=in,8,* " .
                                                "CDEF:bitOut=out,8,* " .
								"COMMENT:'        ' " .
								"COMMENT:'        ' ".
                                                "AREA:bitIn#00CC00:'Entrada DATAPREV  ' " .
                                                "LINE1:bitOut#0000FF:'Saida DATAPREV' " .
  								"COMMENT:'\\n' ".
								"COMMENT:'        ' ".
								"GPRINT:bitIn:MAX:'Maximo\\:%14.1lf %sbit/s' ".
  								"GPRINT:bitOut:MAX:'%11.1lf %sbit/s' ".
								"COMMENT:'\\n' ".
								"COMMENT:'        ' ".
								"GPRINT:bitIn:AVERAGE:'Media\\:%15.1lf %sbit/s' ".
								"GPRINT:bitOut:AVERAGE:'%11.1lf %sbit/s' ".
								"COMMENT:'\\n' ".
								"COMMENT:'        ' ".
								"GPRINT:bitIn:LAST:'Ultima\\:%14.1lf %sbit/s' " .
								"GPRINT:bitOut:LAST:'%11.1lf %sbit/s' ";

						shell_exec($com);
						$this->PrintGraph();

		}


		function GraphPercTrafego()
		{
			$this->arq_rrd	= $this->cod_int . ".rrd";

			$this->ErroNoExist();

				$this->figura = $this->cod_int . "_" . $this->periodo . "_percent_" . date('U') . ".png";
				$this->Escalas(1);

                               $com =  $this->Path_RRD . "rrdtool graph  " . $this->dir_fig .$this->figura .
                                                " --start -". $this->periodo . " --end now --step ". $this->step .
                                                " --title='Percentual de Utilizacao da Concentradora' ".
                                                "--vertical-label 'Percentual' " .
                                                "--width 480 --height 162 " .
                                                "DEF:in=" . $this->dir_rrd_trafego .  $this->arq_rrd . ":ds0:AVERAGE " .
                                                "DEF:out=" . $this->dir_rrd_trafego .  $this->arq_rrd . ":ds1:AVERAGE " .
                                                "CDEF:bitIn=in,0.8,*," . $this->porta . ",/ " .
                                                "CDEF:bitOut=out,0.8,*," . $this->porta . ",/ " .
												"COMMENT:'        ' ".
												"COMMENT:'        ' ".
                                                "AREA:bitIn#00CC00:'Entrada DATAPREV  ' " .
                                                "LINE1:bitOut#0000FF:'Saida DATAPREV' " .
  												"COMMENT:'\\n' " .
												"COMMENT:'        ' ".
												"GPRINT:bitIn:MAX:'Maximo\\:%18.1lf %%' " .
  												"GPRINT:bitOut:MAX:'%17.1lf %%' " .
												"COMMENT:'\\n' " .
												"COMMENT:'        ' ".
												"GPRINT:bitIn:AVERAGE:'Media\\:%19.1lf %%' " .
												"GPRINT:bitOut:AVERAGE:'%17.1lf %%' " .
  												"COMMENT:'\\n' " .
												"COMMENT:'        ' ".
												"GPRINT:bitIn:LAST:'Ultima\\:%18.1lf %%' " .
  												"GPRINT:bitOut:LAST:'%17.1lf %%' "
												;

						shell_exec($com);
						$this->PrintGraph();

		}


		function GraphQtdEstacoes()
		{
			$this->arq_rrd	= $this->cod_int . ".rrd";

			$this->ErroNoExist();

			if(strtoupper($this->tipo)!="P")
			{
					$this->figura = $this->cod_int . "_" . $this->periodo . "_estacoes_" . date('U') . ".png";
					$this->Escalas(1);

					($this->qtd_estacoes==0)?
						$QtdEstacoes=1:$QtdEstacoes=$this->qtd_estacoes;

					$com =	$this->Path_RRD . "rrdtool graph  " . $this->dir_fig . $this->figura .
							" --start -" . $this->periodo . " --end now --step ". $this->step .
							" --title='Trafego medio por Estacoes cadastradas no SART: Total de " . $this->qtd_estacoes . "' " .
							"--vertical-label 'Bits/s' " .
							"--width 480 --height 162 " .
							"DEF:in=" . $this->dir_rrd_trafego .  $this->arq_rrd . ":ds0:AVERAGE " .
							"DEF:out=" . $this->dir_rrd_trafego .  $this->arq_rrd . ":ds1:AVERAGE " .
							"CDEF:bitIn=in,8,*," . $QtdEstacoes . ",/ " .
							"CDEF:bitOut=out,8,*," . $QtdEstacoes . ",/ " .
							"COMMENT:'        ' ".
							"COMMENT:'        ' ".
							"AREA:bitIn#00CC00:'Entrada DATAPREV  ' " .
	                       	"LINE1:bitOut#0000FF:'Saida DATAPREV' " .
							"COMMENT:'\\n' ".
							"COMMENT:'        ' ".
							"GPRINT:bitIn:MAX:'Maximo\\:%14.1lf %sbit/s' ".
	  						"GPRINT:bitOut:MAX:'%11.1lf %sbit/s' ".
							"COMMENT:'\\n' ".
							"COMMENT:'        ' ".
							"GPRINT:bitIn:AVERAGE:'Media\\:%15.1lf %sbit/s' ".
							"GPRINT:bitOut:AVERAGE:'%11.1lf %sbit/s' ".
							"COMMENT:'\\n' ".
							"COMMENT:'        ' ".
							"GPRINT:bitIn:LAST:'Ultima\\:%14.1lf %sbit/s' " .
							"GPRINT:bitOut:LAST:'%11.1lf %sbit/s' ";

							shell_exec($com);

						if ($QtdEstacoes==1)
							echo "<P ALIGN='center'><FONT FACE='Verdana' SIZE='+2'>Quantidade de Estacoes nao cadastrada.</FONT></P>";

						$this->PrintGraph();
			}
		}


		function GraphRly()
		{

				$this->arq_rrd	= $this->cod_int . "_rly.rrd";

					if (file_exists(($this->dir_rrd_rly . $this->arq_rrd)) && !eregi("Eth",$this->serial) )
					{
							$this->figura = $this->cod_int . "_" . $this->periodo . "_rly_" . date('U') . ".png";
							$this->Escalas(2);

							if(strtoupper($this->tipo)=="C")
							{
									$title = "Ponta - " . $this->nome . " - " . $this->serial_dest . " - " . $this->escala . " (" . $this->media . ")";
									$this->GraphRlyPrincipal($title);
									$this->GraphRlyConc();
							}
							else
							{
									$title = "Concentradora DATAPREV - " . $this->serial . " - " . $this->escala . " (" . $this->media . ")";
									$this->GraphRlyPrincipal($title);
							}
					}
			}

		function GraphRlyPrincipal($title)
		{
					$com =	$this->Path_RRD . "rrdtool graph  " . $this->dir_fig . $this->figura .
						" --start -". $this->periodo . " --end now --step ". $this->step .
						" --title='$title' ".
						"--vertical-label 'Confiabilidade' -w 480 -h 162 " .
						"DEF:myrly=" . $this->dir_rrd_rly . $this->arq_rrd . ":rly:AVERAGE " .
						"CDEF:valor=myrly " .
						"CDEF:ideal=valor,255,EQ,valor,0,IF " .
						"CDEF:baixo=valor,255,EQ,0,valor,IF " .
						"HRULE:255#0000FF:'Valor Ideal = 255       ' " .
						"AREA:ideal#80FF80:'Normal       ' "  .
						"AREA:baixo#FE3C36:'Critico\\c' " .
						"COMMENT:'\\n' ".
						"COMMENT:'        ' ".
						"GPRINT:valor:MIN:'Valor Minimo = %10.0lf' " .
						"COMMENT:'\\n' ".
						"COMMENT:'        ' ".
						"GPRINT:valor:LAST:'Ultimo Valor = %10.0lf' "
						;

					shell_exec($com);
					$this->PrintGraph();
		}


		function GraphRlyConc()
		{

			list($Concentradora,$x) = explode(".", $this->serial);
			$query	= "SELECT cod_int FROM " . TABELA . " WHERE uf='" . $this->uf . "' AND serial='" . $Concentradora . "'";
			$result	= mysql_query($query);
			list($cod_conc) = mysql_fetch_row($result);

				$this->arq_rrd	= $cod_conc . "_rly.rrd";

				if (file_exists(($this->dir_rrd_rly . $this->arq_rrd)) && !eregi("Eth",$Concentradora) )
				{
					$this->figura = $cod_conc . "_" . $this->periodo . "_rly_" . date('U') . ".png";
					$this->Escalas(2);

					$com =	$this->Path_RRD . "rrdtool graph  " . $this->dir_fig . $this->figura .
						" --start -". $this->periodo . " --end now --step ". $this->step .
						" --title='Ponta DATAPREV - $Concentradora - " . $this->escala . " (" . $this->media . ")' ".
						"--vertical-label 'Confiabilidade' -w 480 -h 162 " .
						"DEF:myrly=" . $this->dir_rrd_rly . $this->arq_rrd . ":rly:AVERAGE " .
						"CDEF:valor=myrly " .
						"CDEF:ideal=valor,255,EQ,valor,0,IF " .
						"CDEF:baixo=valor,255,EQ,0,valor,IF " .
						"HRULE:255#0000FF:'Valor Ideal = 255       ' " .
						"AREA:ideal#80FF80:'Normal       ' "  .
						"AREA:baixo#FE3C36:'Critico\\c' " .
						"COMMENT:'\\n' ".
						"COMMENT:'        ' ".
						"GPRINT:valor:MIN:'Valor Minimo = %10.0lf' " .
						"COMMENT:'\\n' ".
						"COMMENT:'        ' ".
						"GPRINT:valor:LAST:'Ultimo Valor = %10.0lf' "
						;

					shell_exec($com);
					$this->PrintGraph();
			}

		}

} # FIM DA CLASSE

?>

<?php
/********************************************************
	Gerar graficos utilizando RRDTool
********************************************************/

//error_reporting(0);

	$log		= $_GET["log"];
	$periodo	= $_GET["periodo"];
	$Todos 	= $_GET["Todos"];
	$Traf 	= $_GET["Traf"];
	$Rly 	 	= $_GET["Rly"];
	$Conc	 	= $_GET["Conc"];


?>

<HTML>
<HEAD>
<TITLE> :: DIPRO :: </TITLE>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<script language="javascript">
function mudapagina(combo)
{
	var endereco = combo.value;
	if (endereco != "#")
	{
		novapagina = window.location=endereco;
	}
}
</script>
<script language="JavaScript">
	function validaForm(){
		d = document.select_graph;
		//validar user
		if (d.periodo.value == ""){
			alert("Selecione o Periodo!");
			d.periodo.focus();
			return false;
		}
		return true;
	}
</script>
<style>

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

.tht
	{
		font-size: 11pt;
		font-family: Arial, Verdana;
		font-weight: bold;
		text-align: left;
		text-valign: middle
	}

.tdt
	{
		font-size: 10pt;
		font-family: Verdana, Arial;
		font-weight: bold;
		text-align: center;
		text-valign: middle
	}

.mn_box
	{
		font-family: Verdana, Arial;
		font-size: 10pt;
		color: #000000;
		background-color: #FAF8EB;
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
</style>
</HEAD>

<BODY BACKGROUND="imagens/fundoko.gif">

<?php

if ($log==NULL)
{
	echo "<H1>Erro: Falta Informar a Interface.</H1>";
	exit;
}

	$graph = new RRDGraph;
	$graph->SetLog($log);


	if($periodo!=NULL)
		$graph->SetPeriodo($periodo);

/////////////////////////////////
if ($Todos=="ON")
	$graph->SetChecked("Todos");
else
{
	if($Traf=="ON")
		$graph->SetChecked("Traf");

	if($Perc=="ON")
		$graph->SetChecked("Perc");

	if($Conc=="ON")
		$graph->SetChecked("Conc");

	if($Rly=="ON")
		$graph->SetChecked("Rly");
}

if($Todos==NULL && $Traf==NULL && $Rly==NULL && $Conc==NULL )
		$graph->SetChecked("Traf");

/////////////////////////////////

	$graph->HeaderHtml();

if ($Todos=="ON")
{
	$graph->TodosGraph();
}
else
{
	if($Traf=="ON")
		$graph->GraphTrafego();

	if($Conc=="ON")
		$graph->GraphTrafegoConcentradora();

#	if($Perc=="ON")
#		$graph->GraphPercTrafego();

#	if($Estac=="ON")
#		$graph->GraphQtdEstacoes();

	if($Rly=="ON")
		$graph->GraphRly();
}


	if($Todos==NULL && $Traf==NULL && $Rly==NULL && $Conc==NULL )
	{
			$graph->GraphTrafego();
	}

$graph->Desconecta();
?>

</BODY>
</HTML>
