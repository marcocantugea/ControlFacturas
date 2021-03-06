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

$debug=true;
$redirectpage="ConciliacionDatos.php";

//load user session
if(isset($_SESSION['UserObj'])){
    $SessionUser= unserialize($_SESSION['UserObj']);
}
$SessionUser->GenerateToken();

if(!empty($_GET)){
    if(isset($_GET['token']) && isset($_GET['param']) && isset($_GET['param2'])){
        $token=$_GET['token'];
        if($token==$SessionUser->token){
            $conciliacion = new ConciliacionObj();
            $conciliacion->idconciliacion=$_GET['param2'];
            
            $_ADOConciliacion = new ADOConciliacion();
            $_ADOConciliacion->debug=$debug;
            if($_GET['param']=="true"){
                $_ADOConciliacion->MarkFlagTrue($conciliacion);
                $conciliacion->flag=1;
            }else{
                 $_ADOConciliacion->MarkFlagFalse($conciliacion);
                 $conciliacion->flag=0;
            }
            
            if(isset($_SESSION['FileTmp']) && isset($_SESSION['TmpConciliacion'])){
                $ListConciliacion = unserialize($_SESSION['TmpConciliacion']);
                foreach($ListConciliacion->array as $item){
                    if($item->idconciliacion===$conciliacion->idconciliacion){
                        $item->flag=$conciliacion->flag;
                    }
                }
                $_SESSION['TmpConciliacion']=  serialize($ListConciliacion);
            }
        }
    }
}

unset($_ADOConciliacion);
unset($conciliacion);
unset($token);

if(!$debug){
    echo '<script type="text/javascript">document.location.href="'. $redirectpage .'"</script>';
}