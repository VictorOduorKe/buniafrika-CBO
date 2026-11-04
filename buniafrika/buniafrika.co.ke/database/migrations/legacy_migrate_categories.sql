CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

INSERT IGNORE INTO categories (name)
SELECT DISTINCT category FROM (
    SELECT category FROM projects WHERE category != ''
    UNION
    SELECT category FROM gallery_items WHERE category != ''
    UNION
    SELECT category FROM news_articles WHERE category != ''
) AS all_cats;

ALTER TABLE projects ADD COLUMN category_id INT;
ALTER TABLE gallery_items ADD COLUMN category_id INT;
ALTER TABLE news_articles ADD COLUMN category_id INT;

UPDATE projects p JOIN categories c ON p.category = c.name SET p.category_id = c.id;
UPDATE gallery_items g JOIN categories c ON g.category = c.name SET g.category_id = c.id;
UPDATE news_articles n JOIN categories c ON n.category = c.name SET n.category_id = c.id;

ALTER TABLE projects ADD CONSTRAINT fk_projects_category FOREIGN KEY (category_id) REFERENCES categories(id);
ALTER TABLE gallery_items ADD CONSTRAINT fk_gallery_category FOREIGN KEY (category_id) REFERENCES categories(id);
ALTER TABLE news_articles ADD CONSTRAINT fk_news_category FOREIGN KEY (category_id) REFERENCES categories(id);
