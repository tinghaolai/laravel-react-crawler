<?php

namespace App\Repositories;

use App\Observers\CrawObserver;
use App\Profiles\CrawlSingleUrlProfile;
use App\Repositories\Interfaces\CrawlRepositoryInterface;
use App\Services\ServiceResult;
use Illuminate\Database\Eloquent\Model;
use JetBrains\PhpStorm\ArrayShape;
use Spatie\Crawler\Crawler;

class CrawlRepository implements CrawlRepositoryInterface
{
    private Model $crawlerModel;

    public function __construct(Model $crawlerModel)
    {
        $this->crawlerModel = $crawlerModel;
    }

    public function search(int $perPage, array $conditions): ServiceResult
    {
        $paginate = $this->crawlerModel::select(
            'id',
            'url',
            'title',
            'description',
            'screen_shot_path',
            'created_at'
        )->when(!empty($conditions['title']), function ($query) use ($conditions) {
            $query->where('title', 'like', '%' . $conditions['title'] . '%');
        })->when(!empty($conditions['description']), function ($query) use ($conditions) {
            $query->where('description', 'like', '%' . $conditions['description'] . '%');
        })->when(
            !empty($conditions['createdAt']) && count($conditions['createdAt'])  === 2,
            function ($query) use ($conditions) {
                $query->where('created_at', '>=', $conditions['createdAt'][0])
                    ->where('created_at', '<=', $conditions['createdAt'][1]);
            }
        )->paginate($perPage);

        $paginate->getCollection()->transform(function (Model $crawler) {
            return $this->apiFormat($crawler);
        });

        return ServiceResult::successResult(['crawlerResults' => $paginate]);
    }

    public function get(int $id): ServiceResult
    {
        if (!($crawler = $this->crawlerModel::find($id))) {
            return ServiceResult::failResult(['message' => 'not found']);
        }

        return ServiceResult::successResult($this->apiFormat($crawler));
    }

    public function craw(string $url): ServiceResult
    {
        $crawlerProfile = new CrawlSingleUrlProfile();
        $crawlerProfile->setUrl($url);

        Crawler::create(['verify' => false])
            ->executeJavaScript()
            ->setCrawlProfile($crawlerProfile)
            ->setCrawlObserver(new CrawObserver())
            ->startCrawling($url);

        $crawler = $this->crawlerModel::where('url', $url)->first();
        if (!$crawler) {
            return ServiceResult::failResult();
        }

        return ServiceResult::successResult(['id' => $crawler->id]);
    }

    #[ArrayShape([
        'id'             => "mixed",
        'url'            => "mixed",
        'title'          => "mixed",
        'description'    => "mixed",
        'screenShotPath' => "mixed",
        'body'           => "mixed",
        'createdAt'      => "mixed"
    ])] private function apiFormat(Model $crawler): array
    {
        return [
            'id'             => $crawler->getAttribute('id'),
            'url'            => $crawler->getAttribute('url'),
            'title'          => $crawler->getAttribute('title'),
            'description'    => $crawler->getAttribute('description'),
            'screenShotPath' => $crawler->getAttribute('screen_shot_path'),
            'body'           => $crawler->getAttribute('body'),
            'createdAt'      => $crawler->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
