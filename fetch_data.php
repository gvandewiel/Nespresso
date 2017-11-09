<?php
function percentageOf( $number, $everything, $decimals = 0 ){
    if($everything == 0){
        return "";
    }else{
        return round( $number / $everything * 100, $decimals );
    };
};


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
            $result = $db->query("SELECT * from flavours WHERE id = ".$id);
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$pos = $row['pos'];
				$neg = $row['neg'];
				$total = $pos + $neg;
				$pos = percentageOf($pos, $total);
				$neg = percentageOf($neg, $total);
			};
        }

        $response = array(
            'success' => true,
			'message' => 'Vote processed successfully',
			'pos' => $pos,
			'neg' => $neg,
            'id' => $id
        );
    };

echo json_encode($response);
?>

