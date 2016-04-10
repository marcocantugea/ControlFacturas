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
    $ListFacturas = new ArrayList();
    $ListFacturasNew = new ArrayList();
    $ListAllAFacturas = new ArrayList();
    $_ADOFacturas = new ADOFacturas();
    $selecteduserconf1='';
    $selecteduserconf2='';
    $selecteduserconf3='';
    
    $listofcolumns= array();
    $listofcolumns[]="idfactura";
    $listofcolumns[]="date_format(fecha,'%d - %b - %Y') as fecha";
    $listofcolumns[]="monto";
    $listofcolumns[]="archivoruta";
    $listofcolumns[]="date_format(vencimiento,'%d - %b - %Y') as vencimiento";
    $listofcolumns[]="idestado";
    $listofcolumns[]="numerofactura";
    $listofcolumns[]="numeroorden";
    $listofcolumns[]="montoactual";
    
    if(isset($_COOKIE['confuser'])){
        $viewuser=$_COOKIE['confuser'];
    }else{
        $viewuser="default";
    }
    
    if($viewuser=="pendingtoexpire"){
        // build query for the invoices to expire during the 20 days
        $sqlquery = new SqlQueryBuilder("select");
        $sqlquery->setTable("t_facturas");
        foreach($listofcolumns as $item){
            $sqlquery->addColumn($item);
        }
        $initialdate =date('Y-m-d 00:00:00');
        $enddate = date('Y-m-d');
        $enddate=date('Y-m-d 00:00:00',  strtotime($enddate.'+ 20 days'));
        $sqlquery->setWhere("(vencimiento BETWEEN '$initialdate' AND '$enddate') and idestado=0");

        $_ADOFacturas->GetFacturasByQuery($ListFacturas, $sqlquery);
        $selecteduserconf1='selected="true"';
    }
    
    if($viewuser=="newinvoices"){
        // bouild query for the new top 5 inovices
        $sqlquery2 = new SqlQueryBuilder("select");
        $sqlquery2->setTable("t_facturas");
        foreach($listofcolumns as $item){
            $sqlquery2->addColumn($item);
        }
        $sqlquery2->setOrderBy("idfactura desc");
        $sqlquery2->setLimit("5");

        $_ADOFacturas->GetFacturasByQuery($ListFacturasNew, $sqlquery2);
        $selecteduserconf2='selected="true"';
    }
    
    if($viewuser=="allinvoices"){
        $_ADOFacturas->getFacturas($ListAllAFacturas);
        $selecteduserconf3='selected="true"';
    }
    
    if($viewuser=="default"){
        $_ADOFacturas->getFacturas($ListAllAFacturas);
        $selecteduserconf3='selected="true"';
    }
    
    $SessionUser = new UserObj();
    if(isset($_SESSION['UserObj'])){
        $SessionUser= unserialize($_SESSION['UserObj']);
    }else{
        echo '<script type="text/javascript">document.location.href="../../index.php"</script>';
    }
    $SessionUser->GenerateToken();

    
    
?>
<div style="margin-top: 10px;">
    Ver facturas: 
    <select id="changeview" name="confuser">
        <option value="allinvoices" <?php echo $selecteduserconf3;?> >Todas las Facturas</option>
        <option value="pendingtoexpire" <?php echo $selecteduserconf1;?> >Pendientes por Vencer</option>
        <option value="newinvoices" <?php echo $selecteduserconf2;?> >Nuevas agregadas</option>
    </select>
