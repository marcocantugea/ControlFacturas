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
$redirectpage="CatalogoTransManager.php";

$SessionUser= unserialize($_SESSION['UserObj']);
$SessionUser->GenerateToken();

if(!empty($_GET)){
    if(isset($_GET['param']) && isset($_GET['token']) && isset($_GET['type']) ){
        $token=$_GET['token'];
        if($token==$SessionUser->token){
            $type= $_GET['type'];
            $TipoTrans = new TipoTransaccion();
            $TipoTrans->idctrans=$_GET['param'];
            $_ADOTipoTransaccion= new ADOTipoTransaccion();           

            if($type=="activate"){
                $_ADOTipoTransaccion->Activate($TipoTrans);
            }

            if($type=="deactivate"){
                $_ADOTipoTransaccion->Deactivate($TipoTrans);
            }
        }
        
    }
}

unset($token);
unset($type);
unset($TipoTrans);
unset($_ADOUser);


if(!$debug){
    echo '<script type="text/javascript">document.location.href="'. $redirectpage .'"</script>';
}