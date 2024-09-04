<?php

$titulo = "Mascotas";
require 'template/header.php';
?>


<div class='dashboard-content'>
<input type="text" id="searchInput" placeholder="Buscar...">
<table id="dataTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Juan Perez</td>
            <td>juan@example.com</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Maria Lopez</td>
            <td>maria@example.com</td>
        </tr>
        <tr>
            <td>3</td>
            <td>amaru</td>
            <td>76595194amaru@gmail.com</td>
            <td>hols</td>
        </tr>
        <!-- Más registros aquí -->
    </tbody>
</table>
<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
    var input = this.value.toLowerCase();
    var rows = document.querySelectorAll('#dataTable tbody tr');

    rows.forEach(function(row) {
        var text = row.textContent.toLowerCase();
        if (text.indexOf(input) !== -1) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

</script>
</div>

<?php
require 'template/footer.php';
?>