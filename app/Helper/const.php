<?php

const ACTIVE = 1;
const DEACTIVATE = 0;
const CANCEL = 2;
// User Role Type
const USER_ROLE_OWNER = 1;
const USER_ROLE_TENANT = 2;
const USER_ROLE_MAINTAINER = 3;
const USER_ROLE_ADMIN = 4;
const USER_ROLE_TEAM_MEMBER = 5;

// Gateway
const GATEWAY_MODE_LIVE = 1;
const GATEWAY_MODE_SANDBOX = 2;

// User Status
const USER_STATUS_INACTIVE = 0;
const USER_STATUS_ACTIVE = 1;
const USER_STATUS_DELETED = 2;
const USER_STATUS_UNVERIFIED = 3;

const OWNER_STATUS_ACTIVE = 1;
const OWNER_STATUS_INACTIVE = 0;

const KYC_STATUS_ACCEPTED = 1;
const KYC_STATUS_PENDING = 2;
const KYC_STATUS_REJECTED = 3;

// Order payment status
const ORDER_PAYMENT_STATUS_PENDING = 0;
const ORDER_PAYMENT_STATUS_PAID = 1;
const ORDER_PAYMENT_STATUS_CANCELLED = 2;

const DURATION_TYPE_MONTHLY = 1;
const DURATION_TYPE_YEARLY = 2;

//Property
const PROPERTY_TYPE_OWN = 1;
const PROPERTY_TYPE_LEASE = 2;
const PROPERTY_UNIT_TYPE_SINGLE = 1;
const PROPERTY_UNIT_TYPE_MULTIPLE = 2;

// Property Amenity
const PROPERTY_AMENITY_FIRE_SECURITY = 1;
const PROPERTY_AMENITY_ELECTRICITY = 2;
const PROPERTY_AMENITY_KITCHEN = 3;
const PROPERTY_AMENITY_GARAGE = 4;
const PROPERTY_AMENITY_SWIMMING_POOL = 5;
const PROPERTY_AMENITY_SECURITY_PRIVACY = 6;
const PROPERTY_AMENITY_ECO_FRIENDLY_ENERGY = 7;

// property advantage
const PROPERTY_ADVANTAGE_PETS_ALLOWED = 1;

//Property Unit
const PROPERTY_UNIT_RENT_TYPE_MONTHLY = 1;
const PROPERTY_UNIT_RENT_TYPE_YEARLY = 2;
const PROPERTY_UNIT_RENT_TYPE_CUSTOM = 3;

const LISTING_STATUS_ACTIVE = 1;
const LISTING_STATUS_DEACTIVATE = 2;
const LISTING_STATUS_CLOSED = 3;

const LISTING_CARD_TYPE_ONE = 1;

const LISTING_CONTACT_STATUS_PENDING = 1;
const LISTING_CONTACT_STATUS_VIEWED = 2;
const LISTING_CONTACT_STATUS_MAILED = 3;

const PROPERTY_STATUS_ACTIVE = 1;
const PROPERTY_STATUS_DEACTIVATE = 2;

const SEND_EMAIL_STATUS_ACTIVE = 1;
const SEND_EMAIL_STATUS_DEACTIVATE = 0;

const REMAINDER_STATUS_ACTIVE = 1;
const REMAINDER_STATUS_DEACTIVATE = 0;

const REMAINDER_EVERYDAY_STATUS_ACTIVE = 1;
const REMAINDER_EVERYDAY_STATUS_DEACTIVATE = 0;

const EMAIL_VERIFICATION_STATUS_ACTIVE = 1;
const EMAIL_VERIFICATION_STATUS_DEACTIVATE = 0;

//Message
const SOMETHING_WENT_WRONG = "Something went wrong! Please try again";
const CREATED_SUCCESSFULLY = "Created Successfully";
const UPDATED_SUCCESSFULLY = "Updated Successfully";
const STATUS_UPDATED_SUCCESSFULLY = "Status Updated Successfully";
const DELETED_SUCCESSFULLY = "Deleted Successfully";
const UPLOADED_SUCCESSFULLY = "Uploaded Successfully";
const DATA_FETCH_SUCCESSFULLY = "Data Fetch Successfully";
const SENT_SUCCESSFULLY = "Sent Successfully";
const PAY_SUCCESSFULLY = "Pay Successfully";
const REPLIED_SUCCESSFULLY = "Replied Successfully";
const VALIDATION_ERRORS = "Validation Errors";
const VERIFY_YOUR_EMAIL = "Verify Your Email";
const EMAIL_VERIFIED_SUCCESSFULLY = "Email Verified Successfully";
const LOGIN_SUCCESSFUL = "Login Successful";
const CANCELED_SUCCESSFULLY = "Canceled Successfully!";
const ASSIGNED_SUCCESSFULLY = "Assigned Successfully!";


// Property Step Active Class
const PROPERTY_INFORMATION_ACTIVE_CLASS = 1;
const LOCATION_ACTIVE_CLASS = 2;
const UNIT_ACTIVE_CLASS = 3;
const RENT_CHARGE_ACTIVE_CLASS = 4;
const IMAGE_ACTIVE_CLASS = 5;

//Expense
const EXPENSE_RESPONSIBILITY_TENANT = 1;
const EXPENSE_RESPONSIBILITY_OWNER = 2;

