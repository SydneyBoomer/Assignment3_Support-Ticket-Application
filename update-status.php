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


$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$status = $_POST['status'] ?? '';

if ($id === false || $id === null || $id < 1) {
    http_response_code(400);
    exit('Invalid ticket ID.');
}

if (!in_array($status, allowedStatuses(), true)) {
    http_response_code(400);
    exit('Invalid ticket status.');
}

$tickets = loadTickets();

$ticket = findTicketById($tickets, $id);

if ($ticket === null) {
    http_response_code(404);
    exit('Ticket not found.');
}

if (!updateTicketStatus($tickets, $id, $status)) {
    http_response_code(500);
    exit('Failed to update ticket status.');
}

if (!saveTickets($tickets)) {
    http_response_code(500);
    exit('Failed to save ticket status.');
}

header('Location: detail.php?id=' . $id);
exit;


// http_response_code(501);
// echo 'Status update not implemented yet.';
?>