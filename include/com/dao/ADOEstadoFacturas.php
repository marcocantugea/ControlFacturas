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
 * Description of ADOEstadoFacturas
 *
 * @author MarcoCantu
 */
class ADOEstadoFacturas {
    private $mysqlconector;
    public $debug=false;
    
    public function __construct() {
        $this->mysqlconector= new MysqlConnector();
    }
    
    
    public function getEstadoByID($EstadoFacturaObj){
        if(!empty($EstadoFacturaObj)){
            $this->mysqlconector->OpenConnection();
            $idestado=  mysqli_real_escape_string($this->mysqlconector->conn,$EstadoFacturaObj->idestado);
            $sql="select idestado,estado,activo from t_estados where idestado=$idestado";
            if($this->debug){
                echo '<br/>'. $sql;
            }
            $result=  $this->mysqlconector->conn->query($sql) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
             if($result->num_rows>0){
                 while($row = $result->fetch_assoc()) {
                    $EstadoFacturaObj->idestado=$row['idestado'];
                    $EstadoFacturaObj->estado=$row['estado'];
                    $EstadoFacturaObj->activo=$row['activo'];
                 }
             }
            
            $this->mysqlconector->CloseDataBase();
        }
    }
    
}
