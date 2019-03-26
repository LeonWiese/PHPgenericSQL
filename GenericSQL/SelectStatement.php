<?php

namespace GenericSQL;

class SelectStatement
{
    private $table;
    private $param;
    private $where;
    private $having;
    private $order;
    private $join;
    private $setOperations; //== MengenOperationen


    /**
     * Konstruktor der 
     *
     * @param   string  $table  Die abzufragende Tabelle als 
     * @param   string  $param  Die abzufragenden Parameter als 
     */
    function __construct($table, $param = '*')
    {
        $this->table = $table;
        $this->param = $param;
        $this->where = '';
        $this->order = '';
        $this->having= '';
        $this->join  = [];
        $this->setOperations = [];
    }


    /**
     * Gibt das SelectStatement als string 
     *
     * @return  string  Das SelectStatement als 
     */
    public function getQuery(){
        $sql = "select %s from %s %s %s %s %s %s";

        return sprintf($sql, $this->param, $this->table,
            empty($this->join)  ? '' : implode($this->join),
            empty($this->where) ? '' : 'where '. $this->where,
            empty($this->having)? '' : 'having '. $this->having,
            empty($this->order) ? '' : 'order by '. $this->order,
            empty($this->setOperations) ? '' : implode($this->setOperations)
        );
    }


    /**
     * Weist Parameter zu
     * @param   string          $param  Die zuzuweisenden Parameter
     * @return  SelectStatement         Das aktuelle SelectStatement
     */
    public function param($param){
        $this->param = $param;
        return $this;
    }


    public function where($where){
        $this->where = $where;
        return $this;
    }


    public function having($having){
        $this->having = $having;
        return $this;
    }


    /**
     * Methode
     *
     * @param $order
     * @return $this
     */
    public function orderBy($order){
        $this->order = $order;
        return $this;
    }





    public function join($table, $comparison, $joinPrefix = ''){
        $compareType = strlen(str_replace(['=', '<', '>'], '', $comparison)) === strlen($comparison) ? "using" : "on";
        array_push($this->join, sprintf(" %s join %s %s (%s)", $joinPrefix, $table, $compareType, $comparison));
        return $this;
    }


    public function leftJoin($table, $comparison){
        return $this->join($table, $comparison, "left");
    }


    public function rightJoin($table, $comparison){
        return $this->join($table, $comparison, "right");
    }


    public function fullOuterJoin($table, $comparison){
        return $this->join($table, $comparison, "full outer");
    }





    public function setOperation(SelectStatement $stm, $operation){
        $sql = $stm->getQuery();
        array_push($this->setOperations, sprintf(" %s %s", $operation, $sql));
        return $this;
    }


    public function union(SelectStatement $stm){
        return $this->setOperation($stm, "union");
    }


    public function unionAll(SelectStatement $stm){
        return $this->setOperation($stm, "union all");
    }


    public function intersect(SelectStatement $stm){
        return $this->setOperation($stm, "intersect");
    }


    public function minus(SelectStatement $stm){
        return $this->setOperation($stm, "minus");
    }
}