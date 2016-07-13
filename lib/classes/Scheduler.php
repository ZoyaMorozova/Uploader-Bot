<?php

  class Scheduler extends FolderScanner
  {
        public $img_size_ww_hh;
        
        public function __construct($f, $i_t, $i_s) 
        {
            parent::__construct($f, $i_t);
          
            $this->img_size_ww_hh = $i_s;
        }
        
        public function createQueue()
        {
            
            
            //////////////////
            // Find just image in Folder
            $objfolder = new FolderScanner($this->folder, $this->img_types);
  
            $allimages = $objfolder->getAllImages();
  
            foreach($allimages as $k => $v)
            {
                // files ready to be resized
                $status = "upload"; 
                if($v["ww"] . 'x' . $v["hh"] != $this->img_size_ww_hh)
                {
                    // files ready to be uploaded
                    $status = "resize";          
                }
                
                if(!DataBase::mysqlQuery(TABLE_QUEUE,array("fname" => "'" . $v["fname"] . "'")))
                {
                    DataBase::mysqlInsert(TABLE_QUEUE, 
                               array("fname" => "'" . $v["fname"] . "'", 
                                        "ww" => $v["ww"], 
                                        "hh" => $v["hh"], 
                                     "mtype" => "'" . $v['mtype'] . "'", 
                                    "status" => "'" . $status . "'",
                                  "lasttime" => DT_LASTTIME));
                                  
                }
            }    
            //////////////////
            
            
        }
  }
  
?>