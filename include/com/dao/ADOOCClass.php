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
 * Description of ADOOSClass
 *
 * @author MarcoCantu
 */
class ADOOCClass {
    private $mysqlconector;
    public $debug=false;
    
    public function __construct() {
        $this->mysqlconector= new MysqlConnector();
        $newsettings = new Config();
        $newsettings->username="root";
        $newsettings->password="root";
        $newsettings->database="i1231562_oc1";
        $newsettings->servername="localhost";
        $this->mysqlconector->setSettings($newsettings);
    }
    
    
    public function getAllCustomers($ListOCCustomerObj){
        if(!empty($ListOCCustomerObj)){
            $this->mysqlconector->OpenConnection();
            $sqlobj= new SqlQueryBuilder("select");
            $sqlobj->setTable("oc_customer");
            $sqlobj->addColumn("customer_id");
            $sqlobj->addColumn("store_id");
            $sqlobj->addColumn("firstname");
            $sqlobj->addColumn("lastname");
            $sqlobj->addColumn("email");
            $sqlobj->addColumn("telephone");
            $sqlobj->addColumn("fax");
            $sqlobj->addColumn("password");
            $sqlobj->addColumn("salt");
            $sqlobj->addColumn("cart");
            $sqlobj->addColumn("wishlist");
            $sqlobj->addColumn("newsletter");
            $sqlobj->addColumn("address_id");
            $sqlobj->addColumn("customer_group_id");
            $sqlobj->addColumn("ip");
            $sqlobj->addColumn("status");
            $sqlobj->addColumn("approved");
            $sqlobj->addColumn("token");
            $sqlobj->addColumn("date_added");
            
            if($this->debug){
                echo '<br/>'. $sqlobj->buildQuery();
            }
            $result=  $this->mysqlconector->conn->query($sqlobj->buildQuery()) or trigger_error("Error ADOUsers::AddNewUser:mysqli=".mysqli_error($this->mysqlconector->conn),E_USER_ERROR);
            if($result->num_rows>0){
                while($row = $result->fetch_assoc()) {
                    $occustomer = new OCCustomerObj();
                    $varmembers = get_object_vars($occustomer);
                    foreach ($varmembers as $member=>$value){
                        $occustomer->setValueToMember($member, $row[$member]);
                    }
                    $ListOCCustomerObj->addItem($occustomer);
                    unset($occustomer);
                }
            }
            
            $this->mysqlconector->CloseDataBase();
        }
    }   
}
