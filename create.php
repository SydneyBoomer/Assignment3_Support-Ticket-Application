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

    if (empty($errors)) {
        $tickets = loadTickets();

        /*
         * TODO 8: Build the new ticket.
         *
         * Use nextTicketId($tickets) for the ID.
         * Include name, email, category, description,
         * status, and attachment.
         */

        // $tickets[] = $newTicket;

        /*
         * TODO 9:
         * Save the updated ticket collection.
         *
         * If saving succeeds, redirect to index.php.
         * Do not continue rendering this form after the redirect.
         */
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
