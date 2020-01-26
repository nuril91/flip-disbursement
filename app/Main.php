<?php


class Main
{

    private $db;
    private $host;
    private $username;
    private $password;
    private $conn;
    private $config;

    public function __construct()
    {
        $config = include 'config.php';
        !$config ? $config = include '../config.php' : include 'config.php';

        $this->db = $config['database']['db'];
        $this->host = $config['database']['host'];
        $this->username = $config['database']['username'];
        $this->password = $config['database']['password'];

        // Create connection
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db);
        // Check connection
        if ($this->conn->connect_error) {
            die("Main failed: " . $this->conn->connect_error);
        }
        $this->config = $config;

        return $this->conn;
    }

    public function show()
    {
        $response = null;

        $result = $this->conn->query("SELECT * FROM transaction ORDER BY created_at DESC");

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $response[] = $row;
            }
        }

        return $response;
    }

    public function disburse(array $params)
    {
        $input = $params;
        $credential = $this->config['credential'];

        $input['url'] = $credential['disburseUrl'];

        $response = $this->postDisburse($input);

        $fields = implode(',', array_keys($response));
        $values = implode("','", array_values($response));
        $result = $this->conn->query("INSERT INTO `transaction` ({$fields}) VALUES ('{$values}')");
    }

    public function disburseStatus($id)
    {
        $response = $this->getDisburseStatus($id);

        $valueSets = array();
        foreach($response as $key => $value) {
            $valueSets[] = $key . " = '" . $value . "'";
        }
        $values = implode(',', $valueSets);

        $result = $this->conn->query("UPDATE transaction SET {$values} WHERE id = {$id}");
    }

    public function getDisburseStatus($id)
    {
        $result = null;

        $credential = $this->config['credential'];
        $username = $credential['user'];
        $password = $credential['password'];

        $url = $credential['disburseStatusUrl'] . $id;

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response, true);
        $result['status'] = $response['status'];
        $result['time_served'] = $response['time_served'];
        $result['receipt'] = $response['receipt'];

        return $result;
    }

    public function postDisburse(array $params)
    {
        $response = null;

        $credential = $this->config['credential'];
        $username = $credential['user'];
        $password = $credential['password'];

        $url = $this->getSafe('url', $params);
        $bankCode = $this->getSafe('bank_code', $params);
        $accountNumber = $this->getSafe('account_number', $params);
        $amount = $this->getSafe('amount', $params);
        $remark = $this->getSafe('remark', $params);
        $data = [
            'bank_code' => $bankCode,
            'account_number' => $accountNumber,
            'amount' => $amount,
            'remark' => $remark
        ];

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response, true);

        return $response;
    }

    protected function getSafe($key, array $params, $default = NULL)
    {
        if (array_key_exists($key, $params))
            return $params[$key];

        return $default;
    }


}