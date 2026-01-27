# Career Module - End-to-End Test Plan

## Overview
This test plan covers the complete career module flow from job posting to final interview stages.

---

## Pre-Testing Setup

### 1. Database Setup
- [ ] Ensure all migrations are run: `php artisan migrate`
- [ ] Verify database connection is working
- [ ] Check that required tables exist:
  - `job_posts`
  - `job_applications`
  - `aptitude_test_questions`
  - `aptitude_test_sessions`
  - `self_interview_questions`
  - `self_interview_sessions`
  - `candidates`

### 2. Admin Setup
- [ ] Log in as admin
- [ ] Verify admin dashboard is accessible
- [ ] Check AI prompts are configured (optional but recommended)

---

## Test Flow 1: Job Posting & Application

### Step 1: Create Job Post
**Location:** Admin → Jobs → Create New Job

**Test Steps:**
1. [ ] Navigate to Admin → Jobs
2. [ ] Click "Create New Job"
3. [ ] Fill in all required fields:
   - Title: "Software Developer"
   - Description: "We are looking for..."
   - Requirements: "Bachelor's degree, 3+ years experience..."
   - Location, Salary, etc.
4. [ ] Set job as Active
5. [ ] Save the job post
6. [ ] Verify job appears in the list
7. [ ] Verify job is visible on public careers page (`/careers`)

**Expected Result:** Job post created and visible publicly

---

### Step 2: Create Aptitude Test Questions
**Location:** Admin → Aptitude Test Questions

**Test Steps:**
1. [ ] Navigate to Admin → Aptitude Test Questions
2. [ ] Create at least 2 questions of each type:
   - **Multiple Choice Question:**
     - Section: Numerical
     - Question Type: Multiple Choice
     - Question: "What is 5 + 3?"
     - Options: A) 6, B) 7, C) 8, D) 9
     - Correct Answer: C
     - Points: 4
   - **Calculation Question:**
     - Section: Numerical
     - Question Type: Calculation
     - Question: "Calculate: 15 × 4"
     - Correct Answer: 60
     - Points: 4
   - **Text Question:**
     - Section: Scenario
     - Question Type: Text
     - Question: "Describe a time you solved a difficult problem"
     - Points: 4
3. [ ] Create questions for all sections (Numerical, Logical, Verbal, Scenario)
4. [ ] Verify questions are saved and appear in the list
5. [ ] Test bulk operations:
   - [ ] Select multiple questions
   - [ ] Bulk activate
   - [ ] Bulk deactivate
   - [ ] Verify mobile responsiveness

**Expected Result:** Questions created and manageable

---

### Step 3: Create Self Interview Questions
**Location:** Admin → Self Interview Questions

**Test Steps:**
1. [ ] Navigate to Admin → Self Interview Questions
2. [ ] Create at least 2 questions of each type:
   - **Multiple Choice Question:**
     - Question Type: Multiple Choice
     - Question: "How many years of experience do you have?"
     - Options: A) 0-1, B) 2-3, C) 4-5, D) 5+
     - Correct Answer: C (or leave empty for manual review)
     - Points: 4
   - **Calculation Question:**
     - Question Type: Calculation
     - Question: "If you work 8 hours/day for 5 days, how many hours total?"
     - Correct Answer: 40
     - Points: 4
   - **Text Question:**
     - Question Type: Text
     - Question: "Why do you want to work here?"
     - Points: 4
3. [ ] Verify questions are saved
4. [ ] Test bulk operations
5. [ ] Verify mobile responsiveness

**Expected Result:** Self interview questions created

---

## Test Flow 2: Candidate Application Process

### Step 4: Submit Job Application
**Location:** Public → Careers → Apply

**Test Steps:**
1. [ ] Go to `/careers`
2. [ ] Click on a job post
3. [ ] Click "Apply Now"
4. [ ] Fill in application form:
   - Name, Email, Phone
   - Education details
   - Current position
   - Skills
   - Why interested, Why good fit, Career goals
   - Upload CV (PDF)
5. [ ] Submit application
6. [ ] Verify confirmation message appears
7. [ ] Check email for confirmation (if mail is configured)

**Expected Result:** Application submitted successfully

---

### Step 5: Admin - Process Application
**Location:** Admin → Job Applications

**Test Steps:**
1. [ ] Log in as admin
2. [ ] Navigate to Job Applications
3. [ ] Find the test application
4. [ ] Click to view application details
5. [ ] Test CV parsing:
   - [ ] Click "Parse CV" button
   - [ ] Verify CV is parsed and data extracted
6. [ ] Test AI Analysis:
   - [ ] Click "Analyze with AI" button
   - [ ] Verify AI analysis is generated
   - [ ] Check match score and recommendations
