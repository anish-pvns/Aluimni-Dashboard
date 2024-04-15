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

            <div class="col-lg-6 col-md-12 col-sm-12 services">

                <div class="row">
                    

                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <!-- Graph 2: Job Location Pie Chart -->
                        <canvas id="jobLocationPieChart" width="400" height="400"></canvas>
                    </div>
                </div>

                <div class="row">
                   

                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <!-- Graph 4: Department Pie Chart -->
                        <canvas id="departmentPieChart" width="400" height="400"></canvas>
                    </div>
                </div>

            </div>  

            <div class="col-lg-6 col-md-12 col-sm-12 text-content">
                
                <h1>Welcome to Dashboard!</h1>
                <p>Growth: See our network expand over time.</p>
                <p>Distribution: Explore alumni locations globally.</p>
                <p>Engagement: Measure community involvement.</p>
                
                <button class="btn">Explore Services</button>
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

    // Call functions to draw pie charts
    drawJobLocationPieChart();
    drawDepartmentPieChart();
</script>

                
    
            </div>
        </div>
    </section>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Dummy data for the charts
        var barChartData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [{
                label: 'Dataset 1',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
                data: [65, 59, 80, 81, 56, 55, 40]
            }]
        };
    
        var pieChartData = {
            labels: ['Red', 'Blue', 'Yellow'],
            datasets: [{
                data: [300, 50, 100],
                backgroundColor: ['red', 'blue', 'yellow']
            }]
        };
    
        var lineChartData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [{
                label: 'Dataset 1',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
                data: [65, 59, 80, 81, 56, 55, 40]
            }]
        };
    
        var doughnutChartData = {
            labels: ['Red', 'Blue', 'Yellow'],
            datasets: [{
                data: [300, 50, 100],
                backgroundColor: ['red', 'blue', 'yellow']
            }]
        };
    
        // Create charts
        window.onload = function() {
            var ctx1 = document.getElementById('barChart').getContext('2d');
            new Chart(ctx1, {
                type: 'bar',
                data: barChartData,
                options: {}
            });
    
            var ctx2 = document.getElementById('pieChart').getContext('2d');
            new Chart(ctx2, {
                type: 'pie',
                data: pieChartData,
                options: {}
            });
    
            var ctx3 = document.getElementById('lineChart').getContext('2d');
            new Chart(ctx3, {
                type: 'line',
                data: lineChartData,
                options: {}
            });
    
            var ctx4 = document.getElementById('doughnutChart').getContext('2d');
            new Chart(ctx4, {
                type: 'doughnut',
                data: doughnutChartData,
                options: {}
            });
        };
    </script>
    

   <!-- Campus Drive section -->
<section class="project-section" id="projects">
    <div class="container">
        <div class="row text">
            <div class="col-lg-6 col-md-12">
                <h3>Campus Drives</h3>
                <h1>Explore Company Placements</h1>
                <hr>
            </div>
            <div class="col-lg-6 col-md-12">
                <p>Discover the placement statistics and companies visiting our campus for recruitment</p>
            </div>
        </div>
        <div class="row project">
            <!-- Organization 1 -->
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card">
                    <img src="images/project1.jpg" class="card-img-top" alt="...">
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
            <!-- Additional project cards -->
            <!-- Organization 2 -->
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card">
                    <img src="images/project2.jpg" class="card-img-top" alt="...">
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
            <!-- Organization 3 -->
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card">
                    <img src="images/project3.jpg" class="card-img-top" alt="...">
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
            <!-- Organization 4 -->
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card">
                    <img src="images/project4.jpg" class="card-img-top" alt="...">
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