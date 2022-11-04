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
$sql = "DROP TABLE IF EXISTS user_has_book";
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
                 email VARCHAR(30) UNIQUE,
                 user_id INT NOT NULL,
                 password VARCHAR(20),
                 PRIMARY KEY (user_id),
                 display_name VARCHAR(20)
                 ) ENGINE=InnoDB;";

if (mysqli_query($conn, $sql)) {
  echo "Table user created successfully \n <br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}

// create sys admin table if not exist
$sql = "CREATE TABLE sys_admin (
                user_id INT NOT NULL,
                phone_number VARCHAR(20),
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
                website_url VARCHAR(50),
                author_info VARCHAR(100),
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
                p_id INT NOT NULL,
                p_name VARCHAR(20),
                email VARCHAR(30),
                website_url VARCHAR(50),
                publisher_name VARCHAR(30),
                PRIMARY KEY (p_id)
                ) ENGINE=InnoDB;";
if (mysqli_query($conn, $sql)) {
  echo "Table publisher created successfully \n <br>";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}
// book table
$sql = "CREATE TABLE book (
                book_id INT NOT NULL,
                title VARCHAR(50),
                publisher_id INT,
                publish_date DATE,
                PRIMARY KEY (book_id),
                FOREIGN KEY (publisher_id) REFERENCES publisher(p_id)
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
                author_id INT NOT NULL,
                name VARCHAR(20),
                surname VARCHAR(20),
                personal_info VARCHAR(100),
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
                genre_id INT NOT NULL,
                genre_info VARCHAR(100), 
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
$sql = "CREATE TABLE user_has_book (
                user_id INT NOT NULL,
                book_id INT NOT NULL,
                reading_status ENUM('reading', 'read', 'want to read', 'not interested', 'favorite'),
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
                review_id INT NOT NULL,
                user_id INT NOT NULL,
                text VARCHAR(1000),
                date DATE,
                rating INT,
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
                            on update cascade,
                FOREIGN KEY (author_id) REFERENCES author(author_id)
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
                card_id INT NOT NULL,
                card_number VARCHAR(16),
                name_on_card VARCHAR(50),
                due_date_year INT,
                due_date_month INT,
                cvv INT,
                card_type ENUM('visa', 'mastercard', 'american express'),
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
                purchase_id INT NOT NULL,
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
                            on update cascade
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
                event_id INT NOT NULL,
                event_name VARCHAR(50),
                start_date DATE,
                end_date DATE,
                location VARCHAR(50),
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

