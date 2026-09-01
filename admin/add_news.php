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

            }


            elseif (
                $_FILES["image"]["size"] > 5 * 1024 * 1024
            ) {

                $error = "Image size must not exceed 5MB.";

            }

            else {

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

                }

                else {

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

                        $error =
                            "Invalid image extension.";

                    }

                    else {

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

                        }

                        else {

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

                                // Random secure filename
                                $imageName =
                                    "news_" .
                                    bin2hex(
                                        random_bytes(8)
                                    ) .
                                    "." .
                                    $extension;


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


            }

            catch (PDOException $e) {


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


                $error =
                    "Failed to save article.";

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


        body.admin-body {

            background: #f4f7fb;

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

            color: #0b2a4a;

        }


        .admin-page-header p {

            margin: 8px 0 0;

            color: #64748b;

        }


        .admin-card {

            background: #ffffff;

            border: 1px solid #e2e8f0;

            border-radius: 18px;

            padding: 32px;

            box-shadow:
                0 10px 30px rgba(7, 26, 45, .07);

            max-width: 850px;

        }


        .form-group {

            margin-bottom: 24px;

        }


        .form-group label {

            display: block;

            margin-bottom: 8px;

            font-weight: 700;

            color: #0b2a4a;

        }


        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group textarea {

            width: 100%;

            box-sizing: border-box;

            padding: 13px 14px;

            border: 1px solid #d6dee8;

            border-radius: 10px;

            font-family: inherit;

            font-size: 14px;

            background: #ffffff;

            color: #172033;

            outline: none;

        }


        .form-group textarea {

            min-height: 180px;

            resize: vertical;

        }


        .form-group input:focus,
        .form-group textarea:focus {

            border-color: #20a4f3;

            box-shadow:
                0 0 0 3px rgba(32,164,243,.12);

        }



        .image-upload {

            border: 2px dashed #cbd5e1;

            border-radius: 14px;

            padding: 25px;

            background: #f8fafc;

        }


        .image-upload input[type="file"] {

            width: 100%;

            font-size: 14px;

            cursor: pointer;

        }


        .image-help {

            display: block;

            margin-top: 10px;

            color: #64748b;

            font-size: 13px;

        }



        .image-preview-container {

            display: none;

            margin-top: 20px;

            padding-top: 20px;

            border-top: 1px solid #e2e8f0;

        }


        .image-preview-title {

            margin-bottom: 10px;

            color: #0b2a4a;

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

            border: 1px solid #dbe3ec;

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

            background: #0b5ed7;

            color: #ffffff;

        }


        .btn-primary:hover {

            background: #084db2;

        }


        .btn-secondary {

            display: inline-block;

            padding: 12px 18px;

            border-radius: 9px;

            background: #eaf2f9;

            color: #0b5ed7;

            text-decoration: none;

            font-weight: 700;

        }


        .btn-secondary:hover {

            background: #dceaf6;

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


        <a
            href="logout.php"
            style="color:#fff;"
        >
            Logout
        </a>

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
                class="btn-secondary">
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
                id="newsForm">


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