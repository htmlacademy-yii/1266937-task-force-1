CREATE TABLE IF NOT EXISTS categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL,
  icon VARCHAR(50) NOT NULL,
  UNIQUE INDEX idx_name (name)
);

CREATE TABLE IF NOT EXISTS cities (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  lat DECIMAL(10, 8) NOT NULL,
  `long` DECIMAL(11, 8) NOT NULL,
  UNIQUE INDEX idx_name (name)
);

CREATE TABLE IF NOT EXISTS files (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  url VARCHAR(255) NOT NULL,
  file_path VARCHAR(255) NOT NULL,
  name VARCHAR(255) NOT NULL,
  size INT UNSIGNED NOT NULL
);

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  email VARCHAR(255) NOT NULL,
  username VARCHAR(50) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('customer', 'contractor') NOT NULL,
  avatar_id INT UNSIGNED NULL,
  city_id INT UNSIGNED NOT NULL,
  birthday DATE NULL,
  phone CHAR(11) NULL,
  telegram VARCHAR(64) NULL,
  profile_info TEXT NULL,
  UNIQUE INDEX idx_email (email),
  FOREIGN KEY (city_id) REFERENCES cities(id),
  FOREIGN KEY (avatar_id) REFERENCES files(id)
);

CREATE TABLE IF NOT EXISTS user_categories (
  user_id INT UNSIGNED NOT NULL,
  category_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (user_id, category_id),
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE IF NOT EXISTS tasks (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(80) NOT NULL,
  description TEXT NOT NULL,
  budget INT UNSIGNED NULL,
  deadline_at DATETIME NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  customer_id INT UNSIGNED NOT NULL,
  contractor_id INT UNSIGNED NULL,
  category_id INT UNSIGNED NOT NULL,
  city_id INT UNSIGNED NULL,
  location VARCHAR(255) NULL,
  latitude DECIMAL(10, 8) NULL,
  longitude DECIMAL(11, 8) NULL,
  STATUS ENUM(
    'new',
    'canceled',
    'in_progress',
    'completed',
    'failed'
  ) DEFAULT 'new',
  FOREIGN KEY(customer_id) REFERENCES users(id),
  FOREIGN KEY(contractor_id) REFERENCES users(id),
  FOREIGN KEY(category_id) REFERENCES categories(id),
  FOREIGN KEY(city_id) REFERENCES cities(id)
);

CREATE TABLE IF NOT EXISTS task_files (
  task_id INT UNSIGNED NOT NULL,
  file_id INT UNSIGNED NOT NULL,
  PRIMARY KEY(task_id, file_id),
  FOREIGN KEY(task_id) REFERENCES tasks(id),
  FOREIGN KEY(file_id) REFERENCES files(id)
);

CREATE TABLE IF NOT EXISTS responses (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  task_id INT UNSIGNED NOT NULL,
  contractor_id INT UNSIGNED NOT NULL,
  text_comment TEXT NULL,
  price INT UNSIGNED NULL,
  STATUS ENUM('new', 'accepted', 'rejected') NOT NULL DEFAULT 'new',
  FOREIGN KEY(task_id) REFERENCES tasks(id),
  FOREIGN KEY(contractor_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS reviews (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  task_id INT UNSIGNED NOT NULL,
  customer_id INT UNSIGNED NOT NULL,
  contractor_id INT UNSIGNED NOT NULL,
  rating TINYINT UNSIGNED NOT NULL,
  text_comment TEXT NOT NULL,
  FOREIGN KEY(task_id) REFERENCES tasks(id),
  FOREIGN KEY(customer_id) REFERENCES users(id),
  FOREIGN KEY(contractor_id) REFERENCES users(id),
  INDEX idx_contractor_id (contractor_id)
);