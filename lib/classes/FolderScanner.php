<?php
  
  class FolderScanner
  {
      public $folder;
      public $img_types;
      
      public function __construct($f, $i_t) 
      {
          $this->folder = $f;
          $this->img_types = $i_t;
      }
      
      public function getAllImages()
      {
          $files = scandir($this->folder);
          $masstypes = explode(',', $this->img_types);
          $resmass = array();
          
          foreach($files as $file) 
          {
              if (($file == '.') || ($file == '..')) continue;
              
              $promm = getimagesize($this->folder . '/' . $file);
              if(!$promm) continue;
              
              if(!in_array($promm["mime"], $masstypes)) continue;
              
              $resmass[] = array ("fname" => $file,
                                  "ww" => intval($promm[0]),
                                  "hh" => intval($promm[1]),
                                  "mtype" => $promm["mime"]);                              
               
          } 
          
          return $resmass;     
      }       
          
  }
  
?>