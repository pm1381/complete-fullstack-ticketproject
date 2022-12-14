<?php

namespace App\Core;

use App\Model\Arrays;

class Generator extends Database
{
    private $table;
    private $queryString;
    private $row = [];
    private $where = [];
    private $limit = 0 ;
    private $order = [];
    private $join  = [];
    private $group = [];
    // limit, CNT

    public function __construct($table)
    {
        $this->connectToMySql();
        $this->table = $table;
    }

    public function setTable($table)
    {
        $this->table = $table;
    }

    public function row($row)
    {
        $this->row = $row;
        return $this;
    }

    public function where($where)
    {
        $this->where = $where;
        return $this;
    }

    public function join($join)
    {
        $this->join = $join;
        return $this;
    }

    public function group($group)
    {
        $this->group = $group;
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }
 
    public function order($order)
    {
        $this->order = $order;
        return $this;
    }

    public function setQuery($query)
    {
        $this->queryString = $query;
    }

    public function getQuery()
    {
        return $this->queryString;
    }

    public function generateInsertRows($data)
    {
        $keys = [];
        foreach($data as $key=>$value) {
            $keys[] = $key;
        }
        $data = '(';
        $i = 1;
        foreach ($keys as $col) {
            $data .= '`' .  $col . '`' ;
            if ($i < count($keys)) {
                $data .= ", ";
            }
            $i++;
        }
        $data .= ')';
        return $data;
    }

    public function insert($data)
    {
        $cols = $this->generateInsertRows($data);
        $bind = $this->generateBinds($data);
        $statement = "INSERT INTO " . '`' . $this->table . '`' . $cols ." VALUES " . $bind;
        $query = self::$connection->prepare($statement);
        $this->setQuery($statement);
        $this->insertBindParams($query, $data);
        try {
            $query->execute();
            // DisplayFormat::jsonFormat(true, 'inserted successfully');
        } catch (\PDOException $e) {
            http_response_code(400);
            // DisplayFormat::jsonFormat(false, 'an error occured in inserting to db' . $e->getMessage());
        }
    }

    private function insertBindParams($query, $data)
    {
        foreach ($data as $col => $value){
            $column = ":".$col;
            $query->bindValue($column, $value);
        }
    }

    private function generateJoin($join)
    {
        $data = '';
        foreach ($join as $secondTable => $columns) {
            foreach($columns as $key => $value) {
                $data .= " LEFT JOIN " . '`' . $secondTable . '`' .  ' ON ' . '`'.$key.'`' . ' = ' .'`'.$value.'`';
            }
        }
        return $data;
    }

    private function generateOrder($order)
    {
        $data = '';
        if (count($order) > 0) {
            $data = ' ORDER BY ';
            foreach ($order as $key => $type)
            {
                $type = strtoupper($type);
                $data .= $key . '=' . $type . " ";
            }
        }
        return $data;
    }

    private function generateGroup($group)
    {
        $data = '';
        $i = 1;
        if (count($group)) {
            $data = ' GROUP BY ';
        }
        foreach ($group as $value) {
            $data .= $value;
            if ($i < count($group)) {
                $data .= ',';
            }
        }
        return $data;
    }

    private function whereBindParams($query, $data)
    {
        foreach($data as $col => $value) {
            $opt = $this->findOperand($col);
            if (is_array($value) && gettype($col) == 'integer') {
                // [['a' => 'b', 'e' => 'f']['c' => 'd']]
                foreach($value as $valueKey => $valueVal) {
                    $column = ":".$valueVal;
                    $query->bindValue($column, $valueVal);
                }
            } else {
                if (is_array($value)) {
                    // ['id' => [1,2,3,4]]
                    foreach($value as $val) {
                        $column = ":".$val;
                        $query->bindValue($column, $val);
                    }
                } else {
                    // ['id' => 'pary']
                    $column = ":".$col;
                    $query->bindValue($column, $value);
                }
            }
        }
    }

    private function generateBinds($row)
    {
        $data = '(';
        $i = 1;
        foreach ($row as $col => $value) {
            $data .= ":" .  $col ;
            if ($i < count($row)) {
                $data .= ", ";
            }
            $i++;
        }
        $data .= ')';
        return $data;
    }

    public function direct($query, $var)
    {
        $statement = self::$connection->prepare($query);

        if (count($var)) {
            $statement->execute($var);
        } else {
            $statement->execute();
        }
        
        return $statement->fetchAll();
    }

    private function manageSpecialRows($col, $special)
    {
        preg_match("/$special\((.*)\)/", $col, $matches);
        $newCol = '`' . $matches[1] . '`';
        if ($matches[1] == "*") {
            $newCol = $matches[1];
        }
        return $special . '(' . $newCol . ')';
    }

    private function generateRows($row)
    {
        $specials = Arrays::specialCols();
        if (count($row) > 0) {
            $data = ''; $i = 1;$control = 0;
            foreach ($row as $col) {

                foreach ($specials as $spec) {
                    if (strpos($col, $spec) !== false) {
                        print_f($col);
                        print_f($spec);
                        $data .= $this->manageSpecialRows($col, $spec);
                        $control = 1;
                        break;
                    }
                }

                if ($control == 0) {
                    if (strpos($col, "->") !== false) {
                        list($previousName, $newName) = explode("->", $col);
                        $data .= '`' . $previousName . '`' . 'AS' . '`' . $newName . '`';
                    } else {
                        $data .=  '`' . $col . '`' ;
                    }
                }
                
                if ($i < count($row)) {
                    $data .= ", ";
                }
                $i++;
                $control = 0;
            }
            return $data;
        }
        return '*';
    }

