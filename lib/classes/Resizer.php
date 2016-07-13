<?php

  class Resizer
  {
      public $folder;
      public $resize_folder;
      public $img_size_ww_hh;
      public $img_count_resize;
      
      public function __construct($f, $r_f, $i_s, $i_cr) 
      {
          $this->folder = $f;
          $this->resize_folder = $r_f;
          $this->img_size_ww_hh = $i_s;
          $this->img_count_resize = $i_cr;
      }
      
      public function allImgResize()
      {
          
          //////////////////
          
          $limit_par = ""; if($this->img_count_resize) $limit_par = " LIMIT " . $this->img_count_resize;
          
          $row = DataBase::mysqlQuery(TABLE_QUEUE,array("status" => "'resize'"), "*", $limit_par);
          
          if(!$row) return false;
          
          foreach($row as $k => $v){
                  
                  $this->oneImgResize($v['id'], $v['fname'], $v['ww'], $v['hh'], $v['mtype']);
          }
          //////////////////
          
      }
       
        
      public function oneImgResize($id_img, $fname, $ww, $hh, $mtype)
      {
          $rgb=0xffffff;
          $quality=90;
          
          $src = $this->folder . '/' .$fname;
          $dest = $this->resize_folder . '/' . $fname;
          list($width, $height) = explode('x',$this->img_size_ww_hh);
          
          $format = strtolower(substr($mtype, strpos($mtype, '/')+1));
          $icfunc = "imagecreatefrom" . $format;
          if (!function_exists($icfunc)) return false;
          $x_ratio = $width / $ww;
          $y_ratio = $height / $hh;
          
          $ratio       = min($x_ratio, $y_ratio);
          $use_x_ratio = ($x_ratio == $ratio);

          $new_width   = $use_x_ratio  ? $width  : floor($ww * $ratio);
          $new_height  = !$use_x_ratio ? $height : floor($hh * $ratio);
          $new_left    = $use_x_ratio  ? 0 : floor(($width - $new_width) / 2);
          $new_top     = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);

          $isrc = $icfunc($src);
          $idest = imagecreatetruecolor($width, $height);
          imagefill($idest, 0, 0, $rgb);

          imagecopyresampled($idest, $isrc, $new_left-1, $new_top-1, 0, 0,
                             $new_width+2, $new_height+2, $ww, $hh);

          imagejpeg($idest, $dest, $quality);
          imagedestroy($isrc);
          imagedestroy($idest);
          
          if(file_exists($dest))
          {
              DataBase::mysqlUpdate(TABLE_QUEUE, array("status" => "'resized'"), " WHERE id = ".$id_img);
          
          } else {
              
              DataBase::mysqlUpdate(TABLE_QUEUE, array("status" => "'failed'"), " WHERE id = ".$id_img);
              
          }
          
      }
      
  }
  
?>