<?php

class Post
{

    private $conn;
    private $table = 'posts';

    // post properties
    public $id;
    public $categoryId;
    public $categoryName;
    public $title;
    public $body;
    public $author;
    public $createdAt;

    //constructore with db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    //get posts
    public function allPosts()
    {
        $query = '
            SELECT 
                categories.name as category_name,
                posts.id, posts.category_id, posts.title, 
                posts.body, posts.author, posts.created_at
            FROM ' . $this->table . '
            INNER JOIN
                categories ON posts.category_id = categories.id
            ORDER BY posts.created_at DESC
        ';

        // prepare statement
        $statement = $this->conn->prepare($query);

        $statement->execute();

        return $statement;
    }

    public function read()
    {
        $query = '
            SELECT 
                categories.name as category_name,
                posts.id, posts.category_id, posts.title, 
                posts.body, posts.author, posts.created_at
            FROM ' . $this->table . '
            INNER JOIN
                categories ON posts.category_id = categories.id
            WHERE posts.id = ? LIMIT 1
        ';

        // prepare statement
        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $this->id);

        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        $this->title = $row['title'];
        $this->body = $row['body'];
        $this->author = $row['author'];
        $this->categoryId = $row['category_id'];
        $this->categoryName = $row['category_name'];

        return $statement;
    }
}
