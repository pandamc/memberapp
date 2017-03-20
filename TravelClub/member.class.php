<?php
// include the dataobject class which facilitates data retrieval
require_once "DataObject.class.php";

// derive the member class from the DataObject class
class Member extends DataObject {
    protected $data = array(
        "id" => "",
        "username" => "",
        "password" => "",
        "firstName" => "",
        "lastName" => "",
        "joinDate" => "",
        "gender" => "",
        "favoriteActivity" => "",
        "emailAddress" => "",
        "otherInterests" => ""
    );
// Map the enum values of the activities field
    private $_activities  = array(
        "diving" => "Diving",
        "hiking" => "Hiking",
        "sunbathing" => "Sunbathing",
        "snorkelling" => "Snorkelling",
        "spa" => "Spa",
        "cooking" => "Cooking",
        "eco-tourism" => "Eco-tourism"
    );

    // gather the data for the members using a prepared sql statement, loop through the results and store each in a member object, handle errors,
    public static function getMembers( $startRow, $numRows, $order ) {
        $conn = parent::connect();
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . TBL_MEMBERS . " ORDER BY $order LIMIT :startRow, :numRows";
        try {
            $st = $conn->prepare( $sql );
            $st->bindValue( ":startRow", $startRow, PDO::PARAM_INT );
            $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
            $st->execute();
            $members = array();
            foreach ( $st->fetchAll() as $row ) {
                $members[] = new Member( $row );
            }
            $st = $conn->query( "SELECT found_rows() AS totalRows" );
            $row = $st->fetch();
            parent::disconnect( $conn );
            return array( $members, $row["totalRows"] );
        } catch ( PDOException $e ) {
            parent::disconnect( $conn );
            die( "Query failed: " . $e->getMessage() );
        }
    }

    // gather the data for a specific member using the id for that member, handle errors
    public static function getMember( $id ) {
        $conn = parent::connect();
        $sql = "SELECT * FROM " . TBL_MEMBERS . " WHERE id = :id";
        try {
            $st = $conn->prepare( $sql );
            $st->bindValue( ":id", $id, PDO::PARAM_INT );
            $st->execute();
            $row = $st->fetch();
            parent::disconnect( $conn );
            if ( $row ) return new Member( $row );
        } catch ( PDOException $e ) {
            parent::disconnect( $conn );
            die( "Query failed: " . $e->getMessage() );
        }
    }
    // translate the data string to a word rather than a letter
    public function getGenderString() {
        return ( $this->data["gender"] == "f" ) ? "Female" : "Male";
    }
    // return the value from the activities from the enum values
    public function getFavoriteActivityString() {
        return ( $this->_activities[$this->data["favoriteActivity"]] );
    }
}
?>