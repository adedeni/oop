<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    require_once 'core/init.php';
    echo "for OOP!";
    echo "<br>"; 
    var_dump(config::get('mysql/host')); ;
    echo "<br>";
    ?>
</body>
</html>