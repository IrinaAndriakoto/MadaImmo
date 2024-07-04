<div>
    <table>
        <tr>
            <th>Reference</th>
            <th>Type</th>
            <th>Proprietaire</th>
            <th>Bien</th>
            <th>Description</th>
            <th>Lieu</th>
            <th>Loyer par mois</th>
        </tr>
        <?php foreach($locs as $bien): ?>
        <tr>
            <td><?php echo $bien->reference; ?></td>
            <td><?php echo $bien->type; ?></td>
            <td><?php echo $bien->login; ?></td>
            <td><?php echo $bien->nom; ?></td>
            <td><?php echo $bien->description; ?></td>
            <td><?php echo $bien->region; ?></td>
            <td>Ar <?php echo $bien->loyer; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>