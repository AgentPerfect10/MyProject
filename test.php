<html>
<head>
</head>
<body>
<div id="livesearch" style="max-width: 25%; margin: 0;"></div>
</body>
</html>
<?php 
    include "../store/db/config.inc.php"; 
    if(isset($_GET['q'])){
    $q = $_GET['q'];
    $sql = "SELECT ItemName, ItemSize, ItemPrice FROM searchitem WHERE ItemName LIKE '%$q%'";
    $result = mysqli_query($conn, $sql);
    echo "<form><table style='margin: 0;'>
    <tr>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    </tr>";
    while($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<td><input type='checkbox' id='check' value='".$row['ItemName']."'></td>";
        echo "<td><div id='click'>" . $row['ItemName'] . "</div></td>";
        echo "</tr>";
      }
      echo "</table></form>";
    }
    mysqli_close($conn);
?>
