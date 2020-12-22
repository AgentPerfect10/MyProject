<?php
    session_start();
    include '../store/db/config.inc.php';
    include '../store/title.php';
    include 'search.css.php';
    if($conn){
        echo "";
    } else {
        die(mysqli_error($conn));
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$title?></title>
</head>
<body>
    <form action="<?=htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST">
        <input type="hidden" name="msg" value="0">
        <input type="text" name="q" id="search" class="input search" required>
        <button type="submit" name="sub">Search...</button>
    </form>
    <div>
        <?php
        if (isset($_POST['q'])) {
            $_SESSION['devmsg'] = '';
            $search = mysqli_real_escape_string($conn, $_POST['q']);
            $searchq = "SELECT * FROM searchitem WHERE ItemName LIKE '%$search%'";
            $query = mysqli_query($conn, $searchq);
            $qrows = mysqli_num_rows($query);
        
                if ($qrows == 0) {
                    echo"No such result matching!";
                } else {
                    while ($row = mysqli_fetch_array($query)) {
                        $image = $row['ItemPicture'];
                        $name = $row['ItemName'];
                        $price = $row['ItemPrice'];
                        $size = $row['ItemSize'];

    
                        $output = '<div class="search-images"><img src="'.$image.'" alt="'.$image.' width="100px" height="100px"><div class="searchitem">Name: <span class="search-name">'.$name.'</span><br>Weight: <span class="search-size">'.$size.'</span><br>Price: <span class="search-price">'.$price.'</span></div></div>';
                        echo $output;
                    }
                }
            }   
        ?>
    </div>
</body>
</html>