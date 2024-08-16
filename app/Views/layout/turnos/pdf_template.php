<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura Beauty Timeless</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .invoice-header {
            text-align: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
            background-color: #f5f5f5; /* Color de fondo agradable */
            padding: 20px;
        }
     
        .invoice-header img {
            max-width: 150px; /* Ajusta el tamaño máximo de la imagen */
            width: 150px; /* Ajusta el tamaño deseado */
            height: 100px; /* Ajusta el tamaño deseado */
            border-radius: 50%; /* Bordes redondeados */
            margin-bottom: 10px;
        }
        .invoice-header h1 {
            margin: 10px 0;
        }
        .invoice-header p {
            margin: 2px 0;
        }
        .invoice-details p {
            margin: 2px 0;
        }
        .invoice-details {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            /* margin-bottom: 20px; */
            /* padding: 20px; */
            /* text-align: center; */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .table-header {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <img src="<?php echo base_url(); ?>assets/images/logo.jpg" alt="Logo">
        <p>Fecha: <?= date('d/m/Y') ?></p>
        <p><strong>Factura de servicios # </strong><?php echo $turno['id']?></p>
        <p><strong><?= $empresa[0]['nombre'] ?></strong></p>
        <p>Nit: <?= $empresa[0]['nit'] ?></p>
        <p>Dirección: <?= $empresa[0]['direccion'] ?></p>
        <p>Ciudad: <?= $empresa[0]['ciudad'] ?></p>
        <p>Teléfono: <?= $empresa[0]['telefono'] ?></p>
        <p>Email: <?= $empresa[0]['email'] ?></p>
    </div>
    
    <div class="invoice-details">
        <p><strong>Cliente:</strong> <?= $turno['nombre'] ?> <?= $turno['apellidos'] ?></p>
        <p><strong>Cédula:</strong> <?= $turno['cedula'] ?></p>
        <p><strong>Teléfono:</strong> <?= $turno['telefono'] ?></p>

    </div>
    <h4>Servicios y Productos</h4>
    <table>
        <thead>
            <tr>
                <th>Servicios/Productos</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($servicios as $servicio): ?>
                <tr>
                    <td><?= $servicio['nombre_servicio'] ?></td>
                    <td>1</td>
                    <td>$<?= number_format($servicio['precio_servicio'], 2) ?></td>
                    <td>$<?= number_format($servicio['precio_servicio'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php foreach ($productos_totales as $nombre_producto => $data): ?>
                <tr>
                    <td><?= $nombre_producto ?></td>
                    <td><?= $data['cantidad'] ?></td>
                    <td>$<?= number_format($data['precio_unitario'], 2) ?></td>
                    <td>$<?= number_format($data['subtotal'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="table-header">
                <td colspan="3" style="text-align: right;"><strong>Total pagado</strong></td>
                <td>$<?= number_format($precio_total, 2) ?></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
