<?php

namespace GenericSQL;

/**
 * Erstellt eine Verbinung zur Datenbank.
 *
 * Mit dieser Klasse wird es dem Nutzer ermöglicht, eine Verbindung zur Datenbank zu
 * erstellen. Darüber hinaus bietet diese Klasse Methoden zur Abfrage, Einfügung und
 * zur Aktualisierung der mit ihr verbundenen Datenbank.
 */
class Database
{
    /** @var Database */
    private static $self;
    private $pdo;



    /**
     * Da die Datenbank über das Singleton-Pattern-Prinzip funktioniert,
     * wird mit dieser Methode die aktuelle oder eine neue Instanz der Datenbank-Klasse zurück gegeben.
     *
     * @return Database    Die aktuelle oder eine neue Instanz der Datenbank-Klasse
     */
    public static function getInstance(){
        if (self::$self === null) {
            self::$self = new Database();
        }

        return self::$self;
    }



    /**
     * Ist der Konstruktor der Datenbank-Klasse.
     * Hier wird dem Attribut PDO ein PDO-Objekt mit den Datenbankdaten aus der DBconfig.php zugewiesen.
     * Wenn die Verbindung fehlschlägt, wird ein Fehler geworfen.
     * Es wird die Methode onShutdown am Ende des PHP-Codes ausgeführt.
     */
    private function __construct(){
        $config = require "DBconfig.php";
        try {
            $this->pdo = new \PDO(sprintf("mysql:host=%s;dbname=%s", $config["host"], $config["dbname"]), $config["user"], $config["pw"]);
            register_shutdown_function(function() {
                $this->onShutdown();
            });
        }
        catch (\PDOException $e) {
            print_r("Fehler: ". $e);
        }
    }
    



    /**
     * Lässt Datenbankabfragen ausführen.
     * 
     * @param   SelectStatement|InsertStatement|UpdateStatement|DeleteStatement $statementObj
     * @param   bool $objFetch
     * @return  mixed
     */
    public static function query($statementObj, $objFetch = true){
        $db = self::getInstance();

        $values = [];
        $sql = $statementObj->getQuery($values);

        $statement = $db->pdo->prepare($sql);
        if (!$statement->execute($values)){
            print_r("Query fehlgeschlagen: ".$statement->errorInfo(). ' -- SQL: '.$sql);
            return null;
        }

        try {
            $fetch = $objFetch ? \PDO::FETCH_OBJ : \PDO::FETCH_BOTH;
            return $statement->fetchAll($fetch);
        }
        catch (\PDOException $e){
            print_r("Fehler: ". $e);
        }
    }


    /**
     * Die Methode schließt die Datenbankverbindung nach jeder Ausführung eines PHP-Codes.
     */
    private function onShutdown(){
        $this->pdo->query("SELECT pg_terminate_backend(pg_backend_pid());");
        $this->pdo = null;
        die;
    }
}