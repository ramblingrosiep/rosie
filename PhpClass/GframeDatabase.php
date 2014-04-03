<?php
/**
 * Database routines for the G-Frame. Use this class as instance. Set database connection details upon instantiation.
 *
 * @package G-Frame
 * @author Shu Miyao
 * @since PHP 5.3
 * @version 1.0
 */

class GframeDatabase extends mysqli {
	// variables

	public $db_info = array (
		'host'=>'',
		'user'=>'',
		'password'=>'',
		'databaseName'=>''
	);

	private $db_connection;
	public $statement;
	private $query_result;

	/**
	 constructor
	 */
	public function __construct( $db_info ) {
		if($db_info) $this->db_info = $db_info;
	}

	/*
		 main routine
	*/

	/**
	 * Test sample routine including all the procedures from initiation of database connection to closure of db connection.
	 *
	 * @param None
	 * @return boolean or string True when successful. String with details returned in case of errors.
	 */
	function databaseGeneralRoutine (
		$_sql_statement = 'show databases;',
		$_bind_parameters = array (),
		$_db_info = array()
	) {

		if($_db_info) $this->db_info = $_db_info; // database info reinit if the variable is specified

		$_query_request = array();
		$_query_result = array();

		if ( ($connection_result = $this->connect_database ()) !== TRUE ) {
			return $this->returnAjaxResponseArray(false,'Failed @ connect_database',$connection_result);
		} else {
			// issue SQL statement
			$_query_request = $this->submit_database_query($_sql_statement,$_bind_parameters);

			if($_query_request['result'] === FALSE) {
				// something wrong with the query submission (execution)
				return $this->returnAjaxResponseArray(false,'Failed @ submit_database_query',$_query_request);
			} else {
				// fetch results
				$_query_result = $this->fetch_query_results();

				$this->statement->close();

				if(!$this->close_database_connection ()) {
					return $this->returnAjaxResponseArray(false,'Failed @ close_database_connection',$_query_result);
				} else {
					// returns null if there is no match.
					return $this->returnAjaxResponseArray(
						true,
						(is_null($_query_result)) ? 'No record' : 'Success',
						$_query_result); // exit normally
				}
			}
		}
	}

	/*
		sub routines
	*/
	
	/**
	 * Initiates connection with database. Necessary information for the connection are all set in db_info
	 * inclusing host, user, password and databaseName. db_info is to be set upon creation of GframeDatabase instance.
	 *
	 * example of db init
	 *
	 *  if ( ($connection_result = $this->connect_database ()) !== TRUE ) {
	 *  return 'Failed to connect to the database '.$connection_result;
	 *  } else {
	 *  // Do something
	 *  }
	 *
	 * @param None
	 * @return boolean or string True when successful. String with details returned in case of errors.
	 */
	function connect_database () {
		$this->db_connection = new mysqli (
			$this->db_info['host'],
			$this->db_info['user'],
			$this->db_info['password'],
			$this->db_info['databaseName']
		);

		// always use utf-8
		$this->db_connection->set_charset("utf-8");
		if (mysqli_connect_errno()) {
			return "Connection failed: %s\n". var_export( mysqli_connect_error() ,TRUE );
		}
		if ($this->db_connection->ping()) {
			return true;
		} else {
			return "Error: %s\n" . var_export($mysqli->error,TRUE) ;
		}
		if( $this->db_connection->connect_errno === 0 ) {
			return true;
		}
		return $this->db_connection->connect_error ;
	}

	/**
	 * Submit SQL to the database for operation.
	 * @param $sql_query mySQL statement
	 * @return array '$sql_query' string. '$bind_parameter_types' string. '$bind_parameters' array
	 * $_bind_parameters = array(array('type'=>'s','data'=>'dataString'),....)
	 */
	function submit_database_query ( $sql_query = '' , $_bind_parameters = array () ) {
		$_bind_parameters_type = '';
		$_bind_parameters_data = array ();
		$_bind_parameters_merged = array();

		$this->statement = $this->db_connection->prepare($sql_query);

		if ( $_bind_parameters ) {
			foreach ($_bind_parameters as $_key => $_value){
				$_bind_parameters_data[] = &$_bind_parameters[$_key]['data'];
				$_bind_parameters_type .= $_bind_parameters[$_key]['type'];
			}
			$_bind_parameters = array_merge( array($_bind_parameters_type), $_bind_parameters_data );
			call_user_func_array(array($this->statement, 'bind_param'), $_bind_parameters );
		}

		if($this->statement) $this->statement->execute();

		if(!$this->statement) {
			return array(
				'result' => false,
				'message'=>$this->db_connection->error,
				'errorno'=> $this->db_connection->errno
			);
		} else if($this->statement->errno !== 0) {
				// note: errorno 1062 duplicate record in INSERTing
				return array(
					'result' => false,
					'message'=>$this->statement->error,
					'errorno'=> $this->statement->errno
				);
			} else {
			// the result is in the $this->query_result which has to be fetched
			return array(
				'result' => true,
				'message'=>'OK',
				'errorno'=> 0
			);
		}
	}

	/**
	 * Fetches results of query.
	 * Ref :
	 * @param None
	 * @return array Results of query in array.
	 */
	function fetch_query_results () {
		$_meta = $this->statement->result_metadata();
		$_hits = array();

		if( $_meta === FALSE ) return false;
		while ($field = $_meta->fetch_field()) {
			$params[] = &$row[$field->name];
		}

		call_user_func_array(array($this->statement, 'bind_result'), $params);

		while ($this->statement->fetch()) {
			foreach($row as $key => $val) {
				$c[$key] = $val;
			}
			$_hits[] = $c;
		}

		return $_hits;
	}

	/**
	 * Closes database connection
	 * @param None
	 * @return boolean True upon success. False if there is any error in closing the connection.
	 */
	function close_database_connection () {
		if( $this->db_connection->close()) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 utilities
	 */

	/**
	 * Return an array of ajax response
	 * @param $response boolean, $message string, $json array
	 * @return array Ajax response data in an associative array
	 */
	public function returnAjaxResponseArray ($_response , $_message, $_json) {
		$_ajax_response = array ();

		$_ajax_response += array( 'response' => $_response );
		$_ajax_response += array( 'message' => $_message );
		$_ajax_response += array( 'json' => $_json );

		return $_ajax_response;
	}


	public function returnResponseArray ($_response , $_message, $_result) {
		$_array_response = array ();

		$_array_response += array( 'response' => $_response );
		$_array_response += array( 'message' => $_message );
		$_array_response += array( 'result' => $_result );

		return $_array_response;
	}

}
