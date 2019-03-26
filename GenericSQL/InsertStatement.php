<?php

namespace GenericSQL;

class InsertStatement
{
    private $values;
    private $table;


    /**
     * Lässt die angegebenen Werte in die angegebene Tabelle einfügen.
     * Der MySQL-String wird aus dem assoziativen Array zusammengebaut. Dadurch ist eine flexible Verwendung möglich.
     *
     * @param   string $table Ist die Tabelle, auf der die Abfrage durchgeführt wird.
     * @param   array $values Sind die einzufügenden Werte als assoziatives Array
     *                              Format hierfür ist array(Parameter => Wert, ...)
     */
    function __construct($table, array $values)
    {
        $this->table = $table;
        $this->values = $values;
    }


    /**
     * Gibt das InsertStatement als string zurück.
     *
     * @param   array $values Stellt eine Referenz dar. In dieser werden die Werte als assoziatives array ausgegeben.
     * @return  string              Das InsertStatement als string.
     */
    public function getQuery(&$values)
    {
        $sql = "insert into %s (%s) values (%s)";

        $sqlIndexes = implode(",", array_keys($this->values));

        foreach ($this->values as $index => $value) {
            $this->values[":" . $index] = $value;
            unset($this->values[$index]);
        }

        $sqlValues = implode(",", array_keys($this->values));
        $values = $this->values;

        return sprintf($sql, $this->table, $sqlIndexes, $sqlValues);
    }


    /**
     * Erstellt einen Universally Unique Identifier
     *
     * @param   string $idIndex
     *
     * @return  InsertStatement
     */
    public function generateUUID($idIndex = "id")
    {
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        $this->values[$idIndex] = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));

        return $this;
    }
}