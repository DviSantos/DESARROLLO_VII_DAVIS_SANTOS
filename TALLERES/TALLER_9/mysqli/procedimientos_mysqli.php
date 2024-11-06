<?php
require_once "config_mysqli.php";

$idCliente = 1;
$idProducto = 1;
$ventaId = 1;
$productoId=1;
$vendedorId=1;

// Función para registrar una venta
function registrarVenta($conn, $cliente_id, $producto_id, $cantidad) {
    $query = "CALL sp_registrar_venta(?, ?, ?, @venta_id)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iii", $cliente_id, $producto_id, $cantidad);
    
    try {
        mysqli_stmt_execute($stmt);
        
        // Obtener el ID de la venta
        $result = mysqli_query($conn, "SELECT @venta_id as venta_id");
        $row = mysqli_fetch_assoc($result);
        
        echo "Venta registrada con éxito. ID de venta: " . $row['venta_id'];
    } catch (Exception $e) {
        echo "Error al registrar la venta: " . $e->getMessage();
    }
    
    mysqli_stmt_close($stmt);
}

// Función para obtener estadísticas de cliente
function obtenerEstadisticasCliente($conn, $cliente_id) {
    $query = "CALL sp_estadisticas_cliente(?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $cliente_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $estadisticas = mysqli_fetch_assoc($result);
        
        echo "<h3>Estadísticas del Cliente</h3>";
        echo "Nombre: " . $estadisticas['nombre'] . "<br>";
        echo "Membresía: " . $estadisticas['nivel_membresia'] . "<br>";
        echo "Total compras: " . $estadisticas['total_compras'] . "<br>";
        echo "Total gastado: $" . $estadisticas['total_gastado'] . "<br>";
        echo "Promedio de compra: $" . $estadisticas['promedio_compra'] . "<br>";
        echo "Últimos productos: " . $estadisticas['ultimos_productos'] . "<br>";
    }
    
    mysqli_stmt_close($stmt);
}

// Función para procesar una devolución de producto
function procesarDevolucion($conn, $venta_id, $producto_id, $cantidad_devuelta) {
    $query = "CALL procesar_devolucion(?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iii", $venta_id, $producto_id, $cantidad_devuelta);
    
    try {
        mysqli_stmt_execute($stmt);
        echo "Devolución procesada con éxito.";
    } catch (Exception $e) {
        echo "Error al procesar la devolución: " . $e->getMessage();
    }
    
    mysqli_stmt_close($stmt);
}

// Función para aplicar descuentos basados en el historial de compras del cliente
function aplicarDescuento($conn, $cliente_id, $monto_compra) {
    $query = "CALL aplicar_descuento(?, ?, @monto_final)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "id", $cliente_id, $monto_compra);
    
    try {
        mysqli_stmt_execute($stmt);
        
        // Obtener el monto final con descuento
        $result = mysqli_query($conn, "SELECT @monto_final AS monto_final");
        $row = mysqli_fetch_assoc($result);
        
        echo "Monto final con descuento: $" . $row['monto_final'];
    } catch (Exception $e) {
        echo "Error al aplicar descuento: " . $e->getMessage();
    }
    
    mysqli_stmt_close($stmt);
}

// Función para reporte de productos con bajo stock
function reporteBajoStock($conn) {
    $query = "CALL reporte_bajo_stock()";
    $stmt = mysqli_prepare($conn, $query);
    
    try {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "Producto: {$row['nombre']}, Stock: {$row['stock']}, Sugerido para reposición: {$row['sugerido_reposicion']}<br>";
        }
    } catch (Exception $e) {
        echo "Error al generar el reporte: " . $e->getMessage();
    }
    
    mysqli_stmt_close($stmt);
}

// Función para calcular comisiones por ventas
function calcularComisiones($conn, $vendedor_id) {
    $query = "CALL calcular_comisiones(?, @comision_total)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $vendedor_id);
    
    try {
        mysqli_stmt_execute($stmt);
        
        // Obtener la comisión total calculada
        $result = mysqli_query($conn, "SELECT @comision_total AS comision_total");
        $row = mysqli_fetch_assoc($result);
        
        echo "Comisión total: $" . $row['comision_total'];
    } catch (Exception $e) {
        echo "Error al calcular comisión: " . $e->getMessage();
    }
    
    mysqli_stmt_close($stmt);
}

// Ejemplos de uso
registrarVenta($conn, $idCliente, $idProducto, 2);
obtenerEstadisticasCliente($conn, $idCliente);
procesarDevolucion($conn, $ventaId, $productoId, 1);
aplicarDescuento($conn, $idCliente, 100);
reporteBajoStock($conn);
calcularComisiones($conn, $vendedorId);

mysqli_close($conn);

/*** Procedimientos:
 * 
 * DELIMITER //

CREATE PROCEDURE procesar_devolucion(
    IN p_venta_id INT,
    IN p_producto_id INT,
    IN p_cantidad_devuelta INT
)
BEGIN
    DECLARE stock_actual INT;

    START TRANSACTION;

    -- Obtener el stock actual
    SELECT stock INTO stock_actual FROM productos WHERE id = p_producto_id;

    -- Actualizar el stock sumando la cantidad devuelta
    UPDATE productos SET stock = stock_actual + p_cantidad_devuelta WHERE id = p_producto_id;

    -- Cambiar el estado de la venta a 'devuelto'
    UPDATE ventas SET estado = 'devuelto' WHERE id = p_venta_id;

    COMMIT;
END //

CREATE PROCEDURE aplicar_descuento(
    IN p_cliente_id INT,
    IN p_monto_compra DECIMAL(10,2),
    OUT p_monto_final DECIMAL(10,2)
)
BEGIN
    DECLARE descuento DECIMAL(5,2);

    -- Calcular descuento basado en el historial
    SELECT CASE
        WHEN SUM(total) > 1000 THEN 0.10
        WHEN SUM(total) BETWEEN 500 AND 1000 THEN 0.05
        ELSE 0.0
    END INTO descuento
    FROM ventas WHERE cliente_id = p_cliente_id;

    -- Aplicar descuento y devolver el monto final
    SET p_monto_final = p_monto_compra - (p_monto_compra * descuento);
END //

CREATE PROCEDURE reporte_bajo_stock()
BEGIN
    SELECT 
        nombre,
        stock,
        CASE 
            WHEN stock < 5 THEN 10 - stock
            ELSE 0 
        END AS sugerido_reposicion
    FROM productos
    WHERE stock < 5;
END //

CREATE PROCEDURE calcular_comisiones(
    IN p_vendedor_id INT,
    OUT p_comision_total DECIMAL(10,2)
)
BEGIN
    DECLARE comision DECIMAL(5,2);

    SELECT SUM(total) * 0.05 INTO p_comision_total 
    FROM ventas
    WHERE cliente_id = p_vendedor_id AND estado = 'completada';
END //

DELIMITER ; */
?>