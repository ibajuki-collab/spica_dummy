<?php

require "../config/database.php";
require "../config/auth.php";

require_login();

$id = (int) ($_GET["id"] ?? 0);

if ($id <= 0) {
    header("Location: manage_news.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT *
    FROM news
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$id]);

$news = $stmt->fetch();

if (!$news) {
    header("Location: manage_news.php");
    exit;
}

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

        $oldImageName = $news["image"] ?? null;
        $imageName = $oldImageName;
        $newUploadedImage = null;

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

                $allowedExtensions = [
                    "jpg",
                    "jpeg",
                    "png",
                    "webp"
                ];

                $fileType = mime_content_type(
                    $_FILES["image"]["tmp_name"]
                );

                $extension = strtolower(
                    pathinfo(
                        $_FILES["image"]["name"],
                        PATHINFO_EXTENSION
                    )
                );

                if (
                    !in_array(
                        $fileType,
                        $allowedTypes,
                        true
                    )
                ) {

                    $error =
                        "Only JPG, PNG, and WEBP images are allowed.";

                } elseif (
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
                        !isset($mimeExtensionMap[$fileType]) ||
                        !in_array(
                            $extension,
                            $mimeExtensionMap[$fileType],
                            true
                        )
                    ) {

                        $error =
                            "Image type and extension do not match.";

                    }

                }

                if ($error === "") {

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

                            $newImageName =
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
                            $newImageName;

                        if (
                            move_uploaded_file(
                                $_FILES["image"]["tmp_name"],
                                $targetFile
                            )
                        ) {

                            $imageName = $newImageName;
                            $newUploadedImage = $newImageName;

                        } else {

                            $error =
                                "Failed to save uploaded image.";

                        }

                    }

                }

            }

        }

        if ($error === "") {

            try {

                $stmt = $pdo->prepare("
                    UPDATE news
                    SET
                        title = ?,
                        excerpt = ?,
                        image = ?,
                        published_at = ?
                    WHERE id = ?
                ");

                $stmt->execute([
                    $title,
                    $excerpt,
                    $imageName,
                    $published_at,
                    $id
                ]);

                if (
                    $newUploadedImage &&
                    !empty($oldImageName) &&
                    $oldImageName !== $newUploadedImage
                ) {

                    $oldImagePath =
                        __DIR__ .
                        "/../uploads/news/" .
                        basename($oldImageName);

                    if (is_file($oldImagePath)) {
                        unlink($oldImagePath);
                    }

                }

                header(
                    "Location: manage_news.php?success=updated"
                );

                exit;

            } catch (PDOException $e) {

                if ($newUploadedImage) {

                    $newImagePath =
                        __DIR__ .
                        "/../uploads/news/" .
                        basename($newUploadedImage);

                    if (is_file($newImagePath)) {
                        unlink($newImagePath);
                    }

                }

                $imageName = $oldImageName;

                $error = "Failed to update article.";
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
        Edit News — Spica Admin
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
            --admin-border: #dbe3ec;
            --admin-input-border: #cbd5e1;
            --admin-input-bg: #ffffff;
            --admin-upload-bg: #f8fafc;
            --admin-no-image-bg: #f1f5f9;
            --admin-cancel-bg: #e9eef4;
            --admin-cancel-text: #334155;
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
            --admin-no-image-bg: #202f3d;
            --admin-cancel-bg: #263646;
            --admin-cancel-text: #d8e1ea;
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

        .edit-page {
            width: min(850px, 94%);
            margin: 50px auto 80px;
        }

        .edit-card {
            background: var(--admin-card) !important;
            color: var(--admin-text) !important;
            border: 1px solid var(--admin-border);
            border-radius: 18px;
            padding: 32px;
            box-shadow: 0 10px 30px rgba(7, 26, 45, .07);
            transition:
                background .25s ease,
                border-color .25s ease,
                color .25s ease;
        }

        .edit-card h1 {
            margin-top: 0;
            color: var(--admin-heading) !important;
        }

        .edit-card > p {
            color: var(--admin-muted) !important;
        }

        .form-group {
            margin-bottom: 22px;
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
            font: inherit;
            background: var(--admin-input-bg) !important;
            color: var(--admin-text) !important;
            transition:
                background .25s ease,
                color .25s ease,
                border-color .25s ease;
        }

        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #20a4f3;
            box-shadow: 0 0 0 3px rgba(32, 164, 243, .12);
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: var(--admin-muted);
        }

        .current-image {
            margin-bottom: 15px;
        }

        .current-image img {
            display: block;
            width: 100%;
            max-width: 400px;
            height: 220px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid var(--admin-border);
        }

        .no-image {
            width: 100%;
            max-width: 400px;
            height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--admin-no-image-bg);
            color: var(--admin-muted);
            border-radius: 12px;
            border: 1px dashed var(--admin-input-border);
        }

        .image-upload {
            border: 2px dashed var(--admin-input-border);
            border-radius: 14px;
            padding: 22px;
            background: var(--admin-upload-bg) !important;
            transition:
                background .25s ease,
                border-color .25s ease;
        }

        .image-upload input[type="file"] {
            width: 100%;
            font-size: 14px;
            color: var(--admin-text);
        }

        .image-help {
            display: block;
            margin-top: 10px;
            color: var(--admin-muted) !important;
            font-size: 13px;
        }

        .notice-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 22px;
            font-weight: 600;
        }

        html[data-theme="dark"] .notice-error {
            background: #42252b;
            color: #ffb4b4;
            border-color: #63333b;
        }

        .edit-actions {
            display: flex;
            gap: 12px;
            margin-top: 28px;
        }

        .btn {
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 18px;
            border-radius: 9px;
            font-weight: 700;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-primary {
            background: #0b5ed7 !important;
            color: #ffffff !important;
        }

        .btn-primary:hover {
            background: #084db2 !important;
        }

        .cancel-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 18px;
            border-radius: 9px;
            background: var(--admin-cancel-bg) !important;
            color: var(--admin-cancel-text) !important;
            text-decoration: none;
            font-weight: 700;
            transition: .2s ease;
        }

        .cancel-btn:hover {
            background: var(--admin-border) !important;
        }

        html[data-theme="dark"] .edit-card {
            box-shadow: 0 10px 30px rgba(0, 0, 0, .25);
        }

        html[data-theme="dark"] .form-group input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        html[data-theme="dark"] .form-group input[type="file"] {
            color: #d8e1ea;
        }

        @media (max-width: 700px) {

            .edit-page {
                margin-top: 30px;
                width: min(94%, 850px);
            }

            .edit-card {
                padding: 22px;
            }

            .edit-actions {
                flex-direction: column;
            }

            .edit-actions .btn,
            .edit-actions .cancel-btn {
                width: 100%;
                box-sizing: border-box;
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

<div class="edit-page">

    <div class="edit-card">

        <h1>
            Edit Article
        </h1>

        <p>
            Update the article information and image.
        </p>

        <?php if ($error !== ""): ?>

            <div class="notice-error">

                <?= htmlspecialchars($error) ?>

            </div>

        <?php endif; ?>

        <form
            method="POST"
            enctype="multipart/form-data"
        >

            <div class="form-group">

                <label for="title">
                    Article Title
                </label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    value="<?= htmlspecialchars(
                        $_POST["title"]
                        ?? $news["title"]
                    ) ?>"
                    maxlength="180"
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
                    required
                ><?= htmlspecialchars(
                    $_POST["excerpt"]
                    ?? $news["excerpt"]
                ) ?></textarea>

            </div>

            <div class="form-group">

                <label>
                    Current Image
                </label>

                <div class="current-image">

                    <?php if (!empty($news["image"])): ?>

                        <img
                            src="../uploads/news/<?= htmlspecialchars(
                                basename($news["image"])
                            ) ?>"
                            alt="Current article image"
                        >

                    <?php else: ?>

                        <div class="no-image">
                            No image uploaded
                        </div>

                    <?php endif; ?>

                </div>

            </div>

            <div class="form-group">

                <label for="image">
                    Change Image
                </label>

                <div class="image-upload">

                    <input
                        type="file"
                        id="image"
                        name="image"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                    >

                    <small class="image-help">

                        Leave empty to keep the current image.
                        JPG, PNG, or WEBP. Maximum 5MB.

                    </small>

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
                        ?? $news["published_at"]
                    ) ?>"
                    required
                >

            </div>

            <div class="edit-actions">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    💾 Save Changes
                </button>

                <a
                    href="manage_news.php"
                    class="cancel-btn"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

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

</script>

</body>

</html>