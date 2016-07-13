#!/usr/local/bin/php
<?php

  define("MAINPUTH",__DIR__ . "/");

  require_once "lib/config.php";   
  
  use \Dropbox as dbx;
    
  // Connection database
  DataBase::mysqlConnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
  
  // Create table
  DataBase::mysqlCreateTableQueue(TABLE_QUEUE);

  $echo_options = ("\n" .
                   "Uploader Bot\n" .  
                   "Usage:\n" . 
                   "    command [arguments]\n" .
                   "Available commands:\n" .
                   "    schedule Add filenames to resize queue\n" .
                   "    resize   Resize next images from the queue\n" . 
                   "    status   Output current status in format %queue%:%number_of_images%\n" .  
                   "    upload   Upload next images to remote storage\n");


   if($argc == 1) {
   
       echo $echo_options;
   
   } elseif ($argc > 1) {
       
       switch($argv[1]){
       
           case "schedule":
       
               // **** Scheduler **** 
                
               $objScheduler = new Scheduler($images_dir, $images_types, $images_sizes);        
               $objScheduler->createQueue();
               
               break;
      
           case "resize":
                
               $limit_par = 0; if(isset($argv[3])) $limit_par = intval($argv[3]);
  
               // **** Resizer ****  
               
               $objResizer = new Resizer($images_dir, $images_rdir, $images_sizes, $limit_par);        
               if(!$objResizer->allImgResize())
               {
                  $objScheduler = new Scheduler($images_dir, $images_types, $images_sizes);        
                  $objScheduler->createQueue(); 
                  $objResizer->allImgResize();
               } 
  
               break;
           
           case "upload":
           
               $limit_par = ""; if(isset($argv[3])) $limit_par = " LIMIT " . intval($argv[3]);
               
               // **** Uploader ****
               
               $dbxClient = new dbx\Client(DROPBOX_ACCESSTOKEN, "PHP-Example/1.0");
  
               $row = DataBase::mysqlQuery(TABLE_QUEUE,array(), "*", " status IN('upload','resized')" . $limit_par);
          
               if($row) 
               {
                   foreach($row as $k => $v)
                   {
                       $namef = $images_rdir . '/' . $v['fname'];
                       
                       if(!file_exists($namef)){
                           $namef = $images_dir . '/' . $v['fname'];
                       }
                       
                       if(file_exists($namef)){
                           
                           $fd = fopen($namef, "rb");
                           $fileMetadata = $dbxClient->uploadFile('/uploader/' . $v['fname'], dbx\WriteMode::add(), $fd);
                           fclose($fd);
          
                           $updstatus = "'failed'"; if($fileMetadata) $updstatus = "'done'";
                           DataBase::mysqlUpdate(TABLE_QUEUE, array("status" => $updstatus), " WHERE id = " . $v['id']);
                       
                       }
                       
                       clearstatcache(); 
          
                   }
               }
               
               break;
               
               case "status":
                  
                  // **** Monitoring ****
       
                  echo Monitoring::getReport(); 
                    
               break;
               
               case "retry":
               
                  $limit_par = 0; if(isset($argv[3])) $limit_par = intval($argv[3]);
                  
                  // **** Rescheduler ****
       
                  Rescheduler::imgRescheduler($limit_par);
                  
                  
               break;
                  
           default:
               break;
       }
       
       Rescheduler::allImgResizeCleaner($images_dir, $images_rdir);       
       
   }

   // Closing a database connection
   DataBase::mysqlClose();   
?>