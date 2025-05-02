<?php
include 'config.php';

$id = $_GET['id'];

$conn->query("DELETE FROM complaints WHERE id = $id");
header("Location: view.php");
?>
