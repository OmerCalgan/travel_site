tour_locationsCREATE DATABASE travel_site;

USE travel_site;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE tours (
    tour_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tour_id INT NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (tour_id) REFERENCES tours(tour_id)
);

CREATE TABLE reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    tour_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 5) NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tour_id) REFERENCES tours(tour_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);


DELIMITER //

CREATE PROCEDURE RegisterUser(IN p_username VARCHAR(50), IN p_password VARCHAR(255), IN p_email VARCHAR(100))
BEGIN
    DECLARE userCount INT;
    SET userCount = (SELECT COUNT(*) FROM users WHERE username = p_username OR email = p_email);
    
    IF userCount = 0 THEN
        INSERT INTO users (username, password, email) VALUES (p_username, p_password, p_email);
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Username or email already exists';
    END IF;
END //

CREATE PROCEDURE LoginUser(IN p_username VARCHAR(50), IN p_password VARCHAR(255), OUT p_user_id INT)
BEGIN
    DECLARE db_password VARCHAR(255);
    SET p_user_id = NULL;

    SELECT user_id, password INTO p_user_id, db_password FROM users WHERE username = p_username;
    
    IF p_user_id IS NOT NULL AND db_password = p_password THEN
        -- Successful login
        SET p_user_id = p_user_id;
    ELSE
        -- Failed login
        SET p_user_id = NULL;
    END IF;
END //

CREATE PROCEDURE AddTour(IN p_title VARCHAR(255), IN p_description TEXT, IN p_price DECIMAL(10,2), IN p_start_date DATE, IN p_end_date DATE)
BEGIN
    INSERT INTO tours (title, description, price, start_date, end_date) VALUES (p_title, p_description, p_price, p_start_date, p_end_date);
END //

CREATE PROCEDURE BookTour(IN p_user_id INT, IN p_tour_id INT)
BEGIN
    DECLARE bookingCount INT;
    SET bookingCount = (SELECT COUNT(*) FROM bookings WHERE user_id = p_user_id AND tour_id = p_tour_id AND status = 'pending');

    IF bookingCount = 0 THEN
        INSERT INTO bookings (user_id, tour_id) VALUES (p_user_id, p_tour_id);
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'You already have a pending booking for this tour';
    END IF;
END //

CREATE PROCEDURE AddReview(IN p_tour_id INT, IN p_user_id INT, IN p_rating INT, IN p_comment TEXT)
BEGIN
    INSERT INTO reviews (tour_id, user_id, rating, comment) VALUES (p_tour_id, p_user_id, p_rating, p_comment);
END //

CREATE TRIGGER before_user_insert
BEFORE INSERT ON users
FOR EACH ROW
BEGIN
    SET NEW.created_at = NOW();
    SET NEW.updated_at = NOW();
END //

CREATE TRIGGER before_user_update
BEFORE UPDATE ON users
FOR EACH ROW
BEGIN
    SET NEW.updated_at = NOW();
END //

CREATE TRIGGER before_booking_insert
BEFORE INSERT ON bookings
FOR EACH ROW
BEGIN
    IF NEW.status = 'confirmed' THEN
        SET NEW.booking_date = NOW();
    END IF;
END //

CREATE TRIGGER before_booking_update
BEFORE UPDATE ON bookings
FOR EACH ROW
BEGIN
    IF NEW.status = 'confirmed' AND OLD.status <> 'confirmed' THEN
        SET NEW.booking_date = NOW();
    END IF;
END //

CREATE TRIGGER before_review_insert
BEFORE INSERT ON reviews
FOR EACH ROW
BEGIN
    SET NEW.created_at = NOW();
END //

CREATE TRIGGER before_review_update
BEFORE UPDATE ON reviews
FOR EACH ROW
BEGIN
    SET NEW.created_at = NOW();
END //

CREATE FUNCTION GetUserCount() 
RETURNS INT
BEGIN
    DECLARE user_count INT;
    SELECT COUNT(*) INTO user_count FROM users;
    RETURN user_count;
END //

CREATE FUNCTION GetAverageRating(tour_id INT) 
RETURNS DECIMAL(3,2)
BEGIN
    DECLARE avg_rating DECIMAL(3,2);
    SELECT AVG(rating) INTO avg_rating FROM reviews WHERE reviews.tour_id = tour_id;
    RETURN avg_rating;
END //

CREATE FUNCTION GetUserBookingCount(user_id INT) 
RETURNS INT
BEGIN
    DECLARE booking_count INT;
    SELECT COUNT(*) INTO booking_count FROM bookings WHERE bookings.user_id = user_id;
    RETURN booking_count;
END //

DELIMITER ;

