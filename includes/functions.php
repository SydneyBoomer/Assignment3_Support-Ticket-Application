<?php

const DATA_FILE = __DIR__ . '/../data/tickets.json';
const UPLOAD_DIR = __DIR__ . '/../uploads/';

function allowedCategories(): array
{
    return ['technical', 'billing', 'account', 'other'];
}

function allowedStatuses(): array
{
    return ['Open', 'In Progress', 'Resolved'];
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function loadTickets(): array
{
    if (!file_exists(DATA_FILE)) {
        return [];
    }

    $json = file_get_contents(DATA_FILE);
    $tickets = json_decode($json, true);

    return is_array($tickets) ? $tickets : [];
}

function saveTickets(array $tickets): bool
{
    /*
     * TODO 1:
     * Convert $tickets to formatted JSON and write it to DATA_FILE.
     *
     * Think about:
     * - json_encode(...)
     * - file_put_contents(...)
     * - what should this function return if saving fails?
     */

    $json = json_encode($tickets, JSON_PRETTY_PRINT);
    if ($json !== false) {
        return file_put_contents(DATA_FILE, $json) !== false;
    }

    return false;
}

function findTicketById(array $tickets, int $id): ?array
{
    foreach ($tickets as $ticket) {
        if ((int)$ticket['id'] === $id) {
            return $ticket;
        }
    }

    return null;
}

function nextTicketId(array $tickets): int
{
    /*
     * TODO 2:
     * Return a unique integer ID for a new ticket.
     *
     * Hint:
     * The next ID can be one larger than the current maximum.
     */

    $maxId = 0;

    foreach ($tickets as $ticket) {
        if ($ticket['id'] > $maxId) {
            $maxId = $ticket['id'];
        }
    }

    return $maxId + 1;
    
    
}

function filterTickets(
    array $tickets,
    string $query = '',
    string $status = '',
    string $category = ''
): array {
    /*
     * TODO 3:
     * Filter tickets using the supplied GET parameters.
     *
     * Requirements:
     * - blank filters should not remove records
     * - status should match exactly
     * - category should match exactly
     * - keyword search should be case-insensitive
     * - search at least name, email, and description
     */
    $filteredTickets = [];
    foreach ($tickets as $ticket) {
        if ($status !== '' && $ticket['status'] !== $status) {
            continue;
        }
        if ($category !== '' && $ticket['category'] !== $category) {
            continue;
        }

        if ($query !== '') {
            $searchQuery = strtolower($query);

            $name = strtolower($ticket['name'] ?? '');
            $email = strtolower($ticket['email'] ?? '');
            $description = strtolower($ticket['description'] ?? '');

            if (
                strpos($name, $searchQuery) === false &&
                strpos($email, $searchQuery) === false &&
                strpos($description, $searchQuery) === false
            ) {
                continue;
            }
        }

        $filteredTickets[] = $ticket;
    }

    return $filteredTickets;
}

function updateTicketStatus(array &$tickets, int $id, string $newStatus): bool
{
    /*
     * TODO 4:
     * Locate the ticket with the matching ID and update its status.
     * Return true if a ticket was updated, otherwise false.
     */

    foreach ($tickets as $index => $ticket) {
        if ($ticket['id'] === $id) {
            $tickets[$index]['status'] = $newStatus;
            return true;
        }
    }

    return false;
}