</div>
<?php if($viewuser=="pendingtoexpire") {?>
<h2 style="color: darkgoldenrod">Facturas Por Vencer del <?php echo date("d M Y")?> al <?php echo date('d M Y',  strtotime(date('Y-m-d').'+ 20 days'));?></h2>
<div id="tableinfo">
    <table id="PendingToVoid" class="tableInfo" style="width: 100%">
        <tr>
            <th colspan="4">&nbsp;</th>
        </tr>
        <?php 
            if(count($ListFacturas->array)==0){
                echo '<tr><td colspan="4">No ahi Facturas Pendientes</td></tr>';
            }
        
            foreach($ListFacturas->array as $item){
                $pathfile= $config->domain."/".$config->pathServer.$item->archivoruta;
                $estado="";
                $item->getEstado();
                $estado=$item->EstadoFacturaObj->estado;
        ?>
        <tr>
            <td rowspan="2" style="text-align: center; width: 120px; padding-top: 10px; padding-bottom: 10px;">
                <a href="<?php echo $pathfile;?>" target="_blank"><img src="./images/factura-incono.png" style="width: 40px; height: 40px;" /></a><br/>
                <a href="<?php echo $pathfile;?>" target="_blank">ver</a>
            </td>
            <td>Factura Numero:<strong> <?php echo $item->numerofactura;?></strong></td>
            <td>Fecha Factura: <strong><?php echo $item->fecha;?></strong></td>
            <td>Saldo : $ <strong><span id="t1saldo_<?php echo $item->idfactura;?>"> <?php echo number_format($item->montoactual,2,'.',',');?></span></strong></td>
        </tr>
        <tr>
            <td>Numero de Orden : <strong><?php echo $item->numeroorden;?></strong></td>
            <td>Vence: <strong><?php echo $item->vencimiento;?></strong></td>
            <td>Estado: <strong><?php echo $estado;?></strong> </td>

        </tr>
        <tr>
            
            <td colspan="2" style="padding-left: 15px;">
                <?php if($item->idestado==0){ ?>
                Pago Parcial : <input type="text" name="t1parcialpayment_<?php echo $item->idfactura;?>" value=""  style="width: 55px;">
                &nbsp;Fecha:<input id="t1dateparcial_<?php echo $item->idfactura;?>" type="text" name="t1dateparcial_<?php echo $item->idfactura;?>" value=""  style="width: 88px;">
                <button id="t1makepayment_<?php echo $item->idfactura;?>" style="height: 30px;" > Hacer Pago</button>
                <?php } ?>
            </td>
            <td> 
                <?php if($item->idestado==0){ ?>
                <button id="t1PayInvoice_<?php echo $item->idfactura;?>" style="height: 30px; background-color: darkred;color: white" >  Cerrar Factura</button>
                <!--<button id="t1AddComments_<?php echo $item->idfactura;?>" style="height: 30px;" > Agregar Comentario</button>-->
                <?php } ?>
            </td>
            <td><button id="t1viewDetail_<?php echo $item->idfactura;?>" style="height: 30px;" >  Detalle de Factura</button></td>
        </tr>
        <tr>
            <td colspan="4" >
                <div id="t1message_<?php echo $item->idfactura;?>" style="color: darkred; font-weight: bold; padding-left: 30px;" ></div>
                <hr />
            </td>
        </tr>
        <?php 
            }
        ?>
    </table>
</div>
<?php }?>
<?php if($viewuser=="newinvoices"){?>
<h2 style="color: darkseagreen">Ultimas Facturas agregadas</h2>
<div id="tableinfo">
    <table id="LastNewInvoices" class="tableInfo" style="width: 100%">
        <tr>
            <th colspan="4">&nbsp;</th>
        </tr>
        <?php 
        
            if(count($ListFacturasNew->array)==0){
                echo '<tr><td colspan="4">No ahi Nuevas Facturas</td></tr>';
            }
            
            foreach($ListFacturasNew->array as $item){
                $pathfile= $config->domain."/".$config->pathServer.$item->archivoruta;
                $estado="";
                $item->getEstado();
                $estado=$item->EstadoFacturaObj->estado;
        ?>
        <tr>
            <td rowspan="2" style="text-align: center; width: 120px; padding-top: 10px; padding-bottom: 10px;">
                <a href="<?php echo $pathfile;?>" target="_blank"><img src="./images/factura-incono.png" style="width: 40px; height: 40px;" /></a><br/>
                <a href="<?php echo $pathfile;?>" target="_blank">ver</a>
            </td>
            <td>Factura Numero:<strong> <?php echo $item->numerofactura;?></strong></td>
            <td>Fecha Factura: <strong><?php echo $item->fecha;?></strong></td>
            <td>Saldo : $ <strong><span id="t2saldo_<?php echo $item->idfactura;?>"> <?php echo number_format($item->montoactual,2,'.',',');?></span></strong></td>
        </tr>
        <tr>
            <td>Numero de Orden : <strong><?php echo $item->numeroorden;?></strong></td>
            <td>Vence: <strong><?php echo $item->vencimiento;?></strong></td>
            <td>Estado: <strong><?php echo $estado;?></strong> </td>

        </tr>
        <tr>
            
            <td colspan="2" style="padding-left: 15px;">
                <?php if($item->idestado==0){ ?>
                Pago Parcial : <input type="text" name="t2parcialpayment_<?php echo $item->idfactura;?>" value=""  style="width: 55px;">
                &nbsp;Fecha:<input id="t2dateparcial_<?php echo $item->idfactura;?>" type="text" name="t2dateparcial_<?php echo $item->idfactura;?>" value=""  style="width: 88px;">
                <button id="t2makepayment_<?php echo $item->idfactura;?>" style="height: 30px;" > Hacer Pago</button>
                <?php } ?>
            </td>
            <td> 
                <?php if($item->idestado==0){ ?>
                <button id="t2PayInvoice_<?php echo $item->idfactura;?>" style="height: 30px; background-color: darkred;color: white" >  Cerrar Factura</button>
                <!--<button id="t2AddComments_<?php echo $item->idfactura;?>" style="height: 30px;" > Agregar Comentario</button>-->
                <?php } ?>
            </td>
            <td><button id="t2viewDetail_<?php echo $item->idfactura;?>" style="height: 30px;" >  Detalle de Factura</button></td>
        </tr>
        <tr>
            <td colspan="4" >
                <div id="t2message_<?php echo $item->idfactura;?>" style="color: darkred; font-weight: bold; padding-left: 30px;" ></div>
                <hr />
            </td>
        </tr>
        <?php 
            }
        ?>
    </table>
</div>
<?php }?>
<?php if($viewuser=="allinvoices" || $viewuser=="default"){?>
<h2 style="color: darkseagreen">Lista de Facturas</h2>
<div id="tableinfo">
    <table id="LastNewInvoices" class="tableInfo" style="width: 100%">
        <tr>
            <th colspan="4">&nbsp;</th>
        </tr>
        <?php 
        
            if(count($ListAllAFacturas->array)==0){
                echo '<tr><td colspan="4">No ahi Nuevas Facturas</td></tr>';
            }
            
            foreach($ListAllAFacturas->array as $item){
                $pathfile= $config->domain."/".$config->pathServer.$item->archivoruta;
                $estado="";
                $item->getEstado();
                $estado=$item->EstadoFacturaObj->estado;
        ?>
        <tr>
            <td rowspan="2" style="text-align: center; width: 120px; padding-top: 10px; padding-bottom: 10px;">
                <a href="<?php echo $pathfile;?>" target="_blank"><img src="./images/factura-incono.png" style="width: 40px; height: 40px;" /></a><br/>
                <a href="<?php echo $pathfile;?>" target="_blank">ver</a>
            </td>
            <td>Factura Numero:<strong> <?php echo $item->numerofactura;?></strong></td>
            <td>Fecha Factura: <strong><?php echo $item->fecha;?></strong></td>
            <td>Saldo : $ <strong><span id="t2saldo_<?php echo $item->idfactura;?>"> <?php echo number_format($item->montoactual,2,'.',',');?></span></strong></td>
        </tr>
        <tr>
            <td>Numero de Orden : <strong><?php echo $item->numeroorden;?></strong></td>
            <td>Vence: <strong><?php echo $item->vencimiento;?></strong></td>
            <td>Estado: <strong><?php echo $estado;?></strong> </td>

        </tr>
        <tr>
            
            <td colspan="2" style="padding-left: 15px;">
                <?php if($item->idestado==0){ ?>
                Pago Parcial : <input type="text" name="t2parcialpayment_<?php echo $item->idfactura;?>" value=""  style="width: 55px;">
                &nbsp;Fecha:<input id="t2dateparcial_<?php echo $item->idfactura;?>" type="text" name="t2dateparcial_<?php echo $item->idfactura;?>" value=""  style="width: 88px;">
                <button id="t2makepayment_<?php echo $item->idfactura;?>" style="height: 30px;" > Hacer Pago</button>
                <?php } ?>
            </td>
            <td> 
                <?php if($item->idestado==0){ ?>
                <button id="t2PayInvoice_<?php echo $item->idfactura;?>" style="height: 30px; background-color: darkred;color: white" >  Cerrar Factura</button>
                <!--<button id="t2AddComments_<?php echo $item->idfactura;?>" style="height: 30px;" > Agregar Comentario</button>-->
                <?php } ?>
            </td>
            <td><button id="t2viewDetail_<?php echo $item->idfactura;?>" style="height: 30px;" >  Detalle de Factura</button></td>
        </tr>
        <tr>
            <td colspan="4" >
                <div id="t2message_<?php echo $item->idfactura;?>" style="color: darkred; font-weight: bold; padding-left: 30px;" ></div>
                <hr />
            </td>
        </tr>
        <?php 
            }
        ?>
    </table>
</div>
<?php }?>
<div id="MessageBoard">
    <div id="Message"></div>
