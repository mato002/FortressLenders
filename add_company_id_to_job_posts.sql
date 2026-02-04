-- Add company_id column to job_posts table
-- This column links job posts to companies for multi-tenant support

-- Step 1: Add the column
ALTER TABLE `job_posts` 
ADD COLUMN `company_id` BIGINT UNSIGNED NULL AFTER `id`;

-- Step 2: Add index for better query performance
ALTER TABLE `job_posts` 
ADD INDEX `job_posts_company_id_index` (`company_id`);

-- Step 3: Add foreign key constraint (only if companies table exists)
-- Check if companies table exists first, then run:
ALTER TABLE `job_posts` 
ADD CONSTRAINT `job_posts_company_id_foreign` 
FOREIGN KEY (`company_id`) 
REFERENCES `companies` (`id`) 
ON DELETE CASCADE;

-- Optional: If you want to assign existing job posts to a default company (e.g., Fortress Lenders)
-- First, find or create the default company ID, then run:
-- UPDATE `job_posts` SET `company_id` = 1 WHERE `company_id` IS NULL;
-- (Replace 1 with the actual company ID)
