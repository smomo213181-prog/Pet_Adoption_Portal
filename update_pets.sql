-- Update Sylvester to Oliver
UPDATE pets SET name = 'Oliver' WHERE id = 9;

-- Update Snowball to Cooper
UPDATE pets SET name = 'Cooper', breed = 'Border Collie', location = 'Austin, TX', age_category = 'adult', description = 'An athletic and intelligent Border Collie who loves outdoor adventures.' WHERE name = 'Snowball';

-- Update Mochi's details
UPDATE pets SET location = 'Portland, OR', age_category = 'puppy', breed = 'Holland Lop', description = 'A cute and fluffy rabbit who loves to play.' WHERE name = 'Mochi';

-- Update Luna's details
UPDATE pets SET location = 'San Francisco, CA', age_category = 'adult', breed = 'Golden Retriever Mix' WHERE name = 'Luna';

-- Insert Jasper (cat, Short Hair Mix)
INSERT INTO pets (name, type, breed, age_category, gender, location, description, image, created_at) 
VALUES ('Jasper', 'cat', 'Short Hair Mix', 'puppy', 'male', 'Denver, CO', 'A cute tuxedo kitten who loves to play with toys.', 'images/pet-card-1.jpg', NOW());

-- Insert Bella (dog, French Bulldog)
INSERT INTO pets (name, type, breed, age_category, gender, location, description, image, created_at) 
VALUES ('Bella', 'dog', 'French Bulldog', 'adult', 'female', 'Los Angeles, CA', 'A charming French Bulldog who loves cuddles and short walks.', 'images/pet-card-3.jpg', NOW());
