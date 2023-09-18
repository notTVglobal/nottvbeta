<?php

namespace App\Jobs;

use App\Models\ShowEpisode;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AddVideoUrlFromEmbedCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected ShowEpisode $showEpisode;
    public int $timeout = 60;
    public int $tries = 3;
    public int $backoff = 2;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($showEpisode)
    {
        $this->showEpisode = $showEpisode;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if (!$this->showEpisode->video_embed_code) {
            return;
        } else
        $firstMp4 = '';
        $matches = [];
        $response = '';
        $sourceIs = '';

                        // strip the url from the embed code
                        $regex = '/https?\:\/\/[^\",]+/i';
                        preg_match($regex, $this->showEpisode->video_embed_code, $match);
                        $sourceUrl = implode(" ", $match);
//            $scraperApiKey =  env('SCRAPER_API_KEY');
//            Log::channel('custom_error')->warning($scraperApiKey);

                        // get the page source from the url
//            $proxy_address = 'http://scraperapi.autoparse=true:' . env('SCRAPER_API_KEY') . '@proxy-server.scraperapi.com:8001';
////            $proxy_address = 'https://api.scraperapi.com?api_key=' . env('SCRAPER_API_KEY') . '&autoparse=true&url=' . $url;
//            $response = file_get_contents($proxy_address);
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_PROXY, $proxy_address);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//            curl_setopt($ch, CURLOPT_HEADER, FALSE);
//            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//            $response = curl_exec($ch);
//            curl_close($ch);

                        $url =
                            "https://api.scraperapi.com?api_key=" . env('SCRAPER_API_KEY') . "&url=" . $sourceUrl . "&render=true&country_code=us";
//            Log::channel('custom_error')->error($url);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER,
                            TRUE);
                        curl_setopt($ch, CURLOPT_HEADER,
                            FALSE);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,
                            0);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,
                            0);
                        $response = curl_exec($ch);
                        curl_close($ch);

//            Log::channel('custom_error')->error($response);


                        // get the mp4 urls from the page.
                        if (str_contains($sourceUrl, 'rumble.com')) {
                            // if rumble ...
                            $sourceIs = 'rumble';
                            $pattern = '/https(.*?)mp4/';
                            preg_match_all($pattern, $response, $matches);
                            // start at the first "mp4" extract https: .... .mp4 and replace \ with ""
                            $firstMp4 = $matches[0][0];
                        } elseif (str_contains($sourceUrl, 'bitchute.com')) {
                            // if bitchute ...
                            $sourceIs = 'bitchute';
                            $pattern = '/<source src="https(.*?)mp4/';
                            preg_match_all($pattern, $response, $matches);
                            // start at the first "mp4" extract https: .... .mp4 and replace \ with ""
                            $firstMp4 = $matches[0][0];
//                Log::channel('custom_error')->error('FIRST MP4: '.$firstMp4);
                        }


                        // Check if the data is null
                        if ($matches[0] == []) {
                            throw new \Exception("The data is undefined.");
                        }

                        // this poster save needs to be finished...
                        // get the jpg urls from the page.
//            $pattern = '/poster="([^"]+)"/';
//
//            preg_match_all($pattern, $response, $imageMatches);
                        // start at the first "mp4" extract https: .... .mp4 and replace \ with ""
//            $firstJpg = $imageMatches[0];
//
//            $image = str_replace('poster=', '', $firstJpg);
//            $string = implode(', ', $image);
//            $imageFilename = $this->saveImageFromInternet($string);
//            // create image model
//            $id = DB::table('images')->insertGetId([
////                'name' => $uploadedFile->hashName(),
//                'name' => $imageFilename,
////                'extension' => $imageFilename->extension(),
////                'size' => $imageFilename->getSize(),
//                'user_id' => auth()->user()->id,
//                'show_episode_id' => $episodeId,
//            ]);
//
//            // store image_id to Shows table
//            $episode = ShowEpisode::find($episodeId);
//            $episode->image_id = $id;
//            $episode->save();
                        if ($sourceIs === 'rumble') {
                            $url = str_replace('\\', '', $firstMp4);
                        } elseif ($sourceIs === 'bitchute') {
                            $url = str_replace('<source src="', '', $firstMp4);
//                Log::channel('custom_error')->error('Bitchute Matches: '. $url);
                            // send notification, success.
//                return $url;
                        }

                        // write firstMp4 to database.
                        $updateShowEpisode = ShowEpisode::find($this->showEpisode->id);
                        $updateShowEpisode->video_url = $url;
                        $updateShowEpisode->save();
                        Log::channel('custom_error')->error($url);

//            else
                        // send notification, failed.
//            return false;

                }


}
