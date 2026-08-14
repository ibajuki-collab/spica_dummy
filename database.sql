CREATE DATABASE IF NOT EXISTS spica_dummy CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE spica_dummy;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

INSERT INTO admins (username,password) VALUES
('admin', '$2y$12$I7Xl6L3oc0uEltOEHrmgaOTWpcXLM12hvIRRcUWFUy2NeZxPAV7ny');

CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    icon VARCHAR(20) NOT NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO services (name,icon,description) VALUES
('P&I Claims','⚓','Support and coordination for protection and indemnity related matters.'),
('Cargo Claims','📦','Sample information and support around cargo-related incidents and claims.'),
('Legal Support','⚖️','Professional coordination for maritime matters requiring legal attention.'),
('Marine Survey','🔎','Dummy service card for marine inspection and survey-related support.');

CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(180) NOT NULL,
    excerpt TEXT NOT NULL,
    published_at DATE NOT NULL
);

INSERT INTO news (title,excerpt,published_at) VALUES
('Maritime Industry Update','Sample article content for the website demo. Replace with approved company news.','2026-08-12'),
('Safe Cargo Handling','A dummy update explaining the importance of careful cargo operations.','2026-08-08'),
('Regional Shipping Outlook','A sample article card showing how maritime updates could appear.','2026-08-01');

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(180) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
