<?php

class Category
{

    private $conn;
    private $table = 'categories';

    // Category properties
    public $id;
    public $name;
    public $createdAt;

    //constructore with db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    //get Categorys
    public function allCategory()
    {
        $query = '
            SELECT *
            FROM ' . $this->table . '';

        // prepare statement
        $statement = $this->conn->prepare($query);

        $statement->execute();

        return $statement;
    }

    public function show()
    {
        $query = '
            SELECT *
            FROM ' . $this->table . '
            WHERE id = ? LIMIT 1
        ';

        // prepare statement
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $this->id);

        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        $this->name = $row['name'];

        return $statement;
    }

    public function create()
    {
        // query insert 
        $query = '
            INSERT INTO ' . $this->table . ' 
            SET  name = :name
        ';

        $statement = $this->conn->prepare($query);

        //make sure clean data
        $this->name        = htmlspecialchars(strip_tags($this->name));

        $statement->bindParam(':name', $this->name);

        if ($statement->execute()) {
            return true;
        }

        printf("Error %s. \n", $statement->error);
        return false;
    }

    public function update()
    {
        // query insert 
        $query = '
            UPDATE ' . $this->table . ' 
            SET  name = :name
            WHERE id = :id
        ';

        $statement = $this->conn->prepare($query);

        //make sure clean data
        $this->id           = htmlspecialchars(strip_tags($this->id));
        $this->name        = htmlspecialchars(strip_tags($this->name));

        $statement->bindParam(':id', $this->id);
        $statement->bindParam(':name', $this->name);

        if ($statement->execute()) {
            return true;
        }

        printf("Error %s. \n", $statement->error);
        return false;
    }

    public function delete()
    {
        $query = '
            DELETE FROM ' . $this->table . ' WHERE id = :id
        ';

        $statement = $this->conn->prepare($query);

        //clean data
        $this->id           = htmlspecialchars(strip_tags($this->id));

        $statement->bindParam(':id', $this->id);

        if ($statement->execute()) {
            return true;
        }

        printf("Error %s. \n", $statement->error);
        return false;
    }
}
