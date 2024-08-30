<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = htmlspecialchars($_POST['firstName']);
    $lastName = htmlspecialchars($_POST['lastName']);
    $gender = htmlspecialchars($_POST['gender']);
    $dob = $_POST['dob'];
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $phoneNumber = htmlspecialchars($_POST['number']);
    $streetAddress = $_POST['streetAddress'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postalCode = $_POST['postalCode'];
    $captcha = $_POST['captcha'];

    // Educational Qualifications
    $institutions = $_POST['institution'];
    $qualifications = $_POST['qualification'];
    $years = $_POST['year'];

    // Work Experience
    $companies = $_POST['company'];
    $roles = $_POST['role'];
    $durations = $_POST['duration'];



    // Check if CAPTCHA is correct
    if ($captcha == $_SESSION['captcha']) {
        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "registration";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (first_name, last_name, dob, gender, email, password, phone_number, streetAddress, city, state, postalCode) VALUES (?, ?, ?, ?, ?, ?, ?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssss", $firstName, $lastName, $dob, $gender, $email, $hashedPassword, $phoneNumber, $streetAddress, $city, $state, $postalCode);

        if ($stmt->execute()) {
            $userId = $stmt->insert_id;
            // Handling educational qualifications
            $institutions = $_POST['institution'];
            $qualifications = $_POST['qualification'];

            $years = $_POST['year'];

            $sql = "INSERT INTO education (user_id, qualification, institution, year) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            for ($i = 0; $i < count($qualifications); $i++) {
                $qualification = htmlspecialchars($qualifications[$i]);
                $institution = htmlspecialchars($institutions[$i]);
                $year = htmlspecialchars($years[$i]);
                $stmt->bind_param("isss", $userId, $qualification, $institution, $year);
                $stmt->execute();
            }

             // Handling work experience
             $sql = "INSERT INTO work_experience (user_id, company, role, duration) VALUES (?, ?, ?, ?)";
             $stmt = $conn->prepare($sql);
 
             for ($i = 0; $i < count($companies); $i++) {
                 $company = htmlspecialchars($companies[$i]);
                 $role = htmlspecialchars($roles[$i]);
                 $duration = htmlspecialchars($durations[$i]);
                 $stmt->bind_param("isss", $userId, $company, $role, $duration);
                 $stmt->execute();
             }
 
            echo '<div>
                    <h2>New record created successfully. Redirecting in 5 seconds...</h2>
                    <br>
                    <img src="3.jpg" style="margin-top: 0px; width: 1000px; top: 10px;">
                  </div>';
            // Redirect after 5 seconds
            echo '<script>
                    setTimeout(function() {
                        window.location.href = "index.html";
                    }, 5000);
                  </script>';
            exit; // Ensure no more output is sent
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo '<script>alert("Captcha verification failed.");</script>';
        echo '<script>
        window.location.href = "index.html";
        </script>';
        exit;
    }
}
?>