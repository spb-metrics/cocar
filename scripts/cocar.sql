-- MySQL dump 10.9
--
-- Host: localhost    Database: rede_acesso
-- ------------------------------------------------------
-- Server version	4.1.11

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `rede_acesso`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `rede_acesso` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `rede_acesso`;

--
-- Table structure for table `$cod_int`
--

DROP TABLE IF EXISTS `$cod_int`;
CREATE TABLE `$cod_int` (
  `data_hora` datetime NOT NULL default '0000-00-00 00:00:00',
  `volume_in` int(11) default NULL,
  `volume_out` int(11) default NULL,
  `delay` smallint(6) default NULL,
  `st_delay` tinyint(4) default NULL,
  PRIMARY KEY  (`data_hora`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `bckbone`
--

DROP TABLE IF EXISTS `bckbone`;
CREATE TABLE `bckbone` (
  `cod_int` varchar(25) NOT NULL default '00_invalido_00',
  `nome` varchar(25) default '',
  `orgao` varchar(46) default '',
  `gerencia` varchar(25) default '',
  `uf` char(2) NOT NULL default 'XX',
  `host` varchar(20) NOT NULL default '',
  `community` varchar(8) NOT NULL default 'startrek',
  `serial` varchar(28) NOT NULL default '',
  `tecnologia` char(3) NOT NULL default 'FR',
  `tipo` char(1) NOT NULL default 'C',
  `num_int` int(11) NOT NULL default '0',
  `ip_orig` varchar(15) default '',
  `circuito` varchar(10) default '0',
  `porta` int(6) NOT NULL default '256',
  `cir_in` int(5) NOT NULL default '128',
  `cir_out` int(5) NOT NULL default '128',
  `history` char(2) NOT NULL default 'N',
  `conc_bb` varchar(20) NOT NULL default '',
  `grupo` varchar(20) NOT NULL default '',
  `uf_bb` varchar(5) NOT NULL default '',
  `rly` int(11) NOT NULL default '255',
  `coletor` int(11) NOT NULL default '0',
  `ifAdminStatus` varchar(5) NOT NULL default 'UP',
  `ifOperStatus` varchar(5) NOT NULL default 'UP',
  `GeraAlarme` char(1) NOT NULL default 'S',
  `PontaDestino` varchar(30) NOT NULL default '',
  `ifStatus` varchar(5) NOT NULL default 'UP',
  PRIMARY KEY  (`cod_int`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `configuracoes`
--

DROP TABLE IF EXISTS `configuracoes`;
CREATE TABLE `configuracoes` (
  `codigo` int(11) NOT NULL auto_increment,
  `IpServerMonitora` varchar(25) NOT NULL default '10.82.4.205',
  `Path_RRD` varchar(100) NOT NULL default '/usr/local/rrd-tool-1.0.49/bin/',
  `dir_rrd_trafego` varchar(100) NOT NULL default '/var/www/rrd/',
  `dir_rrd_novos` varchar(100) NOT NULL default '/var/www/rrd/novos/',
  `dir_php` varchar(100) NOT NULL default '/var/www/html/php/',
  `dir_logs` varchar(100) NOT NULL default '/var/www/html/php/logs/',
  `dir_cfg_novas` varchar(100) NOT NULL default '/etc/mrtg/novas_cfg/',
  `dir_fig` varchar(100) NOT NULL default '/var/www/html/graficos/',
  `ServerWeb` varchar(30) NOT NULL default 'webserver',
  `dir_rrd_rly` varchar(100) NOT NULL default '/var/www/rrd/',
  `tabela_rly` varchar(30) NOT NULL default 'reliability',
  `dir_rrd_delay` varchar(100) NOT NULL default '/var/www/rrd/delay/',
  PRIMARY KEY  (`codigo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `cpu`
--

DROP TABLE IF EXISTS `cpu`;
CREATE TABLE `cpu` (
  `CodigoHost` varchar(30) NOT NULL default '',
  `Descricao` varchar(21) NOT NULL default '',
  `IpHost` varchar(15) NOT NULL default '',
  `NomeHost` varchar(100) NOT NULL default '',
  `Origem` varchar(15) NOT NULL default '',
  `ModeloHost` varchar(30) NOT NULL default 'Cisco',
  `TipoEquipamento` varchar(15) NOT NULL default 'Router',
  `community` varchar(100) NOT NULL default 'startrek',
  PRIMARY KEY  (`CodigoHost`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `entidades`
--

DROP TABLE IF EXISTS `entidades`;
CREATE TABLE `entidades` (
  `identificador` varchar(8) NOT NULL default '',
  `nome_entidade` varchar(50) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `rede_acesso`
--

DROP TABLE IF EXISTS `rede_acesso`;
CREATE TABLE `rede_acesso` (
  `cod_int` varchar(20) NOT NULL default '00_invalido_00',
  `auxiliar` varchar(30) default '',
  `nome` varchar(30) default '',
  `orgao` varchar(70) default '',
  `gerencia` varchar(30) default '',
  `uf` varchar(15) NOT NULL default 'XX',
  `host` varchar(15) NOT NULL default '',
  `community` varchar(20) NOT NULL default 'startrek',
  `serial` varchar(18) NOT NULL default '',
  `tecnologia` char(3) NOT NULL default 'FR',
  `tipo` char(2) NOT NULL default 'C',
  `num_int` int(11) NOT NULL default '0',
  `ip_orig` varchar(15) default '',
  `circuito` int(11) default '0',
  `porta` int(11) NOT NULL default '256',
  `cir_in` int(11) NOT NULL default '128',
  `cir_out` int(11) NOT NULL default '128',
  `history` char(2) NOT NULL default 'N',
  `serial_dest` varchar(100) default '',
  `num_int_dest` int(20) default '0',
  `community_dest` varchar(20) default 'localidade',
  `host_dest` varchar(20) default '',
  `rly` int(11) NOT NULL default '-1',
  `qtd_estacoes` int(11) NOT NULL default '0',
  `ifOperStatus` varchar(4) NOT NULL default 'UP',
  `ifAdminStatus` varchar(4) NOT NULL default 'UP',
  `GeraAlarme` char(1) NOT NULL default 'S',
  `ifStatus` varchar(5) NOT NULL default 'UP',
  PRIMARY KEY  (`cod_int`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

GRANT ALL ON rede_acesso.* TO 'cocaradm'@'localhost' IDENTIFIED BY 'cocar';
