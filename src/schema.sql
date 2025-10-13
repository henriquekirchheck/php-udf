CREATE TABLE student (
  cpf CHAR(11) PRIMARY KEY CONSTRAINT cpf_format CHECK(cpf ~ '^[[:digit:]]{11}$'),
  first_name text NOT NULL,
  last_name text NOT NULL,
  full_name text GENERATED ALWAYS AS (first_name || ' ' || last_name) STORED,
  birthDate date NOT NULL,
  address text NOT NULL,
  ddd text NOT NULL CONSTRAINT ddd_format CHECK(ddd ~ '^[[:digit:]]{2}$'),
  phoneNumber text NOT NULL CONSTRAINT phone_format CHECK(phoneNumber ~ '^[[:digit:]]{9}$')
);
