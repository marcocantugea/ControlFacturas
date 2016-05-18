<!DOCTYPE html>
<!--
Copyright (C) 2016 MarcoCantu

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
-->
<?php

include 'topinclude.php';
 //load session user
 $SessionUser= new UserObj();
 $SessionUser = unserialize($_SESSION['UserObj']);
 $SessionUser->GenerateToken();

 //Load accounts
 $ListCuentas = new ArrayList();
 $_ADOCuentasConsil = new ADOCuentasConsil();
 $_ADOCuentasConsil->GetAllCuentas($ListCuentas);
 
 //obtiene años deacuerdo al actual
$ano= date("Y");
$anos=array();
for($i=2;$i>=0;$i--){
    $r=$ano-$i;
    $anos[]=$r;
    
}
for($e=1;$e<=3;$e++){
    $r=$ano+$e;
    $anos[]=$r;
    
}
 

?>
<?php include '../../../view/headinclude.php'?>
<?php include '../../../view/menu.php'?>
<h1>Conciliacion de Cuenta</h1>
<div>
    <form action="SetCuenta.php" name="selconcil" id="frm_selconcil" method="post">
        <div>
            Selecione la cuenta:
            <?php
                echo '<select name="cuenta">';
                foreach($ListCuentas->array as $item){
                    echo '<option value="'.$item->idcuenta.'">'.$item->cuenta.' - '.$item->descripcion.'</option>';
                }
                echo '</select>';
            ?>
            
        </div>
        <br/>
        <div>
            Selecione Mes:<select name="mes" id="sel_mes">
            <?php
                $messel=date('m');
                
                for($i=1;$i<=12;$i++){
                    if($messel==$i){
                        echo "<option selected>$i</option>";
                    }else{
                        echo "<option>$i</option>";
                    }
                    
                }
            
            ?>
            </select> A&ntilde;o:<select name="anio" id="sel_anio">
                <?php
                foreach($anos as $i){
                    if($i==$ano){
                        echo "<option selected>$i</option>";
                    }else{
                        echo "<option>$i</option>";
                    }
                }
                
                ?>
            </select>
        </div>
        <br/>
        <div>
            <button id="btn_send">Guardar Info</button>
        </div>
    </form>
</div>
<script src="../../js/jquery-1.12.1.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">
    
</script>
<?php include '../../../view/footerinclude.php'?>
<?php
    unset($_ADOCuentasConsil);
    unset($ListCuentas);

?>