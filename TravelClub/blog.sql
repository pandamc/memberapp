CREATE TABLE blog (
  user_id              SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  post_id              SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  body                 VARCHAR(250) NOT NULL,
  PRIMARY KEY          (post_id)
);

insert  INTO blog VALUES( 1, 1, "this is a test post");
insert  INTO blog VALUES( 1, 2, "this is another test post");


CREATE TABLE comment (
  userid              SMALLINT UNSIGNED NOT NULL,
  postid              SMALLINT UNSIGNED NOT NULL,
  commentid           SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  body                VARCHAR(250) NOT NULL,
  postdate            TIMESTAMP NOT NULL,
  primary key (commentid)
)


-- cross table query to get all info needed for showing comments in blog posts
SELECT 	t1.postid, t1.body, t2.body as comm, t3.username  FROM blog t1
	LEFT JOIN comments t2 ON t1.postid = t2.postid

		LEFT JOIN members t3 ON t1.userid = t3.id
			WHERE t1.postid IS NOT NULL
	UNION
	SELECT t1.postid, t1.body, t2.body as comm, t3.username  FROM blog t1
	RIGHT JOIN comments t2 ON t1.postid = t2.postid

			RIGHT JOIN members t3 ON t1.userid = t3.id
				WHERE t1.postid IS NOT NULL