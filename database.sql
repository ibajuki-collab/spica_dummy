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