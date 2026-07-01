<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;

class SettingController extends Controller
{
    use ResponseTrait;

    public function systemCurrency()
    {
        return $this->success(getSystemCurrency());
    }

    public function systemSetting()
    {
        $setting = config('settings');
        $data['app_name'] = $setting['app_name'] ?? '';
        $data['build_version'] = $setting['build_version'] ?? 0;
        $data['current_version'] = $setting['current_version'] ?? 0;
        $data['app_name'] = $setting['app_name'] ?? '';
        $data['app_email'] = $setting['app_email'] ?? '';
        $data['app_contact_number'] = $setting['app_contact_number'] ?? '';
        $data['app_location'] = $setting['app_location'] ?? '';
        $data['app_copyright'] = $setting['app_copyright'] ?? '';
        $data['app_developed_by'] = $setting['app_developed_by'] ?? '';
        $data['app_preloader_status'] = $setting['app_preloader_status'] ?? '';
        $data['sign_in_text_title'] = $setting['sign_in_text_title'] ?? '';
        $data['sign_in_text_subtitle'] = $setting['sign_in_text_subtitle'] ?? '';
        $data['meta_keyword'] = $setting['meta_keyword'] ?? '';
        $data['meta_author'] = $setting['meta_author'] ?? '';
        $data['revisit'] = $setting['revisit'] ?? '';
        $data['sitemap_link'] = $setting['sitemap_link'] ?? '';
        $data['meta_description'] = $setting['meta_description'] ?? '';
        $data['app_logo'] = getSettingImage('app_logo') ?? '';
        $data['app_fav_icon'] = getSettingImage('app_fav_icon') ?? '';
        $data['app_preloader'] = getSettingImage('app_preloader') ?? '';
        $data['sign_in_image'] = getSettingImage('sign_in_image') ?? '';
        $data['website_primary_color'] = $setting['website_primary_color'] ?? '';
        $data['website_secondary_color'] = $setting['website_secondary_color'] ?? '';
        $data['button_primary_color'] = $setting['button_primary_color'] ?? '';
        $data['button_hover_color'] = $setting['button_hover_color'] ?? '';
        $data['website_color_mode'] = $setting['website_color_mode'] ?? '';
        $data['PROTYSAAS_current_version'] = $setting['PROTYSAAS_current_version'] ?? 0;
        $data['tax_type'] = $setting['tax_type'] ?? '';
        $data['app_footer_text'] = $setting['app_footer_text'] ?? '';
        $data['trail_duration'] = $setting['trail_duration'] ?? '';
        $data['facebook_url'] = $setting['facebook_url'] ?? '';
        $data['twitter_url'] = $setting['twitter_url'] ?? '';
        $data['linkedin_url'] = $setting['linkedin_url'] ?? '';
        $data['skype_url'] = $setting['skype_url'] ?? '';
        $data['home_hero_title'] = $setting['home_hero_title'] ?? '';
        $data['home_hero_summery'] = $setting['home_hero_summery'] ?? '';
        $data['home_hero_section_status'] = $setting['home_hero_section_status'] ?? '';
        $data['home_hero_image'] = getSettingImage('home_hero_image') ?? 0;
        $data['home_features_name'] = $setting['home_features_name'] ?? '';
        $data['home_features_title'] = $setting['home_features_title'] ?? '';
        $data['home_features_summery'] = $setting['home_features_summery'] ?? '';
        $data['home_feature_section_status'] = $setting['home_feature_section_status'] ?? '';
        $data['home_features_image'] = getSettingImage('home_features_image') ?? '';
        $data['home_about_us_name'] = $setting['home_about_us_name'] ?? '';
        $data['home_about_us_title'] = $setting['home_about_us_title'] ?? '';
        $data['home_about_us_section_status'] = $setting['home_about_us_section_status'] ?? '';
        $data['home_about_us_image'] = getSettingImage('home_about_us_image') ?? '';
        $data['home_how_it_word_section_name'] = $setting['home_how_it_word_section_name'] ?? '';
        $data['home_how_it_word_section_title'] = $setting['home_how_it_word_section_title'] ?? '';
        $data['home_how_it_word_section_summery'] = $setting['home_how_it_word_section_summery'] ?? '';
        $data['home_how_it_word_section_status'] = $setting['home_how_it_word_section_status'] ?? '';
        $data['home_core_pages_section_name'] = $setting['home_core_pages_section_name'] ?? '';
        $data['home_core_pages_section_title'] = $setting['home_core_pages_section_title'] ?? '';
        $data['home_core_pages_section_summery'] = $setting['home_core_pages_section_summery'] ?? '';
        $data['home_core_pages_section_status'] = $setting['home_core_pages_section_status'] ?? '';
        $data['home_price_section_name'] = $setting['home_price_section_name'] ?? '';
        $data['home_price_section_title'] = $setting['home_price_section_title'] ?? '';
        $data['home_pricing_section_status'] = $setting['home_pricing_section_status'] ?? '';
        $data['home_integration_section_name'] = $setting['home_integration_section_name'] ?? '';
        $data['home_integration_section_title'] = $setting['home_integration_section_title'] ?? '';
        $data['home_integration_section_summary'] = $setting['home_integration_section_summary'] ?? '';
        $data['home_integration_section_status'] = $setting['home_integration_section_status'] ?? '';
        $data['home_integration_section_image'] = getSettingImage('home_integration_section_image') ?? '';
        $data['home_testimonial_section_name'] = $setting['home_testimonial_section_name'] ?? '';
        $data['home_testimonial_section_title'] = $setting['home_testimonial_section_title'] ?? '';
        $data['home_testimonial_section_status'] = $setting['home_testimonial_section_status'] ?? '';
        $data['home_faq_section_name'] = $setting['home_faq_section_name'] ?? '';
        $data['home_faq_section_title'] = $setting['home_faq_section_title'] ?? '';
        $data['home_faq_section_status'] = $setting['home_faq_section_status'] ?? '';
        $data['app_logo_white'] = getSettingImage('app_logo_white') ?? '';
        $data['PROTYSMS_build_version'] = $setting['PROTYSMS_build_version'] ?? 0;
        $data['PROTYSMS_current_version'] = $setting['PROTYSMS_current_version'] ?? 0;
        $data['terms_conditions'] = $setting['terms_conditions'] ?? '';
        $data['privacy_policy'] = $setting['privacy_policy'] ?? '';
        $data['cookie_policy'] = $setting['cookie_policy'] ?? '';
        $data['PROTYSAAS_build_version'] = $setting['PROTYSAAS_build_version'] ?? 0;
        $data['PROTYAGREEMENT_build_version'] = $setting['PROTYAGREEMENT_build_version'] ?? 0;
        $data['PROTYAGREEMENT_current_version'] = $setting['PROTYAGREEMENT_current_version'] ?? 0;
        $data['sign_up_email_status'] = $setting['sign_up_email_status'] ?? '';
        $data['send_email_status'] = $setting['send_email_status'] ?? '';
        $data['email_verification_status'] = $setting['email_verification_status'] ?? '';
        $data['PROTYTENANCY_build_version'] = $setting['PROTYTENANCY_build_version'] ?? 0;
        $data['PROTYTENANCY_current_version'] = $setting['PROTYTENANCY_current_version'] ?? 0;
        $data['remainder_status'] = $setting['remainder_status'] ?? '';
        $data['reminder_days'] = $setting['reminder_days'] ?? '';
        $data['remainder_everyday_status'] = $setting['remainder_everyday_status'] ?? '';
        return $this->success($data);
    }

    public function getLanguage()
    {
        $data = appLanguages();
        return $this->success($data);
    }

    public function getLanguageJson($code)
    {
        $data = json_decode(file_get_contents(resource_path('lang/' . $code . '.json')));
        return $this->success($data);
    }
}
