USE twitter;
-- Insert data into users table with email and hashed password using SHA2
--INSERT INTO users (first_name, last_name, email, password) VALUES
--('John', 'Doe', 'john@john.com', SHA2('password123', 256)),
--('Jane', 'Smith', 'jane@jane.com', SHA2('password123', 256)),
--('Alice', 'Johnson', 'alice@alice.com', SHA2('password123', 256)),
--('Bob', 'Williams', 'bob@bob.com', SHA2('password123', 256)),
--('Charlie', 'Brown', 'brown@brown.com', SHA2('password123', 256)),
--('David', 'Miller', 'david@david.com', SHA2('password123', 256)),
--('Eve', 'Davis', 'eve@eve.com', SHA2('password123', 256)),
--('Frank', 'Wilson', 'frank@frank.com', SHA2('password123', 256)),
--('Grace', 'Moore', 'moore@moore.com', SHA2('password123', 256)'),
--('Hannah', 'Taylor', 'taylor@taylor.com', SHA2('password123', 256));

-- Insert data into tweets table
INSERT INTO tweets (user_id, tweet) VALUES
(1, 'Just had a great lunch!'),
(2, 'Loving the new season of my favorite show.'),
(3, 'What a beautiful day!'),
(4, 'Excited about the weekend plans.'),
(5, 'Just finished reading an amazing book.'),
(6, 'Feeling blessed today.'),
(7, 'Can’t wait for the holidays.'),
(8, 'Had a great workout session this morning.'),
(9, 'Learning something new every day.'),
(10, 'Coffee is life.');

-- Insert data into replys table
INSERT INTO replys (tweet_id, user_id, reply) VALUES
(1, 2, 'Sounds delicious!'),
(2, 3, 'Which show are you watching?'),
(3, 4, 'Indeed, it’s wonderful!'),
(4, 5, 'What are your plans?'),
(5, 6, 'I’d love to read that book too!'),
(6, 7, 'You are always so positive!'),
(7, 8, 'Any special plans for the holidays?'),
(8, 9, 'Good for you! Keep it up.'),
(9, 10, 'Learning is the key to success.'),
(10, 1, 'Totally agree!');

