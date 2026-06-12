CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS pets (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    name VARCHAR(255) NOT NULL,
    type ENUM('dog', 'cat') NOT NULL,
    breed VARCHAR(255),
    age_category ENUM('puppy', 'adult', 'senior') NOT NULL,
    gender ENUM('male', 'female') NOT NULL,
    location VARCHAR(255),
    description TEXT,
    image VARCHAR(255),
    created_at DATETIME NOT NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_type (type),
    INDEX idx_age_category (age_category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS favorites (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    pet_id INT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (pet_id) REFERENCES pets(id) ON DELETE CASCADE,
    UNIQUE KEY unique_favorite (user_id, pet_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample pet data
INSERT INTO pets (name, type, breed, age_category, gender, location, description, image, created_at) VALUES
('Mochi', 'cat', 'Domestic Shorthair', 'adult', 'female', 'San Francisco, CA', 'A sweet and playful calico cat who loves cuddles.', 'images/pet-card-1.jpg', NOW()),
('Luna', 'cat', 'Ragdoll Mix', 'senior', 'female', 'Seattle, WA', 'A gentle senior cat who enjoys quiet companionship.', 'images/pet-card-2.jpg', NOW()),
('Snowball', 'dog', 'American Eskimo', 'puppy', 'female', 'Portland, OR', 'An energetic puppy who loves outdoor adventures.', 'images/pet-card-3.jpg', NOW()),
('Sylvester', 'cat', 'Maine Coon', 'adult', 'male', 'Los Angeles, CA', 'A majestic Maine Coon with a fluffy coat and friendly personality.', 'images/pet-card-1.jpg', NOW());

-- Additional pet updates and inserts from update_pets.sql
UPDATE pets SET name = 'Oliver' WHERE id = 9;

UPDATE pets SET name = 'Cooper', breed = 'Border Collie', location = 'Austin, TX', age_category = 'adult', description = 'An athletic and intelligent Border Collie who loves outdoor adventures.' WHERE name = 'Snowball';

UPDATE pets SET location = 'Portland, OR', age_category = 'puppy', breed = 'Holland Lop', description = 'A cute and fluffy rabbit who loves to play.' WHERE name = 'Mochi';

UPDATE pets SET location = 'San Francisco, CA', age_category = 'adult', breed = 'Golden Retriever Mix' WHERE name = 'Luna';

INSERT INTO pets (name, type, breed, age_category, gender, location, description, image, created_at) 
VALUES ('Jasper', 'cat', 'Short Hair Mix', 'puppy', 'male', 'Denver, CO', 'A cute tuxedo kitten who loves to play with toys.', 'images/pet-card-1.jpg', NOW());

INSERT INTO pets (name, type, breed, age_category, gender, location, description, image, created_at) 
VALUES ('Bella', 'dog', 'French Bulldog', 'adult', 'female', 'Los Angeles, CA', 'A charming French Bulldog who loves cuddles and short walks.', 'images/pet-card-3.jpg', NOW());
