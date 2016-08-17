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

include 'topInclude.php';

$debug=false;
$redirectpage="index.php";
$validatecaptcha=false;


if(!empty($_POST)){
    if(isset($_POST['user']) && isset($_POST['pass'])){
        if(isset($_POST['captcha'])){
            $catpcha=$_POST['captcha'];
        }else{
            $catpcha="123";
        }
         
         $user = new UserObj();
         $user->user=$_POST['user'];
         $user->pass=$_POST['pass'];
         $_ADOUser = new ADOUsers();
         $_ADOUser->debug=$debug;
         if(isset($_SESSION['failedattemps'])){
             $failsattemp=$_SESSION['failedattemps'];
             if($failsattemp>3){
                 $validatecaptcha=true;
             }
         }
         
         if($validatecaptcha){
             if($catpcha==$_SESSION['captcha']['code']){
                 $_ADOUser->Loggin($user); 
                
                if($user->iduser>0){
                    $_SESSION['Show_Loggin']=false;
                    $_SESSION['UserObj']= serialize($user);
                    $_SESSION['failedattemps']=0;
                    //echo '0|true';
                }else{
                    $failsattemp=$failsattemp+1;
                    $_SESSION['failedattemps']=$failsattemp;
                    //echo $failsattemp.'|false';      
                }
             }else{
                 //echo $failsattemp.'|invalid captcha';
             }
         }else{
            $_ADOUser->Loggin($user);
            if($user->iduser>0){
                $_SESSION['Show_Loggin']=false;
                $_SESSION['UserObj']= serialize($user);
                //echo '0|true';
            }else{
                $failsattemp=$failsattemp+1;
                $_SESSION['failedattemps']=$failsattemp;
                //echo $failsattemp.'|false';    
            }
             
         }
         
    }
}

unset($_ADOUser);
echo '<script type="text/javascript" > document.location.href="'.$redirectpage.'"</script>';

?>