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

USE spica_dummy;

CREATE TABLE IF NOT EXISTS company_overview (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(255) NOT NULL,
    description TEXT,
    vision TEXT,
    mission TEXT,
    founded VARCHAR(100),
    location VARCHAR(255),
    email VARCHAR(255),
    phone VARCHAR(100)
);

INSERT INTO company_overview (
    company_name,
    description,
    vision,
    mission,
    founded,
    location,
    email,
    phone
)
SELECT
    'Spica Indonesia',
    'PT Spica Putra Sarana is a maritime service company providing professional maritime services and consultancy.',
    'To become a trusted maritime service provider delivering professional, reliable, and innovative solutions.',
    'To provide quality maritime services, build strong partnerships, and support clients through professional expertise and regional cooperation.',
    'Indonesia',
    'Jakarta, Indonesia',
    'info@spicaservices.com',
    '+62 21 0000 0000'
WHERE NOT EXISTS (
    SELECT 1 FROM company_overview
);

CREATE TABLE IF NOT EXISTS about_values (
    id INT AUTO_INCREMENT PRIMARY KEY,
    icon VARCHAR(50) NOT NULL DEFAULT '✦',
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    sort_order INT NOT NULL DEFAULT 0
);

INSERT INTO about_values (icon, title, description, sort_order)
SELECT '🤝', 'Integrity',
'We uphold honesty, transparency, and accountability in every aspect of our business.', 1
WHERE NOT EXISTS (SELECT 1 FROM about_values);

INSERT INTO about_values (icon, title, description, sort_order)
SELECT '⚓', 'Professionalism',
'We maintain high professional standards and strive to deliver quality maritime services.', 2
WHERE NOT EXISTS (SELECT 1 FROM about_values WHERE title = 'Professionalism');

INSERT INTO about_values (icon, title, description, sort_order)
SELECT '🛡️', 'Reliability',
'We are committed to providing dependable services and consistent support to our clients.', 3
WHERE NOT EXISTS (SELECT 1 FROM about_values WHERE title = 'Reliability');

INSERT INTO about_values (icon, title, description, sort_order)
SELECT '🌐', 'Collaboration',
'We believe in building strong partnerships and working together to achieve shared goals.', 4
WHERE NOT EXISTS (SELECT 1 FROM about_values WHERE title = 'Collaboration');

CREATE TABLE IF NOT EXISTS about_strengths (
    id INT AUTO_INCREMENT PRIMARY KEY,
    icon VARCHAR(50) NOT NULL DEFAULT '✦',
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    sort_order INT NOT NULL DEFAULT 0
);

INSERT INTO about_strengths (icon, title, description, sort_order)
SELECT '⚓', 'Maritime Experience',
'Our team brings knowledge and experience in maritime services to support our clients.', 1
WHERE NOT EXISTS (SELECT 1 FROM about_strengths);

INSERT INTO about_strengths (icon, title, description, sort_order)
SELECT '🤝', 'Reliable Service',
'We focus on providing consistent, dependable, and professional services.', 2
WHERE NOT EXISTS (SELECT 1 FROM about_strengths WHERE title = 'Reliable Service');

INSERT INTO about_strengths (icon, title, description, sort_order)
SELECT '🌐', 'Regional Cooperation',
'We build professional relationships and cooperation to support maritime operations.', 3
WHERE NOT EXISTS (SELECT 1 FROM about_strengths WHERE title = 'Regional Cooperation');

INSERT INTO about_strengths (icon, title, description, sort_order)
SELECT '💼', 'Client-Focused',
'We prioritize client needs and build lasting professional relationships.', 4
WHERE NOT EXISTS (SELECT 1 FROM about_strengths WHERE title = 'Client-Focused');