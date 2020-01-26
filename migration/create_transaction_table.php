<?php
include "app/Main.php";
$conn = (new Main())->__construct();

// sql to create table
$sql = "CREATE TABLE transaction (
id INT(11) UNSIGNED PRIMARY KEY,
amount INT(11) NOT NULL,
status VARCHAR(30) NOT NULL,
timestamp TIMESTAMP NULL,
bank_code VARCHAR(10) NOT NULL,
account_number VARCHAR(30) NOT NULL,
beneficiary_name VARCHAR(30) NOT NULL,
remark VARCHAR(100) NULL,
receipt VARCHAR(256) NULL,
time_served VARCHAR(50) NULL,
fee INT(11) NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table Transaction created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();