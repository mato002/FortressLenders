-- SQL Query to add bio_data columns to candidates table
-- Run this query on your production database

-- Check if columns don't exist before adding them
ALTER TABLE `candidates` 
ADD COLUMN IF NOT EXISTS `bio_data` TEXT NULL AFTER `email`,
ADD COLUMN IF NOT EXISTS `bio_data_completed` BOOLEAN DEFAULT FALSE AFTER `bio_data`,
ADD COLUMN IF NOT EXISTS `bio_data_completed_at` TIMESTAMP NULL AFTER `bio_data_completed`;

-- If your MySQL version doesn't support IF NOT EXISTS, use this instead:
-- ALTER TABLE `candidates` 
-- ADD COLUMN `bio_data` TEXT NULL AFTER `email`,
-- ADD COLUMN `bio_data_completed` BOOLEAN DEFAULT FALSE AFTER `bio_data`,
-- ADD COLUMN `bio_data_completed_at` TIMESTAMP NULL AFTER `bio_data_completed`;

-- Note: If you get an error that columns already exist, you can check first:
-- DESCRIBE `candidates`;
