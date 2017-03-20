<?php
// include db connection file
require_once "config.php";

// create abstract class that handles db access and data retrieval

// create class constructor
abstract class DataObject {
    protected $data = array();
    public function __construct( $data ) {
        foreach ( $data as $key => $value ) {
            if ( array_key_exists( $key, $this->data ) ) $this->data[$key] = $value;
        }
    }

    // allow outside code to access data stored in the method
    public function getValue( $field ) {
        if ( array_key_exists( $field, $this->data ) ) {
            return $this->data[$field];
        } else {
            die( "Field not found" );
        }
    }
    // pass through php special chars function to avoid sql injection
    public function getValueEncoded( $field ) {
        return htmlspecialchars( $this->getValue( $field ) );
    }
    // create PDO connection and handle any errors
    protected static function connect() {
        try {
            $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
            $conn->setAttribute( PDO::ATTR_PERSISTENT, true );
            $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        } catch ( PDOException $e ) {
            die( "Connection failed: " . $e->getMessage() );
        }
        return $conn;
    }
    // close DB connection
    protected static function disconnect( $conn ) {
        $conn = "";
    }

}
?>
