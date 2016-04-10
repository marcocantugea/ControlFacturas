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
 * Description of PagoFactura
 *
 * @author MarcoCantu
 */
class PagoFacturaObj {
   public $idfacturapagos;
   public $idfactura;
   public $montoactual;
   public $pagoparcial;
   public $montoantespago;
   public $fechadepago;
   public $commentarios;
   public $FacturaObj;
   
   public function GetFactura(){
       if($this->idfactura>0){
           $this->FacturaObj= new FacturaObj();
           $this->FacturaObj->idfactura=  $this->idfactura;
           $_ADOFacturas = new ADOFacturas();
           $_ADOFacturas->getFacturasById($this->FacturaObj);
       }
   }
   
   public function RecalculaMontoFactura(){
       if(!empty($this->FacturaObj) && $this->pagoparcial>0){
           $this->montoantespago= $this->FacturaObj->montoactual;
           $this->montoactual=  $this->montoantespago - $this->pagoparcial;
           
       }
   }
}
