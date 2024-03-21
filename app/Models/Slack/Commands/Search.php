<?php

namespace App\Models\Slack\Commands;

use App\Models\Member;
use App\Models\Slack\Base;
use App\Models\Slack\Command;

/**
 * Class Search.
 */
class Search extends Base implements Command
{
    private $members;

    private $content = [];

    private $forumProfile = 'https://www.clanaod.net/forums/member.php?u=';

    public function __construct($data)
    {
        parent::__construct($data);

        $this->request = $data;
    }

    /**
     * @return array
     */
    public function handle()
    {
        if (\strlen($this->params) < 3) {
            return [
                'text' => 'Your search criteria must be 3 characters or more',
            ];
        }

        // are we handling multiple names?
        if (strstr($this->params, ',')) {
            $criteria = [];
            $terms = explode(',', $this->params);

            if (\count($terms) > 2) {
                return [
                    'text' => 'Your search criteria can only be a maximum of two terms.',
                ];
            }

            $this->members = Member::where('name', 'LIKE', "%{$terms[0]}%")
                ->orWhere('name', 'LIKE', "%{$terms[1]}%")
                ->get();
        } else {
            $this->members = Member::where('name', 'LIKE', "%{$this->params}%")->get();
        }

        // count before iterating
        if ($this->members->count() > 10) {
            return [
                'text' => 'More than 10 members were found. Please narrow your search terms.',
            ];
        }

        if ($this->members) {
            foreach ($this->members as $member) {
                $division = ($member->division)
                    ? "{$member->division->name} Division"
                    : 'Ex-AOD';

                $memberLink = route('member', $member->getUrlParams());

                $links = [
                    "[Forum]({$this->forumProfile}{$member->clan_id})",
                    "[Tracker]({$memberLink})",
                ];

                $this->content[] = [
                    'name' => "{$member->present()->rankName} ({$member->clan_id}) - {$division}",
                    'value' => 'Profiles: '
                        . implode(', ', $links)
                        . $this->buildActivityBlock($member),
                ];
            }
        }

        if ($this->members->count() >= 1) {
            return [
                'embed' => [
                    'color' => 10181046,
                    'title' => 'The following members were found:',
                    'fields' => $this->content,
                ],
            ];
        }

        return [
            'text' => 'No results were found',
        ];
    }

    /**
     * @return null|string
     */
    private function buildActivityBlock($member)
    {
        $forumActivity = $member->last_activity->diffForHumans();

        return PHP_EOL . "Forum activity: {$forumActivity}";
    }
}
