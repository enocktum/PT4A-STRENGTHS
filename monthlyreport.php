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
          <li><a class="nav-link scrollto" href="patients.php">View Patients</a></li>
          <li><a class="nav-link scrollto o active" href="monthlyreport.php">Monthly Report</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->
<br/><br/><br/><br/>
<center>
    <div style="width:90%;text-align:left;">
        <h1><i style="color:grey;">Monthly Report For Diabetic and Hypertensive Patients</i></h1><br/><br/>
        <fieldset>
            <form style="float: right;" action="" method="post">
                Select year and month to get report: <br/>
                Year:<select name="year">
                    <?php
                    $currentyear=date("Y");
                    for($i=($currentyear);$i>=($currentyear-20);$i--)
                    {
                        echo"<option>".$i."</option>";
                    }
                    ?>
                </select>
                Month:<select name="search">
                    <?php
                    for($i=1;$i<=12;$i++)
                    {
                        echo"<option>".$i."</option>";
                    }
                    ?>
                </select><input type="submit" value="Search"/>
            </form><br/><br/><br/>
            <p>
                <?php
                error_reporting(E_ERROR);
                $search=$_POST['search'];
                $year=$_POST['year'];
                //check if search has been initiated
                if(isset($search) && isset($year))
                {
                    echo"<h1>Monthly report for the Year: $year and the month: $search</h1>";
                    //establish connection to database
                    include "connection.php";
                    $locationQuery=mysqli_query($conn,"select * from location");
                    $ngapiLocation=mysqli_num_rows($locationQuery);
                    if($ngapiLocation>0)
                    {
                        echo"<table style='width:100%;text-align:center;' border='1'>
                           <tr>
                               <th>Month</th>
                               <th>Location</th>
                               <th>New Hypertensive</th>
                               <th>Known Hypertensive</th>
                               <th>New Diabetic</th>
                               <th>Known Diabetic</th>
                               <th>Hypertensive and Diabetic</th>
                           </tr>
                        ";
                        while($locationData=mysqli_fetch_array($locationQuery))
                        {
                            $locationid=$locationData['id'];
                            $query=mysqli_query($conn,"SELECT * FROM flat_cdm_summary where location_id='$locationid' limit 20");
                            $numOfRows=mysqli_num_rows($query);
                            if($numOfRows>0)
                            {
                                $newHypertensive=0;
                                $knownHypertensive=0;
                                $newDiabetic=0;
                                $knownDiabetic=0;
                                $hypertensiveAndDiabetic=0;
                                while($data=mysqli_fetch_array($query))
                                {
                                    $date=$data['encounter_datetime'];
                                    $datearray=explode("-",$date);
                                    $month=$datearray[1];
                                    $yeardb=$datearray[0];
                                    if($month==$search && $year=$yeardb)
                                    {
										$htnstatus=$data['htn_status'];
										$dmstatus=$data['dm_status'];
										if(($htnstatus=="7285" && $dmstatus=="7281") || ($htnstatus=="7286" && $dmstatus=="7282")) 
										{
											$hypertensiveAndDiabetic++;
										}
										if($htnstatus=="7285")
                                        {
                                            $newHypertensive++;
                                        }
										if($htnstatus=="7286")
                                        {
                                            $knownHypertensive++;
                                        }
										if($dmstatus=="7281")
                                        {
                                            $newDiabetic++;
                                        }
										if($dmstatus=="7282")
                                        {
                                            $knownDiabetic++;
                                        }
                                    }
                                }
                                $urefu=strlen($search);
                                if($urefu==1)
                                {
                                    $search="0".$search;
                                }
                                echo"
                                <tr>
                                    <td>$year-$search</td>
                                    <td>".$locationData['name']."</td>
                                    <td><a style='text-decoration: none;font-size: 2em;color:black;' href='getspecific.php?locationid=$locationid && code=7285 && diseaseName=New Hypertension'>".$newHypertensive."</a></td>
                                    <td><a style='text-decoration: none;font-size: 2em;color:black;' href='getspecific.php?locationid=$locationid && code=7286 && diseaseName=Known Hypertension'>".$knownHypertensive."</a></td>
                                    <td><a style='text-decoration: none;font-size: 2em;color:black;' href='getspecific.php?locationid=$locationid && code=7281 && diseaseName=New Diabetic'>".$newDiabetic."</a></td>
                                    <td><a style='text-decoration: none;font-size: 2em;color:black;' href='getspecific.php?locationid=$locationid && code=7282 && diseaseName=Known Diabetic'>".$knownDiabetic."</a></td>
                                    <td><a style='text-decoration: none;font-size: 2em;color:black;' href='getspecific.php?locationid=$locationid && code=either && diseaseName=Hypertensive and Diabetic'>".$hypertensiveAndDiabetic."</a></td>
                                </tr>";
                            }
                            else
                            {
                                echo"<tr colspan='7'>There are no patients in the database for ".$locationData['name']." location</tr>";
                            }
                        }
                        echo"</table>";

                    }
                    else
                    {
                        echo"There is no location information in the database";
                    }

                }
                else
                {
                    //display monthly report for
                    echo"<p>Kindly select a month to display the report and press enter on your keyboard or search button above</p>";
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