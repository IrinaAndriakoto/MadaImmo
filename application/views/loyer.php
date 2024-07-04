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
<div class="container">
        <h1>Loyer</h1>
        <form method="get" action="<?php echo site_url('Traitement/loyer'); ?>">
            <p>Start date : <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $start_date; ?>"></p>
            <p>End date : <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $end_date; ?>"></p>
            <button type="submit">Filter</button>
        </form>
    <p><strong>Total des loyers: </strong> Ar <?php echo number_format($total_loyer_sum, 2); ?></p>
        <table border="1">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Somme du loyer</th>
                    <th>Etat de paiement</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($revenues as $status): ?>
                    <tr>
                        <td><?php echo date('F Y', strtotime($status['month'])); ?></td>
                        <td>Ar <?php echo number_format($status['total_loyer'], 2); ?></td>
                        <td><?php echo $status['status']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="<?php echo site_url('Welcome/client'); ?>">Retour</a>
    </div>
</body>
</html>