7. [ ] Run AI Sieving:
   - [ ] Click "Run AI Sieving" or "Resieve" button
   - [ ] Verify application status changes
   - [ ] Check if candidate account is created (if sieving passed)
   - [ ] Verify aptitude test invitation email is sent

**Expected Result:** Application processed, AI analysis complete, candidate account created if passed

---

## Test Flow 3: Aptitude Test

### Step 6: Candidate Takes Aptitude Test
**Location:** Candidate Dashboard or Email Link

**Test Steps:**
1. [ ] Log in as candidate (or use email link)
2. [ ] Navigate to candidate dashboard
3. [ ] Find application with "Aptitude test pending"
4. [ ] Click "Take Aptitude Test"
5. [ ] Verify test page loads with:
   - [ ] Timer showing 30 minutes
   - [ ] All questions displayed
   - [ ] Multiple choice questions show radio buttons
   - [ ] Calculation questions show text input
   - [ ] Text questions show textarea
6. [ ] Answer questions:
   - [ ] Answer multiple choice questions (select A, B, C, or D)
   - [ ] Answer calculation question (enter numeric value like "60")
   - [ ] Answer text question (enter written response)
7. [ ] Submit test
8. [ ] Verify submission confirmation
9. [ ] Check results page:
   - [ ] Score is displayed
   - [ ] Pass/Fail status is shown
   - [ ] Individual question results are visible

**Expected Result:** Test completed, scored, and results displayed

---

### Step 7: Verify Aptitude Test Scoring
**Location:** Admin → Job Applications → View Application

**Test Steps:**
1. [ ] View the application in admin
2. [ ] Check aptitude test section:
   - [ ] Score is displayed correctly
   - [ ] Pass/Fail status is correct
   - [ ] Multiple choice questions are marked correctly
   - [ ] Calculation questions are marked correctly (test with 60, 60.0, 60.00)
   - [ ] Text questions show "Needs Manual Review"
3. [ ] Test AI Analysis of aptitude test:
   - [ ] If AI prompt is configured, verify it can analyze results
   - [ ] Check that analysis includes section breakdown

**Expected Result:** Scoring works correctly for all question types

---

## Test Flow 4: Self Interview

### Step 8: Candidate Takes Self Interview
**Location:** Candidate Dashboard

**Test Steps:**
1. [ ] Ensure candidate passed aptitude test
2. [ ] Log in as candidate
3. [ ] Navigate to candidate dashboard
4. [ ] Find application with "Self interview pending"
5. [ ] Click "Take Self Interview"
6. [ ] Verify self interview page loads:
   - [ ] All questions displayed
   - [ ] Multiple choice questions show radio buttons
   - [ ] Calculation questions show text input
   - [ ] Text questions show textarea
7. [ ] Answer questions:
   - [ ] Answer multiple choice questions
   - [ ] Answer calculation question (test numeric comparison)
   - [ ] Answer text questions with detailed responses
8. [ ] Submit self interview
9. [ ] Verify submission confirmation
10. [ ] Check results page

**Expected Result:** Self interview completed and scored

---

### Step 9: Verify Self Interview Scoring
**Location:** Admin → Job Applications → View Application

**Test Steps:**
1. [ ] View the application in admin
2. [ ] Check self interview section:
   - [ ] Score is displayed correctly
   - [ ] Pass/Fail status is correct
   - [ ] Multiple choice questions are marked correctly
   - [ ] Calculation questions are marked correctly
   - [ ] Text questions show responses (for manual review)
3. [ ] Test AI Analysis of self interview:
   - [ ] Verify AI can analyze self interview responses
   - [ ] Check cultural fit assessment
   - [ ] Check communication skills evaluation

**Expected Result:** Self interview scoring and AI analysis work correctly

---

## Test Flow 5: Mobile Responsiveness

### Step 10: Test Mobile Views
**Test on mobile device or browser dev tools (mobile view)**

**Test Areas:**
1. [ ] **Aptitude Test Form:**
   - [ ] Form displays correctly on mobile
   - [ ] Questions are readable
   - [ ] Input fields are properly sized
   - [ ] Submit button is accessible
   - [ ] Timer is visible

2. [ ] **Self Interview Form:**
   - [ ] Form displays correctly on mobile
   - [ ] Text areas are properly sized
   - [ ] Calculation inputs work with numeric keyboard
   - [ ] Submit button is accessible

3. [ ] **Admin - Aptitude Test Questions:**
   - [ ] List view is responsive
   - [ ] Bulk actions are accessible
   - [ ] Filters work on mobile
   - [ ] Table scrolls horizontally if needed

4. [ ] **Admin - Self Interview Questions:**
   - [ ] List view is responsive
   - [ ] Bulk actions are accessible
   - [ ] Filters work on mobile

