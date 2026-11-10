<?php

require "../config/database.php";
require "../config/auth.php";

require_login();

$error = ""; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"] ?? "");
    $excerpt = trim($_POST["excerpt"] ?? "");
    $published_at = $_POST["published_at"] ?? "";

    if (
        $title === "" ||
        $excerpt === "" ||
        $published_at === ""
    ) {

        $error = "Please complete all fields.";

    } else {

        $imageName = null;

        if (
            isset($_FILES["image"]) &&
            $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE
        ) {

            if ($_FILES["image"]["error"] !== UPLOAD_ERR_OK) {

                $error = "Failed to upload image.";

            } elseif (
                $_FILES["image"]["size"] > 5 * 1024 * 1024
            ) {

                $error = "Image size must not exceed 5MB.";

            } else {

                $allowedTypes = [
                    "image/jpeg",
                    "image/png",
                    "image/webp"
                ];

                $fileType = mime_content_type(
                    $_FILES["image"]["tmp_name"]
                );

                if (!in_array($fileType, $allowedTypes, true)) {

                    $error =
                        "Only JPG, PNG, and WEBP images are allowed.";

                } else {

                    $allowedExtensions = [
                        "jpg",
                        "jpeg",
                        "png",
                        "webp"
                    ];

                    $extension = strtolower(
                        pathinfo(
                            $_FILES["image"]["name"],
                            PATHINFO_EXTENSION
                        )
                    );

                    if (
                        !in_array(
                            $extension,
                            $allowedExtensions,
                            true
                        )
                    ) {

                        $error = "Invalid image extension.";

                    } else {

                        $mimeExtensionMap = [
                            "image/jpeg" => [
                                "jpg",
                                "jpeg"
                            ],
                            "image/png" => [
                                "png"
                            ],
                            "image/webp" => [
                                "webp"
                            ]
                        ];

                        if (
                            !isset(
                                $mimeExtensionMap[$fileType]
                            ) ||
                            !in_array(
                                $extension,
                                $mimeExtensionMap[$fileType],
                                true
                            )
                        ) {

                            $error =
                                "Image type and extension do not match.";

                        } else {

                            $uploadDir =
                                __DIR__ .
                                "/../uploads/news/";

                            if (!is_dir($uploadDir)) {

                                if (
                                    !mkdir(
                                        $uploadDir,
                                        0755,
                                        true
                                    )
                                ) {

                                    $error =
                                        "Upload folder could not be created.";

                                }

                            }

                            if ($error === "") {

                                try {

                                    $imageName =
                                        "news_" .
                                        bin2hex(
                                            random_bytes(8)
                                        ) .
                                        "." .
                                        $extension;

                                } catch (Exception $e) {

                                    $error =
                                        "Could not generate image filename.";

                                }

                            }

                            if ($error === "") {

                                $targetFile =
                                    $uploadDir .
                                    $imageName;

                                if (
                                    !move_uploaded_file(
                                        $_FILES["image"]["tmp_name"],
                                        $targetFile
                                    )
                                ) {

                                    $error =
                                        "Failed to save uploaded image.";

                                    $imageName = null;

                                }

                            }

                        }

                    }

                }

            }

        }

        if ($error === "") {

            try {

                $stmt = $pdo->prepare("
                    INSERT INTO news
                    (
                        title,
                        excerpt,
                        image,
                        published_at
                    )
                    VALUES (?, ?, ?, ?)
                ");

                $stmt->execute([
                    $title,
                    $excerpt,
                    $imageName,
                    $published_at
                ]);

                header(
                    "Location: manage_news.php?success=added"
                );

                exit;

            } catch (PDOException $e) {

                if (
                    $imageName &&
                    file_exists(
                        __DIR__ .
                        "/../uploads/news/" .
                        $imageName
                    )
                ) {

                    unlink(
                        __DIR__ .
                        "/../uploads/news/" .
                        $imageName
                    );

                }

                $error = "Failed to save article.";

            }

        }

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

    <title>
        Add News — Spica Admin
    </title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

    <style>

        :root {
            --admin-bg: #f4f7fb;
            --admin-card: #ffffff;
            --admin-text: #172033;
            --admin-heading: #0b2a4a;
            --admin-muted: #64748b;
            --admin-border: #e2e8f0;
            --admin-input-border: #d6dee8;
            --admin-input-bg: #ffffff;
            --admin-upload-bg: #f8fafc;
            --admin-preview-border: #dbe3ec;
            --admin-secondary-bg: #eaf2f9;
            --admin-secondary-text: #0b5ed7;
            --admin-primary: #0b5ed7;
        }

        html[data-theme="dark"] {
            --admin-bg: #101923;
            --admin-card: #182532;
            --admin-text: #f1f5f9;
            --admin-heading: #f1f5f9;
            --admin-muted: #9aa9ba;
            --admin-border: #2b3a49;
            --admin-input-border: #3b4b5c;
            --admin-input-bg: #111d29;
            --admin-upload-bg: #14212e;
            --admin-preview-border: #3b4b5c;
            --admin-secondary-bg: #173352;
            --admin-secondary-text: #55a9ff;
            --admin-primary: #55a9ff;
        }

        body.admin-body {
            background: var(--admin-bg) !important;
            color: var(--admin-text) !important;
            transition:
                background .25s ease,
                color .25s ease;
        }

        .admin-nav {
            background: #071a2d;
            color: #ffffff;
            padding: 18px 0;
        }

        .admin-nav .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .admin-nav b {
            color: #ffffff;
            font-size: 16px;
            letter-spacing: .3px;
        }

        .admin-nav a {
            color: #ffffff !important;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
        }

        .admin-theme-button {
            border: 1px solid rgba(255, 255, 255, .3);
            background: transparent;
            color: #ffffff;
            padding: 7px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: .2s ease;
        }

        .admin-theme-button:hover {
            background: rgba(255, 255, 255, .1);
        }

        .admin-page {
            padding: 50px 0 80px;
        }

        .admin-page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .admin-page-header h1 {
            margin: 0;
            color: var(--admin-heading) !important;
        }

        .admin-page-header p {
            margin: 8px 0 0;
            color: var(--admin-muted) !important;
        }

        .admin-card {
            background: var(--admin-card) !important;
            color: var(--admin-text) !important;
            border: 1px solid var(--admin-border);
            border-radius: 18px;
            padding: 32px;
            box-shadow: 0 10px 30px rgba(7, 26, 45, .07);
            max-width: 850px;
            transition:
                background .25s ease,
                border-color .25s ease,
                color .25s ease;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: var(--admin-heading) !important;
        }

        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 13px 14px;
            border: 1px solid var(--admin-input-border);
            border-radius: 10px;
            font-family: inherit;
            font-size: 14px;
            background: var(--admin-input-bg) !important;
            color: var(--admin-text) !important;
            outline: none;
            transition:
                background .25s ease,
                color .25s ease,
                border-color .25s ease;
        }

        .form-group textarea {
            min-height: 180px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #20a4f3;
            box-shadow:
                0 0 0 3px rgba(32, 164, 243, .12);
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: var(--admin-muted);
        }

        .image-upload {
            border: 2px dashed var(--admin-input-border);
            border-radius: 14px;
            padding: 25px;
            background: var(--admin-upload-bg) !important;
            transition:
                background .25s ease,
                border-color .25s ease;
        }

        .image-upload input[type="file"] {
            width: 100%;
            font-size: 14px;
            cursor: pointer;
            color: var(--admin-text);
        }

        .image-help {
            display: block;
            margin-top: 10px;
            color: var(--admin-muted) !important;
            font-size: 13px;
        }

        .image-preview-container {
            display: none;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--admin-border);
        }

        .image-preview-title {
            margin-bottom: 10px;
            color: var(--admin-heading) !important;
            font-size: 14px;
            font-weight: 700;
        }

        .image-preview {
            display: block;
            width: 100%;
            max-width: 500px;
            height: 280px;
            object-fit: cover;
            border-radius: 14px;
            border: 1px solid var(--admin-preview-border);
        }

        .remove-image {
            margin-top: 12px;
            border: none;
            background: #fee2e2;
            color: #dc2626;
            padding: 9px 14px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
        }

        .remove-image:hover {
            background: #fecaca;
        }

        .notice {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 22px;
            font-size: 14px;
            font-weight: 600;
        }

        .notice.error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        html[data-theme="dark"] .notice.error {
            background: #42252b;
            color: #ffb4b4;
            border-color: #63333b;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }

        .btn {
            border: none;
            cursor: pointer;
            padding: 12px 18px;
            border-radius: 9px;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #0b5ed7 !important;
            color: #ffffff !important;
        }

        .btn-primary:hover {
            background: #084db2 !important;
        }

        .btn-secondary {
            display: inline-block;
            padding: 12px 18px;
            border-radius: 9px;
            background: var(--admin-secondary-bg) !important;
            color: var(--admin-secondary-text) !important;
            text-decoration: none;
            font-weight: 700;
            transition: .2s ease;
        }

        .btn-secondary:hover {
            background: var(--admin-border) !important;
        }

        html[data-theme="dark"] .admin-card {
            box-shadow: 0 10px 30px rgba(0, 0, 0, .25);
        }

        html[data-theme="dark"] input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        @media (max-width: 700px) {

            .admin-page {
                padding: 30px 0 60px;
            }

            .admin-page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .admin-card {
                padding: 22px;
            }

            .image-preview {
                height: 200px;
            }

            .form-actions {
                flex-direction: column;
            }

            .form-actions .btn,
            .form-actions .btn-secondary {
                width: 100%;
                box-sizing: border-box;
                text-align: center;
            }

        }

    </style>

</head>

<body class="admin-body">

<div class="admin-nav">

    <div class="container">

        <b>
            SPICA ADMIN DEMO
        </b>

        <div
            style="
                display: flex;
                align-items: center;
                gap: 15px;
            "
        >

            <button
                type="button"
                class="admin-theme-button"
            >
                🌙 Dark Mode
            </button>

            <a
                href="logout.php"
            >
                Logout
            </a>

        </div>

    </div>

</div>

<main class="admin-page">

    <div class="container">

        <div class="admin-page-header">

            <div>

                <h1>
                    Add New Article
                </h1>

                <p>
                    Create a new maritime news article.
                </p>

            </div>

            <a
                href="manage_news.php"
                class="btn-secondary"
            >
                ← Manage News
            </a>

        </div>

        <div class="admin-card">

            <?php if ($error !== ""): ?>

                <div class="notice error">

                    <?= htmlspecialchars($error) ?>

                </div>

            <?php endif; ?>

            <form
                method="POST"
                enctype="multipart/form-data"
                id="newsForm"
            >

                <div class="form-group">

                    <label for="title">
                        Article Title
                    </label>

                    <input
                        type="text"
                        id="title"
                        name="title"
                        placeholder="Enter news title"
                        maxlength="180"
                        value="<?= htmlspecialchars(
                            $_POST["title"] ?? ""
                        ) ?>"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="excerpt">
                        Excerpt
                    </label>

                    <textarea
                        id="excerpt"
                        name="excerpt"
                        placeholder="Write a short description of the article..."
                        required
                    ><?= htmlspecialchars(
                        $_POST["excerpt"] ?? ""
                    ) ?></textarea>

                </div>

                <div class="form-group">

                    <label for="image">
                        Article Image
                    </label>

                    <div class="image-upload">

                        <input
                            type="file"
                            id="image"
                            name="image"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        >

                        <small class="image-help">

                            JPG, PNG, or WEBP.
                            Maximum file size: 5MB.

                        </small>

                        <div
                            id="imagePreviewContainer"
                            class="image-preview-container"
                        >

                            <div class="image-preview-title">
                                Image Preview
                            </div>

                            <img
                                id="imagePreview"
                                class="image-preview"
                                src=""
                                alt="Image Preview"
                            >

                            <button
                                type="button"
                                id="removeImage"
                                class="remove-image"
                            >
                                ✕ Remove Image
                            </button>

                        </div>

                    </div>

                </div>

                <div class="form-group">

                    <label for="published_at">
                        Published Date
                    </label>

                    <input
                        type="date"
                        id="published_at"
                        name="published_at"
                        value="<?= htmlspecialchars(
                            $_POST["published_at"]
                            ?? date("Y-m-d")
                        ) ?>"
                        required
                    >

                </div>

                <div class="form-actions">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        📰 Publish Article
                    </button>

                    <a
                        href="manage_news.php"
                        class="btn-secondary"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</main>

