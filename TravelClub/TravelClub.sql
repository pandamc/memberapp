-- create the database for the application

Create database TravelClub;

-- switch to use the new database
USE TravelClub;

-- create the members table

CREATE TABLE members (
  id              SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  username        VARCHAR(30) BINARY NOT NULL UNIQUE,
  password        CHAR(41) NOT NULL,
  firstName       VARCHAR(30) NOT NULL,
  lastName        VARCHAR(30) NOT NULL,
  joinDate        DATE NOT NULL,
  gender          ENUM( 'm', 'f' ) NOT NULL,
  favoriteActivity   ENUM( 'diving', 'hiking', 'sunbathing', 'snorkelling', 'spa', 'cooking', 'eco-tourism' ) NOT NULL,
  emailAddress    VARCHAR(50) NOT NULL UNIQUE,
  otherInterests  TEXT NOT NULL,
  PRIMARY KEY (id)
);

-- insert some test data in the members table

INSERT INTO members VALUES( 1, 'dolphin', password('123456'), 'Doe', 'Adear', '2007-11-13', 'm', 'diving', 'ddear@example.com', 'Football, fishing and gardening' );
INSERT INTO members VALUES( 2, 'sun lover', password('123456'), 'Mary', 'Jane', '2007-02-06', 'f', 'sunbathing', 'mjane@example.com', 'Writing, hunting and travel' );
INSERT INTO members VALUES( 3, 'chill seeker', password('123456'), 'Anna', 'Belle', '2006-09-03', 'f', 'spa', 'abelle@example.com', 'Genealogy, writing, painting' );
INSERT INTO members VALUES( 4, 'tree hugger', password('123456'), 'Marty', 'Smarty', '2007-01-07', 'm', 'eco-tourism', 'msmarty@example.com', 'Guitar playing, rock music, clubbing' );
INSERT INTO members VALUES( 5, 'foodie', password('123456'), 'Nick', 'Slick', '2007-08-19', 'm', 'cooking', 'nslick@example.com', 'Watching movies, cooking, socializing' );
INSERT INTO members VALUES( 6, 'filpper', password('123456'), 'Joe', 'Blogs', '2007-06-11', 'm', 'snorkelling', 'jblogs@example.com', 'Tennis, judo, music' );
INSERT INTO members VALUES( 7, 'the hills have eyes', password('123456'), 'Jane', 'Doe', '2006-03-03', 'f', 'hiking', 'jdoe@example.com', 'Thai cookery, gardening, traveling' );

-- create a table to log the members' page visits

CREATE TABLE accessLog (
  memberId        SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  pageUrl         VARCHAR(255) NOT NULL,
  numVisits       MEDIUMINT NOT NULL,
  lastAccess      TIMESTAMP NOT NULL,
  PRIMARY KEY (memberId, pageUrl)
);

-- insert test data in the access table

INSERT INTO accessLog( memberId, pageUrl, numVisits ) VALUES( 1, 'diary.php', 2 );
INSERT INTO accessLog( memberId, pageUrl, numVisits ) VALUES( 3, 'books.php', 2 );
INSERT INTO accessLog( memberId, pageUrl, numVisits ) VALUES( 3, 'contact.php', 1 );
INSERT INTO accessLog( memberId, pageUrl, numVisits ) VALUES( 6, 'books.php', 4 );