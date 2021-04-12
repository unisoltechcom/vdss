<?php

    class Database

	{

		private $server = SERVER;
		private $username = USERNAME;   
		private $password = PASSWORD; 
		private $database = DATABASENAME;
		private $pre = TABLEPREFIX; 
		

		private $link_id = 0;
		private $query_result = 0;
		private $affected_rows = 0;
		private $pager = array();		

		# Calls the Contructor

		function __CONSTRUCT()
		{

			//require 'dbconf.php';
			$this->server = SERVER;
			$this->user = USERNAME;
			$this->pass = PASSWORD;
			$this->database = DATABASENAME;
			

			/*$this->tbl_members = $tbl_members;
			$this->student_information = $student_information;
			$this->tbl_attempts = $tbl_attempts;
			$this->connect();*/			

			$this->link_id = @mysqli_connect($this->server, $this->username, $this->password, $this->database);
			
				if ($this->link_id->connect_errno) {
				  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
				  exit();
				}

				

			/*if(!@mysql_select_db($this->database,$this->link_id))

				die(mysql_error());*/

			

			return $this->link_id;

		}

	

		# Connect with Server

		# If $link is not connected then opens a new Connection

		# returns the Connection

		function connection_create($link=false)

		{

			$this->link_id = @mysqli_connect($this->server, $this->username, $this->password, $this->database);
			if ($this->link_id->connect_errno) {
				  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
				  exit();
				}
			return $this->link_id;

		}

		

		# Closes the Conneection to the Server

		function connection_close()

		{

			if($this->link_id == true)
			{
				$this->link_id->close();

				echo "Connection Closed";

			}

		}



		#desc: update query function

		#param: $table , $where and $data

		function query_update($table, $data, $where ='1')

		{   

			$q = "UPDATE `".$this->pre.$table."` SET "; 

			

			foreach($data as $key=>$val)

			{   

				if(strtolower($val)=='null' ) $q .= "`$key`='NULL', ";

				else if(strtolower($val)=='now()') $q .= "`$key`=NOW(), ";

				else $q .= "`$key`='".$val."', ";

			}

			 

		   $q = rtrim($q, ', ')." WHERE ".$where.';';

		   

		   return $this->execute_query($q);

		}  

		

		#desc: delete query fuction 

		#para:$table and $data are two parametr for table and values

		function query_delete($table, $data)

		 {

			$q = "UPDATE `".$this->pre.$table."` SET `isActive` = '0' WHERE ";

	

			foreach($data as $key=>$val) 

			{

				if(strtolower($val)=='null') $q .= "`$key`='NULL', ";

				elseif(strtolower($val)=='now()') $q .= "`$key`=NOW(), ";

				else $q .= "`$key`='".$val."', ";

			}

			$q = rtrim($q, ', ');

			$this->execute_query($q);

		 }

		 

		#desc: restore query fuction 

		#para: $table and $data are two parametr for table and values

		function query_restore($table, $data)

		 {

			$q = "UPDATE `".$this->pre.$table."` SET `isActive` = '1' WHERE ";

	

			foreach($data as $key=>$val) 

			{

				if(strtolower($val)=='null') $q .= "`$key`='NULL', ";

				elseif(strtolower($val)=='now()') $q .= "`$key`=NOW(), ";

				else $q .= "`$key`='".$val."', ";

			}

	

			$q = rtrim($q, ', ');

	

			$this->execute_query($q);

		 }

		 

		 #desc: delete query fuction permanent after checking sub records.

		#para: $table and $data are two parametr for table and values

		function query_delete_permanent($table, $tables, $data)

		 {

		 	$w='';
           
		 	foreach($data as $key=>$val) 

			{

				if(strtolower($val)=='null') $w .= "`$key`='NULL', ";

				elseif(strtolower($val)=='now()') $w .= "`$key`=NOW(), ";

				else $w .= "`$key`='".$val."', ";

				

				if($this->is_child($tables, $key, $val)){return false;}

			}

			

		 	$w = rtrim($w, ', ');

			

			 $q = "DELETE FROM `".$this->pre.$table."` WHERE ".$w; 

	

			$this->execute_query($q);

		 }

		 

		 # Inserts the New Record in the Table

		# Takes two parameters, $table and $data

		# returns the last inserted id if successful otherwise returns false

		 function is_child($tables, $key, $val)

		 {

			 if(count($tables)>0)

			 {

				for($i=0;$i<=count($tables); $i++)

				{

					$table=$tables[$i];

					$record = $this->mysql_query("SELECT * FROM '$table' WHERE '`$key`' = '$val'");

					if($this->mysql_num_rows($record)>0)

					{

						return true;

					}

				}

			}

			return false;

		 }

		 

		# Inserts the New Record in the Table

		# Takes two parameters, $table and $data

		# returns the last inserted id if successful otherwise returns false

		function query_insert($table, $data)

		{

			$query = "INSERT INTO `".$table."` ";

			$k = '';

			$v = '';

			

			foreach ($data as $key => $val)

			{

				$k .= $key.', ';

				if($val == '')

					$v .= "'', ";

				else if(strtolower($val) == 'null')

					$v .= 'NULL, ';

				else if(strtolower($val) == 'now()')

					$v .= 'NOW(), ';

				else

					$v .= "'".$this->mysql_real_escape_string($val)."', ";

			}

			

			 $query .= "(".rtrim($k, ', ').") VALUES (".rtrim($v, ', ').");";

				

			if($this->execute_query($query))

			{
                $lastid = @$this->mysql_insert_id($this->link_id);
				$_SESSION['pid'] = $lastid;
				return $lastid;

			}

			else

				return false;

		}

		

		# Executes the specifid query

		# Takes the $query as the parameter

		# returns number of affected rows if successful otherwise returns false

		function execute_query($query)

		{

			$this->query_result = $this->mysql_query($query, $this->link_id);

			if(!$this->query_result)

				return false;

			else 

			{

				$this->affected_rows = @mysqli_affected_rows($this->link_id);

				return $this->query_result;

			}

		}

		

		# Fetches a single result

		# Takes one parameter $query_result

		# returns record

		function fetch_array($query_result=-1)

		{

			if($query_result != -1)

				$this->query_result = $query_result;

				

			if (isset($this->query_result))

				$result = @$this->mysql_fetch_assoc($this->query_result);

			else

				return false;

				

			return $result;

		}

		

		# Fetches an array of result

		# Takes one parameter $query

		# returns the array of result

		function fetch_array_all($query)

		{

		   //die($query);

			$query_result = $this->execute_query($query);

			$result = array();

			

			while ($row = @$this->mysql_fetch_array($query_result)) 

			{
				$result[] = $row;
			}		

			$this->free_result($query_result);

			return $result;

		}

		

		# Free memory from the result resource

		# Takes one parameter $query_result

		# returns nothing.

		function free_result($query_result=-1)

		{

			if($query_result != -1)
				$this->query_result = $query_result;			

			if($this->query_result != 0 && !@$this->query_result->free_result())
				return false;

		}

		

		# Generate the paging scheme

		# Takes $result(resource), $next_pre ,$show_record_found, $records_per_page as the parameters  

		# Returns the paging
		function pager_new($query, $records_per_page, $show_record_found, $next_text, $pre_text, $first_text, $last_text, $css, $css_page , $query_string = null)
		{
			if(!isset($_GET['start'])){
				$start = 0; $startpoint = 0;
			}else {
				$start = $_GET['start']; 
				$startpoint = ($start * $records_per_page) - $records_per_page;
				}			

			$record = $this->execute_query($query);
			$pager["total"] = $this->mysql_num_rows($record);
			$pager["query"] = $query." LIMIT ".$startpoint.", ".$records_per_page;
			if($show_record_found == 1)
				$pager["total_records_found_text"] =  "Total Records Found: (".$pager['total'].")";
			else
				$pager["total_records_found_text"] = '';
				
			$adjacents = "2"; 
            $per_page = $records_per_page;
			$prevlabel = "&lsaquo; Prev";
			$nextlabel = "Next &rsaquo;";
			$lastlabel = "Last &rsaquo;&rsaquo;";
			$page = $start;
			$page = ($page == 0 ? 1 : $page);  
			$start = ($page - 1) * $per_page;                               
			 
			$prev = $page - 1;                          
			$next = $page + 1;
				
			$pages = $pager["total"] / $records_per_page;
			$pages = ceil($pages);
			$lastpage = $pages;
			$lpm1 = $lastpage - 1;
			
			$val = 0;
			if($pager["total"] > 0 )
			{ 
				if($_SERVER['QUERY_STRING'] != '')
				{	$mainurl = $_SERVER['PHP_SELF'].'?';
					$url = $_SERVER['REQUEST_URI'];
					$part = explode('?', $url); 
					$piece = explode('&', $part[1]); 
					
					if(count($piece) == 1)
					{	if(strpos($piece[0], 'start='))
						{
						}
					}
					else
					{ $query_string='';
						
							for ($i=0; $i<count($piece); $i++)
						{
							if(preg_match('/start=/', $piece[$i]))
							{

							}
							else
							{	$mainurl .= $piece[$i].'&';
							}
						}
					}
				}
				else
				{
					$mainurl = $_SERVER['REQUEST_URI'].'?';
				}
				
				$pre = $start - $records_per_page;

				$pre = $pre < 0 ? '0' : $pre;
				
				   
        @$pager['pages_links'] .= "<ul class='pagination'>";
        $pager['pages_links'] .= "<li class='page_info'>Page {$page} of {$lastpage}</li>";
             
            if ($page > 1) $pager['pages_links'].= "<li><a href='{$mainurl}start={$prev}{$query_string}'>{$prevlabel}</a></li>";
             
        if ($lastpage < 7 + ($adjacents * 2)){   
            for ($counter = 1; $counter <= $lastpage; $counter++){
                if ($counter == $page)
                    $pager['pages_links'].= "<li class='disabled'><a class='current'>{$counter}</a></li>";
                else
                    $pager['pages_links'].= "<li><a href='{$mainurl}start={$counter}{$query_string}'>{$counter}</a></li>";                    
            }
         
        } elseif($lastpage > 5 + ($adjacents * 2)){
             
            if($page < 1 + ($adjacents * 2)) {
                 
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
                    if ($counter == $page) {
                        $pager['pages_links'].= "<li class='disabled'><a class='current'>{$counter}</a></li>";
					}else {
                        $pager['pages_links'].= "<li><a href='{$mainurl}start={$counter}{$query_string}'>{$counter}</a></li>";                    }
                }
                $pager['pages_links'].= "<li class='dot'>...</li>";
                $pager['pages_links'].= "<li><a href='{$mainurl}start={$lpm1}{$query_string}'>{$lpm1}</a></li>";
                $pager['pages_links'].= "<li><a href='{$mainurl}start={$lastpage}{$query_string}'>{$lastpage}</a></li>";  
                     
            } elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                 
                $pager['pages_links'].= "<li><a href='{$mainurl}start=1{$query_string}'>1</a></li>";
                $pager['pages_links'].= "<li><a href='{$mainurl}start=2{$query_string}'>2</a></li>";
                $pager['pages_links'].= "<li class='dot'>...</li>";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                    if ($counter == $page)
                        $pager['pages_links'].= "<li class='disabled'><a class='current'>{$counter}</a></li>";
                    else
                        $pager['pages_links'].= "<li><a href='{$mainurl}start={$counter}{$query_string}'>{$counter}</a></li>";                    
                }
                $pager['pages_links'].= "<li class='dot'>..</li>";
                $pager['pages_links'].= "<li><a href='{$mainurl}start={$lpm1}{$query_string}'>{$lpm1}</a></li>";
                $pager['pages_links'].= "<li><a href='{$mainurl}start={$lastpage}{$query_string}'>{$lastpage}</a></li>";      
                 
            } else {
                 
                $pager['pages_links'].= "<li><a href='{$mainurl}start=1{$query_string}'>1</a></li>";
                $pager['pages_links'].= "<li><a href='{$mainurl}start=2{$query_string}'>2</a></li>";
                $pager['pages_links'].= "<li class='dot'>..</li>";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pager['pages_links'].= "<li><a class='current'>{$counter}</a></li>";
                    else
                        $pager['pages_links'].= "<li><a href='{$mainurl}start={$counter}{$query_string}'>{$counter}</a></li>";                    
                }
            }
        }
         
            if ($page < $counter - 1) {
				$pager['pages_links'].= "<li><a href='{$mainurl}start={$next}{$query_string}'>{$nextlabel}</a></li>";
				$pager['pages_links'].= "<li><a href='{$mainurl}start=$lastpage{$query_string}'>{$lastlabel}</a></li>";
			}
         
        $pager['pages_links'].= "</ul>";        
    
				
				
				
				
			}
			else
			{
				$pager['pages_links'] = '';
			}
			return $pager;
		}
		
		
		
		

		function pager($query, $records_per_page, $show_record_found, $next_text, $pre_text, $first_text, $last_text, $css, $css_page)
		{
			if(!isset($_GET['start']))
				$start = 0;
			else
				$start = $_GET['start'];			

			$record = $this->execute_query($query);
			$pager["total"] = @@$this->mysql_num_rows($record);
			$pager["query"] = $query." LIMIT ".$start.", ".$records_per_page;
			if($show_record_found == 1)
				$pager["total_records_found_text"] =  "Total Records Found: (".$pager['total'].")";
			else
				$pager["total_records_found_text"] = '';
			$pages = $pager["total"] / $records_per_page;
			$pages = ceil($pages);
			$lastpage = $pages;
			
			
			$val = 0;
			if($pager["total"] > 0 )
			{
				if($_SERVER['QUERY_STRING'] != '')
				{	$mainurl = $_SERVER['PHP_SELF'].'?';
					$url = $_SERVER['REQUEST_URI'];
					$part = explode('?', $url);
					$piece = explode('&', $part[1]);
					if(count($piece) == 1)
					{	if(strpos($piece[0], 'start='))
						{
						}
					}
					else
					{	for ($i=0; $i<count($piece); $i++)
						{
							if(preg_match('/start=/', $piece[$i]))
							{

							}
							else
							{	$mainurl .= $piece[$i].'&';
							}
						}
					}
				}
				else
				{
					$mainurl = $_SERVER['REQUEST_URI'].'?';
				}

				$pre = $start - $records_per_page;

				$pre = $pre < 0 ? '0' : $pre;
				
				$pager['pages_links'] =  '<ul class="pagination pagination-sm"><li><a href="'.$mainurl.'start='.$val.'" class="'.$css.'">'.$first_text.'</a></li>'.'  ';
				$pager['pages_links'] .=  '<li><a href="'.$mainurl.'start='.$pre.'" class="'.$css.'">'.$pre_text.'</a></li>'.'  ';
				$flagpg = 0 ;
				for ($i=1; $i<=$pages; $i++)
				{   
					if($start == $val)
					{	$pager['pages_links'] .= '<li class="disabled"><span class="'.$css_page.'">'.$i.'</span></li>';
					}
					else
					{	$pager['pages_links'] .=  '<li><a href="'.$mainurl.'start='.$val.'" class="'.$css.'">'.$i.'</a></li>'.'  ';
					}
				   
					$val = $records_per_page + $val;
				}				

				$next = $start + $records_per_page;
				$next = $next >= $pager["total"] ? $next - $records_per_page : $next; 
				$last = ($pages-1)*$records_per_page;
				$pager['pages_links'] .=  '<li><a href="'.$mainurl.'start='.$next.'" class="'.$css.'">'.$next_text.'</a></li>'.'  ';
				$pager['pages_links'] .=  '<li><a href="'.$mainurl.'start='.$last.'" class="'.$css.'">'.$last_text.'</a></li></ul>';
				
				
				
				
			}
			else
			{
				$pager['pages_links'] = '';
			}
			return $pager;
		}

		

		function get_by_agregate($tableID, $tabe, $field)

		{

			$query = "SELECT count('`$fild`') AS total '`$table`' WHERE '`$value`' = 0";

			$results = $this->execute_query($query);

			$records = @$this->mysql_fetch_array($result);

			return $total = $records['total'];			

		}
		
		function mysql_query($q) {
			
			return mysqli_query($this->link_id,$q);
		}
		
		function mysql_fetch_assoc($q) {
			return mysqli_fetch_assoc($q);
		}
		
		function mysql_fetch_array($q){
			return mysqli_fetch_array($q , MYSQLI_BOTH);
		}
		
		function mysql_num_rows($q){
			return mysqli_num_rows($q);
		}
		
		function mysql_insert_id() {
			return mysqli_insert_id($this->link_id);
		}
		
		function mysql_real_escape_string($q) {
			return mysqli_real_escape_string($this->link_id,$q);
		}

	}

?>