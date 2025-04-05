<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Путевой лист №<?= htmlspecialchars($waybill['waybill_number']) ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin-bottom: 5px; }
        .header .subtitle { font-size: 14px; color: #555; }
        .info-block { margin-bottom: 30px; }
        .info-block h2 { font-size: 16px; border-bottom: 1px solid #000; padding-bottom: 5px; }
        .info-row { display: flex; margin-bottom: 5px; }
        .info-label { width: 200px; font-weight: bold; }
        .info-value { flex-grow: 1; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .signatures { display: flex; justify-content: space-between; margin-top: 50px; }
        .signature { width: 250px; border-top: 1px solid #000; padding-top: 5px; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="header">
        <h1>ПУТЕВОЙ ЛИСТ №<?= htmlspecialchars($waybill['waybill_number']) ?></h1>
        <div class="subtitle">от <?= date('d.m.Y', strtotime($waybill['date'])) ?></div>
    </div>
    
    <div class="info-block">
        <h2>1. Общая информация</h2>
        <div class="info-row">
            <div class="info-label">Автомобиль:</div>
            <div class="info-value"><?= htmlspecialchars($waybill['license_plate']) ?> (<?= htmlspecialchars($waybill['model']) ?>)</div>
        </div>
        <div class="info-row">
            <div class="info-label">Водитель:</div>
            <div class="info-value"><?= htmlspecialchars($waybill['full_name']) ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Статус:</div>
            <div class="info-value">
                <?php 
                $status_text = [
                    'planned' => 'Запланирован',
                    'in_progress' => 'В процессе',
                    'completed' => 'Завершен'
                ];
                echo $status_text[$waybill['status']] ?? $waybill['status'];
                ?>
            </div>
        </div>
        <?php if ($waybill['departure_time']): ?>
        <div class="info-row">
            <div class="info-label">Время выезда:</div>
            <div class="info-value"><?= date('H:i', strtotime($waybill['departure_time'])) ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Время возврата:</div>
            <div class="info-value"><?= $waybill['return_time'] ? date('H:i', strtotime($waybill['return_time'])) : 'еще не вернулся' ?></div>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="info-block">
        <h2>2. Товарная часть</h2>
        <table>
            <thead>
                <tr>
                    <th>№</th>
                    <th>Наименование заказчика</th>
                    <th>ИНН заказчика</th>
                    <th>Адрес</th>
                    <th>Наименование продукции</th>
                    <th>Ед. измер.</th>
                    <th>Количество</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $index => $item): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($item['client_name']) ?></td>
                    <td><?= htmlspecialchars($item['inn']) ?></td>
                    <td><?= htmlspecialchars($item['address']) ?></td>
                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                    <td><?= htmlspecialchars($item['unit_of_measure']) ?></td>
                    <td><?= number_format($item['quantity'], 3, ',', ' ') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="signatures">
        <div class="signature">
            Водитель: ___________________
        </div>
        <div class="signature">
            Экспедитор: ___________________
        </div>
    </div>
    
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 200);
        };
    </script>
</body>
</html>