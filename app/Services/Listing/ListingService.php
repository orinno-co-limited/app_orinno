<?php

namespace App\Services\listing;

use App\Models\EmailTemplate;
use App\Models\FileManager;
use App\Models\Listing;
use App\Models\ListingContact;
use App\Models\ListingImage;
use App\Models\ListingInformation;
use App\Services\SmsMail\MailService;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ListingService
{
    use ResponseTrait;

    public function getAllByOwner()
    {
        $data = Listing::query()
            ->leftJoin('listing_images', function ($q) {
                $q->on('listing_images.listing_id', '=', 'listings.id')->whereNull('listing_images.deleted_at');
            })
            ->leftJoin('file_managers', function ($q) {
                $q->on('file_managers.origin_id', '=', 'listing_images.id')->where('file_managers.origin_type', '=', 'App\Models\ListingImage');
            })
            ->where('listings.owner_user_id', getOwnerUserId())
            ->select('listings.*', 'file_managers.file_name', 'file_managers.folder_name')
            ->groupBy('listings.id')
            ->get();
        return $data->makeHidden(['created_at', 'updated_at', 'deleted_at']);
    }

    public function getAllActive($request)
    {
        $data = Listing::query()
            ->leftJoin('listing_images', function ($q) {
                $q->on('listing_images.listing_id', '=', 'listings.id')->whereNull('listing_images.deleted_at');
            })
            ->leftJoin('file_managers', function ($q) {
                $q->on('file_managers.origin_id', '=', 'listing_images.id')->where('file_managers.origin_type', '=', 'App\Models\ListingImage');
            })
            ->when($request->q, function ($q) use ($request) {
                $q->where('address', 'LIKE', "%$request->q%")
                    ->orWhere('name', 'LIKE', "%$request->q%")
                    ->orWhere('zip_code', $request->q);
            })
            ->when($request->country, function ($q) use ($request) {
                $q->where('country', $request->country)
                    ->when($request->state, function ($q) use ($request) {
                        $q->where('state', $request->state)
                            ->when($request->city, function ($q) use ($request) {
                                $q->where('city', $request->city);
                            });
                    });
            })
            ->when($request->bed_room, function ($q) use ($request) {
                $q->where('bed_room', 'LIKE', "%$request->bed_room%");
            })
            ->when($request->bath_room, function ($q) use ($request) {
                $q->where('bath_room', 'LIKE', "%$request->bath_room%");
            })
            ->when($request->kitchen, function ($q) use ($request) {
                $q->where('kitchen_room', 'LIKE', "%$request->kitchen%");
            })
            ->where('listings.status', LISTING_STATUS_ACTIVE)
            ->select('listings.*', 'file_managers.file_name', 'file_managers.folder_name')
            ->groupBy('listings.id')
            ->orderByDesc('listings.id')
            ->paginate(12);
        return $data;
    }

    public function getAllContactByOwner()
    {
        $contacts = ListingContact::query()
            ->join('listings', 'listing_contacts.listing_id', '=', 'listings.id')
            ->where('listing_contacts.owner_user_id', getOwnerUserId())
            ->select('listing_contacts.*', 'listings.name as listing_name');

        return datatables($contacts)
            ->addIndexColumn()
            ->addColumn('name', function ($contact) {
                return $contact->name;
            })
            ->addColumn('email', function ($contact) {
                return $contact->email;
            })
            ->addColumn('phone', function ($contact) {
                return $contact->phone;
            })
            ->addColumn('listing_name', function ($contact) {
                return $contact->listing_name;
            })
            ->addColumn('details', function ($contact) {
                return Str::limit($contact->details, 50, '...');
            })
            ->addColumn('reply', function ($contact) {
                return Str::limit($contact->reply, 50, '...');
            })
            ->addColumn('status', function ($contact) {
                if ($contact->status == LISTING_CONTACT_STATUS_PENDING) {
                    return '<div class="status-btn status-btn-green font-13 radius-4">' . __('Pending') . '</div>';
                } elseif ($contact->status == LISTING_CONTACT_STATUS_VIEWED) {
                    return '<div class="status-btn status-btn-green font-13 radius-4">' . __('Viewed') . '</div>';
                } elseif ($contact->status == LISTING_CONTACT_STATUS_MAILED) {
                    return '<div class="status-btn status-btn-orange font-13 radius-4">' . __('Mailed') . '</div>';
                }
            })
            ->addColumn('action', function ($contact) {
                return '<div class="tbl-action-btns d-inline-flex">
                    <button type="button" class="p-1 tbl-action-btn reply" data-id="' . $contact->id . '" title="' . __('reply') . '"><span class="iconify" data-icon="bi:reply"></span></button>
                </div>';
            })
            ->rawColumns(['name', 'status', 'action'])
            ->make(true);
    }

    public function getContactInfo($id)
    {
        $contact = ListingContact::query()
            ->join('listings', 'listing_contacts.listing_id', '=', 'listings.id')
            ->select('listing_contacts.*', 'listings.name as listing_name')
            ->findOrFail($id);
        if ($contact->status == LISTING_CONTACT_STATUS_PENDING) {
            $contact->save();
            $contact->status = LISTING_CONTACT_STATUS_VIEWED;
        }
        return $contact->makeHidden(['created_at', 'updated_at', 'deleted_at']);
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', '');
            if ($id != '') {
                $listing = Listing::where('owner_user_id', getOwnerUserId())->findOrFail($request->id);
            } else {
                $listing = new Listing();
            }
            $listing->owner_user_id = getOwnerUserId();
            $listing->name = $request->name;

             if (Listing::where('slug', getSlug($request->name))->where('id', '!=', $id)->count() > 0) {
                $slug = getSlug($request->name) . '-' . rand(100000, 999999);
            }
            else {
                $slug = getSlug($request->name);
            }

            $listing->slug = $slug;
            $listing->address = $request->address;
            $listing->country = $request->country;
            $listing->state = $request->state;
            $listing->city = $request->city;
            $listing->zip_code = $request->zip_code;
            $listing->latitude = $request->latitude;
            $listing->longitude = $request->longitude;
            $listing->price = $request->price;
            $listing->price_duration_type = $request->price_duration_type;
            $listing->bed_room = $request->bed_room;
            $listing->bath_room = $request->bath_room;
            $listing->kitchen_room = $request->kitchen_room;
            $listing->dining_room = $request->dining_room;
            $listing->living_room = $request->living_room;
            $listing->storage_room = $request->storage_room;
            $listing->other_room = $request->other_room;
            $listing->interior = $request->interior;
            $listing->type = $request->type;
            $listing->details = $request->details;
            $listing->amenities = json_encode($request->amenities);
            $listing->advantage = json_encode($request->advantage ?? []);
            $listing->status = $request->status;
            $listing->save();

            if ($request->hasFile('images')) {
                foreach ($request->images as $key => $image) {
                    $newFile = new FileManager();
                    $upload = $newFile->upload('ListingImage', $image);
                    if ($upload['status']) {
                        $propertyImage = new ListingImage();
                        $propertyImage->owner_user_id = getOwnerUserId();
                        $propertyImage->listing_id = $listing->id;
                        $propertyImage->file_id = $upload['file']->id;
                        $propertyImage->save();

                        $upload['file']->origin_id = $propertyImage->id;
                        $upload['file']->origin_type = "App\Models\ListingImage";
                        $upload['file']->save();
                    } else {
                        throw new Exception($upload['message']);
                    }
                }
            }

            if ($listing) {
                $ids = [];
                foreach ($request->info['name'] as $key => $name) {
                    $listingInformation = ListingInformation::updateOrCreate([
                        'id' => $request->info['id'][$key] ?? null,
                        'owner_user_id' => getOwnerUserId()
                    ], [
                        'owner_user_id' => getOwnerUserId(),
                        'listing_id' => $listing->id,
                        'name' => $name,
                        'distance' => $request->info['distance'][$key],
                        'contact_number' => $request->info['contact_number'][$key],
                        'details' => $request->info['details'][$key],
                    ]);

                    if (isset($request->info['image'][$key])) {
                        $exitFile = FileManager::where('origin_type', 'App\Models\ListingInformation')->where('origin_id', $listingInformation->id)->first();
                        if ($exitFile) {
                            $exitFile->removeFile();
                            $upload = $exitFile->updateUpload($exitFile->id, 'ListingInformation', $request->info['image'][$key], $listingInformation->id);
                        } else {
                            $newFile = new FileManager();
                            $upload = $newFile->upload('ListingInformation', $request->info['image'][$key], $listingInformation->id);
                        }

                        if ($upload['status']) {
                            $upload['file']->origin_id = $listingInformation->id;
                            $upload['file']->origin_type = "App\Models\ListingInformation";
                            $upload['file']->save();
                            $listingInformation->update(['file_id' => $upload['file']->id]);
                        } else {
                            throw new Exception($upload['message']);
                        }
                    }
                    $ids[] = $listingInformation->id;
                }
                ListingInformation::where('listing_id', $listing->id)->whereNotIn('id', $ids)->delete();
            }

            DB::commit();
            $message = $request->id ? __(UPDATED_SUCCESSFULLY) : __(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }

    public function getById($id)
    {
        $data = Listing::where('owner_user_id', getOwnerUserId())->findOrFail($id);
        return $data->makeHidden(['created_at', 'updated_at', 'deleted_at']);
    }

    public function getBySlug($slug)
    {
        $data = Listing::query()
            ->join('users', 'listings.owner_user_id', '=', 'users.id')
            ->leftJoin('file_managers', function ($join) {
                $join->on('file_managers.origin_id', '=', 'users.id')->where('file_managers.origin_type', '=', 'App\Models\User');
            })
            ->where('slug', $slug)
            ->select('listings.*', 'users.first_name', 'users.last_name', 'users.email', 'users.contact_number', 'file_managers.folder_name', 'file_managers.file_name')
            ->firstOrFail();
        return $data->makeHidden(['created_at', 'updated_at', 'deleted_at']);
    }

    public function getByCity($city, $slug = null)
    {
        $data = Listing::query()
            ->leftJoin('listing_images', function ($q) {
                $q->on('listing_images.listing_id', '=', 'listings.id')->whereNull('listing_images.deleted_at');
            })
            ->leftJoin('file_managers', function ($q) {
                $q->on('file_managers.origin_id', '=', 'listing_images.id')->where('file_managers.origin_type', '=', 'App\Models\ListingImage');
            })
            ->when($slug, function ($q) use ($slug) {
                $q->whereNot('listings.slug', $slug);
            })
            ->where('listings.city', $city)
            ->whereNot('listings.status', LISTING_STATUS_CLOSED)
            ->select('listings.*', 'file_managers.file_name', 'file_managers.folder_name')
            ->groupBy('listings.id')
            ->orderByDesc('listings.id')
            ->limit(3)
            ->get();
        return $data->makeHidden(['created_at', 'updated_at', 'deleted_at']);
    }

    public function getImages($id)
    {
        $data = ListingImage::query()
            ->leftJoin('file_managers', function ($join) {
                $join->on('file_managers.origin_id', '=', 'listing_images.id')->where('file_managers.origin_type', '=', 'App\Models\ListingImage');
            })
            ->where('listing_id', $id)
            ->select('listing_images.id', 'file_managers.file_name', 'file_managers.folder_name')
            ->get();

        return $data;
    }

    public function getInfoByListId($id)
    {
        $data = ListingInformation::query()
            ->leftJoin('file_managers', function ($join) {
                $join->on('file_managers.id', '=', 'listing_information.file_id')->where('file_managers.origin_type', '=', 'App\Models\ListingInformation');
            })
            ->select('listing_information.id', 'listing_information.name', 'listing_information.distance', 'listing_information.contact_number', 'listing_information.details', 'file_managers.file_name', 'file_managers.folder_name')
            ->where('listing_id', $id)
            ->groupBy('listing_information.id')
            ->get();
        return $data;
    }

    public function delete($id)
    {
        try {
            $listing = Listing::where('owner_user_id', getOwnerUserId())->findOrFail($id);
            ListingInformation::where('listing_id', $listing->id)->delete();
            $listingImages =  ListingImage::query()->where('listing_id', $listing->id)->get();
            foreach ($listingImages as $listingImage) {
                $file = FileManager::where('origin_type', 'App\Models\ListingImage')->find($listingImage->file_id);
                if ($file) {
                    $file->removeFile();
                    $file->delete();
                }
                $listingImage->delete();
            }
            $listing->delete();
            return $this->success([], __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function imageDelete($id)
    {
        try {
            $listingImage =  ListingImage::query()
                ->where('owner_user_id', getOwnerUserId())
                ->findOrFail($id);
            $file = FileManager::where('origin_type', 'App\Models\ListingImage')->find($listingImage->file_id);
            if ($file) {
                $file->removeFile();
                $file->delete();
            }
            $listingImage->delete();
            return $this->success([], __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function contactStore($request)
    {
        DB::beginTransaction();
        try {
            $listing = Listing::find($request->id);
            if (is_null($listing)) {
                throw new Exception(__('No Property Found'));
            }
            $contact = new ListingContact();
            $contact->owner_user_id = $listing->owner_user_id;
            $contact->listing_id = $listing->id;
            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->phone = $request->phone;
            $contact->profession = $request->profession;
            $contact->details = $request->details;
            $contact->save();
            DB::commit();
            if (getOption('send_email_status', 0) == ACTIVE) {
                $emails = [$request->email];
                $ownerUserId = $listing->owner_user_id;
                $mailService = new MailService;
                $template = EmailTemplate::where('owner_user_id', $ownerUserId)->where('category', EMAIL_TEMPLATE_LISTING_CONTACT)->where('status', ACTIVE)->first();
                if ($template) {
                    $customizedFieldsArray = [
                        '{{user_name}}' => $contact->name,
                        '{{app_name}}' => getOption('app_name')
                    ];
                    $content = getEmailTemplate($template->body, $customizedFieldsArray);
                    $mailService->sendCustomizeMail($emails, $template->subject, $content);
                } else {
                    throw new Exception(__('Please Add Contact Mail Template'));
                }
            }
            return $this->success([], __(SENT_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

    public function contactReply($request)
    {
        DB::beginTransaction();
        try {
            $contact = ListingContact::where('owner_user_id', getOwnerUserId())->find($request->id);
            if (is_null($contact)) {
                throw new Exception(__('No Contact Found'));
            }
            $contact->status = LISTING_CONTACT_STATUS_MAILED;
            $contact->reply = $request->reply;
            $contact->save();

            if (getOption('send_email_status', 0) == ACTIVE) {
                $emails = [$contact->email];
                $ownerUserId = getOwnerUserId();

                $mailService = new MailService;
                $template = EmailTemplate::where('owner_user_id', $ownerUserId)->where('category', EMAIL_TEMPLATE_LISTING_REPLY)->where('status', ACTIVE)->first();
                if ($template) {
                    $customizedFieldsArray = [
                        '{{user_name}}' => $contact->name,
                        '{{app_name}}' => getOption('app_name')
                    ];
                    $content = getEmailTemplate($template->body, $customizedFieldsArray);
                    $mailService->sendCustomizeMail($emails, $template->subject, $content);
                } else {
                    throw new Exception(__('Please Add Reply Mail Template'));
                }
            }
            DB::commit();
            return $this->success([], __(REPLIED_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

    public function getCountries()
    {
        $counties = Listing::query()
            ->whereNotNull('country')
            ->groupBy('country')
            ->distinct('country')
            ->get()
            ->pluck('country');
        return $counties;
    }

    public function getStatesByCountry($country)
    {
        $states = Listing::query()
            ->where('country', $country)
            ->whereNotNull('state')
            ->groupBy('state')
            ->distinct('state')
            ->get()
            ->pluck('state');
        return $states;
    }

    public function getCitiesByState($state)
    {
        $cities = Listing::query()
            ->where('state', $state)
            ->whereNotNull('city')
            ->groupBy('city')
            ->distinct('city')
            ->get()
            ->pluck('city');
        return $cities;
    }

    public function getBedRooms()
    {
        $rooms = Listing::query()
            ->whereNotNull('bed_room')
            ->groupBy('bed_room')
            ->distinct('bed_room')
            ->get()
            ->pluck('bed_room');
        return $rooms;
    }

    public function getBathRooms()
    {
        $rooms = Listing::query()
            ->whereNotNull('bath_room')
            ->groupBy('bath_room')
            ->distinct('bath_room')
            ->get()
            ->pluck('bath_room');
        return $rooms;
    }

    public function getKitchenRooms()
    {
        $rooms = Listing::query()
            ->whereNotNull('kitchen_room')
            ->groupBy('kitchen_room')
            ->distinct('kitchen_room')
            ->get()
            ->pluck('kitchen_room');
        return $rooms;
    }
}