</div>
<script src="./js/jquery-1.12.1.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">
$(function() {
        $("input[name*='dateparcial']" ).datepicker({dateFormat: "yy-mm-dd"});
        
        dialog=$('#MessageBoard').dialog({
            autoOpen: false,
            height: 320,
            width: 400,
            modal: true,
            buttons:{OK:function(){dialog.dialog( "close" );}}
        });
        
    });

$("button[id*='AddComments']").click(function(){
    var idbutton= $(this).attr("id");
    var val=idbutton.split("_");
    var idfactura=val[1];
    var token="<?php echo $SessionUser->token;?>";
    
    document.location.href="./modules/InvoiceManager/viewInvoice.php?token="+token+"&param="+idfactura+"&action=comment";
});

$("button[id*='viewDetail']").click(function(){
    var idbutton= $(this).attr("id");
    var val=idbutton.split("_");
    var idfactura=val[1];
    var token="<?php echo $SessionUser->token;?>";
    
    document.location.href="./modules/InvoiceManager/viewInvoice.php?token="+token+"&param="+idfactura+"&action=view/edit";
});

$("button[id^='t1makepayment']").click(function(){
    
    var idbutton= $(this).attr("id");
    var val=idbutton.split("_");
    var idfactura=val[1];
    var pagoparcial=$("input[name='t1parcialpayment_"+idfactura+"']").val();
    var fechapago=$("input[name='t1dateparcial_"+idfactura+"']").val();
    var token="<?php echo $SessionUser->token;?>";
    var submitdata= true;
    var message="";
    //alert("pago="+pagoparcial+" fecha="+fechapago);
    $('#t1message_'+idfactura).html("");
    
    if(pagoparcial===""){
        submitdata=false;
        message=message+"Dato de Pago Vacio!<br />";
    }
    
    if(isNaN(pagoparcial)){
        submitdata=false;
         message=message+"Dato de Pago Invalido!<br />";
    }
    
    if(fechapago===""){
        submitdata=false;
        message=message+"Dato de Fecha Vacio!<br />";
    }
    
    
    if(submitdata){
        $.post(
                "./modules/InvoiceManager/AddPayment.php",
                {token:token,idfactura:idfactura,pagoparcial:pagoparcial,fechapago:fechapago},
                function(result){
                    //alert(result);
                   var valsplit=result;
                   var v=valsplit.split("|");
                   /*var montoactual=v[1];
                   var estado=v[2];
                   $('#saldo_'+idfactura).html(montoactual);
                   if(estado==1){
                       document.location.href="invoiceManager.php";
                   }
                    $('#message_'+idfactura).html("");
                    
                  */
                    if(v[0]==" return"){
                       $('#Message').append("<h2>Deposito Parcial Aplicado<h2>");
                       $('#Message').css("color","green");
                       $('#MessageBoard').dialog('open');
                    }
                    setTimeout(function(){
                        document.location.reload();
                    },3000);
                    
                  
                }
        );
        
        
        
    }else{
        $('#t1message_'+idfactura).html(message);
        
    }
    
    
    
    
});

