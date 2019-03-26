# PHPgenericSQL

This Database-Classes can generate SQL statements and execute them.

Use it like this:

1. Configure the `DBconfig.php` File

2. Write code:
   ```php
   use GenericSQL\Database;
   use GenericSQL\SelectStatement;
   use GenericSQL\InsertStatement;
   use GenericSQL\DeleteStatement;
   use GenericSQL\UpdateStatement;
   
   Database::query((new SelectStatement("Table"))->join("OtherTable", "oid"));
   
   Database::query((new SelectStatement("Table"))->join("OtherTable", "aid = oid"));
   //join can automatically detect using and where

   Database::query((new SelectStatement("Table", "id, name, address"))
       ->intersect(new SelectStatement("OtherTable")));
   //Combine multiple statements

   Database::query((new SelectStatement("Table"))
       ->rightJoin("Other", "id")
       ->orderBy("name")
       ->join("Third", "tid")
       ->param("name, address"));
   //Order does not matter
   
   Database::query((new InsertStatement("Table", [
       "name" => "foo",
       "address" => "grove street"
   ]))->generateUUID("id"));
   //Insert "key" => "value"
   //Generate UUID with Key: id
   
   Database::query(new DeleteStatement("Table", "uid = 3"));
   
   Database::query(new UpdateStatement("Table", [
       "name" => "bar",
       "other" => "value"
   ], "id = 3"));
   ```

Works best, if you got a smart IDE which gives you code suggestions.
