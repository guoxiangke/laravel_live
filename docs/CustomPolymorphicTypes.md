https://www.sitepoint.com/eloquents-polymorphic-relationships-explained/

morph-map	not work!?!?!
	https://github.com/laravel/ideas/issues/1899
	https://nicolaswidart.com/blog/laravel-52-morph-map
	https://josephsilber.com/posts/2018/07/02/eloquent-polymorphic-relations-morph-map
	https://laravel.com/docs/6.x/eloquent-relationships#custom-polymorphic-types

Custom Polymorphic Types
By default, Laravel will use the fully qualified class name to store the type of the related model. For instance, given the example above where an Upvote may belong to an Album or a Song, the default upvoteable_type would be either App\Album or App\Song, respectively.

However, there is one big flaw with this. What if the namespace of the Album model changes? We will have to make some sort of migration to rename all occurrences in the upvotes table. And that’s a bit crafty! Also what happens in the case of long namespaces (such as App\Models\Data\Topics\Something\SomethingElse)? That means we have to set a long max length on the column. And that is where the MorphMap method comes to our rescue.

The “morphMap” method will instruct Eloquent to use a custom name for each model instead of the class name:

use Illuminate\Database\Eloquent\Relations\Relation;

Relation::morphMap([
    'album' => \App\Album::class,
    'song' => \App\Song::class,
]);
We can register the morphMap in the boot function of our AppServiceProvider or create a separate service provider. For the new changes to take effect, we have to run the composer dump-autoload command. So now, we can add this new upvote record:

[
    "id" => 4,
    "upvoteable_type" => "album",
    "upvoteable_id" => 1
]
and it would behave in the exact same manner as the previous example does.