<?php
session_start();

include("connection.php");

if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit; // Ensure script stops execution after redirection
}

// Fetch user's current details from the database
$user_id = $_SESSION['id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve updated details from the form
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];
    $new_phone = $_POST['phone'];
    $new_location = $_POST['location'];
    $new_job_location = $_POST['job_location'];
    $new_gender = $_POST['gender'];
    $new_current_company = $_POST['current_company'];
    $new_position = $_POST['position'];
    $new_domain = $_POST['domain'];
    $new_branch = $_POST['branch'];
    $new_graduation_year = $_POST['graduation_year'];

    // Validate and sanitize user input (You can add your validation logic here)

    // Update user's details in the database using an SQL UPDATE query
    $update_sql = "UPDATE users SET 
        username = '$new_username', 
        email = '$new_email', 
        phone = '$new_phone', 
        location = '$new_location', 
        job_location = '$new_job_location', 
        gender = '$new_gender', 
        current_company = '$new_current_company', 
        position = '$new_position', 
        domain = '$new_domain', 
        branch = '$new_branch', 
        graduation_year = '$new_graduation_year'
        WHERE id = $user_id";

    mysqli_query($conn, $update_sql);

    // Display confirmation message
    $success_message = "Your details have been updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>

<body>

    <div class="container mt-5">
        <?php if (isset($success_message)) : ?>
        <div class="alert alert-success" role="alert">
            <?php echo $success_message; ?>
        </div>
        <?php endif; ?>

        <h2>Update Profile</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username"
                    value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>"
                    required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone"
                    value="<?php echo $user['phone']; ?>">
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location"
                    value="<?php echo $user['location']; ?>">
            </div>
            <div class="mb-3">
                <label for="job_location" class="form-label">Job Location</label>
                <input type="text" class="form-control" id="job_location" name="job_location"
                    value="<?php echo $user['job_location']; ?>">
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-select" id="gender" name="gender">
                    <option value="Male" <?php if ($user['gender'] === 'Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if ($user['gender'] === 'Female') echo 'selected'; ?>>Female</option>
                    <option value="Other" <?php if ($user['gender'] === 'Other') echo 'selected'; ?>>Other</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="current_company" class="form-label">Current Company</label>
                <input type="text" class="form-control" id="current_company" name="current_company"
                    value="<?php echo $user['current_company']; ?>">
            </div>
            <div class="mb-3">
                <label for="position" class="form-label">Position</label>
                <input type="text" class="form-control" id="position" name="position"
                    value="<?php echo $user['position']; ?>">
            </div>
            <div class="mb-3">
                <label for="domain" class="form-label">Domain</label>
                <input type="text" class="form-control" id="domain" name="domain"
                    value="<?php echo $user['domain']; ?>">
            </div>
            <div class="mb-3">
                <label for="branch" class="form-label">Branch</label>
                <input type="text" class="form-control" id="branch" name="branch"
                    value="<?php echo $user['branch']; ?>">
            </div>
            <div class="mb-3">
                <label for="graduation_year" class="form-label">Graduation Year</label>
                <input type="text" class="form-control" id="graduation_year" name="graduation_year"
                    value="<?php echo $user['graduation_year']; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

</body>

</html>

<?php mysqli_close($conn); ?>
