<?php

include 'condb.php';

header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents("php://input"), true);

// ตรวจสอบข้อมูลที่รับมา
if (
    !isset($data['subject']) ||
    !isset($data['detail']) ||
    !isset($data['fullname']) ||
    !isset($data['email'])
) {
    echo json_encode([
        "success" => false,
        "message" => "ข้อมูลไม่ครบ"
    ]);
    exit;
}

try {

    // ไม่ต้องส่ง created_at
    // ให้ MySQL สร้างวันเวลาให้อัตโนมัติ
    $sql = "INSERT INTO contacts
            (subject, detail, fullname, email)
            VALUES
            (:subject, :detail, :fullname, :email)";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        ':subject'  => $data['subject'],
        ':detail'   => $data['detail'],
        ':fullname' => $data['fullname'],
        ':email'    => $data['email']
    ]);

    echo json_encode([
        "success" => true,
        "message" => "เพิ่มข้อมูลเรียบร้อย"
    ]);

} catch (PDOException $e) {

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>
