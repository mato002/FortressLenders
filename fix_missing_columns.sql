-- SQL to add missing columns to job_applications table
-- Run this in your MySQL/MariaDB database
-- Note: MySQL doesn't support IF NOT EXISTS for ALTER TABLE ADD COLUMN
-- If you get "Duplicate column name" errors, those columns already exist and can be ignored

-- Step 1: Add candidate_id column (check if exists first)
-- Check: SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'job_applications' AND COLUMN_NAME = 'candidate_id';
ALTER TABLE job_applications 
ADD COLUMN candidate_id BIGINT UNSIGNED NULL AFTER id;

-- Add foreign key for candidate_id
ALTER TABLE job_applications 
ADD CONSTRAINT job_applications_candidate_id_foreign 
FOREIGN KEY (candidate_id) REFERENCES candidates(id) 
ON DELETE SET NULL;

-- Step 2: Add aptitude test columns
-- Check if aptitude_test_score exists first
ALTER TABLE job_applications 
ADD COLUMN aptitude_test_score INTEGER NULL AFTER status;

-- Check if aptitude_test_passed exists first  
ALTER TABLE job_applications 
ADD COLUMN aptitude_test_passed BOOLEAN NULL AFTER aptitude_test_score;

-- Check if aptitude_test_completed_at exists first
ALTER TABLE job_applications 
ADD COLUMN aptitude_test_completed_at TIMESTAMP NULL AFTER aptitude_test_passed;

-- Step 3: Add self interview columns
-- Check if self_interview_score exists first
ALTER TABLE job_applications 
ADD COLUMN self_interview_score INTEGER NULL AFTER aptitude_test_completed_at;

-- Check if self_interview_passed exists first
ALTER TABLE job_applications 
ADD COLUMN self_interview_passed BOOLEAN NULL AFTER self_interview_score;

-- Check if self_interview_completed_at exists first
ALTER TABLE job_applications 
ADD COLUMN self_interview_completed_at TIMESTAMP NULL AFTER self_interview_passed;

-- Step 4: Add email tracking column
-- Check if confirmation_email_sent_at exists first
ALTER TABLE job_applications 
ADD COLUMN confirmation_email_sent_at TIMESTAMP NULL AFTER status;

-- To check which columns exist, run:
-- SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'job_applications' ORDER BY ORDINAL_POSITION;
