<?php
// เชื่อมต่อฐานข้อมูล
include 'condb.php';

header("Content-Type: application/json; charset=UTF-8");

try {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === "GET") {
        // ดึงข้อมูลพนักงานทั้งหมด
        $stmt = $conn->prepare("SELECT emp_id, firstName, lastName, phone, username FROM employee ORDER BY emp_id DESC");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(["success" => true, "data" => $result]);
    }

    elseif ($method === "DELETE") {
        // ลบข้อมูลพนักงานตาม emp_id
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["emp_id"])) {
            echo json_encode(["success" => false, "message" => "ไม่พบค่า emp_id"]);
            exit;
        }

        $emp_id = intval($data["emp_id"]);

        $stmt = $conn->prepare("DELETE FROM employee WHERE emp_id = :id");
        $stmt->bindParam(":id", $emp_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "ลบข้อมูลเรียบร้อย"]);
        } else {
            echo json_encode(["success" => false, "message" => "ไม่สามารถลบข้อมูลได้"]);
        }
    }

    else {
        echo json_encode(["success" => false, "message" => "Method ไม่ถูกต้อง"]);
    }

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
