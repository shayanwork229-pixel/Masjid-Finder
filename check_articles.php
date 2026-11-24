<?php
// Quick diagnostic script - run via: php check_articles.php from project root
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\IslamicArticle;

$total     = IslamicArticle::count();
$published = IslamicArticle::where('is_published', true)->count();
$featured  = IslamicArticle::where('is_featured', true)->count();

echo "Total articles: $total\n";
echo "Published:      $published\n";
echo "Featured:       $featured\n\n";

foreach (IslamicArticle::latest()->get(['id','title','slug','is_published','published_at']) as $a) {
    $pub = $a->is_published ? 'YES' : 'NO';
    echo "{$a->id}: {$a->title}\n";
    echo "   slug: {$a->slug}\n";
    echo "   published: $pub | published_at: {$a->published_at}\n\n";
}
