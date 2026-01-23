-- SQL to add candidate_id column to job_applications table
-- Run this in your MySQL/MariaDB database

-- Step 1: Check if column exists (optional - just for reference)
-- SELECT COLUMN_NAME 
-- FROM information_schema.COLUMNS 
-- WHERE TABLE_SCHEMA = DATABASE() 
-- AND TABLE_NAME = 'job_applications' 
-- AND COLUMN_NAME = 'candidate_id';

-- Step 2: Add the candidate_id column (only if it doesn't exist)
ALTER TABLE job_applications 
ADD COLUMN candidate_id BIGINT UNSIGNED NULL AFTER id;

-- Step 3: Add foreign key constraint
ALTER TABLE job_applications 
ADD CONSTRAINT job_applications_candidate_id_foreign 
FOREIGN KEY (candidate_id) REFERENCES candidates(id) 
ON DELETE SET NULL;

-- Optional: If you have a user_id column that needs to be removed
-- First, check for foreign keys on user_id:
-- SELECT CONSTRAINT_NAME 
-- FROM information_schema.KEY_COLUMN_USAGE 
-- WHERE TABLE_SCHEMA = DATABASE() 
-- AND TABLE_NAME = 'job_applications' 
-- AND COLUMN_NAME = 'user_id' 
-- AND REFERENCED_TABLE_NAME IS NOT NULL;

-- If foreign key exists, drop it first (replace 'constraint_name' with actual name):
-- ALTER TABLE job_applications DROP FOREIGN KEY constraint_name;

-- Then drop the user_id column:
-- ALTER TABLE job_applications DROP COLUMN user_id;
