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
 
 //Load Cuentas
 $ListCuentas = new ArrayList();
 $_ADOCuentasConsil = new ADOCuentasConsil();
 $_ADOCuentasConsil->GetAllCuentas($ListCuentas);
 
?>
<?php include '../../../view/headinclude.php'?>
<?php include '../../../view/menu.php'?>
<h1>Administracion de Cuentas</h1>
<div>
    <h3>Opciones</h3>
    <table>
        <tr>
            <td>
                <button id="showAddnew">Agregar Nueva Cuenta</button>
            </td>
        </tr>
    </table>
</div>
<div id="AddnewReg" class="addnew">
    <div id="CloseAddnew" style="text-align: right;  width: 100%;margin-bottom: 15px; ">
        <button id="HideAddnew">X</button>
    </div>
    <h3>Agregar Registro</h3>
    <form name="addnewreg" method="post" action="addCuenta.php">
        <table  style="width: 350px">
        <tr>
            <td>
                Cuenta:
            </td>
             <td>
                 <input type="text" name="cuenta" id="cuenta" value=""/>
                  <input type="hidden" name="token" value="<?php echo $SessionUser->token;?>" />
            </td>
        </tr>
        <tr>
            <td>
                Descripcion:
            </td>
             <td>
                 <input type="text" name="descripcion" id="descripcion" value=""/>
                  <input type="hidden" name="token" value="<?php echo $SessionUser->token;?>" />
            </td>
        </tr>
        <tr>
            <td>
               
            </td>
             <td>
                 <button name="btnAddNew" id="btnAddNew" >Guardar Registro</button>
                 
            </td>
        </tr>
    </table>
       
    </form>
    
</div>
<div id="UpdateInfo" class="addnew" style="background-color: lightgoldenrodyellow" >
    <div id="CloseUpdateForm" style="text-align: right;  width: 100%;margin-bottom: 15px; ">
        <button id="HideUpdatefrm">X</button>
    </div>
    <h3>Actualizar Registro</h3>
    <form name="updatereg" method="post" action="updateInfo.php">
        <table  style="width: 350px">
        <tr>
            <td>
                Cuenta:
            </td>
             <td>
                 <input type="text" name="cuenta" id="update_cuenta" value=""/>
                  <input type="hidden" name="token" value="<?php echo $SessionUser->token;?>" />
                  <input type="hidden" name="idcuenta" id="update_idcuenta" value="" />
            </td>
        </tr>
        <tr>
            <td>
                Descripcion:
            </td>
             <td>
                 <input type="text" name="descripcion" id="update_descripcion" value=""/>
            </td>
        </tr>
        <tr>
            <td>
               
            </td>
             <td>
                 <button name="btnAddNew" id="btnAddNew" >Guardar Registro</button>
                 
            </td>
        </tr>
    </table>
    </form>
</div>
<p></p>
<div id="tableinfo" style="margin-left: 15%;">
    <table class="tableInfo" style="width: 75%" id="TableUsers">
        <tr>
            <th>ID</th>
            <th>Cuenta</th>
            <th>Descripcion</th>
            <th colspan="3">Opciones</th>
        </tr>
        <?php
        if(count($ListCuentas->array)>0){
            foreach($ListCuentas->array as $item){
                echo '<tr>';
                echo '<td><input type="hidden" id="idcuenta" name="idcuenta" value="'. $item->idcuenta .'" >'. $item->idcuenta .'</td>';
                echo '<td><input type="hidden" id="cuenta" name="cuenta" value="'. $item->cuenta .'" >'. $item->cuenta .'</td>';
                echo '<td><input type="hidden" id="descripcion" name="descripcion" value="'. $item->descripcion .'" >'. $item->descripcion .'</td>';
                echo '<td><button id="btn_edit_'. $item->idcuenta .'" >Editar</button></td>';
                //echo '<td><button id="btn_delete_'. $item->idcuenta .'" >Eliminar</button></td>';
                echo '<td><button id="btn_concil_'. $item->idcuenta .'" >Conciliar</button></td>';
                echo '<td></td>';
                echo '</tr>';
            }
        }
        ?>
    </table>
</div>
<script src="../../../js/jquery-1.12.1.min.js"></script>
<script type="text/javascript">
    $().ready(function(){
        $('#AddnewReg').hide();
        $('#UpdateInfo').hide();
    });
    
    $('#showAddnew').click(function(){
        $('#AddnewReg').show(500);
    });
    $('#HideAddnew').click(function(){
        $('#AddnewReg').hide(500);
    });
    $('#HideUpdatefrm').click(function(){
       $('#UpdateInfo').hide(500);
    });
    
    $('#tableinfo td button').click(function(){
        var id=$(this).attr('id');
        var strsplited= id.split("_");
        var idcuenta= strsplited[2];
        var action=strsplited[1];
        if(action=="edit"){
            setInfo(idcuenta,"json");
            $('#UpdateInfo').show(500);
        }
    });
    
    function setInfo(param,type){
        //alert('getInfo.php?param='+param+'&token=<?php echo $SessionUser->token;?>&type='+type);
        $.ajax({
            url:'getInfo.php?param='+param+'&token=<?php echo $SessionUser->token;?>&type='+type+'',
            type:'get',
            dataType: "json",
            success:function(result){
                var obj1=result;
                $('#update_descripcion').val(obj1.descripcion)
                $('#update_cuenta').val(obj1.cuenta);
                $('#update_idcuenta').val(obj1.idcuenta);
            }
            
        });
    }
</script>
<?php include '../../../view/footerinclude.php'?>
<?php
        unset($ListCuentas);
        unset($_ADOCuentasConsil);

?>
