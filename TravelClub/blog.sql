CREATE TABLE blog (
  user_id              SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  post_id              SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  body                 VARCHAR(250) NOT NULL,
  PRIMARY KEY          (post_id)
);

insert  INTO blog VALUES( 1, 1, "this is a test post");
insert  INTO blog VALUES( 1, 2, "this is another test post");