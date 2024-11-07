<?php
require_once "config_pdo.php"; // O usar mysqli según prefieras

function verificarCambiosPrecio($pdo, $producto_id, $nuevo_precio) {
    try {
        // Actualizar precio
        $stmt = $pdo->prepare("UPDATE productos SET precio = ? WHERE id = ?");
        $stmt->execute([$nuevo_precio, $producto_id]);
        
        // Verificar log de cambios
        $stmt = $pdo->prepare("SELECT * FROM historial_precios WHERE producto_id = ? ORDER BY fecha_cambio DESC LIMIT 1");
        $stmt->execute([$producto_id]);
        $log = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<h3>Cambio de Precio Registrado:</h3>";
        echo "Precio anterior: $" . $log['precio_anterior'] . "<br>";
        echo "Precio nuevo: $" . $log['precio_nuevo'] . "<br>";
        echo "Fecha del cambio: " . $log['fecha_cambio'] . "<br>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function verificarMovimientoInventario($pdo, $producto_id, $nueva_cantidad) {
    try {
        // Actualizar stock
        $stmt = $pdo->prepare("UPDATE productos SET stock = ? WHERE id = ?");
        $stmt->execute([$nueva_cantidad, $producto_id]);
        
        // Verificar movimientos de inventario
        $stmt = $pdo->prepare("
            SELECT * FROM movimientos_inventario 
            WHERE producto_id = ? 
            ORDER BY fecha_movimiento DESC LIMIT 1
        ");
        $stmt->execute([$producto_id]);
        $movimiento = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<h3>Movimiento de Inventario Registrado:</h3>";
        echo "Tipo de movimiento: " . $movimiento['tipo_movimiento'] . "<br>";
        echo "Cantidad: " . $movimiento['cantidad'] . "<br>";
        echo "Stock anterior: " . $movimiento['stock_anterior'] . "<br>";
        echo "Stock nuevo: " . $movimiento['stock_nuevo'] . "<br>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

//funciion para actualizar menbresia del cliente
function verificarMembresiaCliente($pdo, $cliente_id) {
    $stmt = $pdo->prepare("SELECT nivel_membresia FROM clientes WHERE id = ?");
    $stmt->execute([$cliente_id]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Nivel de Membresía Actual: " . $cliente['nivel_membresia'];
}

//funcion para actualizar ventas por categorias
function verificarEstadisticasCategoria($pdo, $categoria_id) {
    $stmt = $pdo->prepare("SELECT total_ventas FROM estadisticas_ventas_categoria WHERE categoria_id = ?");
    $stmt->execute([$categoria_id]);
    $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Total de Ventas en la Categoría: $" . $categoria['total_ventas'];
}

//funcion que veirifica envio de alerta de stock creiticos
function verificarAlertaStock($pdo, $producto_id) {
    $stmt = $pdo->prepare("SELECT mensaje FROM alertas_stock WHERE producto_id = ? ORDER BY fecha_alerta DESC LIMIT 1");
    $stmt->execute([$producto_id]);
    $alerta = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Alerta de Stock: " . $alerta['mensaje'];
}

//funcion para enviar historial de estado del cliente
function verificarHistorialEstadoCliente($pdo, $cliente_id) {
    $stmt = $pdo->prepare("SELECT estado_anterior, estado_nuevo, fecha_cambio FROM historial_estado_cliente WHERE cliente_id = ? ORDER BY fecha_cambio DESC LIMIT 1");
    $stmt->execute([$cliente_id]);
    $historial = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Estado Cambiado: De " . $historial['estado_anterior'] . " a " . $historial['estado_nuevo'] . " en " . $historial['fecha_cambio'];
}
// Probar los triggers
verificarCambiosPrecio($pdo, 1, 999.99);
verificarMovimientoInventario($pdo, 1, 15);
verificarMembresiaCliente($pdo, 1);
verificarEstadisticasCategoria($pdo, 1);
verificarAlertaStock($pdo, 1);
verificarHistorialEstadoCliente($pdo, 1);
$pdo = null;

/**-- ### Practica triggers###
DELIMITER //

CREATE TRIGGER tr_actualizar_membresia_cliente
AFTER INSERT ON ventas
FOR EACH ROW
BEGIN
    DECLARE total_compras DECIMAL(10,2);

    -- Calcula el total de compras del cliente
    SELECT SUM(total) INTO total_compras
    FROM ventas
    WHERE cliente_id = NEW.cliente_id;

    -- Actualiza el nivel de membresía según el total de compras
    IF total_compras > 5000 THEN
        UPDATE clientes SET nivel_membresia = 'Premium' WHERE id = NEW.cliente_id;
    ELSEIF total_compras > 1000 THEN
        UPDATE clientes SET nivel_membresia = 'Gold' WHERE id = NEW.cliente_id;
    ELSE
        UPDATE clientes SET nivel_membresia = 'Standard' WHERE id = NEW.cliente_id;
    END IF;
END //

CREATE TRIGGER tr_actualizar_estadisticas_categoria
AFTER INSERT ON ventas
FOR EACH ROW
BEGIN
    DECLARE categoria_id INT;
    
    -- Obtén el id de la categoría desde la tabla detalles_venta
    SELECT p.categoria_id INTO categoria_id
    FROM productos p
    JOIN detalles_venta dv ON p.id = dv.producto_id
    WHERE dv.venta_id = NEW.id;
    
    -- Actualiza el total de ventas en la tabla de estadísticas
    INSERT INTO estadisticas_ventas_categoria (categoria_id, total_ventas)
    VALUES (categoria_id, NEW.total)
    ON DUPLICATE KEY UPDATE total_ventas = total_ventas + NEW.total;
END //

CREATE TRIGGER tr_alerta_stock_critico
AFTER UPDATE ON productos
FOR EACH ROW
BEGIN
    IF NEW.stock < 5 THEN
        INSERT INTO alertas_stock (producto_id, mensaje, fecha_alerta)
        VALUES (NEW.id, 'Stock crítico: menos de 5 unidades disponibles', NOW());
    END IF;
END //

CREATE TRIGGER tr_historial_estado_cliente
AFTER UPDATE ON clientes
FOR EACH ROW
BEGIN
    IF OLD.estado != NEW.estado THEN
        INSERT INTO historial_estado_cliente (cliente_id, estado_anterior, estado_nuevo, fecha_cambio)
        VALUES (NEW.id, OLD.estado, NEW.estado, NOW());
    END IF;
END 

DELIMITER ; */
?>