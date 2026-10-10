<?php
require_once __DIR__ . '/condb.php';

header("Content-Type: application/json; charset=UTF-8");

function respond(int $statusCode, array $body): void
{
    http_response_code($statusCode);
    echo json_encode($body, JSON_UNESCAPED_UNICODE);
}

function readRequestData(): ?array
{
    $data = json_decode(file_get_contents("php://input"), true);
    return is_array($data) ? $data : null;
}

try {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === "OPTIONS") {
        http_response_code(204);
        exit;
    }

    if ($method === "GET") {
        $stmt = $conn->query(
            "SELECT id AS contact_id, subject, detail, fullname, email, created_at
             FROM contacts
             ORDER BY id DESC"
        );

        respond(200, [
            "success" => true,
            "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)
        ]);
    } elseif ($method === "POST") {
        $data = readRequestData();

        if ($data === null) {
            respond(400, [
                "success" => false,
                "message" => "ข้อมูล JSON ไม่ถูกต้อง"
            ]);
            exit;
        }

        $required = ["subject", "detail", "fullname", "email"];
        foreach ($required as $field) {
            if (!isset($data[$field]) || trim((string)$data[$field]) === '') {
                respond(400, [
                    "success" => false,
                    "message" => "กรุณากรอกข้อมูลให้ครบ"
                ]);
                exit;
            }
        }

        $stmt = $conn->prepare(
            "INSERT INTO contacts (subject, detail, fullname, email)
             VALUES (:subject, :detail, :fullname, :email)"
        );
        $stmt->execute([
            ":subject" => trim($data["subject"]),
            ":detail" => trim($data["detail"]),
            ":fullname" => trim($data["fullname"]),
            ":email" => trim($data["email"])
        ]);

        respond(201, [
            "success" => true,
            "message" => "เพิ่มข้อมูลเรียบร้อย"
        ]);
    } elseif ($method === "PUT") {
        $data = readRequestData();

        if (
            $data === null ||
            !isset($data["contact_id"]) ||
            !is_numeric($data["contact_id"]) ||
            (int)$data["contact_id"] < 1
        ) {
            respond(400, [
                "success" => false,
                "message" => "รหัสการติดต่อไม่ถูกต้อง"
            ]);
            exit;
        }

        $required = ["subject", "detail", "fullname", "email"];
        foreach ($required as $field) {
            if (!isset($data[$field]) || trim((string)$data[$field]) === '') {
                respond(400, [
                    "success" => false,
                    "message" => "กรุณากรอกข้อมูลให้ครบ"
                ]);
                exit;
            }
        }

        $stmt = $conn->prepare(
            "UPDATE contacts
             SET subject = :subject,
                 detail = :detail,
                 fullname = :fullname,
                 email = :email
             WHERE id = :id"
        );
        $stmt->execute([
            ":subject" => trim($data["subject"]),
            ":detail" => trim($data["detail"]),
            ":fullname" => trim($data["fullname"]),
            ":email" => trim($data["email"]),
            ":id" => (int)$data["contact_id"]
        ]);

        if ($stmt->rowCount() === 0) {
            $exists = $conn->prepare("SELECT 1 FROM contacts WHERE id = :id");
            $exists->execute([":id" => (int)$data["contact_id"]]);

            if (!$exists->fetchColumn()) {
                respond(404, [
                    "success" => false,
                    "message" => "ไม่พบข้อมูลการติดต่อที่ต้องการแก้ไข"
                ]);
                exit;
            }
        }

        respond(200, [
            "success" => true,
            "message" => "แก้ไขข้อมูลเรียบร้อย"
        ]);
    } elseif ($method === "DELETE") {
        $data = readRequestData();

        if (
            $data === null ||
            !isset($data["contact_id"]) ||
            !is_numeric($data["contact_id"]) ||
            (int)$data["contact_id"] < 1
        ) {
            respond(400, [
                "success" => false,
                "message" => "รหัสการติดต่อไม่ถูกต้อง"
            ]);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM contacts WHERE id = :id");
        $stmt->execute([":id" => (int)$data["contact_id"]]);

        if ($stmt->rowCount() === 0) {
            respond(404, [
                "success" => false,
                "message" => "ไม่พบข้อมูลการติดต่อที่ต้องการลบ"
            ]);
            exit;
        }

        respond(200, [
            "success" => true,
            "message" => "ลบข้อมูลเรียบร้อย"
        ]);
    } else {
        header("Allow: GET, POST, PUT, DELETE, OPTIONS");
        respond(405, [
            "success" => false,
            "message" => "ไม่รองรับ HTTP Method นี้"
        ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    error_log($e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => "เกิดข้อผิดพลาดในการเชื่อมต่อหรือจัดการฐานข้อมูล"
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    error_log($e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => "เกิดข้อผิดพลาดภายในเซิร์ฟเวอร์"
    ], JSON_UNESCAPED_UNICODE);
}
?>
