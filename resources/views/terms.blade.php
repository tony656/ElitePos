<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config("app.name")}} - Terms & Conditions</title>
    @include("links")
    
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --navy: #0B1E3D;
            --navy-mid: #112952;
            --navy-light: #1A3A6B;
            --amber: #F59E0B;
            --amber-pale: #FEF3C7;
            --emerald: #059669;
            --emerald-pale: #D1FAE5;
            --rose: #E11D48;
            --rose-pale: #FFE4E6;
            --violet: #7C3AED;
            --violet-pale: #EDE9FE;
            --sky: #0284C7;
            --sky-pale: #E0F2FE;
            --slate-50: #F8FAFC;
            --slate-100: #F1F5F9;
            --slate-200: #E2E8F0;
            --slate-300: #CBD5E1;
            --slate-400: #94A3B8;
            --slate-500: #64748B;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1E293B;
            --white: #FFFFFF;
            --font: 'Sora', system-ui, sans-serif;
            --r: 8px; --r-lg: 13px; --r-xl: 16px;
        }

        body {
            font-family: var(--font);
            background: #ECF0F8;
            color: var(--slate-800);
            min-height: 100vh;
            font-size: 14px;
            line-height: 1.7;
        }

        .wrap {
            padding: 1.5rem 1.75rem 3rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Page Header */
        .pg-header {
            background: var(--navy);
            border-radius: var(--r-xl);
            padding: 1.5rem 2rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            position: relative;
            overflow: hidden;
        }

        .pg-header::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -30px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: var(--navy-light);
            opacity: .45;
            pointer-events: none;
        }

        .pg-header::after {
            content: '';
            position: absolute;
            bottom: -55px;
            right: 100px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: var(--amber);
            opacity: .07;
            pointer-events: none;
        }

        .pg-left {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 1;
        }

        .back-btn {
            width: 40px;
            height: 40px;
            border-radius: var(--r);
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,.7);
            cursor: pointer;
            flex-shrink: 0;
            transition: all .15s;
            text-decoration: none;
        }

        .back-btn:hover {
            background: rgba(255,255,255,.16);
            color: var(--white);
        }

        .header-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--r);
            background: var(--amber);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--navy);
            flex-shrink: 0;
        }

        .pg-title-text h1 {
            font-size: 18px;
            font-weight: 700;
            color: var(--white);
            letter-spacing: -.2px;
        }

        .pg-title-text p {
            font-size: 12px;
            color: rgba(255,255,255,.45);
            margin-top: 1px;
        }

        /* Terms Content */
        .terms-container {
            background: var(--white);
            border-radius: var(--r-xl);
            border: 1px solid var(--slate-200);
            box-shadow: 0 1px 4px rgba(11,30,61,.05);
            overflow: hidden;
        }

        .terms-content {
            padding: 2.5rem;
        }

        .terms-last-updated {
            background: var(--slate-50);
            padding: 0.75rem 1.25rem;
            border-radius: var(--r);
            margin-bottom: 2rem;
            font-size: 13px;
            color: var(--slate-500);
            border: 1px solid var(--slate-200);
        }

        .terms-last-updated strong {
            color: var(--navy);
        }

        .terms-section {
            margin-bottom: 2rem;
        }

        .terms-section:last-child {
            margin-bottom: 0;
        }

        .terms-section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'Sora', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--slate-100);
        }

        .terms-section-title .icon {
            width: 32px;
            height: 32px;
            border-radius: var(--r);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 16px;
        }

        .icon-amber { background: var(--amber-pale); color: #92400e; }
        .icon-emerald { background: var(--emerald-pale); color: var(--emerald); }
        .icon-rose { background: var(--rose-pale); color: var(--rose); }
        .icon-violet { background: var(--violet-pale); color: var(--violet); }
        .icon-sky { background: var(--sky-pale); color: var(--sky); }
        .icon-navy { background: var(--navy); color: var(--white); }

        .terms-text {
            color: var(--slate-600);
            font-size: 14px;
            line-height: 1.8;
        }

        .terms-text p {
            margin-bottom: 0.75rem;
        }

        .terms-text ul, .terms-text ol {
            margin: 0.75rem 0 1rem 1.5rem;
        }

        .terms-text li {
            margin-bottom: 0.5rem;
        }

        .terms-text strong {
            color: var(--navy);
        }

        .terms-highlight {
            background: var(--slate-50);
            padding: 1rem 1.25rem;
            border-radius: var(--r);
            border-left: 4px solid var(--amber);
            margin: 1rem 0;
        }

        .terms-highlight.amber { border-left-color: var(--amber); }
        .terms-highlight.emerald { border-left-color: var(--emerald); }
        .terms-highlight.rose { border-left-color: var(--rose); }
        .terms-highlight.violet { border-left-color: var(--violet); }
        .terms-highlight.sky { border-left-color: var(--sky); }

        /* Acceptance Section */
        .acceptance-section {
            background: var(--slate-50);
            border-radius: var(--r-lg);
            padding: 1.5rem;
            margin-top: 2rem;
            border: 1px solid var(--slate-200);
            text-align: center;
        }

        .acceptance-section .btn-accept {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 32px;
            border-radius: var(--r);
            background: var(--amber);
            color: var(--navy);
            font-family: var(--font);
            font-size: 15px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            box-shadow: 0 3px 14px rgba(245,158,11,.3);
            transition: all .18s;
            text-decoration: none;
        }

        .acceptance-section .btn-accept:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245,158,11,.4);
        }

        .acceptance-section .btn-decline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 32px;
            border-radius: var(--r);
            background: var(--rose-pale);
            color: var(--rose);
            font-family: var(--font);
            font-size: 15px;
            font-weight: 700;
            border: 1.5px solid rgba(225,29,72,.2);
            cursor: pointer;
            transition: all .18s;
            text-decoration: none;
            margin-left: 10px;
        }

        .acceptance-section .btn-decline:hover {
            background: var(--rose);
            color: var(--white);
        }

        @media (max-width: 768px) {
            .wrap { padding: 1rem; }
            .terms-content { padding: 1.5rem; }
            .pg-header { padding: 1rem; }
            .terms-section-title { font-size: 16px; }
            .acceptance-section .btn-accept,
            .acceptance-section .btn-decline {
                width: 100%;
                justify-content: center;
                margin: 5px 0;
            }
            .acceptance-section .btn-decline {
                margin-left: 0;
            }
        }

        /* Print Styles */
        @media print {
            .pg-header { 
                background: var(--navy) !important; 
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .terms-container { box-shadow: none; border: 1px solid #ddd; }
            .back-btn, .acceptance-section .btn-accept, .acceptance-section .btn-decline {
                display: none !important;
            }
            .terms-content { padding: 1.5rem; }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        @include("sidenav")

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="wrap">

                <!-- Page Header -->
                <div class="pg-header">
                    <div class="pg-left">
                        <a href="{{ url('/settings') }}" class="back-btn">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                        <div class="header-icon">
                            <i class="bi bi-file-text"></i>
                        </div>
                        <div class="pg-title-text">
                            <h1>Terms & Conditions</h1>
                            <p>Please read our terms and conditions carefully</p>
                        </div>
                    </div>
                    <div style="position:relative; z-index:1;">
                        <span style="color:rgba(255,255,255,.5); font-size:12px;">
                            <i class="bi bi-calendar3 me-1"></i> Last Updated: {{ date('F d, Y') }}
                        </span>
                    </div>
                </div>

                <!-- Terms Content -->
                <div class="terms-container">
                    <div class="terms-content">

                        <div class="terms-last-updated">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Last Updated:</strong> {{ date('F d, Y') }} &nbsp;|&nbsp; 
                            <strong>Version:</strong> 2.0 &nbsp;|&nbsp; 
                            <strong>Effective Date:</strong> {{ date('F d, Y') }}
                        </div>

                        <!-- Introduction -->
                        <div class="terms-section">
                            <div class="terms-section-title">
                                <span class="icon icon-amber"><i class="bi bi-info-circle"></i></span>
                                Introduction
                            </div>
                            <div class="terms-text">
                                <p>
                                    Welcome to <strong>{{ config('app.name') }}</strong> (the "POS System", "Platform", or "We/Us/Our"). 
                                    These Terms and Conditions ("Terms") govern your use of our Point of Sale (POS) system, 
                                    including all associated software, services, and features provided by <strong>Leruma Enterprises</strong> 
                                    (the "Company", "we", "us", or "our").
                                </p>
                                <p>
                                    By accessing or using the POS System, you agree to be bound by these Terms. 
                                    If you do not agree to these Terms, please do not use the Platform.
                                </p>
                            </div>
                        </div>

                        <!-- Acceptance of Terms -->
                        <div class="terms-section">
                            <div class="terms-section-title">
                                <span class="icon icon-emerald"><i class="bi bi-check-circle"></i></span>
                                Acceptance of Terms
                            </div>
                            <div class="terms-text">
                                <p>
                                    By using the POS System, you acknowledge that you have read, understood, 
                                    and agree to be bound by these Terms. You also agree to comply with all 
                                    applicable laws and regulations regarding your use of the Platform.
                                </p>
                                <div class="terms-highlight emerald">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    <strong>Your continued use of the POS System constitutes your acceptance 
                                    of these Terms and any future amendments.</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Account Registration -->
                        <div class="terms-section">
                            <div class="terms-section-title">
                                <span class="icon icon-sky"><i class="bi bi-person-plus"></i></span>
                                Account Registration
                            </div>
                            <div class="terms-text">
                                <p>To use the POS System, you must create an account. When you register, you agree to:</p>
                                <ul>
                                    <li><strong>Provide accurate, complete, and current information</strong> – including your full name, email address, and contact details.</li>
                                    <li><strong>Maintain and update your information</strong> to keep it accurate and current.</li>
                                    <li><strong>Keep your login credentials confidential</strong> – You are responsible for all activities that occur under your account.</li>
                                    <li><strong>Notify us immediately</strong> of any unauthorized use of your account or any other security breach.</li>
                                </ul>
                                <p>We reserve the right to suspend or terminate your account if you provide false, inaccurate, or outdated information.</p>
                            </div>
                        </div>

                        <!-- User Responsibilities -->
                        <div class="terms-section">
                            <div class="terms-section-title">
                                <span class="icon icon-rose"><i class="bi bi-shield-check"></i></span>
                                User Responsibilities
                            </div>
                            <div class="terms-text">
                                <p>As a user of the POS System, you agree to:</p>
                                <ul>
                                    <li><strong>Use the Platform lawfully</strong> – You shall not use the POS System for any illegal, fraudulent, or unauthorized purpose.</li>
                                    <li><strong>Protect the integrity of the system</strong> – You shall not attempt to reverse-engineer, hack, or disrupt the Platform.</li>
                                    <li><strong>Be responsible for your actions</strong> – Any transactions, orders, or changes made using your account are your responsibility.</li>
                                    <li><strong>Maintain data accuracy</strong> – Ensure that all data entered into the system is accurate and up-to-date.</li>
                                    <li><strong>Secure your devices</strong> – Ensure that all devices used to access the Platform are secure and free from malware.</li>
                                </ul>
                                <div class="terms-highlight rose">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <strong>You are solely responsible for any data loss, financial loss, or damages 
                                    resulting from your failure to comply with these responsibilities.</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Data Privacy -->
                        <div class="terms-section">
                            <div class="terms-section-title">
                                <span class="icon icon-violet"><i class="bi bi-lock"></i></span>
                                Data Privacy & Security
                            </div>
                            <div class="terms-text">
                                <p>
                                    We value your privacy and are committed to protecting your personal data. 
                                    When you use the POS System, we may collect:
                                </p>
                                <ul>
                                    <li><strong>Personal Information:</strong> Name, email, phone number, and role.</li>
                                    <li><strong>Transaction Data:</strong> Sales, purchases, inventory, and customer data.</li>
                                    <li><strong>Usage Data:</strong> IP addresses, device information, and activity logs.</li>
                                </ul>
                                <p>
                                    We implement industry-standard security measures to protect your data. However, 
                                    we cannot guarantee absolute security against all threats.
                                </p>
                                <div class="terms-highlight violet">
                                    <i class="bi bi-shield-lock-fill me-2"></i>
                                    <strong>We will never sell or share your personal data with third parties 
                                    without your explicit consent, except as required by law.</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Intellectual Property -->
                        <div class="terms-section">
                            <div class="terms-section-title">
                                <span class="icon icon-navy"><i class="bi bi-c-circle"></i></span>
                                Intellectual Property
                            </div>
                            <div class="terms-text">
                                <p>
                                    The POS System, including its software, design, algorithms, content, and all 
                                    intellectual property rights, are owned by <strong>Leruma Enterprises</strong>.
                                </p>
                                <p>You are granted a non-exclusive, non-transferable, revocable license to use the 
                                Platform for your business operations. You may not:</p>
                                <ul>
                                    <li>Copy, modify, or distribute any part of the Platform.</li>
                                    <li>Reverse-engineer or attempt to extract the source code.</li>
                                    <li>Use the Platform for competitive purposes.</li>
                                    <li>Remove any proprietary notices or labels.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Acceptable Use -->
                        <div class="terms-section">
                            <div class="terms-section-title">
                                <span class="icon icon-sky"><i class="bi bi-hand-thumbs-up"></i></span>
                                Acceptable Use Policy
                            </div>
                            <div class="terms-text">
                                <p>You agree to use the POS System in a manner that is:</p>
                                <ul>
                                    <li><strong>Lawful</strong> – In compliance with all applicable laws and regulations.</li>
                                    <li><strong>Ethical</strong> – Not harming the reputation or integrity of the Platform.</li>
                                    <li><strong>Respectful</strong> – Not infringing on the rights of other users.</li>
                                </ul>
                                <p>You are specifically prohibited from:</p>
                                <ul>
                                    <li>Introducing viruses, malware, or harmful code.</li>
                                    <li>Attempting to gain unauthorized access to the Platform.</li>
                                    <li>Using the Platform for spamming, phishing, or fraud.</li>
                                    <li>Engaging in any activity that disrupts the Platform's performance.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Limitation of Liability -->
                        <div class="terms-section">
                            <div class="terms-section-title">
                                <span class="icon icon-rose"><i class="bi bi-exclamation-triangle"></i></span>
                                Limitation of Liability
                            </div>
                            <div class="terms-text">
                                <p>
                                    To the fullest extent permitted by law, <strong>Leruma Enterprises</strong> shall 
                                    not be liable for any indirect, incidental, special, consequential, or punitive 
                                    damages arising from your use of the POS System.
                                </p>
                                <div class="terms-highlight rose">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <strong>In no event shall our total liability exceed the total fees paid by 
                                    you to us in the 12 months preceding the claim.</strong>
                                </div>
                                <p>
                                    We do not guarantee that the Platform will be uninterrupted, error-free, 
                                    or completely secure. You acknowledge that your use of the Platform is at 
                                    your own risk.
                                </p>
                            </div>
                        </div>

                        <!-- Termination -->
                        <div class="terms-section">
                            <div class="terms-section-title">
                                <span class="icon icon-rose"><i class="bi bi-x-circle"></i></span>
                                Termination
                            </div>
                            <div class="terms-text">
                                <p>We reserve the right to terminate or suspend your access to the POS System:</p>
                                <ul>
                                    <li>If you violate these Terms.</li>
                                    <li>If you fail to pay any fees due.</li>
                                    <li>If you engage in any activity that is harmful to the Platform or other users.</li>
                                </ul>
                                <p>
                                    Upon termination, you must cease all use of the Platform and delete any 
                                    associated data. We may also disable your account and remove all data 
                                    associated with it.
                                </p>
                            </div>
                        </div>

                        <!-- Modifications -->
                        <div class="terms-section">
                            <div class="terms-section-title">
                                <span class="icon icon-violet"><i class="bi bi-pencil"></i></span>
                                Modifications to Terms
                            </div>
                            <div class="terms-text">
                                <p>
                                    We reserve the right to modify these Terms at any time. Any changes will be 
                                    effective immediately upon posting. We will notify you of significant changes 
                                    via email or through the Platform.
                                </p>
                                <div class="terms-highlight violet">
                                    <i class="bi bi-bell-fill me-2"></i>
                                    <strong>Your continued use of the POS System after any changes constitutes 
                                    your acceptance of the revised Terms.</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Governing Law -->
                        <div class="terms-section">
                            <div class="terms-section-title">
                                <span class="icon icon-navy"><i class="bi bi-gavel"></i></span>
                                Governing Law
                            </div>
                            <div class="terms-text">
                                <p>
                                    These Terms shall be governed by and construed in accordance with the laws of 
                                    the United Republic of Tanzania. Any disputes arising from these Terms shall 
                                    be subject to the exclusive jurisdiction of the courts of Tanzania.
                                </p>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="terms-section">
                            <div class="terms-section-title">
                                <span class="icon icon-amber"><i class="bi bi-envelope"></i></span>
                                Contact Information
                            </div>
                            <div class="terms-text">
                                <p>
                                    If you have any questions, concerns, or feedback regarding these Terms, 
                                    please contact us:
                                </p>
                                <ul>
                                    <li><strong>Email:</strong> <a href="mailto:support@leruma.com" style="color: var(--sky);">support@leruma.com</a></li>
                                    <li><strong>Phone:</strong> +255 123 456 789</li>
                                    <li><strong>Address:</strong> Dar es Salaam, Tanzania</li>
                                </ul>
                                <p>We strive to respond to all inquiries within 24-48 hours.</p>
                            </div>
                        </div>


                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Optional: Add any interactive behavior here
        console.log('Terms & Conditions page loaded');

        // You could add a function to track acceptance
        function recordAcceptance(action) {
            // Send acceptance to server
            fetch('{{ url("/api/terms/acceptance") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    action: action,
                    timestamp: new Date().toISOString()
                })
            })
            .then(response => response.json())
            .then(data => console.log('Acceptance recorded:', data))
            .catch(error => console.error('Error:', error));
        }

        // Attach to buttons if needed
        document.querySelectorAll('.btn-accept').forEach(btn => {
            btn.addEventListener('click', function(e) {
                // recordAcceptance('accept');
            });
        });

        document.querySelectorAll('.btn-decline').forEach(btn => {
            btn.addEventListener('click', function(e) {
                // recordAcceptance('decline');
            });
        });
    });
</script>
@include('footer')

</body>
</html>