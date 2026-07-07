@extends('layouts.app')
@push('title')
    {{ __('Terms & Conditions') }} -
@endpush
@section('content')
    <div id="headless-wrapper">
        <section class="bg-white py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-9">
                        <div class="mb-25 text-center">
                            <a href="{{ route('frontend') }}">
                                <img src="{{ getSettingImage('app_logo') }}" alt="{{ getOption('app_name') }}" style="max-height:60px;">
                            </a>
                        </div>

                        <h1 class="mb-2">{{ __('Terms of Use & Terms and Conditions') }}</h1>
                        <p class="text-muted mb-4">
                            {{ __('Version 1.1') }} &middot; {{ __('Effective Date') }}: 8th July 2026 &middot;
                            {{ __('Jurisdiction') }}: {{ __('Republic of Uganda') }}<br>
                            {{ getOption('app_name') }} {{ __('Co. Limited, Mbarara, Uganda') }} &middot;
                            <a href="mailto:orinnocore@gmail.com">orinnocore@gmail.com</a>
                        </p>

                        <p class="fw-bold">
                            {{ __('PLEASE READ THESE TERMS CAREFULLY BEFORE ACCESSING OR USING THE PLATFORM.') }}
                        </p>

                        <hr class="my-4">

                        <h4>{{ __('Section 1: Definitions and Interpretation') }}</h4>
                        <p><strong>{{ __('1.1 Defined Terms') }}</strong></p>
                        <ul>
                            <li><strong>"Platform"</strong> means the {{ getOption('app_name') }} web-based property management application, including all features, modules, and services accessible via the website and any associated sub-domains or mobile applications.</li>
                            <li><strong>"Company," "We," "Us," or "Our"</strong> refers to the registered business entity operating the Platform under the laws of the Republic of Uganda.</li>
                            <li><strong>"User," "You," or "Your"</strong> refers to any individual or entity that accesses, registers on, or uses the Platform in any capacity, including Landlords, Tenants, Maintainers, and Administrators.</li>
                            <li><strong>"Landlord" or "Owner"</strong> refers to any User who registers on the Platform to list, manage, or administer one or more properties or property units.</li>
                            <li><strong>"Tenant"</strong> refers to any User who is assigned or linked to a property unit through the Platform for the purpose of occupancy and rental payment management.</li>
                            <li><strong>"Maintainer"</strong> refers to any individual engaged through the Platform to carry out maintenance, repair, or inspection services on a property. Maintainers are independent contractors and are not employees or agents of the Company.</li>
                            <li><strong>"Administrator"</strong> refers to any User granted elevated system permissions to manage other Users, packages, or Platform settings on behalf of a Landlord or the Company.</li>
                            <li><strong>"Property"</strong> refers to any real estate asset listed, registered, or managed within the Platform, including all associated units, images, and details.</li>
                            <li><strong>"Unit"</strong> refers to a discrete rentable space within a Property, including but not limited to apartments, rooms, offices, or commercial spaces.</li>
                            <li><strong>"Tenancy"</strong> refers to the formal rental relationship between a Landlord and a Tenant as recorded and managed within the Platform.</li>
                            <li><strong>"Invoice"</strong> refers to any rent demand, payment request, or billing document generated through the Platform.</li>
                            <li><strong>"Transaction"</strong> refers to any financial transfer, payment, or collection of funds recorded in the Platform. As described in Section 6, the Company does not itself hold, custody, or take title to any such funds unless expressly stated otherwise in writing.</li>
                            <li><strong>"KYC"</strong> means Know Your Customer, referring to the identity verification processes conducted within the Platform as required.</li>
                            <li><strong>"Agreement"</strong> refers to any lease, tenancy contract, or digital document executed or stored through the Platform's agreement management module.</li>
                            <li><strong>"Maintenance Request"</strong> refers to any repair, inspection, or service request submitted through the Platform by a Tenant, Landlord, or Maintainer.</li>
                            <li><strong>"Personal Data"</strong> has the meaning ascribed to it under the Data Protection and Privacy Act 2019 of Uganda, and includes any information that identifies or can identify a natural person.</li>
                            <li><strong>"Services"</strong> refers collectively to all features, tools, modules, and functionalities offered through the Platform.</li>
                            <li><strong>"Content"</strong> means any data, text, images, documents, messages, or other materials uploaded, submitted, or transmitted through the Platform by Users.</li>
                            <li><strong>"Account"</strong> means a registered User profile on the Platform with associated login credentials and assigned roles.</li>
                            <li><strong>"Package" or "Subscription"</strong> refers to any paid service tier or feature bundle offered by the Company through the Platform, licensed to a single Landlord entity for that Landlord's own properties.</li>
                        </ul>

                        <p><strong>{{ __('1.2 Interpretation') }}</strong></p>
                        <ul>
                            <li>Words in the singular include the plural and vice versa.</li>
                            <li>References to any legislation include all amendments, re-enactments, or successor legislation thereto.</li>
                            <li>Section headings are for convenience only and shall not affect interpretation.</li>
                            <li>The word "including" shall mean "including without limitation."</li>
                        </ul>

                        <hr class="my-4">

                        <h4>{{ __('Section 2: Acceptance of Terms') }}</h4>
                        <p><strong>2.1 {{ __('Agreement to Terms') }}</strong> &mdash; By accessing, browsing, registering for, or using the Platform in any way, you acknowledge that you have read, understood, and agree to be legally bound by these Terms and Conditions and any additional policies or guidelines published on the Platform from time to time. If you do not agree to these Terms, you must immediately cease use of the Platform.</p>
                        <p><strong>2.2 {{ __('Acceptance by Conduct') }}</strong> &mdash; Your continued use of the Platform following any updates or modifications to these Terms shall constitute your acceptance of the revised Terms. It is your responsibility to review these Terms periodically. The Company will make reasonable efforts to notify Users of material changes in accordance with Section 14.1.</p>
                        <p><strong>2.3 {{ __('Capacity to Contract') }}</strong> &mdash; By using the Platform, you represent and warrant that:</p>
                        <ul>
                            <li>You are at least eighteen (18) years of age;</li>
                            <li>You have the legal capacity and authority to enter into a binding agreement under the laws of Uganda;</li>
                            <li>If you are accepting these Terms on behalf of a company, organisation, or other legal entity, you have the authority to bind that entity to these Terms;</li>
                            <li>Your use of the Platform does not violate any applicable law or regulation.</li>
                        </ul>
                        <p>Where the Company reasonably determines that an Account was registered by a person under eighteen (18) years of age, the Company reserves the right to immediately suspend the Account and delete associated Personal Data, save for records the Company is legally required to retain.</p>
                        <p><strong>2.4 {{ __('Electronic Agreement') }}</strong> &mdash; In accordance with the Electronic Transactions Act 2011 of Uganda, your acceptance of these Terms by electronic means (including by checking the "I Agree" box at registration, or using the Platform) constitutes a valid, binding, and enforceable electronic agreement.</p>

                        <hr class="my-4">

                        <h4>{{ __('Section 3: Description of the Platform and Services') }}</h4>
                        <p><strong>3.1 {{ __('Overview') }}</strong> &mdash; {{ getOption('app_name') }} is a cloud-based property management platform designed to digitise the rental lifecycle in Uganda, facilitating the management of properties, tenancies, payments, maintenance, communications, and associated documentation between Landlords, Tenants, and Maintainers.</p>
                        <p><strong>3.2 {{ __('Core Services Offered') }}</strong></p>
                        <p>a) <strong>Property and Unit Management</strong> &mdash; listing, categorisation, and management of residential and commercial properties and units; property search and filtering for prospective Tenants.</p>
                        <p>b) <strong>Tenancy Management</strong> &mdash; creation and management of tenancy records, onboarding of Tenants, tracking of tenancy status and documentation.</p>
                        <p>c) <strong>Invoicing and Rent Tracking</strong> &mdash; generation, delivery, and tracking of rent invoices, recurring invoice schedules, recording of payments, and access to payment history.</p>
                        <p>d) <strong>Agreement Management</strong> &mdash; storage and management of lease agreements and tenancy documents, and electronic agreement tracking.</p>
                        <p>e) <strong>Maintenance Request Management</strong> &mdash; submission and tracking of maintenance requests, assignment to Maintainers, and status tracking.</p>
                        <p>f) <strong>Financial Management</strong> &mdash; expense tracking, financial reporting tools, and tax and fee settings <em>for the Landlord's own record-keeping purposes only</em>. These tools do not constitute, and are not a substitute for, professional tax, accounting, or legal advice, and the Company accepts no liability for decisions made in reliance on them. Users should consult a qualified professional for tax and compliance matters.</p>
                        <p>g) <strong>Communication Tools</strong> &mdash; in-platform messaging, notice board, and a ticket and support request system.</p>
                        <p>h) <strong>Identity Verification (KYC)</strong> &mdash; collection and verification of identity documents to enhance trust on the Platform.</p>
                        <p>i) <strong>Subscription Packages</strong> &mdash; access to premium features through paid subscription plans, licensed per Landlord as described in Section 6.3.</p>

                        <p><strong>3.3 {{ __('Platform Limitations') }}</strong> &mdash; The Company does not own, lease, operate, or manage any physical property listed on the Platform, and is not a party to any tenancy, lease, or property agreement between Users. The Company acts solely as a technology service provider facilitating connections, record-keeping, and communication between Landlords, Tenants, and Maintainers.</p>
                        <p><strong>3.4 {{ __('Service Availability') }}</strong> &mdash; The Company will use reasonable efforts to maintain Platform availability but does not guarantee uninterrupted, error-free, or continuous access, and reserves the right to suspend, modify, or discontinue any feature at any time with reasonable notice where practicable.</p>

                        <hr class="my-4">

                        <h4>{{ __('Section 4: User Accounts and Registration') }}</h4>
                        <p><strong>4.1 {{ __('Account Registration') }}</strong> &mdash; To access the full features of the Platform, Users must register and create an Account, providing accurate, current, and complete information, and keeping it updated.</p>
                        <p><strong>4.2 {{ __('Account Types') }}</strong> &mdash; Landlord/Owner, Tenant, Maintainer, and Administrator Accounts.</p>
                        <p><strong>4.3 {{ __('Account Security') }}</strong> &mdash; You are solely responsible for maintaining the confidentiality of your login credentials, all activity under your Account, notifying the Company immediately at orinnocore@gmail.com of any unauthorised access or security breach, and not sharing your credentials with any third party. The Company shall not be liable for loss or damage arising from your failure to maintain the security of your credentials, except where such loss arises from the Company's own negligence or breach of Section 7.6.</p>
                        <p><strong>4.4 {{ __('Accuracy of Information') }}</strong> &mdash; You warrant that all information you submit is truthful, accurate, and not misleading. Providing false or fraudulent information constitutes a breach of these Terms and may result in immediate Account suspension or termination, in addition to any legal remedies available to the Company.</p>
                        <p><strong>4.5 {{ __('Account Suspension and Termination by the Company') }}</strong> &mdash; The Company reserves the right to suspend, restrict, or permanently terminate any Account at its sole discretion where the User has breached these Terms, engaged in fraudulent, abusive, or illegal activity, poses a risk to the security or integrity of the Platform, or where required by applicable law. Where practicable, the Company will provide reasonable notice of termination.</p>
                        <p><strong>4.6 {{ __('Voluntary Account Closure') }}</strong> &mdash; A User may request closure of their Account at any time by writing to orinnocore@gmail.com. Where a Landlord's Account is closed or terminated while a Tenancy is active, the Landlord remains responsible for making alternative arrangements to manage that Tenancy, and the Company will, where reasonably practicable, provide Tenants a limited grace period of access to their own historical invoice and payment records before deletion.</p>
                        <p><strong>4.7 {{ __('Administrator Access and Confidentiality') }}</strong> &mdash; Administrator Accounts are granted elevated access strictly for the purpose of managing the Platform, Users, or packages on behalf of a Landlord or the Company. Administrators must not access, use, or disclose another User's Personal Data or financial information except as strictly necessary to perform their assigned role, and remain bound by the confidentiality and data protection obligations in Section 7.</p>

                        <hr class="my-4">

                        <h4>{{ __('Section 5: User Obligations and Prohibited Conduct') }}</h4>
                        <p><strong>5.1 {{ __('General User Obligations') }}</strong> &mdash; All Users agree to use the Platform lawfully and in accordance with these Terms, comply with applicable Ugandan law, treat other Users with respect and dignity, promptly report bugs, errors, or security vulnerabilities to the Company, and not interfere with the proper functioning of the Platform.</p>
                        <p><strong>5.2 {{ __('Landlord-Specific Obligations') }}</strong> &mdash; Landlords additionally agree to ensure listings are accurate and not misleading, comply with all applicable Ugandan housing, tenancy, and landlord-tenant laws (including holding any permits or licences required to lawfully let the Property), not manage properties they do not own or have authority to manage, issue only lawful invoices, and treat Tenants fairly and without discrimination on any ground protected under the Constitution of the Republic of Uganda or other applicable law.</p>
                        <p><strong>5.3 {{ __('Tenant-Specific Obligations') }}</strong> &mdash; Tenants additionally agree to pay rent and other charges as invoiced, provide accurate information during onboarding and KYC verification, not misuse the maintenance request system, and comply with lawful notices issued by their Landlord through the Platform.</p>
                        <p><strong>5.4 {{ __('Prohibited Conduct') }}</strong> &mdash; The following is strictly prohibited: submitting false, fraudulent, misleading, or defamatory content; impersonating another person or entity; attempting unauthorised access to another User's Account or the Platform's backend systems; facilitating money laundering, fraud, or any financial crime; uploading viruses, malware, or harmful code; harvesting or scraping data without written authorisation; sending unsolicited communications or spam; conduct that violates the Computer Misuse Act 2011; sublicensing, reselling, or commercially exploiting Platform access without written consent; and any conduct that damages, disrupts, or impairs the Platform's operation.</p>
                        <p><strong>5.5 {{ __('Non-Circumvention') }}</strong> &mdash; Where the Platform is used to identify, screen, or connect Landlords, Tenants, or Maintainers, Users agree not to structure Transactions, invoicing, or communications outside the Platform for the specific purpose of avoiding applicable Platform fees or commissions disclosed to them at the time of the introduction. This clause does not restrict a Landlord and Tenant's general freedom to agree payment channels for an existing, already-invoiced Tenancy.</p>
                        <p><strong>5.6 {{ __('Subscription Seat Integrity') }}</strong> &mdash; A Landlord Subscription is licensed for use by that Landlord (and Administrators or staff it authorises) in respect of its own properties only. Sharing, sub-licensing, or reselling access to a Subscription to unrelated Landlords or third parties in order to avoid separate subscription fees is prohibited and may result in immediate suspension of the Account without refund.</p>
                        <p><strong>5.7 {{ __('Consequences of Breach') }}</strong> &mdash; Any breach of this Section may result in immediate Account suspension or termination, removal of Content, restriction of access to features, and/or legal action under applicable Ugandan law.</p>

                        <hr class="my-4">

                        <h4>{{ __('Section 6: Payments, Transactions, and Fees') }}</h4>
                        <p><strong>6.1 {{ __('Nature of Payment Facilitation') }}</strong> &mdash; The Platform facilitates the recording, tracking, and invoicing of rental payments and service fees. Unless expressly agreed with a User in a separate written agreement, the Company does not receive, hold, or take custody of Tenant or Landlord funds at any point: rent and other payments are made directly between Users through their own bank accounts, mobile money channels, or other third-party payment gateways, and the Platform records the resulting Transaction for tracking purposes only. The Company is not a payment institution, escrow agent, or trustee of any funds referenced on the Platform.</p>
                        <p><strong>6.2 {{ __('Transaction Commission') }}</strong> &mdash; Where the Company charges a service commission on a Transaction, the applicable rate will be disclosed to Users at the time of transaction or within the Platform's pricing and subscription information.</p>
                        <p><strong>6.3 {{ __('Subscription Fees') }}</strong> &mdash; Access to premium features requires payment of a subscription fee under the applicable Package. Subscription fees are non-refundable unless otherwise specified in writing by the Company, subject to change with reasonable advance notice to active subscribers, and payable in Ugandan Shillings (UGX) or such other currency as the Platform may support. Subscriptions renew and may be cancelled in accordance with the renewal and cancellation terms disclosed at the point of purchase. If a Subscription lapses or is cancelled, the Landlord's access to premium features will be restricted, but underlying tenancy, invoice, and payment records already created will be retained and remain viewable in accordance with Section 7.8, and are not deleted solely because a Subscription has lapsed.</p>
                        <p><strong>6.4 {{ __('Tenant Screening Fees') }}</strong> &mdash; Landlords may be charged fees for accessing tenant background checks or reputation scoring features, disclosed within the Platform prior to payment.</p>
                        <p><strong>6.5 {{ __('Responsibility for Transactions') }}</strong> &mdash; The Company is not responsible for: any failure by a Tenant to pay rent; disputes between Landlords and Tenants regarding payment amounts or conditions; payment failures caused by third-party payment gateways, mobile money providers, or network issues; or any loss resulting from incorrect payment details submitted by a User.</p>
                        <p><strong>6.6 {{ __('Taxes') }}</strong> &mdash; Users are responsible for all applicable taxes arising from their use of the Platform, including VAT and withholding taxes, in accordance with the Tax Procedures Code Act, Uganda Revenue Authority requirements, and applicable Ugandan tax legislation.</p>
                        <p><strong>6.7 {{ __('Disputes on Charges') }}</strong> &mdash; Any dispute regarding Platform fees or commissions must be raised in writing to orinnocore@gmail.com within fourteen (14) days of the charge. Disputes between a Landlord and a Tenant regarding the underlying rent or Tenancy itself are governed by their own tenancy agreement and, where they cannot be resolved directly, may be escalated under Section 13.</p>

                        <hr class="my-4">

                        <h4>{{ __('Section 7: Data Protection and Privacy') }}</h4>
                        <p><strong>7.1 {{ __('Commitment to Data Protection') }}</strong> &mdash; The Company is committed to responsible data handling and to aligning its data practices with the Data Protection and Privacy Act 2019 of Uganda ("DPPA") and all applicable data protection frameworks in force from time to time.</p>
                        <p><strong>7.2 {{ __('Data We Collect') }}</strong> &mdash; identity and contact information (including National ID/Passport details and photographs for KYC); property and tenancy information; financial information (invoice/payment histories, bank or mobile money details where provided, transaction records); and technical and usage data (IP addresses, device identifiers, access logs, in-platform communications).</p>
                        <p><strong>7.3 {{ __('Purpose of Data Collection') }}</strong> &mdash; to create and manage Accounts; facilitate tenancy management, invoicing, and payment tracking; conduct KYC and identity verification; improve the Platform; communicate with Users; comply with legal and regulatory obligations; and detect, investigate, and prevent fraud or misuse.</p>
                        <p><strong>7.4 {{ __('Legal Basis for Processing') }}</strong> &mdash; performance of a contract; legitimate interests in the security, improvement, and operation of the Platform; consent, where given for specific processing; and legal obligation, where required by Ugandan law.</p>
                        <p><strong>7.5 {{ __('Data Sharing') }}</strong> &mdash; The Company does not sell Personal Data. Data may be shared with other Users where necessary for the operation of the Platform (e.g. sharing Tenant information with a Landlord), with third-party service providers who assist in Platform operations (e.g. payment processors, hosting providers) under confidentiality obligations, with regulatory or law enforcement authorities where required by law, and in the event of a merger, acquisition, or restructuring, subject to confidentiality obligations.</p>
                        <p><strong>7.6 {{ __('Data Security') }}</strong> &mdash; The Company implements reasonable technical and organisational measures to protect Personal Data from unauthorised access, disclosure, alteration, and destruction, including encryption of sensitive data where practicable and role-based access controls for Administrators. However, no electronic transmission or storage system is entirely secure, and by using the Platform you acknowledge the inherent risks of electronic data transmission and storage.</p>
                        <p><strong>7.7 {{ __('Data Breach Notification') }}</strong> &mdash; In the event of a data breach that the Company reasonably believes is likely to result in a risk to the rights and freedoms of affected Users, the Company will, without undue delay and in accordance with the DPPA, notify affected Users and, where required, the relevant regulatory authority, and take reasonable steps to contain and remediate the breach.</p>
                        <p><strong>7.8 {{ __('Cross-Border Data Transfer') }}</strong> &mdash; Where the Platform's hosting, backup, or support infrastructure is located outside Uganda, the Company will only transfer Personal Data to such locations where the recipient is subject to a level of data protection consistent with the DPPA, including through contractual safeguards with its service providers.</p>
                        <p><strong>7.9 {{ __('User Rights') }}</strong> &mdash; Subject to applicable Ugandan law, you have the right to request access to, correction of, or deletion of your Personal Data (subject to the Company's legal retention obligations), to withdraw consent where consent was the basis for processing, and to lodge a complaint with the Personal Data Protection Office established under the DPPA or any successor regulatory authority. To exercise these rights, contact orinnocore@gmail.com; the Company will respond within a reasonable period.</p>
                        <p><strong>7.10 {{ __('Data Retention') }}</strong> &mdash; The Company retains Personal Data for as long as necessary for the purposes set out in these Terms or as required by applicable Ugandan law. Financial records may be retained for up to seven (7) years for tax and accounting compliance. Inactive Accounts may be subject to data retention review after twenty-four (24) months of inactivity.</p>
                        <p><strong>7.11 {{ __('Cookies and Tracking') }}</strong> &mdash; The Platform may use cookies and similar technologies to enhance User experience, manage sessions, and analyse usage patterns. You may disable cookies through your browser settings, though this may affect Platform functionality.</p>

                        <hr class="my-4">

                        <h4>{{ __('Section 8: Identity Verification (KYC)') }}</h4>
                        <p><strong>8.1</strong> The Platform may require Users to undergo KYC verification as a condition of accessing certain features, including processing payments, entering formal tenancy relationships, or accessing premium subscription features.</p>
                        <p><strong>8.2</strong> KYC verification may require a valid government-issued identification document, proof of address, and such other documentation as specified in the Platform's KYC configuration.</p>
                        <p><strong>8.3</strong> By submitting KYC documents, you represent that they are genuine, accurate, and complete, that you are the lawful holder of the documents, and you consent to the Company processing and storing your KYC information for verification purposes.</p>
                        <p><strong>8.4</strong> The Company reserves the right to reject any KYC submission that appears fraudulent, incomplete, or inconsistent, and failure to complete KYC verification may result in restricted access to certain features.</p>
                        <p><strong>8.5</strong> KYC documents and associated data are stored and processed in accordance with Section 7 and will not be shared with third parties except as required by applicable law or with the User's explicit consent.</p>

                        <hr class="my-4">

                        <h4>{{ __('Section 9: Intellectual Property') }}</h4>
                        <p><strong>9.1</strong> All intellectual property rights in the Platform, including software code, algorithms, interface designs, logos, and documentation, are owned exclusively by the Company or its licensors.</p>
                        <p><strong>9.2</strong> You retain ownership of any Content you upload. By uploading Content, you grant the Company a non-exclusive, royalty-free, worldwide licence to use, store, process, and display such Content solely to operate and improve the Platform. The Company will not sell or commercially exploit your Content without your prior consent.</p>
                        <p><strong>9.3</strong> Users must not copy, reproduce, modify, or create derivative works from the Platform; reverse-engineer, decompile, or disassemble its software; remove proprietary notices; or use automated tools to extract data without written permission.</p>
                        <p><strong>9.4</strong> Feedback you provide about the Platform may be used by the Company for any purpose without obligation or compensation to you.</p>

                        <hr class="my-4">

                        <h4>{{ __('Section 10: Disclaimers and Limitation of Liability') }}</h4>
                        <p><strong>10.1</strong> THE PLATFORM IS PROVIDED ON AN "AS IS" AND "AS AVAILABLE" BASIS WITHOUT WARRANTIES OF ANY KIND, EXPRESS OR IMPLIED, TO THE FULLEST EXTENT PERMITTED BY APPLICABLE LAW.</p>
                        <p><strong>10.2</strong> The Company does not guarantee that a Tenant will pay rent on time or in full; that listings are accurate or available; that KYC verification will prevent all fraud; that maintenance requests will be fulfilled to any particular standard, or the quality of any Maintainer's work; or the creditworthiness, identity, or conduct of any User. Maintainers are independent contractors and the Company is not liable for their acts, omissions, negligence, or any resulting property damage.</p>
                        <p><strong>10.3</strong> The Platform may integrate with or link to third-party services, including payment gateways and mobile money providers. The Company is not responsible for the availability, accuracy, or conduct of such services, which are governed by their own terms.</p>
                        <p><strong>10.4 {{ __('Limitation of Liability') }}</strong> &mdash; To the maximum extent permitted by the laws of Uganda, the Company shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising out of or in connection with your use of the Platform. The Company's aggregate liability to any User for any claim arising from or related to the Platform shall not exceed the total amount paid by that User to the Company in the twelve (12) months preceding the event giving rise to the claim, or UGX 500,000, whichever is lower.</p>
                        <p><strong>10.5 {{ __('Carve-Outs') }}</strong> &mdash; Nothing in these Terms excludes or limits the Company's liability for: (a) death or personal injury caused by the Company's negligence; (b) fraud or fraudulent misrepresentation by the Company; (c) gross negligence or wilful misconduct by the Company; or (d) any liability that cannot be excluded or limited under the laws of Uganda. The limitation in Section 10.4 applies only to the extent such liability is not covered by this Section 10.5.</p>
                        <p><strong>10.6 {{ __('Indemnification') }}</strong> &mdash; You agree to indemnify, defend, and hold harmless the Company, its officers, directors, employees, agents, and contractors from claims, liabilities, damages, losses, and expenses (including legal fees) arising out of your use of the Platform in breach of these Terms, any Content you submit, your violation of any law or third-party right, or any dispute between you and another User.</p>
                        <p><strong>10.7 {{ __('Force Majeure') }}</strong> &mdash; The Company shall not be liable for any failure or delay in performance due to circumstances beyond its reasonable control, including acts of God, natural disasters, government restrictions, power failures, internet outages, or civil unrest.</p>

                        <hr class="my-4">

                        <h4>{{ __('Section 11: Maintenance Requests and Agreements') }}</h4>
                        <p><strong>11.1</strong> Tenants may submit maintenance requests and Landlords may assign them to Maintainers. The Company is not responsible for the quality, timeliness, or outcome of any maintenance work; the feature is a facilitation tool only; all contractual obligations regarding maintenance remain between the Landlord, Tenant, and Maintainer; and Maintainers are independent contractors, not employees of the Company.</p>
                        <p><strong>11.2</strong> The Platform supports creation, storage, and management of digital tenancy agreements. The Company does not verify the legal enforceability of any agreement created or stored on the Platform, is not a party to any such agreement, and Users are solely responsible for ensuring agreements comply with applicable Ugandan tenancy and contract law. Users should seek independent legal advice before executing binding agreements.</p>
                        <p><strong>11.3</strong> Records of agreement transactions, signing statuses, and associated data may be stored on the Platform for audit and record-keeping purposes in accordance with Section 7.</p>

                        <hr class="my-4">

                        <h4>{{ __('Section 12: Communications, Notices, and Messaging') }}</h4>
                        <p><strong>12.1</strong> Messages sent through the Platform are stored and may be accessed by the Company for security, legal compliance, and dispute resolution purposes. You must not use the messaging system to send unlawful, abusive, threatening, defamatory, or offensive content, and the Company may remove any message that violates these Terms.</p>
                        <p><strong>12.2</strong> Landlords may post notices to Tenants through the notice board. Notices are deemed received by the Tenant upon publication unless the Tenant can demonstrate otherwise.</p>
                        <p><strong>12.3</strong> Users may submit support requests through the Platform's ticket system. The Company will use reasonable efforts to respond within a reasonable timeframe but does not guarantee specific response times.</p>
                        <p><strong>12.4</strong> By creating an Account, you consent to receiving communications from the Company related to your Account, transactions, and Platform updates, and may opt out of non-essential communications through your Account settings.</p>
                        <p><strong>12.5</strong> Legal notices to the Company under these Terms shall be sent to orinnocore@gmail.com, and are deemed effective upon acknowledgment of receipt by the Company.</p>

                        <hr class="my-4">

                        <h4>{{ __('Section 13: Governing Law and Dispute Resolution') }}</h4>
                        <p><strong>13.1</strong> These Terms are governed by the laws of the Republic of Uganda, including the Electronic Transactions Act 2011, the Computer Misuse Act 2011, the Contract Act (Cap. 73), the Data Protection and Privacy Act 2019, the Communications Act 2013, and the Tax Procedures Code Act 2014.</p>
                        <p><strong>13.2</strong> The parties shall first attempt to resolve any dispute amicably through good-faith negotiation within thirty (30) days of written notice.</p>
                        <p><strong>13.3</strong> If unresolved, the parties agree to submit the dispute to mediation under the rules of the Centre for Arbitration and Dispute Resolution (CADER) or another agreed mediation body, conducted in Kampala, Uganda, in English.</p>
                        <p><strong>13.4</strong> If the dispute remains unresolved after mediation, either party may refer it to the courts of competent jurisdiction within the Republic of Uganda, to whose exclusive jurisdiction the parties irrevocably submit.</p>
                        <p><strong>13.5</strong> Disputes shall be resolved on an individual basis and not as part of a class action or representative proceeding, to the extent permitted by applicable law.</p>

                        <hr class="my-4">

                        <h4>{{ __('Section 14: Miscellaneous Provisions') }}</h4>
                        <p><strong>14.1 {{ __('Amendments') }}</strong> &mdash; The Company may amend, update, or replace these Terms at any time. Material changes will be communicated to Users via email or in-platform notification at least fourteen (14) days prior to taking effect, where reasonably practicable. Continued use of the Platform after the effective date of any amendment constitutes acceptance of the revised Terms.</p>
                        <p><strong>14.2 {{ __('Severability') }}</strong> &mdash; If any provision of these Terms is found invalid, unlawful, or unenforceable by a competent court, that provision shall be severed without affecting the validity of the remaining provisions.</p>
                        <p><strong>14.3 {{ __('Entire Agreement') }}</strong> &mdash; These Terms, together with any additional policies published on the Platform, constitute the entire agreement between you and the Company regarding your use of the Platform.</p>
                        <p><strong>14.4 {{ __('No Waiver') }}</strong> &mdash; The Company's failure to enforce any provision shall not be construed as a waiver of its right to do so at any future time.</p>
                        <p><strong>14.5 {{ __('Assignment') }}</strong> &mdash; You may not assign or transfer your rights or obligations under these Terms without the Company's prior written consent. The Company may assign its rights and obligations without restriction.</p>
                        <p><strong>14.6 {{ __('Third-Party Beneficiaries') }}</strong> &mdash; These Terms do not create rights in or for any third parties unless expressly stated otherwise.</p>
                        <p><strong>14.7 {{ __('Language') }}</strong> &mdash; These Terms are written in English. In the event of any conflict between an English version and a translated version, the English version prevails.</p>
                        <p><strong>14.8 {{ __('Relationship of Parties') }}</strong> &mdash; Nothing in these Terms creates a partnership, joint venture, employment, or agency relationship between you and the Company. The Company and Users are independent parties.</p>

                        <hr class="my-4">

                        <h4>{{ __('Section 15: Contact Information') }}</h4>
                        <p>
                            <strong>Company:</strong> {{ getOption('app_name') }} Co. Limited<br>
                            <strong>Email:</strong> <a href="mailto:orinnocore@gmail.com">orinnocore@gmail.com</a><br>
                            <strong>Location:</strong> Mbarara, Uganda<br>
                            <strong>Response Time:</strong> {{ __('We aim to respond to all enquiries within 5 business days.') }}
                        </p>

                        <hr class="my-4">

                        <h4>{{ __('Section 16: User Acknowledgement') }}</h4>
                        <p class="border p-3 bg-light">
                            {{ __('BY ACCESSING, REGISTERING FOR, OR USING THE PLATFORM, YOU CONFIRM THAT YOU HAVE READ, UNDERSTOOD, AND AGREE TO BE BOUND BY THESE TERMS AND CONDITIONS IN THEIR ENTIRETY. IF YOU DO NOT AGREE, YOU MUST IMMEDIATELY DISCONTINUE USE OF THE PLATFORM.') }}
                        </p>
                        <p class="text-muted">{{ __('These Terms and Conditions were last updated on 8th July 2026 and are effective immediately upon publication.') }}</p>
                        <p class="text-muted">&copy; {{ date('Y') }} {{ getOption('app_name') }} Co. Limited. {{ __('All Rights Reserved.') }}</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
