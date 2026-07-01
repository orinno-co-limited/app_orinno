<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Saas\FrontendController;
use App\Models\Blog;
use App\Models\CorePage;
use App\Services\FaqService;
use App\Services\TestimonialService;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class CommonController extends Controller
{
    public function index()
    {
        if (isAddonInstalled('PROTYSAAS') > 1) {
            $frontendController = new FrontendController;
            return $frontendController->index();
        }
        return redirect()->route('login');
    }

    public function generateInvoice()
    {
        try {
            Artisan::call('generate:invoice');
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function aboutUs(){

        $testimonialService = new TestimonialService;
        $data['testimonials'] = $testimonialService->getActiveAll();
        $faqService = new FaqService;
        $data['faqs'] = $faqService->getActiveAll();
        $data['advanceFeature'] = CorePage::where('status',ACTIVE)->orderBy('id','DESC')->take(2)->get();

        return view('saas.frontend.about-us',$data);
    }

    public function contactUs(){

        return view('saas.frontend.contact-us');
    }
    public function feature(){

        $faqService = new FaqService;
        $data['faqs'] = $faqService->getActiveAll();
        $data['advanceFeature'] = CorePage::where('status',ACTIVE)->orderBy('id','DESC')->get();

        return view('saas.frontend.feature',$data);
    }

    public function blogList(){

        $faqService = new FaqService;
        $data['faqs'] = $faqService->getActiveAll();
        $data['blogData'] = Blog::with('blogCategory')
            ->paginate(6);

        return view('saas.frontend.blog-list',$data);
    }
    public function blogDetails($blog_slug){

        $data['blogDetails'] = Blog::with('blogCategory')->where('slug',$blog_slug)->first();
        $data['relatedBlog'] = Blog::with('blogCategory')
            ->where('blog_category_id',$data['blogDetails']->blog_category_id)
            ->where('slug','!=',$data['blogDetails']->slug)
            ->take(3)->get();

        return view('saas.frontend.blog-details',$data);
    }
}
