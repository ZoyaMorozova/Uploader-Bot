<?php

  class DataBase
  {
    public static $mConnect;	// result of a database connection
    public static $mSelectDB;	// result of select database

    // The method creates a database connection
    public static function mysqlConnect($host, $user, $pass, $name)
	{
		// We try to create a database connection
		self::$mConnect = @mysql_connect($host, $user, $pass);

		// If the connection is not passed, display an error message ...
		if(!self::$mConnect)
		{
			echo "<p><b>Sorry, failed to connect to MySQL server</b></p>";
			exit();
			return false;
		}

		// Try to select database
		self::$mSelectDB = mysql_select_db($name, self::$mConnect);

		// If the database is not selected, display an error message ..
		if(!self::$mSelectDB)
		{
			echo "<p><b>".mysql_error()."</b></p>";
			exit();
			return false;
		}
        
        mysql_query("SET NAMES UTF8;");
        mysql_query("set character_set_client='utf8'");
        mysql_query("set character_set_results='utf8'");
        mysql_query("set collation_connection='utf8'");
   
		return self::$mConnect;
	}

    public static function mysqlInsert($nametable, array $arguments=array())
    {
        $sql = "INSERT INTO " . trim($nametable) . " (" . join(', ',array_keys($arguments)) . ") VALUES(" . join(", ",array_values($arguments)) . ")";   
        
        mysql_query($sql); 
    }
    
    public static function mysqlUpdate($nametable, array $arguments=array(), $wherestr = '')
    {
        if($wherestr == '') return false;
        
        $sql = "UPDATE " . $nametable . " SET ";
        
        $promstr = '';
        foreach($arguments as $k => $v) 
        {
            if($promstr != '') 
            {
                $promstr .= ' AND ';
            }
                
            $promstr .= $k . ' = ' . $v;   
        } 
        
        $sql .= $promstr . $wherestr;
         
        mysql_query($sql);
    }
    
    public static function mysqlQuery($nametable, array $arguments=array(), $fields = '*', $advstr = '')
    {
        $sql = "SELECT " . $fields . " FROM " . trim($nametable) . " WHERE "; 
        
        $promstr = '';
        foreach($arguments as $k => $v) 
        {
            if($promstr != '') 
            {
                $promstr .= ', ';
            }
                
            $promstr .= $k . ' = ' . $v;   
        } 
        
        $sql .= $promstr . $advstr; 
        
        $res = mysql_query($sql);

        if(!$res) 
        {
            return 0;
        } 
        
        for($i = 0; $i < @mysql_num_rows($res); ++$i)
        {
            $row[] = mysql_fetch_array($res,MYSQL_ASSOC);
        }

        return $row;
    }
    
    public static function mysqlCreateTableQueue($nametable)
    {
        mysql_query("CREATE TABLE IF NOT EXISTS `" . $nametable . "` (
                     `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                     `fname` varchar(80) NOT NULL,
                     `ww` int(11) NOT NULL,
                     `hh` int(11) NOT NULL,
                     `mtype` varchar(20) NOT NULL,
                     `status` varchar(20) NOT NULL,
                     `lasttime` int(11) NOT NULL,
                     PRIMARY KEY (`id`)
                     ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    }
        
    // The method closes the database connection
	public static function mysqlClose()
	{
		return mysql_close(self::$mConnect);
	}
    
}

?>