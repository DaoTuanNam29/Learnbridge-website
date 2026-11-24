CREATE DATABASE IF NOT EXISTS learnbridge;
USE learnbridge;

CREATE TABLE IF NOT EXISTS eoi (
  EOInumber     INT AUTO_INCREMENT PRIMARY KEY,
  JobRef        VARCHAR(5)   NOT NULL,
  FirstName     VARCHAR(20)  NOT NULL,
  LastName      VARCHAR(20)  NOT NULL,
  DOB           DATE         NOT NULL,
  Gender        VARCHAR(10)  NOT NULL,
  StreetAddress VARCHAR(40)  NOT NULL,
  Suburb        VARCHAR(40)  NOT NULL,
  State         VARCHAR(3)   NOT NULL,
  Postcode      CHAR(4)      NOT NULL,
  Email         VARCHAR(100) NOT NULL,
  Phone         VARCHAR(12)  NOT NULL,
  Skills        VARCHAR(255) NOT NULL DEFAULT '',
  OtherSkills   TEXT,
  Status        VARCHAR(10)  NOT NULL DEFAULT 'New'
);