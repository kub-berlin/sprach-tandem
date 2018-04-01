<p>
    <a href="index.php?action=table&lang=<?php e($label['lang']) ?>" class="button">
        <?php icon('menu') ?>
        Tabelle
    </a>
</p>

<h3>Statistik</h3>

<p><a href="index.php?action=stat&t=year&lang=<?php e($label['lang']) ?>">dieses Jahr</a></p>
<p><a href="index.php?action=stat&t=always&lang=<?php e($label['lang']) ?>">Immer</a></p>

<p>folgende Sprach-Tandem Anzeigen wurden eingetragen:</p>

<table>
    <thead>
        <tr>
            <th>Angebotene Sprache</th>
            <th>Gesuchte Sprache</th>
            <th>Anzahl der Anzeigen</th>
            <th>Antworten</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($db_erg as $index => $zeile) : ?>
            <tr>
                <td><?php e($label[$zeile[$GLOBALS['db_colName_spracheAng']]]) ?></td>
                <td><?php e($label[$zeile[$GLOBALS['db_colName_spracheGes']]]) ?></td>
                <td><?php e($zeile['count']) ?></td>
                <td><?php e($replies[$index]) ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<p>Insgesamt wurden <?php e($sum_count) ?> Anzeigen aufgegeben und <?php e($sum_replies) ?> mal geantwortet</p>
