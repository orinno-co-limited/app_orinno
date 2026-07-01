<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Models\PropertyImage;
use App\Services\PropertyService;
use App\Traits\ResponseTrait;
use Exception;

class PropertyController extends Controller
{
    use ResponseTrait;
    public $propertyService;

    public function __construct()
    {
        $this->propertyService = new PropertyService;
    }

    public function allProperty()
    {
        $data['properties'] = $this->propertyService->getAll();
        return $this->success($data);
    }

    public function details($id)
    {
        try {
            $data['property'] = $this->propertyService->getDetailsById($id);

            $propertyImages = PropertyImage::query()
                ->where('property_id', $data['property']->id)
                ->join('file_managers', 'property_images.file_id', '=', 'file_managers.id')
                ->get();
            $data['units'] = $this->propertyService->getUnitsByPropertyId($id)->getData()->data;

            $images = array();
            foreach ($propertyImages as $image) {
                $images[] = getFileUrl($image->folder_name, $image->file_name);
            }
            $data['images'] = $images;
            return $this->success($data);
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function allUnit()
    {
        $data['units'] = $this->propertyService->allUnit();
        return $this->success($data);
    }
}
