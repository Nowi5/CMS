<?php

namespace Grafite\Cms\Models;

use Grafite\Cms\Models\CmsModel;
use Illuminate\Support\Facades\Config;

// Load dynamically from config the right basis class
$cmsModel = Config::get('cms.models.file') ?? CmsModel::class;
if (! is_a($cmsModel, CmsModel::class, true)) {
    throw InvalidConfiguration::modelIsNotValid($cmsModel);
}
class_alias($cmsModel, 'Grafite\Cms\Models\CmsBaseFileModel');

class File extends CmsBaseFileModel
{
    public $table = 'files';

    public $primaryKey = 'id';

    protected $guarded = [];

    public static $rules = [
        'location' => 'required',
    ];

    protected $fillable = [
        'name',
        'location',
        'user',
        'tags',
        'details',
        'mime',
        'size',
        'is_published',
        'order',
    ];

    public function __construct(array $attributes = [])
    {
        $keys = array_keys(request()->except('_method', '_token'));
        $this->fillable(array_values(array_unique(array_merge($this->fillable, $keys))));
        parent::__construct($attributes);
    }
}
