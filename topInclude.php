<?php

/* 
 * Copyright (C) 2016 MarcoCantu
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

date_default_timezone_set("America/Mexico_City");

//Configuracion del sistema
$pathconfig="/ControlFacturas";
include ($_SERVER['DOCUMENT_ROOT'].$pathconfig."/Config.php");
$config= new Config();

session_start();
$_SESSION['Show_Loggin']=true;
if(isset($_SESSION['UserObj'])){
    $_SESSION['Show_Loggin']=false;
}

$direccion="/".$config->pathServer;
$logoutpage="$config->domain$direccion/logout.php";


// Connector de base de datos
include ($_SERVER['DOCUMENT_ROOT'].$direccion."/include/com/database/MysqlConnector.php");


// Classes communes
include ($_SERVER['DOCUMENT_ROOT'].$direccion."/include/com/common/ArrayList.php");
include ($_SERVER['DOCUMENT_ROOT'].$direccion."/include/com/common/DateFunctions.php");
include ($_SERVER['DOCUMENT_ROOT'].$direccion."/include/com/common/SqlQueryBuilder.php");

// Access Data Objects
include ($_SERVER['DOCUMENT_ROOT'].$direccion."/include/com/dao/ADOUsers.php");
include ($_SERVER['DOCUMENT_ROOT'].$direccion."/include/com/dao/ADOFacturas.php");
include ($_SERVER['DOCUMENT_ROOT'].$direccion."/include/com/dao/ADOPagosFactura.php");
include ($_SERVER['DOCUMENT_ROOT'].$direccion."/include/com/dao/ADOEstadoFacturas.php");

//Entidades del sistema
include ($_SERVER['DOCUMENT_ROOT'].$direccion."/include/com/entities/UserObj.php");
include ($_SERVER['DOCUMENT_ROOT'].$direccion."/include/com/entities/FacturaObj.php");
include ($_SERVER['DOCUMENT_ROOT'].$direccion."/include/com/entities/PagoFacturaObj.php");
include ($_SERVER['DOCUMENT_ROOT'].$direccion."/include/com/entities/EstadoFacturaObj.php");

// External
include ($_SERVER['DOCUMENT_ROOT'].$direccion."/include/external/simplecapcha/simple-php-captcha.php");