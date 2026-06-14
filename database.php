<?php
try {
    $conn = new mysqli("localhost", "root", "", "pixel_playground", 3307);
} catch (Exception $e) {
    die("Fout bij verbinden met database: " . $e->getMessage());
}
?>
