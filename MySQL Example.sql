mysql> DESCRIBE STUDENTS;
+-------+-------------+------+-----+---------+----------------+
| Field | Type        | Null | Key | Default | Extra          |
+-------+-------------+------+-----+---------+----------------+
| ID    | int         | NO   | PRI | NULL    | auto_increment |
| NAME  | varchar(32) | NO   |     | NULL    |                |
| CLASS | int         | NO   |     | NULL    |                |
+-------+-------------+------+-----+---------+----------------+
3 rows in set (0.00 sec)


mysql> SELECT * FROM STUDENTS;
+----+---------+-------+
| ID | NAME    | CLASS |
+----+---------+-------+
|  1 | MANI    |     8 |
|  2 | BHUVAN  |     6 |
|  3 | SAI     |     5 |
|  4 | SREE    |     4 |
|  5 | SATYA   |     5 |
|  6 | BALU    |     7 |
|  7 | SIVA    |     8 |
|  8 | NAGA    |     8 |
|  9 | NITHIN  |     4 |
| 10 | KRISHNA |     9 |
+----+---------+-------+
10 rows in set (0.00 sec)


mysql> DESCRIBE BOOKS;
+--------------+-------------+------+-----+---------+----------------+
| Field        | Type        | Null | Key | Default | Extra          |
+--------------+-------------+------+-----+---------+----------------+
| ID           | int         | NO   | PRI | NULL    | auto_increment |
| STUDENT_ID   | int         | NO   | MUL | NULL    |                |
| BOOK_NAME    | varchar(32) | NO   |     | NULL    |                |
| BOOK_SECTION | varchar(32) | NO   |     | NULL    |                |
+--------------+-------------+------+-----+---------+----------------+
4 rows in set (0.00 sec)

mysql> SELECT * FROM BOOKS;
+----+------------+-----------+--------------+
| ID | STUDENT_ID | BOOK_NAME | BOOK_SECTION |
+----+------------+-----------+--------------+
|  1 |          1 | PHYSICS   | SCIENCE      |
|  2 |          1 | SCIENCE   | SCIENCE      |
|  3 |          2 | ECONOMICS | SOCIAL       |
|  4 |          3 | CHEMISTRY | SCIENCE      |
|  5 |          6 | BIOLOGY   | SCIENCE      |
|  6 |          6 | HISTORY   | SOCIAL       |
|  7 |          4 | MATHS     |              |
|  8 |          8 | PHYSICS   | SCIENCE      |
|  9 |          9 | CIVICS    | SOCIAL       |
| 10 |         10 | ALGEBRA   | MATHS        |
+----+------------+-----------+--------------+
10 rows in set (0.00 sec)


mysql> DESCRIBE BOOK_DESCRIPTION;
+-------------+--------------+------+-----+---------+----------------+
| Field       | Type         | Null | Key | Default | Extra          |
+-------------+--------------+------+-----+---------+----------------+
| ID          | int          | NO   | PRI | NULL    | auto_increment |
| STUDENT_ID  | int          | NO   | MUL | NULL    |                |
| BOOK_ID     | int          | NO   | MUL | NULL    |                |
| DESCRIPTION | varchar(100) | NO   |     | NULL    |                |
+-------------+--------------+------+-----+---------+----------------+
4 rows in set (0.00 sec)


mysql> SELECT * FROM BOOK_DESCRIPTION;
+----+------------+---------+----------------+
| ID | STUDENT_ID | BOOK_ID | DESCRIPTION    |
+----+------------+---------+----------------+
|  1 |          1 |       1 | NICE PHYSICS   |
|  2 |          1 |       2 | EXCELLENT BOOK |
|  3 |          5 |       7 | NO BOOK        |
|  4 |          6 |       5 | POOR BIOLOGY   |
|  5 |          9 |       9 | AVERAGE CIVICS |
+----+------------+---------+----------------+
5 rows in set (0.00 sec)


mysql> SELECT STUDENTS.name as STUDENT_name, STUDENTS.CLASS ,BOOKS.BOOK_NAME, BOOK_DESCRIPTION.DESCRIPTION FROM STUDENTS
    -> INNER JOIN BOOKS
    -> ON BOOKS.STUDENT_ID = STUDENTS.ID
    -> INNER JOIN BOOK_DESCRIPTION
    -> ON BOOK_DESCRIPTION.BOOK_ID = BOOKS.ID;
