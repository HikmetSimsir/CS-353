<?php
$configs = include('config.php');

// connect database
$conn = mysqli_connect($configs["hostname"], $configs["username"], $configs["password"], $configs["database"]);
// check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
} else {
  echo "Connected successfully <br>";
}


//news


$sql = "DROP TABLE IF EXISTS post;";
mysqli_query($conn, $sql);
$sql = "DROP TABLE IF EXISTS system_report;";
mysqli_query($conn, $sql);

$sql = "DROP TABLE IF EXISTS book_forum;";
mysqli_query($conn, $sql);


$sql = "DROP TABLE IF EXISTS user_participate_event";
mysqli_query($conn, $sql);
$sql = "DROP TABLE IF EXISTS event";
mysqli_query($conn, $sql);
$sql = "DROP TABLE IF EXISTS user_follow_user";
mysqli_query($conn, $sql);
$sql = "DROP TABLE IF EXISTS purchase;";
mysqli_query($conn, $sql);
$sql = "DROP TABLE IF EXISTS user_has_credit_card;";
$result = mysqli_query($conn, $sql);

$sql = "DROP TABLE IF EXISTS author_publish_ebook";
$result = mysqli_query($conn, $sql);

$sql = "DROP TABLE IF EXISTS e_book;";
mysqli_query($conn, $sql);

$sql = "DROP TABLE IF EXISTS user_vote_review;";
mysqli_query($conn, $sql);

$sql = "DROP TABLE IF EXISTS book_review;";
mysqli_query($conn, $sql);

$sql = "DROP TABLE IF EXISTS book_genre;";
mysqli_query($conn, $sql);

$sql = "DROP TABLE IF EXISTS sys_admin;";
mysqli_query($conn, $sql);
$sql = "DROP TABLE IF EXISTS sys_author;";
mysqli_query($conn, $sql);
$sql = "DROP TABLE IF EXISTS user_has_book_lists";
$result = mysqli_query($conn, $sql);
$sql = "DROP TABLE IF EXISTS user;";
mysqli_query($conn, $sql);
$sql = "DROP TABLE IF EXISTS book_author;";
mysqli_query($conn, $sql);

$sql = "DROP TABLE IF EXISTS book;";
mysqli_query($conn, $sql);

$sql = "DROP TABLE IF EXISTS publisher;";
mysqli_query($conn, $sql);

$sql = "DROP TABLE IF EXISTS author;";
mysqli_query($conn, $sql);
$sql = "DROP TABLE IF EXISTS genre;";
mysqli_query($conn, $sql);


$sql = "DROP TABLE IF EXISTS credit_card;";
mysqli_query($conn, $sql);


$sql = "CREATE TABLE user (
                 email varchar(255) UNIQUE,
                 user_id INT NOT NULL AUTO_INCREMENT,
                 password varchar(255),
                 PRIMARY KEY (user_id),
                 display_name varchar(255)
                 ) ENGINE=InnoDB;";

if (mysqli_query($conn, $sql)) {
  echo "Table user created successfully \n <br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}

// create sys admin table if not exist
$sql = "CREATE TABLE sys_admin (
                user_id INT NOT NULL,
                phone_number varchar(255),
                PRIMARY KEY (user_id),
                FOREIGN KEY (user_id) REFERENCES user(user_id)
                    on delete cascade
                    on update cascade
                ) ENGINE=InnoDB;";
if (mysqli_query($conn, $sql)) {
  echo "Table sys_admin created successfully \n <br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}

// create sys_author
$sql = "CREATE TABLE sys_author (
                user_id INT NOT NULL,
                website_url varchar(255),
                author_info varchar(255),
                first_name varchar(255),
                last_name varchar(255),
                PRIMARY KEY (user_id),
                FOREIGN KEY (user_id) REFERENCES user(user_id)
                        on delete cascade
                        on update cascade
                ) ENGINE=InnoDB;";
if (mysqli_query($conn, $sql)) {
  echo "Table sys_author created successfully \n <br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}
// crete publisher table
$sql = "CREATE TABLE publisher (
                publisher_id INT NOT NULL AUTO_INCREMENT,
                publisher_name varchar(255),
                email varchar(255),
                website_url varchar(255),
                PRIMARY KEY (publisher_id)
                ) ENGINE=InnoDB;";
if (mysqli_query($conn, $sql)) {
  echo "Table publisher created successfully \n <br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}
// book table
$sql = "CREATE TABLE book (
                book_id INT NOT NULL AUTO_INCREMENT,
                title varchar(255),
                publisher_id INT,
                publish_date DATE,
                PRIMARY KEY (book_id),
                FOREIGN KEY (publisher_id) REFERENCES publisher(publisher_id)
                  on delete set null
                  on update cascade
                 ) ENGINE=InnoDB;";
if (mysqli_query($conn, $sql)) {
  echo "Table book created successfully \n <br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}

// author table
$sql = "CREATE TABLE author (
                author_id INT NOT NULL AUTO_INCREMENT,
                name varchar(255),
                surname varchar(255),
                personal_info varchar(255),
                PRIMARY KEY (author_id)
                 ) ENGINE=InnoDB;";
