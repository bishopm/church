<?php

namespace Bishopm\Church\Filament\Pages;
 
use Closure;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Bishopm\Church\Filament\Clusters\Settings as SettingsCluster;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Outerweb\FilamentSettings\Filament\Pages\Settings as BaseSettings;
use Bishopm\Church\Models\Group;
 
class Settings extends BaseSettings
{
    protected static ?string $cluster = SettingsCluster::class;

    public function schema(): array|Closure
    {
        return [
            Tabs::make('Settings')
                ->schema([
                    Tabs\Tab::make('General')
                        ->columns(2)
                        ->schema([
                            TextInput::make('general.church_name')->required(),
                            TextInput::make('general.church_abbreviation')->required(),
                            TextInput::make('general.site_logo'),
                            TextInput::make('general.physical_address'),
                            TextInput::make('general.app_version'),
                            TagsInput::make('general.services'),
                            Map::make('general.map_location')->label('Location')
                        ]),
                    Tabs\Tab::make('Admin')
                        ->columns(2)
                        ->schema([
                            KeyValue::make('admin.agendas')->columnSpanFull(),
                            Textarea::make('admin.bank_details')->columnSpanFull()->rows(4)
                        ]),
                    Tabs\Tab::make('Automation')
                        ->columns(2)
                        ->schema([
                            Select::make('automation.birthday_group')->label('Send birthday emails to this group')
                                ->options(Group::all()->sortBy('groupname')->pluck('groupname', 'id'))
                                ->searchable(),
                            Select::make('automation.maintenance_group')->label('Send maintenance emails to this group')
                                ->options(Group::all()->sortBy('groupname')->pluck('groupname', 'id'))
                                ->searchable()
                        ]),
                    Tabs\Tab::make('Email')
                        ->columns(2)
                        ->schema([
                            TextInput::make('email.church_email'),
                            TextInput::make('email.mailer'),
                            TextInput::make('email.mail_host'),
                            TextInput::make('email.mail_port'),
                            TextInput::make('email.mail_username'),
                            TextInput::make('email.mail_password')->password(),
                            TextInput::make('email.mail_encryption'),
                            TextInput::make('email.mail_from_address'),
                            TextInput::make('email.mail_from_name')
                        ]),
                    Tabs\Tab::make('Services')
                        ->columns(2)
                        ->schema([
                            TextInput::make('services.google_email_address'),
                            TextInput::make('services.google_api'),
                            TextInput::make('services.society_id')->label('methodist.church.net.za society ID'),
                            TextInput::make('services.bulksms_clientid')->label('BulkSMS Client ID'),
                            TextInput::make('services.bulksms_api_secret')->label('BulkSMS API Secret'),
                        ]),                    
                    Tabs\Tab::make('Website')
                        ->columns(2)
                        ->schema([
                            TextInput::make('website.church_telephone'),
                            TextInput::make('website.whatsapp'),
                            TextInput::make('website.youtube_channel'),
                            TextInput::make('website.youtube_channel_id'),
                            TextInput::make('website.facebook_page'),
                            TextInput::make('website.youversion_page'),
                            TextInput::make('website.instagram_account'),
                            TextInput::make('website.twitter_feed'),
                            TextInput::make('website.text_logo'),
                            TextInput::make('website.mapbox_token'),
                            Select::make('website.theme')
                                ->options([
                                    'cerulean'  =>  'Cerulean',
                                    'cosmo'     =>  'Cosmo',
                                    'cyborg'    =>  'Cyborg',
                                    'darkly'    =>  'Darkly',
                                    'flatly'    =>  'Flatly',
                                    'journal'   =>  'Journal',
                                    'litera'    =>  'Litera',
                                    'lumen'     =>  'Lumen',
                                    'lux'       =>  'Lux',
                                    'materia'   =>  'Materia',
                                    'minty'     =>  'Minty',
                                    'morph'     =>  'Morph',
                                    'pulse'     =>  'Pulse',
                                    'quartz'    =>  'Quartz',
                                    'sandstone' =>  'Sandstone',
                                    'simplex'   =>  'Simplex',
                                    'sketchy'   =>  'Sketchy',
                                    'slate'     =>  'Slate',
                                    'solar'     =>  'Solar',
                                    'spacelab'  =>  'Spacelab',
                                    'superhero' =>  'Superhero',
                                    'united'    =>  'United',
                                    'vapor'     =>  'Vapor',
                                    'yeti'      =>  'Yeti',
                                    'zephyr'    =>  'Zephyr'
                                ])
                        ]),
                    Tabs\Tab::make('Worship')
                        ->columns(2)
                        ->schema([
                            TextInput::make('worship.worship_email'),
                            KeyValue::make('worship.order_of_service')->columnSpanFull()                            
                        ]),
                ]),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Site settings';
    }
}