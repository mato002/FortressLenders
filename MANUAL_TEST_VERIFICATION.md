# Manual Test Verification Checklist

## ✅ Code Verification (Completed)

I've verified the following code components:

### 1. Question Type Detection ✅
- [x] `AptitudeTestQuestion::isMultipleChoice()` - Correctly checks `question_type === 'multiple_choice'`
- [x] `AptitudeTestQuestion::isCalculation()` - Correctly checks `question_type === 'calculation'`
- [x] `AptitudeTestQuestion::isText()` - Correctly checks `question_type === 'text'`
- [x] `SelfInterviewQuestion::isMultipleChoice()` - **FIXED** - Now correctly checks `question_type === 'multiple_choice'`
- [x] `SelfInterviewQuestion::isCalculation()` - Correctly checks `question_type === 'calculation'`
- [x] `SelfInterviewQuestion::isText()` - **FIXED** - Now correctly checks `question_type === 'text'`

### 2. Scoring Logic ✅
- [x] `AptitudeTestSession::calculateScore()` - Handles all three question types
- [x] `AptitudeTestSession::isNumericAnswerCorrect()` - Handles numeric comparison with tolerance
- [x] `SelfInterviewSession::calculateScore()` - Handles all three question types
- [x] `SelfInterviewSession::isNumericAnswerCorrect()` - Handles numeric comparison with tolerance

### 3. Form Views ✅
- [x] `aptitude-test/take.blade.php` - Shows correct input types for each question type
- [x] `self-interview/take.blade.php` - Shows correct input types for each question type

### 4. Validation ✅
- [x] `AptitudeTestController` - Accepts answers up to 500 characters
- [x] `SelfInterviewController` - Accepts answers up to 500 characters

### 5. AI Prompts ✅
- [x] `AIPromptSettingsController` - Includes `aptitude_test_analysis` prompt
- [x] `AIPromptSettingsController` - Includes `self_interview_analysis` prompt
- [x] `AIAnalysisService` - Has `analyzeAptitudeTest()` method
- [x] `AIAnalysisService` - Has `analyzeSelfInterview()` method

---

## 🧪 Manual Testing Steps

### Test 1: Create Aptitude Test Questions

**Steps:**
1. Log in as admin
2. Go to Admin → Aptitude Test Questions
3. Click "Create New Question"

**Test Multiple Choice:**
- Section: Numerical
- Question Type: **Multiple Choice**
- Question: "What is 5 + 3?"
- Options: A) 6, B) 7, C) 8, D) 9
- Correct Answer: **c**
- Points: 4
- Mark as Active
- Save

**Expected:** Question created, appears in list

**Test Calculation:**
- Section: Numerical
- Question Type: **Calculation**
- Question: "Calculate: 15 × 4"
- Correct Answer: **60**
- Points: 4
- Mark as Active
- Save

**Expected:** Question created, no options field shown

**Test Text:**
- Section: Scenario
- Question Type: **Text**
- Question: "Describe a challenging project"
- Points: 4
- Mark as Active
- Save

**Expected:** Question created, no options or correct answer required

---

### Test 2: Create Self Interview Questions

**Steps:**
1. Go to Admin → Self Interview Questions
2. Click "Create New Question"

**Test Multiple Choice:**
- Question Type: **Multiple Choice**
- Question: "Years of experience?"
- Options: A) 0-1, B) 2-3, C) 4-5, D) 5+
- Correct Answer: **c**
- Points: 4
- Mark as Active
- Save

**Test Calculation:**
- Question Type: **Calculation**
- Question: "8 hours × 5 days = ?"
- Correct Answer: **40**
- Points: 4
- Mark as Active
- Save

**Test Text:**
- Question Type: **Text**
- Question: "Why do you want this job?"
- Points: 4
- Mark as Active
- Save

---

### Test 3: Job Application Flow

**Steps:**
1. Go to `/careers`
2. Click on a job post
3. Click "Apply Now"
4. Fill in application form
5. Upload CV (optional)
6. Submit

**Expected:** Application submitted, confirmation shown

---

### Test 4: Admin - Process Application

**Steps:**
1. Log in as admin
2. Go to Job Applications
3. Find the test application
4. Click to view details
5. Click "Parse CV" (if CV uploaded)
6. Click "Run AI Sieving" or "Resieve"
7. Verify status changes to "sieving_passed"
8. Verify candidate account created (if passed)

**Expected:** Application processed, candidate can now take tests

---

### Test 5: Aptitude Test - Multiple Choice

**Steps:**
1. Log in as candidate (or use email link)
2. Go to candidate dashboard
3. Find application with "Aptitude test pending"
4. Click "Take Aptitude Test"
5. Verify question displays with radio buttons (A, B, C, D)
6. Select correct answer (e.g., "c")
7. Submit test
8. Verify score shows correctly
9. Verify question marked as "Correct"

**Expected:** Multiple choice question auto-graded correctly

---

### Test 6: Aptitude Test - Calculation (Format Variations)