<script>

const themeButton =
    document.querySelector(".admin-theme-button");

function applyTheme(theme) {

    document.documentElement.setAttribute(
        "data-theme",
        theme
    );

    if (theme === "dark") {

        themeButton.textContent =
            "☀️ Light Mode";

    } else {

        themeButton.textContent =
            "🌙 Dark Mode";

    }

}

const savedTheme =
    localStorage.getItem("spicaAdminTheme") || "light";

applyTheme(savedTheme);

themeButton.addEventListener(
    "click",
    function () {

        const currentTheme =
            document.documentElement.getAttribute(
                "data-theme"
            );

        const newTheme =
            currentTheme === "dark"
                ? "light"
                : "dark";

        localStorage.setItem(
            "spicaAdminTheme",
            newTheme
        );

        applyTheme(newTheme);

    }
);

const imageInput =
    document.getElementById("image");

const previewContainer =
    document.getElementById(
        "imagePreviewContainer"
    );

const imagePreview =
    document.getElementById("imagePreview");

const removeImage =
    document.getElementById("removeImage");

imageInput.addEventListener(
    "change",
    function () {

        const file = this.files[0];

        if (!file) {

            previewContainer.style.display =
                "none";

            imagePreview.src = "";

            return;

        }

        if (file.size > 5 * 1024 * 1024) {

            alert(
                "Image size must not exceed 5MB."
            );

            this.value = "";

            previewContainer.style.display =
                "none";

            imagePreview.src = "";

            return;

        }

        const allowedTypes = [
            "image/jpeg",
            "image/png",
            "image/webp"
        ];

        if (!allowedTypes.includes(file.type)) {

            alert(
                "Only JPG, PNG, and WEBP images are allowed."
            );

            this.value = "";

            previewContainer.style.display =
                "none";

            imagePreview.src = "";

            return;

        }

        const allowedExtensions = [
            "jpg",
            "jpeg",
            "png",
            "webp"
        ];

        const extension =
            file.name
                .split(".")
                .pop()
                .toLowerCase();

        if (!allowedExtensions.includes(extension)) {

            alert(
                "Invalid image extension."
            );

            this.value = "";

            previewContainer.style.display =
                "none";

            imagePreview.src = "";

            return;

        }

        const reader =
            new FileReader();

        reader.onload = function (event) {

            imagePreview.src =
                event.target.result;

            previewContainer.style.display =
                "block";

        };

        reader.readAsDataURL(file);

    }
);

removeImage.addEventListener(
    "click",
    function () {

        imageInput.value = "";

        imagePreview.src = "";

        previewContainer.style.display =
            "none";

    }
);

</script>

</body>

</html>