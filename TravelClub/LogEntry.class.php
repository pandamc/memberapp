<?php
// include the dataobject class which facilitates data retrieval
require_once "DataObject.class.php";

// derive the LogEntry class from the DataObject class
class LogEntry extends DataObject {
    protected $data = array(
        "memberId" => "",
        "pageUrl" => "",
        "numVisits" => "",
        "lastAccess" => ""
    );

    // retrieve a list of log of pages accessed by each user, order by last accessed timestamp
    public static function getLogEntries( $memberId ) {
        $conn = parent::connect();
        $sql = "SELECT * FROM " . TBL_ACCESS_LOG . " WHERE memberId = :memberId ORDER BY lastAccess DESC";
        try {
            $st = $conn->prepare( $sql );
            $st->bindValue( ":memberId", $memberId, PDO::PARAM_INT );
            $st->execute();
            $logEntries = array();
            foreach ( $st->fetchAll() as $row ) {
                $logEntries[] = new LogEntry( $row );
            }
            parent::disconnect( $conn );
            return $logEntries;
            // handle errors
        } catch ( PDOException $e ) {
            parent::disconnect( $conn );
            die( "Query failed: " . $e->getMessage() );
        }
    }
}
?>