**Steps:**
1. Take aptitude test with calculation question
2. Answer with "60" → Submit → Verify correct
3. Retake (if possible) or create new session
4. Answer with "60.0" → Submit → Verify correct
5. Retake (if possible) or create new session
6. Answer with "60.00" → Submit → Verify correct
7. Answer with "61" → Submit → Verify incorrect

**Expected:** All formats (60, 60.0, 60.00) accepted as correct

---

### Test 7: Aptitude Test - Text Question

**Steps:**
1. Take aptitude test with text question
2. Enter a written response (e.g., "I worked on a complex project...")
3. Submit test
4. Verify response is saved
5. Verify question marked as "Needs Manual Review" (not auto-scored)

**Expected:** Text question saved but not auto-graded

---

### Test 8: Self Interview - All Question Types

**Steps:**
1. Log in as candidate
2. Take self interview
3. Answer multiple choice question → Verify auto-graded
4. Answer calculation question (test with 40, 40.0, 40.00) → Verify all formats work
5. Answer text question → Verify saved for manual review

**Expected:** All question types work correctly

---

### Test 9: Mobile Responsiveness

**Steps:**
1. Open aptitude test form on mobile device (or browser dev tools)
2. Verify:
   - [ ] Form displays correctly
   - [ ] Radio buttons are clickable
   - [ ] Text inputs work with numeric keyboard
   - [ ] Textareas are properly sized
   - [ ] Submit button is accessible
3. Repeat for self interview form
4. Check admin question lists on mobile

**Expected:** All forms are mobile-responsive

---

### Test 10: Bulk Operations

**Steps:**
1. Go to Admin → Aptitude Test Questions
2. Select multiple questions (checkboxes)
3. Click "Bulk Activate" → Verify all selected activated
4. Select multiple questions
5. Click "Bulk Deactivate" → Verify all selected deactivated
6. Select multiple questions
7. Click "Bulk Delete" → Verify confirmation dialog → Confirm → Verify deleted
8. Repeat for Self Interview Questions

**Expected:** Bulk operations work correctly

---

### Test 11: AI Prompts Configuration

**Steps:**
1. Go to Admin → AI Prompt Settings
2. Verify "Aptitude Test Analysis Prompt" is listed
3. Click to edit → Modify text → Save → Verify saved
4. Verify "Self Interview Analysis Prompt" is listed
5. Click to edit → Modify text → Save → Verify saved
6. Test reset to default

**Expected:** AI prompts are configurable

---

### Test 12: Mixed Question Types in One Test

**Steps:**
1. Create aptitude test with:
   - 1 multiple choice question
   - 1 calculation question
   - 1 text question
2. Take the test
3. Answer all questions correctly (where applicable)
4. Submit
5. Verify:
   - Multiple choice: Scored
   - Calculation: Scored
   - Text: Saved but not scored
   - Total score only includes auto-graded questions

**Expected:** Mixed question types handled correctly

---

## 🐛 Known Issues Fixed

1. ✅ **SelfInterviewQuestion::isMultipleChoice()** - Fixed to match AptitudeTestQuestion logic
2. ✅ **SelfInterviewQuestion::isText()** - Fixed to match AptitudeTestQuestion logic
3. ✅ **Validation** - Updated to accept 500 characters for calculation/text answers
4. ✅ **Form Views** - Updated to show correct input types for each question type

---

## 📝 Test Results Template

```
Date: ___________
Tester: ___________

Test Results:
[ ] Test 1: Create Aptitude Test Questions - PASS / FAIL
[ ] Test 2: Create Self Interview Questions - PASS / FAIL
[ ] Test 3: Job Application Flow - PASS / FAIL
[ ] Test 4: Admin - Process Application - PASS / FAIL
[ ] Test 5: Aptitude Test - Multiple Choice - PASS / FAIL
[ ] Test 6: Aptitude Test - Calculation Formats - PASS / FAIL
[ ] Test 7: Aptitude Test - Text Question - PASS / FAIL
[ ] Test 8: Self Interview - All Types - PASS / FAIL
[ ] Test 9: Mobile Responsiveness - PASS / FAIL
[ ] Test 10: Bulk Operations - PASS / FAIL
[ ] Test 11: AI Prompts Configuration - PASS / FAIL
[ ] Test 12: Mixed Question Types - PASS / FAIL

Issues Found:
1. _______________________
2. _______________________
3. _______________________

Notes:
_________________________________
_________________________________
```

---

## 🚀 Quick Verification Commands

```bash
# Check routes
php artisan route:list | findstr career
php artisan route:list | findstr aptitude
php artisan route:list | findstr self-interview

# Check database tables
php artisan tinker
>>> \App\Models\AptitudeTestQuestion::count()
>>> \App\Models\SelfInterviewQuestion::count()
>>> \App\Models\JobApplication::count()

# Clear caches
php artisan optimize:clear
```

---

## ✅ Summary

**Code Status:** All critical code components verified and fixed
**Ready for Testing:** Yes
**Next Steps:** Follow manual testing steps above
