<?php

class AbstractRepository {

    private DbConnect $db_connect;

    private PDO $db;

    private Utils $utils;

    public function __construct() {
        $this->db_connect = new DbConnect();
        $this->db = $this->db_connect->getDb();
        $this->utils = new Utils();
    }
       
    public function persist(string $table, array $data, int $id):void {
        $update = 'UPDATE ' . $table . ' SET ';
        $execute = [];
        foreach($data as $key => $value) {
            $update .= $key . ' = ?, ';
            if (str_contains($key, '_at') && $value != null) {
                $execute[] = $value->format('Y-m-d H:i:s');
            }
            else {
                $execute[] = $value;
            }
            
        }

        $update = substr($update, 0, -2);
        $update .= ' WHERE id = ?';
        $execute[] = $id;

        $query = $this->db->prepare($update);
        $query->execute($execute);
    }

}