**Expected Result:** All views are mobile-friendly

---

## Test Flow 6: Edge Cases & Error Handling

### Step 11: Test Edge Cases

1. [ ] **Calculation Question Edge Cases:**
   - [ ] Test with answer "42" when correct is "42.0" → Should pass
   - [ ] Test with answer "42.00" when correct is "42" → Should pass
   - [ ] Test with answer "42.5" when correct is "42.50" → Should pass
   - [ ] Test with wrong answer "43" when correct is "42" → Should fail
   - [ ] Test with empty answer → Should fail

2. [ ] **Text Question Handling:**
   - [ ] Submit text question with long answer (500+ characters)
   - [ ] Verify answer is saved correctly
   - [ ] Verify it's marked for manual review

3. [ ] **Missing Questions:**
   - [ ] Try to take test when no questions are configured
   - [ ] Verify appropriate error message

4. [ ] **Session Expiry:**
   - [ ] Start test, wait, then submit
   - [ ] Verify session is still valid

5. [ ] **Already Completed:**
   - [ ] Try to retake completed test
   - [ ] Verify redirect to results page

**Expected Result:** All edge cases handled gracefully

---

## Test Flow 7: AI Prompts

### Step 12: Test AI Prompt Configuration
**Location:** Admin → AI Prompt Settings

**Test Steps:**
1. [ ] Navigate to AI Prompt Settings
2. [ ] Verify all prompt types are listed:
   - [ ] System Prompt
   - [ ] CV Analysis Prompt
   - [ ] Application Analysis Prompt
   - [ ] Profile Summary Prompt
   - [ ] Skill Matching Prompt
   - [ ] **Aptitude Test Analysis Prompt** (NEW)
   - [ ] **Self Interview Analysis Prompt** (NEW)
3. [ ] Edit Aptitude Test Analysis Prompt:
   - [ ] Click to edit
   - [ ] Modify the prompt text
   - [ ] Save changes
   - [ ] Verify changes are saved
4. [ ] Edit Self Interview Analysis Prompt:
   - [ ] Click to edit
   - [ ] Modify the prompt text
   - [ ] Save changes
   - [ ] Verify changes are saved
5. [ ] Test reset to default:
   - [ ] Click reset on a prompt
   - [ ] Verify it reverts to default

**Expected Result:** AI prompts are configurable and working

---

## Test Flow 8: Bulk Operations

### Step 13: Test Bulk Operations

**Aptitude Test Questions:**
1. [ ] Select multiple questions
2. [ ] Bulk activate → Verify all selected are activated
3. [ ] Bulk deactivate → Verify all selected are deactivated
4. [ ] Bulk delete → Verify all selected are deleted (with confirmation)

**Self Interview Questions:**
1. [ ] Select multiple questions
2. [ ] Bulk activate → Verify all selected are activated
3. [ ] Bulk deactivate → Verify all selected are deactivated
4. [ ] Bulk delete → Verify all selected are deleted (with confirmation)

**Expected Result:** Bulk operations work correctly

---

## Quick Test Checklist

### Critical Path Test (Minimum Viable Test)
1. [ ] Create job post
2. [ ] Create 1 aptitude test question (multiple choice)
3. [ ] Create 1 self interview question (text)
4. [ ] Submit job application
5. [ ] Admin: Parse CV and run AI sieving
6. [ ] Candidate: Take aptitude test
7. [ ] Candidate: Take self interview
8. [ ] Admin: Verify both tests are completed and scored

---

## Common Issues to Watch For

1. **Database Connection:** If you see "Access denied" errors, check `.env` file
2. **Missing Questions:** Ensure questions are created and marked as "Active"
3. **Calculation Scoring:** Test with different number formats (42, 42.0, 42.00)
4. **Mobile View:** Test on actual mobile device or use browser dev tools
5. **AI Prompts:** Ensure AI API key is configured if testing AI features
6. **Email:** Check mail configuration if testing email notifications

---

## Test Data Suggestions

### Test Candidate
- Name: Test Candidate
- Email: test@example.com
- Phone: 0712345678

### Test Job Post
- Title: Software Developer
- Description: Test job description
- Requirements: Test requirements

### Test Questions
- At least 2-3 questions per section
- Mix of question types (multiple choice, calculation, text)
- Ensure questions are marked as "Active"

---

## Post-Testing

After testing, verify:
- [ ] All test data can be cleaned up
- [ ] No errors in logs
- [ ] Database integrity maintained
- [ ] All features work as expected

---

## Notes

- Test in a development/staging environment first
- Keep test data separate from production data
- Document any bugs or issues found
- Test with different user roles (admin, candidate, client)
