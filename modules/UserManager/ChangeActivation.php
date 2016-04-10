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
$redirectpage="UserManager.php";

$SessionUser= unserialize($_SESSION['UserObj']);
$SessionUser->GenerateToken();

if(!empty($_GET)){
    if(isset($_GET['param']) && isset($_GET['token']) && isset($_GET['type']) ){
        $token=$_GET['token'];
        if($token==$SessionUser->token){
            $type= $_GET['type'];
            $user= new UserObj();
            $user->iduser=$_GET['param'];

            //TODO: Validate Token
            $_ADOUser = new ADOUsers();
            $_ADOUser->debug=$debug;

            if($type=="activate"){
                $_ADOUser->ActivateUser($user);
            }

            if($type=="deactivate"){
                $_ADOUser->DeactivateUser($user);
            }
        }
        
    }
}

if(!$debug){
    header("Location: $redirectpage");
    die();
}