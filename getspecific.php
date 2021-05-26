
<html>
<body>
<center>
    <div style="width:70%;text-align:left;">
        <h1><a style="text-decoration: underline;color:black;" title="go back to home page" href="index.php"><b>HOME</b></a> ==> <i style="color:grey;">Location based specific disease analysis.</i></h1>
        <fieldset>
            <div>
                <p>
                    <?php
                    include "connection.php";
                    $diseaseCode=$_GET['code'];
                    $diseaseName=$_GET['diseaseName'];
                    $location_id=$_GET['locationid'];
                    if(isset($diseaseCode) && isset($diseaseCode) && isset($diseaseName))
                    {
                        $locquery=mysqli_query($conn,"select * from location where id='$location_id'");
                        $locdata=mysqli_fetch_array($locquery);
                        $locname=$locdata['name'];
                        echo"<legend style='text-transform: uppercase;'><b>$locname $diseaseName analysis report</b></legend>";
                        $query=mysqli_query($conn,"SELECT * FROM flat_cdm_summary where location_id='$location_id' && (htn_status='$diseaseCode' || dm_status='$diseaseCode') LIMIT 20");
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
                    else
                    {
                        header("location: monthlyreport.php");
                    }

                    ?>
                </p>
            </div>
        </fieldset>
    </div>
</center>
</body>
</html>

