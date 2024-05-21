<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MovieTrailer;
use App\Models\Movie;
use App\Models\Video;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MovieTrailer>
 */
class MovieTrailerFactory extends Factory
{
    protected $model = MovieTrailer::class;

    public function definition()
    {
        $video = Video::inRandomOrder()->first();
        $movie = Movie::inRandomOrder()->first();

        return [
            'movie_id' => $movie ? $movie->id : null,  // Handle the case where there is no movie
            'extension' => $this->faker->randomElement(['mp4', 'mov', 'avi']),  // Example extensions
            'size' => $this->faker->numberBetween(1000, 10000),  // Example size in KB
            'file_path' => $this->faker->filePath(),  // Generates a random file path
            'file_url' => $this->faker->url,  // Generates a random URL
            'video_id' => $video ? $video->id : null,  // Handle the case where there is no video
        ];
    }
}
