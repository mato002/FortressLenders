# Quick Test Guide - Career Module

## 🚀 Quick Start Testing

Follow these steps to quickly test the career module end-to-end.

---

## Prerequisites Check

```bash
# 1. Clear cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 2. Check database connection
php artisan migrate:status

# 3. Start server (if not running)
php artisan serve
```

---

## 5-Minute Critical Path Test

### 1. Admin Setup (2 minutes)
1. Log in as admin → `/login`
2. Go to **Jobs** → Create a test job post
   - Title: "Test Developer"
   - Mark as **Active**
   - Save
3. Go to **Aptitude Test Questions** → Create 1 question:
   - Section: Numerical
   - Type: Multiple Choice
   - Question: "2 + 2 = ?"
   - Options: A) 3, B) 4, C) 5, D) 6
   - Correct: B
   - Points: 4
   - Mark as **Active**
4. Go to **Self Interview Questions** → Create 1 question:
   - Type: Text
   - Question: "Tell us about yourself"
   - Points: 4
   - Mark as **Active**

### 2. Candidate Application (1 minute)
1. Go to `/careers`
2. Click on the test job
3. Click "Apply Now"
4. Fill minimal form:
   - Name: Test User
   - Email: test@test.com
   - Phone: 0712345678
   - Fill other required fields
5. Submit

### 3. Admin Processing (1 minute)
1. Go to **Job Applications**
2. Find the test application
3. Click "Parse CV" (if CV uploaded)
4. Click "Run AI Sieving" or "Resieve"
5. Verify status changes
6. If passed, verify candidate account created

### 4. Candidate Tests (1 minute)
1. Log in as candidate (or use email link)
2. Take **Aptitude Test**:
   - Answer the question
   - Submit
   - Verify score
3. Take **Self Interview**:
   - Answer the question
   - Submit
   - Verify completion

---

## Detailed Test Scenarios

### Scenario 1: Multiple Choice Question
**Test:** Aptitude test with multiple choice
1. Create question: Type = Multiple Choice, Options = A/B/C/D
2. Candidate selects answer
3. **Expected:** Auto-graded, score calculated

### Scenario 2: Calculation Question
**Test:** Aptitude test with calculation
1. Create question: Type = Calculation, Correct Answer = 60
2. Candidate enters: "60" or "60.0" or "60.00"
3. **Expected:** All formats accepted, marked correct

### Scenario 3: Text Question
**Test:** Self interview with text question
1. Create question: Type = Text
2. Candidate writes response
3. **Expected:** Saved, marked for manual review

### Scenario 4: Mobile View
**Test:** Forms on mobile device
1. Open test form on mobile
2. **Expected:** Responsive layout, inputs work correctly

---

## Common Test Data

### Test Job Post
```
Title: Software Developer
Description: We are looking for...
Requirements: Bachelor's degree, 3+ years...
Location: Remote
Status: Active
```

### Test Aptitude Questions
```
Numerical - Multiple Choice:
Q: "5 × 4 = ?"
A) 15, B) 20, C) 25, D) 30
Correct: B

Numerical - Calculation:
Q: "Calculate: 10 + 15"
Correct: 25

Scenario - Text:
Q: "Describe a challenging project"
```

### Test Self Interview Questions
```
Multiple Choice:
Q: "Years of experience?"
A) 0-1, B) 2-3, C) 4-5, D) 5+
Correct: C

Calculation:
Q: "8 hours × 5 days = ?"
Correct: 40

Text:
Q: "Why do you want this job?"
```

---

## Verification Checklist

After each test, verify:

### Aptitude Test
- [ ] Questions display correctly
- [ ] Multiple choice shows radio buttons
- [ ] Calculation shows text input
- [ ] Text shows textarea
- [ ] Submission works
- [ ] Score is calculated correctly
- [ ] Multiple choice: Correct/Incorrect
- [ ] Calculation: Accepts 42, 42.0, 42.00
- [ ] Text: Saved and marked for review

### Self Interview
- [ ] Questions display correctly
- [ ] All question types work
- [ ] Submission works
- [ ] Score calculated (for auto-graded questions)
- [ ] Text responses saved

### Admin
- [ ] Can view test results
- [ ] Scores are accurate
- [ ] AI analysis works (if configured)
- [ ] Bulk operations work
- [ ] Mobile responsive

---

## Troubleshooting

### Issue: "Access denied for user 'root'@'localhost'"
**Solution:** Check `.env` file database credentials

### Issue: Questions not showing
**Solution:** 
- Check questions are marked as "Active"
- Check questions are assigned to correct company/job

### Issue: Calculation not scoring correctly
**Solution:**
- Verify correct answer is numeric
- Test with: 42, 42.0, 42.00 (all should work)

### Issue: Mobile view broken
**Solution:**
- Clear browser cache
- Check Tailwind CSS is compiled: `npm run build`

### Issue: AI prompts not working
**Solution:**
- Check AI API key in `.env`
- Verify prompts are configured in Admin → AI Prompt Settings

---

## Test Results Template

```
Date: ___________
Tester: ___________

Test Results:
[ ] Job Post Creation - PASS / FAIL
[ ] Aptitude Test Questions - PASS / FAIL
[ ] Self Interview Questions - PASS / FAIL
[ ] Application Submission - PASS / FAIL
[ ] CV Parsing - PASS / FAIL
[ ] AI Sieving - PASS / FAIL
[ ] Aptitude Test (Multiple Choice) - PASS / FAIL
[ ] Aptitude Test (Calculation) - PASS / FAIL
[ ] Aptitude Test (Text) - PASS / FAIL
[ ] Self Interview (Multiple Choice) - PASS / FAIL
[ ] Self Interview (Calculation) - PASS / FAIL
[ ] Self Interview (Text) - PASS / FAIL
[ ] Mobile Responsiveness - PASS / FAIL
[ ] Bulk Operations - PASS / FAIL
[ ] AI Prompts - PASS / FAIL

Issues Found:
1. _______________________
2. _______________________
3. _______________________
```

---

## Next Steps After Testing

1. Document any bugs found
2. Test edge cases
3. Test with different user roles
4. Test with large datasets
5. Performance testing
6. Security testing

---

## Quick Commands

```bash
# Clear all caches
php artisan optimize:clear

# Check routes
php artisan route:list | grep career

# Check database
php artisan tinker
>>> \App\Models\JobApplication::count()
>>> \App\Models\AptitudeTestQuestion::count()
>>> \App\Models\SelfInterviewQuestion::count()

# Run migrations
php artisan migrate:fresh --seed
```
