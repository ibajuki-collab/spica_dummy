<?php

require "config/database.php";

$sent = false;
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $subject = trim($_POST["subject"] ?? "");
    $message = trim($_POST["message"] ?? "");

    if (
        $name &&
        filter_var($email, FILTER_VALIDATE_EMAIL) &&
        $subject &&
        $message
    ) {
        $stmt = $pdo->prepare(
            "INSERT INTO messages (name,email,subject,message) VALUES (?,?,?,?)"
        );

        $stmt->execute([
            $name,
            $email,
            $subject,
            $message
        ]);

        $sent = true;
    } else {
        $error = "Please complete all fields with a valid email.";
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Contact — Spica Indonesia</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <nav class="navbar">

        <div class="container nav-inner">

            <a class="logo" href="index.php">
                SPICA<span>INDONESIA</span>
            </a>

            <button class="menu">
                ☰
            </button>

            <button id="darkModeToggle" class="dark-mode-toggle" type="button">
                        🌙
            </button>

            <div class="nav-links">

                <a href="index.php">
                    Home
                </a>

                <a href="about.php">
                    About
                </a>

                <a href="services.php">
                    Services
                </a>

                <a href="network.php">
                    Network
                </a>

                <a href="news.php">
                    Updates
                </a>

                <a class="active" href="contact.php">
                    Contact
                </a>

            </div>

        </div>

    </nav>

    <header class="page-hero">

        <div class="container">

            <h1>
                Contact Us
            </h1>

            <p>
                Messages are stored in MySQL for this dummy project.
            </p>

        </div>

    </header>

    <section class="section">

        <div class="container split">

            <div>

                <div class="eyebrow">
                    Indonesia Office
                </div>

                <h2>
                    Let's connect.
                </h2>

                <p>
                    RPX Center Building, Jakarta Selatan — use the official website for real contact information.
                </p>

                <div class="map">
    <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.8738372477615!2d106.77178560000002!3d-6.280312299999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f1abfc48ff55%3A0xa01eb29572c2f170!2sRPX%20One%20Stop%20Logistics!5e0!3m2!1sid!2sid!4v1786678548887!5m2!1sid!2sid"
        width="600"
        height="450"
        style="border:0;"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="strict-origin-when-cross-origin">
    </iframe>
</div>

            </div>

            <div>

                <div class="card">

                    <?php if ($sent): ?>

                        <div class="notice">
                            Message sent successfully (demo).
                        </div>

                    <?php elseif ($error): ?>

                        <div class="notice error">
                            <?= htmlspecialchars($error) ?>
                        </div>

                    <?php endif; ?>

                    <form class="form" method="post">

                        <div>

                            <label>
                                Name
                            </label>

                            <input name="name" required>

                        </div>

                        <div>

                            <label>
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                required
                            >

                        </div>

                        <div>

                            <label>
                                Subject
                            </label>

                            <input name="subject" required>

                        </div>

                        <div>

                            <label>
                                Message
                            </label>

                            <textarea
                                name="message"
                                required
                            ></textarea>

                        </div>

                        <button
                            class="btn btn-primary"
                            type="submit"
                        >
                            Send Message
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </section>

    <footer class="footer">

        <div class="container">

            <p>
                ©️ 2026 Dummy Spica Indonesia Project
            </p>

        </div>

    </footer>

    <script src="assets/js/app.js"></script>

</body>

</html>