CREATE TABLE student (
  cpf CHAR(11) PRIMARY KEY,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  birthDate DATE NOT NULL,
  address VARCHAR(255) NOT NULL,
  ddd CHAR(2) NOT NULL,
  phoneNumber CHAR(9) NOT NULL
);