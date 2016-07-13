<?php
  
  class Rescheduler
  {
        public static function imgRescheduler($img_count)
        {
            $limit_par = ""; if(isset($img_count)) $limit_par = " LIMIT " . intval($img_count); 
            DataBase::mysqlUpdate(TABLE_QUEUE, array("status" => "resize"), " WHERE status = 'failed'" . $limit_par);    
        }
        
        public static function allImgResizeCleaner($folder, $folder_tmp)
        {
            $row = DataBase::mysqlQuery(TABLE_QUEUE,array("status" => "'done'"), "*", "");
          
            if(!$row) return false;
          
            foreach($row as $k => $v)
            {
                $src_orig = $folder . '/' .$v['fname'];
                $src_tmp = $folder_tmp . '/' .$v['fname'];
                
                if(file_exists($src_tmp)) 
                { 
                    if(file_exists($src_orig))
                    {
                        unlink($src_orig); 
                        echo "DEL ".$v['fname']."\n";
                    }
                     
                }
                  
            }
            
            clearstatcache();
          
        }
  }
  
?>