CREATE DATABASE taskforce
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE taskforce;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  auth_key VARCHAR(255) NOT NULL,
  birthdate DATETIME,
  phone VARCHAR(11),
  telegram VARCHAR(64),
  about VARCHAR(255),
  city_id INT NOT NULL,
  avatar_id INT,
  date_created DATETIME DEFAULT (now())
);

CREATE INDEX idx_email ON users (email);
CREATE INDEX idx_city_id ON users (city_id);
CREATE INDEX idx_date_created ON users (date_created);

CREATE TABLE tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  price INT NOT NULL,
  published_at DATETIME DEFAULT (now()),
  expired_at DATETIME,
  current_status VARCHAR(255) NOT NULL,
  category_id INT NOT NULL,
  client_id INT NOT NULL,
  worker_id INT,
  city_id INT NOT NULL
);

CREATE INDEX idx_price ON tasks (price);
CREATE INDEX idx_published_at ON tasks (published_at);
CREATE INDEX idx_current_status ON tasks (current_status);
CREATE INDEX idx_category_id ON tasks (category_id);
CREATE INDEX idx_worker_id ON tasks (worker_id);
CREATE INDEX idx_city_id ON tasks (city_id);

CREATE TABLE reactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  worker_id INT NOT NULL,
  task_id INT NOT NULL,
  worker_price INT NOT NULL,
  comment VARCHAR(255),
  date_created DATETIME DEFAULT (now())
);

CREATE INDEX idx_task_id ON reactions (task_id);

CREATE TABLE feedbacks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  client_id INT NOT NULL,
  worker_id INT NOT NULL,
  task_id INT NOT NULL,
  comment VARCHAR(255),
  rating INT COMMENT 'от 1 до 5' NOT NULL,
  date_created DATETIME DEFAULT (now())
);

CREATE INDEX idx_rating ON feedbacks (rating);
CREATE INDEX idx_date_created ON feedbacks (date_created);

CREATE TABLE files (
  id INT AUTO_INCREMENT PRIMARY KEY,
  file_path VARCHAR(255) NOT NULL
);

CREATE TABLE task_files (
  id INT AUTO_INCREMENT PRIMARY KEY,
  task_id INT NOT NULL,
  file_id INT NOT NULL
);

CREATE INDEX idx_task_id ON task_files (task_id);

CREATE TABLE cities (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  latitude VARCHAR(255),
  longitude VARCHAR(255)
);

CREATE INDEX idx_name ON cities (name);

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  icon VARCHAR(20) NOT NULL
);

CREATE TABLE user_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL
);

CREATE INDEX idx_category_id ON user_categories (category_id);
CREATE INDEX idx_user_id ON user_categories (user_id);

CREATE INDEX idx_name ON categories (name);

ALTER TABLE users ADD FOREIGN KEY (city_id) REFERENCES cities (id);
ALTER TABLE users ADD FOREIGN KEY (avatar_id) REFERENCES files (id);

ALTER TABLE tasks ADD FOREIGN KEY (city_id) REFERENCES cities (id);

ALTER TABLE task_files ADD FOREIGN KEY (task_id) REFERENCES tasks (id);
ALTER TABLE task_files ADD FOREIGN KEY (file_id) REFERENCES files (id);

ALTER TABLE tasks ADD FOREIGN KEY (category_id) REFERENCES categories (id);
ALTER TABLE tasks ADD FOREIGN KEY (client_id) REFERENCES users (id);
ALTER TABLE tasks ADD FOREIGN KEY (worker_id) REFERENCES users (id);

ALTER TABLE feedbacks ADD FOREIGN KEY (task_id) REFERENCES tasks (id);
ALTER TABLE feedbacks ADD FOREIGN KEY (client_id) REFERENCES users (id);

ALTER TABLE reactions ADD FOREIGN KEY (worker_id) REFERENCES users (id);
ALTER TABLE reactions ADD FOREIGN KEY (task_id) REFERENCES tasks (id);

ALTER TABLE user_categories ADD FOREIGN KEY (user_id) REFERENCES users (id);
ALTER TABLE user_categories ADD FOREIGN KEY (category_id) REFERENCES categories (id);
