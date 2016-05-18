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
 
 //load conciliaciones generadas
 $ListConciliacion = new ArrayList();
 $_ADOConciliacion = new ADOConciliacion();
 $_ADOConciliacion->GetConciliaciones($ListConciliacion);
 
 $_ADOCuentasConsil = new ADOCuentasConsil();
 
?>
<?php include '../../../view/headinclude.php'?>
<?php include '../../../view/menu.php'?>
<h1>Conciliaciones</h1>
<div>
    <h2>Opciones</h2>
    <div>
        <button id="btn_doconciliacion" >Realizar Conciliacion</button>
    </div>
</div>
<div>
    <h2>Conciliaciones</h2>
    <table id="table_conciliaciones" class="tableInfo" style="width: 50%;font-size: small">
        <tr>
            <th>Cuenta</th>
            <th>Mes</th>
            <th>A&ntilde;o</th>
            <th>Opciones</th>
        </tr>
        <?php 
            foreach($ListConciliacion->array as $item){
                $cuenta = new CuentaConcilObj();
                $cuenta->idcuenta=$item->idcuenta;
                $_ADOCuentasConsil->GetCuentaByID($cuenta);
                echo '<tr style="text-align:center">';
                echo '<td>'.$cuenta->cuenta.' - '. $cuenta->descripcion .'</td>';
                echo '<td>'.$item->mes.'</td>';
                echo '<td>'.$item->anio.'</td>';
                echo '<td><button id="item_view_'.$item->idcuenta.'_'.$item->mes.'_'.$item->anio.'">Abrir</button>&nbsp;<button id="item_delete_'.$item->idcuenta.'_'.$item->mes.'_'.$item->anio.'">Borrar</button></td>';
                echo '</tr>';
            }
        ?>
    </table>
</div>
<div id="modal_delete">
    <h4 style="color: red">Desea Eliminar Esta Conciliacion?</h4>
</div>
<script src="../../../js/jquery-1.12.1.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">
    var sel_mes=0;
    var sel_anio=0;
    var sel_idcuenta=0;
    
    $(function(){
        
        dialog=$('#modal_delete').dialog({autoOpen: false,modal: true,buttons:{
                Cancelar:function(){dialog.dialog( "close" );},
                Borrar:function(){
                        document.location.href="DeleteConciliacion.php?param="+sel_idcuenta+"&param2="+sel_mes+"&param3="+sel_anio+"&token=<?php echo $SessionUser->token ?>";
                }
        }});
    });
       
    $('#btn_doconciliacion').click(function(){
        document.location.href="SetFileConciliacion.php";
    });
    
    $('#table_conciliaciones button[id^=item_delete]').click(function(){
        var idbutton= $(this).attr("id");
        var val=idbutton.split("_");
        sel_idcuenta=val[2];
        sel_mes=val[3];
        sel_anio=val[4]
        $('#modal_delete').dialog('open');
    });
    
    $('#table_conciliaciones button[id^=item_view]').click(function(){
        var idbutton= $(this).attr("id");
        var val=idbutton.split("_");
        var idcuenta=val[2];
        var mes=val[3];
        var anio=val[4]
        document.location.href="LoadConciliacion.php?param="+idcuenta +"&param2="+mes+"&param3="+anio+"&token=<?php echo $SessionUser->token ?>";
    });
    
</script>
<?php include '../../../view/footerinclude.php'?>
<?php
        unset($_ADOCuentasConsil);
        unset($_ADOConciliacion);
        unset($ListConciliacion);

?>