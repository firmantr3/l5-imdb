<?php

namespace firmantr3\L5Imdb;

use Imdb\Title;
use Imdb\Config;
use Imdb\Person;
use Imdb\TitleSearch;
use Imdb\PersonSearch;
use Illuminate\Log\Logger;
use Illuminate\Cache\Repository;

class L5Imdb
{
    /**
     * imdb config
     *
     * @var Config
     */
    protected $config;

    /**
     * Imdb result instance
     *
     * @var Title
     */
    protected $result;

    /**
     * App logger
     *
     * @var Logger
     */
    protected $logger;

    /**
     * App cache
     *
     * @var Repository
     */
    protected $cache;

    /** @var bool */
    protected $getDetail;

    /** @var bool */
    protected $getDetailUnstable;

    /** @var bool */
    protected $getDetailSites;

    /**
     * Instantiate class
     */
    public function __construct()
    {
        $this->config = $this->initializeConfig();
        $this->logger = app(Logger::class);
        $this->cache = app(Repository::class);
        $this->getDetail = config('imdb.auto_get.detail');
        $this->getDetailUnstable = config('imdb.auto_get.unstable');
        $this->getDetailSites = config('imdb.auto_get.sites');
    }

    /**
     * Initialize library's config
     *
     * @return void
     */
    protected function initializeConfig() {
        $config = new Config();
        $appConfig = config('imdb.library');

        foreach ($appConfig as $key => $value) {
            $config->{$key} = $value;
        }

        return $config;
    }

    /**
     * Initialize title
     *
     * @param int|string $title
     * @return self
     */
    public function title($title) {
        $this->result = new Title($title, $this->config, $this->logger, $this->cache);

        return $this;
    }

    /**
     * Initialize person
     *
     * @param int|string $person
     * @return self
     */
    public function person($person)
    {
        $this->result = new Person($person, $this->config, $this->logger, $this->cache);

        return $this;
    }

    /**
     * Initialize search IMDB
     *
     * @param string $terms
     * @param array|string|null $types
     * @param int|null $maxResult
     * @return self
     */
    public function searchTitle($terms, $types = 'all', $maxResult = null) {
        $engine = new TitleSearch($this->config, $this->logger, $this->cache);

        $this->result = $engine->search($terms, $this->mapSearchTypes($types, $this->availableTitleSearchTypes()), $maxResult);

        return $this;
    }

    /**
     * get available title search types
     *
     * @return array
     */
    protected function availableTitleSearchTypes() {
        return [
            Title::MOVIE,
            Title::TV_SERIES,
            Title::TV_EPISODE,
            Title::TV_MINI_SERIES,
            Title::TV_MOVIE,
            Title::TV_SPECIAL,
            Title::TV_SHORT,
            Title::GAME,
            Title::VIDEO,
            Title::SHORT,
        ];
    }

    /**
     * Initialize search person IMDB
     *
     * @param string $terms
     * @param array|string|null $types
     * @param int|null $maxResult
     * @return self
     */
    public function searchPerson($terms, $types = 'all', $maxResult = null)
    {
        $engine = new PersonSearch($this->config, $this->logger, $this->cache);

        $this->result = $engine->search($terms, $this->mapSearchTypes($types, $this->availablePersonSearchTypes()), $maxResult);

        return $this;
    }

    /**
     * get available person search types
     *
     * @return array
     */
    protected function availablePersonSearchTypes()
    {
        return array_merge($this->availableTitleSearchTypes(), [
            'Documentary',
            'TV Movie documentary',
            'TV Series documentary',
            'Video documentary short',
            'Video documentary',
        ]);
    }

    /**
     * map search types query
     *
     * @param string|array|null $types
     * @return array|null
     */
    protected function mapSearchTypes($types, $availableTypes)
    {
        $availableTypes = collect($availableTypes);

        if (is_array($types)) {
            return collect($types)->filter(function ($type) use ($availableTypes) {
                return $availableTypes->contains($type);
            })->toArray();
        }

        if ($types === 'all') {
            return $availableTypes->toArray();
        }

        return null;
    }

