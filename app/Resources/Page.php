<?php

namespace App\Resources;

use AngryMoustache\Rambo\Fields\AttachmentField;
use AngryMoustache\Rambo\Resource;
use AngryMoustache\Rambo\Fields\IDField;
use AngryMoustache\Rambo\Fields\TextField;
use AngryMoustache\Translatable\Rambo\TranslationsField;
use App\Models\Page as ModelsPage;

class Page extends Resource
{
    public $model = ModelsPage::class;

    public $displayName = 'working_title';

    public function fields()
    {
        return [
            IDField::make(),

            TextField::make('working_title')
                ->searchable()
                ->sortable()
                ->rules('required'),

            AttachmentField::make('attachment_id')
                ->label('Header'),

            TranslationsField::make(PageTranslation::class),
        ];
    }
}
