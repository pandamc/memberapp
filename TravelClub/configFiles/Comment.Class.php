<?php

/**
 * Created by PhpStorm.
 * User: junemc
 * Date: 04/04/2017
 * Time: 13:37
 */
class Comment extends DataObject
{
    // map the database fields
    protected $data = array(
        "userid" => "",
        "postid" => "",
        "body" => "",
        "postdate" => ""
    );

    // require functions for insert, edit and delete and display
    //
    //
    //

    // gather the data for the blog posts using a prepared sql statement, loop through the results and store each in a blog object, handle errors,
    // user can view all their own posts
    public static function viewComments($startRow, $numRows, $order, $userid)
    {
        $conn = parent::connect();
        //$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . TBL_BLOG .  " WHERE userid = :userid " . "ORDER BY $order  $direction LIMIT :startRow, :numRows";
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . TBL_BLOG .  " WHERE userid = :userid " . "ORDER BY $order   LIMIT :startRow, :numRows";
        try {
            $st = $conn->prepare($sql);
            $st->bindValue(":startRow", $startRow, PDO::PARAM_INT);
            $st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
            $st->bindValue(":userid", $userid, PDO::PARAM_INT);
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


    // add a comment to a post
    public function addComment()
    {
        $conn = parent::connect();
        $sql = "INSERT INTO " . TBL_COMMENT . " (
            userid,
            postid,
            body,
            postdate
        ) VALUES (
            :userid,
            :postid,
            :body,
            :postdate
        )";
        // bind values and handle any errors encountered
        try {
            $st = $conn->prepare($sql);
            $st->bindValue(":userid", $this->data["userid"], PDO::PARAM_STR);
            $st->bindValue(":postid", $this->data["postid"], PDO::PARAM_STR);
            $st->bindValue(":body", $this->data["body"], PDO::PARAM_STR);
            $st->bindValue(":postdate", $this->data["postdate"], PDO::PARAM_STR);
            $st->execute();
            parent::disconnect($conn);
        } catch (PDOException $e) {
            parent::disconnect($conn);
            die("Query failed: " . $e->getMessage());
        }
    }


  


}