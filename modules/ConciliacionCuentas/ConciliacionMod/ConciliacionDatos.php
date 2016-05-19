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
 
 //Load conciliacion
if(isset($_SESSION['TmpConciliacion'])){
    $ListConciliacion = unserialize($_SESSION['TmpConciliacion']);
}

//load tipo transaccion
$ListTipoTrans = new ArrayList();
$_ADOTipoTransaccion= new ADOTipoTransaccion();
$_ADOTipoTransaccion->GetAllTipoTrans($ListTipoTrans);

// carga lista de facturas
$ListFacturas= new ArrayList();
$_ADOFactura= new ADOFacturas();
$_ADOFactura->GetFacturasToConciliate($ListFacturas);
?>
<?php include '../../../view/headinclude.php'?>
<?php include '../../../view/menu.php'?>
<h1>Conciliacion de Cuenta</h1>
<div>
    <table id="controlfacturas" class="tableInfo" style="width: 100%;font-size: small">
        <tr>
            <th>&nbsp;</th>
            <th>Dia</th>
            <th>Concepto</th>
            <th>Cargo</th>
            <th>Abono</th>
            <th>Saldo</th>
            <th>Tipo Transaccion</th>
            <th>Opcion</th>
        </tr>
        <?php
            if(count($ListConciliacion->array)>0){
                $TotalDepositos=0;
                $TotalCargos=0;
                foreach($ListConciliacion->array as $item){
                    $TotalDepositos+=$item->abono;
                    $TotalCargos+=$item->cargo;
                    
                    $flagchecked="";
                    $flagstyle="";
                    if($item->flag==1){
                        $flagchecked="checked";
                        $flagstyle='style="background-color:yellow;"';
                    }
                    
                    echo '<tr '.$flagstyle.'>';
                    echo '<td><input type="checkbox" id="item_sel_'.$item->idconciliacion.'"></td>';
                    echo '<td>'.$item->dia.'</td>';
                    echo '<td>'.$item->concepto.'</td>';
                    echo '<td style="text-align: center">'.$item->cargo.'</td>';
                    echo '<td style="text-align: center">'.$item->abono.'</td>';
                    echo '<td style="text-align: center">'.$item->saldo.'</td>';
                    echo '<td>';
                    echo '<select id="item_seltipotrans_'.$item->idconciliacion.'">';
                    echo '<option value="-1" >&nbsp;</option>';
                    foreach($ListTipoTrans->array as $it){
                        if($item->idctrans==$it->idctrans){
                            echo '<option value="'.$it->idctrans.'" selected>'.$it->descripcion.'</option>';
                        }else{
                            echo '<option value="'.$it->idctrans.'" >'.$it->descripcion.'</option>';
                        }
                    }
                    echo '</select><br/><span id="item_messagetrans_'.$item->idconciliacion.'"></span>';
                    echo '</td>';
                    echo '<td style="text-align: center"><a id="item_showoption_'.$item->idconciliacion.'" href="#">Mas</a></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td colspan="8">';
                    echo '<div id="item_options_'.$item->idconciliacion.'">';
                    echo '<button id="item_addrelation_'.$item->idconciliacion.'" >Relacionar Pago Con Facturas</button><button id="item_viewrelation_'.$item->idconciliacion.'">Ver Facturas Relacionadas</button>';
                    echo '<br/><br/>Por Revisar:<input type="checkbox" id="item_flag_'.$item->idconciliacion.'" '.$flagchecked.'>';
                    echo 'Comentarios:<input type="Text" style="width:40%;" id="item_txtcoment_'.$item->idconciliacion.'" value="'.$item->comentario.'" /><button id="button_showoption_'.$item->idconciliacion.'">Grabar</button>';
                    echo '<br/></div>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '<tr>';
                echo '<th colspan="3"></th>';
                echo '<th>'.$TotalCargos.'</th>';
                echo '<th>'.$TotalDepositos.'</th>';
                echo '<th colspan="3"></th>';
                echo '</tr>';
            }
        
        ?>
    </table>
</div>
<div id="modal_facturas_rel">
    <table class="tableInfo" style="width: 100%;font-size: small">
        <tr>
            <th></th>
            <th>Factura</th>
            <th>Fecha</th>
            <th>Monto</th>
        </tr>
        <?php
            foreach($ListFacturas->array as $item){
                echo '<tr style="text-align:center;">';
                echo '<td><input type="checkbox" id="item_selfac_'.$item->idfactura.'"></td>';
                echo '<td>'. $item->numerofactura.'</td>';
                echo '<td>'.$item->fecha.'</td>';
                echo '<td>'.$item->monto.'</td>';
                echo '</tr>';
            }
        ?>
    </table>
</div>
<div id="modal_facturas_view">
    <table class="tableInfo" style="width: 100%;font-size: small" id="tbl_facturas_rel">
        <tr>
            <th></th>
            <th>Factura</th>
            <th>Fecha</th>
            <th>Monto</th>
        </tr>
    </table>
</div>
<script src="../../../js/jquery-1.12.1.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">
    var sel_idconciliacion=0;
    $(function(){
        $("div[id^='item_options']" ).hide();
        dialog=$('#modal_facturas_rel').dialog({
            autoOpen: false,
            height: 400,
            width: 600,
            modal: true,
            buttons:{
                    Agregar:function(){
                    $('#modal_facturas_rel input[id^=item_selfac]').each(function(){
                       if(this.checked){
                           var idbutton= $(this).attr("id");
                           var val=idbutton.split("_");
                           var idfactura=val[2];
                           //alert(idfactura);
                           //alert(sel_idconciliacion);
                           SetRelFactura(idfactura,sel_idconciliacion,'');
                           var row = $(this).closest('tr');
                           $(row).remove();
                           
                       }
                    });
                    
                
            }}
        });
        
        dialog=$('#modal_facturas_view').dialog({
            autoOpen: false,
            height: 400,
            width: 600,
            modal: true,
            buttons:{Cancelar:function(){
                    dialog.dialog( "close" );
                    },
                    Remove:function(){
                        $('#modal_facturas_view input[id^=item_selfacrel]').each(function(){
                            if(this.checked){
                                 var idbutton= $(this).attr("id");
                                 var val=idbutton.split("_");
                                 var idfactura=val[2];
                                //alert(idfactura);
                                RemoveRelFac(idfactura,sel_idconciliacion);
                            }
                        dialog.dialog( "close" );
                        document.location.reload();
                        });
                    }
            }
        });
        
        
    });
    
    // muestra mas opciones debajo del registro.
    $("#controlfacturas a[id^=item_showoption]").click(function(){
       var idbutton= $(this).attr("id");
       var val=idbutton.split("_");
       var iditem=val[2];
       var isvisible=$('#item_options_'+iditem).is(':visible');
       if(isvisible){
           $('#item_options_'+iditem).hide();
           $(this).html("Mas");
       }else{
           $('#item_options_'+iditem).show();
           $(this).html("Cerrar");
       }
       return false;
    });
    
    //actualiza el tipo de transaccion
    $('#controlfacturas select[id^=item_seltipotrans]').change(function(){
        var idbutton= $(this).attr("id");
        var val=idbutton.split("_");
        var iditem=val[2];
        var valuesel=$(this).val();
        UpdateTransTypeInfo(valuesel,iditem)
        
    });
    
    /// controla el boton de comentarios de la tabla
    $('#controlfacturas button[id^=button_showoption]').click(function(){
        var idbutton= $(this).attr("id");
        var val=idbutton.split("_");
        var iditem=val[2];
        var comment=$('#controlfacturas #item_txtcoment_'+iditem).val();
        UpdateComment(comment,iditem);
    });
        
    //controla la accion del checkbox de revisar
    $('#controlfacturas input[id^=item_flag]').click(function(){
        var idbutton= $(this).attr("id");
        var val=idbutton.split("_");
        var iditem=val[2];
        var row = $(this).closest('tr');
        var prev = row.prev();
        
        if(this.checked){
            UpdateFlag("true",iditem);
            $(prev).css("background-color","yellow");
        }else{
            UpdateFlag("false",iditem);
            $(prev).css("background-color","white");
        }
        

    });
    
    //controla la accion del modal de las factura
    $('#controlfacturas button[id^=item_addrelation]').click(function(){
        var idbutton= $(this).attr("id");
        var val=idbutton.split("_");
        sel_idconciliacion=val[2];
        $('#modal_facturas_rel').dialog('open'); 
    });
    
    //Controla la accion del modal de las facturas relacionadas
    $('#controlfacturas button[id^=item_viewrelation]').click(function(){
        var idbutton= $(this).attr("id");
        var val=idbutton.split("_");
        sel_idconciliacion=val[2];
        SelectRelFactura(sel_idconciliacion);
        $('#modal_facturas_view').dialog('open'); 
    });
    
    function UpdateTransTypeInfo(param,param2){
        //alert('getInfo.php?param='+param+'&token=<?php echo $SessionUser->token;?>&type='+type);
        $.ajax({
            url:'updateTransType.php?param='+param+'&token=<?php echo $SessionUser->token;?>&param2='+param2,
            type:'get',
            dataType: "json",
            success:function(result){
                var obj1=result;
                alert(result);
            }
            
        });
    }
    
    function UpdateComment(param,param2){
        //alert('updateComment.php?param='+param+'&token=<?php echo $SessionUser->token;?>&param2='+param2);
        $.ajax({
            url:'updateComment.php?param='+param+'&token=<?php echo $SessionUser->token;?>&param2='+param2,
            type:'get',
            dataType: "json",
            success:function(result){
                var obj1=result;
                alert(obj1);
            }
            
        });
    }
    
    function UpdateFlag(param,param2){
        //alert('updateComment.php?param='+param+'&token=<?php echo $SessionUser->token;?>&param2='+param2);
        $.ajax({
            url:'updateFlag.php?param='+param+'&token=<?php echo $SessionUser->token;?>&param2='+param2,
            type:'get',
            dataType: "json",
            success:function(result){
                var obj1=result;
                alert(obj1);
            }
            
        });
    }
    
    function SetRelFactura(idfactura,idrelcon,comment){
        //alert('SetFacturaToTrans.php?param='+idfactura+'&token=<?php echo $SessionUser->token;?>&param2='+idrelcon+'&param3='+comment);
        $.ajax({
            url:'SetFacturaToTrans.php?param='+idfactura+'&token=<?php echo $SessionUser->token;?>&param2='+idrelcon+'&param3='+comment,
            type:'get',
            dataType: "json",
            success:function(result){
                var obj1=result;
                alert(obj1);
            }
            
        });
    }
    function SelectRelFactura(param){
        //alert('getFacturasConciliadas.php?param='+param+'&token=<?php echo $SessionUser->token;?>');
        $.ajax({
            url:'getFacturasConciliadas.php?param='+param+'&token=<?php echo $SessionUser->token;?>',
            type:'get',
            dataType: "json",
            success:function(result){
                var obj1=result;
                //alert(obj1);
                $('#tbl_facturas_rel tr').not(":first").remove();
                for(i=0;i<obj1.array.length;i++){
                    var newrow="";
                    newrow+='<tr style="text-align:center;">';
                    newrow+='<td><input type="checkbox" id="item_selfacrel_'+obj1.array[i].idfactura+'"></td>';
                    newrow+="<td>"+obj1.array[i].numerofactura+"</td>";
                    newrow+='<td>'+obj1.array[i].fecha+'</td>';
                    newrow+='<td>'+obj1.array[i].monto+'</td>';
                    newrow+="</tr>";
                    $('#tbl_facturas_rel').append(newrow);
                }
            }
            
        });
    }
    
    function RemoveRelFac(param,param2){
        //alert('RemoveFacturaToTrans.php?param='+param+'&token=<?php echo $SessionUser->token;?>&param2='+param2);
        $.ajax({
            url:'RemoveFacturaToTrans.php?param='+param+'&token=<?php echo $SessionUser->token;?>&param2='+param2,
            type:'get',
            dataType: "json",
            success:function(result){
                var obj1=result;
                //alert(obj1);
            }
            
        });
    }
    
    
    
</script>
<?php include '../../../view/footerinclude.php'?>
<?php

    unset($ListTipoTrans);
    unset($_ADOTipoTransaccion);
    unset($ListFacturas);
    unset($_ADOFactura);
    
?>
