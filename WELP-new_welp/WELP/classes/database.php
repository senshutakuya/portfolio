<?php

/**
 * 
 * @package database
 * 
 * @author Yoshida Kento
 * 
 * @since PHP7.2
 * 
 * @version 1.0
 * 
 * データベースに関連するすべての処理をここで行う。
 * 
*/
class Database {

    private $db_handler;
    private $db_statement;
    private $sql;
    private $bind_array = [];

    public function connect(){
        global $root;
        $config = require($root . 'config/config.php');
        try{
            $this->db_handler = new PDO(
                "mysql:host=".$config["DB_HOST"].";dbname=".$config["DB_NAME"].';charset=utf8mb4',
                $config["DB_USER"],
                $config["DB_PASSWORD"],
                [
                    \PDO::ATTR_EMULATE_PREPARES => false,
                    \PDO::MYSQL_ATTR_MULTI_STATEMENTS => false
                ]
            );
            date_default_timezone_set("Asia/Tokyo");
        }catch(\PDOException $e){

        }
        unset($config);
    }

    public function setSQL($sql){
        $this->sql = $sql;
    }

    public function setBindArray($array){
        $this->bind_array = $array;
    }

    public function execute(){
        try{
            if(empty($this->db_handler)){
                $this->connect();
            }
            $this->db_handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db_statement = $this->db_handler->prepare($this->sql);
            //foreach($this->bind_array as $key => $value){
            //    $this->db_statement->bindParam($key, $value);
            //}
            $this->db_statement->execute($this->bind_array);
        }catch(Exception $e){
            print($e);
            exit;
        }
    }

    public function fetch(){
        return $this->db_statement->fetch(PDO::FETCH_BOTH);
    }

    public function fetchAll(){
        return $this->db_statement->fetchAll(PDO::FETCH_BOTH);
    }

    public function getLastInsertId() : string|false {
        return $this->db_handler->lastInsertId();
    }
}