+--------------+-------+-----------+----------------+
| STUDENT_name | CLASS | BOOK_NAME | DESCRIPTION    |
+--------------+-------+-----------+----------------+
| MANI         |     8 | PHYSICS   | NICE PHYSICS   |
| MANI         |     8 | SCIENCE   | EXCELLENT BOOK |
| SREE         |     4 | MATHS     | NO BOOK        |
| BALU         |     7 | BIOLOGY   | POOR BIOLOGY   |
| NITHIN       |     4 | CIVICS    | AVERAGE CIVICS |
+--------------+-------+-----------+----------------+
5 rows in set (0.00 sec)


mysql> SELECT STUDENTS.name as STUDENT_name, STUDENTS.CLASS ,BOOKS.BOOK_NAME, BOOK_DESCRIPTION.DESCRIPTION FROM STUDENTS
    -> LEFT JOIN BOOKS
    -> ON BOOKS.STUDENT_ID = STUDENTS.ID
    -> LEFT JOIN BOOK_DESCRIPTION
    -> ON BOOK_DESCRIPTION.BOOK_ID = BOOKS.ID;
+--------------+-------+-----------+----------------+
| STUDENT_name | CLASS | BOOK_NAME | DESCRIPTION    |
+--------------+-------+-----------+----------------+
| MANI         |     8 | PHYSICS   | NICE PHYSICS   |
| MANI         |     8 | SCIENCE   | EXCELLENT BOOK |
| BHUVAN       |     6 | ECONOMICS | NULL           |
| SAI          |     5 | CHEMISTRY | NULL           |
| SREE         |     4 | MATHS     | NO BOOK        |
| SATYA        |     5 | NULL      | NULL           |
| BALU         |     7 | BIOLOGY   | POOR BIOLOGY   |
| BALU         |     7 | HISTORY   | NULL           |
| SIVA         |     8 | NULL      | NULL           |
| NAGA         |     8 | PHYSICS   | NULL           |
| NITHIN       |     4 | CIVICS    | AVERAGE CIVICS |
| KRISHNA      |     9 | ALGEBRA   | NULL           |
+--------------+-------+-----------+----------------+
12 rows in set (0.09 sec)

mysql> SELECT STUDENTS.name as STUDENT_name, STUDENTS.CLASS ,BOOKS.BOOK_NAME, BOOK_DESCRIPTION.DESCRIPTION FROM STUDENTS
    -> RIGHT JOIN BOOKS
    -> ON BOOKS.STUDENT_ID = STUDENTS.ID
    -> RIGHT JOIN BOOK_DESCRIPTION
    -> ON BOOK_DESCRIPTION.BOOK_ID = BOOKS.ID;
+--------------+-------+-----------+----------------+
| STUDENT_name | CLASS | BOOK_NAME | DESCRIPTION    |
+--------------+-------+-----------+----------------+
| MANI         |     8 | PHYSICS   | NICE PHYSICS   |
| MANI         |     8 | SCIENCE   | EXCELLENT BOOK |
| SREE         |     4 | MATHS     | NO BOOK        |
| BALU         |     7 | BIOLOGY   | POOR BIOLOGY   |
| NITHIN       |     4 | CIVICS    | AVERAGE CIVICS |
| SREE         |     4 | MATHS     | EASY MATHS     |
| NULL         |  NULL | HINDI     | EASY LEARN     |
+--------------+-------+-----------+----------------+
7 rows in set (0.00 sec)

mysql> SELECT STUDENTS.name as STUDENT_name, STUDENTS.CLASS ,BOOKS.BOOK_NAME, BOOK_DESCRIPTION.DESCRIPTION FROM STUDENTS
    -> FULL JOIN BOOKS
    -> ON BOOKS.STUDENT_ID = STUDENTS.ID
    -> FULL JOIN BOOK_DESCRIPTION
    -> ON BOOK_DESCRIPTION.BOOK_ID = BOOKS.ID;
