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
        "postdate" => "",
        "count" => "",
        "commentid" => ""
    );

    // require functions for insert, edit and delete and display
    //
    //
    //



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


    // get comments related to each post to return count and link
    public static function getCommentsPerPost($startRow, $numRows, $order, $postid)
    {
        $conn = parent::connect();
        $sql = "SELECT postid, body, postdate, commentid, userid" . " FROM comments "
            . " WHERE postid = :postid" . " and  "
            . "  EXISTS" . "( select  t1.postid, t1.body, t2.postid "
            . " from comments t1, blog t2 " . " where t1.postid = t2.postid) "
            .  "ORDER BY $order   LIMIT :startRow, :numRows ";
        try {
            $st = $conn->prepare($sql);
            $st->bindValue(":startRow", $startRow, PDO::PARAM_INT);
            $st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
            $st->bindValue(":postid", $postid, PDO::PARAM_INT);
            $st->execute();
            $comments = array();
            foreach ($st->fetchAll() as $row) {
                $comments[] = new Comment($row);
            }
            $st = $conn->query("SELECT found_rows() AS totalRows");
            $row = $st->fetch();
            parent::disconnect($conn);
            return array($comments, $row["totalRows"]);
        } catch (PDOException $e) {
            parent::disconnect($conn);
            die("Query failed: " . $e->getMessage());
        }
    }


    // view single post in update form
    public function viewComment($commentid )
    {
        $conn = parent::connect();
        $sql = "SELECT * from " . TBL_COMMENT . " WHERE commentid = :commentid ";
        try {
            $st = $conn->prepare($sql);
            $st->bindValue(":commentid", $commentid, PDO::PARAM_STR);
            //$st->bindValue(":body", $body, PDO::PARAM_STR);
            $st->execute();
            $row = $st->fetch();
            parent::disconnect($conn);
            if ($row) return new Comment($row);
        } catch (PDOException $e) {
            parent::disconnect($conn);
            die("Query failed: " . $e->getMessage());
        }
    }

    // update record from form data
    public function updateComment()
    {
        $conn = parent::connect();


        $sql = "UPDATE comments set body = :body where commentid = :commentid";
        echo $sql;
        try {
            $st = $conn->prepare($sql);
            $st->bindValue( ":commentid", $this->data["commentid"], PDO::PARAM_STR );
            $st->bindValue( ":body", $this->data["body"], PDO::PARAM_STR );
            $st->execute();
            parent::disconnect($conn);
        } catch (PDOException $e) {
            parent::disconnect($conn);
            die("Query failed: " . $e->getMessage());
        }
    }


    // delete single comment
    public function deleteComment($commentid )
    {
        $conn = parent::connect();
        $sql = "DELETE  from " . TBL_COMMENT . " WHERE commentid = :commentid ";
        //echo $sql;
        try {
            $st = $conn->prepare($sql);
            $st->bindValue(":commentid", $commentid, PDO::PARAM_STR);
            //$st->bindValue(":body", $body, PDO::PARAM_STR);
            $st->execute();
            //$row = $st->fetch();
            parent::disconnect($conn);
            //if ($row) return new Blog($row);
        } catch (PDOException $e) {
            parent::disconnect($conn);
            die("Query failed: " . $e->getMessage());
        }
    }


}