<?php

namespace App\Resources;

use AngryMoustache\Rambo\Fields\AttachmentField;
use AngryMoustache\Rambo\Resource;
use AngryMoustache\Rambo\Fields\BooleanField;
use AngryMoustache\Rambo\Fields\DateTimeField;
use AngryMoustache\Rambo\Fields\HabtmField;
use AngryMoustache\Rambo\Fields\HasManyField;
use AngryMoustache\Rambo\Fields\IDField;
use AngryMoustache\Rambo\Fields\ManyAttachmentField;
use AngryMoustache\Rambo\Fields\SelectField;
use AngryMoustache\Rambo\Fields\SlugField;
use AngryMoustache\Rambo\Fields\TextField;
use App\Models\Pull as ModelsPull;
use App\Resources\Actions\OfflineAction;

class Pull extends Resource
{
    public $model = ModelsPull::class;

    public $displayName = 'name';

    // public $defaultOrderDir = 'asc';
    // public $defaultOrderCol = 'name';
    public $globalSearchBladeComponent = 'rambo::components.global-search.pull';

    public $pagination = 10;

    public function fields()
    {
        return [
            IDField::make(),

            TextField::make('name')
                ->searchable()
                ->sortable()
                ->rules('required'),

            SlugField::make('slug')
                ->hideFrom(['index'])
                ->readonly(),

            AttachmentField::make('attachment_id')
                ->label('Attachment')
                ->rules('required'),

            SelectField::make('pull_origin')
                ->resource(Pull::class)
                ->nullable()
                ->searchable()
                ->sortable()
                ->hideFrom(['index'])
                ->rules('required'),

            TextField::make('source')
                ->searchable()
                ->hideFrom(['index', 'edit'])
                ->rules('required'),

            HasManyField::make('tags')
                ->resource(Tag::class),

            HabtmField::make('tags')
                ->resource(Tag::class)
                ->hideFrom(['index', 'show']),

            DateTimeField::make('created_at')
                ->hideFrom(['create', 'edit'])
                ->format('d M Y')
                ->sortable(),

            DateTimeField::make('updated_at')
                ->hideFrom(['create', 'edit'])
                ->humanReadable()
                ->sortable(),

            DateTimeField::make('archived_at')
                ->hideFrom(['index', 'create']),

            BooleanField::make('online')
                ->sortable()
                ->toggleable(),
        ];
    }

    public function canDelete()
    {
        return ! $this->item->online;
    }

    // public function itemActions()
    // {
    //     return array_merge([
    //         OfflineAction::class,
    //     ], parent::itemActions());
    // }
}
