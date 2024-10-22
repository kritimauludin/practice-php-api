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
    # code...
}

if ($requestMethod == 'PUT') {
    # code...
}

if ($requestMethod == 'DELETE') {
    # code...
}
