<?php

//header config
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//initializing api
include_once('../core/Initialize.php');

//getting request method
$requestMethod = $_SERVER['REQUEST_METHOD'];

$category = new Category($db);

//GET data id
$category->id = isset($_GET['id']) ? $_GET['id'] : false;

//GET all post
if ($requestMethod == 'GET' && $category->id == false) {
    //result query post
    $result = $category->allCategory();

    $rowCount = $result->rowCount();

    if ($rowCount > 0) {
        $categoryArr = [];
        $categoryArr['data'] = [];

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $categoryItem = [
                'id' => $id,
                'name' => $name,
            ];

            array_push($categoryArr['data'], $categoryItem);
        }

        //convert to JSON Format
        echo json_encode($categoryArr);
    } else {
        echo json_encode(['message' => 'No category found!!']);
    }
}

//GET single category
if ($requestMethod == 'GET' && $category->id != false) {
    //result query single category
    $result = $category->show();

    $rowCount = $result->rowCount();

    if ($rowCount > 0) {
        $categoryArr = [];
        $categoryArr['data'] = [];

        $categoryItem = [
            'id' => $category->id,
            'name' => $category->name,
        ];

        array_push($categoryArr['data'], $categoryItem);


        //convert to JSON Format
        echo json_encode($categoryArr);
    } else {
        echo json_encode(['message' => 'No post found!!']);
    }
}

if ($requestMethod == 'POST') {
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

    // get raw data
    $data = json_decode(file_get_contents("php://input"));

    $category->name = $data->name;

    if ($category->create()) {
        echo json_encode(
            array('message' => 'Category created!!')
        );
    } else {
        echo json_encode(
            array('message' => 'Category not created!!')
        );
    }
}

if ($requestMethod == 'PUT') {
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

    // get raw data
    $data = json_decode(file_get_contents("php://input"));

    $category->id = $data->id;
    $category->name = $data->name;

    if ($category->update()) {
        echo json_encode(
            array('message' => 'Category updated!!')
        );
    } else {
        echo json_encode(
            array('message' => 'Category not updated!!')
        );
    }
}

if ($requestMethod == 'DELETE') {
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

    // get raw data
    $data = json_decode(file_get_contents("php://input"));

    $category->id = $data->id;

    if ($category->delete()) {
        echo json_encode(
            array('message' => 'Category deleted!!')
        );
    } else {
        echo json_encode(
            array('message' => 'Category not deleted!!')
        );
    }
}
