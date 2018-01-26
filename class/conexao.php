<?php

class Conexao {

    private $engine;
    private $host;
    private $database;
    private $login;
    private $senha;

    public function __construct() {

        $this->engine = 'mysql';
        $this->host = 'localhost';
        $this->database = 'contato';
        $this->login = 'root';
        $this->senha = '123456';
    }

    public function con() {

        try {
            $pdo = new PDO($this->engine . ':dbname=' . $this->database . ";host=" . $this->host, $this->login, $this->senha);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $pdo['error']['tipo'] = 'banco';
            $pdo['error']['msg'] = 'Falha ao conectar no banco de dados: ' . $e->getMessage();
        }

        return $pdo;
    }

    public function insert($data = array()) {

        $i = 0;
        $value = '';

        foreach ($data['data'] as $key => $values) {
            $fields[] = $key;
            if ($i === 0) {
                $value .= "'" . $values . "'";
            } else {
                $value .= ", '" . $values . "'";
            }

            $i++;
        }

        $fields = implode(',', $fields);

        try {
            return $this->con()->exec("INSERT INTO {$data['table']} ({$fields}) VALUES ({$value})");
        } catch (PDOException $e) {
            print_r($e->getMessage());
            $pdo['error']['tipo'] = 'banco';
            $pdo['error']['msg'] = 'Erro: ' . $e->getMessage();
        }
    }

    public function update($data = array()) {

        $fields = '';
        $where = '';
        $i = 0;

        foreach ($data['data'] as $key => $values) {

            if ($i === 0) {
                $fields .= $key . " = '" . $values . "'";
            } else {
                $fields .= ", " . $key . " = '" . $values . "'";
            }

            $i++;
        }

        if (isset($data['where'])) {

            $where = ' WHERE ';
            $i = 0;

            foreach ($data['where'] as $key => $values) {

                if ($i === 0) {
                    $where .= $key . " = '" . $values . "'";
                } else {
                    $where .= " AND " . $key . " = '" . $values . "'";
                }

                $i++;
            }
        }

        return $this->con()->exec("UPDATE {$data['table']} SET {$fields} {$where}");
    }

    public function select($data = array()) {

        $where = '';
        $field = '*';

        if (isset($data['field'])) {
            $field = '(' . implode(',', $data['field']) . ')';
        }

        if (!empty($data['data'])) {
            if (count($data['data']) > 1) {

                $where = 'WHERE ';
                $i = 0;

                foreach ($data['data'] as $key => $values) {

                    if ($i === 0) {
                        $where .= $key . " = '" . $values . "'";
                    } else {
                        $where .= " AND " . $key . " = '" . $values . "'";
                    }

                    $i++;
                }
            } else if (count($data['data']) === 1) {
                foreach ($data['data'] as $key => $values) {
                    $where = 'WHERE ' . $key . " = '" . $values . "'";
                }
            }
        }

        if (isset($data['limit'])) {
            $where .= ' LIMIT ' . $data['limit'];
        }

        return $this->con()->query("SELECT {$field} FROM {$data['table']} {$where}", PDO::FETCH_ASSOC);
    }

    public function delete($data = array()) {

        $where = '';
        $i = 0;

        foreach ($data['where'] as $key => $values) {
            if ($i === 0) {
                $where .= $key . '=' . $values;
            } else {
                $where .= ' AND ' . $key . '=' . $values;
            }
            $i++;
        }

        return $this->con()->exec("DELETE FROM {$data['table']} WHERE {$where}");
    }

    public function query($query) {
        return $this->con()->query($query);
    }

}
