<?php
    // var_dump($test);
    // foreach($test as $t):
    //     echo $t->inf;
    //     echo $t->sup;
    // endforeach;
?>
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
<table>
    <thead>
        <tr>
            <th>n</th>
            <th>Reference</th>
            <th>Nom</th>
            <th>Type</th>
            <th>Durée de location</th>
            <th>Date debut</th>
            <th>Date fin</th>
            <th>Loyer</th>
            <th>Commission</th>
            <th>Gain admin</th>
            <th>Gain proprio</th>
        </tr>
        <?php foreach($details as $d): ?>
            <tr>
                <td><?php echo $d->rang_mois; ?></td>
                <td><?php echo $d->reference; ?></td>
                <?php
                    $current_date = new DateTime(); // Assume this is the current date
                    // $d = new stdClass();/
                    // $d->datefin = new DateTime('2024-07-10'); // Example datefin
                    if (!($d->datedebut instanceof DateTime)) {
                        $d->datedebut = new DateTime($d->datedebut);
                    }
                    $datedebut_month = $d->datedebut->format('Y-m');
                    $current_month = $current_date->format('Y-m');
                    ?>
                    <td
                        <?php if($datedebut_month <= $current_month) { ?>
                            style="background-color:green;"
                        <?php } else { ?>
                            style="background-color:red;"
                        <?php } ?>
                ><?php echo $d->nom; ?></td>
                <td><?php echo $d->type; ?></td>
                <td><?php echo $d->duree; ?></td>
                <td><?php echo $d->datedebut->format('Y-m-d'); ?></td>
                <td><?php echo $d->datefin ?></td>
                <?php foreach($test as $t): ?>
                <td
                    <?php if($d->loyerparmois > $t->inf && $d->loyerparmois < $t->sup) { ?>
                        style="background-color:yellow;"
                    <?php } ?> 
                ><?php echo $d->loyerparmois; ?></td>
                <?php endforeach; ?>
                <td><?php echo $d->commission; ?> %</td>
                <td><?php echo $d->gain_admin; ?></td>
                <td><?php echo $d->gain_proprio; ?></td>

            </tr>
        <?php endforeach; ?>
    </thead>
</table>
<a href="<?php echo site_url('Welcome/admin'); ?>">Retour</a>
</body>
</html>