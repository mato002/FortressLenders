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
            [
                'title' => 'Senior Loan Officer',
                'description' => 'Lead our loan origination team and handle complex loan applications. You will work with high-value clients and help expand our loan portfolio while maintaining quality standards.',
                'requirements' => '• Bachelor\'s degree in Finance, Business, or related field
• 5+ years of experience in lending or financial services
• Proven track record of meeting and exceeding loan targets
• Strong relationship-building skills
• Knowledge of commercial and personal lending
• Excellent negotiation skills
• Leadership experience preferred',
                'responsibilities' => '• Originate and process complex loan applications
• Build and maintain relationships with high-value clients
• Mentor junior loan officers
• Review and approve loans within authority limits
• Develop new business opportunities
• Ensure portfolio quality and compliance
• Participate in strategic planning',
                'location' => 'Nairobi',
                'department' => 'Operations',
                'employment_type' => 'full-time',
                'experience_level' => 'Senior Level',
                'application_deadline' => now()->addDays(40),
                'is_active' => true,
            ],
            [
                'title' => 'Collections Officer',
                'description' => 'Manage loan collections and work with customers to ensure timely repayments. You will handle overdue accounts while maintaining positive customer relationships.',
                'requirements' => '• High school diploma or equivalent
• 2+ years of collections or customer service experience
• Strong communication and negotiation skills
• Ability to handle difficult conversations professionally
• Computer literacy
• Knowledge of debt collection regulations
• Patience and empathy',
                'responsibilities' => '• Contact customers with overdue payments
• Negotiate payment plans and arrangements
• Maintain accurate collection records
• Update customer accounts
• Escalate complex cases to management
• Provide excellent customer service
• Meet collection targets',
                'location' => 'Nakuru',
                'department' => 'Collections',
                'employment_type' => 'full-time',
                'experience_level' => 'Entry Level',
                'application_deadline' => now()->addDays(28),
                'is_active' => true,
            ],
            [
                'title' => 'Accountant',
                'description' => 'Manage our financial records, prepare reports, and ensure accurate accounting. You will play a key role in financial planning and analysis.',
                'requirements' => '• Bachelor\'s degree in Accounting or Finance
• CPA or equivalent qualification preferred
• 3+ years of accounting experience
• Proficiency in accounting software
• Strong analytical skills
• Attention to detail
• Knowledge of financial regulations',
                'responsibilities' => '• Prepare financial statements and reports
• Maintain general ledger and accounts
• Process accounts payable and receivable
• Reconcile bank statements
• Assist with budgeting and forecasting
• Prepare tax returns and filings
• Support audits and compliance reviews',
                'location' => 'Nakuru',
                'department' => 'Finance',
                'employment_type' => 'full-time',
                'experience_level' => 'Mid Level',
                'application_deadline' => now()->addDays(30),
                'is_active' => true,
            ],
            [
                'title' => 'Business Development Officer',
                'description' => 'Drive business growth by identifying new opportunities, building partnerships, and expanding our customer base. You will be instrumental in our expansion strategy.',
                'requirements' => '• Bachelor\'s degree in Business, Marketing, or related field
• 3+ years of business development experience
• Strong networking and relationship-building skills
• Excellent communication and presentation skills
• Self-motivated and results-oriented
• Knowledge of microfinance industry
• Valid driver\'s license',
                'responsibilities' => '• Identify and pursue new business opportunities
• Build relationships with potential partners
• Develop and execute business development strategies
• Organize marketing events and campaigns
• Conduct market research
• Prepare business proposals
• Meet business growth targets',
                'location' => 'Nairobi',
                'department' => 'Business Development',
                'employment_type' => 'full-time',
                'experience_level' => 'Mid Level',
                'application_deadline' => now()->addDays(35),
                'is_active' => true,
            ],
            [
                'title' => 'HR Assistant',
                'description' => 'Support our human resources department with recruitment, employee relations, and administrative tasks. Great opportunity to learn HR operations in a growing organization.',
                'requirements' => '• Bachelor\'s degree in HR, Business, or related field
• 1-2 years of HR experience preferred
• Good communication and interpersonal skills
• Computer literacy (MS Office)
• Attention to detail
• Discretion and confidentiality
• Willingness to learn',
                'responsibilities' => '• Assist with recruitment and onboarding
• Maintain employee records
• Process payroll and benefits
• Coordinate training programs
• Handle employee inquiries
• Assist with performance management
• Support HR projects and initiatives',
                'location' => 'Nakuru',
                'department' => 'Human Resources',
                'employment_type' => 'full-time',
                'experience_level' => 'Entry Level',
                'application_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'title' => 'Data Analyst',
                'description' => 'Analyze business data to provide insights that drive decision-making. You will work with various departments to understand trends and improve operations.',
                'requirements' => '• Bachelor\'s degree in Statistics, Mathematics, Economics, or related field
• 2+ years of data analysis experience
• Proficiency in Excel, SQL, and data visualization tools
• Strong analytical and problem-solving skills
• Attention to detail
• Good communication skills
• Experience with BI tools preferred',
                'responsibilities' => '• Collect and analyze business data
• Create reports and dashboards
• Identify trends and patterns
• Support decision-making with data insights
• Maintain data quality and accuracy
• Present findings to management
• Collaborate with various departments',
                'location' => 'Nakuru',
                'department' => 'Analytics',
                'employment_type' => 'full-time',
                'experience_level' => 'Mid Level',
                'application_deadline' => now()->addDays(30),
                'is_active' => true,
            ],
            [
                'title' => 'Security Officer',
                'description' => 'Ensure the safety and security of our premises, staff, and customers. You will monitor security systems and respond to incidents.',
                'requirements' => '• High school diploma or equivalent
• Security training or certification preferred
• 2+ years of security experience
• Good physical condition
• Alert and observant
• Good communication skills
• Ability to handle stressful situations
• Clean criminal record',
                'responsibilities' => '• Monitor premises and security systems
• Control access to facilities
• Patrol areas regularly
• Respond to security incidents
• Report suspicious activities
• Maintain security logs
• Assist with emergency procedures
• Provide customer service',
                'location' => 'Nakuru',
                'department' => 'Security',
                'employment_type' => 'full-time',
                'experience_level' => 'Entry Level',
                'application_deadline' => now()->addDays(20),
                'is_active' => true,
            ],
            [
                'title' => 'Part-time Customer Service Representative',
                'description' => 'Join our customer service team on a part-time basis. Perfect for students or those seeking flexible work hours while helping customers with their financial needs.',
                'requirements' => '• High school diploma or equivalent
• Previous customer service experience preferred
• Excellent communication skills
• Friendly and professional demeanor
• Ability to work flexible hours
• Computer literacy
• Weekend availability preferred',
                'responsibilities' => '• Assist customers with inquiries
• Provide information about products and services
• Process basic transactions
• Maintain customer records
• Handle customer complaints
• Support full-time staff during peak hours',
                'location' => 'Nakuru',
                'department' => 'Customer Service',
                'employment_type' => 'part-time',
                'experience_level' => 'Entry Level',
                'application_deadline' => now()->addDays(15),
                'is_active' => true,
            ],
            [
                'title' => 'Operations Manager',
                'description' => 'Oversee daily operations and ensure efficient service delivery. You will manage multiple teams and processes to achieve operational excellence.',
                'requirements' => '• Bachelor\'s degree in Business, Operations, or related field
• 5+ years of operations management experience
• Strong leadership and team management skills
• Excellent problem-solving abilities
• Process improvement experience
• Knowledge of financial services operations
• Ability to work under pressure',
                'responsibilities' => '• Manage daily operations
• Oversee multiple departments
• Develop and implement operational policies
• Monitor performance metrics
• Identify and implement process improvements
• Manage budgets and resources
• Ensure compliance with regulations
• Lead operational projects',
                'location' => 'Nairobi',
                'department' => 'Operations',
                'employment_type' => 'full-time',
                'experience_level' => 'Senior Level',
                'application_deadline' => now()->addDays(45),
                'is_active' => true,
            ],
            [
                'title' => 'Digital Marketing Specialist',
                'description' => 'Drive our digital presence and online marketing efforts. You will manage social media, create content, and run digital campaigns to attract customers.',
                'requirements' => '• Bachelor\'s degree in Marketing, Communications, or related field
• 2+ years of digital marketing experience
• Strong social media management skills
• Content creation abilities
• Knowledge of SEO and SEM
• Experience with analytics tools
• Creative thinking
• Excellent writing skills',
                'responsibilities' => '• Manage social media accounts
• Create engaging content
• Run digital marketing campaigns
• Monitor and analyze performance
• Optimize website and online presence
• Engage with online community
• Support brand building efforts',
                'location' => 'Nakuru',
                'department' => 'Marketing',
                'employment_type' => 'full-time',
                'experience_level' => 'Mid Level',
                'application_deadline' => now()->addDays(30),
                'is_active' => true,
            ],
            [
                'title' => 'Legal Officer',
                'description' => 'Provide legal support and ensure our operations comply with all legal requirements. You will handle contracts, legal documentation, and regulatory matters.',
                'requirements' => '• Bachelor of Laws (LLB) degree
• Admitted to practice law in Kenya
• 3+ years of legal experience, preferably in financial services
• Strong knowledge of financial regulations
• Excellent research and writing skills
• Attention to detail
• Good communication skills',
                'responsibilities' => '• Review and draft contracts
• Provide legal advice to management
• Ensure regulatory compliance
• Handle legal documentation
• Liaise with external counsel
• Conduct legal research
• Support risk management
• Represent company in legal matters',
                'location' => 'Nairobi',
                'department' => 'Legal',
                'employment_type' => 'full-time',
                'experience_level' => 'Mid Level',
                'application_deadline' => now()->addDays(40),
                'is_active' => true,
            ],
            [
                'title' => 'Field Officer',
                'description' => 'Work directly in communities to serve customers, process loan applications, and build relationships. This role involves field visits and customer outreach.',
                'requirements' => '• High school diploma or equivalent
• 1+ years of field work or sales experience
• Excellent communication skills
• Ability to work independently
• Valid driver\'s license or motorcycle license
• Physical fitness for field work
• Customer service orientation',
                'responsibilities' => '• Visit customers in their locations
• Process loan applications in the field
• Collect loan repayments
• Build community relationships
• Conduct customer education
• Maintain field records
• Report on field activities',
                'location' => 'Nakuru',
                'department' => 'Operations',
                'employment_type' => 'full-time',
                'experience_level' => 'Entry Level',
                'application_deadline' => now()->addDays(25),
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

