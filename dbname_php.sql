CREATE TABLE users
(
  user_id numeric(10,0) NOT NULL,
  username character varying(30) NOT NULL,
  email character varying(40) NOT NULL,
  nope character varying(20) NOT NULL,
  password character varying(20) NOT NULL,
  create_date date NOT NULL,
  name character varying(40) NOT NULL,
  CONSTRAINT users_pkey PRIMARY KEY (user_id),
  CONSTRAINT uk_email_uts UNIQUE (email),
  CONSTRAINT uk_no_pe UNIQUE (nope),
  CONSTRAINT uk_uname UNIQUE (username)
);

CREATE TABLE category
(
  category_id numeric(10,0) NOT NULL,
  nama_kategori character varying(30) NOT NULL,
  create_date date NOT NULL,
  create_by character varying(30) NOT NULL,
  update_date date NOT NULL,
  update_by character varying(30),
  CONSTRAINT category_pkey PRIMARY KEY (category_id),
  CONSTRAINT uk_nm_kategori UNIQUE (nama_kategori)
);

CREATE TABLE products
(
  products_id numeric(10,0) NOT NULL,
  category_id numeric(10,0) NOT NULL,
  nama_produk character varying(30) NOT NULL,
  entry_date date NOT NULL,
  exp_date date NOT NULL,
  create_date date NOT NULL,
  create_by character varying(30) NOT NULL,
  update_date date NOT NULL,
  update_by character varying(30),
  image character varying(200) NOT NULL,
  CONSTRAINT products_pkey PRIMARY KEY (products_id),
  CONSTRAINT fk_cat_prod FOREIGN KEY (category_id)
      REFERENCES category (category_id),
  CONSTRAINT uk_image UNIQUE (image)
);


CREATE TABLE products_detail
(
  products_detail_id numeric(10,0) NOT NULL,
  products_id numeric(10,0) NOT NULL,
  valid_form date NOT NULL,
  valid_until date NOT NULL,
  create_date date NOT NULL,
  create_by character varying(30) NOT NULL,
  update_date date NOT NULL,
  update_by character varying(30),
  price numeric(10,0),
  CONSTRAINT products_detail_pkey PRIMARY KEY (products_detail_id),
  CONSTRAINT fk_cat_proddetail FOREIGN KEY (products_id)
      REFERENCES products (products_id)
);