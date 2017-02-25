<?php

namespace App\Http\Controllers;

use App\Division;
use App\Http\Requests\CreateSquadForm;
use App\Http\Requests\UpdateSquadForm;
use App\Member;
use App\Platoon;
use App\Squad;
use Illuminate\Http\Request;

class SquadController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Division $division
     * @param Platoon $platoon
     * @return \Illuminate\Http\Response
     */
    public function index(Division $division, Platoon $platoon)
    {
        $squads = $platoon->squads()
            ->with(
                'members',
                'members.rank',
                'leader',
                'leader.rank'
            )->get()->sortByDesc('members.rank_id');

        $unassigned = $platoon->unassigned()
            ->with('rank', 'position')
            ->get();

        return view('platoon.squads', compact(
            'platoon',
            'division',
            'squads',
            'unassigned'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Division $division
     * @param Platoon $platoon
     * @return \Illuminate\Http\Response
     */
    public function create(Division $division, Platoon $platoon)
    {
        $this->authorize('create', [Squad::class, $division]);

        return view('squad.create', compact('division', 'platoon'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateSquadForm $form
     * @param Division $division
     * @param Platoon $platoon
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateSquadForm $form, Division $division, Platoon $platoon)
    {
        if ($form->leader_id && ! $this->isMemberOfDivision($division, $form)) {
            return redirect()->back()
                ->withErrors(['leader_id' => 'Member not assigned to this division!'])
                ->withInput();
        }

        $form->persist();

        flash("{$division->locality('squad')} has been created!", 'success');

        return redirect()->route('platoonSquads', [$division->abbreviation, $platoon]);
    }

    /**
     * Display the specified resource.
     *
     * @param Division $division
     * @param Squad $squad
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show(Division $division, Squad $squad)
    {
        return view('squad.show', compact('squad'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Division $division
     * @param Platoon $platoon
     * @param Squad $squad
     * @return \Illuminate\Http\Response
     */
    public function edit(Division $division, Platoon $platoon, Squad $squad)
    {
        $this->authorize('update', $squad);

        return view('squad.update', compact('squad', 'platoon', 'division'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSquadForm $form
     * @param Division $division
     * @param Platoon $platoon
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateSquadForm $form, Division $division, Platoon $platoon, Squad $squad)
    {
        if ($form->leader_id && ! $this->isMemberOfDivision($division, $form)) {
            return redirect()->back()
                ->withErrors(['leader_id' => 'Member not assigned to this division!'])
                ->withInput();
        }

        $form->persist($squad);

        return redirect()->route('platoonSquads', [$division->abbreviation, $platoon]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Squad $squad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Squad $squad)
    {
        $this->authorize('delete', $squad);
    }

    /**
     * @param $request
     * @param Division $division
     * @return bool
     */
    public function isMemberOfDivision(Division $division, $request)
    {
        $member = Member::whereClanId($request->leader_id)->first();

        return $member->primaryDivision instanceof Division &&
            $member->primaryDivision->id === $division->id;
    }
}
