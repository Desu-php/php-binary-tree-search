<?php
if (!empty($_POST['field']) && !empty($_POST['value'])){
    require 'vendor/autoload.php';

    $data = json_decode(file_get_contents('documents.json'), true);

    $tree = new \App\BinarySearchTree();

    $field = $_POST['field'];

    $tree->createIndex($data, $field);

    $value = $_POST['value'];

    $result = $tree->search($field, $value);

    foreach ($result as $item){
        print_r($item);
        echo "</br>Iteration {$item['iteration']}</br> <hr/>";
    }

    $withoutIndexIteration = 0;
    foreach ($data as $document) {
        $withoutIndexIteration++;

        if (isset($document[$field]) && $document[$field] === $value) {
            print_r($document);
            echo "</br>Without Index Iteration $withoutIndexIteration </br> <hr/>";
        }
    }
}
?>

<form action="/" method="post">
    <input name="field" placeholder="field" required> </br>
    <input name="value" placeholder="value" required> </br>
    <button type="submit">Submit</button>
</form>
