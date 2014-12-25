# SUPPORT

## Introduction

Support consists of a series of useful classes to easy development and the use of best practices and design patterns with Laravel 5.

Part of this package is highly inspired by the [culttt.com](http://culttt.com/) blog, which I highly recommend to both new and experienced developers since it focuses on a wide range of aspects with always interesting point of views and discussions. I have personally learned much from it.

## Installation

Support can be installed through Composer, just include `"angioni/support": "dev-master"` to your composer.json.

## Module summary

Support consists of the following modules: Cache, Repositories, Semaphore and a Helpers class. In addition, Support comes with several new custom exceptions.

## Configuration

Support does not need any configuration to work. However, you may publish Support files through the artisan command `php artisan config:publish angioni/support`.

You can than edit the config.php file in your `app/config/packages/angioni/support` directory to customize the support behaviour.

## Repositories Usage

The abstract class `AbstractEloquentRepository` consists for a wrapper with a series of useful standard queries to be performed over the Laravel models.
This way implementing the repository pattern becomes straightforward.

As an example let's take a `Post` model. First of all we shall create a repository interface which will be injected in the constructor of the classes where we need the repo.
We then define the `PostRepositoryInterface` as

     <?php

     interface PostRepositoryInterface {}

We need now an implementation. The easiest way to create a Post repository is to define the following class

    <?php

    use TopGames\Support\Repos\AbstractEloquentRepository;
    use Post;

    class EloquentPostRepository extends AbstractEloquentRepository implements PostRepositoryInterface
    {
        protected $model;

        public function __construct(Post $model)
        {
            $this->model = $model;
        }
    }

Now we need to bind the implementation to the interface, which can be done by adding

    $this->app->bind(
        'PostRepositoryInterface',
        'EloquentPostRepository'
    );

to an existing Laravel Service Provider. Or we can create a new one

    <?php

    use Illuminate\Support\ServiceProvider;

    class RepositoryServiceProvider extends ServiceProvider {

        public function register()
        {
            $this->app->bind(
                'PostRepositoryInterface',
                'EloquentPostRepository'
            );
        }
    }

and add it to the config/app.php in the providers array

    'RepositoryServiceProvider',

Suppose that now we need the Post repo in our PostController. We simply inject our `PostRepositoryInterface` in the controller which gets resolved thanks to the Laravel IoC Container

    <?php

    use PostRepositoryInterface as PostRepo;

    class PostController extends BaseController {

        private $postRepo;

        function __construct(PostRepo $postRepo)
        {
            $this->postRepo = $postRepo;
        }

        public function show($idPost)
        {
            $post = $this->postRepo->find($idPost);

            // Use the retrieved post
        }
    }

The `AbstractEloquentRepository` empowers automatically our repositories of the following public methods:

- all()
- find($id, array $array)
- findOrFail($id, array $with = array())
- first()
- firstOrFail()
- firstBy(array $where = array(), array $with = array())
- firstOrFailBy(array $where = array(), array $with = array())
- getBy(array $where = array(), array $with = array())
- getByLimit($limit, array $where = array(), array $with = array())
- getByOrder($orderBy, array $where = array(), array $with = array(), $order = 'desc', $limit = 0)
- has($relation, array $where = array(), array $with = array(), $hasAtLeast = 1)
- hasFirst($relation, array $where = array(), array $with = array(), $hasAtLeast = 1)
- hasFirstOrFail($relation, array $where = array(), array $with = array(), $hasAtLeast = 1)
- getByPage($page = 1, $limit = 10, array $where = array(), $with = array())
- insert(array $collection)
- create(array $inputs)
- update(array $inputs)
- updateById($id, array $inputs)
- updateBy(array $where, array $inputs)
- updateOrCreateBy(array $where, array $inputs)
- destroy($id)
- destroyFirstBy(array $where)
- truncate()
- count()
- countBy(array $where = array())

The Repos module also supports xml repositories. Suppose we have a staff.xml file. We need to define a `StaffXMLRepositoryInterface`

    <?php

    interface StaffXMLRepositoryInterface {}

then we can create our xml repository as follows

    <?php

    use TopGames\Support\Repos\AbstractSimpleXMLRepository;

    class SimpleXMLStaffRepository extends AbstractSimpleXMLRepository implements StaffXMLRepositoryInterface
    {
        protected $autoload = false;

        protected $xmlPath = '/assets/xml/staff.xml';
    }

the $xmlPath property define the path to the xml file (base path is the /app folder) while the $autoload property defines if the xml file is automatically loaded when instantiating the class.
The `AbstractSimpleXMLRepository` contains the methods we need:

- getFilePath() : return the xml file path
- loadFile() : load the xml file for further use
- getFile() : load the xml file if not previously loaded and return it as an SimpleXMLElement instance

As done with "standard" repos, we need to instruct the IoC Container and we can achieve that by defining XMLRepositoryServiceProvider

    <?php

    use Illuminate\Support\ServiceProvider;

    class XMLRepositoryServiceProvider extends ServiceProvider {

        public function register()
        {
            $this->app->bind(
                'StaffXMLRepositoryInterface',
                'SimpleXMLStaffRepository'
             );
        }
    }

We can than inject the repo in the class we need or simply call it through the Laravel application instance / facade

    $xmlStaffRepo = App::make('StaffXMLRepositoryInterface');

### !!Warning!!

The `AbstractEloquentRepository` and `AbstractSimpleXMLRepository` classes do NOT provide any input validation!

## Cache Usage

The Cache module can be used to give Cache capabilities to our repos. We can then continue our previous example of a Post model and its repo.
We define a `CachePostRepoDecorator` as follows

    <?php

    use TopGames\Support\Cache\CacheInterface;
    use TopGames\Support\Cache\KeyManager;
    use TopGames\Support\Cache\AbstractCacheRepositoryDecorator;
    use TopGames\Support\Repos\RepositoryCacheableQueriesInterface;

    class CachePostRepoDecorator extends AbstractCacheRepositoryDecorator implements PostRepositoryInterface {

        /**
         * Section of the Cache the repo belongs to.
         *
         * @var string
         */
        protected $section = 'Forum';

        /**
         * Construct
         *
         * @param  RepositoryCacheableQueriesInterface $repo
         * @param  CacheInterface  $cache
         * @param  KeyManager      $keyManager
         */
        public function __construct(RepositoryCacheableQueriesInterface $repo, CacheInterface $cache, KeyManager $keyManager)
        {
            parent::__construct($repo, $cache, $keyManager);
        }

    }

The section property can be used to define a Cache section and it is used when generating the Cache keys.
This class implements the `PostRepositoryInterface`, so that it is recognized as the Post repo, of which it is nothing but a wrapper. It also extends the `AbstractCacheRepositoryDecorator` where all magic happens.
The AbstractCacheRepositoryDecorator implements the `RepositoryCacheableQueriesInterface`, which is a very basic interface which instructs our system to what repo methods are going to be cached.
Default methods are all(), find() and findOrFail(), but you can define your own interface and abstract cache decorator with more methods.

The `AbstractCacheRepositoryDecorator` constructor needs a repository implementing the `RepositoryCacheableQueriesInterface`, a Cache manager implementing the `CacheInterface` and a Key Manager implementing the `KeyManagerInterface`.
Laravel owns a very good Cache manager, so the package `LaravelCache` class can be used as Cache Manager.
This package comes with the `KeyManager` class as the default Key Manager. It supports a solid Cache key generator, but you can define your own.

In fact, all you need to use the Cache is to edit your RepositoryServiceProvider instructing the Laravel IoC Container to use the caching repo

    <?php

    use Illuminate\Support\ServiceProvider;
    use TopGames\Support\Cache\KeyManager;
    use TopGames\Support\Cache\LaravelCache;

    class RepositoryServiceProvider extends ServiceProvider {

        public function register()
        {
            $this->app->bind(
                'PostRepositoryInterface', function($app)
                {
                    $repo = $app->make('EloquentPostRepository');

                    return new CachePostRepoDecorator($repo, new LaravelCache($app['cache']), new KeyManager);
                }
            );
        }
    }

Now you can use the Post repo as before, but when calling the all(), find() or findOrFail() methods the result will be cached for 10 minutes.

### Hint

Want to manually delete a cache result?
In your Post model define a flush() method as follows

    public function flush()
    {
        Cache::tags(get_called_class().'id'.$this->{$this->primaryKey})->flush();
    }

You can then call it when editing or deleting a Post model you that your clients don't get outdated results.

The Cache module comes with xml cache handlers too. Let's take the staff.xml class we used before. All we need if to define the caching xml repo

    <?php

    namespace TopGames\Libraries\TopPlayer\CacheXML;

    use TopGames\Support\Cache\CacheInterface;
    use TopGames\Support\Cache\AbstractCacheSimpleXMLRepositoryDecorator;
    use TopGames\Support\Repos\XMLRepositoryInterface;
    use StaffXMLRepositoryInterface;

    class CacheSimpleXMLStaffRepoDecorator extends AbstractCacheSimpleXMLRepositoryDecorator implements StaffXMLRepositoryInterface {

        /**
         * Section of the Cache the repo belongs to.
         *
         * @var string
         */
        protected $section = 'Forum';

        /**
         * Construct
         *
         * @param XMLRepositoryInterface  $repo
         * @param CacheInterface          $cache
         */
        public function __construct(XMLRepositoryInterface $repo, CacheInterface $cache)
        {
            parent::__construct($repo, $cache);
        }
    }

and update the `XMLRepositoryServiceProvider`

    <?php

    use Illuminate\Support\ServiceProvider;
    use CacheSimpleXMLStaffRepoDecorator;
    use TopGames\Support\Cache\LaravelCache;

    class XMLRepositoryServiceProvider extends ServiceProvider {

        public function register()
        {
            $this->app->bind(
                'TopGames\Libraries\TopPlayer\XMLRepos\StaffXMLRepositoryInterface', function($app)
            {
                $repo = $app->make('TopGames\Libraries\TopPlayer\XMLRepos\SimpleXMLStaffRepository');

                return new CacheSimpleXMLStaffRepoDecorator($repo, new LaravelCache($app['cache'])
                );
            });
        }
    }

## Semaphores Usage

The semaphores module consists of a single class, the `SemaphoresManager`. Its constructor needs a Cache Manager and a Key Manager.
The Support package provides both of them, so we can bind them to the SemaphoresManager in a service provider as follow

    <?php

    namespace TopGames\Libraries\TopPlayer;

    use Illuminate\Support\ServiceProvider;
    use TopGames\Support\Cache\KeyManager;
    use TopGames\Support\Cache\LaravelCache;
    use TopGames\Support\Semaphores\SemaphoresManager;

    class SemaphoresServiceProviders extends ServiceProvider {

        $this->app->bind(
            'TopGames\Support\Semaphores\SemaphoresManager', function($app)
            {
                return new SemaphoresManager(new LaravelCache($app['cache']), new KeyManager);
            });
        }
    }