const FORM_STEP_ONE = 1;
const FORM_STEP_TWO = 2;
const FORM_STEP_THREE = 3;

const TENANT_STATUS_ACTIVE = 1;
const TENANT_STATUS_INACTIVE = 2;
const TENANT_STATUS_DRAFT = 3;
const TENANT_STATUS_CLOSE = 4;

const RENT_TYPE_MONTHLY = 1;
const RENT_TYPE_YEARLY = 2;
const RENT_TYPE_CUSTOM = 3;
//Invoice
const INVOICE_STATUS_PENDING = 0;
const INVOICE_STATUS_PAID = 1;
const INVOICE_STATUS_OVER_DUE = 2;

const INVOICE_RECURRING_TYPE_MONTHLY = 1;
const INVOICE_RECURRING_TYPE_YEARLY = 2;
const INVOICE_RECURRING_TYPE_CUSTOM = 3;

const NOTICE_STATUS_VIEW = 1;
const NOTICE_STATUS_PENDING = 0;

const NOTIFICATION_TYPE_MULTIPLE = 1;
const NOTIFICATION_TYPE_SINGLE = 2;

const MAINTENANCE_REQUEST_STATUS_COMPLETE = 1;
const MAINTENANCE_REQUEST_STATUS_INPROGRESS = 2;
const MAINTENANCE_REQUEST_STATUS_PENDING = 3;

const TICKET_STATUS_OPEN = 1;
const TICKET_STATUS_INPROGRESS = 2;
const TICKET_STATUS_CLOSE = 3;
const TICKET_STATUS_REOPEN = 4;
const TICKET_STATUS_RESOLVED = 5;

const TAX_TYPE_FIXED = 0;
const TAX_TYPE_PERCENTAGE = 1;

const TYPE_FIXED = 0;
const TYPE_PERCENTAGE = 1;

//Gateway Name
const PAYPAL = 'paypal';
const STRIPE = 'stripe';
const RAZORPAY = 'razorpay';
const INSTAMOJO = 'instamojo';
const MOLLIE = 'mollie';
const PAYSTACK = 'paystack';
const SSLCOMMERZ = 'sslcommerz';
const MERCADOPAGO = 'mercadopago';
const FLUTTERWAVE = 'flutterwave';
const BINANCE = 'binance';
const ALIPAY = 'alipay';
const BANK = 'bank';
const CASH = 'cash';
const WALLET = 'wallet';
const COINBASE = 'coinbase';
const PAYTM = 'paytm';
const MAXICASH = 'maxicash';
const IYZIPAY = 'iyzipay';
const BITPAY = 'bitpay';
const ZITOPAY = 'zitopay';
const PAYHERE = 'payhere';
const CINETPAY = 'cinetpay';
const VOGUEPAY = 'voguepay';
const TOYYIBPAY = 'toyyibpay';
const PAYMOB = 'paymob';
const AUTHORIZE = 'authorize';
const XENDIT = 'xendit';
const PADDLE = 'paddle';

// email templates
const EMAIL_TEMPLATE_CUSTOM = 1;
const EMAIL_TEMPLATE_INVOICE = 2;
const EMAIL_TEMPLATE_REMINDER = 3;
const EMAIL_TEMPLATE_SIGN_UP = 4;
const EMAIL_TEMPLATE_SUBSCRIPTION_SUCCESS = 5;
const EMAIL_TEMPLATE_THANK_YOU = 6;
const EMAIL_TEMPLATE_EMAIL_VERIFY = 7;
const EMAIL_TEMPLATE_WELCOME = 8;
const EMAIL_TEMPLATE_LISTING_REPLY = 9;
const EMAIL_TEMPLATE_LISTING_CONTACT = 10;


// target audience
const TARGET_AUDIENCE_PROPERTY = 1;
const TARGET_AUDIENCE_USER = 2;
const TARGET_AUDIENCE_CUSTOM = 3;

// history status
const SMS_STATUS_DELIVERED = 1;
const SMS_STATUS_PENDING = 2;
const SMS_STATUS_FAILED = 3;

const MAIL_STATUS_DELIVERED = 1;
const MAIL_STATUS_PENDING = 2;
const MAIL_STATUS_FAILED = 3;

// user type
const USER_TYPE_TENANT = 1;
const USER_TYPE_MAINTAINER = 2;

// package rules
const RULES_MAINTAINER = 1;
const RULES_PROPERTY = 2;
const RULES_TENANT = 3;
const RULES_INVOICE = 4;
const RULES_AUTO_INVOICE = 5;
const RULES_PLAN_REMAINING_DAYS = 6;
const RULES_UNIT = 7;

const PACKAGE_DURATION_TYPE_MONTHLY = 1;
const PACKAGE_DURATION_TYPE_YEARLY = 2;

const PACKAGE_TYPE_DEFAULT = 0;
const PACKAGE_TYPE_PROPERTY = 1;
const PACKAGE_TYPE_UNIT = 2;
const PACKAGE_TYPE_TENANT = 3;

const LINK_SAAS_ADDON = "https://codecanyon.net/item/zaiproty-property-management-saas-addon/45346185?s_rank=18";
const LINK_MAIN_SCRIPT = "https://codecanyon.net/item/zaiproty-property-management-laravel-script/43413718";
const LISTING_ADDON = "https://codecanyon.net/item/zaiproty-agreementdocument-signing-addon/46668990?s_rank=15";
