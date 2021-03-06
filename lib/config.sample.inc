<?php
  
  define("DT_LASTTIME",time()); 
  
  // Add your token 
  define("DROPBOX_ACCESSTOKEN",""); 
  
  // Connection settings database
  define("DB_NAME","");
  define("DB_HOSTNAME","localhost");
  define("DB_USERNAME","");
  define("DB_PASSWORD","");  
  
  // Table Name
  define("TABLE_QUEUE","table_bot_queue");
  
  $images_dir = MAINPUTH . "images";
  
  $images_rdir = MAINPUTH . "images_resized";
  
  /**
  * 
  * Can use string parameters separated by "," 
  * "image/jpeg,image/png,image/gif" 
  * 
  */  
  $images_types = "image/jpeg";
  
  $images_sizes = "640x640";
  
  $classes_dir = MAINPUTH . "lib/classes/";
  require_once $classes_dir . "DataBase.php";
  require_once $classes_dir . "FolderScanner.php";
  require_once $classes_dir . "Scheduler.php";
  require_once $classes_dir . "Resizer.php";
  require_once $classes_dir . "Monitoring.php";
  require_once $classes_dir . "Rescheduler.php";
  
  require_once MAINPUTH . "lib/Dropbox/autoload.php";
  
  
?>