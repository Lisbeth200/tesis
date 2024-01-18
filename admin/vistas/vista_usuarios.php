<!-- vista_usuarios.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Usuarios</title>
    <style>
        .selected {
            background-color: #d3d3d3;
        }
    </style>
</head>
<body>

<h1>Lista de Usuarios</h1>

<?php if (!empty($usuarios)) : ?>
    <table border="1" id="tablaUsuarios">
        <tr>
            <th>ID Usuario</th>
            <th>Sueldo Fijo</th>
        </tr>
        <?php foreach ($usuarios as $usuario) : ?>
            <tr onclick="seleccionarFila(this)">
                <td><?php echo $usuario->idusuario; ?></td>
                <td><?php echo $usuario->sueldo_fijo; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else : ?>
    <p>No hay usuarios disponibles.</p>
<?php endif; ?>

<script>
    function seleccionarFila(fila) {
        // Desmarcar todas las filas
        var filas = document.querySelectorAll('#tablaUsuarios tr');
        filas.forEach(function(f) {
            f.classList.remove('selected');
        });

        // Marcar la fila seleccionada
        fila.classList.add('selected');
    }
</script>

</body>
</html>
