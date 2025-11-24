@extends('layouts.website')

@section('title', 'About Us - Fortress Lenders Ltd')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-teal-800 via-teal-700 to-teal-900 text-white py-12 sm:py-16 md:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-3 sm:mb-4 px-4">About Fortress Lenders</h1>
            <p class="text-lg sm:text-xl text-teal-100 px-4">The Force Of Possibilities</p>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Introduction</h2>
                <div class="prose prose-lg text-gray-600 space-y-4">
                    <p>
                        FORTRESS LENDERS LTD, hereafter referred to as "Fortress," is registered under the Company's Act in the Republic of Kenya, the year 2019 certificate no. PVT-KAUXJED. We are licensed and trade as a Credit only institution as stated in our company memorandum. The Head office is in Fortress Hse, Nakuru County- Barnabas Muguga Opp. Epic ridge Academy.
                    </p>
                    <p>
                        At FORTRESS we exist to enable people to achieve their dreams. This we do through provision of customer centric financial and non-financial solutions. We provide Microfinance and Microcredit products and services in a unique and innovative way. The company was established to respond to the ever-growing need for small business loans.
                    </p>
                    <p>
                        Currently the client base is well over 3000 spread out within Nakuru, Gilgil, Olkalou, Nyahururu and Rumuruti. The company is cognizant of the fact that customers are interested in products that are affordable, diverse and with flexible terms of payment. Fortress therefore undertook to meet these customer requirements and over time, the organization has built a brand that is strong, trusted and appealing.
                    </p>
                    <p>
                        Fortress is interested in seeing our clients start, grow and diversify resulting in increased family income, nutrition, employment and well-being. We achieve this through the provision of financial literacy and business management programs to our clients.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission, Vision, Values Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
                <!-- Mission -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <div class="w-16 h-16 bg-gradient-to-br from-teal-700 to-teal-800 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h3>
                    <p class="text-gray-600">
                        To provide a full range of financial and non-financial products aimed at improving lives of low income rural and urban communities that will derive great economic impact with increased income levels and restore customer dignity while also increase value for our stakeholders.
                    </p>
                </div>

                <!-- Vision -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-yellow-500 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Vision</h3>
                    <p class="text-gray-600">
                        To be the preferred financial institution providing excellent financial and non-financial solutions through continuous innovation and ensuring sustainable growth for all stakeholders.
                    </p>
                </div>
            </div>

            <!-- Core Values -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h3 class="text-3xl font-bold text-gray-900 mb-8 text-center">Our Core Values</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-teal-700 to-teal-800 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-2">Integrity</h4>
                        <p class="text-sm text-gray-600">We operate with honesty and transparency</p>
                    </div>
                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-amber-500 to-yellow-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-2">Excellence</h4>
                        <p class="text-sm text-gray-600">We strive for the highest standards</p>
                    </div>
                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-2">Prudence</h4>
                        <p class="text-sm text-gray-600">We manage resources wisely</p>
                    </div>
                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-2">Commitment</h4>
                        <p class="text-sm text-gray-600">We are dedicated to our clients</p>
                    </div>
                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-2">Teamwork</h4>
                        <p class="text-sm text-gray-600">We work together for success</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Governance Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8 text-center">Governance Statement</h2>
                <div class="prose prose-lg text-gray-600 space-y-4">
                    <p>
                        FORTRESS LTD is committed to high standards of corporate governance. The organization is responsible to all its stakeholders for good corporate governance; both in principle and practice. FORTRESS LTD has continuously focused on refining key aspects of its business, physical and organizational infrastructure, information technology systems, products, policies and procedures and the focus on customer centricity, resulting in strong and sustained growth in portfolio and outreach.
                    </p>
                    <p>
                        FORTRESS LTD applies the following strategies to keep up with the good corporate governance requirement.
                    </p>
                </div>

                <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Board of Directors</h3>
                        <p class="text-gray-600">
                            There is a board of Directors (BOD) composed of executive directors and non-executive directors. The strength of the Board is drawn from the wealth of expertise of the members which cuts across relevant fields of interest to the business of FORTRESS LENDERS LTD.
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Board Committees</h3>
                        <ul class="text-gray-600 space-y-2">
                            <li>• Audit and Compliance committee</li>
                            <li>• Investment committee</li>
                            <li>• Credit committee</li>
                            <li>• Special committee</li>
                            <li>• Complaint committee</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-20 bg-gray-50" id="team">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-12 text-center">Our Team</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- James Ndegwa -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all transform hover:-translate-y-2">
                    <div class="bg-gradient-to-br from-teal-700 to-teal-800 h-48 flex items-center justify-center">
                        <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center border-4 border-white shadow-lg">
                            <span class="text-4xl font-bold text-red-600">JN</span>
                        </div>
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">JAMES NDEGWA</h3>
                        <p class="text-teal-800 font-semibold mb-4">Executive Director</p>
                        <p class="text-gray-600 text-sm mb-4">
                            An astute, result oriented and widely experienced businessman well entrenched in business strategically and customer relations. Holds a BSc. in Actuarial Science from JKUAT and an Advanced Diploma in Hardware & Software Engineering from City & Guilds, UK.
                        </p>
                    </div>
                </div>

                <!-- Ann Wairimu -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all transform hover:-translate-y-2">
                    <div class="bg-gradient-to-br from-orange-500 to-yellow-500 h-48 flex items-center justify-center">
                        <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center border-4 border-white shadow-lg">
                            <span class="text-4xl font-bold text-orange-600">AW</span>
                        </div>
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">ANN WAIRIMU</h3>
                        <p class="text-blue-900 font-semibold mb-4">Human Resource</p>
                        <p class="text-gray-600 text-sm mb-4">
                            In charge of the human resource department. Her role is to enhance the organization's human resources by planning, implementing, and evaluating employee relations and human resources policies, programs, and practices. Holds a Bachelor's degree in Mass communication from Daystar University and a MBA from Kenyatta University.
                        </p>
                    </div>
                </div>

                <!-- Allan Libese -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all transform hover:-translate-y-2">
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-500 h-48 flex items-center justify-center">
                        <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center border-4 border-white shadow-lg">
                            <span class="text-4xl font-bold text-blue-600">AL</span>
                        </div>
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Mr. ALLAN LIBESE</h3>
                        <p class="text-blue-900 font-semibold mb-4">Head of ICT</p>
                        <p class="text-gray-600 text-sm mb-4">
                            The ICT Manager with vast experience in financial services and Core Banking Systems. His role is to support the achievement of FORTRESS LTD strategic and operational objectives through the provision of high information communication technology and associated infrastructure, systems, procedures and solutions. Holds a Bachelor of Science degree in Information Technology from Karatina University.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Organizational Structure Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8 text-center">Organizational Structure</h2>
                <div class="prose prose-lg text-gray-600 space-y-4">
                    <p>
                        There exist very clear lines of authority and reporting within FORTRESS LTD where competent staffs have been recruited through a rigorous recruitment process. One of the key human resources goals is to ensure that staffs are developed through training, on-the-job induction sessions and skills impartation.
                    </p>
                    <p>
                        The human resource Training needs is identified through a "training need assessment" (TNA) exercises that aim to ensure staff acquire the required skills for them to fulfill their responsibilities efficiently and enable the Company to meet its customer needs adequately.
                    </p>
                </div>

                <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Information Systems</h3>
                        <p class="text-gray-600">
                            FORTRESS LTD has invested in robust information system software that serves its business lines effectively. This IT software is outsourced and internally customized to meet the unique needs of the Organization.
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Compliance With Regulations</h3>
                        <p class="text-gray-600">
                            FORTRESS LTD is keen on adhering to all regulatory requirements. We are guided by the various statutes which include but not limited to; The Constitution of Kenya, the Company Act, County Government laws and regulations as well as Employment Act among other laws.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

