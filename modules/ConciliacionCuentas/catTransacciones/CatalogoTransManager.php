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
    //load user session
if(isset($_SESSION['UserObj'])){
    $SessionUser= unserialize($_SESSION['UserObj']);
}
$SessionUser->GenerateToken();
//Carga los tipos de transaccion
$ListTipoTrans= new ArrayList();
$_ADOTipoTransaccion= new ADOTipoTransaccion();
$_ADOTipoTransaccion->GetAllTipoTrans($ListTipoTrans);
    
?>
<?php include '../../../view/headinclude.php';?>
<?php include '../../../view/menu.php';?>
<h1>Catalogo de Transaciones </h1>
<div>
    <h3>Opciones</h3>
    <table>
        <tr>
            <td>
                <button id="showAddnew">Agregar nuevo Registro</button>
            </td>
        </tr>
    </table>
</div>
<div id="AddnewReg" class="addnew">
    <div id="CloseAddnew" style="text-align: right;  width: 100%;margin-bottom: 15px; ">
        <button id="HideAddnew">X</button>
    </div>
    <h3>Agregar Registro</h3>
    <form name="addnewreg" method="post" action="addNewTransaccion.php">
        <table  style="width: 350px">
        <tr>
            <td>
                Tipo Transaccion:
            </td>
             <td>
                 <input type="text" name="descripcion" id="descripcion" value=""/>
                  <input type="hidden" name="active" value="1">
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
                Tipo Transaccion:
            </td>
             <td>
                 <input type="text" name="update_descripcion" id="update_descripcion" value=""/>
                  <input type="hidden" name="update_active" value="" id="update_active">
                  <input type="hidden" name="update_idctrans" value="" id="update_idctrans">
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
<P></P>
<div id="tableinfo" style="margin-left: 15%;">
    <table class="tableInfo" style="width: 55%" id="TableUsers">
        <tr>
            <th>ID</th>
            <th>Descripcion</th>
            <th>Estado</th>
            <th colspan="3">Opciones</th>
        </tr>
        <?php
             if(count($ListTipoTrans->array)>0){
                 foreach($ListTipoTrans->array as $item){
                     $estado=0;
                    if($item->active==1){
                        $estado="Activo";
                    }else{
                         $estado="Desactivado";
                    }
                    
                    echo '<tr>';
                    echo '<td><input type="hidden" id="idctrans" name="idctrans" value="'. $item->idctrans .'" >'. $item->idctrans .'</td>';
                    echo '<td><input type="hidden" id="descripcion" name="descripcion" value="'. $item->descripcion .'" >'. $item->descripcion .'</td>';
                    echo '<td style="text-align: center"><input type="hidden" id="active" name="active" value="'. $item->active .'" >'. $estado .'</td>';
                    echo '<td><button id="btn_edit_'. $item->idctrans .'" >Editar</button></td>';
                    echo '<td><button id="btn_delete_'. $item->idctrans .'" >Eliminar</button></td>';
                    if($item->active==1){
                        echo '<td><button id="btn_deactivate_'. $item->idctrans .'" >Desactivar</button></td>';
                    }
                    if($item->active==0){
                         echo '<td><button id="btn_activate_'. $item->idctrans .'" >Activar</button></td>';
                    }
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
        var idctrans= strsplited[2];
        var action=strsplited[1];
        if(action=="edit"){
            setInfo(idctrans,"json");
            $('#UpdateInfo').show(500);
        }
        
        if(action=="delete"){
            document.location.href="deleteTipoTrans.php?param="+idctrans+"&token=<?php echo $SessionUser->token;?>"
            
        }
        if(action=="activate"){
            document.location.href="ChangeActivation.php?param="+idctrans+"&token=<?php echo $SessionUser->token;?>&type=activate";
            
        }
        
        if(action=="deactivate"){
            document.location.href="ChangeActivation.php?param="+idctrans+"&token=<?php echo $SessionUser->token;?>&type=deactivate";
            
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
                $('#update_active').val(obj1.active);
                $('#update_idctrans').val(obj1.idctrans);
            }
            
        });
    }
</script>
<?php include '../../../view/footerinclude.php';?>
<?php
unset($_ADOTipoTransaccion);
unset($ListTipoTrans);
?>