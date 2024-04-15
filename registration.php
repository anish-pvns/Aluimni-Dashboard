<?php
session_start();
include "connection.php";

if (isset($_POST['register'])) {

    $name = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $cpass = $_POST['cpass'];
    $phone = $_POST['phone'];
    $location = $_POST['location']; 
    $job_location = $_POST['job_location']; 
    $gender = $_POST['gender']; 
    $current_company = $_POST['current_company'];
    $position = $_POST['position']; 
    $domain = $_POST['domain']; 
    $branch = $_POST['branch']; 
    $graduation_year = $_POST['graduation_year']; 

    $check = "SELECT * FROM users WHERE email='{$email}'";
    $res = mysqli_query($conn, $check);
    $passwd = password_hash($pass, PASSWORD_DEFAULT);
    $key = bin2hex(random_bytes(12));

    if (mysqli_num_rows($res) > 0) {
        echo "<div class='message'><p>This email is already in use. Please use another email.</p></div><br>";
        echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button></a>";
    } else {
        if ($pass === $cpass) {
            $sql = "INSERT INTO users (username, email, password, phone, location, job_location, gender, current_company, position, domain, branch, graduation_year) 
                    VALUES ('$name', '$email', '$passwd', '$phone', '$location', '$job_location', '$gender', '$current_company', '$position', '$domain', '$branch', '$graduation_year')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                echo "<div class='message'><p>You are registered successfully!</p></div><br>";
                echo "<a href='login.php'><button class='btn'>Login Now</button></a>";
            } else {
                echo "<div class='message'><p>Failed to register. Please try again later.</p></div><br>";
                echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button></a>";
            }
        } else {
            echo "<div class='message'><p>Passwords do not match.</p></div><br>";
            echo "<a href='signup.php'><button class='btn'>Go Back</button></a>";
        }
    }
} else {
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/style1.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="container">
        <div class="form-box box">
            <header>Sign Up</header>
            <hr>

            <form action="#" method="POST">

                <div class="form-box">

                    <div class="input-container">
                        <i class="fa fa-user icon"></i>
                        <input class="input-field" type="text" placeholder="Username" name="username" required>
                    </div>

                    <div class="input-container">
                        <i class="fa fa-envelope icon"></i>
                        <input class="input-field" type="email" placeholder="Email Address" name="email" required>
                    </div>

                    <div class="input-container">
                        <i class="fa fa-phone icon"></i>
                        <input class="input-field" type="text" placeholder="Phone Number" name="phone" required>
                    </div>

                    <div class="input-container">
                        <i class="fa fa-map-marker icon"></i>
                        <input class="input-field" type="text" placeholder="Location" name="location" required>
                    </div>

                    <div class="input-container">
                        <i class="fa fa-building icon"></i>
                        <input class="input-field" type="text" placeholder="Job Location" name="job_location" required>
                    </div>

                    <div class="input-container">
                        <i class="fa fa-venus-mars icon"></i>
                        <select class="input-field" name="gender" required>
                            <option value="" disabled selected>Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="input-container">
                        <i class="fa fa-industry icon"></i> <!-- Added current company icon -->
                        <input class="input-field" type="text" placeholder="Current Company" name="current_company" required> <!-- Added current company input field -->
                    </div>

                    <div class="input-container">
                        <i class="fa fa-suitcase icon"></i> <!-- Added position icon -->
                        <input class="input-field" type="text" placeholder="Position" name="position" required> <!-- Added position input field -->
                    </div>

                    <div class="input-container">
                        <i class="fa fa-book icon"></i> <!-- Added domain icon -->
                        <select class="input-field" name="domain" required>
                            <option value="" disabled selected>Select School</option>
                            <option value="Engineering">Engineering</option>
                            
                           
                            <!-- Add more options as needed -->
                        </select>
                    </div>

                    <div class="input-container">
                        <i class="fa fa-graduation-cap icon"></i> <!-- Added branch icon -->
                        <select class="input-field" name="branch" required>
                            <option value="" disabled selected>Select Department</option>
                            <option value="Computer Science">Computer Science</option>
                            <option value="Electrical Engineering">Electrical </option>
                            <option value="Mechanical Engineering">Mechanical </option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>

                    <div class="input-container">
                        <i class="fa fa-calendar icon"></i> <!-- Added graduation year icon -->
                        <input class="input-field" type="number" placeholder="Graduation Year" name="graduation_year" required> <!-- Added graduation year input field -->
                    </div>

                    <div class="input-container">
                        <i class="fa fa-lock icon"></i>
                        <input class="input-field password" type="password" placeholder="Password" name="password" required>
                        <i class="fa fa-eye icon toggle"></i>
                    </div>

                    <div class="input-container">
                        <i class="fa fa-lock icon"></i>
                        <input class="input-field" type="password" placeholder="Confirm Password" name="cpass" required>
                        <i class="fa fa-eye icon"></i>
                    </div>

                </div>

                <center><input type="submit" name="register" id="submit" value="Signup" class="btn"></center>

                <div class="links">
                    Already have an account? <a href="login.php">Signin Now</a>
                </div>

            </form>
        </div>
    </div>

    <script>
        const toggle = document.querySelector(".toggle"),
            input = document.querySelector(".password");
        toggle.addEventListener("click", () => {
            if (input.type === "password") {
                input.type = "text";
                toggle.classList.replace("fa-eye-slash", "fa-eye");
            } else {
                input.type = "password";
            }
        })
    </script>
</body>

</html>

<?php
}
?>
    