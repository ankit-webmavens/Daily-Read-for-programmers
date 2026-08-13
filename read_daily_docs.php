<?php
// 2026-08-13 06:55:41
/*
Topic: MySQL Indexing for Efficient Database Queries

Explanation: In MySQL, indexing is a technique used to improve the performance of database queries by quickly locating specific data within a table. An index is a data structure that associates a specific value with a row in a table, allowing the database to retrieve data more efficiently. Indexing can be used on columns that are frequently used in WHERE, JOIN, and ORDER BY clauses.

Code Example:
```php
// Create a table with a large number of rows
CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255),
  email VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

// Insert 100000 rows into the table
SET @i = 1;
WHILE @i <= 100000 DO
  INSERT INTO users (name, email)
  VALUES (CONCAT('User', @i), CONCAT('user', @i, '@example.com'));
  SET @i = @i + 1;
END WHILE;

// Create an index on the email column
CREATE INDEX idx_email ON users (email);

// Run a query that uses the index
SELECT * FROM users WHERE email = 'user10000@example.com';

// Explain the query to view the index usage
EXPLAIN SELECT * FROM users WHERE email = 'user10000@example.com';

// Drop the index
DROP INDEX idx_email ON users;
```
*/
