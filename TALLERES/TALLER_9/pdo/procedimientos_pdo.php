<?php
require_once "config_pdo.php";

$idCliente = 1;
$idProducto = 1;
$ventaId = 1;
$productoId=1;
$vendedorId=1;

// Función para registrar una venta
function registrarVenta($pdo, $cliente_id, $producto_id, $cantidad) {
    try {
        $stmt = $pdo->prepare("CALL sp_registrar_venta(:cliente_id, :producto_id, :cantidad, @venta_id)");
        $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->execute();
        
        // Obtener el ID de la venta
        $result = $pdo->query("SELECT @venta_id as venta_id")->fetch(PDO::FETCH_ASSOC);
        
        echo "Venta registrada con éxito. ID de venta: " . $result['venta_id'];
    } catch (PDOException $e) {
        echo "Error al registrar la venta: " . $e->getMessage();
    }
}

// Función para obtener estadísticas de cliente
function obtenerEstadisticasCliente($pdo, $cliente_id) {
    try {
        $stmt = $pdo->prepare("CALL sp_estadisticas_cliente(:cliente_id)");
        $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $estadisticas = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<h3>Estadísticas del Cliente</h3>";
        echo "Nombre: " . $estadisticas['nombre'] . "<br>";
        echo "Membresía: " . $estadisticas['nivel_membresia'] . "<br>";
        echo "Total compras: " . $estadisticas['total_compras'] . "<br>";
        echo "Total gastado: $" . $estadisticas['total_gastado'] . "<br>";
        echo "Promedio de compra: $" . $estadisticas['promedio_compra'] . "<br>";
        echo "Últimos productos: " . $estadisticas['ultimos_productos'] . "<br>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}


//funcion para procesar una devolución de producto
function procesarDevolucion($pdo, $venta_id, $producto_id, $cantidad_devuelta) {
    try {
        $stmt = $pdo->prepare("CALL procesar_devolucion(:venta_id, :producto_id, :cantidad_devuelta)");
        $stmt->bindParam(':venta_id', $venta_id, PDO::PARAM_INT);
        $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
        $stmt->bindParam(':cantidad_devuelta', $cantidad_devuelta, PDO::PARAM_INT);
        
        $stmt->execute();
        echo "Devolución procesada con éxito.";
    } catch (PDOException $e) {
        echo "Error al procesar la devolución: " . $e->getMessage();
    }
}

//funcion para aplicar descuentos basados en el historial de compras del cliente
function aplicarDescuento($pdo, $cliente_id, $monto_compra) {
    try {
        $stmt = $pdo->prepare("CALL aplicar_descuento(:cliente_id, :monto_compra, @monto_final)");
        $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $stmt->bindParam(':monto_compra', $monto_compra);

        $stmt->execute();
        
        // Obtener el monto final con descuento
        $result = $pdo->query("SELECT @monto_final AS monto_final");
        $row = $result->fetch(PDO::FETCH_ASSOC);
        
        echo "Monto final con descuento: $" . $row['monto_final'];
    } catch (PDOException $e) {
        echo "Error al aplicar descuento: " . $e->getMessage();
    }
}

// funcion para reporte de productos con bajo stock
function reporteBajoStock($pdo) {
    try {
        $stmt = $pdo->prepare("CALL reporte_bajo_stock()");
        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($result as $row) {
            echo "Producto: {$row['nombre']}, Stock: {$row['stock']}, Sugerido para reposición: {$row['sugerido_reposicion']}<br>";
        }
    } catch (PDOException $e) {
        echo "Error al generar el reporte: " . $e->getMessage();
    }
}

//funcion para calcular comisiones por ventas
function calcularComisiones($pdo, $vendedor_id) {
    try {
        $stmt = $pdo->prepare("CALL calcular_comisiones(:vendedor_id, @comision_total)");
        $stmt->bindParam(':vendedor_id', $vendedor_id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Obtener la comisión total calculada
        $result = $pdo->query("SELECT @comision_total AS comision_total");
        $row = $result->fetch(PDO::FETCH_ASSOC);
        
        echo "Comisión total: $" . $row['comision_total'];
    } catch (PDOException $e) {
        echo "Error al calcular comisión: " . $e->getMessage();
    }
}

// Ejemplos de uso
registrarVenta($pdo, $idCliente, $idCliente, 2);
obtenerEstadisticasCliente($pdo, $idCliente);
procesarDevolucion($pdo, $ventaId, $productoId, 1);
aplicarDescuento($pdo, $idCliente, 100);
reporteBajoStock($pdo);
calcularComisiones($pdo, $vendedorId);

$pdo = null;
?>