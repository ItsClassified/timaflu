<?php 

    require 'php/functions.php';

    function DrawTable($query)
    {
        $db = ConnectDatabase();
             
            $result = $db->prepare($query);                           
            $result->execute();
            $colcount = $result->columnCount();
            
        
            echo "<table class='stats'>";
            echo "<tr>";
            for ($i = 0; $i < $colcount; $i++){
                $meta = $result->getColumnMeta($i)["name"];
                echo('<th>' . $meta . '</th>');
            }
            echo('</tr>');
        

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
            {
                echo('<tr>');
                for ($i = 0; $i < $colcount; $i++){
                    $meta = $result->getColumnMeta($i)["name"];
                    echo('<td>' . $row[$meta] . '</td>');
                }
                echo('</tr>');
            }
            echo "</table>";
        
    }

?>

<html>
    <head>
    <link rel="stylesheet" type="text/css" href="css/stylesheet.css"/>
        <link rel="stylesheet" type="text/css" href="css/top.css"/>
        <link rel="stylesheet" type="text/css" href="css/form.css" />
        <link rel="stylesheet" href="css/animate.css">
        <link rel="stylesheet" href="css/message.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <!-- <link href="css/stylesheet.css" rel="stylesheet" type="text/css"> -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
        <script src="js/main.js"></script>
    </head>

    <h2>Welkom op Timaflu</h2>

    <div class="tabel">

        <?php DrawTable('select id as Id, customer_id as CID from orders WHERE id = 2'); ?>

    </div>


</html>