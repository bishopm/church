<?php namespace Bishopm\Church;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'Church Plugin Settings',
                'description' => 'Manage church plugin settings.',
                'category'    => 'Church',
                'icon'        => 'icon-cog',
                'class'       => 'Bishopm\Church\Models\Settings',
                'order'       => 500,
                'keywords'    => 'church',
                'permissions' => ['bishopm.church.manage']
            ]
        ];
    }

    public function registerPermissions()
    {
        return [
            'bishopm.church.manage' => [
                'label' => 'Manage church plugin',
                'tab' => 'Church'
            ],
            'bishopm.church.members.manage' => [
                'label' => 'Manage church membership',
                'tab' => 'Church'
            ],
            'bishopm.church.giving.manage' => [
                'label' => 'Manage individual giving',
                'tab' => 'Church'
            ],
            'bishopm.church.bookshop.manage' => [
                'label' => 'Manage bookshop',
                'tab' => 'Church'
            ],
            'bishopm.church.website.manage' => [
                'label' => 'Manage website',
                'tab' => 'Church'
            ],
            'bishopm.church.worship.manage' => [
                'label' => 'Manage worship',
                'tab' => 'Church'
            ],
            'bishopm.church.app.publish' => [
                'label' => 'Publish app devotional content',
                'tab' => 'Church'
            ],
            'bishopm.church.pastoralcare.manage' => [
                'label' => 'Manage pastoral care',
                'tab' => 'Church'
            ]
        ];
    }
}
