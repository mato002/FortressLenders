<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Who can qualify for a loan with Fortress Lenders Ltd?',
                'answer' => "We serve formally employed, self‑employed and business owners with a verifiable and consistent source of income.\n\nEligibility is assessed based on your income, existing commitments and ability to repay comfortably. We also serve farmers and agricultural businesses with our specialized farming loan products.",
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'What documents do I need to apply for a loan?',
                'answer' => "Typical documentation includes:\n• Copy of your National ID\n• KRA PIN certificate\n• Recent payslips (for employed) or bank statements (at least 3 months)\n• Proof of residence\n\nDepending on the product, we may also request additional documents such as business registration certificates, farming records, or security documentation.",
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'How long does loan approval and disbursement take?',
                'answer' => "Once we receive all the required documentation, most applications are processed within 24–48 business hours.\n\nYou will receive an SMS or call from our team once your application has been approved and is ready for disbursement. The actual disbursement can happen on the same day or within 1-2 business days after approval.",
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'question' => 'What loan amounts are available?',
                'answer' => "We offer loans ranging from KES 3,000 to KES 200,000 depending on the product:\n\n• Trade & Service Loans: KES 3,000 - KES 200,000 (weekly repayments)\n• Farming Loans: KES 5,000 - KES 30,000 (monthly repayments)\n\nThe exact amount you qualify for depends on your income, existing commitments, and repayment capacity.",
                'display_order' => 4,
                'is_active' => true,
            ],
            [
                'question' => 'What are the repayment periods?',
                'answer' => "Repayment periods vary by product:\n\n• Trade & Service Loans: 4 to 16 weeks (weekly repayments)\n• Farming Loans: Up to 1 month (monthly repayments)\n\nThe specific repayment period depends on the loan product you choose and your agreement with our team.",
                'display_order' => 5,
                'is_active' => true,
            ],
            [
                'question' => 'How are service charges calculated?',
                'answer' => "Service charges vary by product and can be either:\n\n• Fixed amount per month or per 6-week period\n• Percentage of the loan amount per month\n\nAll charges are clearly explained before you sign the loan agreement. There are no hidden fees. Our relationship officers will provide a detailed breakdown of all costs before you commit.",
                'display_order' => 6,
                'is_active' => true,
            ],
            [
                'question' => 'Can I repay my loan early without penalties?',
                'answer' => "Yes. Early repayment is allowed on most of our products and helps you save on interest.\n\nTalk to your relationship officer so we can guide you through the early settlement process for your specific facility. We'll provide you with a settlement statement showing the exact amount needed to clear your loan.",
                'display_order' => 7,
                'is_active' => true,
            ],
            [
                'question' => 'What happens if I miss a payment?',
                'answer' => "If you anticipate difficulty making a payment, contact us immediately. We're here to help and can work with you to find a solution.\n\nLate payments may incur penalties as outlined in your loan agreement. However, we prefer to work with customers proactively rather than reactively. Early communication is key to finding mutually beneficial solutions.",
                'display_order' => 8,
                'is_active' => true,
            ],
            [
                'question' => 'Where are your branches located?',
                'answer' => "We currently serve customers through branches in:\n• Nakuru\n• Gilgil\n• Olkalou\n• Nyahururu\n• Rumuruti\n\nYou can also start your application online and our team will direct you to the nearest branch for completion. Visit our branches page for detailed addresses and contact information.",
                'display_order' => 9,
                'is_active' => true,
            ],
            [
                'question' => 'Can I apply for a loan online?',
                'answer' => "Yes! You can start your loan application online through our website. Simply:\n\n1. Select the loan product that fits your needs\n2. Fill out the online application form\n3. Submit your details\n\nOur team will review your application and contact you within 24-48 hours. You may be asked to visit a branch to complete the process and provide original documents.",
                'display_order' => 10,
                'is_active' => true,
            ],
            [
                'question' => 'What is the difference between Trade & Service loans and Farming loans?',
                'answer' => "The main differences are:\n\n• Trade & Service Loans: Weekly repayment schedule, suitable for businesses with regular weekly income. Range from KES 3,000 to KES 200,000.\n\n• Farming Loans: Monthly repayment schedule, designed to align with crop cycles and harvest periods. Range from KES 5,000 to KES 30,000.\n\nOur relationship officers can help you determine which product best suits your needs and cash flow pattern.",
                'display_order' => 11,
                'is_active' => true,
            ],
            [
                'question' => 'Do I need collateral or a guarantor?',
                'answer' => "Most of our loan products do not require traditional collateral. However, requirements vary by:\n\n• Loan amount\n• Loan product type\n• Your credit history with us\n• Your income and repayment capacity\n\nSome higher-value loans may require a guarantor or security. Our team will clearly explain all requirements during the application process.",
                'display_order' => 12,
                'is_active' => true,
            ],
            [
                'question' => 'Can I get a loan if I have an existing loan?',
                'answer' => "Yes, you may qualify for an additional loan if:\n\n• You have a good repayment history on your existing loan\n• Your income can support both loan repayments\n• You meet all other eligibility criteria\n\nWe assess each application on its own merits. Having an existing loan with a good repayment record can actually strengthen your application.",
                'display_order' => 13,
                'is_active' => true,
            ],
            [
                'question' => 'How do I check my loan balance and repayment schedule?',
                'answer' => "You can check your loan details by:\n\n• Contacting your relationship officer directly\n• Visiting any of our branches\n• Calling our customer service line\n• Checking your loan statement (provided at disbursement)\n\nWe're working on adding online account access in the future. For now, our team is always available to provide you with up-to-date information about your loan.",
                'display_order' => 14,
                'is_active' => true,
            ],
            [
                'question' => 'What payment methods do you accept?',
                'answer' => "We accept loan repayments through:\n\n• Mobile money (M-Pesa, Airtel Money)\n• Bank transfers\n• Cash payments at our branches\n• Bank deposits\n\nYour relationship officer will provide you with the specific payment details and account information when your loan is approved.",
                'display_order' => 15,
                'is_active' => true,
            ],
            [
                'question' => 'Can I change my repayment schedule?',
                'answer' => "Repayment schedules are agreed upon at loan approval. However, if you experience changes in your financial situation, contact us immediately.\n\nWe may be able to:\n• Adjust your repayment schedule (subject to terms)\n• Restructure your loan\n• Provide a payment plan\n\nEarly communication is essential. We're committed to working with you to find solutions that work for both parties.",
                'display_order' => 16,
                'is_active' => true,
            ],
            [
                'question' => 'What makes Fortress Lenders different from other lenders?',
                'answer' => "At Fortress Lenders, we focus on:\n\n• Transparent pricing with no hidden charges\n• Flexible repayment terms that match your cash flow\n• Personal relationship officers who understand your business\n• Quick processing (24-48 hours)\n• Products designed for real needs (trade, service, farming)\n• Responsible lending practices\n• Local presence in multiple towns\n\nWe're not just a lender; we're a financial partner committed to your success.",
                'display_order' => 17,
                'is_active' => true,
            ],
            [
                'question' => 'How can I contact customer service?',
                'answer' => "You can reach us through:\n\n• Phone: Call our main line or your relationship officer\n• Email: Send us an email through our contact page\n• Visit: Drop by any of our branches during business hours\n• Online: Use the contact form on our website\n\nWe aim to respond to all inquiries within 24 hours. For urgent matters, calling or visiting a branch is the fastest option.",
                'display_order' => 18,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }
    }
}



