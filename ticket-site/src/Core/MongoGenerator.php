<?php

namespace App\Core;

include 'Config.php';


class MongoGenerator extends Database
{
    private $collection;
    private $option = [];
    private $where = [];
    private $group = [];
    private $queryResult;
    private $count = 0;

    public function __construct($collection)
    {
        $this->connectToMongo();
        $this->setCollection($collection);
    }

    private function setCollection($collection)
    {
        $this->collection = $this->getMongoConnection()->selectCollection(MONGO_DB_NAME, $collection);
    }
 
    public function option($option)
    {
        $this->option = $option;
        return $this;
    }

    public function where($where)
    {
        $this->where = $where;
        return $this;
    }

    public function group($group)
    {
        $this->group = $group;
        return $this;
    }
 
    private function setQueryResult($result)
    {
        $this->queryResult = $result;
    }

    private function findCount($filter)
    {
        try {
            $count = $this->getCollection()->count($filter);
        } catch (\Throwable $th) {
            Tools::print($th, true);
        }
        $this->count = $count;           
    }

    public function getCount()
    {
        return $this->count;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    public function getOption()
    {
        return $this->option;
    }

    public function getWhere()
    {
        return $this->where;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function getResult()
    {
        return $this->queryResult;
    }

    public function insert($data)
    {
        try {
            $result = $this->getCollection()->insertOne($data);
        } catch (\Throwable $th) {
            Tools::print($th, true);
        }
        $this->setQueryResult($result);
        return $this;
    }

    public function insertMany($data)
    {
        try {
            $result = $this->getCollection()->insertMany($data);
        } catch (\Throwable $th) {
            Tools::print($th, true);
        }
        $this->setQueryResult($result);
        return $this;
    }

    public function updateOne($data)
    {
        try {
            $filter = $this->manageWhere($this->getWhere());
            $update = ['$set' => $data];
            $result = $this->getCollection()->updateOne($filter, $update);
        } catch (\Throwable $th) {
            Tools::print($th, true);
        }
        $this->setQueryResult($result);
        return $this;
    }

    public function update($data)
    {
        try {
            $filter = $this->manageWhere($this->getWhere());
            $update = ['$set' => $data];
            $result = $this->getCollection()->updateMany($filter, $update);
        } catch (\Throwable $th) {
            Tools::print($th, true);
        }
        $this->setQueryResult($result);
        return $this;
    }

    public function delete()
    {
        try {
            $filter = $this->manageWhere($this->getWhere());
            $result = $this->getCollection()->deleteMany($filter);
        } catch (\Throwable $th) {
            Tools::print($th, true);
        }
        $this->setQueryResult($result);
        return $this;
    }

    public function deleteOne()
    {
        try {
            $filter = $this->manageWhere($this->getWhere());
            $result = $this->getCollection()->deleteOne($filter);
        } catch (\Throwable $th) {
            Tools::print($th, true);
        }
        $this->setQueryResult($result);
        return $this;
    }

    public function bulk($operation) {
        // ناقص
        try {
            $result = $this->getCollection()->bulkWrite($operation);
        } catch (\Throwable $th) {
            Tools::print($th, true);
        }
        $this->setQueryResult($result);
        return $this;
    }

    public function distinctVal($field)
    {
        try {
            $result = $this->getCollection()->distinct($field, $this->getWhere());
        } catch (\Throwable $th) {
            Tools::print($th, true);
        }
        $this->setQueryResult($result);
        return $this;
    }

    private function manageWhere($whereArray, $operand= "and")
    {
        // ['id' => 'parham'] // ['id' => [1,24,5,3]] // [['id' => 'p' , 'name' => 'd']] // [['id' => 'sma'], ['name' => 'ddd']]
        foreach ($whereArray as $key => $value) {
            
        }
    }

    public function findData()
    {
        // SELECT IN MYSQL
        try {
            $filter = $this->manageWhere($this->getWhere());
            $this->findCount($filter);
            $result = $this->getCollection()->find($filter, $this->getOption());
        } catch (\Throwable $th) {
            Tools::print($th, true);
        }
        $this->setQueryResult($result);
        return $this;
    }

    public function replace($data)
    {
        // does not need $set -- like UPDATEONE() appears on one element.
        try {
            $filter = $this->manageWhere($this->getWhere());
            $result = $this->getCollection()->replaceOne($filter, $data);
        } catch (\Throwable $th) {
            Tools::print($th, true);
        }
        $this->setQueryResult($result);
        return $this;
    }  
}