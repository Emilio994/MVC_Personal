<?php

namespace Frame\Models;

class BaseModel {

    protected static $db;
    protected string $modelName;

    public function __construct()
    {   
        global $db;
        static::$db = $db;
    }

    public function exec(string $query, mixed $params = null, bool $many=true)
    {
        $query = static::$db->prepare($query);


        try {

            $query->execute($params);

            if($many) return $query->fetchAll();

            return $query->fetch();

        } catch(\PDOException $e) {

            echo $e->getMessage();

        }
    }

    public function get($id,$override=''){

        $query = $override  
            ? ("SELECT *
                FROM $override
                WHERE $override = :$override") 
            : ("SELECT *
                FROM {$this->modelName}
                WHERE id = :id")
            ;

        return $this->exec(
            $query,
            [':id' => $id],
            false
        );
    }

    public function all() {

        $query = "SELECT * FROM {$this->modelName}";

        return $this->exec($query);
    }

}