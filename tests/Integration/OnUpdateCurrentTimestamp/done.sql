DROP TABLE IF EXISTS t;

CREATE TABLE t (
    id INT NOT NULL PRIMARY KEY,
    val INT NOT NULL,
    updated_at TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP
);
