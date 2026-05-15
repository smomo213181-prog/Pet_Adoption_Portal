CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS pets (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('dog', 'cat') NOT NULL,
    breed VARCHAR(255),
    age_category ENUM('puppy', 'adult', 'senior') NOT NULL,
    gender ENUM('male', 'female') NOT NULL,
    location VARCHAR(255),
    description TEXT,
    image VARCHAR(255),
    created_at DATETIME NOT NULL,
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