We can them simply inject the SemaphoresManager in a constructor to be resolver by the IoC Container and it is ready to use through the following methods:

- setLockingTime($minutes) : set the semaphores locking time in minutes
- lockSemaphore($id, $section) : lock a semaphore with input id belonging to input section
- unlockSemaphore($id, $section) : unlock the semaphore with input id belonging to input section
- checkIfSemaphoreIsLocked($id, $section) : check if the semaphore with input id belonging to input section is actually locked
- getSemaphoreKey($id, $section) : return the cache key used by the semaphore with input id belonging to input section is actually locked

## Helpers Usage

The helpers class provides several useful methods which simplify php development. Support has also an Helpers facade which can be registered in the app.php file under the aliases array as

    'Helpers' => 'TopGames\Support\Facades\Helpers'

The main available methods are:

- Helpers::isInt($int, $min = false, $max = false) : check if input $int is an integer. Examples:
int(4), string '4', float(4), 0x7FFFFFFF return true.
int(4.1), string '1.2', string '0x8', float(1.2) return false.
min and max allowed values can be inserted.
- Helpers::RandInArray(array $array) : return a random value out of an array
- checkDate($date, $format = 'Y-m-d') : check if input date is a valid date based of input format
- checkDatetime($datetime) : check if input datetime is a valid 'Y-m-d H:m:s' datetime
- splitDates($first_date, $second_date, $max_difference = 0) : split two 'Y-m-d'-format dates into an array of dates
- daysBetweenDates($date1, $date2) : return the number of days between the two input 'Y-m-d' or 'Y-m-d X' (X is some text) dates
- compareDates($date, $referenceDate) : compare $date with $referenceDate. Return true if $date is more recent, false otherwise (included if the two dates are identical)
- divideCollectionIntoGroups(Collection $collection, $groupsNumber = 2) : split a Collection into groups of equal numbers. $groupsNumber must be a multiplier of 2
- getTodayDay() : get today day (format("D"))
- getDate($offset = 0) : return today's day in format Y-m-d. Offset in days
- getTime($offset = 0) : return today's time in format H:i:s. Offset in minutes
- getRandomValueUrandom($min = 0, $max = 0x7FFFFFFF) : return a random value between input $min and $max values by using the MCRYPT_DEV_URANDOM source
- getUniqueRandomValues($min = 0, $max, $quantity = 1) : return $quantity UNIQUE random value between $min and $max

## Contribution guidelines

Pull requests are welcome. More unit tests are very welcome. Breaking changes won't be merged.

## License

Support is free software distributed under the terms of the MIT license.

## Contacts

Feel free to contact me.