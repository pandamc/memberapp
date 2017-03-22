<?php

/**
 * Created by PhpStorm.
 * User: junemc
 * Date: 21/03/2017
 * Time: 20:55
 */
class Blog extends DataObject
{
    // map the database fields
    protected $data = array(
        "userid" => "",
        "postid" => "",
        "body" => "",
        "postdate" => ""
    );

    // gather the data for the blog posts using a prepared sql statement, loop through the results and store each in a blog object, handle errors,
    public static function getPosts($startRow, $numRows, $order)
    {
        $conn = parent::connect();
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . TBL_BLOG . " ORDER BY $order LIMIT :startRow, :numRows";
        try {
            $st = $conn->prepare($sql);
            $st->bindValue(":startRow", $startRow, PDO::PARAM_INT);
            $st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
            $st->execute();
            $posts = array();
            foreach ($st->fetchAll() as $row) {
                $posts[] = new Blog($row);
            }
            $st = $conn->query("SELECT found_rows() AS totalRows");
            $row = $st->fetch();
            parent::disconnect($conn);
            return array($posts, $row["totalRows"]);
        } catch (PDOException $e) {
            parent::disconnect($conn);
            die("Query failed: " . $e->getMessage());
        }
    }


    // insert new record from form data
    public function insertBlogPost() {
        $conn = parent::connect();
        $sql = "INSERT INTO " . TBL_BLOG . " (
            userid,
            body,
            postdate
        ) VALUES (
            :userid,
            :body,
            :postdate
        )";
        // bind values and handle any errors encountered
        try {
            $st = $conn->prepare( $sql );
            // have to get the member id in here too
            $st->bindValue( ":userid", $this->data["userid"], PDO::PARAM_STR );
            $st->bindValue( ":body", $this->data["body"], PDO::PARAM_STR );
            $st->bindValue( ":postdate", $this->data["postdate"], PDO::PARAM_STR );
            $st->execute();
            parent::disconnect( $conn );
        } catch ( PDOException $e ) {
            parent::disconnect( $conn );
            die( "Query failed: " . $e->getMessage() );
        }
    }

}