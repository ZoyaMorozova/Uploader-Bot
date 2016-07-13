<?php
  
  class Rescheduler
  {
        public static function imgRescheduler()
        {
            DataBase::mysqlUpdate(TABLE_QUEUE, array("status" => "resize"), " WHERE status = 'failed'");    
        }
        
        public static function allImgResizeCleaner($folder)
        {
            $row = DataBase::mysqlQuery(TABLE_QUEUE,array("status" => "'resized'"), "*", " LIMIT 3");
          
            if(!$row) return false;
          
            foreach($row as $k => $v)
            {
                $src = $folder . '/' .$v['fname'];
                  
                if(file_exists($scr)) unlink($src);
                  
            }
          
        }
  }
  
?>