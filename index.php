<?php
include 'app/Main.php';
$config = include 'config.php';

$db = new Main();
?>

<html>
    <head>
        <title>Penarikan Dana</title>
    </head>
    <body>
    <h3>Penarikan Dana</h3>
        <form action="app/process.php?action=disburse" method="post">
            <table>
                <tr>
                    <td>Bank</td>
                    <td>
                        <select name="bank_code" required>
                            <option value="">--Pilih Bank</option>
                            <?php
                            $banks = $config['banks'];
                            foreach ($banks as $bank) {
                                echo "<option value='$bank'>$bank</option>";
                            } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Nomor Rekening</td>
                    <td><input type='number' min="1" name='account_number' required></td>
                </tr>
                <tr>
                    <td>Jumlah Dana</td>
                    <td><input type='number' min="1" name='amount' required></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='hidden' name='action' value='create' />
                        <input type='hidden' name='remark' value='sample remark' />
                        <input type='submit' value='Save' />
                    </td>
                </tr>
            </table>
        </form>

        <?php
            if ($db->show()) {
                echo "<table border='1'>
                    <tr>
                        <th>No</th>
                        <th>Bank</th>
                        <th>Nomor Rekening</th>
                        <th>Resi</th>
                        <th>Jumlah Dana</th>
                        <th>Tanggal Berhasil</th>
                        <th>Status</th>
                        <th></th>
        
                    </tr>";
                    $no = 1;
                    foreach ($db->show() as $data) {
                        echo "<tr>
                        <td>$no</td>
                        <td>$data[bank_code]</td>
                        <td>$data[account_number]</td>
                        <td>$data[receipt]</td>
                        <td>$data[amount]</td>
                        <td>$data[time_served]</td>
                        <td>$data[status]</td>
                        <td><a href='app/process.php?action=disburse_status&id=$data[id]'>Update Status</a></td>
                    </tr>";
                    $no++;
                }
                echo "</table>";
            }
        ?>
    </body>
</html>