if (mysqli_query($conn, $sql)) {
  echo "Table author created successfully \n <br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}

// book_author table


$sql = "CREATE TABLE book_author (
                book_id INT NOT NULL,
                author_id INT NOT NULL,
                PRIMARY KEY (book_id, author_id),
                FOREIGN KEY (book_id) REFERENCES book(book_id)
                         on DELETE CASCADE
                         on UPDATE CASCADE,
                FOREIGN KEY (author_id) REFERENCES author(author_id)
                            on DELETE CASCADE
                            on UPDATE CASCADE
                ) ENGINE=InnoDB;";
if (mysqli_query($conn, $sql)) {
  echo "Table book_author created successfully \n <br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}

// genre table
$sql = "CREATE TABLE genre (
                genre_id INT NOT NULL AUTO_INCREMENT,
                genre_name varchar(255),
                genre_info varchar(255), 
                PRIMARY KEY (genre_id)
                 ) ENGINE=InnoDB;";
if (mysqli_query($conn, $sql)) {
  echo "Table genre created successfully \n <br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}

// book_genre table
$sql = "CREATE TABLE book_genre (
                book_id INT NOT NULL,
                genre_id INT NOT NULL,
                PRIMARY KEY (book_id, genre_id),
                FOREIGN KEY (book_id) REFERENCES book(book_id)
                        on delete cascade
                        on update cascade,
                FOREIGN KEY (genre_id) REFERENCES genre(genre_id)
                        on delete cascade
                        on update cascade
                ) ENGINE=InnoDB;";
if (mysqli_query($conn, $sql)) {
  echo "Table book_genre created successfully \n <br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}

// user_has_book table
$sql = "CREATE TABLE user_has_book_lists (
                user_id INT NOT NULL,
                book_id INT NOT NULL,
                book_list_name ENUM('reading', 'read', 'want to read', 'not interested', 'favorite'),
                PRIMARY KEY (user_id, book_id),
                FOREIGN KEY (user_id) REFERENCES user(user_id)
                           on delete cascade
                           on update cascade,
                FOREIGN KEY (book_id) REFERENCES book(book_id)
                            on delete cascade
                            on update cascade
                 ) ENGINE=InnoDB;";
if (mysqli_query($conn, $sql)) {
  echo "Table user_has_book created successfully \n <br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}


// book_review
$sql = "CREATE TABLE book_review (
                book_id INT NOT NULL,
                review_id INT NOT NULL AUTO_INCREMENT,
                user_id INT NOT NULL,
                text varchar(255),
                date DATE,
                rating INT,
                KEY (review_id),
                PRIMARY KEY (book_id, review_id),
                FOREIGN KEY (book_id) REFERENCES book(book_id)
                            on delete cascade
                            on update cascade,
                FOREIGN KEY (user_id) REFERENCES user(user_id) 
                            on delete cascade
                            on update cascade
                ) ENGINE=InnoDB;";
if (mysqli_query($conn, $sql)) {
  echo "Table book_review created successfully \n <br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}

// user vote review
$sql = "CREATE TABLE user_vote_review (
                review_id INT NOT NULL,
                user_id INT NOT NULL,
                book_id INT NOT NULL,
                vote ENUM('up', 'down'),
                PRIMARY KEY (user_id, review_id, book_id),
                FOREIGN KEY (user_id) REFERENCES user(user_id)
                            on delete cascade
                            on update cascade,
                FOREIGN KEY (book_id, review_id) REFERENCES book_review(book_id, review_id)
                            on delete cascade
                            on update cascade
                ) ENGINE=InnoDB;";
if (mysqli_query($conn, $sql)) {
  echo "Table user_vote_review created successfully \n <br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}

// e-book
$sql = "CREATE TABLE e_book (
                book_id INT NOT NULL,
                price INT,
                content VARBINARY(1000000),
                PRIMARY KEY (book_id),
                FOREIGN KEY (book_id) REFERENCES book(book_id)
                            on delete cascade
                            on update cascade
                ) ENGINE=InnoDB;";
if (mysqli_query($conn, $sql)) {
  echo "Table e_book created successfully \n <br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}

// author publish ebook
$sql = "CREATE TABLE author_publish_ebook (
                book_id INT NOT NULL,
                author_id INT NOT NULL,
                date DATE,
                PRIMARY KEY (book_id, author_id),
                FOREIGN KEY (book_id) REFERENCES e_book(book_id)
                            on delete cascade
                            on update cascade
                ) ENGINE=InnoDB;";
if (mysqli_query($conn, $sql)) {
  echo "Table author_publish_ebook created successfully \n <br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}


// credit card table
$sql = "CREATE TABLE credit_card (
                card_id INT NOT NULL AUTO_INCREMENT,
                card_number varchar(255) UNIQUE NOT NULL,
                name_on_card varchar(255),
                due_date_year INT,
                due_date_month INT,
                cvv INT,
                card_type ENUM('visa', 'mastercard', 'american express'),
                balance INT,
                PRIMARY KEY (card_id)
                ) ENGINE=InnoDB;";
