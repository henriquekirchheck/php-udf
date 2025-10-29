CREATE TABLE student (
  cpf text PRIMARY KEY CHECK(cpf ~ "[[:digit:]]{11}"),
  name text NOT NULL,
  birth_date timestamptz NOT NULL,
  gender text,
  email text UNIQUE NOT NULL,
  address_cep text REFERENCES address (cep),
  git text,
  created_at timestamptz DEFAULT now(),
  updated_at timestamptz DEFAULT now()
);

CREATE TABLE address (
  cep text PRIMARY KEY CHECK(cep ~ "[[:digit:]]{8}"),
  street text NOT NULL,
  district text NOT NULL,
  city_id text REFERENCES city (id)
);

CREATE TABLE city (
  id int PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
  name text NOT NULL,
  uf text CHECK (uf ~ "[[:alpha:]]{2}")
);

CREATE INDEX search_idx ON student
USING bm25 (cpf, email, name)
WITH (key_field='cpf', text_fields='{"name": {"tokenizer": {"type": "ngram", "min_gram": 1, "max_gram": 5, "prefix_only": false}}, "email": {"tokenizer": {"type": "ngram", "min_gram": 1, "max_gram": 5, "prefix_only": false}}}');

CREATE OR REPLACE FUNCTION update_modified_column()
RETURNS TRIGGER AS $$
BEGIN
NEW.updated_at = now();
RETURN NEW;
END;
$$ language 'plpgsql';

CREATE TRIGGER update_modified_time BEFORE UPDATE ON student FOR EACH ROW EXECUTE PROCEDURE update_modified_column();