    private function findOperand(&$key)
    {
        $opt = '=';

        if (strpos($key, "LIKE") !== false  &&  strpos($key, "NOTLIKE") === false) {
            $key = trim(explode("LIKE", $key)[0]);
            $opt = ' like ';
        }
         
        if (strpos("!=", $key) !== false) {
            $opt = ' != ';
            $key = trim(explode("!=", $key)[0]);
        }

        if (strpos($key, "NOTLIKE") !== false) {
            $key = trim(explode("NOTLIKE", $key)[0]);
            $opt = ' not like ';
        }

        return $opt;
    }

    private function generateWhere($where)
    {
        if ($where == "" || count($where) == 0) {
            return '';
        }
        $data = ' WHERE '; $i = 1;
        foreach ($where as $key => $value) {
            $opt = $this->findOperand($key);
            if (is_array($value) && gettype($key) == 'integer' ) { // [], ke yani har chi dakhelesh hast ba ham or beshan
                $data .= '( '; $j = 1;
                foreach($value as $valueKey => $valueVal) {
                    $data .= '(';
                    $data .= '('.$valueKey . $opt . ':' . $valueVal . ') ';
                    if ($j < count($value)) {
                        $data .= 'AND ';
                    }
                    $j++;
                }
                $data .= ')';
                if ($i < count($where)) {
                    $data .= 'OR ';
                }
                $i++;
            } else {
                if (is_array($value)) { // 'id' => []
                    foreach($value as $val) {
                        $data .= '('.$key . $opt . ':' . $val . ') ';
                        if ($i < count($value)) {
                            $data .= 'OR ';
                        }
                        $i++;
                    }
                } else { // 'id' => 'pm'
                    $data .= '('. '`' .$key . '`' . $opt . ':' . $key . ') ';
                    if ($i < count($where)) {
                        $data .= 'AND ';
                    }
                    $i++;
                }
            }
        }
        return $data;
    }

    public function generateUpdateSet($set)
    {
        $data = 'SET ';
        $i = 1;
        foreach($set as $col => $value){
            $data .= $col . '=' . ':' . $col;
            if ($i < count($set)) {
                $data .= ', ';
            }
            $i++;
        }
        return $data;
    }

    public function generateLimit($limit)
    {
        $data = ' LIMIT ';
        if (is_array($limit)) {
            $data .= $limit[0] . " OFFSET " . $limit[1] ;
        } else {
            $data .= $limit . ' ';
        }
        if ($limit == 0) {
            return '';
        }
        return $data;
    }

    public function update($data)
    {
        $where = $this->generateWhere($this->where);
        $set = $this->generateUpdateSet($data);
        $query = $this->createQuery('', 'UPDATE', '', $where, '', '', '', $set);
        $this->insertBindParams($query, $data);
        $this->insertBindParams($query, $this->where);
        try {
            $query->execute();
            // DisplayFormat::jsonFormat(true, 'updated successfully');
        } catch (\PDOException $e) {
            http_response_code(400);
            // DisplayFormat::jsonFormat(false, 'an error occured while updating' . $e->getMessage());        
        } 
    }

    public function delete($data)
    {
        $where = $this->generateWhere($data);
        $query = $this->createQuery('', 'DELETE', '', $where, '', '', '', '');
        $this->insertBindParams($query, $data);
        try {
            $query->execute();
            // DisplayFormat::jsonFormat(true, 'deleted successfully');
        } catch (\PDOException $e) {
            http_response_code(400);
            // DisplayFormat::jsonFormat(false, 'an error occured while deleting' . $e->getMessage());
        }
    }

    public function select()
    {
        $data = [];
        $row = $this->generateRows($this->row);
        $limit = $this->generateLimit($this->limit);
        $where = $this->generateWhere($this->where);
        $join  = $this->generateJoin($this->join);
        $order = $this->generateOrder($this->order);
        $group = $this->generateGroup($this->group);
        $query = $this->createQuery($row, 'SELECT', $join, $where, $order, $limit, $group, '');
        $this->whereBindParams($query, $this->where);
        try {
            $query->execute();
            if ($this->limit == 1) {
            $data = $query->fetch();
            } else {
                $data = $query->fetchAll();
            }
            if ($data === false) {
                return [];
            }
            if (is_null($data)) {
                $data = [];    
            }
            return $data;
        } catch (\PDOException $e) {
            http_response_code(400);
            // DisplayFormat::jsonFormat(false, 'an error occured while selecting' . $e->getMessage());
        }
    }

    private function createQuery($row, $crud, $join = '', $where = '', $order = '', $limit = '', $group = '', $set = '')
    {
        $statement  = '';
        switch ($crud) {
            case 'SELECT':
                $statement = "SELECT ". $row ." FROM " . '`' . $this->table . '`' . $join . $where . $order . $limit . $group;
                break;
            case 'UPDATE':
                $statement = "UPDATE " . '`' . $this->table .'` '.  $set . $where;
                break;
            case 'DELETE':
                $statement = "DELETE FROM " . '`' .  $this->table . '`' . $where;
                break;
            default:
                break;
        }
        $query = self::$connection->prepare($statement);
        $this->setQuery($statement);
        return $query;
    } 
}