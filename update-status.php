<?php
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

/*
 * TODO 10:
 * Read and validate the submitted ticket ID and status.
 *
 * Requirements:
 * - ID must be a valid integer
 * - status must be in allowedStatuses()
 * - ticket must exist
 * - update must be saved to the JSON file
 * - redirect back to detail.php?id=...
 *
 * Think about what status code/message should be returned
 * when the request is invalid.
 */

http_response_code(501);
echo 'Status update not implemented yet.';
