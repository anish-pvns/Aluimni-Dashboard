<?php
session_start();

include("connection.php");

if (!isset($_SESSION['username'])) {
    header("location:login.php");
}




?>
    
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./css/style2.css?php  echo time();?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</head>

<body>

    <!-- navbar section   -->

    <header class="navbar-section">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="signup.php"><i class="bi bi-book"></i> Centurion Nexus</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        
                        <li class="nav-item">
                            <div class="dropdown">
                                <a class='nav-link dropdown-toggle' href='edit.php?id=$res_id' id='dropdownMenuLink'
                                    data-bs-toggle='dropdown' aria-expanded='false'>
                                    <i class='bi bi-person'></i>
                                </a>
                                

                                <ul class="dropdown-menu mt-2 mr-0" aria-labelledby="dropdownMenuLink">

                                    <li>
                                        <?php

                                        $id = $_SESSION['id'];
                                        $query = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");

                                        while ($result = mysqli_fetch_assoc($query)) {
                                            $res_username = $result['username'];
                                            $res_email = $result['email'];
                                            $res_id = $result['id'];
                                        }


                                        echo "<a class='dropdown-item' href='edit.php?id=$res_id'>Change Profile</a>";


                                        ?>

                                    </li>
                                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                                </ul>
                            </div>

                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>


    <div class="name">
        <center>Welcome
            <?php
            // echo $_SESSION['valid'];
            
            echo $_SESSION['username'];

            ?>
            !
        </center>
    </div>

   

    <?php
//session_start();
include "connection.php";
 
// Fetch data from the database for total profiles
$sqlTotalProfiles = "SELECT COUNT(*) as total_profiles FROM users";
$resultTotalProfiles = mysqli_query($conn, $sqlTotalProfiles);
$totalProfiles = mysqli_fetch_assoc($resultTotalProfiles)['total_profiles'];


// Fetch data from the database for demographic account number
$demographicData = [];
$sqlDemographic = "SELECT job_location, COUNT(*) as count FROM users GROUP BY job_location";
$resultDemographic = mysqli_query($conn, $sqlDemographic);
while ($row = mysqli_fetch_assoc($resultDemographic)) {
    $demographicData[$row['job_location']] = $row['count'];
}

// Fetch data from the database for job location pie chart
$jobLocationData = [];
$sqlJobLocation = "SELECT job_location, COUNT(*) as count FROM users GROUP BY job_location";
$resultJobLocation = mysqli_query($conn, $sqlJobLocation);
while ($row = mysqli_fetch_assoc($resultJobLocation)) {
    $jobLocationData[$row['job_location']] = $row['count'];
}

// Fetch data from the database for department pie chart
$departmentData = [];
$sqlDepartment = "SELECT branch, COUNT(*) as count FROM users GROUP BY branch";
$resultDepartment = mysqli_query($conn, $sqlDepartment);
while ($row = mysqli_fetch_assoc($resultDepartment)) {
    $departmentData[$row['branch']] = $row['count'];
}

?>


<!-- services section  -->
<section class="services-section" id="services">
    <div class="container">
        <div class="row">
            <!-- Graphs -->
            <div class="col-lg-12 col-md-12 col-sm-12 services">
                <div class="row flex items-baseline">
                    <!-- Graph 1: Job Location Pie Chart -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <h3 class="graph-title-center text-center">Job Location Distribution</h3>
                        <div style="border: 2px solid black; padding: 10px; border-radius: 50px; margin-bottom: 20px;">
                            <canvas id="jobLocationPieChart" width="400" height="400"></canvas>
                        </div>
                    </div>
                    <!-- Graph 3: histogram-->
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <h3 class="graph-title text-center">Students Placed </h3>
                        <div style="border: 2px solid black; padding: 10px; border-radius: 50px;">
                        <canvas id="placedVsTotalHistogram" width="100" height="100" ></canvas>
                        </div>
                    </div>
                    <!-- Graph 2: Department Pie Chart -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <h3 class="graph-title text-center">Department Distribution</h3>
                        <div style="border: 2px solid black; padding: 10px; border-radius: 50px; margin-bottom: 20px;">
                            <canvas id="departmentPieChart" width="400" height="400"></canvas>
                        </div>
                    </div>
                    <!-- Graph 4: bar - line chart-->
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <h3 class="graph-title text-center">Years vs Total Students Placed</h3>
                        <div style="border: 2px solid black; padding: 10px; border-radius: 50px; margin-top: 20px;">
                            <canvas id="yearsVsTotalStudentsPlaced" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>  
        </div>
    </div>
