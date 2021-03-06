<?php


class Post extends DatabaseQuery {

    public $id; public $title; public $body; public $status; public $added; public $added_by; public $edited; public $edited_by;
    public static $tableName = 'posts';
    public static $className = 'Post';

    public static function findByTitle($title) {
        global $db;

        $sql = 'SELECT * FROM ' . static::$tableName . " where title=? LIMIT 1";
        
        $stmt = $db->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, static::$className);
        $stmt->execute([$title]);

        return $stmt->fetch();
    }

    public static function all($start = 5, $end = 5, $search = "", $auth = "", $counter = false) {
        global $db;
        
        $sql = 'SELECT * FROM ' . static::$tableName;

        $execute = [];
        if (!empty($search)) {
            $search = "%{$search}%";
            $sql .= ' where title LIKE ?';
            $execute[] = $search;
        }
        
        if (!empty($auth) && $auth == 'auth') {

            if (in_array($_SESSION['role'],['admin', 'moderator'])) {
            } else {
                $sql .= !empty($search) ? ' AND' : ' WHERE';
                $sql .= ' added_by = ?';
                $execute[] = $_SESSION['user_id']; 
            }
        }



        if ($counter == false) {
            if ($start === 0 && $end === 0) {
                $sql .= ' ORDER BY id DESC LIMIT 10';
            } else {
                $sql .= ' ORDER BY id DESC LIMIT ?, ?';
                $execute[] = $start;
                $execute[] = $end;
            }
        }
        
        $stmt = $db->prepare($sql);
        $stmt->execute($execute);
        return $stmt->fetchAll();
    }

}