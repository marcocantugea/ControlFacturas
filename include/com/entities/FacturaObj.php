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

/**
 * Description of FacturaObj
 *
 * @author MarcoCantu
 */
class FacturaObj {
    public $idfactura;
    public $fecha;
    public $monto;
    public $archivoruta;
    public $vencimiento;
    public $montoactual;
    public $idestado;
    public $numerofactura;
    public $numeroorden;
    public $EstadoFacturaObj;
    public $ListPagosFactura;
    
    public function getEstado(){
        if($this->idestado>0){
            $this->EstadoFacturaObj = new EstadoFacturaObj();
            $this->EstadoFacturaObj->idestado=  $this->idestado;
            $_ADOEstadoFacturas= new ADOEstadoFacturas();
            $_ADOEstadoFacturas->getEstadoByID($this->EstadoFacturaObj);
            
        }else{
            $this->EstadoFacturaObj = new EstadoFacturaObj();
            $this->EstadoFacturaObj->idestado=0;
            $this->EstadoFacturaObj->estado="Pendiente";
            $this->EstadoFacturaObj->activo=1;
        }
    }
    
    public function getPagosFactura(){
        if($this->idfactura>0){
            $_ADOPagosFactura= new ADOPagosFactura();
            $this->ListPagosFactura = new ArrayList();
            $_ADOPagosFactura->getPagosByFacturaId($this->ListPagosFactura, $this);
        }
    }
    
}
