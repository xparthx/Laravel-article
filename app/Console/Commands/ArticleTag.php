<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Article;
use App\Tag;
use Illuminate\Support\Facades\DB;

class ArticleTag extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:articletag';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To assign tags to the articles';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $tag_ids = Tag::pluck('id');
        if (!$tag_ids) {
            $this->error('No tag exist in database');
            exit;
        }

        $article_ids = Article::pluck('id')->toArray();

        if (!$article_ids) {
            $this->error('No article exist in database');
            exit;
        }

        $bar = $this->output->createProgressBar(count($article_ids));

        DB::table('article_tag')->truncate();
        foreach ($article_ids as $aid) {
            $article = Article::find($aid);
            $article->tags()->attach($tag_ids->random());
            $article->save();
            $bar->advance();
        }
        $bar->finish();
    }

}