    /**
     * Map title to array
     *
     * @param Title $title
     * @return array
     */
    protected function mapTitle($title) {
        $data = [
            'title' => $title->title(),
            'orig_title' => $title->orig_title(),
            'main_url' => $title->main_url(),
            'year' => $title->year(),
            'movieTypes' => $title->movieTypes(),
            'runtime' => $title->runtime(),
            'aspect_ratio' => $title->aspect_ratio(),
            'rating' => $title->rating(),
            'votes' => $title->votes(),
            'metacriticRating' => $title->metacriticRating(),
            'comment' => $title->comment(),
            'comment_split' => $title->comment_split(),
            'keywords' => $title->keywords(),
            'language' => $title->language(),
            'languages' => $title->languages(),
            'languages_detailed' => $title->languages_detailed(),
            'genre' => $title->genre(),
            'genres' => $title->genres(),
            'colors' => $title->colors(),
            'creator' => $title->creator(),
            'tagline' => $title->tagline(),
            'seasons' => $title->seasons(),
            'is_serial' => $title->is_serial(),
            'episodeTitle' => $title->episodeTitle(),
            'episodeSeason' => $title->episodeSeason(),
            'episodeEpisode' => $title->episodeEpisode(),
            'episodeAirDate' => $title->episodeAirDate(),
            'get_episode_details' => $title->get_episode_details(),
            'plotoutline' => $title->plotoutline(),
            'storyline' => $title->storyline(),
            'photo' => $title->photo(false),
            'photoThumb' => $title->photo(),
            'mainPictures' => $title->mainPictures(),
            'country' => $title->country(),
            'sound' => $title->sound(),
            'prodNotes' => $title->prodNotes(),
            'top250' => $title->top250(),
            'cast' => $title->cast(true),
            'budget' => $title->budget(),
        ];

        if($this->getDetail) {
            $data = array_merge($data, $this->mapTitleDetail($title));
        }

        if ($this->getDetail && $this->getDetailUnstable) {
            $data = array_merge($data, $this->mapTitleDetailUnstable($title));
        }

        if($this->getDetail && $this->getDetailSites) {
            $data = array_merge($data, $this->mapTitleDetailSites($title));
        }

        return $data;
    }

    /**
     * map detailed data from title
     *
     * @param Title $title
     * @return array
     */
    protected function mapTitleDetail($title) {
        return [
            'runtimes' => $title->runtimes(),
            'mpaa' => $title->mpaa(),
            'mpaa_hist' => $title->mpaa_hist(),
            'mpaa_reason' => $title->mpaa_reason(),
            'synopsis' => $title->synopsis(),
            'taglines' => $title->taglines(),
            'director' => $title->director(),
            'castDetail' => $title->cast(),
            'writing' => $title->writing(),
            'producer' => $title->producer(),
            'composer' => $title->composer(),
            'crazy_credits' => $title->crazy_credits(),
            'episodes' => $title->episodes(),
            'goofs' => $title->goofs(),
            'quotes' => $title->quotes(),
            'quotes_split' => $title->quotes_split(),
            'trailers' => $title->trailers(),
            'trivia' => $title->trivia(),
            'triviaSpoil' => $title->trivia(true),
            'soundtrack' => $title->soundtrack(),
            'movieconnection' => $title->movieconnection(),
            'extReviews' => $title->extReviews(),
            'prodCompany' => $title->prodCompany(),
            'distCompany' => $title->distCompany(),
            'specialCompany' => $title->specialCompany(),
            'otherCompany' => $title->otherCompany(),
            'parentalGuide' => $title->parentalGuide(),
            'parentalGuideSpoil' => $title->parentalGuide(true),
            'keywords_all' => $title->keywords_all(),
            'awards' => $title->awards(),
            'filmingDates' => $title->filmingDates(),
            'alternateVersions' => $title->alternateVersions(),
            'officialSites' => $title->officialSites(),
        ];
    }

