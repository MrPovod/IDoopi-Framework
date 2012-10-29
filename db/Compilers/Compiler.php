<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once "/../Database.php";

/**
 * Description of Compiler
 *
 * @author Борис
 */
class Compiler extends BaseCompiler {
   
    
    public function selectByLatest($tableName, $field)
    {
        return "SELECT * FROM $tableName ORDER BY $field DESC LIMIT 0, 1";
    }
    
    public function queryBuild($query)
    {
        $table = $query->getTableName();
        $sql = "SELECT ";
       
        if ($query->isOnlyValues())
        {
            $c = "";
            foreach ($query->getValues() as $v)
            {
                $sql .= "$c$v";
                $c = ",";
            }
        }
        else if ($query->getDistinct())
        {
            $sql .= "DISTINCT ";
            
            $c = "";
            foreach ($query->getDistinct() as $d)
            {
                $sql .= "$c$d ";
                $c =",";
            }
        }
        else
            $sql .= "{$query->getSelect()}";
        
       $sql .= " FROM {$table}";
       
       
        
        $where = NULL;
        
        if (!$query->isAll())
        {
            $where = "";
            //
            // Билдим where.
            //
            if (count($query->getFilter()) > 0)
            {
                $filter = $query->getFilter();
                $keys = array_keys($filter);
                
                $and = "";
                foreach ($keys as $k)
                {
                    $where .= "$and $k = :$k";
                    $and = " AND ";
                }
            }
            
            if (count($query->getExclude()))
            {
                $exclude = $query->getExclude();
                $keys = array_keys($exclude);
                
                $and = "";
                foreach ($keys as $k)
                {
                    $where .= "$and NOT $k = :$k";
                    $and = " AND ";
                }
            }
            
            if (!empty($where))
            {
                $where = " WHERE " . $where;
                $sql .= $where;
            }
        }
        
        if ($query->getOrderBy())
        {
            $orderBy = $query->getOrderBy();
            
            if (count($orderBy) > 0)
            {
                $sql .= " ORDER BY ";
                
                $c = "";
                foreach ($orderBy as $o)
                {
                    $sql .= "$c $o";
                    $c = ",";
                }
                
                if ($query->getDesc())
                    $sql .= " DESC";
            }
        }
        
        if ($query->getGroupBy())
        {
            $groupBy = $query->getGroupBy();
            
            if (count($groupBy) > 0)
            {
                $sql .= " GROUP BY ";
                
                $c = "";
                foreach ($groupBy as $o)
                {
                    $sql .= "$c $o";
                    $c = ",";
                }
            }
        }
        
        if ($query->isSetLimit())
        {
            $limit = $query->getLimit();
            $sql .= " LIMIT {$limit[0]}, {$limit[1]}";
        }
        
        return $sql;
    }
    
    public function select($tableName, $fields = NULL)
    {
        $sql = "SELECT * FROM $tableName";
        
        if ($fields)
        {
            $sql .= " WHERE ";
            $and = "";
            
            $keys = array_keys($fields);
            
            foreach ($keys as $key)
            {
                $sql .= "$and $key = :$key ";
                $and = "AND";
            }
        }
        
        return $sql;
    }
    
    public function insert($scheme)
    {
        $name = $scheme->getName();
        $fields = $scheme->getFields();
        
        $sql = "INSERT INTO $name(";
        $sql .= $scheme->getAllFieldsSQL(TRUE);
        $sql .= ") VALUES(";
        $sql .= $scheme->getNameFieldsSQL(TRUE);
        $sql .= ")";
        
        return $sql;
    }
    
    public function update($scheme)
    {
        $name = $scheme->getName();
        $fields = $scheme->getFields();
        $serial = $scheme->getSerial();
        
        $sql = "UPDATE $name SET ";
        
        $c = "";
        foreach ($fields as $field)
        {
            if ($field->isSerial())
                continue;
                
            $name = $field->getName();
            
            $sql .= "$c$name = :$name ";
            $c = ",";
        }
        
        $serialName = $serial->getName();
        $sql .= "WHERE $serialName = :$serialName";
        
        return $sql;
    }
    
    public function createTable($scheme) {
        $name = $scheme->getName();
        $sql = "CREATE TABLE IF NOT EXISTS $name";

        $sql .= "(" . $scheme->getAllFieldsSQLType();
       
        if ($scheme->isHavePrimaryKeys())
            $sql .= ", " . $scheme->getPrimaryKeysSQLType();
        
        if ($scheme->isHaveUniqueKeys())
            $sql .= ", " . $scheme->getUniqueKeysSQLType();
        
        $sql .= ")";
        return $sql;
    } 
    
    public function delete($scheme)
    {
        $name = $scheme->getName();
        $serialName = $scheme->getSerial()->getName();
       
        return "DELETE FROM $name WHERE $serialName = :$serialName";
    }
    
    public function deleteTable($name) {
        return "DROP TABLE IF EXISTS $name";
    }
    
}

?>