+--------------+-------+-----------+----------------+
| STUDENT_name | CLASS | BOOK_NAME | DESCRIPTION    |
+--------------+-------+-----------+----------------+
| MANI         |     8 | PHYSICS   | NICE PHYSICS   |
| MANI         |     8 | SCIENCE   | EXCELLENT BOOK |
| BHUVAN       |     6 | ECONOMICS | NULL           |
| SAI          |     5 | CHEMISTRY | NULL           |
| SREE         |     4 | MATHS     | NO BOOK        |
| SATYA        |     5 | NULL      | NULL           |
| BALU         |     7 | BIOLOGY   | POOR BIOLOGY   |
| BALU         |     7 | HISTORY   | NULL           |
| SIVA         |     8 | NULL      | NULL           |
| NAGA         |     8 | PHYSICS   | NULL           |
| NITHIN       |     4 | CIVICS    | AVERAGE CIVICS |
| KRISHNA      |     9 | ALGEBRA   | NULL           |
| NULL         |  NULL | HINDI     | EASY LEARN     |
| NULL         |  NULL | TELUGU    | NULL           |
+--------------+-------+-----------+----------------+
14 rows in set (0.01 sec)

VIEW
mysql> CREATE VIEW STUDENT_LEFTVIEW AS
    -> SELECT STUDENTS.name as STUDENT_name, STUDENTS.CLASS ,BOOKS.BOOK_NAME, BOOK_DESCRIPTION.DESCRIPTION FROM STUDENTS
    -> LEFT JOIN BOOKS
    -> ON BOOKS.STUDENT_ID = STUDENTS.ID
    -> LEFT JOIN BOOK_DESCRIPTION
    -> ON BOOK_DESCRIPTION.BOOK_ID = BOOKS.ID;
Query OK, 0 rows affected (0.01 sec)

mysql> select * from student_leftview;
+--------------+-------+-----------+----------------+
| STUDENT_name | CLASS | BOOK_NAME | DESCRIPTION    |
+--------------+-------+-----------+----------------+
| MANI         |     8 | SCIENCE   | EXCELLENT BOOK |
| MANI         |     8 | PHYSICS   | NICE PHYSICS   |
| BHUVAN       |     6 | ECONOMICS | NULL           |
| SAI          |     5 | CHEMISTRY | NULL           |
| SREE         |     4 | MATHS     | EASY MATHS     |
| SREE         |     4 | MATHS     | NO BOOK        |
| SATYA        |     5 | NULL      | NULL           |
| BALU         |     7 | HISTORY   | NULL           |
| BALU         |     7 | BIOLOGY   | POOR BIOLOGY   |
| SIVA         |     8 | NULL      | NULL           |
| NAGA         |     8 | PHYSICS   | NULL           |
| NITHIN       |     4 | CIVICS    | AVERAGE CIVICS |
| KRISHNA      |     9 | ALGEBRA   | NULL           |
+--------------+-------+-----------+----------------+
13 rows in set (0.01 sec)

mysql> SELECT STUDENTS.name as STUDENT_name, STUDENTS.CLASS ,BOOKS.BOOK_NAME, BOOK_DESCRIPTION.DESCRIPTION FROM STUDENTS
    -> LEFT JOIN BOOKS^C
mysql> CREATE VIEW STUDENT_RIGHTVIEW AS
    -> SELECT STUDENTS.name as STUDENT_name, STUDENTS.CLASS ,BOOKS.BOOK_NAME, BOOK_DESCRIPTION.DESCRIPTION FROM STUDENTS
    -> RIGHT JOIN BOOKS
    -> ON BOOKS.STUDENT_ID = STUDENTS.ID
    -> RIGHT JOIN BOOK_DESCRIPTION
    -> ON BOOK_DESCRIPTION.BOOK_ID = BOOKS.ID;
Query OK, 0 rows affected (0.01 sec)

mysql> SELECT * FROM STUDENT_RIGHTVIEW;
+--------------+-------+-----------+----------------+
| STUDENT_name | CLASS | BOOK_NAME | DESCRIPTION    |
+--------------+-------+-----------+----------------+
| MANI         |     8 | PHYSICS   | NICE PHYSICS   |
| MANI         |     8 | SCIENCE   | EXCELLENT BOOK |
| SREE         |     4 | MATHS     | NO BOOK        |
| BALU         |     7 | BIOLOGY   | POOR BIOLOGY   |
| NITHIN       |     4 | CIVICS    | AVERAGE CIVICS |
| SREE         |     4 | MATHS     | EASY MATHS     |
| NULL         |  NULL | HINDI     | EASY LEARN     |
+--------------+-------+-----------+----------------+
7 rows in set (0.00 sec)


