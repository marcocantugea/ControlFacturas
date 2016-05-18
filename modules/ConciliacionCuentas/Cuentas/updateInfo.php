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

include 'topinclude.php';
$debug=false;
$redirectpage="CuentasManager.php";

$SessionUser= unserialize($_SESSION['UserObj']);
$SessionUser->GenerateToken();

if(!empty($_POST)){
    if(isset($_POST['token']) && isset($_POST['descripcion']) && isset($_POST['cuenta']) && isset($_POST['idcuenta'])){
        $token=$_POST['token'];
        if($token==$SessionUser->token){
            $cuenta= new CuentaConcilObj();
            $cuenta->idcuenta=$_POST['idcuenta'];
            $cuenta->cuenta=$_POST['cuenta'];
            $cuenta->descripcion=$_POST['descripcion'];
            
            $_ADOCuentasConsil= new ADOCuentasConsil();
            $_ADOCuentasConsil->debug=$debug;
            $_ADOCuentasConsil->UpdateInfo($cuenta);
        }
    }
}

unset($_ADOCuentasConsil);
unset($cuenta);
unset($token);

if(!$debug){
    echo '<script type="text/javascript">document.location.href="'. $redirectpage .'"</script>';
}