if (mysqli_query($conn, $sql)) {
  echo "Table credit_card created successfully \n <br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}

// user has credit card
$sql = "CREATE TABLE user_has_credit_card (
                user_id INT NOT NULL,
                card_id INT NOT NULL,
                PRIMARY KEY (user_id, card_id),
                FOREIGN KEY (user_id) REFERENCES user(user_id)
                            on delete cascade
                            on update cascade,
                FOREIGN KEY (card_id) REFERENCES credit_card(card_id)
                            on delete cascade
                            on update cascade
                ) ENGINE=InnoDB;";
if (mysqli_query($conn, $sql)) {
  echo "Table user_has_credit_card created successfully \n <br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}

// purchase
$sql = "CREATE TABLE purchase (
                purchase_id INT NOT NULL AUTO_INCREMENT,
                user_id INT NOT NULL,
                book_id INT NOT NULL,
                date DATE,
                card_id INT NOT NULL,
                PRIMARY KEY (purchase_id),
                FOREIGN KEY (user_id) REFERENCES user(user_id)
                            on delete cascade
                            on update cascade,
                FOREIGN KEY (book_id) REFERENCES book(book_id)
                            on delete cascade
                            on update cascade,
                FOREIGN KEY (card_id) REFERENCES credit_card(card_id)
                            on delete cascade
                            on update cascade,
                UNIQUE (user_id, book_id)
                ) ENGINE=InnoDB;";
if (mysqli_query($conn, $sql)) {
  echo "Table purchase created successfully \n <br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}

// user follow user
$sql = "CREATE TABLE user_follow_user (
                user_id INT NOT NULL,
                follower_id INT NOT NULL,
                PRIMARY KEY (user_id, follower_id),
                FOREIGN KEY (user_id) REFERENCES user(user_id)
                            on delete cascade
                            on update cascade,
                FOREIGN KEY (follower_id) REFERENCES user(user_id)
                            on delete cascade
                            on update cascade
                ) ENGINE=InnoDB;";
if (mysqli_query($conn, $sql)) {
  echo "Table user_follow_user created successfully \n <br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}

// event table
$sql = "CREATE TABLE event (
                event_id INT NOT NULL AUTO_INCREMENT,
                event_name varchar(255),
                start_date DATE,
                start_time TIME,
                end_time TIME,
                location varchar(255),
                description varchar(255),
                creator_id INT NOT NULL,
                PRIMARY KEY (event_id),
                FOREIGN KEY (creator_id) REFERENCES user(user_id)
                            on delete cascade
                            on update cascade
                ) ENGINE=InnoDB;";
if (mysqli_query($conn, $sql)) {
  echo "Table event created successfully \n <br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}

// user participate event
$sql = "CREATE TABLE user_participate_event (
                user_id INT NOT NULL,
                event_id INT NOT NULL,
                PRIMARY KEY (user_id, event_id),
                FOREIGN KEY (user_id) REFERENCES user(user_id)
                            on delete cascade
                            on update cascade,
                FOREIGN KEY (event_id) REFERENCES event(event_id)
                            on delete cascade
                            on update cascade
                ) ENGINE=InnoDB;";
if (mysqli_query($conn, $sql)) {
  echo "Table user_participate_event created successfully \n <br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}


// book forum

$sql = "create table book_forum(
  
        forum_id int AUTO_INCREMENT,
        title varchar(255) unique not null,
        user_id int not null,
        creationDate Date,
        primary key (forum_id),
        foreign key (user_id) references sys_admin(user_id) on delete cascade   on update cascade) ENGINE=InnoDB;";
if (mysqli_query($conn, $sql)) {
  echo "Table user_participate_event created successfully \n <br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}


// forum post


$sql = "create table post(
        forum_id int NOT NULL ,
        post_id int AUTO_INCREMENT,
        user_id int NOT NULL ,
        text varchar(255) not null,
        date Date not null,
        parent_id int,
        primary key (post_id),
        foreign key (parent_id) references post(post_id) on delete cascade on update cascade,
        foreign key (user_id) references user(user_id) on delete cascade on update cascade ) ENGINE=InnoDB;";


if (mysqli_query($conn, $sql)) {
  echo "Table user_participate_event created successfully \n <br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}


// system report table

$sql = " create table system_report(
        user_id int not null,
        report_id int not null AUTO_INCREMENT,
        comment varchar(255) not null,
        date Date not null,
        primary key (report_id),
        foreign key (user_id) references sys_admin(user_id) on delete cascade on update cascade) ENGINE=InnoDB;";
if (mysqli_query($conn, $sql)) {
  echo "Table user_participate_event created successfully \n <br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}



//set current date automatically when new book review is made
$sql = "CREATE TRIGGER set_date BEFORE INSERT ON book_review FOR EACH ROW SET NEW.date = CURDATE();";



?>