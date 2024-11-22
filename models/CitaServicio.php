<?php

namespace Model;


class CitaServicio extends ActiveRecord{
    protected static $tabla = 'citaServicios';
    protected static $columnasDB = ['id','citaID','servicioId'];

    public $id;
    public $citaID;
    public $servicioId;

    public function __construct($args=[]) {
        $this->id =$args['id']??null;
        $this->citaID = $args['citaID'] ?? 4;
        $this->servicioId = $args['servicioId']??'';
    }
}