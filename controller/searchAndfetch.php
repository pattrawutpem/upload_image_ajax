<?php
    include('../config/db.php');
    include('function.php');
    $query = '';
    $output = array();
    $query .= "SELECT * FROM users";
    if(isset($_POST["search"]["value"]))
    {
        $query .= ' where first_name LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= 'OR last_name LIKE "%'.$_POST["search"]["value"].'%" ';
    }
    if(isset($_POST["order"]))
    {
        $query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
    }
    else
    {
        $query .= 'ORDER BY id DESC ';
    }
    if($_POST["length"] != -1)
    {
        $query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
    }
    $statement = $connection->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $data = array();
    $filtered_rows = $statement->rowCount();
    foreach($result as $row)
    {
        $image = '';
        if($row["image"] != '')
        {
            $image = '<img src="upload/'.$row["image"].'" class="img-thumbnail" width="100" height="35" />';
        }
        else
        {
            $image = '';
        }
        $sub_array = array();
        $sub_array[] = $image;
        $sub_array[] = $row["first_name"];
        $sub_array[] = $row["last_name"];
        $sub_array[] = '<button type="button" name="update" id="'.$row["id"].'" class="btn btn-primary btn-xs update mx-4"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>';
        $sub_array[] = '<button type="button" name="delete" id="'.$row["id"].'" class="btn btn-danger btn-xs delete mx-4"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
        $data[] = $sub_array;
    }
    $output = array(
        "draw"              =>  intval($_POST["draw"]),
        "recordsTotal"      =>  $filtered_rows,
        "recordsFiltered"   =>  get_total_all_records(),
        "data"              =>  $data
    );
    echo json_encode($output);
?>