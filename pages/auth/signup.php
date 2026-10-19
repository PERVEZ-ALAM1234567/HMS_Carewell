<?php

require_once "../../config/database.php";

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Get form data
    $fullName = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";


    // -----------------------------
    // Validation
    // -----------------------------

    if (
        empty($fullName) ||
        empty($email) ||
        empty($phone) ||
        empty($password) ||
        empty($confirmPassword)
    ) {

        $message = "Please fill in all fields.";
        $messageType = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $messageType = "error";

    } elseif (strlen($password) < 8) {

        $message = "Password must contain at least 8 characters.";
        $messageType = "error";

    } elseif ($password !== $confirmPassword) {

        $message = "Passwords do not match.";
        $messageType = "error";

    } else {

        try {

            // -----------------------------
            // Check existing email
            // -----------------------------

            $check = $conn->prepare(
                "SELECT id FROM users WHERE email = :email LIMIT 1"
            );

            $check->execute([
                ":email" => $email
            ]);

            if ($check->fetch()) {

                $message = "An account with this email already exists.";
                $messageType = "error";

            } else {

                // -----------------------------
                // Hash password
                // -----------------------------

                $hashedPassword = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );


                // -----------------------------
                // Insert user
                // -----------------------------

                $sql = "
                    INSERT INTO signup
                    (
                        full_name,
                        email,
                        phone,
                        password,
                        role
                    )
                    VALUES
                    (
                        :full_name,
                        :email,
                        :phone,
                        :password,
                        'patient'
                    )
                ";

                $stmt = $conn->prepare($sql);

                $stmt->execute([
                    ":full_name" => $fullName,
                    ":email" => $email,
                    ":phone" => $phone,
                    ":password" => $hashedPassword
                ]);


                // -----------------------------
                // Success
                // -----------------------------

                header(
                    "Location: signup.html?success=1"
                );

                exit;
            }

        } catch (PDOException $e) {

            $message = "Something went wrong. Please try again.";
            $messageType = "error";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        CareWell Hospital — Signup Error
    </title>

    <link
        rel="stylesheet"
        href="signup.css">

</head>


<body>

    <main class="auth-page">

        <div class="container">

            <section
                class="signup-card"
                style="max-width: 600px; margin: auto;">

                <div class="card-header">

                    <span class="card-icon">
                        !
                    </span>

                    <div>

                        <h2>
                            Account Registration
                        </h2>

                        <p>
                            CareWell Hospital
                        </p>

                    </div>

                </div>


                <div
                    style="
                        padding: 18px;
                        border-radius: 14px;
                        background: rgba(239, 83, 80, 0.08);
                        color: #ef5350;
                        font-size: 14px;
                        margin-bottom: 24px;
                    ">

                    <?php echo htmlspecialchars($message); ?>

                </div>


                <a
                    href="signup.html"
                    class="btn btn-primary btn-lg"
                    style="width: 100%;">

                    ← Back to Signup

                </a>

            </section>

        </div>

    </main>

</body>

</html>