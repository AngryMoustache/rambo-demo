<?php

namespace App\Resources;

use AngryMoustache\Arnold\Fields\PageArchitectField;
use AngryMoustache\Rambo\Fields\BooleanField;
use AngryMoustache\Rambo\Fields\IDField;
use AngryMoustache\Rambo\Fields\SlugField;
use AngryMoustache\Rambo\Fields\TextField;
use AngryMoustache\Rambo\Fields\SelectField;
use AngryMoustache\Translatable\Rambo\TranslationResource;
use App\Models\PageTranslation as ModelsPageTranslation;

class PageTranslation extends TranslationResource
{
    public $model = ModelsPageTranslation::class;

    public $redirectResource = Page::class;
    public $redirectResourceId = 'page_id';

    public $displayName = 'name';

    public $pagination = 10;

    public function fields()
    {
        return [
            IDField::make(),

            SelectField::make('page_id')
                ->label('Page')
                ->resource(Page::class)
                ->hideFrom(['index', 'edit']),

            TextField::make('name')
                ->searchable()
                ->sortable()
                ->rules('required'),

            SlugField::make('slug')
                ->hideFrom(['index'])
                ->readonly(),

            PageArchitectField::make('body')
                ->hideFrom(['index']),

            BooleanField::make('online')
                ->sortable()
                ->toggleable(),
        ];
    }

    public function previewRoute()
    {
        return 'page.show';
    }

    // public function canEdit()
    // {
    //     // Only allowed to edit some locales
    //     return in_array($this->item->locale, ['nl']);
    // }

    // public function canShow()
    // {
    //     // Only allowed to view some locales
    //     return in_array($this->item->locale, ['nl']);
    // }
}
