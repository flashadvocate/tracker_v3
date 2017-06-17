<?php

namespace App\Http\Controllers;

use App\Division;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Twig_Error;
use Twig_Error_Syntax;
use Twig_Loader_String;
use Twig_Sandbox_SecurityError;
use TwigBridge\Facade\Twig;

/**
 * Class DivisionStructureController
 *
 * @package App\Http\Controllers
 */
class DivisionStructureController extends Controller
{

    use AuthorizesRequests;

    /**
     * DivisionStructureController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'activeDivision']);
    }

    /**
     * @param Division $division
     * @return string
     */
    public function show(Division $division)
    {
        Twig::setLoader(new Twig_Loader_String());

        try {
            $compiledData = $this->compileDivisionData($division);

            $data = Twig::render($division->structure, ['division' => $compiledData]);
        } catch (Twig_Error $error) {
            $data = $this->handleTwigError($error);
        }

        return view('division.structure', compact('data', 'division'));
    }

    /**
     * @param Division $division
     * @return \stdClass
     */
    private function compileDivisionData(Division $division)
    {
        $data = new \stdClass();
        $data->structure = $division->structure;
        $data->name = $division->name;
        $data->leaders = $division->leaders()->with('position', 'rank');
        $data->generalSergeants = $division->generalSergeants();
        $data->platoons = $division->platoons()->with([
            'squads.members.handles' => function ($query) use ($division) {
                $query->where('id', $division->handle_id);
            },
            'squads.members.rank',
            'squads.leader.rank',
            'leader.rank'
        ], [
            'leader.handles' => function ($query) use ($division) {
                $query->where('id', $division->handle_id);
            }
        ])->get();

        return $data;
    }

    /**
     * @param $error
     * @return string
     */
    private function handleTwigError($error)
    {
        if ($error instanceof Twig_Error_Syntax) {
            return $error->getMessage();
        }

        if ($error instanceof Twig_Sandbox_SecurityError) {
            return "You attempted to use an unauthorized tag, filter, or method";
        }

        return $error->getMessage();
    }

    /**
     * @param Division $division
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function modify(Division $division)
    {
        $this->authorize('manageDivisionStructure', auth()->user());

        if ( ! auth()->user()->isRole(['sr_ldr', 'admin'])) {
            abort(403);
        }

        return view('division.structure-editor', compact('division'));
    }

    /**
     * @param Request $request
     * @param Division $division
     */
    public function update(Request $request, Division $division)
    {
        $this->authorize('manageDivisionStructure', auth()->user());

        $division->structure = $request->structure;
        $division->save();

        return redirect(route('division.structure', $division->abbreviation));
    }

    /**
     * @param Division $division
     * @return string
     */
    public function twigfiddleJson(Division $division)
    {
        return json_encode([
            "division" => [
                "name" => $division->name,
                "leaders" => $division->leaders(),
                "generalSergeants" => $division->generalSergeants(),
                "platoons" => $division->platoons()->with(
                    [
                        'squads.members.handles' => function ($query) use ($division) {
                            // filtering handles for just the relevant one
                            $query->where('id', $division->handle_id);
                        }
                    ],
                    'leaders.position', 'squads', 'squads.members', 'squads.members.rank'
                )->get()
            ]
        ]);
    }
}