</section>




<script>
    
    // JavaScript code to initialize and draw the pie charts
    var jobLocationData = <?php echo json_encode($jobLocationData); ?>;
    var departmentData = <?php echo json_encode($departmentData); ?>;

    // Function to draw job location pie chart
    function drawJobLocationPieChart() {
        var jobLocationCanvas = document.getElementById("jobLocationPieChart");
        var jobLocationCtx = jobLocationCanvas.getContext("2d");
        var jobLocationLabels = Object.keys(jobLocationData);
        var jobLocationValues = Object.values(jobLocationData);

        new Chart(jobLocationCtx, {
            type: 'pie',
            data: {
                labels: jobLocationLabels,
                datasets: [{
                    data: jobLocationValues,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(255, 159, 64, 0.5)'
                    ]
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Job Location Distribution'
                }
            }
        });
    }

    // Function to draw department pie chart
    function drawDepartmentPieChart() {
        var departmentCanvas = document.getElementById("departmentPieChart");
        var departmentCtx = departmentCanvas.getContext("2d");
        var departmentLabels = Object.keys(departmentData);
        var departmentValues = Object.values(departmentData);

        new Chart(departmentCtx, {
            type: 'pie',
            data: {
                labels: departmentLabels,
                datasets: [{
                    data: departmentValues,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(255, 159, 64, 0.5)'
                    ]
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Department Distribution'
                }
            }
        });
    }

    
    // JavaScript code to initialize and draw the pie charts
    var jobLocationData = <?php echo json_encode($jobLocationData); ?>;
    var departmentData = <?php echo json_encode($departmentData); ?>;
    
    // Function to fetch placed student counts from department distribution data
    function getPlacedStudents() {
        var placedStudents = [];
        Object.values(departmentData).forEach(function(count) {
            placedStudents.push(count);
        });
        return placedStudents;
    }

    // Function to draw histogram graph
    function drawHistogram() {
        var totalStudents = [100, 150, 200]; // Static total student numbers
        var placedStudents = getPlacedStudents();

        var histogramCanvas = document.getElementById("placedVsTotalHistogram");
        var histogramCtx = histogramCanvas.getContext("2d");

        new Chart(histogramCtx, {
            type: 'bar',
            data: {
                labels: ['CSE', 'Mech', 'ECE'],
                datasets: [
                    {
                        label: 'Total Students',
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        data: totalStudents
                    },
                    {
                        label: 'Placed Students',
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        data: placedStudents
                    }
                ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    }
    
    // Function to fetch years and total students placed data from the backend
    function fetchYearsVsTotalStudentsPlacedData() {
     
            return {
                years: ['2020', '2021', '2022','2023','2024'],
                totalStudentsPlaced: [70, 65, 100,110,120,100] 
            };
        }

    // Function to draw years vs total students placed graph
    function drawYearsVsTotalStudentsPlaced() {
    var data = fetchYearsVsTotalStudentsPlacedData();

    var yearsVsTotalStudentsPlacedCanvas = document.getElementById("yearsVsTotalStudentsPlaced");
    var yearsVsTotalStudentsPlacedCtx = yearsVsTotalStudentsPlacedCanvas.getContext("2d");

    // Define an array of colors for each bar
    var barColors = ['rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(255, 206, 86, 0.5)', 'rgba(75, 192, 192, 0.5)', 'rgba(153, 102, 255, 0.5)'];

    new Chart(yearsVsTotalStudentsPlacedCtx, {
        type: 'bar',
        data: {
            labels: data.years,
            datasets: [{
                label: 'Total Students Placed',
                data: data.totalStudentsPlaced,
                backgroundColor: barColors,
            }]
        },
        options: {
            legend: {
                display: true,
                position: 'left'
            },
            scales: {
                xAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Year'
                    }
                }],
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        
                    },
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
}




    // Call function to draw visualizations
    drawHistogram();
    drawJobLocationPieChart();
    drawDepartmentPieChart();     
    drawYearsVsTotalStudentsPlaced();                   
</script>


   <!-- Campus Drive section -->
<section class="project-section" id="projects">
    <div class="container">
        <div class="row text">
            <div class="col-lg-6 col-md-12">
                <h3>Campus Drives</h3>
                <h1>Explore Campus Placements Companies</h1>
                <hr>
            </div>
            <div class="col-lg-6 col-md-12">
                <h3>Up Coming Campus Drives</h3>
                <h1>Explore Upcoming Companies</h1>
                <hr>
            </div>
        </div>
        <div class="row project grid grid-cols-12">


<!--  Carousel  -->
<div class="col-lg-6 col-sm-12 ">
<div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
    <div class="col-lg-7  col-md-6 col-sm-12 mx-auto" style="height:500px;">
                <div class="card">
                    <img src="images/rupeek.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <div class="text">
                            <h4 class="card-title">Rupeek Fintech</h4>
                            <br>
                            <h3 class="card-heading">Applied : 30</h3>
                            <h3 class="card-heading">Placed  : 11 </h3>
                        
                            <!-- Canvas element for chart -->
                            <canvas id="chart1" width="200" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <div class="carousel-item">
    <div class="col-lg-7  col-md-6 col-sm-12 mx-auto" style="height:500px;">
                <div class="card">
                    <img src="images/ediglobe.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <div class="text">
                            <h4 class="card-title">EdiGlobe</h4>
                            <br>
                            <h3 class="card-heading">Applied : 30</h3>
                            <h3 class="card-heading">Placed  : 18 </h3>
                            <!-- Canvas element for chart -->
                            <canvas id="chart2" width="200" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <div class="carousel-item">
    <div class="col-lg-7  col-md-6 col-sm-12 mx-auto" style="height:500px;">
                <div class="card">
                    <img src="images/cognizant.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <div class="text">
                            <h4 class="card-title">Cognizant</h4>
                            <br>
                            <h3 class="card-heading">Applied : 150</h3>
                            <h3 class="card-heading">Placed  :  20</h3>
                            <!-- Canvas element for chart -->
                            <canvas id="chart3" width="200" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <div class="carousel-item">
    <div class="col-lg-7  col-md-6 col-sm-12 mx-auto" style="height:500px;">
                <div class="card">
                    <img src="images/tcs.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <div class="text">
                            <h4 class="card-title">TCS</h4>
                            <br>
                            <h3 class="card-heading">Applied : 137</h3>
                            <h3 class="card-heading">Placed  : 11 </h3>
                            <!-- Canvas element for chart -->
                            <canvas id="chart4" width="200" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
</div>
<div class="col-lg-6 col-sm-12">


<!-- right side carousel-->
<div id="carouselExampleControls1" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
    <div class="col-lg-7  col-md-6 col-sm-12 mx-auto" style="height:500px;">
                <div class="card">
                    <img src="images/rupeek.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <div class="text">
                            <h4 class="card-title">Rupeek Fintech</h4>
                            <h3 class="card-heading">Bangalore</h3>
                            <h3 class="card-heading"> Date:19th April 2023 </h3>
                            <button class="GFG" onclick="window.location.href='https://rupeek.com/?city=Bangalore';" > Learn More  </button>                        
                            <!-- Canvas element for chart -->
                            <canvas id="chart1" width="200" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <div class="carousel-item">
    <div class="col-lg-7  col-md-6 col-sm-12 mx-auto" style="height:500px;">
                <div class="card">
                    <img src="images/ediglobe.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                    <div class="text">
                            <h4 class="card-title">EdiGlobe</h4>
                            <h3 class="card-heading">Visakapatanam</h3>
                            <h3 class="card-heading"> Date:20th April 2023 </h3>
                            <button class="GFG" onclick="window.location.href='https://ediglobe.com/';" > Learn More  </button>                        
                            <!-- Canvas element for chart -->
                            <canvas id="chart1" width="200" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <div class="carousel-item">
    <div class="col-lg-7  col-md-6 col-sm-12 mx-auto" style="height:500px;">
                <div class="card">
                    <img src="images/cognizant.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                    <div class="text">
                            <h4 class="card-title">Cognizant</h4>
                            <h3 class="card-heading">Hyderabad</h3>
                            <h3 class="card-heading"> Date:29th April 2023 </h3>
                            <button class="GFG" onclick="window.location.href='https://www.cognizant.com/in/en';" > Learn More  </button>                        
                            <!-- Canvas element for chart -->
                            <canvas id="chart1" width="200" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <div class="carousel-item">
    <div class="col-lg-7  col-md-6 col-sm-12 mx-auto" style="height:500px;">
                <div class="card">
                    <img src="images/tcs.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <div class="text">
                        <h4 class="card-title">TCS</h4>
                            <h3 class="card-heading">Chennai</h3>
                            <h3 class="card-heading"> Date:28th April 2023 </h3>
                            <button class="GFG" onclick="window.location.href='https://www.tcs.com/';" > Learn More  </button>                        
                            <!-- Canvas element for chart -->
                            <canvas id="chart4" width="200" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls1" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls1" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

</div>
        </div>
    </div>
</section>


    <!-- contact section  -->

    <section class="contact-section" id="contact">
        <div class="container">

            <div class="row gy-4">

                <h1>Contact Us</h1>
                <div class="col-lg-6">

                    <div class="row gy-4">
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-geo-alt"></i>
                                <h3>Address</h3>
                                <p>Centurion Universtiy,<br>Andhra Pradesh, 535003</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-telephone"></i>
                                <h3>Call Us</h3>
                                <p>+91 9032210686<br>+91 9642511151</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-envelope"></i>
                                <h3>Email Us</h3>
                                <p>cutmap@CenturionNexus.com<br>support@CenturionNexus.com</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-clock"></i>
                                <h3>Open Hours</h3>
                                <p>Monday - Friday<br>9:00AM - 05:00PM</p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg-6 form">
                    <form action="contact.php" method="POST" class="php-email-form">
                        <div class="row gy-4">

                            <div class="col-md-6">
                                <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                            </div>

                            <div class="col-md-6 ">
                                <input type="email" class="form-control" name="email" placeholder="Your Email" required>
                            </div>

                            <div class="col-md-12">
                                <input type="text" class="form-control" name="subject" placeholder="Subject" required>
                            </div>

                            <div class="col-md-12">
                                <textarea class="form-control" name="message" rows="5" placeholder="Message"
                                    required></textarea>
                            </div>

                            <div class="col-md-12 text-center">
                                <button type="submit" name="submit">Send Message</button>
                            </div>

                        </div>
                    </form>

                </div>

            </div>

        </div>
    </section>

    <!-- footer section  -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12 col-sm-12">
                <p class="logo"><i class="bi bi-laptop"></i> <br>  Centurion Nexus</p>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12">
                <ul class="d-flex">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Services</a></li>
                    <li><a href="#">Stories</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-12 col-sm-12">
                <p>&copy;2024_CenturionNexus</p>
            </div>
            <div class="col-lg-1 col-md-12 col-sm-12">
                <!-- back to top  -->
                <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
                        class="bi bi-arrow-up-short"></i></a>
            </div>
        </div>
        <!-- Social Media Icons -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <ul class="social-media-icons d-flex justify-content-center">
                    <li><a href="https://www.facebook.com/share/JcpyYGNe2u1gGEdy/?mibextid=WC7FNe"><i class="bi bi-facebook"></i></a></li>
                    <li><a href="https://www.linkedin.com/in/centurion-university-andhra-pradesh-5050062a6/"><i class="bi bi-linkedin"></i></a></li>
                    <li><a href="https://www.instagram.com/centurion_university_ap/"><i class="bi bi-instagram"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>
</body>

</html>