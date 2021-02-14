<?php

namespace App\Http\Controllers\Api;

use App\Helpers\UrlHelper;
use App\Models\Tag;
use App\Models\Traits\UrlParser;
use App\Models\Url;
use Illuminate\Database\QueryException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UrlController extends Controller
{
    public function add(Request $request) {

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

        $link = new Url([
            'link' => $request->get('url'),
            'user_id' => $user->id
        ]);

        $linkExists = UrlHelper::findAndCompare($request->get('url'), $user->id);

        if (!$link->save()) {
            return response()->json(['error' => 'Could not save link'], 400);
        }

        if ($linkExists > 0) {
            return response()->json(['error' => 'Link already exists'], 400);
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


        return response()->json(['message' => 'Link was successfully saved'], 200);
    }

    public function view(Request $request): JsonResource {

        /** @var User $user */
        /** @var Url $links */

        $user = $request->user();

        $links = Url::where('user_id', $user->id)->get();

        return new JsonResource($links);
    }

    public function search(Request $request): JsonResource {

        $validator = Validator::make($request->all(), [
            'tags' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        /** @var User $user */
        /** @var Url $links */

        $user = $request->user();
        $searchTerms = explode(',', $request->get('tags'));

        $tags = $user->tags()->whereIn('title', $searchTerms)->with('url')->get()->toArray();

        $links = [];

        foreach ($tags as $tag) {
            array_push($links, $tag['url']);
        }

        return new JsonResource($links);
    }
}
