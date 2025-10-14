CREATE TABLE student (
  cpf text PRIMARY KEY CONSTRAINT cpf_format CHECK(cpf ~ '^[[:digit:]]{11}$'),
  first_name text NOT NULL,
  last_name text NOT NULL,
  full_name text GENERATED ALWAYS AS (first_name || ' ' || last_name) STORED,
  birth_date date NOT NULL,
  address text NOT NULL,
  ddd text NOT NULL CONSTRAINT ddd_format CHECK(ddd ~ '^[[:digit:]]{2}$'),
  phone_number text NOT NULL CONSTRAINT phone_format CHECK(phone_number ~ '^[[:digit:]]{9}$')
);

CREATE INDEX search_idx ON student
USING bm25 (cpf, full_name)
WITH (key_field='cpf');