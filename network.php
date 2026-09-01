<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Network — Spica Indonesia</title>
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .network-section {
            padding: 80px 0;
        }

        .network-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 45px;
            align-items: center;
        }

        .network-map-box {
            background: #ffffff;
            border: 1px solid #dfe7ef;
            border-radius: 18px;
            padding: 12px;
            box-shadow: 0 12px 35px rgba(7, 26, 45, 0.10);
            transition: 0.3s ease;
        }

        .network-map {
            width: 100%;
            border: 1px solid #dfe7ef;
            border-radius: 12px;
            overflow: hidden;
            background: #f5f9fc;
            transition: 0.3s ease;
        }

        .network-map img {
            display: block;
            width: 100%;
            height: 430px;
            object-fit: contain;
            background: white;
        }

        .network-text h2 {
            font-size: 36px;
            line-height: 1.2;
            margin: 10px 0 18px;
            color: #0b2a4a;
        }

        .network-text > p {
            color: #6b7785;
            margin-bottom: 25px;
            line-height: 1.7;
        }

        .network-cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .network-card {
            background: #ffffff;
            border: 1px solid #dfe7ef;
            border-radius: 14px;
            padding: 20px;
            min-height: 115px;
            box-shadow: 0 8px 25px rgba(7, 26, 45, 0.05);
            transition: 0.25s ease;
        }

        .network-card:hover {
            transform: translateY(-3px);
            border-color: #20a4f3;
            box-shadow: 0 12px 30px rgba(7, 26, 45, 0.10);
        }

        .network-card h3 {
            margin: 0 0 8px;
            font-size: 16px;
            color: #0b2a4a;
        }

        .network-card h3 span {
            color: #0b5ed7;
            font-size: 12px;
            font-weight: 800;
            margin-right: 5px;
        }

        .network-card p {
            margin: 0;
            color: #6b7785;
            font-size: 14px;
            line-height: 1.5;
        }

        body.dark-mode .network-map-box,
        body.dark .network-map-box {
            background: #111c31;
            border-color: #263958;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.35);
        }

        body.dark-mode .network-map,
        body.dark .network-map {
            background: #0b1628;
            border-color: #334765;
        }

        body.dark-mode .network-map img,
        body.dark .network-map img {
            background: #0b1628;
        }

        body.dark-mode .network-text h2,
        body.dark .network-text h2 {
            color: #f8fafc;
        }

        body.dark-mode .network-text > p,
        body.dark .network-text > p {
            color: #b8c4d6;
        }

        body.dark-mode .network-card,
        body.dark .network-card {
            background: #111c31;
            border-color: #263958;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
        }

        body.dark-mode .network-card:hover,
        body.dark .network-card:hover {
            border-color: #20a4f3;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.35);
        }

        body.dark-mode .network-card h3,
        body.dark .network-card h3 {
            color: #f8fafc;
        }

        body.dark-mode .network-card h3 span,
        body.dark .network-card h3 span {
            color: #38bdf8;
        }

        body.dark-mode .network-card p,
        body.dark .network-card p {
            color: #aebbd0;
        }

        @media (max-width: 800px) {
            .network-wrapper {
                grid-template-columns: 1fr;
            }

            .network-map img {
                height: auto;
            }

            .network-cards {
                grid-template-columns: 1fr;
            }

            .network-text h2 {
                font-size: 30px;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="container nav-inner">

            <a class="logo" href="index.php">
                SPICA<span>INDONESIA</span>
            </a>

            <button class="menu" type="button">
                ☰
            </button>

            <button
                id="darkModeToggle"
                class="dark-mode-toggle"
                type="button"
            >
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

                <a class="active" href="network.php">
                    Network
                </a>

                <a href="news.php">
                    Updates
                </a>

                <a href="contact.php">
                    Contact
                </a>

            </div>

        </div>
    </nav>

    <header class="page-hero">
        <div class="container">

            <h1>
                Regional Network
            </h1>

            <p>
                Dummy interactive-style network section for Southeast Asia.
            </p>

        </div>
    </header>

    <section class="section network-section">

        <div class="container network-wrapper">

            <div class="network-map-box">

                <div class="network-map">

                    <img
                        src="assets/images/network-map.png.jpeg"
                        alt="Southeast Asia Network Map"
                    >

                </div>

            </div>

            <div class="network-text">

                <div class="eyebrow">
                    Regional Presence
                </div>

                <h2>
                    Connected across Southeast Asia.
                </h2>

                <p>
                    Sample office cards inspired by the regional
                    footprint shown on the public Spica website.
                </p>

                <div class="network-cards">

                    <div class="network-card">

                        <h3>
                            <span>SG</span>
                            Singapore
                        </h3>

                        <p>
                            Regional headquarters concept.
                        </p>

                    </div>

                    <div class="network-card">

                        <h3>
                            <span>ID</span>
                            Indonesia
                        </h3>

                        <p>
                            Jakarta office concept.
                        </p>

                    </div>

                    <div class="network-card">

                        <h3>
                            <span>MY</span>
                            Malaysia
                        </h3>

                        <p>
                            Regional office concept.
                        </p>

                    </div>

                    <div class="network-card">

                        <h3>
                            <span>VN</span>
                            Vietnam
                        </h3>

                        <p>
                            Regional office concept.
                        </p>

                    </div>

                    <div class="network-card">

                        <h3>
                            <span>TH</span>
                            Thailand
                        </h3>

                        <p>
                            Regional office concept.
                        </p>

                    </div>

                    <div class="network-card">

                        <h3>
                            <span>MM</span>
                            Myanmar
                        </h3>

                        <p>
                            Regional office concept.
                        </p>

                    </div>

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