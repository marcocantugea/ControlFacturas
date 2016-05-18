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

//load user session
if(isset($_SESSION['UserObj'])){
    $SessionUser= unserialize($_SESSION['UserObj']);
}
$SessionUser->GenerateToken();

if(!empty($_GET)){
    if(isset($_GET['token']) && isset($_GET['param'])){
        $token=$_GET['token'];
        if($token==$SessionUser->token){
            $CatTrans= new TipoTransaccion();
            $CatTrans->idctrans=$_GET['param'];
            $_ADOTipoTransaccion = new ADOTipoTransaccion();
            $_ADOTipoTransaccion->debug=$debug;
            $_ADOTipoTransaccion->DeleteTipoTrans($CatTrans);
            
        }
    }
}

unset($_ADOTipoTransaccion);
unset($CatTrans);
unset($token);

if(!$debug){
    echo '<script type="text/javascript">document.location.href="'. $redirectpage .'"</script>';
}