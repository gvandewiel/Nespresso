<?php
    // configuration
    include 'config.php';

    if(empty($_POST) or empty($_POST['id']) or empty($_POST['vote'])){
        $response = array(
            'success' => false,
            'message' => 'Somethin went wrong when trying to process your vote.'
        );
    }else{
        $id = $_POST['id'];
        $vote = $_POST['vote'];

        // selecting posts
        if($vote == 'pos'){
            $query = "UPDATE flavours SET pos = (pos + 1) WHERE id = ".$id;
        };

        if($vote == 'neg'){
            $query = "UPDATE flavours SET neg = (neg + 1) WHERE id = ".$id;
        };

        $result = $db->query($query);

        if($result){

        }

        $response = array(
            'success' => true,
            'message' => 'Vote processed successfully'
        );
    };

echo json_encode($response);
?>

