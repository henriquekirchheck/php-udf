CREATE TABLE student (
  cpf CHAR(11) PRIMARY KEY CONSTRAINT cpf_format CHECK(VALUE ~ '^[[:digit:]]{11}$'),
  first_name text NOT NULL,
  last_name text NOT NULL,
  full_name text GENERATED ALWAYS AS (concat_ws(' ', first_name, last_name)) STORED,
  birthDate date NOT NULL,
  address text NOT NULL,
  ddd text NOT NULL CONSTRAINT ddd_format CHECK(VALUE ~ '^[[:digit:]]{2}$'),
  phoneNumber text NOT NULL CONSTRAINT phone_format CHECK(VALUE ~ '^[[:digit:]]{9}$')
);
