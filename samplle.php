<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST" action="fg.php"><input type="date" name="birthdate"><input type="date" name="birthdate2"><button type="submit">clcik</buton></form>
    <?php
        
        if (isset($_POST['birthdate'])) {
           /* $timestamp = strtotime($_POST['birthdate']); 
            $date=date('d',$timestamp);
            echo "ghh".$date;*/
            $start = strtotime($_POST['birthdate']);
            $end = strtotime($_POST['birthdate2']);

             $days_between = ceil(abs($end - $start) / 86400);
             echo "ghh".$days_between;
        }
        
       
    ?>
</body>
</html>