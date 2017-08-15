<?php

namespace Tests;

use App\Services\FavoriteService;
use App\Topic;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations, CreatesUser;

    public function testAddAndRemoveFavorite()
    {
        $userEditor = $this->createEditor();
        $topic = factory(Topic::class)->create([
            'user_id' => $userEditor->id,
        ]);

        $favoriteService = new FavoriteService();
        $favoriteService->save('topics', $topic->id, $userEditor->id);
        $favorites = $favoriteService->getUserFavorites($userEditor->id);

        $this->assertTrue($favorites instanceof Collection);
        $this->assertArraySubset(
            $topic->toArray(),
            $favorites->get(0)->toArray()['favoritable']
        );

        $favoriteService->delete('topics', $topic->id, $userEditor->id);
        $favorites = $favoriteService->getUserFavorites($userEditor->id);

        $this->assertTrue($favorites instanceof Collection);
        $this->assertTrue($favorites->count() === 0);
    }
}
