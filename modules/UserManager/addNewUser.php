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

include("topinclude.php");
$debug=false;
$redirectpage="UserManager.php";
//load user session

$SessionUser= unserialize($_SESSION['UserObj']);
$SessionUser->GenerateToken();



if(!empty($_POST)){
    if(isset($_POST['user']) && isset($_POST['email']) && isset($_POST['pass']) && isset($_POST['active']) && isset($_POST['token'])){
        $token=$_POST['token'];
        if($token==$SessionUser->token){
            $NewUser = new UserObj();
            $NewUser->user=$_POST['user'];
            $NewUser->email=$_POST['email'];
            $NewUser->pass=$_POST['pass'];
            $NewUser->active=$_POST['active'];
            $_ADOUser =  new ADOUsers();
            $_ADOUser->debug=$debug;


            $_ADOUser->AddNewUser($NewUser);
            if($debug){
                echo '<br/> Record Added';
            }
        }
    }
}

unset($token);
unset($NewUser);
unset($_ADOUser);


if(!$debug){
    header("Location: $redirectpage");
    die();
}

        

