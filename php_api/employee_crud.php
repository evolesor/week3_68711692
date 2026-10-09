<?php
include 'condb.php';

header("Content-Type: application/json; charset=UTF-8");

try {
    $method = $_SERVER['REQUEST_METHOD'];

    // ดึงข้อมูลพนักงานทั้งหมด
    if ($method === "GET") {
        $stmt = $conn->prepare(
            "SELECT emp_id, firstName, lastName, phone, username
             FROM employee
             ORDER BY emp_id DESC"
        );

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            "success" => true,
            "data" => $result
        ]);
    }

    // เพิ่มข้อมูลพนักงาน
    elseif ($method === "POST") {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "ข้อมูล JSON ไม่ถูกต้อง"
            ]);
            exit;
        }

        $required = [
            "firstName",
            "lastName",
            "phone",
            "username",
            "password"
        ];

        foreach ($required as $field) {
            if (!isset($data[$field]) || trim((string)$data[$field]) === '') {
                http_response_code(400);
                echo json_encode([
                    "success" => false,
                    "message" => "กรุณากรอกข้อมูลให้ครบ"
                ]);
                exit;
            }
        }

        $passwordHash = password_hash(
            $data["password"],
            PASSWORD_DEFAULT
        );

        $stmt = $conn->prepare(
            "INSERT INTO employee
             (firstName, lastName, phone, username, password)
             VALUES
             (:firstName, :lastName, :phone, :username, :password)"
        );

        $stmt->execute([
            ":firstName" => trim($data["firstName"]),
            ":lastName" => trim($data["lastName"]),
            ":phone" => trim($data["phone"]),
            ":username" => trim($data["username"]),
            ":password" => $passwordHash
        ]);

        echo json_encode([
            "success" => true,
            "message" => "เพิ่มข้อมูลพนักงานเรียบร้อย"
        ]);
    }

    // แก้ไขข้อมูลพนักงาน
    elseif ($method === "PUT") {
        $data = json_decode(file_get_contents("php://input"), true);

        if (
            !is_array($data) ||
            !isset($data["emp_id"]) ||
            !is_numeric($data["emp_id"]) ||
            (int)$data["emp_id"] < 1
        ) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "รหัสพนักงานไม่ถูกต้อง"
            ]);
            exit;
        }

        $required = ["firstName", "lastName", "phone", "username"];

        foreach ($required as $field) {
            if (!isset($data[$field]) || trim((string)$data[$field]) === '') {
                http_response_code(400);
                echo json_encode([
                    "success" => false,
                    "message" => "กรุณากรอกข้อมูลให้ครบ"
                ]);
                exit;
            }
        }

        $empId = (int)$data["emp_id"];

        if (!empty($data["password"])) {
            $passwordHash = password_hash(
                $data["password"],
                PASSWORD_DEFAULT
            );

            $sql = "UPDATE employee
                    SET firstName = :firstName,
                        lastName = :lastName,
                        phone = :phone,
                        username = :username,
                        password = :password
                    WHERE emp_id = :id";
        } else {
            $sql = "UPDATE employee
                    SET firstName = :firstName,
                        lastName = :lastName,
                        phone = :phone,
                        username = :username
                    WHERE emp_id = :id";
        }

        $stmt = $conn->prepare($sql);

        $params = [
            ":firstName" => trim($data["firstName"]),
            ":lastName" => trim($data["lastName"]),
            ":phone" => trim($data["phone"]),
            ":username" => trim($data["username"]),
            ":id" => $empId
        ];

        if (!empty($data["password"])) {
            $params[":password"] = $passwordHash;
        }

        $stmt->execute($params);

        echo json_encode([
            "success" => true,
            "message" => "แก้ไขข้อมูลพนักงานเรียบร้อย"
        ]);
    }

    // ลบข้อมูลพนักงาน
    elseif ($method === "DELETE") {
        $data = json_decode(file_get_contents("php://input"), true);

        if (
            !is_array($data) ||
            !isset($data["emp_id"]) ||
            !is_numeric($data["emp_id"]) ||
            (int)$data["emp_id"] < 1
        ) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "รหัสพนักงานไม่ถูกต้อง"
            ]);
            exit;
        }

        $empId = (int)$data["emp_id"];

        $stmt = $conn->prepare(
            "DELETE FROM employee WHERE emp_id = :id"
        );

        $stmt->execute([":id" => $empId]);

        if ($stmt->rowCount() > 0) {
            echo json_encode([
                "success" => true,
                "message" => "ลบข้อมูลพนักงานเรียบร้อย"
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "message" => "ไม่พบข้อมูลพนักงานที่ต้องการลบ"
            ]);
        }
    }

    // Method อื่นที่ไม่รองรับ
    else {
        http_response_code(405);
        echo json_encode([
            "success" => false,
            "message" => "ไม่รองรับ HTTP Method นี้"
        ]);
    }

} catch (PDOException $e) {
    http_response_code(500);

    // บันทึกรายละเอียดข้อผิดพลาดไว้ใน PHP error log
    error_log($e->getMessage());

    echo json_encode([
        "success" => false,
        "message" => "เกิดข้อผิดพลาดในการเชื่อมต่อหรือจัดการฐานข้อมูล"
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    error_log($e->getMessage());

    echo json_encode([
        "success" => false,
        "message" => "เกิดข้อผิดพลาดภายในเซิร์ฟเวอร์"
    ]);
}
?>