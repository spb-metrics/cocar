#!/bin/bash
#
#
#    Este arquivo � parte do programa COCAR
#
#
#
#    COCAR � um software livre; voc� pode redistribui-lo e/ou
#
#    modifica-lo dentro dos termos da Licen�a P�blica Geral GNU como
#
#    publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da
#
#    Licen�a.nt
#
#
#
#    Este programa � distribuido na esperan�a que possa ser  util,66"
#
#    mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUA��O a qualquer
#
#    MERCADO ou APLICA��O EM PARTICULAR. Veja a
#
#    Licen�a P�blica Geral GNU para maiores detalhes (GPL2.txt).
#
#
#
#    Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
#
#    junto com este programa, se n�o, escreva para a Funda��o do Software
#
#    Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
#

#instalador do sistema COCAR
#autor: Marcio Moriaki Ihsikawa
#27/08/2007

#avaliação do sisma
Kernel=`uname -r`
End_ip=`ifconfig|grep inet|head -1|awk '{ print $3; }'`
Httpd_conf=`find /etc -name httpd.conf|head -1`
Dir_cron=`find /var/ -type d -name cron`
Document_root=`grep ^DocumentRoot $Httpd_conf|awk '{ print $2; }'`
Httpd_user=`grep ^User $Httpd_conf|awk '{ print $2; }'`

echo Kernel=$Kernel
echo ip=$End_ip
echo httpd.conf=$Httpd_conf
echo Dircron=$Dir_cron
echo Document root=$Document_root
echo httpd user=$Httpd_user

ok=0
msg="\n"
#testando se existe snmp
#checagem de prerequisitos
#existe apache
if [ $Httpd_conf != '' ]; then
	echo apache ok
else
	ok=1
	$msg=$msg"Falta instalar apache\n"
fi

#existe snmpwalk
if which snmpwalk 2>&1 > /dev/null ; then 
	echo snmp ok
else
	ok=1
	$msg=$msg"Falta instalar cliente snmp\n"
fi

#existe mysql
if which mysql 2>&1 > /dev/null ; then 
	echo mysql ok;
else
	ok=1
	$msg=$msg"Falta instalar banco de dados mysql\n"
fi

#existe rrdtool
if which rrdtool 2>&1 > /dev/null ; then 
	echo rrdtool ok;
else
	ok=1
	$msg=$msg"Falta instalar rrdtool\n"
fi

#testando cron
if [ `ps -ef|grep cron|grep -v grep|wc -l` -gt 1 ] ; then 
	echo cron ok;
else
	ok=1
	$msg=$msg"cron nao instalado ou nao iniciado\n"
fi

#testando se existe PHP
if which php 2>&1 > /dev/null ; then 
	echo php ok;
	#testando modulos do PHP
	PHP_INF=`php -i`
	#mysql
	if [ `echo $PHP_INF|grep -i mysql[^i]|grep enabled|wc -l` -ge 1 ]; then
		echo php_mysql ok.
	else
		ok=1
		$msg=$msg"Falta instalar modulo php_mysql\n"
	fi
	#rrd
	if [ `echo $PHP_INF|grep -i rrdtool|grep enabled|wc -l` -ge 1 ]; then
		echo php_rrdtool ok.
	else
		ok=1
		$msg=$msg"Falta instalar modulo PHP_rrdtool\n"
	fi
	#bcmath
	if [ `echo $PHP_INF|grep -i snmp|grep enabled|wc -l` -ge 1 ]; then
		echo php_snmp ok.
	else
		ok=1
                $msg=$msg"Falta instalar modulo PHP_snmp\n"
	fi
	#bcmath
	if [ `echo $PHP_INF|grep -i gd|grep enabled|wc -l` -ge 1 ]; then
		echo php_gd ok.
	else
		ok=1
                $msg=$msg"Falta instalar modulo PHP_gd\n"
	fi
	#bcmath
	if [ `echo $PHP_INF|grep -i BCMath|grep enabled|wc -l` -ge 1 ]; then
		echo php_bcmath ok.
	else
		ok=1
                $msg=$msg"Falta instalar modulo PHP_bcmath\n"
	fi
else
	ok=1
	$msg=$msg"Falta instalar PHP\n"
fi

if [ $ok -eq 0 ]; then
#perguntas sobre configuração
#qual diretório de pubicaco
cont=0
while [ $cont -eq 0 ]; do
	echo "Digite o diretório de publicação apache:"
	echo -n "[default:$Document_root]: "
	read $Dir_docroot

	if [ $Dir_docroot <> '' ]; then
		$Document_root = $Dir_docroot
		if [ ! -d $Document_root ]; then
			echo "Diretorio $Document_root não existe, por favor informe novamente";
			read
		else 
			$cont=1
		fi
	else
		$cont=0	
	fi
done


#criação da estra de diretórios e cópia de arquivos
	cp -r cocar $Document_root

#criação doanco de dados
	echo "A seguir serásolicitada a senha do usuario root do mysql"
	mysql -p < scripts/cocar.sql

#instalação dos agendamentos do cocar
	cat scripts/agenda >> $Dircron/root

	echo "A instalcao do COCAR foi completada com sucesso!"

else
	echo "A instalacao do COCAR falhou"
	echo $msg
fi

