<?php

namespace App\Services;

use App\Traits\ResponseTrait;

class LocationService
{
    use ResponseTrait;

    public function getCountry()
    {
        $country_file = public_path('file/countries.csv');
        $countries = csvToArray($country_file);
        return $this->success($countries);
    }

    public function getStateByCountryId($country_id)
    {
        $states_file = public_path('file/states.csv');
        $response['stateArr'] = csvToArray($states_file);
        $response['states'] = [];
        foreach ($response['stateArr'] as $stateArr) {
            if ($stateArr['country_id'] == $country_id) {
                $state = array(
                    'id' => $stateArr['id'],
                    'name' => $stateArr['name'],
                    'country_id' => $stateArr['country_id'],
                );
                array_push($response['states'], $state);
            }
        }
        return $this->success($response);
    }

    public function getCitiesByStateId($state_id)
    {
        $cities_file = public_path('file/cities.csv');
        $response['cityArr'] = csvToArray($cities_file);
        $response['cities'] = [];
        foreach ($response['cityArr'] as $cityArr) {
            if ($cityArr['state_id'] == $state_id) {
                $city = array(
                    'id' => $cityArr['id'],
                    'name' => $cityArr['name'],
                    'state_id' => $cityArr['state_id'],
                );
                array_push($response['cities'], $city);
            }
        }
        return $this->success($response);
    }
}
