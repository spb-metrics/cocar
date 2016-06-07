<?php
error_reporting(0);
        $arg = $_SERVER['argv'];
        $Path_RRD = "/usr/bin/";
        $dir_rrd = "/var/www/rrd/";
?>

<?php
##################################################
##      INCLUINDO ARQUIVOS DE CONFIGURACAO      ##
##################################################

        ## CONFIGURACAO DE CONEXAO COM MYSQL
        include("config/conexao.php");
        $conexao = conexao();

##################################################

        mysql_select_db(BANCO);

        $tabela = "bckbone";
        define(TabelaServico,"SwitchService");

        if ($arg == NULL)
        {
                echo "ERRO:   FALTOU INFORMAR O GRUPO!!!\n";
                exit;
        }

$sql = "SELECT DISTINCT community, host FROM $tabela WHERE uf='$arg[1]'";
if ($arg[1] == "AP") $sql = "SELECT DISTINCT community_dest, host_dest FROM $tabela WHERE uf='$arg[1]'";

        $consulta = mysql_query($sql) or die ("ERRO: ". mysql_error()."\n");
        list($C, $H) = mysql_fetch_row($consulta);
        $sysName = shell_exec("snmpget -Ov -t 1 -r 1 -c $C -v 1 $H .1.3.6.1.2.1.1.5.0");

$sql = "SELECT cod_int, host, community, num_int FROM $tabela WHERE uf='$arg[1]' AND IfStatus='UP' ORDER BY num_int";
if ($arg[1] == "AP") $sql = "SELECT cod_int, host_dest, community_dest, num_int_dest FROM $tabela WHERE uf='$arg[1]' AND IfStatus='UP' ORDER BY num_int";

                $consulta = mysql_query($sql);

        while ( list ($cod_int, $host, $community, $num_int) = mysql_fetch_array($consulta,MYSQL_NUM))
        {
		echo "$community - $host - $num_int";

                $com = "snmpget -Ov -t 1 -r 1 -c $community -v 1 $host .1.3.6.1.2.1.2.2.1.10.$num_int .1.3.6.1.2.1.2.2.1.16.$num_int";
                list ($In, $Out) = explode("\n", shell_exec($com));

                $InOctets = snmp($In);
                $OutOctets = snmp($Out);
if ($arg[1] == "AP") 
{
	$TempOctets = $InOctets;
	$InOctets = $OutOctets;
	$OutOctets = $TempOctets;
}
                  if($InOctets || $OutOctets)
                        {
                                $arq_rrd = $dir_rrd . $cod_int . '.rrd';

                                if (!file_exists($arq_rrd))
                                        create_rrd($arq_rrd);

                        update_rrd($arq_rrd, $InOctets, $OutOctets);
                    }
        }
        function snmp($resp)
        {
                $resp = strstr($resp, ':');
                $resp = str_replace(":", "", $resp);
                return (trim($resp));
        }

        function update_rrd($arq_rrd, $in, $out)
        {
                        global $Path_RRD;

                $data = date('U');
                $insert = $Path_RRD . "rrdtool update $arq_rrd $data:$in:$out";

                        shell_exec($insert);

        }

        function create_rrd($arq_rrd)
        {
                        global $Path_RRD;

                        $create =       $Path_RRD . "rrdtool create $arq_rrd --step 60 " .
                                                "DS:ds0:COUNTER:120:0:125000000 " .
                                                "DS:ds1:COUNTER:120:0:125000000 " .
                                                "RRA:AVERAGE:0.5:1:4320 " .
                                                "RRA:AVERAGE:0.5:5:2016 " .
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

        function SwitchHabilitado($maq)
        {
                        $update =   "UPDATE " .  TabelaServico . " " .
                                        "SET desabilitado='0'
                                        WHERE equipamento='$maq'
                                        ";

                                        mysql_query($update);
        }

        function SwitchDesabilitado($maq)
        {
                $select =       "
                                SELECT desabilitado
                                        FROM " . TabelaServico . " " .
                                        "WHERE equipamento='$maq'
                                ";

                $consulta = mysql_query($select);
                list ($desabilitado) = mysql_fetch_row($consulta);


                        if($desabilitado==0)
                        {
                                echo ">>> EQUIPAMENTO $maq: INEXISTENTE, NAO RESPONDE OU NAO POSSUI HOSTNAME ! ! !\n";

                                        $update =
                                                        "UPDATE " .  TabelaServico . " " .
                                                        "SET desabilitado='1'
                                                        WHERE equipamento='$maq'
                                                        ";

                                        mysql_query($update);
                        }
                        elseif($desabilitado>=30)
                        {
                                        $para = "eduardo.vale@previdencia.gov.br";
                                        $assunto = "Probelmas Switch $maq";
                                        $mensagem = "DIPRO - " . date('d:m:Y H:i') . "\n" .
                                                        "AVISO!\n\n".
                                                        "Equipamento $maq\n nao esta monitorando"
                                                        ;
                                        $headers = 'De: Mandacaru <system>';

                                        mail($para, $assunto, $mensagem, $headers);


                                                $update =
                                                                "UPDATE " .  TabelaServico . " " .
                                                                "SET desabilitado='0'
                                                                WHERE equipamento='$maq'
                                                                ";

                                                mysql_query($update);

                        }
                        else
                        {
                              $desabilitado++;
                                        $update =
                                                        "UPDATE " .  TabelaServico . " " .
                                                        "SET desabilitado='$desabilitado'
                                                        WHERE equipamento='$maq'
                                                        ";

                                        mysql_query($update);
                        }


                        exit;
        }
        mysql_close();

?>
