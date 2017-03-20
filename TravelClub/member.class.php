<?php
// include the dataobject class which facilitates data retrieval
require_once "DataObject.class.php";

// derive the member class from the DataObject class
class Member extends DataObject
{
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
    private $_activities = array(
        "diving" => "Diving",
        "hiking" => "Hiking",
        "sunbathing" => "Sunbathing",
        "snorkelling" => "Snorkelling",
        "spa" => "Spa",
        "cooking" => "Cooking",
        "eco-tourism" => "Eco-tourism"
    );

    // gather the data for the members using a prepared sql statement, loop through the results and store each in a member object, handle errors,
    public static function getMembers($startRow, $numRows, $order)
    {
        $conn = parent::connect();
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . TBL_MEMBERS . " ORDER BY $order LIMIT :startRow, :numRows";
        try {
            $st = $conn->prepare($sql);
            $st->bindValue(":startRow", $startRow, PDO::PARAM_INT);
            $st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
            $st->execute();
            $members = array();
            foreach ($st->fetchAll() as $row) {
                $members[] = new Member($row);
            }
            $st = $conn->query("SELECT found_rows() AS totalRows");
            $row = $st->fetch();
            parent::disconnect($conn);
            return array($members, $row["totalRows"]);
        } catch (PDOException $e) {
            parent::disconnect($conn);
            die("Query failed: " . $e->getMessage());
        }
    }

    // gather the data for a specific member using the id for that member, handle errors
    public static function getMember($id)
    {
        $conn = parent::connect();
        $sql = "SELECT * FROM " . TBL_MEMBERS . " WHERE id = :id";
        try {
            $st = $conn->prepare($sql);
            $st->bindValue(":id", $id, PDO::PARAM_INT);
            $st->execute();
            $row = $st->fetch();
            parent::disconnect($conn);
            if ($row) return new Member($row);
        } catch (PDOException $e) {
            parent::disconnect($conn);
            die("Query failed: " . $e->getMessage());
        }
    }

    // retrieve all members from database and make sure both username and email address are unique
    public static function getByUsername( $username ) {
        $conn = parent::connect();
        $sql = "SELECT * FROM " . TBL_MEMBERS . " WHERE username = :username";
        try {
            $st = $conn->prepare( $sql );
            $st->bindValue( ":username", $username, PDO::PARAM_STR );
            $st->execute();
            $row = $st->fetch();
            parent::disconnect( $conn );
            if ( $row ) return new Member( $row );
        } catch ( PDOException $e ) {
            parent::disconnect( $conn );
            die( "Query failed: " . $e->getMessage() );
        }
    }

    public static function getByEmailAddress( $emailAddress ) {
        $conn = parent::connect();
        $sql = "SELECT * FROM " . TBL_MEMBERS . " WHERE emailAddress = :emailAddress";
        try {
            $st = $conn->prepare( $sql );
            $st->bindValue( ":emailAddress", $emailAddress, PDO::PARAM_STR );
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
    public function getGenderString()
    {
        return ($this->data["gender"] == "f") ? "Female" : "Male";
    }

    // return the value from the activities from the enum values
    public function getFavoriteActivityString()
    {
        return ($this->_activities[$this->data["favoriteActivity"]]);
    }

    //
    public function getActivities()
    {
        return $this->_activities;
    }

    // insert new record from form data
    public function insert() {
        $conn = parent::connect();
        $sql = "INSERT INTO " . TBL_MEMBERS . " (
            username,
            password,
            firstName,
            lastName,
            joinDate,
            gender,
            favoriteActivity,
            emailAddress,
            otherInterests
        ) VALUES (
            :username,
            password(:password),
            :firstName,
            :lastName,
            :joinDate,
            :gender,
            :favoriteActivity,
            :emailAddress,
            :otherInterests
        )";
        // bind values and handle any errors encountered
        try {
            $st = $conn->prepare( $sql );
            $st->bindValue( ":username", $this->data["username"], PDO::PARAM_STR );
            $st->bindValue( ":password", $this->data["password"], PDO::PARAM_STR );
            $st->bindValue( ":firstName", $this->data["firstName"], PDO::PARAM_STR );
            $st->bindValue( ":lastName", $this->data["lastName"], PDO::PARAM_STR );
            $st->bindValue( ":joinDate", $this->data["joinDate"], PDO::PARAM_STR );
            $st->bindValue( ":gender", $this->data["gender"], PDO::PARAM_STR );
            $st->bindValue( ":favoriteActivity", $this->data["favoriteActivity"], PDO::PARAM_STR );
            $st->bindValue( ":emailAddress", $this->data["emailAddress"], PDO::PARAM_STR );
            $st->bindValue( ":otherInterests", $this->data["otherInterests"], PDO::PARAM_STR );
            $st->execute();
            parent::disconnect( $conn );
        } catch ( PDOException $e ) {
            parent::disconnect( $conn );
            die( "Query failed: " . $e->getMessage() );
        }
    }


    // allow a registered user to login and handle errors 
    public function authenticate() {
        $conn = parent::connect();
        $sql = "SELECT * FROM " . TBL_MEMBERS . " WHERE username = :username AND password = password(:password)";
        try {
            $st = $conn->prepare( $sql );
            $st->bindValue( ":username", $this->data["username"], PDO::PARAM_STR );
            $st->bindValue( ":password", $this->data["password"], PDO::PARAM_STR );
            $st->execute();
            $row = $st->fetch();
            parent::disconnect( $conn );
            if ( $row ) return new Member( $row );
        } catch ( PDOException $e ) {
            parent::disconnect( $conn );
            die( "Query failed: " . $e->getMessage() );
        }
    }
}

//
?>

