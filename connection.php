<?php
error_reporting(E_ERROR);
$conn=mysqli_connect("localhost","root","","testDB");
if($conn)
{
    //proceed
}
else{
    echo"The server configurations are not proper, please check again and continue";
}
