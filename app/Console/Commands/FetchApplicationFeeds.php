<?php

namespace App\Console\Commands;

use App\AOD\RssFeedService;
use App\Models\Division;
use App\Notifications\NewDivisionApplication;
use Illuminate\Console\Command;

class FetchApplicationFeeds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'do:fetch-application-feeds';

    protected $description = 'Fetch recruitment RSS feeds and notify about new applications';

    private RssFeedService $rssFeedService;

    public function __construct(RssFeedService $rssFeedService)
    {
        parent::__construct();
        $this->rssFeedService = $rssFeedService;
    }

    public function handle()
    {
        $excludedDivisions = [
            'Bluntz\' Reserves',
            'Floater'
        ];

        $divisions = Division::active()->whereNotIn('name', $excludedDivisions)->get();

        foreach ($divisions as $division) {
            try {
                $feedUrl = $division->settings()->get('recruitment_rss_feed');

                if (! $feedUrl) {
                    continue;
                }

                $rssContent = $this->rssFeedService->fetchRssContent($feedUrl);
                if (! $rssContent) {
                    \Log::error("Failed to fetch RSS content for division: {$division->name}");

                    continue;
                }

                $cacheKey = "rss_feed_{$division->id}";
                $newItems = $this->rssFeedService->detectNewItems($cacheKey, $rssContent);

                foreach ($newItems as $item) {
                    $division->notify(new NewDivisionApplication($item->title, $item->link));
                }
            } catch (\Exception $exception) {
                // silently fail because we probably don't have a setting
            }

            return self::SUCCESS;
        }
    }
}
