<?php

namespace Database\Seeders;

use App\Models\JobPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = [
            [
                'title' => 'Loan Officer',
                'description' => 'We are seeking an experienced Loan Officer to join our team. You will be responsible for evaluating, authorizing, and recommending approval of loan applications for individuals and businesses.',
                'requirements' => '• Bachelor\'s degree in Finance, Business, or related field
• Minimum 2 years of experience in lending or financial services
• Strong analytical and decision-making skills
• Excellent communication and customer service abilities
• Knowledge of lending regulations and compliance requirements
• Ability to work under pressure and meet targets',
                'responsibilities' => '• Evaluate loan applications and assess creditworthiness
• Conduct interviews with loan applicants
• Analyze financial statements and credit reports
• Recommend loan approvals or rejections
• Maintain relationships with clients and provide excellent service
• Ensure compliance with lending policies and regulations
• Meet monthly loan origination targets',
                'location' => 'Nakuru',
                'department' => 'Operations',
                'employment_type' => 'full-time',
                'experience_level' => 'Mid Level',
                'application_deadline' => now()->addDays(30),
                'is_active' => true,
            ],
            [
                'title' => 'Customer Service Representative',
                'description' => 'Join our customer service team and help clients with their financial needs. You will be the first point of contact for customers, providing information about our products and services.',
                'requirements' => '• High school diploma or equivalent
• Previous customer service experience preferred
• Excellent verbal and written communication skills
• Ability to handle multiple tasks simultaneously
• Computer literacy and familiarity with CRM systems
• Friendly and professional demeanor',
                'responsibilities' => '• Respond to customer inquiries via phone, email, and in-person
• Provide information about loan products and services
• Assist customers with account inquiries
• Process loan applications and documentation
• Maintain accurate customer records
• Resolve customer complaints and issues
• Cross-sell products when appropriate',
                'location' => 'Nakuru',
                'department' => 'Customer Service',
                'employment_type' => 'full-time',
                'experience_level' => 'Entry Level',
                'application_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'title' => 'Credit Analyst',
                'description' => 'We are looking for a detail-oriented Credit Analyst to assess the creditworthiness of loan applicants. You will analyze financial data and prepare credit reports to support lending decisions.',
                'requirements' => '• Bachelor\'s degree in Finance, Accounting, or Economics
• 3+ years of experience in credit analysis or risk assessment
• Strong analytical and quantitative skills
• Proficiency in financial analysis software
• Knowledge of credit risk assessment methodologies
• Attention to detail and accuracy
• Excellent report writing skills',
                'responsibilities' => '• Analyze financial statements and credit reports
• Assess credit risk and repayment capacity
• Prepare detailed credit analysis reports
• Make recommendations on loan approvals
• Monitor existing loan portfolios
• Conduct industry and market research
• Collaborate with loan officers on complex cases',
                'location' => 'Nakuru',
                'department' => 'Risk Management',
                'employment_type' => 'full-time',
                'experience_level' => 'Mid Level',
                'application_deadline' => now()->addDays(35),
                'is_active' => true,
            ],
            [
                'title' => 'Marketing Manager',
                'description' => 'Lead our marketing efforts to promote Fortress Lenders and attract new customers. You will develop and execute marketing strategies across various channels to increase brand awareness and loan applications.',
                'requirements' => '• Bachelor\'s degree in Marketing, Business, or related field
• 5+ years of marketing experience, preferably in financial services
• Strong understanding of digital marketing channels
• Experience with social media marketing and content creation
• Excellent communication and presentation skills
• Creative thinking and problem-solving abilities
• Ability to manage multiple projects and deadlines',
                'responsibilities' => '• Develop and execute comprehensive marketing strategies
• Manage digital marketing campaigns (social media, email, SEO)
• Create marketing materials and content
• Organize promotional events and community outreach
• Analyze marketing performance and ROI
• Manage marketing budget
• Coordinate with external agencies and vendors
• Build brand awareness in target communities',
                'location' => 'Nakuru',
                'department' => 'Marketing',
                'employment_type' => 'full-time',
                'experience_level' => 'Senior Level',
                'application_deadline' => now()->addDays(40),
                'is_active' => true,
            ],
            [
                'title' => 'IT Support Specialist',
                'description' => 'Provide technical support to our staff and ensure our IT systems run smoothly. You will troubleshoot issues, maintain hardware and software, and help implement new technology solutions.',
                'requirements' => '• Bachelor\'s degree in Computer Science, IT, or related field
• 2+ years of IT support experience
• Knowledge of Windows and Linux operating systems
• Experience with network administration
• Strong troubleshooting and problem-solving skills
• Excellent communication skills
• Ability to work independently and as part of a team',
                'responsibilities' => '• Provide technical support to staff
• Troubleshoot hardware and software issues
• Maintain and update IT systems
• Install and configure new equipment
• Monitor network performance and security
• Provide training to staff on new systems
• Maintain IT documentation
• Assist with IT projects and implementations',
                'location' => 'Nakuru',
                'department' => 'IT',
                'employment_type' => 'full-time',
                'experience_level' => 'Mid Level',
                'application_deadline' => now()->addDays(30),
                'is_active' => true,
            ],
            [
                'title' => 'Branch Manager',
                'description' => 'Lead one of our branch operations and ensure excellent customer service and business growth. You will manage branch staff, oversee daily operations, and drive business development in your area.',
                'requirements' => '• Bachelor\'s degree in Business, Finance, or related field
• 5+ years of experience in banking or microfinance
• Previous management or supervisory experience
• Strong leadership and team management skills
• Excellent customer service orientation
• Knowledge of lending operations and regulations
• Ability to meet and exceed business targets',
                'responsibilities' => '• Manage branch operations and staff
• Ensure excellent customer service delivery
• Drive business growth and loan origination
• Monitor branch performance and KPIs
• Handle escalated customer issues
• Ensure compliance with policies and regulations
• Develop and maintain community relationships
• Prepare branch reports and analysis',
                'location' => 'Nakuru',
                'department' => 'Operations',
                'employment_type' => 'full-time',
                'experience_level' => 'Senior Level',
                'application_deadline' => now()->addDays(45),
                'is_active' => true,
            ],
            [
                'title' => 'Finance Intern',
                'description' => 'Gain valuable experience in microfinance operations through this internship opportunity. You will assist with financial analysis, loan processing, and various administrative tasks.',
                'requirements' => '• Currently pursuing or recently completed Bachelor\'s degree in Finance, Accounting, or Business
• Strong interest in microfinance and financial services
• Basic knowledge of financial principles
• Good communication skills
• Willingness to learn and take on new challenges
• Computer literacy (MS Office, Excel)',
                'responsibilities' => '• Assist with loan application processing
• Support financial analysis and reporting
• Help with data entry and record keeping
• Assist with customer service tasks
• Participate in team meetings and training
• Complete assigned projects and tasks
• Learn about microfinance operations',
                'location' => 'Nakuru',
                'department' => 'Finance',
                'employment_type' => 'internship',
                'experience_level' => 'Entry Level',
                'application_deadline' => now()->addDays(20),
                'is_active' => true,
            ],
            [
                'title' => 'Compliance Officer',
                'description' => 'Ensure our operations comply with all regulatory requirements and internal policies. You will monitor compliance, conduct audits, and help maintain our reputation for ethical business practices.',
                'requirements' => '• Bachelor\'s degree in Law, Finance, or related field
• 3+ years of compliance experience in financial services
• Strong knowledge of financial regulations
• Excellent attention to detail
• Strong analytical and problem-solving skills
• Ability to work independently
• Good communication and reporting skills',
                'responsibilities' => '• Monitor compliance with regulations and policies
• Conduct compliance audits and reviews
• Prepare compliance reports
• Provide compliance training to staff
• Investigate compliance issues
• Update policies and procedures
• Liaise with regulatory bodies
• Ensure documentation is up to date',
                'location' => 'Nakuru',
                'department' => 'Compliance',
                'employment_type' => 'full-time',
                'experience_level' => 'Mid Level',
                'application_deadline' => now()->addDays(35),
                'is_active' => true,
            ],
        ];

        foreach ($jobs as $job) {
            JobPost::updateOrCreate(
                ['slug' => Str::slug($job['title'])],
                array_merge(
                    $job,
                    [
                        'slug' => Str::slug($job['title']),
                    ]
                )
            );
        }
    }
}

