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
        <title><?php echo $title;?></title>
        <?php
        $_SESSION['devmsg'] = "0";
        ?>
    <title><?php echo $title;?></title>
</head>
<body>
    <form action="/search/insert.php" method="post" enctype="multipart/form-data">
        <fieldset>
        <legend><h1>Search Database Insert</h1></legend>
        <label for="itemname">Item Name</label><br>
        <input type="text" name="itemname" id="ins" placeholder="Name"><br>
        <label for="itemtype">Type of Product</label><br>
        <input type="text" name="itemtype" id="ins" placeholder="Type of Product (E.g: rice, dal, pulses, biscuit,etc)"><br>
        <label for="itemsize">Size of Product</label><br>
        <input type="text" name="itemsize" id="ins" placeholder="Size of Product (E.g: 500gm, 1kg, 2kg, 5kg, 10kg, 20kg, 25kg,30kg, 50kg)"><br>
        <label for="itemquality">Quality of Product</label><br>
        <textarea type="text" cols="75" rows="10" name="itemquality" id="ins" placeholder="Quality of Product
Example:
1. Hygienically packed Rahar dal.
2. Dals are sorted to deliver consistent quality of grains through the year
3. No artificial flavours. No preservatives
4. Dals are a rich source of protein"></textarea><br>
        <label for="price">Price</label><br>
        <input type="number" name="itemprice" id="price" placeholder="Price of Product"><br>
        <label for="image">image</label><br>
        <input type="file" name="file" id="file"><br>
        <button type="submit" name="sub">Insert</button>
        </fieldset>
    </form>
    <?php
            if (isset($_POST['sub'])) {
                $user = "Admin";
                $ins = mysqli_real_escape_string($conn, $_POST['itemname']);
                $type = mysqli_real_escape_string($conn, $_POST['itemtype']);
                $size = mysqli_real_escape_string($conn, $_POST['itemsize']);
                $quality = mysqli_real_escape_string($conn, $_POST['itemquality']);
                $price = mysqli_real_escape_string($conn, $_POST['itemprice']);
                $inssql= "SELECT * FROM searchitem WHERE ItemName = '$ins'";
                $query = mysqli_query($conn, $inssql);
                $ins_rows = mysqli_num_rows($query);
    
                if($ins_rows == 1){
                    echo "Search query already Exist";
                } else{
                    $file = $_FILES['file'];
                    $fileName = $file['name'];
                    $fileType = $file['type'];
                    $fileTmpName = $file['tmp_name'];
                    $fileError = $file['error'];
                    $fileSize = $file['size'];
                    $fileExt = explode('.',$fileName);
                    $fileActualExt = strtolower(end($fileExt));
    
                    $allowed = array('jpeg','jpg','png','gif');
                    if (in_array($fileActualExt, $allowed)) {
                        if($fileError === 0){
                            if ($fileSize < 50000000) {
                                $fileNameNew = uniqid('',true).'.'.$fileActualExt;
                                $dir = "../images/".$fileNameNew;
    
                                $insert_sql = "INSERT INTO searchitem (Username,ItemName, ItemType, ItemSize, ItemQuality, ItemPrice, ItemPicture) VALUES ('$user','$ins','$type','$size','$quality','$price','$dir')";
                                $iquery = mysqli_query($conn, $insert_sql);

                                if ($iquery) {
                                    move_uploaded_file($fileTmpName, $dir);
                                    echo "Insert and Upload success";
                                } else {
                                    echo "Insert and Upload unsuccess:<br>", mysqli_error($conn);
                                }
                            } else {
                                echo "Image size has too big!";
                            }
                        } else {
                            echo "The was an error uploading your image";
                        }
                    } else {
                        echo "Please check the file type";
                    }
    
                }
        
            }   
    ?>
</body>
</html>