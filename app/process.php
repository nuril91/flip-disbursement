<?php
require_once __DIR__ . "/Main.php";
$db = new Main();

$action = $_GET['action'];

if ($action == 'disburse') {

    $data = [
        'bank_code' => $_POST['bank_code'],
        'account_number' => $_POST['account_number'],
        'amount' => $_POST['amount'],
        'remark' => $_POST['remark']
    ];

    $db->disburse($data);
}

if ($action == 'disburse_status') {
    $id = $_GET['id'];

    $db->disburseStatus($id);
}

header("location:../index.php");