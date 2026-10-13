<?php
require_once __DIR__ . '/includes/functions.php';

$values = [
    'name' => '',
    'email' => '',
    'category' => '',
    'description' => ''
];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values['name'] = trim($_POST['name'] ?? '');
    $values['email'] = trim($_POST['email'] ?? '');
    $values['category'] = trim($_POST['category'] ?? '');
    $values['description'] = trim($_POST['description'] ?? '');

    /*
     * TODO 6: Server-side validation
     *
     * Validate:
     * - name is not empty
     * - email is valid
     * - category is in allowedCategories()
     * - description meets your chosen minimum length
     *
     * Store useful messages in $errors.
     */

    //validate name isn't empty
    if ($values['name'] === "") {
        $errors[] = 'Name should not be empty.';
    }

    if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address.';
    }

    $categoryFound = false;

    foreach (allowedCategories() as $category) {
        if ($values['category'] === $category) {
            $categoryFound = true;
            break;
        }     
    }

    if (!$categoryFound) {
        $errors[] = 'Invalid category';
    }

    if (strlen($values['description']) < 20) {
        $errors[] = 'description is too short';
    }

    $attachment = null;

    /*
     * TODO 7: Optional file upload
     *
     * If a file was submitted:
     * - check upload error
     * - enforce a maximum size
     * - validate the real file type
     * - generate a safe filename
     * - move it to UPLOAD_DIR
     *
     * Add an error to $errors if the upload is invalid.
     */

    if (isset($_FILES['attachment'])) {
        $file = $_FILES['attachment'];

        if ($file['error'] !== UPLOAD_ERR_NO_FILE) {

            if ($file['error'] !== UPLOAD_ERR_OK) {
                $errors[] = 'There was a problem uploading the image.';
            } else {

                $maxSize = 2 * 1024 * 1024;

                if ($file['size'] > $maxSize) {
                    $errors[] = 'Image must be no larger than 2 MB.';
                } else {

                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mimeType = $finfo->file($file['tmp_name']);

                    $allowedTypes = [
                        'image/jpeg' => 'jpg',
                        'image/png' => 'png',
                        'image/webp' => 'webp'
                    ];

                    if (!isset($allowedTypes[$mimeType])) {
                        $errors[] = 'Only JPEG, PNG, and WebP images are allowed.';
                    } else {

                        $filename = bin2hex(random_bytes(16))
                            . '.' . $allowedTypes[$mimeType];

                        if (empty($errors)) {
                            if (move_uploaded_file(
                                $file['tmp_name'],
                                UPLOAD_DIR . $filename
                            )) {
                                $attachment = $filename;
                            } else {
                                $errors[] = 'Failed to save the uploaded image.';
                            }
                        }
                    }
                }
            }
        }
    }


    if (empty($errors)) {
        $tickets = loadTickets();

        /*
         * TODO 8: Build the new ticket.
         *
         * Use nextTicketId($tickets) for the ID.
         * Include name, email, category, description,
         * status, and attachment.
         */

        $newTicket = [
            'id' => nextTicketId($tickets),
            'name' => $values['name'],
            'email' => $values['email'],
            'category' => $values['category'],
            'description' => $values['description'],
            'status' => 'Open',
            'attachment' => $attachment
        ];

        $tickets[] = $newTicket;

        /*
         * TODO 9:
         * Save the updated ticket collection.
         *
         * If saving succeeds, redirect to index.php.
         * Do not continue rendering this form after the redirect.
         */
        
        if (saveTickets($tickets)) {
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Failed to save the ticket. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Support Ticket</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<main class="container">
    <p><a href="index.php">&larr; Back to tickets</a></p>

    <section class="panel">
        <h1>Create Support Ticket</h1>
        <p>Complete the form below. Important validation must occur on the server.</p>

        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <strong>Please correct the following:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="create.php" enctype="multipart/form-data">
            <label for="name">Customer Name</label>
            <input
                type="text"
                id="name"
                name="name"
                value="<?= e($values['name']) ?>"
            >

            <label for="email">Email Address</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= e($values['email']) ?>"
            >

            <label for="category">Category</label>
            <select id="category" name="category">
                <option value="">Choose a category</option>
                <?php foreach (allowedCategories() as $category): ?>
                    <option
                        value="<?= e($category) ?>"
                        <?= $values['category'] === $category ? 'selected' : '' ?>
                    >
                        <?= e(ucfirst($category)) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="description">Description</label>
            <textarea id="description" name="description" rows="7"><?= e($values['description']) ?></textarea>

            <label for="attachment">Optional Image</label>
            <input
                type="file"
                id="attachment"
                name="attachment"
                accept="image/jpeg,image/png,image/webp"
            >

            <button type="submit">Submit Ticket</button>
        </form>
    </section>
</main>
</body>
</html>
