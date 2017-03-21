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


    // if a row exists for user in TBL_ACCESS_LOG increment otherwise create row and set value to 1, handle errors 
    public function record() {
        $conn = parent::connect();
        $sql = "SELECT * FROM " . TBL_ACCESS_LOG . " WHERE memberId = :memberId AND pageUrl = :pageUrl";
        try {
            $st = $conn->prepare( $sql );
            $st->bindValue( ":memberId", $this->data["memberId"], PDO::PARAM_INT );
            $st->bindValue( ":pageUrl", $this->data["pageUrl"], PDO::PARAM_STR );
            $st->execute();
            if ( $st->fetch() ) {
                $sql = "UPDATE " . TBL_ACCESS_LOG . " SET numVisits = numVisits + 1 WHERE memberId = :memberId AND pageUrl = :pageUrl";
                $st = $conn->prepare( $sql );
                $st->bindValue( ":memberId", $this->data["memberId"], PDO::PARAM_INT );
                $st->bindValue( ":pageUrl", $this->data["pageUrl"], PDO::PARAM_STR );
                $st->execute();
            } else {
                $sql = "INSERT INTO " . TBL_ACCESS_LOG . " ( memberId, pageUrl, numVisits ) VALUES ( :memberId, :pageUrl, 1 )";
                $st = $conn->prepare( $sql );
                $st->bindValue( ":memberId", $this->data["memberId"], PDO::PARAM_INT );
                $st->bindValue( ":pageUrl", $this->data["pageUrl"], PDO::PARAM_STR );
                $st->execute();
            }
            parent::disconnect( $conn );
        } catch ( PDOException $e ) {
            parent::disconnect( $conn );
            die( "Query failed: " . $e->getMessage() );
        }
    }
}
?>