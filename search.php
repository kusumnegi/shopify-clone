<?php
include_once __DIR__ . '/admin/config/db.php';

header('Content-Type: application/json; charset=utf-8');

$query = $_GET['q'] ?? '';

if ($query !== '') {
    $stmt = $conn->prepare("
        SELECT id, title, image 
        FROM products 
        WHERE LOWER(title) LIKE LOWER(?) 
        ORDER BY 
            CASE 
                WHEN LOWER(title) LIKE LOWER(CONCAT(?, '%')) THEN 1  -- starts with query
                WHEN LOWER(title) LIKE LOWER(?) THEN 2               -- contains exact query
                ELSE 3                                               -- fallback
            END,
            LENGTH(title) ASC                                       -- shorter titles first
        LIMIT 15
    ");
    $searchLike = "%" . $query . "%";
    $searchStarts = $query . "%";
    $stmt->bind_param("sss", $searchLike, $searchStarts, $searchLike);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        echo json_encode($products);
    } else {
        echo json_encode(["error" => $stmt->error]);
    }
} else {
    echo json_encode([]);
}
