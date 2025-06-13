CREATE TABLE toilet_test (
  id SERIAL PRIMARY KEY,
  location POINT,
  name TEXT,
  category TEXT,
  prefecture TEXT,
  municipalities TEXT,
  in_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);