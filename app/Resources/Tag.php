<?php

namespace App\Resources;

use AngryMoustache\Rambo\Fields\HabtmField;
use AngryMoustache\Rambo\Fields\HasManyField;
use AngryMoustache\Rambo\Fields\IDField;
use AngryMoustache\Rambo\Fields\SelectField;
use AngryMoustache\Rambo\Fields\SlugField;
use AngryMoustache\Rambo\Fields\TextField;
use AngryMoustache\Rambo\Resource;

class Tag extends Resource
{
    public $displayName = 'name';

    public $paginate = 10000;

    // public $globalSearchWeight = 10;

    public $indexTableView = 'rambo::components.crud.tables.tags';

    public $defaultOrderDir = 'asc';
    public $defaultOrderCol = 'name';

    public $searchableFields = [
        'name',
        'slug',
    ];

    public function fields()
    {
        return [
            IDField::make(),

            TextField::make('name')
                ->sortable()
                ->rules('required')
                ->searchable(),

            SlugField::make('slug')
                ->hideFrom(['index'])
                ->sortable(),

            SelectField::make('parent_id')
                ->resource(self::class)
                ->label('Parent')
                ->nullable()
                ->sortable(),

            HabtmField::make('pulls')
                ->resource(Pull::class)
                ->hideFrom(['index']),

            HasManyField::make('children')
                ->resource(self::class)
                ->hideFrom(['index', 'edit', 'create']),
        ];
    }
}
