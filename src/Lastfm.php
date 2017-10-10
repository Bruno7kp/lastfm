<?php

declare(strict_types=1);

namespace Barryvanveen\Lastfm;

use Barryvanveen\Lastfm\Exceptions\InvalidPeriodException;
use GuzzleHttp\Client;

class Lastfm
{
    /** @var Client */
    protected $httpClient;

    /** @var array */
    protected $query;

    /** @var null|string */
    protected $pluck = null;

    /** @var null|array */
    protected $data = null;

    /**
     * Protected Lastfm constructor. Use any of the named constructors (eg. userInfo, userTopAlbums, etc) to
     * instantiate a Lastfm object.
     *
     * @param Client $client
     * @param string $api_key
     */
    public function __construct(Client $client, string $api_key)
    {
        $this->httpClient = $client;

        $this->query = [
            'format' => 'json',
            'api_key' => $api_key,
        ];
    }

    /**
     * Get an array with user information.
     *
     * @param string $username
     *
     * @return Lastfm
     */
    public function userInfo(string $username): Lastfm
    {
        $this->query = array_merge($this->query, [
            'method' => 'user.getInfo',
            'user' => $username,
        ]);

        $this->pluck = 'user';

        return $this;
    }

    /**
     * Get an array of top albums.
     *
     * @param string $username
     *
     * @return Lastfm
     */
    public function userTopAlbums(string $username): Lastfm
    {
        $this->query = array_merge($this->query, [
            'method' => 'user.getTopAlbums',
            'user' => $username,
        ]);

        $this->pluck = 'topalbums.album';

        return $this;
    }

    /**
     * Get an array of top artists.
     *
     * @param string $username
     *
     * @return Lastfm
     */
    public function userTopArtists(string $username): Lastfm
    {
        $this->query = array_merge($this->query, [
            'method' => 'user.getTopArtists',
            'user' => $username,
        ]);

        $this->pluck = 'topartists.artist';

        return $this;
    }

    /**
     * Get an array of top tracks.
     *
     * @param string $username
     *
     * @return Lastfm
     */
    public function userTopTracks(string $username): Lastfm
    {
        $this->query = array_merge($this->query, [
            'method' => 'user.getTopTracks',
            'user' => $username,
        ]);

        $this->pluck = 'toptracks.track';

        return $this;
    }

    /**
     * Get an array of weekly top albums.
     *
     * @param string    $username
     * @param \DateTime $startdate
     *
     * @return Lastfm
     */
    public function userWeeklyTopAlbums(string $username, \DateTime $startdate): Lastfm
    {
        $this->query = array_merge($this->query, [
            'method' => 'user.getWeeklyAlbumChart',
            'user' => $username,
            'from' => $startdate->format('U'),
            'to' => $startdate->modify('+7 day')->format('U'),
        ]);

        $this->pluck = 'weeklyalbumchart.album';

        return $this;
    }

    /**
     * Get an array of weekly top artists.
     *
     * @param string    $username
     * @param \DateTime $startdate
     *
     * @return Lastfm
     */
    public function userWeeklyTopArtists(string $username, \DateTime $startdate): Lastfm
    {
        $this->query = array_merge($this->query, [
            'method' => 'user.getWeeklyArtistChart',
            'user' => $username,
            'from' => $startdate->format('U'),
            'to' => $startdate->modify('+7 day')->format('U'),
        ]);

        $this->pluck = 'weeklyartistchart.artist';

        return $this;
    }

    /**
     * Get an array of weekly top tracks.
     *
     * @param string    $username
     * @param \DateTime $startdate
     *
     * @return Lastfm
     */
    public function userWeeklyTopTracks(string $username, \DateTime $startdate): Lastfm
    {
        $this->query = array_merge($this->query, [
            'method' => 'user.getWeeklyTrackChart',
            'user' => $username,
            'from' => $startdate->format('U'),
            'to' => $startdate->modify('+7 day')->format('U'),
        ]);

        $this->pluck = 'weeklytrackchart.track';

        return $this;
    }

    /**
     * Get an array of weekly chart list.
     *
     * @param string $username
     *
     * @return Lastfm
     */
    public function userWeeklyChartList(string $username): Lastfm
    {
        $this->query = array_merge($this->query, [
            'method' => 'user.getWeeklyChartList',
            'user' => $username,
        ]);

        $this->pluck = 'weeklychartlist.chart';

        return $this;
    }