FUNCTION

//SHOWS THE STRINGS TO LOWER CASE//

mysql> SELECT NAME, LOWER(NAME) FROM STUDENTS;
+---------+-------------+
| NAME    | LOWER(NAME) |
+---------+-------------+
| MANI    | mani        |
| BHUVAN  | bhuvan      |
| SAI     | sai         |
| SREE    | sree        |
| SATYA   | satya       |
| BALU    | balu        |
| SIVA    | siva        |
| NAGA    | naga        |
| NITHIN  | nithin      |
| KRISHNA | krishna     |
+---------+-------------+
10 rows in set (0.00 sec)



//SHOWS THE ADDITION VALUE TO COLUMN//

mysql> SELECT NAME, CLASS+1 FROM STUDENTS;
+---------+---------+
| NAME    | CLASS+1 |
+---------+---------+
| MANI    |       9 |
| BHUVAN  |       7 |
| SAI     |       6 |
| SREE    |       5 |
| SATYA   |       6 |
| BALU    |       8 |
| SIVA    |       9 |
| NAGA    |       9 |
| NITHIN  |       5 |
| KRISHNA |      10 |
+---------+---------+
10 rows in set (0.00 sec)



//SHOWS STRING LENGTH IN COLUMN//

mysql> SELECT NAME, LENGTH(NAME) FROM STUDENTS;
+---------+--------------+
| NAME    | LENGTH(NAME) |
+---------+--------------+
| MANI    |            4 |
| BHUVAN  |            6 |
| SAI     |            3 |
| SREE    |            4 |
| SATYA   |            5 |
| BALU    |            4 |
| SIVA    |            4 |
| NAGA    |            4 |
| NITHIN  |            6 |
| KRISHNA |            7 |
+---------+--------------+
10 rows in set (0.00 sec)


mysql> SELECT * FROM STUDENTS
    -> WHERE LENGTH(NAME)=4;
+----+------+-------+
| ID | NAME | CLASS |
+----+------+-------+
|  1 | MANI |     8 |
|  4 | SREE |     4 |
|  6 | BALU |     7 |
|  7 | SIVA |     8 |
|  8 | NAGA |     8 |
+----+------+-------+
5 rows in set (0.00 sec)


INDEX

//BEFORE ADDING INDEX

mysql> SELECT * FROM STUDENTS
    -> WHERE CLASS = 4;
+----+--------+-------+
| ID | NAME   | CLASS |
+----+--------+-------+
|  4 | SREE   |     4 |
|  9 | NITHIN |     4 |
+----+--------+-------+
2 rows in set (0.00 sec)

mysql> EXPLAIN SELECT * FROM STUDENTS
    -> WHERE CLASS = 4;
+----+-------------+----------+------------+------+---------------+------+---------+------+------+----------+-------------+
| id | select_type | table    | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra       |
+----+-------------+----------+------------+------+---------------+------+---------+------+------+----------+-------------+
|  1 | SIMPLE      | STUDENTS | NULL       | ALL  | NULL          | NULL | NULL    | NULL |    9 |    11.11 | Using where |
+----+-------------+----------+------------+------+---------------+------+---------+------+------+----------+-------------+
1 row in set, 1 warning (0.00 sec)


//AFTER ADDING INDEX

mysql> CREATE INDEX IDX_CLASS ON STUDENTS(CLASS);
Query OK, 0 rows affected (0.03 sec)
Records: 0  Duplicates: 0  Warnings: 0

mysql> EXPLAIN SELECT * FROM STUDENTS
    -> WHERE CLASS = 4;
+----+-------------+----------+------------+------+---------------+-----------+---------+-------+------+----------+-------+
| id | select_type | table    | partitions | type | possible_keys | key       | key_len | ref   | rows | filtered | Extra |
+----+-------------+----------+------------+------+---------------+-----------+---------+-------+------+----------+-------+
|  1 | SIMPLE      | STUDENTS | NULL       | ref  | IDX_CLASS     | IDX_CLASS | 4       | const |    2 |   100.00 | NULL  |
+----+-------------+----------+------------+------+---------------+-----------+---------+-------+------+----------+-------+
1 row in set, 1 warning (0.00 sec)