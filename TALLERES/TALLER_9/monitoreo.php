<?php
require_once "pdo/config_pdo.php";

function obtenerEstadisticasTabla($pdo, $tabla) {
    try {
        $sql = "SHOW TABLE STATUS LIKE :tabla";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':tabla' => $tabla]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function obtenerEstadisticasIndices($pdo, $tabla) {
    try {
        $sql = "SHOW INDEX FROM " . $tabla;
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function mostrarVariablesRendimiento($pdo) {
    try {
        $variables = [
            'innodb_buffer_pool_size',
            'key_buffer_size',
            'max_connections',
            'query_cache_size',
            'tmp_table_size',
            'max_heap_table_size'
        ];
        
        $sql = "SHOW VARIABLES WHERE Variable_name IN ('" . implode("','", $variables) . "')";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

// Mostrar estadísticas
$tablas = ['productos', 'ventas', 'detalles_venta', 'clientes'];

echo "<h2>Estadísticas de Tablas</h2>";
foreach ($tablas as $tabla) {
    echo "<h3>Tabla: $tabla</h3>";
    $tablaEstadisticas = obtenerEstadisticasTabla($pdo, $tabla);
    
    if (is_array($tablaEstadisticas)) {
        echo "<table border>";
        foreach ($tablaEstadisticas as $key => $value) {
            echo "<tr><td><strong>$key</strong></td><td>$value</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>$tablaEstadisticas</p>";
    }

    echo "<h4>Índices:</h4>";
    $indices = obtenerEstadisticasIndices($pdo, $tabla);
    if (is_array($indices) && count($indices) > 0) {
        echo "<table border>";
        echo "<tr><th>Key_name</th><th>Column_name</th><th>Non_unique</th><th>Seq_in_index</th><th>Index_type</th></tr>";
        foreach ($indices as $index) {
            echo "<tr>";
            echo "<td>{$index['Key_name']}</td>";
            echo "<td>{$index['Column_name']}</td>";
            echo "<td>{$index['Non_unique']}</td>";
            echo "<td>{$index['Seq_in_index']}</td>";
            echo "<td>{$index['Index_type']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No hay índices disponibles o error en la consulta.</p>";
    }
}

// Mostrar variables de rendimiento
echo "<h2>Variables de Rendimiento</h2>";
$variablesRendimiento = mostrarVariablesRendimiento($pdo);

if (is_array($variablesRendimiento) && count($variablesRendimiento) > 0) {
    echo "<table border>";
    echo "<tr><th>Variable</th><th>Valor</th></tr>";
    foreach ($variablesRendimiento as $variable) {
        echo "<tr>";
        echo "<td>{$variable['Variable_name']}</td>";
        echo "<td>{$variable['Value']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No se pudieron obtener las variables de rendimiento o error en la consulta.</p>";
}

$pdo = null;
?>