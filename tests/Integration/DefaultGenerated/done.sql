DROP TABLE IF EXISTS t;

CREATE TABLE t (
    d1 DATETIME NOT NULL DEFAULT NOW(),
    d2 DATETIME NOT NULL DEFAULT '2019-12-31 23:59:59'
);