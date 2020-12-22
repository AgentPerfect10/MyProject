<?php 
    session_start();
    include '../store/db/config.inc.php';
    include '../store/title.php';
    include 'search.css.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$title?></title>
    <style>
    </style>
</head>
<body>
    <form action="<?=htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
        <input type="text" name="q" id="addinput" placeholder="Name of the product" autocomplete="off" required>
        <div id="autolive" class="autolive"></div>
        <script>
            $("addinput").onchange = function () {
                function autocomplete(str){
                    if(str == ""){
                        $("#autolive").txt("");
                        return;
                    } 
                    xhr.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("txtHint").innerHTML = this.responseText;
                        }
                    };
                    xhr.open("POST","calc.php?q="+str, TRUE);
                    xhr.send();
                }
            }
        </script>
        <?php
            $sql = "SELECT ItemName FROM searchitem WHERE searchitem = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt,"s", $_POST['q']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_rsult($searchpp);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);

            echo $searchpp;
        ?>
        <input type="text" name="s" id="addinput" placeholder="Size of the product" autocomplete="off" required>
        <select name="unit" id="unit">
            <option value="kg">Kilogram</option>
            <option value="g">Gram</option>
            <option value="l">Litre</option>
            <option value="ml">Mililitre</option>
        </select>
        <button type="submit" name="sub">+</button>
    </form>
    <?php 
        if (isset($_POST['q'])) {
            $_SESSION['devmsg'] = '';
            $search = mysqli_real_escape_string($conn, $_POST['q']);
            $size = $_POST['s'];
            $unit = $_POST['unit'];
            $sizef = $size; 
            $searchq = "SELECT * FROM searchitem WHERE ItemName LIKE '%$search%' AND ItemSize ='$sizef'";
            $query = mysqli_query($conn, $searchq);
            $qrows = mysqli_num_rows($query);
            
            if($size == 0){
                echo "This matter doesn't exist in our World. Please mention the size.";
            } else { 
                if ($qrows == 0) {
                    echo"No such result matching!";
                } else {
    ?>
    <table border='1px'>
        <tr>
            <th>Item ID</th>
            <th>Item Name</th>
            <th>Size</th>
            <th>Rate</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
    <?php
        while ($row = mysqli_fetch_array($query)) { 
            $dbname = $row['ItemName'];
            $dbrate = $row['ItemPrice'];
            $id = $row['Item ID'];
            # $rate = Rate of Item per 1kg or 1ltr in Grocery Items
            $dbsize = $row['ItemSize'];
            
            switch ($unit){
                case "kg":
                    $price = $dbrate * $size;
                    break;
                case "l":
                    $price = $dbrate * $size;
                    break;
                case "ml":
                    $price = round($dbrate * ($size/1000));
                    break;
                case 'g':
                    $price = $dbrate * $size;
                    break;
            }
            
             
            
    ?>
        <tr>
            <td><?=$id?></td>
            <td><?=$dbname?></td>  
            <?php
            switch ($dbsize) {
                case "1L":
                    $unit = "l";
                    
                    break;
                case  "500ML":
                    $unit = "ML";
                    
                    break;
                case "1kg":
                    $unit = "kg";
                    
                    break;
                case "1.5L":
                    $unit = "l";
                    
                    break;
                case "2.25L":
                    $unit = "l";
                   
                    break;
            }
            ?> 
            <td><?=$size, $unit?></td>
            <td><?=$dbrate?></td>
            <td><?=$price?></td>
            <td><?="<div class='tooltip' id='add'>+<span class='tooltiptext'>Add item</span></div>"?></td>
        </tr>

    <?php
    
                }
            }
        }
        }
    ?>
     </table>
</body>
</html>
<?php mysqli_close($conn);?>