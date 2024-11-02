<?php
require_once "config_pdo.php";

function mostrarResumenCategorias($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM vista_resumen_categorias");

        echo "<h3>Resumen por Categorías:</h3>";
        echo "<table border='1'>";
        echo "<tr>
                <th>Categoría</th>
                <th>Total Productos</th>
                <th>Stock Total</th>
                <th>Precio Promedio</th>
                <th>Precio Mínimo</th>
                <th>Precio Máximo</th>
              </tr>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$row['categoria']}</td>";
            echo "<td>{$row['total_productos']}</td>";
            echo "<td>{$row['total_stock']}</td>";
            echo "<td>{$row['precio_promedio']}</td>";
            echo "<td>{$row['precio_minimo']}</td>";
            echo "<td>{$row['precio_maximo']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function mostrarProductosPopulares($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM vista_productos_populares LIMIT 5");

        echo "<h3>Top 5 Productos Más Vendidos:</h3>";
        echo "<table border='1'>";
        echo "<tr>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Total Vendido</th>
                <th>Ingresos Totales</th>
                <th>Compradores Únicos</th>
              </tr>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$row['producto']}</td>";
            echo "<td>{$row['categoria']}</td>";
            echo "<td>{$row['total_vendido']}</td>";
            echo "<td>{$row['ingresos_totales']}</td>";
            echo "<td>{$row['compradores_unicos']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function mostrarProductosBajosStock($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM vista_bajo_stock");

        echo "<h3>Productos con Bajo Stock:</h3>";
        echo "<table border='1'>";
        echo "<tr>
                <th>Producto</th>
                <th>Stock</th>
                <th>Total Vendido</th>
                <th>Ingresos Totales</th>
              </tr>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$row['producto']}</td>";
            echo "<td>{$row['stock']}</td>";
            echo "<td>{$row['total_vendido']}</td>";
            echo "<td>{$row['ingresos_totales']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function MostrarHistorialVentas($pdo){
    try{ 
        $stmt = $pdo->query ("SELECT * FROM vista_historial_clientes");

        echo"<h3>Historial Completo de Clientes:</h3>";
        echo "<table border='1'>";
        echo "<tr>
                <th>Cliente</th>
                <th>Email</th>
                <th>Venta</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Monto Total</th>
              </tr>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$row['cliente']}</td>";
            echo "<td>{$row['email']}</td>";
            echo "<td>{$row['venta_id']}</td>";
            echo "<td>{$row['producto']}</td>";
            echo "<td>{$row['cantidad']}</td>"; 
            echo "<td>{$row['precio_unitario']}</td>";
            echo "<td>{$row['monto_total']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function mostrarRendimientoCategorias($pdo){
    try{
        $stmt = $pdo->query ("SELECT * FROM vista_rendimiento_categorias");
        echo "<h3>Rendimiento por Categoría:</h3>";
        echo "<table border='1'>";
        echo "<tr>
                <th>Categoría</th>
                <th>Ingresos Totales</th>
                <th>Ventas Totales</th>
                <th>Rendimiento</th>
              </tr>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$row['categoria']}</td>";
            echo "<td>{$row['ventas_totales']}</td>";
            echo "<td>{$row['total_vendido']}</td>";
            echo "<td>{$row['producto_mas_vendido']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function mostrarTendencias($pdo){
    try{
        $stmt = $pdo->query ("SELECT * FROM vista_tendencias_ventas");
        echo "<h3>Tendencias:</h3>";
        echo "<table border='1'>";
        echo "<tr>
            <th>Ventas totales</th>
            <th>Ingresos Totales</th>   
        </tr>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
        echo "<td>{$row['total_ventas']}</td>";    
        echo "<td>{$row['ingresos_totales']}</td>";
        echo "</tr>";
        }
        echo "</table>";

    }catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
// Mostrar los resultados
mostrarResumenCategorias($pdo);
mostrarProductosPopulares($pdo);
mostrarProductosBajosStock($pdo);
MostrarHistorialVentas($pdo);
mostrarRendimientoCategorias($pdo);
mostrarTendencias($pdo);

$pdo = null;

/**####Vistas en MySQL####
 * 
 *  ==Vista de Productos con Bajo Stock==
 * 
 * CREATE VIEW vista_bajo_stock AS
 * SELECT 
 * p.id AS producto_id,
 * p.nombre AS producto,
 * p.stock,
 * SUM(dv.cantidad) AS total_vendido,
 * SUM(dv.subtotal) AS ingresos_totales
 * FROM productos p
 * LEFT JOIN detalles_venta dv ON p.id = dv.producto_id
 * WHERE p.stock < 5
 * GROUP BY p.id;
 * 
 * ==Vista de Historial Completo de Clientes==
 * 
 * CREATE VIEW vista_historial_clientes AS
 * SELECT 
    c.id AS cliente_id,
    c.nombre AS cliente,
    c.email,
    v.id AS venta_id,
    v.fecha_venta,
    p.nombre AS producto,
    dv.cantidad,
    dv.precio_unitario,
    dv.subtotal AS monto_total
 * FROM clientes c
 * JOIN ventas v ON c.id = v.cliente_id
 * JOIN detalles_venta dv ON v.id = dv.venta_id
 * JOIN productos p ON dv.producto_id = p.id;
 * 
 * ==Vista de Métricas de Rendimiento por Categoría==
 * 
 * CREATE VIEW vista_rendimiento_categorias AS
 * SELECT 
    cat.nombre AS categoria,
    COUNT(p.id) AS total_productos,
    SUM(dv.cantidad) AS total_vendido,
    SUM(dv.subtotal) AS ventas_totales,
    MAX(p.nombre) AS producto_mas_vendido
 * FROM categorias cat
 * JOIN productos p ON cat.id = p.categoria_id
 * LEFT JOIN detalles_venta dv ON p.id = dv.producto_id
 * GROUP BY cat.id;
 * 
 * ==Vista de Tendencias de Ventas por Mes==
 * 
 * CREATE VIEW vista_tendencias_ventas AS
 * SELECT 
    DATE_FORMAT(v.fecha_venta, '%Y-%m') AS mes,
    COUNT(v.id) AS total_ventas,
    SUM(dv.subtotal) AS ingresos_totales
 * FROM ventas v
 * JOIN detalles_venta dv ON v.id = dv.venta_id
 * GROUP BY DATE_FORMAT(v.fecha_venta, '%Y-%m');
 */
?>