<?php

namespace GenericSQL;

class UpdateStatement
{
    private $table;
    private $values;
    private $where;


    /**
     * Lässt Werte in der Tabelle aktualisieren.
     * Auch hier wird der MySQL-String aus dem assoziativen Array zusammengebaut.
     * Wenn where nicht angegeben ist, werden alle Parameter der angegebenen Tabelle geupdatet.
     *
     * @param       string  $table      Ist die Tabelle, auf der die Abfrage durchgeführt wird.
     * @param       array   $values     Sind die Werte, die upgedated werden als assoziatives Array
     *                                  Format hierfür ist array(Parameter => Wert, ...)
     * @param       string  $where      Ist die Where-Abfrage mit der selektiert werden kann, welche Parameter
     *                                  abgefragt werden. Das Format dazu ist "Parameter = Wert".
     */
    function __construct($table, array $values, $where = "")
    {
        $this->table = $table;
        $this->values = $values;
        $this->where = $where;
    }



    public function getQuery(&$values){
        $sql = 'update %s set %s %s';
        $setStm = '';

        foreach ($this->values as $index => $value) {
            $setStm .= ',' . $index . '=:' . $index;
        }

        $setStm = substr($setStm, 1);

        $sql = sprintf($sql, $this->table, $setStm,
            empty($where) ? '' : 'where '. $where
        );

        foreach ($this->values as $index => $value) {
            $this->values[":".$index] = $value;
            unset($this->values[$index]);
        }

        $values = $this->values;
        return $sql;
    }


    public function where($where){
        $this->where = $where;
        return $this;
    }
}