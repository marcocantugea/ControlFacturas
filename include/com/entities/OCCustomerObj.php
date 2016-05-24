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
 * Description of OCCustomerObj
 *
 * @author MarcoCantu
 */
class OCCustomerObj {
    public $customer_id;
    public $store_id;
    public $firstname;
    public $lastname;
    public $email;
    public $telephone;
    public $fax;
    public $password;
    public $salt;
    public $cart;
    public $wishlist;
    public $newsletter;
    public $address_id;
    public $customer_group_id;
    public $ip;
    public $status;
    public $approved;
    public $token;
    public $date_added;
    
    public function setValueToMember ($membername, $value){
        switch ($membername){
            case "customer_id":
                $this->customer_id=$value;
                break;
            case "store_id":
                $this->store_id=$value;
                break;
            case "firstname":
                $this->firstname=$value;
                break;
            case "lastname":
                $this->lastname=$value;
                break;
            case "email":
                $this->email=$value;
                break;
            case "telephone":
                $this->telephone=$value;
                break;
            case "fax":
                $this->fax=$value;
                break;
            case "password":
                $this->password=$value;
                break;
            case "salt":
                $this->salt=$value;
                break;
            case "cart":
                $this->cart=$value;
                break;
            case "wishlist":
                $this->wishlist=$value;
                break;
            case "newsletter":
                $this->newsletter=$value;
                break;
            case "address_id":
                $this->address_id=$value;
                break;
            case "customer_group_id":
                $this->customer_group_id=$value;
                break;
            case "ip":
                $this->ip=$value;
                break;
            case "status":
                $this->status=$value;
                break;
            case "approved":
                $this->approved=$value;
                break;
            case "token":
                $this->token=$value;
                break;
            case "date_added":
                $this->date_added=$value;
                break;
        }
    }
    
}
