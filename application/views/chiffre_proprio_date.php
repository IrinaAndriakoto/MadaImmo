<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/main.css'); ?>" />
    <script src="<?php echo base_url('assets/js/main.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/chart.js'); ?>"></script>
	<link rel="shortcut icon" href="<?php echo base_url('assets/img/logo.png'); ?>" type="image/x-icon">
</head>
<body>
<?php if (isset($message)): ?>
    <p><?php echo $message; ?></p>
<?php else: ?>
    <p><strong>Total des loyers: </strong> Ar <?php echo number_format($total_loyer_sum, 2); ?></p>
    <p><strong>Total des gains: </strong> Ar <?php echo number_format($argent_encaisse, 2); ?></p>
    <table>
        <thead>
            <tr>
                <th>Mois</th>
                <th>Total locations</th>
                <th>Loyer</th>
                <th>Revenue par mois</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($revenues as $revenue): ?>
            <tr>
                <td><?php echo date('F Y', strtotime($revenue['month'])); ?></td>
                <td><?php echo $revenue['total_locations']; ?></td>
                <td>Ar <?php echo number_format($revenue['total_loyer'], 2); ?></td>
                <td>Ar <?php echo number_format($revenue['argent_encaisse'], 2); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<a href="<?php echo site_url('Welcome/proprio'); ?>"><button>Retourner a la page d'accueil.</button></a>
</body>
</html>