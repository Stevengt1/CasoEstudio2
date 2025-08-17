<?php
session_start();
require_once 'include/conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id_pago = $input['id_pago'] ?? null;
    $response = ["success" => false, "message" => "ID de pago no recibido."];

    if ($id_pago) {
        $stmt = $mysqli->prepare("DELETE FROM pagos WHERE id_pago = ?");
        $stmt->bind_param("i", $id_pago);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $response = ["success" => true, "message" => "Pago eliminado correctamente."];
        } else {
            $response["message"] = "No se pudo eliminar el pago.";
        }
        $stmt->close();
    }
    $mysqli->close();
    echo json_encode($response);
    exit();
}

echo json_encode(["success" => false, "message" => "Método no permitido."]);
exit();
