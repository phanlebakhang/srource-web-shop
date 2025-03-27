<?php
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'], $data['name'], $data['price'], $data['quantity'])) {
    $filePath = 'xmap.json';

    if (!file_exists($filePath)) {
        file_put_contents($filePath, json_encode([]));
    }

    $cart = json_decode(file_get_contents($filePath), true);

    $productIndex = -1;
    foreach ($cart as $index => $item) {
        if ($item['id'] === $data['id']) {
            $productIndex = $index;
            break;
        }
    }

    if ($productIndex === -1) {
        $cart[] = $data;
    } else {
        $cart[$productIndex]['quantity'] += 1;
    }

    file_put_contents($filePath, json_encode($cart));

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Dữ liệu không hợp lệ']);
}
?>
