<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Prefer the seeded admin user as the author
        $author = User::where('is_admin', true)->first() ?? User::first();

        if (! $author) {
            return;
        }

        $posts = [
            [
                'title' => 'Welcome to Fortress Lenders Ltd',
                'excerpt' => 'Learn who we are, what we do and how we support individuals, groups and businesses with accessible credit solutions.',
                'content' => <<<HTML
<p>Fortress Lenders Ltd is a licensed credit-only microfinance institution dedicated to providing fast, flexible and responsible lending solutions to our customers.</p>

<p>We understand that access to finance is critical for growth &mdash; whether you are running a business, managing a farm, supporting your family or pursuing education. Our products are tailored to meet real needs on the ground.</p>

<ul>
    <li>Flexible repayment terms aligned to your cash flows</li>
    <li>Transparent pricing with no hidden charges</li>
    <li>Dedicated relationship officers at every branch</li>
</ul>

<p>We are committed to responsible lending, customer education and long‑term partnerships with our clients.</p>
HTML,
                'days_ago' => 30,
            ],
            [
                'title' => 'Simple Steps to Apply for a Loan with Fortress',
                'excerpt' => 'A quick guide on how to start and complete your loan application with Fortress Lenders Ltd.',
                'content' => <<<HTML
<p>Applying for a loan with Fortress Lenders Ltd is simple and transparent. You can start online or visit any of our branches.</p>

<ol>
    <li>Choose the loan product that best fits your needs.</li>
    <li>Submit your basic details and supporting documents.</li>
    <li>Our team reviews your application and provides feedback.</li>
    <li>Once approved, you sign the offer letter and funds are disbursed.</li>
</ol>

<p>If you are unsure which product is best for you, our team will gladly walk you through the options and recommend a suitable solution.</p>
HTML,
                'days_ago' => 25,
            ],
            [
                'title' => 'Understanding Our Loan Products: Trade & Service Loans',
                'excerpt' => 'Discover our range of weekly repayment loans designed for traders, service providers and small business owners.',
                'content' => <<<HTML
<p>Our Trade & Service loan products are specifically designed for individuals and businesses that need quick access to capital with flexible weekly repayment terms.</p>

<h3>Product Range</h3>
<p>We offer loans from KES 3,000 to KES 200,000 with repayment periods ranging from 4 to 16 weeks. Our products include:</p>
<ul>
    <li><strong>Fasta</strong> - Quick loans from KES 3,000 to KES 5,000</li>
    <li><strong>Fasta Fasta</strong> - For amounts between KES 6,000 and KES 10,000</li>
    <li><strong>Malkia</strong> - Tailored for KES 11,000 to KES 15,000</li>
    <li><strong>Imara</strong> - Supporting businesses with KES 16,000 to KES 20,000</li>
    <li><strong>Pepea</strong> - For larger needs from KES 21,000 to KES 25,000</li>
    <li><strong>Vuka, Mwangaza, Almasi, Shaba, Kilele & Dhahabu</strong> - Higher value loans up to KES 200,000</li>
</ul>

<p>Each product has been designed with specific repayment schedules and service charges to match different business cash flow patterns.</p>
HTML,
                'days_ago' => 20,
            ],
            [
                'title' => 'Farming Loans: Supporting Agricultural Growth',
                'excerpt' => 'Learn about our monthly repayment loans designed specifically for farmers and agricultural businesses.',
                'content' => <<<HTML
<p>Agriculture is the backbone of our economy, and we understand the unique financial needs of farmers. Our farming loan products are designed with monthly repayment schedules that align with crop cycles and harvest periods.</p>

<h3>Farming Loan Products</h3>
<ul>
    <li><strong>Kilimo</strong> - Loans from KES 5,000 to KES 15,000 for small-scale farmers</li>
    <li><strong>Kilimo Advance</strong> - For medium-scale operations, KES 16,000 to KES 20,000</li>
    <li><strong>Mavuno</strong> - Supporting larger farming enterprises with KES 21,000 to KES 30,000</li>
</ul>

<p>All farming loans feature monthly repayment terms, making it easier to manage cash flow around planting, growing and harvesting seasons. Our relationship officers understand the agricultural calendar and can help you plan your loan to match your farming activities.</p>
HTML,
                'days_ago' => 18,
            ],
            [
                'title' => 'Managing Your Loan Responsibly',
                'excerpt' => 'Practical tips to help you stay on top of your repayments and maintain a healthy credit relationship.',
                'content' => <<<HTML
<p>At Fortress Lenders Ltd, we believe that responsible borrowing leads to long‑term financial stability.</p>

<h3>Key Tips for Loan Management</h3>
<ul>
    <li><strong>Borrow Wisely:</strong> Only borrow what you can comfortably repay from your income. Avoid over-borrowing even if you qualify for more.</li>
    <li><strong>Set Reminders:</strong> Mark your repayment dates on your calendar or set phone reminders to avoid late payments and penalties.</li>
    <li><strong>Communicate Early:</strong> If you anticipate challenges in meeting your obligations, talk to us immediately. We can work together to find a solution.</li>
    <li><strong>Build a Relationship:</strong> Get to know your relationship officer. They are there to support you and can provide valuable financial advice.</li>
    <li><strong>Track Your Payments:</strong> Keep records of all your payments and receipts for your own records.</li>
</ul>

<p>Our relationship officers are available to support you throughout the life of your loan. Don't hesitate to reach out if you have questions or concerns.</p>
HTML,
                'days_ago' => 15,
            ],
            [
                'title' => 'The Importance of Good Credit History',
                'excerpt' => 'Learn how maintaining a good repayment record can open doors to better loan terms and larger amounts in the future.',
                'content' => <<<HTML
<p>Your credit history is like a financial report card. It shows lenders how reliable you are at repaying borrowed money. At Fortress Lenders, we value customers who maintain good repayment records.</p>

<h3>Benefits of Good Credit History</h3>
<ul>
    <li><strong>Access to Larger Loans:</strong> Consistent repayment history can qualify you for higher loan amounts</li>
    <li><strong>Better Terms:</strong> Good customers may receive preferential rates and flexible terms</li>
    <li><strong>Faster Approval:</strong> Established customers often experience quicker application processing</li>
    <li><strong>Financial Opportunities:</strong> A strong credit record opens doors to other financial products and services</li>
</ul>

<h3>Building Your Credit History</h3>
<p>Start small, repay on time, and gradually build your relationship with us. Every successful loan repayment strengthens your credit profile. Our team is here to help you succeed and build a positive financial track record.</p>
HTML,
                'days_ago' => 12,
            ],
            [
                'title' => 'Fortress Branch Network and Customer Support',
                'excerpt' => 'An overview of our growing branch network and how to get help when you need it.',
                'content' => <<<HTML
<p>We are steadily expanding our footprint to bring services closer to you. Our branches are currently located in Nakuru, Gilgil, Olkalou, Nyahururu and Rumuruti.</p>

<h3>Our Branch Locations</h3>
<p>Each branch is staffed with experienced officers who understand the local market and are ready to support you through the entire loan journey. Whether you need help with:</p>
<ul>
    <li>Understanding loan products</li>
    <li>Completing your application</li>
    <li>Managing repayments</li>
    <li>Financial planning advice</li>
</ul>

<p>Our team is always ready to assist.</p>

<h3>Multiple Ways to Reach Us</h3>
<p>You can reach us through:</p>
<ul>
    <li>Visit any of our branches during business hours</li>
    <li>Call us on our dedicated phone lines</li>
    <li>Send us an email</li>
    <li>Use our online contact form</li>
    <li>Start your application online through our website</li>
</ul>

<p>We're committed to making our services accessible and convenient for all our customers.</p>
HTML,
                'days_ago' => 10,
            ],
            [
                'title' => 'Common Loan Application Mistakes to Avoid',
                'excerpt' => 'Learn about the most common mistakes applicants make and how to avoid them for a smoother loan approval process.',
                'content' => <<<HTML
<p>Applying for a loan can seem daunting, but avoiding these common mistakes can make the process much smoother:</p>

<h3>1. Incomplete Documentation</h3>
<p>One of the biggest delays in loan processing is missing or incomplete documents. Always ensure you have:</p>
<ul>
    <li>Valid National ID</li>
    <li>KRA PIN certificate</li>
    <li>Recent payslips or bank statements (at least 3 months)</li>
    <li>Proof of residence</li>
    <li>Any additional documents specific to your loan type</li>
</ul>

<h3>2. Providing Incorrect Information</h3>
<p>Always double-check your application details. Incorrect phone numbers, addresses, or income figures can delay or derail your application.</p>

<h3>3. Not Understanding the Terms</h3>
<p>Before signing, make sure you fully understand:</p>
<ul>
    <li>Repayment schedule</li>
    <li>Service charges and fees</li>
    <li>Total amount payable</li>
    <li>Consequences of late payment</li>
</ul>

<h3>4. Borrowing More Than You Need</h3>
<p>While it might be tempting to borrow the maximum amount, only borrow what you actually need and can comfortably repay.</p>

<p>Our relationship officers are here to guide you through the process and answer any questions you may have.</p>
HTML,
                'days_ago' => 8,
            ],
            [
                'title' => 'How to Use Your Loan to Grow Your Business',
                'excerpt' => 'Strategic tips on how to effectively use your loan funds to expand and grow your business operations.',
                'content' => <<<HTML
<p>Getting a loan is just the first step. How you use the funds can determine whether your business grows or struggles. Here are some strategic ways to use your loan effectively:</p>

<h3>1. Inventory and Stock</h3>
<p>Use loan funds to purchase inventory in bulk, which often comes with better pricing. This is especially effective for retail and trading businesses.</p>

<h3>2. Equipment and Tools</h3>
<p>Invest in equipment that increases your productivity or allows you to offer new services. Calculate the return on investment before purchasing.</p>

<h3>3. Marketing and Expansion</h3>
<p>Allocate funds for marketing campaigns, opening new locations, or expanding your product/service offerings.</p>

<h3>4. Working Capital</h3>
<p>Use funds to cover operational expenses during slow periods, ensuring your business continues running smoothly.</p>

<h3>5. Debt Consolidation</h3>
<p>If you have multiple high-interest debts, consider consolidating them with a single loan at better terms.</p>

<h3>Best Practices</h3>
<ul>
    <li>Create a budget before spending</li>
    <li>Track all expenses related to the loan</li>
    <li>Set aside funds for loan repayment</li>
    <li>Monitor the impact on your business growth</li>
</ul>

<p>Remember, the goal is to use the loan to generate more income than the cost of borrowing.</p>
HTML,
                'days_ago' => 5,
            ],
            [
                'title' => 'Early Loan Repayment: Benefits and Process',
                'excerpt' => 'Learn about the advantages of repaying your loan early and how to go about it.',
                'content' => <<<HTML
<p>Repaying your loan early can save you money and improve your credit standing. Here's what you need to know:</p>

<h3>Benefits of Early Repayment</h3>
<ul>
    <li><strong>Save on Interest:</strong> Paying early reduces the total interest you pay over the loan period</li>
    <li><strong>Improve Credit Score:</strong> Early repayment demonstrates financial responsibility</li>
    <li><strong>Financial Freedom:</strong> Clear your debt faster and free up cash flow</li>
    <li><strong>Future Opportunities:</strong> Good repayment history opens doors to better loan terms</li>
</ul>

<h3>How to Repay Early</h3>
<ol>
    <li>Contact your relationship officer to discuss early repayment</li>
    <li>Request a settlement statement showing the outstanding balance</li>
    <li>Review any early repayment terms or conditions</li>
    <li>Make the payment through your preferred channel</li>
    <li>Obtain a clearance certificate confirming full settlement</li>
</ol>

<h3>Things to Consider</h3>
<p>Before repaying early, consider:</p>
<ul>
    <li>Whether you have sufficient funds without affecting your business operations</li>
    <li>If there are any early repayment fees (most of our products allow early repayment without penalties)</li>
    <li>Your overall financial situation and other obligations</li>
</ul>

<p>Our team is always available to discuss early repayment options and help you make the best financial decision.</p>
HTML,
                'days_ago' => 3,
            ],
        ];

        foreach ($posts as $postData) {
            $publishedAt = now()->subDays($postData['days_ago']);

            Post::updateOrCreate(
                ['slug' => Str::slug($postData['title'])],
                [
                    'title' => $postData['title'],
                    'slug' => Str::slug($postData['title']),
                    'excerpt' => $postData['excerpt'],
                    'content' => $postData['content'],
                    'featured_image_path' => null,
                    'published_at' => $publishedAt,
                    'is_published' => true,
                    'author_id' => $author->id,
                ]
            );
        }
    }
}



