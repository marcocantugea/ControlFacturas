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


if(!empty($_POST)){
    if(isset($_POST['iduser']) && isset($_POST['user']) && isset($_POST['email']) && isset($_POST['active']) && isset($_POST['token']) ){
        $token=$_POST['token'];
        if($token==$SessionUser->token){
            $user = new UserObj();
            $user->iduser=$_POST['iduser'];
            $user->user=$_POST['user'];
            $user->email=$_POST['email'];
            $user->active=$_POST['active'];
            $token=$_POST['token'];

            //TODO: validate token
            $_ADOUser = new ADOUsers();
            $_ADOUser->UpdateUser($user);
            if($debug){
                echo 'Record Updated';
            }
        }
    }
}

if(!$debug){
    header("Location: $redirectpage");
    die();
}