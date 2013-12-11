<?php
	include("configures.php");

	class DataBase{
		
		public $tables_list = array();
			
		public $tables_fields = array();
		
		function __construct(){
			mysql_connect(Configures::$db_host, Configures::$db_user, Configures::$db_pass)||exit(mysql_error());
			mysql_select_db(Configures::$db_name)||exit(mysql_error());	
			$this->get_fields();
		}			
			
		public function get_tables(){
			$tables = mysql_query("show tables");
			$i=0;
			while($row = mysql_fetch_row($tables)){
				$this->tables_list[$i] = $row[0];
				$i++;
			}
		}
		
		public function get_fields(){
			$this->get_tables();
			foreach($this->tables_list as $table){
				$fields_q = mysql_query("SHOW FIELDS FROM {$table}");
				
				while($fields_r = mysql_fetch_assoc($fields_q)){
					if(!isset($this->tables_fields[$table]))
						$this->tables_fields[$table] = array();
					array_push($this->tables_fields[$table],$fields_r);
				}
			}
		}
		
		private function generate_data($_data){
			$type = preg_replace("(\(\d+\))","",$_data);
			preg_match("(\d+)",$_data,$tmp_array);
			$len = $tmp_array[0];
			$result = "";
			if(($type=="int")||($type=="tinyint")||($type=="smallint")||($type=="mediumint")||($type=="bigint"))
				for($i=1;$i<=rand(1,$len);$i++)
					$result .= rand(1,9);
			elseif(($type=="char")||($type=="varchar")||($type=="text"))
				if($type=="text"){
						for($i=1;$i<=rand(1,200);$i++)
							$result .= chr(rand(97,122));
						$result[0]="'";		
						$result[strlen($result)]="'";		
					}
				else{
					for($i=1;$i<=rand(1,$len);$i++)
						$result .= chr(rand(97,122));
					$result[0]="'";		
					$result[strlen($result)]="'";		
				}
			elseif($type="date")
				$result = rand(0,2200)."-".rand(1,12)."-".rand(1,31);
			return $result;
		}	
		
		public function insert_data($table){
			$fields_str = "";
			$values = "";
			
			$this->tables_fields = array();
			$this->get_fields();
			$fields = $this->tables_fields; 
			
			foreach($fields[$table] as $i=>$field)
				if(empty($field['Extra'])){
					$fields_str .= $i!=count($fields[$table])-1 ? $field['Field']."," : $field['Field'] ;
					$values .= $i!=count($fields[$table])-1 ? $this->generate_data($field['Type'])."," : $this->generate_data($field['Type']);
				}
			mysql_query("insert into {$table}({$fields_str}) values({$values})")||print(mysql_error());
			echo "Запись добавлена";
		}
		
		public function get_data($_table){
			$q = mysql_query("select*from {$_table}");
			$return_array = array();
			$i=0;
			while($row = mysql_fetch_assoc($q)){
				$return_array[$i] = $row;
				$i++;
			}
			return $return_array;
		}

	}

?>