    /**
     * Get an array of most recent tracks.
     *
     * @param string $username
     *
     * @return Lastfm
     */
    public function userRecentTracks(string $username): Lastfm
    {
        $this->query = array_merge($this->query, [
            'method' => 'user.getRecentTracks',
            'user' => $username,
        ]);

        $this->pluck = 'recenttracks.track';

        return $this;
    }

    /**
     * Get an array with album information.
     *
     * @param string $artist
     * @param string $album
     * @param null|string $username Optional. If supplied, the user's playcount for this album is included in the response.
     *
     * @return Lastfm
     */
    public function albumInfo(string $artist, string $album, string $username = null): Lastfm
    {
        $this->query = array_merge($this->query, [
            'method' => 'album.getInfo',
            'artist' => $artist,
            'album' => $album,
            'user' => $username,
        ]);

        $this->pluck = 'album';

        return $this;
    }

    /**
     * Get an array with artist information.
     *
     * @param string $artist
     * @param string|null $username Optional. If supplied, the user's playcount for this artist is included in the response.
     *
     * @return Lastfm
     */
    public function artistInfo(string $artist, string $username = null): Lastfm
    {
        $this->query = array_merge($this->query, [
            'method' => 'artist.getInfo',
            'artist' => $artist,
            'user' => $username,
        ]);

        $this->pluck = 'artist';

        return $this;
    }

    /**
     * Get an array with track information.
     *
     * @param string $artist
     * @param string $track
     * @param string|null $username Optional. If supplied, the user's playcount for this track is included in the response.
     *
     * @return Lastfm
     */
    public function trackInfo(string $artist, string $track, string $username = null): Lastfm
    {
        $this->query = array_merge($this->query, [
            'method' => 'track.getInfo',
            'artist' => $artist,
            'track' => $track,
            'user' => $username,
        ]);

        $this->pluck = 'track';

        return $this;
    }

    /**
     * Retrieve the track that is currently playing or "false" if not
     * currently playing any track.
     *
     * @param string $username
     *
     * @return array|bool
     */
    public function nowListening(string $username)
    {
        $this->query = array_merge($this->query, [
            'method' => 'user.getRecentTracks',
            'user' => $username,
        ]);

        $this->pluck = 'recenttracks.track.0';

        $most_recent_track = $this->limit(1)->get();

        if (!isset($most_recent_track['@attr']['nowplaying'])) {
            return false;
        }

        return $most_recent_track;
    }

    /**
     * Set or overwrite the period requested from the Last.fm API.
     *
     * @param string $period
     *
     * @throws InvalidPeriodException
     *
     * @return $this
     */
    public function period(string $period)
    {
        if (!in_array($period, Constants::PERIODS)) {
            throw new InvalidPeriodException('Request period is not valid. Valid values are defined in \Barryvanveen\Lastfm\Constants::PERIODS.');
        }

        $this->query = array_merge($this->query ?? [], ['period' => $period]);

        return $this;
    }

    /**
     * Set or overwrite the number of items that is requested from the Last.fm API.
     *
     * @param int $limit
     *
     * @return $this
     */
    public function limit(int $limit)
    {
        $this->query = array_merge($this->query ?? [], ['limit' => $limit]);

        return $this;
    }

    /**
     * Set or overwrite the page of items that is requested from the Last.fm API.
     *
     * @param int $page
     *
     * @return $this
     */
    public function page(int $page)
    {
        $this->query = array_merge($this->query ?? [], ['page' => $page]);

        return $this;
    }

    /**
     * Transform misspelled artist names into correct artist names, returning the correct version instead. (Optional parameter of the Last.fm API)
     *
     * @param bool $bool
     *
     * @return $this
     */
    public function autocorrect(bool $bool)
    {
        $this->query = array_merge($this->query ?? [], ['autocorrect' => (int) $bool]);

        return $this;
    }

    /**
     * Set or overwrite the language to return the biography (in artists/albums/tracks) in, expressed as an ISO 639 alpha-2 code. (Optional parameter of the Last.fm API)
     *
     * @param string $lang
     *
     * @return $this
     */
    public function lang(string $lang)
    {
        $this->query = array_merge($this->query ?? [], ['lang' => $lang]);

        return $this;
    }

    /**
     * Retrieve results from the Last.fm API.
     *
     * @return array
     */
    public function get(): array
    {
        $dataFetcher = new DataFetcher($this->httpClient);

        $this->data = $dataFetcher->get($this->query, $this->pluck);

        return $this->data;
    }
}
