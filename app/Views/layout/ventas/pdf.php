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
            background-color: #f5f5f5;
            padding: 20px;
        }
        .invoice-header img {
            max-width: 150px;
            width: 150px;
            height: 100px;
            border-radius: 50%;
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
    <p><strong>Factura de venta #</strong> <?= $venta['id'] ?></p>
        <p><strong><?= $empresa['nombre'] ?></strong></p>
        <p>Nit: <?= $empresa['nit'] ?></p>
        <p>Dirección: <?= $empresa['direccion'] ?></p>
        <p>Ciudad: <?= $empresa['ciudad'] ?></p>
        <p>Teléfono: <?= $empresa['telefono'] ?></p>
        <p>Email: <?= $empresa['email'] ?></p>
    </div>
    
    <div class="invoice-details">
        <p><strong>Cliente:</strong> <?= $cliente['nombre'] ?> <?= $cliente['apellidos'] ?></p>
        <p><strong>Cédula:</strong> <?= $cliente['cedula'] ?></p>
        <p><strong>Teléfono:</strong> <?= $cliente['telefono'] ?></p>
    </div>

    <h4>Servicios y Productos</h4>
    <table>
        <thead>
            <tr>
                <th>Servicios/Productos</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Descuento</th>
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
                    <td><?= $data['producto_nombre'] ?></td>
                    <td><?= $data['cantidad'] ?></td>
                    <td>$<?= number_format($data['precio_unitario'], 2) ?></td>
                    <td>$<?= number_format($data['descuento'], 2) ?></td>
                    <td>$<?= number_format($data['valor_total'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="table-header">
                <td colspan="4" style="text-align: right;"><strong>Total pagado</strong></td>
                <td>$<?= number_format($precio_total, 2) ?></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
