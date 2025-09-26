CREATE TABLE planting (
    id INT(11) NOT NULL AUTO_INCREMENT,
    farmer_id INT(11) NOT NULL,
    crop_name VARCHAR(255) NOT NULL,
    planting_date DATE NOT NULL,
    harvest_date DATE DEFAULT NULL,
    status VARCHAR(100) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (farmer_id) REFERENCES farmers(id) ON DELETE CASCADE ON UPDATE CASCADE
);