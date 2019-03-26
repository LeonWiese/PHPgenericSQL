<?php

namespace GenericSQL;

/**
 * Lässt Datensätze aus der Tabelle entfernen
 */
class DeleteStatement
{
    private $table;
    private $where;


    /**
     * Konstruktor der Klasse
     *
     * @param   string    $table    Gibt die Tabelle an, aus der die Daten entfernt werden sollen
     * @param   string    $where    Ist die Bedingung, welche zum Löschen angegeben werden kann
     */
    function __construct($table, $where = "")
    {
        $this->table = $table;
        $this->where = $where;
    }


    /***
     * Gibt das DeleteStatement als String zurück.
     */
    public function getQuery(){
        $sql = 'delete from %s %s';

        return sprintf($sql, $this->table,
            empty($this->where) ? '' : 'where '. $this->where
        );
    }


    /**
     * Weist die Where-Abfrage dem Objekt zu.
     *
     * @param   string              $where  Die zuzuordnende Where-Abfrage
     * @return  DeleteStatement             Das aktuelle DeleteStatement.
     */
    public function where($where) {
        $this->where = $where;
        return $this;
    }
}