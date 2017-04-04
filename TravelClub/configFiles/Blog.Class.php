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
        "postdate" => "",
        "username" => ""
    );




    // gather the data for the blog posts using a prepared sql statement, loop through the results and store each in a blog object, handle errors,
    public static function getPosts($startRow, $numRows, $order)
    {
        $conn = parent::connect();
        $sql = "select  SQL_CALC_FOUND_ROWS t1.username, t2.postid,   t2.body, t2.postdate" .  " from members t1, blog t2" .  " where t1.id = t2.userid" . " ORDER BY $order LIMIT :startRow, :numRows";
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
    public function insertBlogPost()
    {
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
            $st = $conn->prepare($sql);
            $st->bindValue(":userid", $this->data["userid"], PDO::PARAM_STR);
            $st->bindValue(":body", $this->data["body"], PDO::PARAM_STR);
            $st->bindValue(":postdate", $this->data["postdate"], PDO::PARAM_STR);
            $st->execute();
            parent::disconnect($conn);
        } catch (PDOException $e) {
            parent::disconnect($conn);
            die("Query failed: " . $e->getMessage());
        }
    }


    // user can view all their own posts
        public static function viewMyPosts($startRow, $numRows, $order, $userid)
    {
        $conn = parent::connect();
        $sql = "select  SQL_CALC_FOUND_ROWS t1.username, t2.postid,   t2.body, t2.postdate" .  " from members t1, blog t2" .  " where t1.id = t2.userid and userid = :userid" . " ORDER BY $order LIMIT :startRow, :numRows";
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


    // view single post in update form
    public function viewBlogPost($postid )
    {
        $conn = parent::connect();
        $sql = "SELECT * from " . TBL_BLOG . " WHERE postid = :postid ";
        try {
            $st = $conn->prepare($sql);
            $st->bindValue(":postid", $postid, PDO::PARAM_STR);
            //$st->bindValue(":body", $body, PDO::PARAM_STR);
            $st->execute();
            $row = $st->fetch();
            parent::disconnect($conn);
            if ($row) return new Blog($row);
        } catch (PDOException $e) {
            parent::disconnect($conn);
            die("Query failed: " . $e->getMessage());
        }
    }



    // update record from form data
    public function updateBlogPost()
    {
        $conn = parent::connect();


        $sql = "UPDATE blog set body = :body where postid = :postid";
        try {
            $st = $conn->prepare($sql);
            $st->bindValue( ":postid", $this->data["postid"], PDO::PARAM_STR );
            $st->bindValue( ":body", $this->data["body"], PDO::PARAM_STR );
            $st->execute();
            parent::disconnect($conn);
        } catch (PDOException $e) {
            parent::disconnect($conn);
            die("Query failed: " . $e->getMessage());
        }
    }



    // delete single post
    public function deleteBlogPost($postid )
    {
        $conn = parent::connect();
        $sql = "DELETE  from " . TBL_BLOG . " WHERE postid = :postid ";
        //echo $sql;
        try {
            $st = $conn->prepare($sql);
            $st->bindValue(":postid", $postid, PDO::PARAM_STR);
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