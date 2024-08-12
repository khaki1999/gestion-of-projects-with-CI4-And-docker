<?php
namespace App\Libraries;
class hash
{
    public static function make($password){
        return password_hash($password,PASSWORD_BCRYPT);

    }

     public static function check ($password,$db_hashed_password){
        if( password_verify($password,$db_hashed_password)){
            return true;
        }else{
            return false;
        }
     }
}