$("button[id^='t2makepayment']").click(function(){
    
    var idbutton= $(this).attr("id");
    var val=idbutton.split("_");
    var idfactura=val[1];
    var pagoparcial=$("input[name='t2parcialpayment_"+idfactura+"']").val();
    var fechapago=$("input[name='t2dateparcial_"+idfactura+"']").val();
    var token="<?php echo $SessionUser->token;?>";
    var submitdata= true;
    var message="";
    //alert("pago="+pagoparcial+" fecha="+fechapago);
    $('#t2message_'+idfactura).html("");
    
    if(pagoparcial===""){
        submitdata=false;
        message=message+"Dato de Pago Vacio!<br />";
    }
    
    if(isNaN(pagoparcial)){
        submitdata=false;
         message=message+"Dato de Pago Invalido!<br />";
    }
    
    if(fechapago===""){
        submitdata=false;
        message=message+"Dato de Fecha Vacio!<br />";
    }
    
    
    if(submitdata){
        $.post(
                "./modules/InvoiceManager/AddPayment.php",
                {token:token,idfactura:idfactura,pagoparcial:pagoparcial,fechapago:fechapago},
                function(result){
                    //alert(result);
                   /*var valsplit=result;
                   var v=valsplit.split("|");
                   var montoactual=v[1];
                   var estado=v[2];
                   $('#saldo_'+idfactura).html(montoactual);
                   if(estado==1){
                       document.location.href="invoiceManager.php";
                   }
                    $('#message_'+idfactura).html("");
                    */
                   document.location.reload();
                   
                }
                );
        
        
    }else{
        $('#t2message_'+idfactura).html(message);
        
    }
    
    
    
    
});

$("button[id*='PayInvoice_']").click(function(){
    var idbutton= $(this).attr("id");
    var val=idbutton.split("_");
    var idfactura=val[1];
    var token="<?php echo $SessionUser->token;?>";
    
    $.post(
            "./modules/InvoiceManager/CloseInvoice.php",
            {token:token,idfactura:idfactura},
                function(result){
                    document.location.reload();
                }
            );
    
});

$('#changeview').change(function(){
    var confuser=$(this).val();
    $.post(
           "setConfuser.php",
           {confuser:confuser},
           function(result){
               document.location.reload();
           }
           );
});


</script>