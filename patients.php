<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>DEVELOPER PROJECT FOR PT4A/STRENGTHS</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top">
    <div class="container d-flex align-items-center justify-content-between">

      <p><a href="index.php">PT4A/STRENGTHS DEVELOPER PROJECT</a></p>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto" href="index.php">Home</a></li>
          <li><a class="nav-link scrollto  active" href="patients.php">View Patients</a></li>
          <li><a class="nav-link scrollto o" href="monthlyreport.php">Monthly Report</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->
  <br/><br/><br/><br/>
<center>
<div style="width:70%;text-align:left;">
        <h1><i style="color:grey;text-transform:uppercase;">load,view and search for patient data</i></h1>
        <fieldset>
            <form style="float: right;" action="" method="post">
                <input type="text" name="search" placeholder="search patient by name" /><input type="submit" value="Search"/>
            </form><br/><br/>
            <div>
                <p>
                    <?php
                        error_reporting(E_ERROR);
                        $search=$_POST['search'];
                        //check if search has been initiated
                        if(isset($search) && $search!="")
                        {
                            echo"<h1>Search results for: $search</h1>";
                            //establish connection to database
                            include "connection.php";
                            $pquery=mysqli_query($conn,"select * from patient where name like '%$search%' limit 20 ");
                            while($pdata=mysqli_fetch_array($pquery))
                            {
                                $pid=$pdata['patient_id'];
                                $query=mysqli_query($conn,"SELECT * FROM flat_cdm_summary where patient_id='$pid' LIMIT 20");
                                $numOfRows=mysqli_num_rows($query);
                                if($numOfRows>0)
                                {
                                    //print the patients with a limit of 20
                                    echo"
                                <table style='width:100%;text-align:left;'>
                                    <tr>
                                        <th>Patient Name</th>
                                        <th>Encounter Date</th>
                                        <th>Location</th>
                                        <th>Hypertension status</th>
                                        <th>Diabetes Status</th>
                                        <th>Gender</th>
                                        <th>Age</th>
                                    </tr>
                                ";
                                    while($data=mysqli_fetch_array($query))
                                    {
                                        //get necessary variables in order to get other variables
                                        $patient_id=$data['patient_id'];
                                        $location_id=$data['location_id'];
                                        $htn_status=$data['htn_status'];
                                        $dm_status=$data['dm_status'];
                                        $patient_query=mysqli_query($conn,"select * from patient where patient_id='$patient_id'");
                                        //check if record exists
                                        $num=mysqli_num_rows($patient_query);
                                        if($num>0)
                                        {
                                            //get patient details
                                            $patient_data=mysqli_fetch_array($patient_query);
                                            $location_query=mysqli_query($conn,"select * from location where id='$location_id'");
                                            //check if location exists
                                            if((mysqli_num_rows($location_query))>0)
                                            {
                                                //get location details
                                                $location_data=mysqli_fetch_array($location_query);
                                                //get hypertension status
                                                if($htn_status==7285)
                                                {
                                                    $hyper="New";
                                                }
                                                else if($htn_status==7286)
                                                {
                                                    $hyper="Known";
                                                }
                                                else
                                                {
                                                    $hyper="Undetermined";
                                                }
                                                //check for diabetes status
                                                if($dm_status==7281)
                                                {
                                                    $diab="New";
                                                }
                                                else if($dm_status==7282)
                                                {
                                                    $diab="Known";
                                                }
                                                else
                                                {
                                                    $diab="Undetermined";
                                                }
                                                //calculate age
                                                $currentYear=date("Y");
                                                $databaseDOB=$patient_data['dob'];
                                                $dobArray=explode("-",$databaseDOB);
                                                $birthyear=$dobArray[0];
                                                if($birthyear==$currentYear)
                                                {
                                                    $age="Under 1";
                                                }
                                                else if($birthyear<$currentYear) {
                                                    $age = $currentYear - $birthyear;
                                                }
                                                else
                                                {
                                                    $age="not born";
                                                }
                                                echo"
                                            <tr>
                                                <td>".$patient_data['name']."</td>
                                                <td>".$data['encounter_datetime']."</td>
                                                <td>".$location_data['name']."</td>
                                                <td>".$hyper."</td>
                                                <td>".$diab."</td>
                                                <td>".$patient_data['gender']."</td>
                                                <td>".$age."</td>
                                            </tr>
                                            ";
                                            }
                                            else
                                            {
                                                echo"The location this resource is trying to locate has been deleted or there is a server problem";
                                            }
                                        }
                                        else
                                        {
                                            echo"This patient has been deleted or there is a server error.";
                                        }
                                    }
                                    echo"
                                </table>
                                ";
                                }
                                else
                                {
                                    echo"There are no patients in the database. Please add to view";
                                }
                            }


                        }
                        else
                        {
                            //display all data
                            include"connection.php";
                            $query=mysqli_query($conn,"SELECT * FROM flat_cdm_summary LIMIT 20");
                            $numOfRows=mysqli_num_rows($query);
                            if($numOfRows>0)
                            {
                                //print the patients with a limit of 20
                                echo"
                                <table style='width:100%;text-align:left;'>
                                    <tr>
                                        <th>Patient Name</th>
                                        <th>Encounter Date</th>
                                        <th>Location</th>
                                        <th>Hypertension status</th>
                                        <th>Diabetes Status</th>
                                        <th>Gender</th>
                                        <th>Age</th>
                                    </tr>
                                ";
                                while($data=mysqli_fetch_array($query))
                                {
                                    //get necessary variables in order to get other variables
                                    $patient_id=$data['patient_id'];
                                    $location_id=$data['location_id'];
                                    $htn_status=$data['htn_status'];
                                    $dm_status=$data['dm_status'];
                                    $patient_query=mysqli_query($conn,"select * from patient where patient_id='$patient_id'");
                                    //check if record exists
                                    $num=mysqli_num_rows($patient_query);
                                    if($num>0)
                                    {
                                        //get patient details
                                        $patient_data=mysqli_fetch_array($patient_query);
                                        $location_query=mysqli_query($conn,"select * from location where id='$location_id'");
                                        //check if location exists
                                        if((mysqli_num_rows($location_query))>0)
                                        {
                                            //get location details
                                            $location_data=mysqli_fetch_array($location_query);
                                            //get hypertension status
                                            if($htn_status==7285)
                                            {
                                                $hyper="New";
                                            }
                                            else if($htn_status==7286)
                                            {
                                                $hyper="Known";
                                            }
                                            else
                                            {
                                                $hyper="Undetermined";
                                            }
                                            //check for diabetes status
                                            if($dm_status==7281)
                                            {
                                                $diab="New";
                                            }
                                            else if($dm_status==7282)
                                            {
                                                $diab="Known";
                                            }
                                            else
                                            {
                                                $diab="Undetermined";
                                            }
                                            //calculate age
                                            $currentYear=date("Y");
                                            $databaseDOB=$patient_data['dob'];
                                            $dobArray=explode("-",$databaseDOB);
                                            $birthyear=$dobArray[0];
                                            if($birthyear==$currentYear)
                                            {
                                                $age="Under 1";
                                            }
                                            else if($birthyear<$currentYear) {
                                                $age = $currentYear - $birthyear;
                                            }
                                            else
                                            {
                                                $age="not born";
                                            }
                                            echo"
                                            <tr>
                                                <td>".$patient_data['name']."</td>
                                                <td>".$data['encounter_datetime']."</td>
                                                <td>".$location_data['name']."</td>
                                                <td>".$hyper."</td>
                                                <td>".$diab."</td>
                                                <td>".$patient_data['gender']."</td>
                                                <td>".$age."</td>
                                            </tr>
                                            ";
                                        }
                                        else
                                        {
                                            echo"The location this resource is trying to locate has been deleted or there is a server problem";
                                        }
                                    }
                                    else
                                    {
                                        echo"This patient has been deleted or there is a server error.";
                                    }
                                }
                                echo"
                                </table>
                                ";
                            }
                            else
                            {
                                echo"There are no patients in the database. Please add to view";
                            }
                        }
                    ?>
                </p>
            </div>
        </fieldset>
</div>
</center>
  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="container d-md-flex py-4">
	  <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
      <div class="me-md-auto text-center text-md-start">
        <div class="copyright" style="float:left;">
          &copy;<strong><span>PT4A/STRENGTHS</span></strong>. All Rights Reserved
        </div>
      </div>
    </div>
  </footer><!-- End Footer -->

  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/purecounter/purecounter.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>