@extends('website.layouts.storefront')

@section('title', 'Privacy Policy - Kenkie')
@section('favicon', asset('assets/images/favicon/5.png'))
@section('body-class', 'theme-color-3 dark')

@section('page-styles')
    <style>
        .policy-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 36px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            line-height: 1.75;
        }
        .policy-card h3 {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-top: 32px;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f1f5f9;
        }
        .policy-card p {
            color: #475569;
            margin-bottom: 16px;
        }
        .policy-card ul {
            color: #475569;
            margin-bottom: 20px;
            padding-left: 24px;
        }
        .policy-card ul li {
            margin-bottom: 8px;
        }
        .policy-header-badge {
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
            border-radius: 50px;
            padding: 6px 16px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }
    </style>
@endsection

@section('body')
    <!-- Loader Start -->
    <div class="fullpage-loader">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <!-- Loader End -->

    <!-- Header Start -->
    @include('website.includes.home-header')
    <!-- Header End -->

    <!-- Breadcrumb Section Start -->
    <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h2>Privacy Policy</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Privacy Policy</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <section class="section-b-space">
        <div class="container-fluid-lg">
            <div class="row justify-content-center">
                <div class="col-xl-9 col-lg-10">
                    <div class="policy-card">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 pb-3 border-bottom">
                            <div>
                                <h2 class="fw-bold mb-1 text-dark">Privacy Policy</h2>
                                <span class="text-muted small">This Privacy Policy sets out how we at KENKIE obtains, stores and uses your personal information.</span>
                            </div>
                            <span class="policy-header-badge">Effective Date: 1st November 2020</span>
                        </div>

                        <h3>KENKIE Ltd Privacy Statement</h3>
                        <p>
                            KENKIE Ltd together with any group companies (“we”, “us”, “our”) are committed to protecting and respecting your privacy. This privacy policy (including any other documents referred to in it) sets out the basis on which we process any personal data that we collect from you or about you that you provide to us or that we receive from other sources. By processing, we mean when we collect, use, store, delete and otherwise manipulate or access personal data.
                        </p>
                        <p>
                            Nonetheless, including as a result of using our website, our apps, if we ask you to hand information from which you can be identified.
                        </p>
                        <p>
                            Please read this policy precisely to understand our practices regarding your separate data and how we will treat it. We recommend that you issue off a copy of this Privacy Policy and any coming accounts in force from time to time for your records.
                        </p>

                        <h3>Our Data Protection Obligations to You</h3>
                        <p>
                            KENKIE Limited will process your personal data in accordance with the law. For the purposes of data protection legislation, we are the data controller and we will process your personal data in accordance with the General Data Protection Regulation (EU) 2016/679 and national laws which relate to the processing of personal data.
                        </p>

                        <h3>The Data Controller</h3>
                        <p>
                            KENKIE Limited is registered as a data controller with the Information Commissioner’s Office under Company registration number: <strong>11147305</strong>.
                        </p>

                        <h3>The Data Protection Lead for KENKIE Limited</h3>
                        <p>
                            We have appointed a Data Protection Officer to oversee compliance with this privacy policy. If you have any questions, comments or requests regarding this policy or how we use your personal data please contact our Data Protection Officer at <a href="mailto:info@kenkie.com" class="theme-color fw-semibold">info@kenkie.com</a>.
                        </p>
                        <p>
                            This is in addition to your right to contact the Information Commissioners Office if you are unsatisfied with our response to any issues you raise.
                        </p>

                        <h3>How We Collect or Obtain Personal Information About You</h3>
                        <p>
                            We collect personal information about you when you provide it to us, such as through your use of our website and its features, when you contact us directly by email, phone, via social media, in writing, in store in person and by connecting to our in-store wifi, when you order goods and services, when you use any of our other websites or applications or any other means by which you provide personal information to us.
                        </p>
                        <p>
                            Where permitted or authorised by law, we may also receive information about you from third parties such as affiliates, business partners, credit and fraud checking agencies, search engines and advertisers. We also collect information about your use of our website through cookies and similar technologies. Our cookies policy sets out more of the information on how we use cookies and similar technologies to collect information about you.
                        </p>
                        <p>
                            You can reject some or all of the cookies we use on or via our website by changing your browser settings, but doing so may impair your ability to use our website or some or all of its features.
                        </p>
                        <p>
                            We use Google Analytics on our website to understand how you engage and interact with it. For information on how Google Analytics collects and processes data using cookies, please visit <a href="https://www.google.com/policies/privacy/partners/" target="_blank" rel="noopener noreferrer" class="theme-color">www.google.com/policies/privacy/partners/</a>. You can opt out of Google Analytics tracking by visiting: <a href="https://tools.google.com/dlpage/gaoptout" target="_blank" rel="noopener noreferrer" class="theme-color">tools.google.com/dlpage/gaoptout</a>.
                        </p>
                        <p>
                            <strong>Website Links</strong> – our Site may, from time to time, contain links to and from the websites of third parties. Please note that if you follow a link to any of these websites, such websites will apply different terms to the collection and privacy of your personal data and we do not accept any responsibility or liability for these policies. When you leave our Site, we encourage you to read the privacy notice/policy of every website you visit.
                        </p>

                        <h3>Personal Information We Collect or Obtain About You</h3>
                        <p>The type of information we collect about you may include (but is not limited to) information such as:</p>
                        <ul>
                            <li>Your name;</li>
                            <li>Your email address;</li>
                            <li>Your address;</li>
                            <li>Your phone number;</li>
                            <li>Your age;</li>
                            <li>Your payment information (e.g. your credit or debit card details);</li>
                            <li>Information about your computer (e.g. your IP address and browser type);</li>
                            <li>Information about how you use our website (e.g. which pages you have viewed, the time you viewed them and what you clicked on);</li>
                            <li>Information about your mobile device (such as your geographical location);</li>
                            <li>Information on your purchases;</li>
                            <li>Information about your device (e.g. MAC address, manufacturer, operating system);</li>
                            <li>Information on your social profile(s).</li>
                        </ul>
                        <p>
                            Information we obtain from third parties will generally be your name and contact details, but may include any additional information they provide to us, including (but not limited to) any of the types of information set out in the list above.
                        </p>
                        <p>
                            We may also obtain personal information about you from certain publicly accessible sources, including (but not limited to) the electoral register, online customer databases, business directories, media publications, social media, websites and other publicly accessible sources.
                        </p>

                        <div class="mt-4 pt-3 border-top text-center text-muted small">
                            Have questions regarding your data or rights? Contact our Data Protection Lead at <a href="mailto:info@kenkie.com" class="theme-color">info@kenkie.com</a>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Start -->
    @include('website.includes.home-footer')
    <!-- Footer End -->
@endsection
