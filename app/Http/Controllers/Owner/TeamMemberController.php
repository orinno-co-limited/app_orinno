<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Services\TeamMemberService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeamMemberController extends Controller
{
    use ResponseTrait;

    public $teamMemberServices;

    public function __construct()
    {
        $this->teamMemberServices = new TeamMemberService();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->teamMemberServices->getInfo();
        } else {
            $data['pageTitle'] = __('Staff User');
            $data['roles'] = $this->teamMemberServices->getRoles();
            return view('owner.team-member.index', $data);
        }
    }

    public function store(Request $request){

        $request->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => [
                'required',
                Rule::unique('users')->ignore($request->id),
            ],
            'contact_number' => [
                'required',
                Rule::unique('users')->ignore($request->id),
            ],
        ]);
        return $this->teamMemberServices->store($request);
    }

    public function edit($id){

        $data['activeTeamMember'] = 'active';
        $data['teamMember'] = $this->teamMemberServices->getEditInfo($id);
        $data['roles'] = $this->teamMemberServices->getRoles();
        return view('owner.team-member.edit', $data);
    }

    public function delete($id){

        return $this->teamMemberServices->delete($id);
    }

}
