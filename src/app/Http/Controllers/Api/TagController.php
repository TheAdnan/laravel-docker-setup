<?php

namespace App\Http\Controllers\Api;

use App\Helpers\UrlHelper;
use App\Models\Tag;
use App\Models\Traits\UrlParser;
use App\Models\Url;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Weidner\Goutte\GoutteFacade as Goutte;

class TagController extends Controller
{
    // Assuming the task meant adding tags to an existing link

    public function add (Request $request) {

        $validator = Validator::make($request->all(), [
            'url' => 'required|max:255',
            'tags' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        /** @var User $user */
        /** @var Url $link */

        $user = $request->user();

        $link = Url::where('link', UrlParser::parse($request->get('url')))->where('user_id', $user->id)->first();

        if (is_null($link)) {
            return response()->json(['error' => 'This link does not exist'], 400);
        }

        $tags = explode(',', $request->get('tags'));

        try {
            DB::transaction(function () use ($user, $tags, $link) {
                foreach ($tags as $tag) {
                    $tag = strtolower($tag);
                    DB::insert('insert into tags (title, user_id, url_id, suggested) values (?, ?, ?, false)', [$tag, $user->id, $link->id]);
                }
            });
        }
        catch (QueryException $e) {
            return response()->json(['error' => 'Could not save tags for link'], 400);
        }

        return response()->json(['message' => 'Tags were added successfully'], 200);
    }

    public function getSuggestedTagsByAnalysis (Request $request) {

        $validator = Validator::make($request->all(), [
            'url' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        /** @var User $user */

        $user = $request->user();

        $linkExists = UrlHelper::findAndCompare($request->get('url'), $user->id);

        if ($linkExists > 0) {

            $content = Goutte::request('GET', $request->get('url'))->html();

            return new JsonResource($this->filterKeywords($content));
        }

        return response()->json(['error' => 'Link does not exists'], 400);

    }

    public function getSuggestedTags (Request $request) {

        $validator = Validator::make($request->all(), [
            'url' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $link = $request->get('url');
        $linkExists = UrlHelper::findAndCompare($link);

        /** @var User $user */
        $user = $request->user();

        $url = Url::where('link', UrlParser::parse($link))->first();

        // Return only tags from other users

        if ($linkExists > 0) {
            $tags = Tag::where('url_id', $url->id)
                ->where('user_id', '!=', $user->id)
                ->get()
                ->toArray();

            return new JsonResource($tags);
        }

        return response()->json(['error' => 'Link does not exists in database'], 400);

    }

    private function filterKeywords($content): array
    {
        $text = strip_tags($content);

        $text = trim(preg_replace('/[^A-Za-z0-9\-]/i', ' ', $text));
        $text = trim(preg_replace('/\s+/', ' ', $text));
        $keywords = explode(" ", $text);

        $numberOfRepetitions = array_count_values($keywords);
        array_multisort($numberOfRepetitions, SORT_DESC, $numberOfRepetitions);

        if (count($numberOfRepetitions) > 10) {
            return array_slice($numberOfRepetitions, 0, 10);
        }

        return $numberOfRepetitions;
    }
}