    /**
     * map detailed unstable API data from title
     *
     * @param Title $title
     * @return array
     */
    protected function mapTitleDetailUnstable($title)
    {
        return [
            'releaseInfo' => $title->releaseInfo(),
            'locations' => $title->locations(),
            'plot' => $title->plot(),
            'plot_split' => $title->plot_split(),
            'alsoknow' => $title->alsoknow(),
            'movie_recommendations' => $title->movie_recommendations(),
        ];
    }

    /**
     * map detailed sites API data from title
     *
     * @param Title $title
     * @return array
     */
    protected function mapTitleDetailSites($title)
    {
        return [
            'soundclipsites' => $title->soundclipsites(),
            'photosites' => $title->photosites(),
            'miscsites' => $title->miscsites(),
            'videosites' => $title->videosites(),
        ];
    }

    protected function mapPerson($person) {
        $data = [
            'imdbid' => $person->imdbid(),
            'main_url' => $person->main_url(),
            'name' => $person->name(),
            'photo' => $person->photo(false),
            'photoThumb' => $person->photo(),
            'movies_all' => $person->movies_all(),
            'movies_actor' => $person->movies_actor(),
            'movies_actress' => $person->movies_actress(),
            'movies_producer' => $person->movies_producer(),
            'movies_director' => $person->movies_director(),
            'movies_soundtrack' => $person->movies_soundtrack(),
            'movies_crew' => $person->movies_crew(),
            'movies_thanx' => $person->movies_thanx(),
            'movies_self' => $person->movies_self(),
            'movies_writer' => $person->movies_writer(),
            'movies_archive' => $person->movies_archive(),
        ];

        if($this->getDetail) {
            $data = array_merge($data, $this->mapPersonDetail($person));
        }

        return $data;
    }

    protected function mapPersonDetail($person) {
        return [
            'birthname' => $person->birthname(),
            'nickname' => $person->nickname(),
            'born' => $person->born(),
            'died' => $person->died(),
            'height' => $person->height(),
            'spouse' => $person->spouse(),
            'bio' => $person->bio(),
            'trivia' => $person->trivia(),
            'quotes' => $person->quotes(),
            'trademark' => $person->trademark(),
            'salary' => $person->salary(),
            'pubprints' => $person->pubprints(),
            'pubmovies' => $person->pubmovies(),
            'pubpotraits' => $person->pubpotraits(),
            'interviews' => $person->interviews(),
            'articles' => $person->articles(),
            'pictorials' => $person->pictorials(),
            'magcovers' => $person->magcovers(),
        ];
    }

    /**
     * get result instance
     *
     * @return Title|array
     */
    public function result()
    {
        return $this->result;
    }

    /**
     * Toggles $this->getDetail value
     *
     * @return self
     */
    public function detail() {
        $this->getDetail = !$this->getDetail;

        return $this;
    }

    /**
     * Toggles $this->getDetailUnstable value
     *
     * @return self
     */
    public function detailUnstable()
    {
        $this->getDetailUnstable = !$this->getDetailUnstable;

        return $this;
    }

    /**
     * Toggles $this->getDetailSites value
     *
     * @return self
     */
    public function detailSites()
    {
        $this->getDetailSites = !$this->getDetailSites;

        return $this;
    }

    /**
     * get mapped result
     *
     * @return array
     */
    public function all() {
        if($this->result() instanceof Title) {
            return $this->mapTitle($this->result());
        }

        if ($this->result() instanceof Person) {
            return $this->mapPerson($this->result());
        }

        if(is_array($this->result())) {
            if($this->result()[0] instanceof Title) {
                return array_map([$this, 'mapTitle'], $this->result);
            }

            if ($this->result()[0] instanceof Person) {
                return array_map([$this, 'mapPerson'], $this->result);
            }
        }

        return null;
    }

}
