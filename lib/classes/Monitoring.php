<?php

  class Monitoring
  {
        public static function getReport()
        {
            $resstr = "Images Processor Bot \n";
            $resstr .= "Queue Count \n";
  
            $row = DataBase::mysqlQuery(TABLE_QUEUE,array("status" => "'resize'"));
            $resstr .= "resize " . count($row) . " \n";
  
            $row = DataBase::mysqlQuery(TABLE_QUEUE,array("status" => "'upload'"));
            $resstr .= "upload " . count($row) . " \n";
  
            $row = DataBase::mysqlQuery(TABLE_QUEUE,array("status" => "'done'"));
            $resstr .= "done " . count($row) . " \n";
  
            $row = DataBase::mysqlQuery(TABLE_QUEUE,array("status" => "'failed'"));
            $resstr .= "failed " . count($row) . " \n";
            
            return $resstr;
            
        }
  }
  
?>