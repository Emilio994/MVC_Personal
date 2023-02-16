<?php

namespace Frame\Models;

class Parkings extends BaseModel {

    protected string $modelName = 'parkings';

    public function parkExists($name) {

        return $this->exec(
            "SELECT *
            FROM parkings
            WHERE nome = :name",
            [
                ':name' => $name
            ],
            false
        );
    }

}