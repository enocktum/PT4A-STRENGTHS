<html>
<body>
<center>
    <div style="width:70%;text-align:left;">
        <h1><a style="text-decoration: underline;color:black;" title="go back to home page" href="index.php"><b>HOME</b></a> ==> <i style="color:grey;">CDM Monthly Report For Diabetic and Hypertensive Patients</i></h1>
        <fieldset>
            <legend>PATIENT SEARCH</legend>
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
                                        if($data['htn_status']=="7285")
                                        {
                                            $newHypertensive++;
                                        }
                                        else if($data['htn_status']=="7286")
                                        {
                                            $knownHypertensive++;
                                        }
                                        else if($data['dm_status']=="7281")
                                        {
                                            $newDiabetic++;
                                        }
                                        else if($data['dm_status']=="7282")
                                        {
                                            $knownDiabetic++;
                                        }
                                        else if(($data['htn_status']=="7285" && $data['dm_status']=="7281") || ($data['htn_status']=="7286" && $data['dm_status']=="7282"))
                                        {
                                            $hypertensiveAndDiabetic++;
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
                                    <td style='font-size: 2em;'>".$hypertensiveAndDiabetic."</td>
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
</body>
</html>
<?php
