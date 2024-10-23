<?php

//header config
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//initializing api
include_once('../core/Initialize.php');

//getting request method
$requestMethod = $_SERVER['REQUEST_METHOD'];

$post = new Post($db);

//GET data id
$post->id = isset($_GET['id']) ? $_GET['id'] : false;

//GET all post
if ($requestMethod == 'GET' && $post->id == false) {
    //result query post
    $result = $post->allPosts();

    $rowCount = $result->rowCount();

    if ($rowCount > 0) {
        $postArr = [];
        $postArr['data'] = [];

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $postItem = [
                'id' => $id,
                'title' => $title,
                'body' => html_entity_decode($body),
                'author' => $author,
                'category_id' => $category_id,
                'category_name' => $category_name
            ];

            array_push($postArr['data'], $postItem);
        }

        //convert to JSON Format
        echo json_encode($postArr);
    } else {
        echo json_encode(['message' => 'No post found!!']);
    }
}

//GET single post
if ($requestMethod == 'GET' && $post->id != false) {
    //result query single post
    $result = $post->read();

    $rowCount = $result->rowCount();

    if ($rowCount > 0) {
        $postArr = [];
        $postArr['data'] = [];

        $postItem = [
            'id' => $post->id,
            'title' => $post->title,
            'body' => html_entity_decode($post->body),
            'author' => $post->author,
            'category_id' => $post->categoryId,
            'category_name' => $post->categoryName
        ];

        array_push($postArr['data'], $postItem);


        //convert to JSON Format
        echo json_encode($postArr);
    } else {
        echo json_encode(['message' => 'No post found!!']);
    }
}

if ($requestMethod == 'POST') {
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

    // get raw data
    $data = json_decode(file_get_contents("php://input"));

    $post->title = $data->title;
    $post->body = $data->body;
    $post->author = $data->author;
    $post->categoryId = $data->category_id;

    if (is_null($data)) {
        die("Invalid JSON data.");
    }

    if ($post->create()) {
        echo json_encode(
            array('message' => 'Post created!!')
        );
    } else {
        echo json_encode(
            array('message' => 'Post not created!!')
        );
    }
}

if ($requestMethod == 'PUT') {
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

    // get raw data
    $data = json_decode(file_get_contents("php://input"));

    $post->id = $data->id;
    $post->title = $data->title;
    $post->body = $data->body;
    $post->author = $data->author;
    $post->categoryId = $data->category_id;

    if (is_null($data)) {
        die("Invalid JSON data.");
    }

    if ($post->update()) {
        echo json_encode(
            array('message' => 'Post updated!!')
        );
    } else {
        echo json_encode(
            array('message' => 'Post not updated!!')
        );
    }
}

if ($requestMethod == 'DELETE') {
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

    // get raw data
    $data = json_decode(file_get_contents("php://input"));

    $post->id = $data->id;

    if (is_null($data)) {
        die("Invalid JSON data.");
    }

    if ($post->delete()) {
        echo json_encode(
            array('message' => 'Post deleted!!')
        );
    } else {
        echo json_encode(
            array('message' => 'Post not deleted!!')
        );
    }
}
