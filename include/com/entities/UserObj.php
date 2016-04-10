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
 * Description of UserObj
 *
 * @author MarcoCantu
 */
class UserObj {
    public $iduser;
    public $user;
    public $pass;
    public $active;
    public $email;
    public $token;
    
    public function GenerateToken(){
        if($this->iduser>0 && !empty($this->email)){
             $this->token=md5($this->iduser.  $this->email . date('d'));
        }
    }
}
