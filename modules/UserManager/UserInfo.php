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

$SessionUser= unserialize($_SESSION['UserObj']);
$SessionUser->GenerateToken();

if(!empty($_GET)){
    if(isset($_GET['param']) && isset($_GET['token']) && isset($_GET['type'])){
        $token=$_GET['token'];
        if($token==$SessionUser->token){
            $iduser=$_GET['param'];
            $type=$_GET['type'];

            if($iduser>0){
                $user= new UserObj();
                $user->iduser=$iduser;
                $_ADOUser = new ADOUsers();
                $_ADOUser->debug=$debug;
                $_ADOUser->getUsuarioByID($user);
            }

            switch ($type){
                case "json": echo json_encode($user);                break;
                case "text": echo $user->iduser." ". $user->user." ". $user->email ." ". $user->active;break;
                case "csv": echo $user->iduser.";". $user->user.";". $user->email .";". $user->active;break;
                case "pipe": echo $user->iduser."|". $user->user."|". $user->email ."|". $user->active;break;
            }
        }
        
    }
}